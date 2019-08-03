<?php
class CatModel{

    protected $_cat_id, $_product_id, $_predok, $_cat_name, $_menu, $cat_list, $_db;
    protected $_myCat = array();
    protected $_mainCatProduct = array();
    protected $_bread = array();
    public $indexCatId;


    public function __construct(){

        $this->_db = DBConnect::run();

    }

    public function getCatListForIndex(){
        if(empty($this->_myCat)) $this->_getCatList();
        //Helper::print_arr($this->_myCat); exit;
        $i = 0;
        foreach ($this->_myCat as $key=>$val){

            if($val["parent_id"] != 0) continue;

            $this->_menu .= "<li class='nav-item'>\n";
            if($i == 0){
                $this->indexCatId = $val["id"];
                $this->_menu .= "<span class='nav-link active'>$val[name]</span><span class='sr-only'>(current)</span>\n";
            }else{
                $this->_menu .= "<a class='nav-link' href='http://$_SERVER[HTTP_HOST]/category/show/id/$val[id]/'>$val[name]</a>\n";
            }
            $this->_menu .= "</li>\n";
            $i++;
        }
        return $this->_menu;

    }

    public function getCatListForCatPage($cat_id){
        if(empty($this->_myCat)) $this->_getCatList();

        foreach ($this->_myCat as $key=>$val){
            if($val["parent_id"] != 0) continue;
            $this->_menu .= "<li class='nav-item'>\n";
            if($val["id"] == $cat_id){
                $this->_menu .= "<span class='nav-link active'>$val[name]</span><span class='sr-only'>(current)</span>\n";
            }else{
                $this->_menu .= "<a class='nav-link' href='http://$_SERVER[HTTP_HOST]/category/show/id/$val[id]/'>$val[name]</a>\n";
            }
            $this->_menu .= "</li>\n";
        }
        return $this->_menu;

    }

    private function _drawCatMenu(&$out, $parent=0, &$level=0){

        if(empty($this->_myCat)) $this->_getCatList();

        foreach($this->_myCat as $row){
            if($row['parent_id']==$parent){
                $sel=($row['id']==$this->indexCatId)?' class="sel"':'';
                $level++;


                if($this->_predok==$row["id"] or $row['id']==$this->indexCatId){
                    $classtitle = "but_title_do";
                    $class = "slidemenuDown";
                }else{
                    $classtitle = "but_title";
                    $class = "slidemenuUp";
                }

                if($level==1){
                    $pagesMaiCat = "";
                    foreach ($this->_mainCatProduct as $v){
                        if($v["cat_id"]!=$row['id'])continue;
                        $selPage=($v["product_id"]==$this->_product_id)?' class="sel"':'';
                        $pagesMaiCat .= sprintf('%s<li%s><a href="/product/%d/"%s>%s</a></li>%s',
                          str_repeat("\t",3),$selPage,$v['product_id'],$selPage,$v['product_name'],"\n");
                    }
                    if($pagesMaiCat != "")$pagesMaiCat=str_repeat("\t",$level)."<ul>\n$pagesMaiCat".str_repeat("\t",2)."</ul>";

                    $out.="
        			<span class='$classtitle'>$row[name]</span>
                <menu class='$class nav-catalog'>";

                }else


                    $out.=sprintf('%s<li%s><a href="/category/show/id/%d/"%s>%s</a>',
                      str_repeat("\t",$level),$sel,$row['id'],$sel,$row['name']);

                $inner='';
                $level++;
                $this->_drawCatMenu($inner,$row['id'],$level);
                $level--;
                if(strlen($inner)>0){
                    $out.=sprintf('%s%s<ul>%s%s%s</ul>%s%s',
                      "\n",str_repeat("\t",$level+1),"\n",$inner,str_repeat("\t",$level+1),"\n",str_repeat("\t",$level));
                }
                if($level==1)

                    $out.="$pagesMaiCat</menu>\n";
                else
                    $out.="</li>\n";
                $level--;
            }
        }

        $this->_menu = $out;

    }

    private function _drawCatOption($id, &$out, $parent=0, &$level=0){


        foreach($this->_myCat as $row){
            if($row['parent_id']==$parent){
                $sel=($row['id']==$id)?' selected':'';
                $level++;
                $optionClass = ($level==1)?"class='mainCat'":"";
                $out.="<option $optionClass value='$row[id]'$sel>".str_repeat("\t&nbsp;",$level)."$row[name]</option>";
                /*$out.=sprintf('%s<li><a href="http://'.$_SERVER['HTTP_HOST'].'/category/show/id/%d/">%s</a>',
                  str_repeat("\t",$level),$row['id'],$row['name']);*/
                $inner='';
                $level++;
                $this->_drawCatOption($id,$inner,$row['id'],$level);
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

    private function _drawCatOptionForAdd(&$out, $parent=0, &$level=0){
        if(empty($this->_myCat)) $this->_getCatList();

        foreach($this->_myCat as $row){
            if($row['parent_id']==$parent){
                $level++;
                $optionClass = ($level==1)?"class='mainCat'":"";
                $out.="<option $optionClass value='$row[id]'>".str_repeat("\t&nbsp;",$level)."$row[name]</option>\n";
                $inner='';
                $level++;
                $this->_drawCatOptionForAdd($inner,$row['id'],$level);
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

//    private function _getCat(){
//        $sql = "SELECT id,parent_id,predok,name
//                FROM category
//                ORDER BY sort";
//        $stmt = $this->_db->query($sql);
//        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }

    private function _getCatList(){



        $sql = "SELECT id,parent_id,predok,name,img
                FROM category
                ORDER BY sort";
        $stmt = $this->_db->query($sql);
        $this->_myCat = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT category.id AS cat_id,
                product.id AS product_id,
                product.name AS product_name
                FROM product
                INNER JOIN category_product_xref
        	       ON product.id = category_product_xref.product_id
                INNER JOIN category
        	       ON category.id = category_product_xref.category_id 
       
                WHERE category.parent_id = 0
                ORDER BY product.id";

        $stmt = $this->_db->query($sql);
        $this->_db->query('SET SQL_BIG_SELECTS=1');
        $this->_mainCatProduct =  $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getIndexCatId(){
        return $this->_myCat[0]["id"];
    }

    public function getCatName($cat_id){
        $cat_id  = $this->_db->quote($cat_id);
        $sql = "SELECT name
                FROM category
                WHERE id = $cat_id";
        $stmt = $this->_db->query($sql);
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPredok($cat_id){
        $cat_id  = $this->_db->quote($cat_id);
        $sql = "SELECT predok
                FROM category
                WHERE id = $cat_id";
        $stmt = $this->_db->query($sql);
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $out = '';
        $this->_drawCatMenu($out,0);

        return $this->_menu;
    }

    public function getCatOptionForAdd() {
        $out="";
        return $this->_drawCatOptionForAdd($out,0);
    }
    public function getCatOption($cat_id) {
        $out = '';
        return $this->_drawCatOption($cat_id,$out,0);
    }

    public function getCatIdByName($catName){
        $catName  = $this->_db->quote($catName);
        $sql = "SELECT id
                FROM category
                WHERE name = $catName";
        $stmt = $this->_db->query($sql);
        return  $stmt->fetchColumn();

    }





}