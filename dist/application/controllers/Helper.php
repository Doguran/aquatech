<?php

/**
 * @author Doguran
 * @copyright 2013
 */

class Helper {
    
    
    
    //распечатка массива
    public static function print_arr($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }
    
    
    
    //фильтрация данных
    public static function clearData($data, $type="s"){
            switch($type){
            case "s": $data = trim(htmlspecialchars($data, ENT_QUOTES,"UTF-8")); break;
            case "i": $data = abs((int)$data); break;
            case "html": $data = trim($data); break;
            case "date": $data = (preg_match("/\d\d\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])/",$data)) ? $data : date("Y-m-d"); break;
            case "email": $data = Helper::email($data) ? $data : ""; break;
            case "json": $data = Helper::isJSON($data) ? $data : ""; break;
        }
        return $data;
    }

    ///////Проверить строку на JSON
    public static function isJSON($string) {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }
    
    //генерация пароля
    public static function generatePass(){
            
        $pass = substr(base64_encode(md5(microtime())), 1, 12);  
        $pass = mb_strtoupper(wordwrap($pass, 4, "-", true));
        return $pass;
    }
    
    
    //шифрование пароля 
    public static function cryptoPass($pass){
        
        return md5(sha1($pass));
    }
    
    
    /**
	 * Check an email address for correct format.
	 *
	 * @param   string  $email  email address
	 * @return  boolean
	 */
	public static function email($email){
	   
		if (mb_strlen($email, 'UTF-8') > 254)  
		{
			return FALSE;
		}

		$expression = '/^[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})$/iD';
		return (bool) preg_match($expression, (string) $email);
	}
    
    //проверка на админа
    public static function checkAdmin(){
        
        return (isset($_SESSION['user']['status']) and $_SESSION['user']['status'] == "admin")? true : false;
    }
    
############################################################
//функция генерирует случайные знаки
###########################################################
public static function generateString($length = 8)
{
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);

}

    #########################################################################
    //функция загружает изображения по url из интернета, возвращает имя файла
    #########################################################################
    public static function sineta($url, $uploaddir = 'photo/')
    {


        $parent = "/(jpg|jpeg|gif|png|pjpeg|bmp)$/i";
        $result = preg_match($parent, $url, $ext);
        if ($result != 0) {
            $newname = self::generateString() . "." . $ext[1];
            $result_copy = copy($url, $uploaddir . $newname);
            if ($result_copy) {
                $info = getimagesize($uploaddir . $newname); //берем информацию о файле
                if (preg_match('{image/(.*)}is', $info['mime'])) { //убеждаемся что это изображение
                    return $newname;
                } else {
                    unlink($uploaddir . $newname);
                    return false;
                }
            }

        } else {
            return false;

        }


    }

###########################################################################
//функция загрузки изображений из формы, возвращает имя файла
###########################################################################
public static function uploadimg($uploaddir = 'photo/')
{

    if ($_FILES['photo']['error'] == 0 //если ошибок нет
        && $_FILES["photo"]["size"] < 1024 * 12 * 1024) { //и если не больше 12 мегабайт
        if ($_FILES['photo']['type'] == 'image/png' || $_FILES['photo']['type'] ==
            'image/jpg' || $_FILES['photo']['type'] == 'image/gif' || $_FILES['photo']['type'] ==
            'image/jpeg' || $_FILES['photo']['type'] == 'image/pjpeg') {

            $info = getimagesize($_FILES['photo']['tmp_name']); //берем информацию о файле
            if (preg_match('{image/(.*)}is', $info['mime'], $extension)) { //убеждаемся что файл есть ни что иное как изображение и заносим расширение файла в $extension[1]
                //$newname = $this->generateString() . "." . $extension[1];
                $newname = substr(md5(date('YmdHis')), 0, 16) . "." . $extension[1];
                $result = move_uploaded_file($_FILES['photo']['tmp_name'], $uploaddir . $newname);
                if ($result && file_exists($uploaddir . $newname)) {
                    return $newname;
                } else {
                    return false;
                }

            } else {
                return false;
            }

        } else {
            return false;
        }

    } else {
        return false;
    }

}

