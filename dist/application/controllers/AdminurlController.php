<?php
class AdminurlController implements IController {

    protected $_db;
    
	public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        $this->_db = DBConnect::run();
        
    }
    
    public function indexAction() {

        $sql = "SELECT id,name 
                FROM category";

        $stmt = $this->_db->query($sql);
        $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arr AS $val){

            $this->addUrl($val["id"],Helper::getChpu($val["name"]));

        }
		
	}

    public function addUrl($id,$url){
        $id = $this->_db->quote($id);
        $url = $this->_db->quote($url);
        $sql="UPDATE category
                SET url=$url
              WHERE id=$id";
        $this->_db->exec($sql);
    }
    
       

    
    
}
