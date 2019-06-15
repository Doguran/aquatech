<?php

class AuthModel{
    
    protected $_db;
    
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }
    
    public function authUser($email,$pass){
        
        $email = $this->_db->quote($email);
        $pass  = $this->_db->quote($pass);
        
        $sql = "SELECT id,name,status 
                FROM customer 
                WHERE email=$email
                AND pass=$pass";
        $stmt = $this->_db->query($sql); 
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function editPass($new_pass){
        
        $new_pass = $this->_db->quote($new_pass);
        $id  = $this->_db->quote($_SESSION["user"]["id"]);
        
        $sql = "UPDATE customer
                SET pass = $new_pass
                WHERE id = $id
                LIMIT 1";
        return $this->_db->exec($sql); 
        
    }
    
    
    
    
    
    
}