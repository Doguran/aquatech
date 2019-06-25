<?php
class AdminordersController implements IController {
    
    public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        
    }
    public function indexAction() {
	   
        
        
        $fc = FrontController::getInstance();
        $model = new FileModel();
        
            $AdminordersObj = new AdminordersModel();
            $model->orders = $AdminordersObj->getOrders();

        //выводим все
        $output = $model->render("adminorders.tpl.php");
		$fc->setBody($output);
        
        
        
        
	}
    
    public function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["order"])){
            
        $order_id = abs((int)$params["order"]); 
        
        if(ADMIN) {
        
        $CustomerModelObj = new AdminordersModel();
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
            $model->contact = $OrderData[0]["contact"]." ".$OrderData[0]["customer_name"];
            $model->summa = $OrderData[0]["summa"];
            $model->note = $OrderData[0]["note"];
            $model->customer_name = $OrderData[0]["customer_name"];
            $model->pass_mail_text =  "Покупатель: ".$OrderData[0]["customer_name"];
            
            $output = $model->render("blocks/cart-order.tpl.php");
        }else{
            $output = "<p>Данных нет</p>";
        }
        $fc->setBody($output);        
        
            }else{
               throw new Exception("Нет доступа"); 
            }           
         
                    
        }else{
            throw new Exception("Нет параметров");
        }
  }
  
  public function deleteordersAction(){
    if (isset($_POST["delete_orders"])) {
        $delete_orders = $_POST["delete_orders"];
        //Helper::print_arr($delete_orders);
        $AdminordersModel = new AdminordersModel();
        foreach($delete_orders as $val){
           $AdminordersModel->deleteOrder($val); 
        }
   }
   header("Location: /adminorders/"); 
   exit;
  }
    
    
}
