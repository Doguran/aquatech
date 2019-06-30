<?php
class ProductModel{
    
    protected $_db;
    private $_product;
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }
    
    
    public function getProduct($product_id){
        
        $product_id = $this->_db->quote($product_id);   
            
        $sql = "SELECT category.id AS cat_id,
                product.id AS id,
                product.name AS name,
                product.sku AS sku,
                product.price AS price,
                product.old_price AS old_price,
                product.shot_desc AS shot_desc,
                product.description AS description,
                product.thumb_img AS thumb_img,
                product.full_img AS full_img,
                /*product.compare AS compare,*/
                product.title AS title,
                product.keywords AS keywords,
                product.seo_desc AS seo_desc,
                /*product.new AS new,
                product.liders AS liders,
                product.sale AS sale,*/
                product.complete AS complete,
                /*product.model AS model,
                product.yandex_cat AS yandex_cat,
                product.garant AS garant,
                product.vote AS vote,
                product.voters AS voters,*/
                product.valuta AS valuta
              
                FROM product
                INNER JOIN category_product_xref
        	       ON product.id = category_product_xref.product_id
                INNER JOIN category
        	       ON category.id = category_product_xref.category_id 
       
                WHERE product.id = $product_id";
                
                
                
        $stmt = $this->_db->query($sql); 
        $this->_product =  $stmt->fetch(PDO::FETCH_ASSOC);
        
        $fc = FrontController::getInstance();
        $fc->setCatId($this->_product["cat_id"]);
        $fc->setProductId($this->_product["id"]);
        
        return $this->_product;
        
    }
    
    public function drawParametrTable($cat_id,$product_id){
        
        $cat_id = $this->_db->quote($cat_id);
        $product_id  = $this->_db->quote($product_id);
        
        $sql = "SELECT params_product.param_id AS param_id,
                    		params_product.param_value AS param_value,
                    		params.param_name AS param_name
                    FROM params_product
                    INNER JOIN params
                    	   ON params_product.param_id = params.id
                    WHERE params_product.product_id = $product_id";
        $stmt = $this->_db->query($sql); 
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        if(!empty($arr)){
        $out = "<table class='table table-condensed table-hover'>\n";    
            foreach($arr as $val){
                $out .= "<tr>
                \t<td>$val[param_name]</td>
                \t<td>$val[param_value]</td>
                </tr>\n"; 
            }
        $out .= "</table>\n";
        }else{
          $out = false;  
        }     
        
        return $out;
        
    }
    
    public function drawCompleteList($complete){
        
        if(!empty($complete)){
            $compArr =  explode(",", $complete);
            $comp_list = "<ul>\n";
            foreach($compArr as $val){
                $comp_list .= "<li>".ucfirst(trim($val))."</li>\n";
            }
            $comp_list .= "</ul>\n";
        }else{
          $comp_list = false;  
        }     
        
        return $comp_list;
        
    }
    
        
   public function getFromAsk($id){
    
    $id = $this->_db->quote($id); 
    $sql="SELECT id,sku,name,thumb_img
            FROM product
            WHERE id = $id";
    $stmt = $this->_db->query($sql); 
    return  $stmt->fetch(PDO::FETCH_ASSOC);        
    
   }  
   
   
   public function getComment($id){
    
    $id = $this->_db->quote($id); 
    $sql="SELECT comment.id AS id,
                 comment.text AS text,
                 DATE_FORMAT(comment.date_add,'%d %b %Y') AS date,
		         customer.name AS name
	      FROM comment
	      INNER JOIN customer
		  ON comment.user_id = customer.id
	      WHERE comment.product_id = $id
          ORDER BY comment.id DESC";
    $stmt = $this->_db->query($sql); 
    return  $stmt->fetchAll(PDO::FETCH_ASSOC);        
    
   }  
   
   
      
        
}
    
    
 