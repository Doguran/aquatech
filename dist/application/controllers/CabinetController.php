<?php
class CabinetController implements IController {
    
    
    public function indexAction() {



        $fc = FrontController::getInstance();
        $model = new FileModel();

        if(isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id'])) {// если юзер существует и авторизирован
            $customerObj = new CustomerModel();
            $model->orders = $customerObj->getOrders();
        }

        //выводим все
        $output = $model->render("cabinet.tpl.php");
		$fc->setBody($output);

    }
    
    public function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["order"])){
            
        $order_id = abs((int)$params["order"]); 
        
        if(isset($_SESSION['user']['id'])) {// если юзер существует и авторизирован
        
        $CustomerModelObj = new CustomerModel();
        $OrderData = $CustomerModelObj->getOrderData($order_id);
        
        $model = new FileModel(); 
        $model->basketArr = $OrderData;
        
        if(!empty($OrderData)){
            
            $sum = 0;
            foreach($OrderData as $val){
                $sum += $val["price"]*$val["quantity"];
            }
            
            $model->courier_price = $OrderData[0]["summa"] - $sum;
            $model->order_id = $order_id;
            $model->delivery_method = $OrderData[0]["delivery"] == "courier" ? "курьер" : "самовывоз";
            $model->payment_method = $OrderData[0]["payment"] == "bank" ? "банковский перевод" : "наличными";
            $model->welcome = "{$OrderData[0]['date_d']} {$OrderData[0]['date_m']} {$OrderData[0]['date_y']}";
            $model->contact = $OrderData[0]["contact"];
            $model->summa = $OrderData[0]["summa"];
            $model->note = $OrderData[0]["note"];
            $model->pass_mail_text = null;
            
            
        }else{
            $output = "<p>Данных нет</p>";
        }
        $output = $model->render("blocks/cart-order.tpl.php");
        $fc->setBody($output);        
        
            }else{
               throw new Exception("Нет доступа"); 
            }           
         
                    
        }else{
            throw new Exception("Нет параметров");
        }
        
		
        
	}
    
    
}
