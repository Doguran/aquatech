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

        $sql = "SELECT category.id AS cat_id,
                product.id AS id,
                product.name AS name,
                product.sku AS sku,
                product.price AS price,
                product.full_img AS full_img,
                category.name AS cat_name,
                category.predok AS predok
                FROM product
                INNER JOIN category_product_xref
        	       ON product.id = category_product_xref.product_id
                INNER JOIN category
        	       ON category.id = category_product_xref.category_id 
       
                WHERE product.id IN (".$wherein.")
                ORDER BY FIELD(product.id,".$wherein.")";
        
//        $sql = "SELECT id,name,price,full_img,sku
//                FROM product
//                WHERE id IN (".$wherein.")
//                ORDER BY FIELD(id,".$wherein.")";
        $stmt = $this->_db->query($sql); 
        //$productArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $cartarray = array();
        $CatModel = new CatModel();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($row["price"] == 0 || $row["price"] == "") continue;


            $imgDir = $row["predok"] ? $CatModel->getCatName($row["predok"]) : $CatModel->getCatName($row["cat_id"]);
            $price = round($row["price"]*EVRO, -1);
            
            $cartarray[] = array(
                'id' => $row["id"],
                'name' => $row["name"],
                'price' =>  $price*$_SESSION["cart"][$row["id"]]["quantity"],
                'sku' => $row["sku"],
                'full_img' => $row["full_img"],
                'cat_name' => $row["cat_name"],
                'imgDir' => Helper::getChpu($imgDir["name"]),
                'priceAll' => $price*$_SESSION["cart"][$row["id"]]["quantity"],
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
            //$product_url = $this->_db->quote($val["url"]);
            $product_id = $this->_db->quote($val["id"]);
            $name = $this->_db->quote($val["name"]);
            $sku = $this->_db->quote($val["sku"]);
            
            $sql = "INSERT INTO shopping (order_id,product_id,quantity,price,product_name,product_sku)
                VALUES ($order_id,$product_id,$quantity,$price,$name,$sku)";
            $result = $this->_db->exec($sql); 
            
        }
    
    }
        
        
        
        
    }
    
    
 