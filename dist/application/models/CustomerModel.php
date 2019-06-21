<?php
class CustomerModel{
    
    protected $_db;
    
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }
    
    public function addCustomer ($name,$email,$phone,$address,$pass){
        
        $name = $this->_db->quote($name);
        $email = $this->_db->quote($email);
        $phone = $this->_db->quote($phone);
        $address = $this->_db->quote($address);
        $pass = $this->_db->quote($pass);
        
        $sql = "INSERT INTO customer (name,email,phone,address,pass,status)
                VALUES ($name,$email,$phone,$address,$pass,'customer')";
        try{      
        $result = $this->_db->exec($sql); 
        } catch (Exception $e){
            
            $resData["success"] = 0;
            $resData["msg"] = "Ошибка базы данных";
            if($e->errorInfo[1] == 1062)
            $resData["msg"] = "Email $email уже присутствует в базе данных.<br />Пожалуйста, авторизируйтесь на сайте.";
            echo json_encode($resData);
            //var_dump($e->errorInfo);
            exit;
            
        } 
        if($result === false) exit("ERROR");
        
        return $this->_db->lastInsertId();
        
                
    }
    
    public function updateCustomer ($id,$name,$email,$phone,$address){
        
        $name = $this->_db->quote($name);
        $email = $this->_db->quote($email);
        $phone = $this->_db->quote($phone);
        $address = $this->_db->quote($address);
        $id = $this->_db->quote($id);
        
        $sql = "UPDATE customer 
                    SET name = $name,
                    email = $email,
                    phone = $phone,
                    address = $address
                WHERE id = $id";
        try{      
        $result = $this->_db->exec($sql); 
        } catch (Exception $e){
            
            $resData["success"] = 0;
            $resData["msg"] = "Ошибка базы данных";
            if($e->errorInfo[1] == 1062)
            $resData["msg"] = "Email $email уже присутствует в базе данных.";
            echo json_encode($resData);
            //var_dump($e->errorInfo);
            exit;
            
        } 
        if($result === false) exit("ERROR");
        
        return $this->_db->lastInsertId();
        
                
    }
    
    public function getCustomerData (){
        
        $costomerData = array(
                        'name'=>null,
                        'email'=>null,
                        'phone'=>null,
                        'address'=>null
                        );
                        
        if(isset($_SESSION["user"])){
            
            $id = $this->_db->quote($_SESSION['user']['id']);
            $sql = "SELECT name,email,phone,address 
                    FROM customer 
                    WHERE id=$id";
            $stmt = $this->_db->query($sql); 
            $costomerData =  $stmt->fetch(PDO::FETCH_ASSOC);
            
        }
        return $costomerData;
     }
     
     
     public function getOrders (){
        
            $id = $this->_db->quote($_SESSION['user']['id']);
            $sql = "SELECT  id,
                            summa,
                            DATE_FORMAT(date_order, '%e') as date_d,
                            DATE_FORMAT(date_order, '%M') as date_m, 
                            DATE_FORMAT(date_order, '%Y') as date_y 
                    FROM orders 
                    WHERE customer_id=$id
                    ORDER BY id DESC";
            //$this->_db->query("SET lc_time_names = 'ru_RU'");
            $stmt = $this->_db->query($sql); 
            $ordersData =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            
       
        return $ordersData;
     }
     
     
     public function getOrderData ($id){
        
            $costomer_id = $this->_db->quote($_SESSION['user']['id']);
            $order_id = $this->_db->quote($id);
            
            $sql = "SELECT shopping.product_id as product_id,
                    		shopping.product_name as product_name,
                            /*shopping.product_url as product_url,*/
                    		shopping.product_sku as product_sku,
                    		shopping.quantity as quantity,
                    		shopping.price as price,
                    		DATE_FORMAT(orders.date_order, '%e') as date_d,
                            DATE_FORMAT(orders.date_order, '%M') as date_m, 
                            DATE_FORMAT(orders.date_order, '%Y') as date_y,
                    		orders.delivery as delivery,
                    		orders.payment as payment,
                    		orders.summa as summa,
                    		orders.note as note,
                    		orders.contact as contact,
                    		orders.status as status
                    FROM   shopping
                    INNER JOIN orders
                    	   ON orders.id = shopping.order_id
                    WHERE orders.id = $order_id AND orders.customer_id = $costomer_id";
            //$this->_db->query("SET lc_time_names = 'ru_RU'");
            $stmt = $this->_db->query($sql); 
            $orderData =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            
       
        return $orderData;
     }
    
        
    
    
    
 } 