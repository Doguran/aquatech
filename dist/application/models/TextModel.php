<?php
class TextModel{
    
    protected $_db;
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }

    public static function getStaticContact(){
        $sql="SELECT mode,address,email,phone1,phone2,phone3,footer,maps
            FROM contact";
        $stmt = DBConnect::run()->query($sql);
        return  $stmt->fetch(PDO::FETCH_ASSOC);

    }


    public function getContact(){
    $sql="SELECT mode,address,email,phone1,phone2,phone3,footer,contact_text,maps
            FROM contact";
    $stmt = $this->_db->query($sql); 
    return  $stmt->fetch(PDO::FETCH_ASSOC);        
    
   }  
   
   public function getContactText(){
    $sql="SELECT contact_text
            FROM contact";
    $stmt = $this->_db->query($sql); 
    return  $stmt->fetch(PDO::FETCH_ASSOC);        
    
   }
   
   public function getText($column){
    $sql="SELECT $column
            FROM texts";
    $stmt = $this->_db->query($sql); 
    return  $stmt->fetch(PDO::FETCH_ASSOC);        
    
   }
   
   
   public function getAdminMail(){
    $sql="SELECT email
    FROM contact";
    $stmt = $this->_db->query($sql); 
    $email  = $stmt->fetch(PDO::FETCH_ASSOC); 
    return $email["email"];
   }
      
        
}
    
    
 