<?php
class CatModel{

    protected $_db;
    protected $_catArray;
    protected $_menu = '';
    public $indexCatId;


    public function __construct(){

        $this->_db = DBConnect::run();

    }

    public function getCatListForIndex(){

        foreach ($this->_getCatList() as $key=>$val){
            $this->_menu .= "<li class='nav-item'>\n";
            if($key == 0){
                $this->indexCatId = $val["id"];
                $this->_menu .= "<span class='nav-link active'>$val[name]</span><span class='sr-only'>(current)</span>\n";
            }else{
                $this->_menu .= "<a class='nav-link' href='http://$_SERVER[HTTP_HOST]/category/show/id/$val[id]/'>$val[name]</a>\n";
            }
            $this->_menu .= "</li>\n";
        }
        return $this->_menu;
        
    }

    public function getCatListForCatPage($cat_id){

        foreach ($this->_getCatList() as $key=>$val){
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

    private function _getCatList(){

        $sql = "SELECT id,name,img
                FROM category
                WHERE parent_id = 0
                ORDER BY sort";
        $stmt = $this->_db->query($sql);
        return $this->_catArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getIndexCatId(){
        return $this->_catArray[0]["id"];
    }

    public function getCatName($cat_id){
        $cat_id  = $this->_db->quote($cat_id);
        $sql = "SELECT name
                FROM category
                WHERE id = $cat_id";
        $stmt = $this->_db->query($sql);
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }


    
    
    
 } 