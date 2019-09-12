<?php
class TxtController implements IController {
    
	public function indexAction() {
		throw new Exception("Нет запроса"); 
    }
    
    public function contactAction() {

        $fc = FrontController::getInstance();


        $model = new FileModel();
        $model->page = "contact";

        $model->h1 = "Контакты";
        $model->title = "Контакты";


        $output = $model->render("contact.tpl.php");
        $fc->setBody($output);
        
	}
    
    public function priceAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt =  $TextModel->getText("price");
        $model->text = $txt["price"];
        $model->title = "Прайс-листы";
        $model->column = "price";
		$model->cartAll = CartController::countCart();
        $model->contact = $TextModel->getContact();
        
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function deliveryAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		

        $TextModel = new TextModel();
        $txt =  $TextModel->getText("delivery");
        $model->text = $txt["delivery"];
        $model->h1 = "Оплата и доставка";
        $model->column = "delivery";

		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function catalogAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt =  $TextModel->getText("catalog");
        $model->text = $txt["catalog"];
        $model->title = "Каталог";
        $model->column = "catalog";
		$model->cartAll = CartController::countCart();
        $model->contact = $TextModel->getContact();
        
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function newsAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt =  $TextModel->getText("news");
        $model->text = $txt["news"];
        $model->title = "Новости";
        $model->column = "news";
		$model->cartAll = CartController::countCart();
        $model->contact = $TextModel->getContact();
        
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function detalirovkaAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt =  $TextModel->getText("detalirovka");
        $model->text = $txt["detalirovka"];
        $model->title = "Деталировка";
        $model->column = "detalirovka";
		$model->cartAll = CartController::countCart();
        $model->contact = $TextModel->getContact();
        
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function instructionsAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt =  $TextModel->getText("instructions");
        $model->text = $txt["instructions"];
        $model->title = "Инструкции";
        $model->column = "instructions";
		$model->cartAll = CartController::countCart();
        $model->contact = $TextModel->getContact();
        
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}
    
    public function aboutAction() {
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->categoriesForIndex = $catModel->getCatListForIndex();
        
        /*$ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();*/
        
        $TextModel = new TextModel();
        $txt = $TextModel->getText("about");
        $model->text = $txt["about"];
        $model->title = "О магазине";
        $model->column = "about";
		$model->cartAll = CartController::countCart();
		$output = $model->render(TXT_FILE);
		$fc->setBody($output);
        
	}

    public function sendcontactAction() {

        // Если  запрос не AJAX (XMLHttpRequest), то завершить работу
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
            exit();
        }

        $email = Helper::clearData($_POST["email"], 'email');
        $text  = Helper::clearData($_POST["text"]);
        $name  = Helper::clearData($_POST["name"]);
        $phone = Helper::clearData($_POST["phone"]);
        $url   = Helper::clearData($_POST["url"]);
        $action = isset($_POST["action"]) ? $_POST["action"] : "";
        //$name = empty($name) ? "не указано" : $name;
        $phone = empty($phone) ? "не указан" : $phone;
        $resData = array();
        if(empty($email) || empty($text) || empty($name) || $action != "send" || $url != ""){
            $resData["success"] = 0;
            $resData["msg"] = 'Извините, произошла ошибка';
            echo json_encode($resData);
        }else{
            include 'Mail.php';
            include 'Mail/mime.php' ;
            $mailParameter = array(
              'head_charset' => "utf-8",
              'text_charset' => "utf-8",
              'html_charset' => "utf-8",
              'eol' => "\n", //разделитель
              'delay_file_io' => true //вставлять изображения сразу в тело письма
            );
            $mime = new Mail_mime($mailParameter);
            $textVersion = "
                    Пользователь отправил сообщение с сайта ".$_SERVER['HTTP_HOST']."
                    Имя:  $name 
                    E-mail: $email
                    Телефон:  $phone 
                    Сообщение: $text
                    ";
            $htmlVersion = "
                    Пользователь отправил сообщение с сайта ".$_SERVER['HTTP_HOST'].". <br /><br />
                    <strong>Имя:</strong> $name<br />
                    <strong>E-mail:</strong> $email<br />
                    <strong>Телефон:</strong> $phone<br />
                    <strong>Сообщение:</strong> $text
                    ";
            $host = Helper::getHost();
            $hdrs = array(
              'From'    => "Интернет-магазин $host <".SMTP_USERNAME.">",
              'Subject' => "Пользователь отправил сообщение с сайта ".$_SERVER['HTTP_HOST']
            );

            $mime->setTXTBody($textVersion);
            $mime->setHTMLBody($htmlVersion);

            $body = $mime->get();
            $hdrs = $mime->headers($hdrs);

            $admin_email = Helper::getAdminMail();

            $mail =& Mail::factory('smtp', array('host' => SMTP_HOST, 'debug' => false, 'pipelining' => false, 'port' => SMTP_PORT, 'auth' => true, 'username' => SMTP_USERNAME, 'password' => SMTP_PASSWORD));
            //$mail =& Mail::factory('mail');
            $mail->send($admin_email, $hdrs, $body);

            if (PEAR::isError($mail)) {
                $resData["success"] = 0;
                $resData["msg"] = "<p>Произошла ошибка</p>";

            } else {
                $resData["success"] = 1;
                $resData["msg"] = "Спасибо, Ваше сообщение отправлено. Мы ответим в ближайшее время.</p>";
            }

            echo json_encode($resData);

        }


    }

    public function phonemodalAction() {

        if($_SERVER["REQUEST_METHOD"]=='POST'){

            $phone  = Helper::clearData($_POST['phone']);
            $name   = Helper::clearData($_POST['name']);
            $action = Helper::clearData($_POST['action']);
            $url    = Helper::clearData($_POST['url']);
            if(empty($phone) || empty($name) || $action != "send" || $url != ""){
                $resData["success"] = 0;
                $resData["msg"] = 'Извините, произошла ошибка';
                echo json_encode($resData);
            }else{
                //мылим админу
                include 'Mail.php';
                include 'Mail/mime.php' ;
                $mailParameter = array(
                  'head_charset' => "utf-8",
                  'text_charset' => "utf-8",
                  'html_charset' => "utf-8",
                  'eol' => "\n", //разделитель
                  'delay_file_io' => true //вставлять изображения сразу в тело письма
                );
                $mime = new Mail_mime($mailParameter);
                $textVersion = "
                    Пользователь $name заказал обратный звонок с сайта ".$_SERVER['HTTP_HOST']."
                    Имя:  $name 
                    Телефон:  $phone 
                    ";
                $htmlVersion = "
                    Пользователь $name заказал обратный звонок с сайта ".$_SERVER['HTTP_HOST']."<br /><br />
                    <strong>Имя:</strong> $name<br />
                    <strong>Телефон:</strong> $phone<br />
                    ";
                $host = Helper::getHost();
                $hdrs = array(
                  'From'    => "Интернет-магазин $host <".SMTP_USERNAME.">",
                  'Subject' => "Заказ обратного звонка ".$_SERVER['HTTP_HOST']
                );

                $mime->setTXTBody($textVersion);
                $mime->setHTMLBody($htmlVersion);

                $body = $mime->get();
                $hdrs = $mime->headers($hdrs);

                $admin_email = Helper::getAdminMail();

                $mail =& Mail::factory('smtp', array('host' => SMTP_HOST, 'debug' => false, 'pipelining' => false, 'port' => SMTP_PORT, 'auth' => true, 'username' => SMTP_USERNAME, 'password' => SMTP_PASSWORD));
                //$mail =& Mail::factory('mail');
                $mail->send($admin_email, $hdrs, $body);

                if (PEAR::isError($mail)) {
                    $resData["success"] = 0;
                    $resData["msg"] = "<p>Произошла ошибка</p>";

                } else {
                    $resData["success"] = 1;
                    $resData["msg"] = "<p>Спасибо. Мы позвоним в ближайшее время.</p>";
                }


                echo json_encode($resData);
            }



        }else{

            // Если  запрос не AJAX (XMLHttpRequest), то завершить работу
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
                exit();
            }
            $fc = FrontController::getInstance();
            $model = new FileModel();

            //выводим все
            $output['body'] = $model->render("phone-form.tpl.php");
            $fc->setBody(json_encode($output));



        }



    }




}
