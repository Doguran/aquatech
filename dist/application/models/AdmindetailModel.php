<?php

class AdmindetailModel{
    
    protected $_db;
    
    
    public function __construct(){
        if(!ADMIN)
        throw new Exception("Нет доступа");
        $this->_db = DBConnect::run();
        
    }



    public function drawParametrForAdminDetail($cat_id,$product_id){
        $out = "";
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
        
        $out .= "<table id='parametr-table-admin' class='table table-condensed'>\n"; 
        if(!empty($arr)){
            $ids = array();
           
            foreach($arr as $val){
                $out .= "<tr>\n
                <td>$val[param_name]</td>\n
                <td><input type='text' class='form-control input-sm' name='parametrs[$val[param_id]|$val[param_name]]' value='$val[param_value]'></td>\n
                </tr>\n"; 
                $ids[] = $val["param_id"];
            }
            $ids = implode(",", $ids);
            $sql = "SELECT DISTINCT param_name,
    				id
                    FROM params	
                    WHERE cat_id = $cat_id AND id NOT IN ($ids)";
                
        }else{
            $sql = "SELECT DISTINCT param_name,
    				id
                    FROM params	
                    WHERE cat_id = $cat_id"; 
        } 
        $out .= "</table>\n";      
        $stmt = $this->_db->query($sql); 
        $arr2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $out .="<select id='add-parametr'>\n";
        $out.="<option value='default' class='default' selected>Выбрать параметр из существующих в этой категории</option>\n";
        if(!empty($arr2)){
            foreach($arr2 as $val){
             $out.="<option value='$val[id]'>$val[param_name]</option>\n";   
            }
        }
            
        $out .="</select>\n";
        return $out;
        
    }


