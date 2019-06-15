<?php
class BasketModel{
    
    protected $_db;
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }
    
    //достаем корзину из сессии
    public function getCartProduct(){ 
        
        $where = array();
        foreach($_SESSION["cart"] as $key=>$val){
            if($val["quantity"]==0 || $key==0) continue;
            
            $where[] = $this->_db->quote($key);
        }
        $wherein = implode(",", $where);
        
        $sql = "SELECT id,name,price,thumb_img,sku,url
                FROM product
                WHERE id IN (".$wherein.")
                ORDER BY FIELD(id,".$wherein.")";
        $stmt = $this->_db->query($sql); 
        //$productArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cartarray = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($row["price"] == 0 || $row["price"] == "") continue;
            
            $cartarray[] = array(
                'id' => $row["id"],
                'url' => $row["url"],
                'name' => $row["name"],
                'price' => $row["price"],
                'sku' => $row["sku"],
                'thumb_img' => $row["thumb_img"],
                'priceAll' => $row["price"]*$_SESSION["cart"][$row["id"]]["quantity"],
                'quantity' => $_SESSION["cart"][$row["id"]]["quantity"]
                );
            
        }
        
        return $cartarray;
        
    }
    
    
    //заполняем таблицу shopping продуктами из корзины при оформлении покупки
    public function registrationOrder($customer_id,$delivery,$payment,$note,$summa,$contact){ 
    
    
        $customer_id = $this->_db->quote($customer_id);
        $delivery    = $this->_db->quote($delivery);
        $payment     = $this->_db->quote($payment);
        $note        = $this->_db->quote($note);
        $summa       = $this->_db->quote($summa);
        $contact     = $this->_db->quote($contact);
                
        $sql = "INSERT INTO orders (customer_id,delivery,payment,note,summa,contact,date_order)
                VALUES ($customer_id,$delivery,$payment,$note,$summa,$contact,NOW())";
        $result = $this->_db->exec($sql); 
        if($result === false) exit("ERROR");
        
        return $this->_db->lastInsertId();
        
    }
        
        
        
    //заполняем таблицу shopping продуктами из корзины при оформлении покупки
    public function registrationShoppingProduct($basketArr,$order_id){ 
    
        $order_id = $this->_db->quote($order_id);
        foreach($basketArr as $val){
            
            $price = $this->_db->quote($val["price"]);
            $quantity = $this->_db->quote($val["quantity"]);
            $product_url = $this->_db->quote($val["url"]);
            $product_id = $this->_db->quote($val["id"]);
            $name = $this->_db->quote($val["name"]);
            $sku = $this->_db->quote($val["sku"]);
            
            $sql = "INSERT INTO shopping (order_id,product_id,quantity,price,product_name,product_sku,product_url)
                VALUES ($order_id,$product_id,$quantity,$price,$name,$sku,$product_url)";
            $result = $this->_db->exec($sql); 
            
        }
    
    }
        
        
        
        
    }
    
    
 