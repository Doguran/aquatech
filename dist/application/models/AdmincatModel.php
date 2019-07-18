<?php

class AdmincatModel{
    
    protected $_db;
    protected $_myCat = array();
    protected $_cat_data = array();
    
    
    public function __construct(){
        if(!ADMIN)
        throw new Exception("Нет доступа");
        $this->_db = DBConnect::run();
        
    }
    
    private function _getCatListForAdmin(){
        
        $sql = "SELECT id,parent_id,predok,name
                FROM category
                ORDER BY sort";
        $stmt = $this->_db->query($sql); 
        $this->_myCat =  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
    
    private function _drawCatListForAdmin(&$out, $parent=0, &$level=0){
                    $i=1;
                    foreach($this->_myCat as $row){
                        if($row['parent_id']==$parent){
                          
                          $level++;
                          $trClass = ($level==1)?" class='mainCat'":"";
                          if ($level==1){
                            $trClass = " class='mainCat success'";
                            $input_sort = " <input type='text' class='input-sort input-sm form-control' value='$i' name='id$row[id]'> ";
                            $i++;
                          }else{
                            $trClass = "";
                            $input_sort = "";
                          }
//                          $out.="<tr".$trClass."><td>$input_sort".str_repeat("&nbsp;&nbsp;",$level)."<a href='".HTTP_PATH."category/show/id/$row[id]/'>$row[name]</a></td>
//                                            <td class='admin-link'><a href='".HTTP_PATH."/admincat/delete/id/$row[id]/' onclick=\"return confirm('Удалить можно только пустую категорию. Продолжить?');\"><span class='glyphicon glyphicon-trash'></span></a></td>
//                                            <td class='admin-link'><a href='".HTTP_PATH."/admincat/editcat/id/$row[id]/'><span class='glyphicon glyphicon-edit edit-cat-link' id='edit-cat-$row[id]'></span></a></td></tr>";

                          $out.="<tr".$trClass."><td>$input_sort".str_repeat("&nbsp;&nbsp;",$level)."<a href='".HTTP_PATH."category/show/id/$row[id]/'>$row[name]</a></td>
                                            <td class='admin-link'><a href='".HTTP_PATH."/admincat/delete/id/$row[id]/' onclick=\"return confirm('Удалить можно только пустую категорию. Продолжить?');\"><i class='fas fa-times'></i></a></td>
                                            <td class='admin-link'><a href='".HTTP_PATH."/admincat/editcat/id/$row[id]/'><i class='fas fa-edit' id='edit-cat-$row[id]'></i></a></td></tr>";

                          $inner='';
                          $level++;
                          $this->_drawCatListForAdmin($inner,$row['id'],$level);
                          $level--;
                          
                          if(strlen($inner)>0){
                            $out.= "\n".$inner."\n";
                          }
                          $out.="\n";
                          $level--;
                        }
                      }
                      return $out;
        
    }
    
    public function getDrawCatListForAdmin(){
        
        $this->_getCatListForAdmin();
        $out = '';
        $out = $this->_drawCatListForAdmin($out,0);
       
       return "<form action='/admincat/sortcat/' method='post'>\n
                <table class='table table-hover' id='admin-table-all-cat'>
                $out
               </table>\n<br /><br />
               <input type='submit' class='btn btn-primary' value='Поменять расположение главных категорий'>\n
               </form>";
    }
    
    
    private function _drawCatOptionForAdmin(&$out, $parent=0, &$level=0){
        
        
          foreach($this->_myCat as $row){
            if(empty($this->_cat_data)){
                $sel="";  
            }else{
                if  ($row['id']==$this->_cat_data['id'])continue;
                $sel = ($this->_cat_data['parent_id']==$row['id'])?' selected':'';
            }
            if($row['parent_id']==$parent){
              $level++;
              $optionClass = ($level==1)?"class='mainCat'":"";
              $out.="<option $optionClass value='$row[id]|$row[predok]'$sel>".str_repeat("\t&nbsp;",$level)."$row[name]</option>\n";
              $inner='';
              $level++;
              $this->_drawCatOptionForAdmin($inner,$row['id'],$level);
              $level--;
              if(strlen($inner)>0){
                $out.= "\n".$inner."\n";
              }
              $out.="\n";
              $level--;
            }
          }
          return $out;
        }
    
    public function drawEditForm($cat_id){
        $cat_id = $this->_db->quote($cat_id);
        $sql="SELECT id,parent_id,name,before_text,after_text,title,keywords,seo_desc,img
            FROM category
            WHERE id=$cat_id";
        $stmt = $this->_db->query($sql); 
        $this->_cat_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->_getCatListForAdmin();
        $out = '';
        $out = $this->_drawCatOptionForAdmin($out,0);
        $this->_cat_data['select'] = $out;
        return $this->_cat_data;
              
        
    }
    
    public function drawAddForm(){
        $this->_getCatListForAdmin();
        $out = '';
        $out = $this->_drawCatOptionForAdmin($out,0);
        return $out;
        
        
    }
    
public function editCat($name,$cat_id,$pid,$predok,$before_text,$after_text,$title,$keywords,$seo_desc,$cat_img){
        $name   = $this->_db->quote($name);
        $cat_id = $this->_db->quote($cat_id);
        $pid    = $this->_db->quote($pid);
        $predok = $this->_db->quote($predok);
        $before_text = $this->_db->quote($before_text);
        $after_text = $this->_db->quote($after_text);
        $title = $this->_db->quote($title);
        $keywords = $this->_db->quote($keywords);
        $seo_desc = $this->_db->quote($seo_desc);
        $cat_img = $this->_db->quote($cat_img);
        
        $sql="UPDATE category
                SET parent_id=$pid,
                    predok=$predok,
                    name=$name,
                    before_text=$before_text,
                    after_text=$after_text,
                    title=$title,
                    keywords=$keywords,
                    seo_desc=$seo_desc,
                    img = $cat_img
                WHERE id=$cat_id";
        $res = $this->_db->exec($sql);
        
        if($res){
            //Ищем детей изменяемой категории для передачи им нового предка
            $sql="SELECT id 
                    FROM category
                    WHERE parent_id=$cat_id";
            $stmt = $this->_db->query($sql); 
            $arr  =  $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($arr)){
                foreach($arr as $val){
                    $id = $this->_db->quote($val["id"]);  
                    $sql = "UPDATE category
                                SET predok = $predok 
                                WHERE id=$id";
                    $this->_db->exec($sql);
                }
            }

        }
        return $res;


}


public function addCat($name,$pid,$predok,$before_text,$after_text,$title,$keywords,$seo_desc,$cat_img){
        $name   = $this->_db->quote($name);
        $pid    = $this->_db->quote($pid);
        $predok = $this->_db->quote($predok);
        $before_text = $this->_db->quote($before_text);
        $after_text = $this->_db->quote($after_text);
        $title = $this->_db->quote($title);
        $keywords = $this->_db->quote($keywords);
        $seo_desc = $this->_db->quote($seo_desc);
        $cat_img = $this->_db->quote($cat_img);
        $sql="INSERT INTO category (parent_id,predok,name,before_text,after_text,title,keywords,seo_desc,img)
                VALUES ($pid,$predok,$name,$before_text,$after_text,$title,$keywords,$seo_desc,$cat_img)";

        if($this->_db->exec($sql)){
            return $this->_db->lastInsertId();
        }else{
            return false;
        }

}


