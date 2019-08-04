<?php
class ProductArrModel{
    
    
    protected $_db;
    protected $_min_price = "";
    protected $_max_price = "";
    protected $_start_price = "";
    protected $_end_price = "";
    
        
    public function __construct(){
        
        $this->_db = DBConnect::run();
          
    }
    
    public function forIndex(){
        
        $sql = "SELECT product_id
                FROM main_page
                ORDER BY sort";
        $stmt = $this->_db->query($sql); 
        $idsArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($idsArr)){
            $ids = array();
            foreach($idsArr as $val){
                $ids[] = $this->_db->quote($val["product_id"]);
            }
            $in_id = implode(",", $ids);
            $sql = "SELECT id,sku,name,price,valuta,thumb_img
                    FROM product
                    WHERE id IN ($in_id)
                    ORDER BY FIELD(id,$in_id)";
            $stmt = $this->_db->query($sql); 
            
			return  Helper::exchange($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        
    }
    
    
    public function forMarketingIndex($column){
        
            $sql = "SELECT id,sku,name,price,valuta,thumb_img
                    FROM product
                    WHERE $column = 1
                    ORDER BY RAND()
                    LIMIT 2";
            $stmt = $this->_db->query($sql); 
            return  Helper::exchange($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    
    
    public function forMarketing($column){
        
            $sql = "SELECT id,sku,name,price,valuta,thumb_img
                    FROM product
                    WHERE $column = 1
                    ORDER BY sort";
            $stmt = $this->_db->query($sql); 
            return  Helper::exchange($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        
    
    
    public function getProduct($cat_id){
        $cat_id = $this->_db->quote($cat_id);
        $sql = "SELECT product.id AS id,
                        product.sku AS sku,
                        product.name AS name,
                        product.price AS price,
                        product.shot_desc AS shot_desc,
                        product.full_img AS full_img,
                        category.name AS cat_name,
                        category.predok AS predok
                FROM product
                INNER JOIN category_product_xref
        	       ON product.id = category_product_xref.product_id
                INNER JOIN category
        	       ON category.id = category_product_xref.category_id 
       
                WHERE category.id = $cat_id
                -- ORDER BY product.sort";
        $stmt = $this->_db->query($sql); 
        $productArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //$productArr = Helper::exchange($productArr);
        
//        $prices = array();//собираем прайс
//        foreach($productArr as $v){
//            if($v["price"]==0) continue;
//            $prices[] = $v["price"];
//        }
//        if(!empty($prices)){
//            $this->_min_price = min($prices);
//            $this->_max_price = max($prices);
//        }
        
        
        return  $productArr;
        
    }
    
    public function getSubCat($cat_id){
        $cat_id = $this->_db->quote($cat_id);
        $sql = "SELECT id,name,predok,img
                       
                FROM category
                      
                WHERE parent_id = $cat_id
                ORDER BY sort";
        $stmt = $this->_db->query($sql); 
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
    
        
    public function getFiltrProduct($cat_id){
        
        $cat_id = $this->_db->quote($cat_id);
        
        if((isset($_GET["start_price"]) AND !empty($_GET["start_price"])) AND
        (isset($_GET["end_price"]) AND !empty($_GET["end_price"]))) {
            
            $start_price = abs((int)$_GET["start_price"]);
            $end_price = abs((int)$_GET["end_price"]);
            
            if($start_price<$end_price){
                
                $this->_start_price = $start_price;
                $this->_end_price = $end_price;
            
            
               $start_price = $this->_db->quote($start_price); 
               $end_price = $this->_db->quote($end_price);
               
               $price_filtr1 = " WHERE product.price >=$start_price
                               AND product.price <=$end_price";
               $price_filtr2 = " AND product.price >=$start_price
                               AND product.price <=$end_price";               
            }else{
               $price_filtr1 = $price_filtr2 = ""; 
            }
            
        }else{
               $price_filtr1 = $price_filtr2 = "";
        }
        
        //фильтруем get в $get_param оставляем только те значения, которые массивы 
        $get_param = array_filter($_GET, "is_array");
        $count = count($get_param);
        
        if($count>0){//если нужно фильтровать по параметрам
            $int = 0;
            foreach($get_param as $key=>$val){
                
                                
                    $param = explode("|",$key);
                    $param_id = $this->_db->quote($param[1]);
                    
                    for($i=0,$len = count($val); $i<$len; $i++){
                        
                        $param_value = $this->_db->quote($val[$i]);
                        if($i==0)
                        $sql_param_string = "(param_id = $param_id AND param_value = $param_value)\n";
                        else
                        $sql_param_string .= " OR (param_id = $param_id AND param_value = $param_value)\n";
                    }
                    
                    if($int==0)
                    $sql_param_string_all = "(".$sql_param_string.")\n";
                    else
                    $sql_param_string_all .= " OR (".$sql_param_string.")\n";
                $int++;
            }
                      
            $sql = 'SELECT product.id AS id,
                        product.sku AS sku,
                        product.name AS name,
                        product.price AS price,
                        product.old_price AS old_price,
                        product.valuta AS valuta,
                        product.thumb_img AS thumb_img,
                        product.compare AS compare,
                        category.name AS cat_name,
                        category.predok AS predok
                    FROM (
                    	SELECT params_product.product_id
                    	FROM params_product
                    	INNER JOIN params ON params.id=params_product.param_id
                    	WHERE (params.cat_id = '.$cat_id.')
                            AND 
                		    ( 
                        	'.$sql_param_string_all.'
                            )
                    	GROUP BY params_product.product_id HAVING COUNT(*)='.$count.'	
                    ) AS p
                    JOIN product ON product.id=p.product_id
                    JOIN category_product_xref ON product.id = category_product_xref.product_id
                    JOIN category ON category.id = category_product_xref.category_id'.$price_filtr1.'
                    ORDER BY product.sort';
        
        
        }else{//если фильтровать по параметрам не нужно
            
                    $sql = "SELECT product.id AS id,
                            product.sku AS sku,
                            product.name AS name,
                            product.price AS price,
                            product.old_price AS old_price,
                            product.valuta AS valuta,
                            product.thumb_img AS thumb_img,
                            product.compare AS compare,
                            category.name AS cat_name,
                            category.predok AS predok
                        FROM product
                        INNER JOIN category_product_xref
                	       ON product.id = category_product_xref.product_id
                        INNER JOIN category
                	       ON category.id = category_product_xref.category_id 
               
                        WHERE category.id = $cat_id
                        $price_filtr2
                        ORDER BY product.sort";
        }
    //echo $sql;
    
    if(!empty($_GET["min_price"]) AND !empty($_GET["max_price"])){
            $this->_min_price = abs((int)$_GET["min_price"]);
            $this->_max_price = abs((int)$_GET["max_price"]);
            
        }
        
        $stmt = $this->_db->query($sql); 
        $productArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(empty($productArr))//массив пуст, возвращаем строку
        $productArr = "По заданным критериям ничего не найдено.";
        else
        $productArr = Helper::exchange($productArr);
        
        
        
        return  $productArr;
    
    
    }
    
    
    public function getAllProduct(){
        $sql = "SELECT id,name
                FROM product
                ORDER BY sort";
        $stmt = $this->_db->query($sql); 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getSearchProduct($idsArr){
        foreach($idsArr as $val){
              $ids[] = $this->_db->quote($val);
            }
            $in_id = implode(",", $ids);
            $sql = "SELECT id,sku,name,price,old_price,valuta,thumb_img
                    FROM product
                    WHERE id IN ($in_id)";
            $stmt = $this->_db->query($sql); 
            return  Helper::exchange($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    public function getMinPrice(){
        return $this->_min_price;
        
    }
    public function getMaxPrice(){
        return $this->_max_price;
        
    }
    public function getStartPrice(){
        
        /* if($this->_start_price == "")
        return $this->_min_price;
        else */
        return $this->_start_price;
    }
    public function getEndPrice(){
        /* if($this->_end_price == "")
        return $this->_max_price;
        else */
        return $this->_end_price;
        
    }
    
    
    
} 