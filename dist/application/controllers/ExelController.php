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


    //функция удаляет рекурсивно все файлы и папки
//    private function _rmRec($path) {
//        if (is_file($path)) return unlink($path);
//        if (is_dir($path)) {
//            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
//                $this->_rmRec($path.DIRECTORY_SEPARATOR.$p);
//            if(!$path == $this->exelPath)
//                return rmdir($path);
//            else
//                return false;
//        }
//        return false;
//    }


    public function insertAction() {
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            $XlsxparserController = new XlsxparserController();
            $sheet = $XlsxparserController->parserXslxAllSheets();
            foreach ($_POST['id'] AS $val){
                $id = Helper::clearData($val);
                list($rId,$imgDir) = explode("|", $id, 2);
                $this->_mainCat = $sheet[$rId];

                $sheets = $XlsxparserController->parserXslxSheet($rId,$imgDir);
                //исключаем пустые массивы и пустые ячейки
                foreach($sheets AS $v){
                    $product = array_filter($v,'strlen' );
                    if(count($product) == 1){
                        $this->_subCat = $product[0];
                    }else{

                    }
                }

                $sheets = (array_filter($sheets));



            }



        }

    }




}