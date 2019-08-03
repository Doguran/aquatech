<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.07.19
 * Time: 8:34
 */

class ExelController implements IController {

    public $exelPath = 'exel'; //директория для распаковки файла exel
    protected  $_mainCat = "";
    protected  $_subCat = "";

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
                //удаляем все старые товары главной категории
                $AdmindetailModel = new AdmindetailModel();
                $AdmindetailModel->delAllProductInCat($sheet[$rId]);

                exit;

                $sheets = $XlsxparserController->parserXslxSheet($rId,$imgDir);


                foreach($sheets AS $v){
                        $product = array_filter($v,'strlen' );
                        if($product){//проверка на пустоту
                            if(count($product) == 1){
                                $this->_subCat = $product[0];
                            }else{
                            Helper::print_arr($product);

                            }
                    }
                }

                $sheets = (array_filter($sheets));



            }



        }

    }




}