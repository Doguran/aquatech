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
    protected  $_log = "";

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
        $fc = FrontController::getInstance();
        $model = new FileModel();
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
                if($cat_id){
                    $this->_log .= "<br><br>Категория ".$sheet[$rId]."<br>";
                }

                $AdmincatModel = new AdmincatModel();
                if(!$cat_id){//если категории нет - создаем ее

                    $cat_id = $AdmincatModel->addCat($sheet[$rId],0,0,null,null,$sheet[$rId],null,null,null);
                    if($cat_id){
                        $this->_log .= "<br><br>Создана категория ".$sheet[$rId]."<br>";
                    }
                }
                $this->_catId = $cat_id;

                $sheets = $XlsxparserController->parserXslxSheet($rId,$imgDir);


                foreach($sheets AS $v){
                        $product = array_filter($v,'strlen' );
                        if($product){//проверка на пустоту
                            if(count($product) == 1){//создаем субкатегорию

                                $this->_subCat =  array_shift($product);
                                $this->_catId = $AdmincatModel->addCat($this->_subCat,$cat_id,$cat_id,null,null,$this->_subCat,null,null,null);
                                if($this->_catId){
                                    $this->_log .= "&nbsp;&nbsp;Создана подкатегория ".$this->_subCat."<br>";
                                }
                            }else{

//                                header('Content-Type: text/html; charset=utf-8');
//                            Helper::print_arr($product);

                                /// распихиваем товары
                                /// [0] артикул
                                /// [1} название
                                /// [2] описание
                                /// [3] фото
                                /// [6] цена в евро (рекомендованная)
                                $product[0] = isset($product[0]) ? $product[0] : null;
                                $product[1] = isset($product[1]) ? $product[1] : null;
                                $product[2] = isset($product[2]) ? $product[2] : null;
                                $product[3] = isset($product[3]) ? $product[3] : null;
                                $product[6] = isset($product[6]) ? $product[6] : null;

                                $product_id = $AdmindetailModel->addProductOfExel($product[1],$product[0],$product[6],$product[2],$product[3],$product[1]);
                                if(!is_array($product_id)){
                                    $this->_log .= "&nbsp;&nbsp;&nbsp;&nbsp;Добавлен товар ".$product[1]."<br>";
                                    $AdmindetailModel->insertProductCategory($product_id,$this->_catId);
                                }else{
                                    $this->_log .= "&nbsp;&nbsp;&nbsp;&nbsp;ОШИБКА ДОБАВЛЕНИЯ ТОВАРА: арт. ".$product[0]." - ".$product[1]." - ".$product_id["msg"]."<br>";
                                }

                            }
                        }
                }

            }
        }
        $model->log = $this->_log;
        $output = $model->render("exel-log.tpl.php");
        $fc->setBody($output);

    }




}