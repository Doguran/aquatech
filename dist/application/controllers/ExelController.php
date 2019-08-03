<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.07.19
 * Time: 8:34
 */

class ExelController implements IController {

    public $exelPath = 'exel'; //директория для распаковки файла exel
    protected  $_mainCat = ""; //имя главной категории
    protected  $_subCat = ""; //имя субкатегории
    protected  $_catId = "";

    public function __construct(){
        if(!ADMIN)
            throw new Exception("Нет доступа");
    }
    public function indexAction() {
        $fc = FrontController::getInstance();
        $model = new FileModel();

        $message = '';
                    if (isset($_FILES['file']) AND $_FILES['file']['size']>0){
                        if(mime_content_type($_FILES['file']["tmp_name"]) == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                            $zip = new ZipArchive;
                            $res = $zip->open($_FILES['file']["tmp_name"]);

                            if ($res === TRUE) {
                                Helper::rmRec($this->exelPath); //удаляем старое содержимое папки
                                mkdir($this->exelPath); //создаем папку
                                $zip->extractTo($this->exelPath); //распаковываем в этупапку
                                $zip->close();

                                $message = 'Файл загружен и распакован успешно!';

                            } else {
                                $message = "Не удалось распаковать файл.";
                            }

                        }else{
                            $message = "Загружен неправильный тип файла.";
                        };
                    }

                    $XlsxparserController = new XlsxparserController();
                    $sheets = $XlsxparserController->parserXslxAllSheets();
                    $model->sheets = $sheets;
                    $model->selectSize = count($sheets);
                    $model->message = $message;



        $output = $model->render("exel.tpl.php");
        $fc->setBody($output);

    }


    public function insertAction() {
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            $XlsxparserController = new XlsxparserController();
            $sheet = $XlsxparserController->parserXslxAllSheets();
            foreach ($_POST['id'] AS $val){
                $id = Helper::clearData($val);
                list($rId,$imgDir) = explode("|", $id, 2);
                $this->_mainCat = $sheet[$rId];

                //удаляем все старые товары главной и дочерних категорий и получаем id главной или false если нет
                $AdmindetailModel = new AdmindetailModel();
                $cat_id = $AdmindetailModel->delAllProductInCat($sheet[$rId]);

                $AdmincatModel = new AdmincatModel();
                if(!$cat_id){//если категории нет - создаем ее

                    $cat_id = $AdmincatModel->addCat($sheet[$rId],0,0,null,null,$sheet[$rId],null,null,null);
                }
                $this->_catId = $cat_id;

                $sheets = $XlsxparserController->parserXslxSheet($rId,$imgDir);


                foreach($sheets AS $v){
                        $product = array_filter($v,'strlen' );
                        if($product){//проверка на пустоту
                            if(count($product) == 1){//создаем субкатегорию
                                $this->_subCat = $product[0];
                                $this->_catId = $AdmincatModel->addCat($product[0],$cat_id,$cat_id,null,null,$product[0],null,null,null);
                            }else{


                            Helper::print_arr($product);


                                /// распихиваем товары

                            }
                    }
                }

                $sheets = (array_filter($sheets));



            }



        }

    }




}