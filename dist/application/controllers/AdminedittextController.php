<?php
class AdminedittextController implements IController {
    
   	public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        
    }
    
    public function indexAction() {
		throw new Exception("Нет запроса"); 
    }
    
	public function contacttextAction() {
	   
       if($_SERVER["REQUEST_METHOD"]=='POST'){
        
        //инициализация пришедших переменных
            $phone1   = Helper::clearData($_POST['phone1']);
            $phone2   = Helper::clearData($_POST['phone2']);
            //$phone3   = Helper::clearData($_POST['phone3']);
            $address = Helper::clearData($_POST['address']);
            $email   = Helper::clearData($_POST['email'],"email");
            $mode    = Helper::clearData($_POST['mode']);
            //$footer  = Helper::clearData($_POST['footer']);
            $maps  = Helper::clearData($_POST['maps']);

            
            $error = array();
            if(!$phone1 and !$phone2) $error[] = "Не указаны телефоны";
            if(!$address) $error[] = "Не указан адрес";
            if(!$email) $error[] = "Неверный email";
            if(!$mode) $error[] = "Не указан режим работы";
            //if(!$footer) $error[] = "Не указан текст в футере";
            if(!$maps) $error[] = "Не указаны координаты";
            
            if(empty($error)){
                $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editContact ($phone1,$phone2,$address,$email,$mode,$maps);
               if($res) {
                   $_SESSION["contact"] = TextModel::getStaticContact();
                   header("Location: /");
                }else{
                   header("Location: /adminedittext/contacttext/?er=".urlencode("Ошибка обновления"));
                }
            }else{
                $str = implode("<br />", $error);
                header("Location: adminedittext/contacttext/?er=".urlencode($str));
            }
            
        
       }else{
        $fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		

        $TextModel = new TextModel();

        
        $model->contact = $TextModel->getContact();

		$output = $model->render("admincontacttext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    public function priceAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text,"price"); 
               if($res) header("Location: /txt/price/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/price/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		/*$catModel = new CatModel();
        $model->categories = $catModel->getCategories();*/
        
        $TextModel = new TextModel();
        $ContactText = $TextModel->getText("price");
        $model->text = $ContactText["price"];
        $model->title = "Прайс-листы";
        $model->url = "price";
        $model->cartAll = CartController::countCart();
        
        $model->contact = $TextModel->getContact();
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    public function deliveryAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
//            $text = QuillConvert::toHtml($_POST['text']);
            $text    = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "delivery");
               if($res){
                    $this->ok_msg();
               }else{
                   $this->not_ok_msg("Произошла ошибка");
               }

        }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("delivery");
        $model->text = $ContactText["delivery"];
        $model->title = "Оплата и доставка";
        $model->url = "delivery";

		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    
    public function instructionsAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "instructions"); 
               if($res) header("Location: /txt/instructions/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/instructions/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		/*$catModel = new CatModel();
        $model->categories = $catModel->getCategories();*/
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("instructions");
        $model->text = $ContactText["instructions"];
        $model->title = "Инструкции";
        $model->url = "instructions";
        $model->cartAll = CartController::countCart();
        
        $model->contact = $TextModel->getContact();
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    public function detalirovkaAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "detalirovka"); 
               if($res) header("Location: /txt/detalirovka/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/detalirovka/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		/*$catModel = new CatModel();
        $model->categories = $catModel->getCategories();*/
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("detalirovka");
        $model->text = $ContactText["detalirovka"];
        $model->title = "Деталировка";
        $model->url = "detalirovka";
        $model->cartAll = CartController::countCart();
        
        $model->contact = $TextModel->getContact();
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    
    public function newsAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "news"); 
               if($res) header("Location: /txt/news/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/news/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		/*$catModel = new CatModel();
        $model->categories = $catModel->getCategories();*/
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("news");
        $model->text = $ContactText["news"];
        $model->title = "Новости";
        $model->url = "news";
        $model->cartAll = CartController::countCart();
        
        $model->contact = $TextModel->getContact();
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    
    public function catalogAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "catalog"); 
               if($res) header("Location: /txt/catalog/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/catalog/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		/*$catModel = new CatModel();
        $model->categories = $catModel->getCategories();*/
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("catalog");
        $model->text = $ContactText["catalog"];
        $model->title = "Каталог";
        $model->url = "catalog";
        $model->cartAll = CartController::countCart();
        
        $model->contact = $TextModel->getContact();
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}
    
    
    
    public function mainAction() {

        if($_SERVER["REQUEST_METHOD"]=='POST'){

//            $text    = QuillConvert::toHtml($_POST['text']);
//            $text2   = QuillConvert::toHtml($_POST['text2']);
            $text    = Helper::clearData($_POST['text'],"html");
            $text2   = Helper::clearData($_POST['text2'],"html");
            
            $AdminedittextModel= new AdminedittextModel();
            $res = $AdminedittextModel->editMainText($text,$text2);
            if($res){
                $this->ok_msg();
            }else{
                $this->not_ok_msg("Ошибка");
            }

            
            
            }else{
                
                
                $fc = FrontController::getInstance();
        		/* Инициализация модели */
        		$model = new FileModel();
        		
        		                
        		$TextModel = new TextModel();
                $text = $TextModel->getText("index_txt1,index_txt2");
                $model->text = $text["index_txt1"];

                $model->text2 = $text["index_txt2"];

                
                
                $model->title = "Редактирование главной страницы";
                
                $model->cartAll = CartController::countCart();
                
                $model->contact = $TextModel->getContact();
        		$output = $model->render("adminindex.tpl.php");
        		
        		$fc->setBody($output);
                
            }
        
    }
    
    public function aboutAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $text = Helper::clearData($_POST['text'],"html");
            $AdminedittextModel= new AdminedittextModel();
               $res = $AdminedittextModel->editText($text, "about"); 
               if($res) header("Location: /txt/about/?ok=".urlencode("Страница обновлена!"));
               else
               header("Location: /txt/about/?er=".urlencode("Ошибка обновления")); 
        
                   
        
       }else{
		$fc = FrontController::getInstance();
		/* Инициализация модели */
		$model = new FileModel();
		
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        
		$TextModel = new TextModel();
        $ContactText = $TextModel->getText("about");
        $model->text = $ContactText["about"];
        $model->title = "О магазине";
        $model->url = "about";
        
		$output = $model->render("admintext.tpl.php");
		
		$fc->setBody($output);
        }
	}



    private function ok_msg() {
        $resData = array();
        $resData["success"] = 1;
        echo json_encode($resData);


    }
    private function not_ok_msg($msg="") {

        $resData = array();
        $resData["success"] = 0;
        $resData["msg"] = $msg;
        echo json_encode($resData);

    }
    
    
    
    
}