    public function getParametrForDetailEdit($cat_id,$product_id){
        $cat_id  = $this->_db->quote($cat_id);
        $product_id  = $this->_db->quote($product_id);

        $sql = "SELECT p.id AS id,
		               p.param_name AS param_name,
		               v.param_value AS param_value
                FROM (SELECT id, param_name FROM params WHERE cat_id = $cat_id) AS p
                LEFT JOIN (SELECT param_value, param_id FROM params_product WHERE product_id = $product_id) AS v
                ON p.id = v.param_id";
        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);


    }

    public function getParametrForDetailAdd($cat_id){
        $cat_id  = $this->_db->quote($cat_id);
        $sql = "SELECT id, param_name
                FROM params
                WHERE cat_id = $cat_id";
        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    
    
    
    public function updateProductTable($product_id,$name,$sku,$price,$old_price,$description,$thumb_img,$full_img,$title,$keywords,$seo_desc,$complete){
        
       if(defined('PARAM')) $compare = 1; else $compare = 0;
        
       $product_id  = $this->_db->quote($product_id);
       $name        = $this->_db->quote($name);
       $sku         = $this->_db->quote($sku);
       $price       = $this->_db->quote($price);
       $old_price   = $this->_db->quote($old_price);
       $description = $this->_db->quote($description);

       $thumb_img   = $this->_db->quote($thumb_img);
       $full_img    = $this->_db->quote($full_img);
       $title       = $this->_db->quote($title);
       $keywords    = $this->_db->quote($keywords);
       $seo_desc    = $this->_db->quote($seo_desc);
       $compare     = $this->_db->quote($compare);
       $complete    = $this->_db->quote($complete);

       
           
        $sql = "UPDATE product
                SET name        = $name,
                    sku         = $sku,
                    price       = $price,
                    old_price   = $old_price,
                    description = $description,
                    thumb_img   = $thumb_img,
                    full_img    = $full_img,
                    title       = $title,
                    keywords    = $keywords,
                    seo_desc    = $seo_desc,
                    compare     = $compare,
                    complete    = $complete
                   
                WHERE id        = $product_id
                LIMIT 1";
        try{      
        $result = $this->_db->exec($sql); 
        } catch (Exception $e){
            
            $resData["success"] = 0;
            $resData["msg"] = "Ошибка базы данных";
            if($e->errorInfo[1] == 1062)
            $resData["msg"] = "Такой артикул уже есть.";
            echo json_encode($resData);
            //var_dump($e->errorInfo);
            exit;
            
        } 
        if($result === false) {
            exit("ERROR");
        }else{
            return true;
        }
                
    }
    
    public function updateProductCategory($product_id,$new_cat_id){
        
        $product_id  = $this->_db->quote($product_id);
        $new_cat_id  = $this->_db->quote($new_cat_id);
        $sql = "UPDATE category_product_xref
                SET category_id  = $new_cat_id
                WHERE product_id = $product_id
                LIMIT 1";
        return $this->_db->exec($sql);
        
    }
    
    public function insertProductCategory($product_id,$new_cat_id){
        
        $product_id  = $this->_db->quote($product_id);
        $new_cat_id  = $this->_db->quote($new_cat_id);
        $sql="INSERT INTO category_product_xref (category_id,product_id)
            VALUES ($new_cat_id,$product_id)";        
        return $this->_db->exec($sql);
        
    }
    
    
    public function deleteProductParams($product_id){
       $product_id   = $this->_db->quote($product_id); 
        $sql = "DELETE FROM params_product
                WHERE product_id = $product_id";
        $this->_db->exec($sql);
    }
    
    
    public function updateParamsWhenChangingCategory($new_cat_id,$param_id,$param_value,$product_id){

        if($param_value != ""){
            
        if(!defined('PARAM')) define('PARAM',true);
        $product_id   = $this->_db->quote($product_id);
        $param_value  = $this->_db->quote($param_value);
        
        //узнвем есть ли такое имя параметра в новой категории, если есть вернем ид, нет - false
        $id_p = $this->_checkParamInCat($new_cat_id,$param_id);
        
            if($id_p){//если есть- вставляем в params_product строку с айди параметра новой категории
                $insertId = $id_p["id"];
                $insertId = $this->_db->quote($insertId);

                $sql="INSERT INTO params_product (param_id,param_value,product_id)
                    VALUES ($insertId,$param_value,$product_id)";
                $this->_db->exec($sql);
            }

        }
        

    }
    
 //узнвем есть ли товар на главной странице, если есть true, нет - false    
public function checkMainPage($id){
    $id = $this->_db->quote($id); 
    $sql = "SELECT id
            FROM main_page
            WHERE product_id = $id";
        $stmt = $this->_db->query($sql); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
}  

public function getMainPage($product_id,$main_page,$newproduct=false){
    if(!$newproduct){// если товар не вновь вставляемый
    $check = $this->checkMainPage($product_id);
        if($check and !$main_page){//если товар был на главной, я сейчас нужно убрать
            $product_id = $this->_db->quote($product_id);
            $sql="DELETE FROM main_page
                    WHERE product_id = $product_id
                    LIMIT 1";
            $this->_db->exec($sql);         
            
        }elseif(!$check and $main_page){//если товар не был на главной, я сейчас поставить
            $product_id = $this->_db->quote($product_id);
            $sql="INSERT INTO main_page (product_id)
                        VALUES ($product_id)";
            $this->_db->exec($sql);   
        }
    }else{//если товар новый
            $product_id = $this->_db->quote($product_id);
            $sql="INSERT INTO main_page (product_id)
                        VALUES ($product_id)";
            $this->_db->exec($sql);   
    }
}
  
    
    
//узнвем есть ли такое имя параметра в категории, если есть вернем ид, нет - false    
private function _checkParamInCat($cat_id,$param_id){
    $cat_id = $this->_db->quote($cat_id);
    $param_id = $this->_db->quote($param_id);
    
    $sql = "SELECT id
            FROM params
            WHERE cat_id = $cat_id AND param_name = (SELECT param_name FROM params WHERE id = $param_id)";
        $stmt = $this->_db->query($sql); 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    
}    
    
private function _checkActualParamId($param_id){
    
    ////проверяем актуально ли имя параметра в старой категории, если нет - удаляем строку из params
            $sql = "SELECT COUNT(*)
                    FROM params_product
                    WHERE param_id = $param_id";
            $stmt = $this->_db->query($sql)->fetchColumn(); 
            
            if(!$stmt){
                $sql = "DELETE FROM params
                        WHERE id = $param_id
                        LIMIT 1";
                $this->_db->exec($sql);
            }
    
}
    
//public function addParams($new_cat_id,$param_name,$param_value,$product_id){
//
//        $product_id   = $this->_db->quote($product_id);
//
//        if($param_value != "" AND $param_name != ""){
//            if(!defined('PARAM')) define('PARAM',true);
//            $new_cat_id   = $this->_db->quote($new_cat_id);
//            $param_name   = $this->_db->quote($param_name);
//            $param_value  = $this->_db->quote($param_value);
//
//            //узнвем есть ли такое имя параметра в новой категории, если есть вернем ид, нет - false
//            $insertId = $this->_checkParamInCat($new_cat_id,$param_name);
//
//            if(!$insertId){//если нету - создаем в таблице params новую запись где с новой категорией и с именем параметра, а потом вставляем ид этой записи в params_product
//                $sql="INSERT INTO params(cat_id,param_name)
//                        VALUES ($new_cat_id,$param_name)";
//                $dbh = $this->_db->exec($sql);
//                $param_id = $this->_db->lastInsertId();
//             }else{
//                $param_id = $insertId["id"];
//             }
//            $param_id  = $this->_db->quote($param_id);
//            $sql="INSERT INTO params_product (param_id,param_value,product_id)
//                    VALUES ($param_id,$param_value,$product_id)";
//
//            $this->_db->exec($sql);
//
//
//        }
//    }

    public function addParams($param_id,$param_value,$product_id){

        $product_id   = $this->_db->quote($product_id);
        $param_id   = $this->_db->quote($param_id);
        $param_value  = $this->_db->quote($param_value);

        $sql="INSERT INTO params_product (param_id,param_value,product_id)
                    VALUES ($param_id,$param_value,$product_id)";

        $this->_db->exec($sql);


    }
    
    
    
public function updateParams($param_id,$param_value,$product_id){
        
        $param_id     = $this->_db->quote($param_id);
        $product_id   = $this->_db->quote($product_id);
        
        if($param_value != ""){
        if(!defined('PARAM')) define('PARAM',true);
        $param_value  = $this->_db->quote($param_value);
        
            $sql="UPDATE params_product
                    SET param_value = $param_value
                    WHERE product_id = $product_id
                    AND param_id = $param_id
                    LIMIT 1";
            $this->_db->exec($sql);
            
        }else{
          
          $sql = "DELETE FROM params_product
                    WHERE product_id = $product_id AND param_id = $param_id
                    LIMIT 1";
            $this->_db->exec($sql);
            
            ////проверяем актуально ли имя параметра в категории, если нет - удаляем строку из params
            $this->_checkActualParamId($param_id);
        }
        
}


public function delBuyTogether($product_id){
    $product_id   = $this->_db->quote($product_id);
    $sql = "DELETE FROM buy_together
            WHERE id_product = $product_id";
    return $this->_db->exec($sql);
}  

public function addBuyTogether($product_id,$buy_together){
    $product_id   = $this->_db->quote($product_id);
    foreach($buy_together as $val){
        $id_together_product = $this->_db->quote($val);
        $sql = "INSERT INTO buy_together (id_product,id_together_product)
                VALUES ($product_id,$id_together_product)";
        $this->_db->exec($sql);
    }
} 

public function getBuyTogether($product_id){
    $product_id   = $this->_db->quote($product_id);
    $sql = "SELECT id_together_product
            FROM buy_together
            WHERE id_product = $product_id";
    $stmt = $this->_db->query($sql); 
    $arr =  $stmt->fetchAll(PDO::FETCH_ASSOC);
    $newArr = array();
    foreach($arr as $val){
        $newArr[] = $val["id_together_product"];
    }
    return $newArr;
}

    
public function deleteProduct($product_id){
    $id   = $this->_db->quote($product_id);
    
    //удаляем фотки
    $sql = "SELECT thumb_img,full_img
            FROM product
            WHERE id = $id";
    $stmt = $this->_db->query($sql); 
    $img =  $stmt->fetch(PDO::FETCH_ASSOC); 
    if($img["thumb_img"]!='sm_default.jpg' AND $img["full_img"]!='default.jpg'){//удаляем старые фотки
        @unlink("images/product/".$img["thumb_img"]); 
        @unlink("images/product/".$img["full_img"]);
    }      
    
    //удаляем товар из таблицы product
    $sql = "DELETE FROM product
            WHERE id = $id
            LIMIT 1";
    $this->_db->exec($sql);
    
    //удаляем из таблицы category_product_xref
    $sql = "DELETE FROM category_product_xref
            WHERE product_id = $id
            LIMIT 1";
    $this->_db->exec($sql);
    
    //удаляем рейтинги товара
    $sql = "DELETE FROM rating
            WHERE product_id = $id";
    $this->_db->exec($sql);
    
    //удаляем отзывы о товаре
    $sql = "DELETE FROM comment
            WHERE product_id = $id";
    $this->_db->exec($sql);
    
    //разбираемся с параметрами
    $this->deleteProductParams($product_id);

    //$this->delBuyTogether($product_id);
}


public function addProductTable($name,$sku,$price,$description,$full_img,$title,$keywords,$seo_desc){
        

       
       $name        = $this->_db->quote($name);
       $sku         = $this->_db->quote($sku);
       $price       = $this->_db->quote($price);
       $description = $this->_db->quote($description);
       $full_img    = $this->_db->quote($full_img);
       $title       = $this->_db->quote($title);
       $keywords    = $this->_db->quote($keywords);
       $seo_desc    = $this->_db->quote($seo_desc);


       
       $sql="INSERT INTO product (name,sku,price,shot_desc,full_img,title,keywords,seo_desc)
             VALUES ($name,$sku,$price,$description,$full_img,$title,$keywords,$seo_desc)";
        try{      
        $result = $this->_db->exec($sql); 
        $insertId = $this->_db->lastInsertId();
        } catch (Exception $e){
            
            $resData["success"] = 0;
            $resData["msg"] = "Ошибка базы данных";
            if($e->errorInfo[1] == 1062)
            $resData["msg"] = "Такой артикул уже есть.";
            echo json_encode($resData);
            //var_dump($e->errorInfo);
            exit;
            
        } 
        if($result === false) {
            exit("ERROR");
        }else{
            return $insertId;
        }
                
    }

    public function addProductOfExel($name,$sku,$price,$description,$img,$title){



        $name        = $this->_db->quote($name);
        $sku         = $this->_db->quote($sku);
        $price       = $this->_db->quote($price);
        $description = $this->_db->quote($description);
        $img   = $this->_db->quote($img);
        $title       = $this->_db->quote($title);


        $sql="INSERT INTO product (name,sku,price,shot_desc,full_img,title)
             VALUES ($name,$sku,$price,$description,$img,$title)";
        $resData["product"] = $name;
        try{
            $result = $this->_db->exec($sql);
            $insertId = $this->_db->lastInsertId();
        } catch (Exception $e){


            $resData["msg"] = "Ошибка базы данных";
            if($e->errorInfo[1] == 1062)
                $resData["msg"] = "Такой артикул уже есть.";
            return $resData;


        }
        if($result === false) {
            $resData["msg"] = "Error";
            return $resData;
        }else{
            return $insertId;
        }

    }




    public function delAllProductInCat($catName){
        $CatModel = new CatModel();
        $cat_id = $CatModel->getCatIdByName($catName);

        if($cat_id){
            $this->_delCatAndProductForExel($cat_id);
            return $cat_id;
        }
        return false;

    }
    private function _delCatAndProductForExel($cat_id){
        /// удаляем все товары этой категории
        /// выбираем из таблицы category_product_xref id товаров для удаления
        $id   = $this->_db->quote($cat_id);
        $sql = "SELECT product_id
            FROM category_product_xref
            WHERE category_id = $id";
        $stmt = $this->_db->query($sql);
        $arrProduct =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        /// удаляем товары
        if($arrProduct){
        $strProduct = implode(",",array_map(function($a) {return $a['product_id'];},$arrProduct));
        $sql = "DELETE FROM product
            WHERE id IN (".$strProduct.")";
        $this->_db->exec($sql);

        /// удаляем связи из category_product_xref
        $sql = "DELETE FROM category_product_xref
            WHERE category_id = $id";
        $this->_db->exec($sql);
        }

        ///удаляем категорию, если она не главная
        $sql = "DELETE FROM category
            WHERE id = $id AND parent_id <> 0";
        $this->_db->exec($sql);

        /// нахдим дочерние категории и по рекурсии
        $sql = "SELECT id
        FROM category
            WHERE parent_id = $id";
        $stmt = $this->_db->query($sql);
        $arrCat =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($arrCat) {
            foreach ($arrCat AS $v){
                $this->_delCatAndProductForExel($v['id']);
            }
        }

    }


    
    
}