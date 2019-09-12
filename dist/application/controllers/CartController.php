<?php
class CartController implements IController {
    
    public function indexAction() {
		$this->showAction();
    }
    
    //Добавление продуктов в корзину
    public function addtocartAction() {
	   
		$resData = array();
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
        $id = abs((int)$_POST["id"]); 
        $quantity = abs((int)$_POST["quantity"]);//количество
        $price = abs((int)$_POST["price"]);
        
        if($quantity == 0 || $price ==0 || $id ==0 ){
           $resData["success"] = 0; 
        }else{
           $resData["success"] = 1; 
           
           $_SESSION["cart"][$id] = array("quantity"=>$quantity,"price"=>$price);
        
        $countCart = $this->countCart();
        
        $resData["quantityAll"] = $countCart["quantityAll"];
        $resData["priceAll"] = $countCart["priceAll"];
        }
        
       }else{
        $resData["success"] = 0;
       }     
       echo json_encode($resData);
       
        
	}
    
    
    //изменение количества товара
    public function editAction() {
	   
		$resData = array();
        if($_SERVER["REQUEST_METHOD"]=='POST'){
        $id = explode("_", $_POST["id"]);   
        $id = abs((int)$id[1]); 
        $quantity = abs((int)$_POST["quantity"]);//количество
        
        
        if($quantity == 0 || $id ==0 ){
           $resData["success"] = 0; 
        }else{
           $resData["success"] = 1; 
           
           $_SESSION["cart"][$id]["quantity"] = $quantity;
        
        $countCart = $this->countCart();
        
        $resData["quantityAll"] = $countCart["quantityAll"];
        $resData["priceAll"] = $countCart["priceAll"];
        $resData["id"] = $id;
        $resData["sum"] = $_SESSION["cart"][$id]["price"]*$quantity;
        
        }
        
       }else{
        $resData["success"] = 0;
       }     
       echo json_encode($resData);
       
    }
    
    
    //изменение количества товара
    public function deleteAction() {
	   
		$resData = array();
        if($_SERVER["REQUEST_METHOD"]=='POST'){
        $id = explode("_", $_POST["id"]);   
        $id = abs((int)$id[1]); 
        
        if($id ==0 ){
           $resData["success"] = 0; 
        }else{
           $resData["success"] = 1; 
           
           unset($_SESSION["cart"][$id]);
        
        $countCart = $this->countCart();
        
        $resData["quantityAll"] = $countCart["quantityAll"];
        $resData["priceAll"] = $countCart["priceAll"];
        $resData["id"] = $id;
        
        }
        
       }else{
        $resData["success"] = 0;
       }     
       echo json_encode($resData);
    }
    
    
    static public function countCart(){

        //Helper::print_arr($_SESSION["cart"]); exit;


        
        $quantityAll = 0;
        $priceAll = 0;
        
        foreach($_SESSION["cart"] as $val){


            if($val["price"] == 0 || $val["price"] == "" || $val["quantity"] == 0) continue;
            $quantityAll += $val["quantity"];
            $priceAll += $val["quantity"]*$val["price"];

        }
        
                
        return array("quantityAll"=>$quantityAll,"priceAll"=>$priceAll);
    }
    
    //ищет товар в козине по id, если находит вернет его количество, если нет - false
    static public function findtoCart($id){
        
        if(array_key_exists($id, $_SESSION["cart"]))
            return $_SESSION["cart"][$id]["quantity"];
        else
            return false;
        
    }
    
    
    public function showAction() {
	   
		$fc = FrontController::getInstance();
        
        $model = new FileModel();
        
        
        
        if(!empty($_SESSION["cart"])){
            $BasketModel = new BasketModel();
            $cartArr = $BasketModel->getCartProduct();
            
            //Helper::print_arr($_SESSION["cart"]);
            //Helper::print_arr($BasketModel->getCartProduct());
        }
        
        if(isset($cartArr) AND is_array($cartArr) AND !empty($cartArr)){
           $CustomerObj = new CustomerModel();
           $model->customerData = $CustomerObj->getCustomerData();
           $model->cart = $cartArr;
        }else{
           $model->cart = null;
        }

        $model->cartAll = $this->countCart();
        $output = $model->render(BASKET_FILE);
		
		$fc->setBody($output);
        
    
    }
    
    
    public function takeorderAction() {
        
        $resData = array();
        if($_SERVER["REQUEST_METHOD"]=='POST' and !empty($_SESSION["cart"])){
            
            $errors = array();
            if(!empty($_POST["url"]) || $_POST["action"] != "send"){
               $errors[] = "Антиспам";
            }
            
            $name = isset($_POST["name"]) ? Helper::clearData($_POST["name"]) : "";
            if(mb_strlen($name,'utf8')<10) {$errors[] = "Неккоректное ФИО"; }
            
            $email = isset($_POST["email"]) ? Helper::clearData($_POST["email"],"email") : "";
            if($email=='') {$errors[] = "Неккоректный email"; }
            
            $phone = isset($_POST["phone"]) ? Helper::clearData($_POST["phone"]) : "";
            
            $address = isset($_POST["address"]) ? Helper::clearData($_POST["address"]) : "";
            if(mb_strlen($address,'utf8')<10) {$errors[] = "Неккоректный адрес"; }
            
            $note = isset($_POST["note"]) ? Helper::clearData($_POST["note"]) : "";
            
            $delivery = (isset($_POST["delivery"]) and ($_POST["delivery"] == "courier" || $_POST["delivery"] == "self")) ? $_POST["delivery"] : "";
            if($delivery=='') {$errors[] = "Не выбран способ доставки"; }
            
            $payment = (isset($_POST["payment"]) and ($_POST["payment"] == "cash" || $_POST["payment"] == "bank")) ? $_POST["payment"] : "";
            if($payment=='') {$errors[] = "Не выбран способ оплаты"; }
            
            
            
            if(empty($errors)){ //если ошибок нет
            
            
                //смотрим, что в корине
                $basketObj = new BasketModel();
                $basketArr = $basketObj->getCartProduct();
                                
                if(!empty($basketArr)){//если в корзине что-то есть

                    $model = new FileModel();
                    $customerObj = new CustomerModel();
                    if(isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id'])) {// если юзер существует и авторизирован
                        $customer_id = $_SESSION['user']['id'];
                        $pass_mail_text = null;
                        
                        //обновляем инфу по юзеру
                        $customerObj->updateCustomer($customer_id,$name,$email,$phone,$address);
                        $_SESSION['user']['name'] = $name;
                        $_SESSION['user']['email'] = $email;
                        
                    }else{
                        //создаем пользователя, оформляем покупку
                        //НОвый пользователь
                        $pass = Helper::generatePass(); //генерируем пароль
                                            
                        $cryptpass = Helper::cryptoPass($pass);//шифруем пароль
                        
                        $customer_id = $customerObj->addCustomer($name,$email,$phone,$address,$cryptpass);
                        
                        //заводим юзера
                        $_SESSION['user']['id'] = $customer_id;
                        $_SESSION['user']['name'] = $name;
                        $_SESSION['user']['email'] = $email;
                        $_SESSION['user']['status'] = 'customer';
                        $_SESSION['user']['ip'] = $_SERVER['REMOTE_ADDR'];
                        
                        $pass_mail_text = "Теперь Вы можете входить на сайт как зарегистрированный пользователь и просматривать историю заказов.
                        <br /><br />
                        Ваш пароль, сгенерированный системой: <strong>$pass</strong><br />
                        Вы сможете сменить его в <a href='".HTTP_PATH."cabinet/'>личном кабинете</a>. <br /><br />";

                    }
                    
                     
                    //оформляем покупку
                    //заполняем таблицу orders
                    
                    $courier_price = $delivery == "courier" ? COURIER_PRISE : 0;
                    $summa = 0;
                    foreach($basketArr as $val){
                        $summa += $val["priceAll"];
                    }
                    $summa += $courier_price;
                    $contact = "Контакты: $address, $email, тел.:$phone";
                    $order_id = $basketObj->registrationOrder($customer_id,$delivery,$payment,$note,$summa,$contact);
                    
                                        
                    //заполняем таблицу shopping
                    $basketObj->registrationShoppingProduct($basketArr,$order_id);
                    
                    $host = Helper::getHost();

                    //мылим покупателю
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

                    $delivery_method = $delivery == "courier" ? "курьер" : "самовывоз";
                    $payment_method = $payment == "bank" ? "банковский перевод" : "наличными";

                    $textVersion = '';
                    $table = '';
                    foreach($basketArr as $val){
                        
                        $table .= "<tr>
                    				<td>
                    					
                    					$val[name]
                    				</td>
                                    <td align='center'>$val[price] руб.</td>
                    				<td align='center'>$val[quantity]</td>
                    				<td align='center'>$val[priceAll] руб</td>
                    				
                    			  </tr>\n";
                        $textVersion .= "$val[name] - $val[price] руб. X $val[quantity] шт. = $val[priceAll] руб.\n-------------------------------\n";

                        
                    }

                    $body_usery_textVersion = "$name! Спасибо Вам за покупку.\n".strip_tags($pass_mail_text)."
                    Номер вашего заказа: $order_id\n 
                    $textVersion 
                    Способ доставки: $delivery_method - $courier_price руб.
                    Способ оплаты: $payment_method 
                    Итого: $summa руб.
                    Примечание к заказу: $note
                    Адрес доставки: $address, $email, тел.: $phone, $name\n".HTTP_PATH;

                    
                    
                    
                    $body_usery = "
                    $name! Спасибо Вам за покупку.<br />
                    
                    $pass_mail_text
                     
                    Номер вашего заказа: <strong>$order_id</strong><br /><br />
                    <br />
                    <table class='table table-bordered '>
                			
        			  <tr>
        				<th>наименование</th>
                        <th align='center'>цена за ед.</th>
        				<th align='center'>количество</th>
        				<th align='center'>стоимость</th>
        				
        			  </tr>
                      
        			  $table
                      
                      <tr>
        				<td>Способ доставки:</td>
                        <td colspan='2' align='right'>$delivery_method</td>
        				<td align='center'>$courier_price руб</td>
                      </tr>
                      <tr>
        				<td>Способ оплаты:</td>
                        <td colspan='2' align='right'>$payment_method</td>
        				<td></td>
        			 </tr>
        			  <tr>
        				<td colspan='4' align='right'>Итого: <strong>$summa руб.</strong></td>
        				
        			  </tr>
                      <tr>
        				<td colspan='4'>Примечание к заказу: $note</td>
        				
        			  </tr>
                      <tr>
        				<td colspan='4'><strong>Адрес доставки:</strong> $address, <strong>e-mail:</strong> $email, <strong>тел.:</strong> $phone, $name</td>
        				
        			  </tr>
        			</table>
                    <br />
                    <a href='".HTTP_PATH."'>".HTTP_PATH."</a>
                    ";

                    $hdrs = array(
                      'From'    => "Интернет-магазин $host <".SMTP_USERNAME.">",
                      'Subject' => "Детали Вашего заказа #$order_id"
                    );

                    $mime->setTXTBody($body_usery_textVersion);
                    $mime->setHTMLBody($body_usery);

                    $body = $mime->get();
                    $hdrs = $mime->headers($hdrs);

                    $mail =& Mail::factory('smtp', array('host' => SMTP_HOST, 'debug' => false, 'pipelining' => false, 'port' => SMTP_PORT, 'auth' => true, 'username' => SMTP_USERNAME, 'password' => SMTP_PASSWORD));
                    $mail->send($email, $hdrs, $body);
                    

                    
                    //мылим админу
                    $mime_admin = new Mail_mime($mailParameter);
                    $body_textVersion = "Номер вашего заказа: $order_id\n 
                    $textVersion 
                    Способ доставки: $delivery_method - $courier_price руб.
                    Способ оплаты: $payment_method 
                    Итого: $summa руб.
                    Примечание к заказу: $note
                    Адрес доставки: $address, $email, тел.: $phone, $name\n".HTTP_PATH;

                    $body = "
                    Номер заказа: <strong>$order_id</strong><br /><br />
                    Детали заказа:<br /><br />
                    <table border='0' cellspacing='0' cellpadding='5'>
                			
        			  <tr>
        				<th>наименование</th>
                        <th align='center'>цена за ед.</th>
        				<th align='center'>количество</th>
        				<th align='center'>стоимость</th>
        				
        			  </tr>
                      
        			  $table
                      
                      <tr>
        				<td>Способ доставки:</td>
                        <td colspan='2' align='right'>$delivery_method</td>
        				<td align='center'>0 руб</td>
                      </tr>
                      <tr>
        				<td>Способ оплаты:</td>
                        <td colspan='2' align='right'>$payment_method</td>
        				<td></td>
        			 </tr>
        			  <tr>
        				<td colspan='4' align='right'>Итого: <strong>$summa руб.</strong></td>
        				
        			  </tr>
                      <tr>
        				<td colspan='4'>Примечание к заказу: $note</td>
        				
        			  </tr>
                      <tr>
        				<td colspan='4'>Адрес доставки:$address<br />e-mail: $email<br />тел.: $phone<br />$name</td>
        				
        			  </tr>
        			</table>
                    ";

                    $hdrs_admin = array(
                      'From'    => "Интернет-магазин $host <".SMTP_USERNAME.">",
                      'Subject' => "Поступил заказ #$order_id"
                    );

                    $mime_admin->setTXTBody($body_textVersion);
                    $mime_admin->setHTMLBody($body);

                    $body = $mime_admin->get();
                    $hdrs_admin = $mime_admin->headers($hdrs_admin);

                    $mail =& Mail::factory('smtp', array('host' => SMTP_HOST, 'debug' => false, 'pipelining' => false, 'port' => SMTP_PORT, 'auth' => true, 'username' => SMTP_USERNAME, 'password' => SMTP_PASSWORD));
                    //$admin_email = Helper::getAdminMail();
                    $mail->send(ADMIN_EMAIL, $hdrs_admin, $body);

                    
                    
                    //чистим корзину
                    $_SESSION["cart"] = array();
                    
                    
                    
                    
                    $resData["success"] = 1;
                    //отвечаем, что все ок
                    $model->name = $name;
                    $model->welcome = $name.", cпасибо Вам за покупку!";
                    $model->email = $email;
                    $model->basketArr = $basketArr;
                    $model->pass_mail_text = $pass_mail_text;
                    $model->summa = $summa;
                    $model->contact = $contact;
                    $model->order_id = $order_id;
                    $model->courier_price = $courier_price;
                    $model->delivery_method = $delivery_method;
                    $model->payment_method = $payment_method;
                    $model->note = $note;
                    $model->address = $address;
                    $model->phone = $phone;

                    $resData["msg"] = "<h4 class=\"featurette-heading my-5 text-center\">Заказ оформлен</h4>\n".
                    $model->render('blocks/cart-order.tpl.php');
                    
                    
                    
                }
                
                
            }else{
                $error_msg = "";
                foreach($errors as $val){
                    $error_msg .= $val."<br>";
                }
                $resData["success"] = 0;
                $resData["msg"] = $error_msg;
            }
            
           echo json_encode($resData);
        
       }
            
       
	   
		
    }

    public function modalAction() {

        $model = new FileModel();
        echo $model->render("cart-modal.tpl.php");


    }
    
    
    
    
}