 public function delete($id){
    $id = $this->_db->quote($id);
    // Проверяем на наличие дочерних категорий.
    $sql = "SELECT COUNT(*)
            FROM category
            WHERE parent_id = $id";
    $stmt = $this->_db->query($sql)->fetchColumn(); 
    if ($stmt) return false;
    
    // Проверяем на наличие товаров.
    $sql = "SELECT COUNT(*)
            FROM product
            INNER JOIN category_product_xref
    	       ON product.id = category_product_xref.product_id
            INNER JOIN category
    	       ON category.id = category_product_xref.category_id 
            WHERE category.id = $id";
    $stmt = $this->_db->query($sql)->fetchColumn(); 
    if ($stmt) return false;
    
    // удвляем
    $sql = "DELETE FROM category 
            WHERE id =$id
            LIMIT 1";
    $res = $this->_db->exec($sql);
    if (!$res) return false;
    return true;
}

public function updateSort($sort,$id,$table="category"){
    $sort = $this->_db->quote($sort);
    $id   = $this->_db->quote($id);
    $sql = "UPDATE $table 
                SET sort=$sort 
                WHERE id=$id"; 
   return $this->_db->exec($sql);  
}
public function updateMainSort($sort,$id){
    $sort = $this->_db->quote($sort);
    $id   = $this->_db->quote($id);
    $sql = "UPDATE main_page 
                SET sort=$sort 
                WHERE product_id=$id"; 
   return $this->_db->exec($sql);  
}


    public function getCatParam($cat_id){
        $cat_id = $this->_db->quote($cat_id);
        $sql="SELECT id,param_name
            FROM params
            WHERE cat_id=$cat_id";
        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function delParametr($id){

        $id = $this->_db->quote($id);

        $sql = "DELETE FROM params
                WHERE id =$id
                LIMIT 1";
        $res = $this->_db->exec($sql);
        if($res){
            $sql = "DELETE FROM params_product
                    WHERE param_id =$id";
            $this->_db->exec($sql);

        }
    }

    public function addParams($cat_id,$array_parametr_name){
        $stmt = $this->_db->prepare('INSERT INTO params (cat_id,param_name)
                                              VALUES (?, ?)');
        foreach ($array_parametr_name as $k=>$v) {
            $stmt->execute(array($cat_id,$v));
        }
    }
    
    
    
    
}