<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.07.19
 * Time: 8:34
 */

class ExelController implements IController {

    public $exelPath = 'exel'; //директория для распаковки файла exel

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
                                $this->_rmRec($this->exelPath); //удаляем старое содержимое папки
                                $zip->extractTo($this->exelPath);
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
    private function _rmRec($path) {
        if (is_file($path)) return unlink($path);
        if (is_dir($path)) {
            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                $this->_rmRec($path.DIRECTORY_SEPARATOR.$p);
            if(!$path == $this->exelPath)
                return rmdir($path);
            else
                return false;
        }
        return false;
    }


    public function insertAction() {
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            $XlsxparserController = new XlsxparserController();
            foreach ($_POST['id'] AS $val){
                $id = Helper::clearData($val);

                $sheets = $XlsxparserController->parserXslxSheet($id);
                Helper::print_arr($sheets);
            }



        }

    }




}