<?php

define("DB_CONN","mysql:host=localhost;dbname=aquatehnica;charset=utf8");
define("DB_USER","root");
define("DB_PASS","");

//define("DB_CONN","mysql:host=91.224.23.222;dbname=aquatecnica;charset=utf8");
//define("DB_USER","aquatecnica");
//define("DB_PASS","N1f5N8q9");

//header('Content-Type: text/html; charset=utf-8');

//устанавливаем курсы валют
$valuta_arr = file("valuta.data",FILE_IGNORE_NEW_LINES);
define("DOLLAR",$valuta_arr[0]);
define("EVRO",$valuta_arr[1]);

define("COURIER_PRISE",500);// стоимость доставки курьером

define("ADMIN_EMAIL",'portotecnica1@gmail.com');// на какую почту админу слать письма из корзины

define('SMTP_HOST', 'mail.supermoika.ru');
define('SMTP_PORT', '25');
define('SMTP_USERNAME', 'no-reply@supermoika.ru');
define('SMTP_PASSWORD', '0M5x9B9z');



define('TEMPLATE', 'default');

define('HTTP_PATH', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('TEMPLATE_PATH', 'http://'.$_SERVER['HTTP_HOST'].'/application/views/'.TEMPLATE.'/');

define('DOCROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

$controllers = 'application/controllers';
$models = 'application/models';
$views = 'application/views/'.TEMPLATE;
 
define('CONTROLLERSPATH', realpath(DOCROOT . $controllers) . DIRECTORY_SEPARATOR);
define('MODELSPATH', realpath(DOCROOT . $models) . DIRECTORY_SEPARATOR);
define('VIEWSPATH', realpath(DOCROOT . $views) . DIRECTORY_SEPARATOR);


/* Пути по-умолчанию для поиска файлов */
set_include_path(get_include_path()
					.PATH_SEPARATOR.$controllers
					.PATH_SEPARATOR.$models
					.PATH_SEPARATOR.$views);
					
unset($controllers, $models, $views);					

/* Имена файлов: views */
define('DEFAULT_FILE', 'index.tpl.php');
define('DETAIL_FILE', 'detail.tpl.php');
define('LINE_FILE', 'line.tpl.php');
define('CAT_PAGE', 'cat.tpl.php');
define('TXT_FILE', 'txt.tpl.php');
define('BASKET_FILE', 'cart.tpl.php');
define('TABLE_PRODUCT', 'table-view.tpl.php');
define('ASK', 'ask.tpl.php');

/* Автозагрузчик классов */

function __autoload($class){
	//@include_once($class.'.php');
  
	$path = array(CONTROLLERSPATH.$class.'.php',
				  MODELSPATH.$class.'.php',
 				 VIEWSPATH.$class.'.php');
	
	$found = false;
      foreach ($path as $file) {
        if (is_file($file)) {
          $found = true;
          break;
        }
      }
	 if($found)
		require_once($class.'.php');
        
        
		
}




/* Инициализация и запуск FrontController */
try{
    
    //стартуем сессию
    session_start();
    if(!isset($_SESSION["cart"])){
        $_SESSION["cart"] = array();
    }

    if(!isset($_SESSION["contact"])){
        $_SESSION["contact"] = TextModel::getStaticContact();
    }

    
    if(Helper::checkAdmin())
        define('ADMIN', true);
    else
        define('ADMIN', false);

    $cartAll = CartController::countCart();
    define('QUANTITY', $cartAll["quantityAll"]);
    define('COST', $cartAll["priceAll"]);
    unset($cartAll);




    //Helper::print_arr($_SESSION["compare"]);
    $front = FrontController::getInstance();
    $front->route();
    
    
    
}catch (Exception $e) {
    
   //в продакшен этот абзац закоментить, а следующий раскоментить
    header("HTTP/1.0 404 Not Found");
    header("Content-Type: text/html; charset=utf-8");
    echo 'Выброшено исключение: ',  $e->getMessage(),"\n";
    
//    $rc = new ReflectionClass("Error404Controller");
//	$controller = $rc->newInstance();
//	$method = $rc->getMethod("indexAction");
//	$method->invoke($controller);
	
}


/* Вывод данных */
echo $front->getBody();