##########################################################################3
//функция делает ресайз изображений и записывает их в папку
##########################################################################
public static function create_small_copy($file_name, $width, $height, $category_path = 'images/', $teg = '' )
{

    $quallity = 100;

    $filename = $category_path . $file_name;
    $filename_small = $category_path . $teg . $file_name;


    list($width_orig, $height_orig) = getimagesize($filename);

    if ($width_orig > $width || $height_orig > $height) {
        $ratio_orig = $width_orig / $height_orig;

        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }

        $image_p = imagecreatetruecolor($width, $height);

        if (preg_match("/.jpg$|.JPG$|.JPEG$|.jpeg$/", $filename)) {
            $image = imagecreatefromJpeg($filename);
            $type = 'jpeg';
        } elseif (preg_match("/.png$|.PNG$/", $filename)) {
            $image = imagecreatefrompng($filename);
            $type = 'png';
        } elseif (preg_match("/.gif$|.GIF$/", $filename)) {
            $image = imagecreatefromgif($filename);
            $type = 'gif';
        }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        switch ($type) {

            case 'jpeg':
                imagejpeg($image_p, $filename_small, $quallity);
                break;

            case 'gif':
                imagegif($image_p, $filename_small, $quallity);
                break;

            case 'png':
                imagepng($image_p, $filename_small);
                break;
        }
        unset($image_p);
        unset($image);
    } else {
        copy($filename, $filename_small);
    }
    if (file_exists($filename_small))
        return true;
    else
        return false;
}

    
 public static function getAdminMail(){
    //$TextModel = new TextModel();
    //return $TextModel->getAdminMail();
     return $_SESSION["contact"]["email"];
 }
 
 
 //разбивает строку на две равные части, не обрезая слова
 public static function wordSafeBreak($str) {
    for($middle = intval(strlen($str)/2); $middle >= 0 && $str[$middle] !== ' '; $middle--){
    if ($middle < 0)
        return array('', $str);
    }
    return array(substr($str, 0, $middle), substr($str, $middle+1));
}
 
 
 //извлекает ХОСТ из HTTP_PATH
  public static function getHost(){
    $url = parse_url(HTTP_PATH);
    return $url["host"];
 }
 
 
 public static function getChpu ($str)
		{
		$converter = array(
	        'а' => 'a',   'б' => 'b',   'в' => 'v',
	        'г' => 'g',   'д' => 'd',   'е' => 'e',
	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
	        'и' => 'i',   'й' => 'y',   'к' => 'k',
	        'л' => 'l',   'м' => 'm',   'н' => 'n',
	        'о' => 'o',   'п' => 'p',   'р' => 'r',
	        'с' => 's',   'т' => 't',   'у' => 'u',
	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
	        'ь' => '',  'ы' => 'y',   'ъ' => '',
	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
 
	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
	        'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		$str = strtr($str, $converter);
		$str = strtolower($str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		$str = trim($str, "-");
		return $str;
		}



        ############################################################# Преобразует формат Quill (Delta) в HTML ##########################
        public static function quillToHtml($answer){
            $formattedAnswer = '';
            $answer = json_decode($answer,true);

//            self::print_arr($answer);
//            exit;

            foreach($answer['ops'] as $key=>$element){
                if(empty($element['insert']['image'])){
                    $result = $element['insert'];
                    if(!empty($element['attributes'])){
                        foreach($element['attributes'] as $key=>$attribute){
                            $result = self::operate($result,$key,$attribute);
                        }
                    }
                }else{
                    $image = $element['insert']['image'];
                    // if you are getting the image as url
                    if(strpos($image,'http://') !== false || strpos($image,'https://') !== false){
                        $result = "<img src='".$image."' />";
                    }else{
                        //if the image is uploaded
                        //saving the image somewhere and replacing it with its url
//                        $imageUrl = getImageUrl($image);
//                        $result = "<img src='".$imageUrl."' />";
                        $result = "<img src='".$image."' />";//временная строка
                    }
                }
                $formattedAnswer = $formattedAnswer.$result;
            }
            return nl2br($formattedAnswer);
        }
    private static function operate($text,$ops,$attribute){
        $operatedText = null;
        switch($ops){
            case 'bold':
                $operatedText = '<strong>'.$text.'</strong>';
                break;
            case 'italic':
                $operatedText = '<i>'.$text.'</i>';
                break;
            case 'strike':
                $operatedText = '<s>'.$text.'</s>';
                break;
            case 'underline':
                $operatedText = '<u>'.$text.'</u>';
                break;
            case 'link':
                $operatedText = '<a href="'.$attribute.'" target="_blank">'.$text.'</a>';
                break;
            default:
                $operatedText = $text;
        }
        return $operatedText;
    }


    //погготавливает строку с номером телефона для ссылки
    public static function telLink($str) {
        $str = trim($str);
        if ($str[0]==8){
            $str = "+7".substr($str,1);
        }
        return preg_replace('/[^+0-9]/', '', $str);

    }


    #########################################################################
    /**
     * создает массив из латинских букв определенной длины
     *
     * @param int $length
     *
     * @return array
     */
     public static function createLetterRange($length) {
        $range   = [];
        $letters = range('A', 'Z');
        for ($i = 0; $i < $length; $i++) {
            $position = $i * 26;
            foreach ($letters as $ii => $letter) {
                $position++;
                if ($position <= $length) {
                    $range[] = ($position > 26 ? $range[$i - 1] : '') . $letter;
                }
            }
        }
        return $range;
    }


    ###########################################################################
    //функция загрузки изображений из формы, возвращает имя файла
    ###########################################################################
    public static function uploadXlsx($uploaddir = 'xlsx/')
    {

        if ($_FILES['file']['error'] == 0) {
            if ($_FILES['photo']['type'] == 'image/png' || $_FILES['photo']['type'] ==
                                                           'image/jpg' || $_FILES['photo']['type'] == 'image/gif' || $_FILES['photo']['type'] ==
                                                                                                                     'image/jpeg' || $_FILES['photo']['type'] == 'image/pjpeg') {

                $info = getimagesize($_FILES['photo']['tmp_name']); //берем информацию о файле
                if (preg_match('{image/(.*)}is', $info['mime'], $extension)) { //убеждаемся что файл есть ни что иное как изображение и заносим расширение файла в $extension[1]
                    //$newname = $this->generateString() . "." . $extension[1];
                    $newname = substr(md5(date('YmdHis')), 0, 16) . "." . $extension[1];
                    $result = move_uploaded_file($_FILES['photo']['tmp_name'], $uploaddir . $newname);
                    if ($result && file_exists($uploaddir . $newname)) {
                        return $newname;
                    } else {
                        return false;
                    }

                } else {
                    return false;
                }

            } else {
                return false;
            }

        } else {
            return false;
        }

    }




    ####################################### функция рекурсивного удаления файлов и папок
    public static function rmRec($path) {
        if (is_file($path)) return unlink($path);
        if (is_dir($path)) {
            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                self::rmRec($path.DIRECTORY_SEPARATOR.$p);
            return rmdir($path);
        }
        return false;
    }
    
}

