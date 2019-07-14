<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13.07.19
 * Time: 8:34
 */

class ExelController implements IController {
    public function __construct(){
        if(!ADMIN)
            throw new Exception("Нет доступа");
    }
    public function indexAction() {
        $fc = FrontController::getInstance();
        $model = new FileModel();
        if(isset($_FILES)){
                    if ($_FILES['file']['size']>0){
                        //var_dump($_FILES['file']);
                        if(mime_content_type($_FILES['file']["tmp_name"]) == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                            $zip = new ZipArchive;
                            $res = $zip->open($_FILES['file']["tmp_name"]);

                            if ($res === TRUE) {
                                $this->_rmRec('exel/'); //удаляем старое содержимое папки
                                $zip->extractTo('exel/');
                                $zip->close();

                                echo "ok";

                            } else {
                                $error[] = "Не удалось распаковать файл";
                            }

                        }else{
                            $error[] = "Загружен неправильный тип файла";
                        };
                    }

        var_dump($_FILES);
        echo mime_content_type($_FILES['file']["tmp_name"]);
        $output = $model->render("exel.tpl.php");
        $fc->setBody($output);
        }



    }


    //функция удаляет рекурсивно все файлы и папки
    private function _rmRec($path) {
        if (is_file($path)) return unlink($path);
        if (is_dir($path)) {
            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                $this->_rmRec($path.DIRECTORY_SEPARATOR.$p);
            return rmdir($path);
        }
        return false;
    }

    public function mimeAction(){

        header('Content-Type: text/html; charset=utf-8');
        $odir = opendir('exel');
        while (($file = readdir($odir)) !== FALSE)
        {
            if ($file != '.' && $file != '..')
            {
                //echo $file.'<br>';
                echo mime_content_type('exel/'.$file);
            }
        }
        closedir($odir);



    }

}