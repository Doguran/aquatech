<?php
class AdminordersModel{
    
    protected $_db;
    
    
    public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
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
        
            
            $sql = "SELECT  orders.id as id,
                            orders.summa as summa,
                            DATE_FORMAT(orders.date_order, '%e') as date_d,
                            DATE_FORMAT(orders.date_order, '%M') as date_m, 
                            DATE_FORMAT(orders.date_order, '%Y') as date_y,
                            customer.name as name
                    FROM orders 
                    INNER JOIN customer
                    	   ON customer.id = orders.customer_id
                    ORDER BY orders.id DESC";
            //$this->_db->query("SET lc_time_names = 'ru_RU'");
            $stmt = $this->_db->query($sql); 
            $ordersData =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            
       
        return $ordersData;
     }
     
     
     public function getOrderData ($id){
        
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
                    		orders.status as status,
                            customer.name as customer_name
                    FROM   shopping
                    INNER JOIN orders
                    	   ON orders.id = shopping.order_id
                    INNER JOIN customer
                    	   ON customer.id = orders.customer_id
                    WHERE orders.id = $order_id";
            //$this->_db->query("SET lc_time_names = 'ru_RU'");
            $stmt = $this->_db->query($sql); 
            $orderData =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            
       
        return $orderData;
     }
     
     public function deleteOrder($id){
        $order_id = $this->_db->quote($id);
        $sql = "DELETE FROM orders
                WHERE id=$id
                LIMIT 1";
        $result = $this->_db->exec($sql);
        $sql = "DELETE FROM shopping
                WHERE order_id=$id";
        $result = $this->_db->exec($sql);
     }
    
        
    
    
    
 } 