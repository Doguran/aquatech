<?php
class AdminedittextModel{
    
    protected $_db;
    
    
    public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        $this->_db = DBConnect::run();
        
    }
    
    public function editContact($phone1,$phone2,$address,$email,$mode,$maps){
        
        $phone1 = $this->_db->quote($phone1);
        $phone2 = $this->_db->quote($phone2);
        $email = $this->_db->quote($email);
        $mode = $this->_db->quote($mode);
        $address = $this->_db->quote($address);
        //$footer = $this->_db->quote($footer);
        $maps = $this->_db->quote($maps);
        
        $sql="UPDATE contact
                SET mode=$mode,
                    address=$address,
                    email=$email,
                    maps=$maps,
                    phone1=$phone1,
                    phone2=$phone2";
        return $this->_db->exec($sql); 
                
    }
    
    public function editText($text,$column){
        
        $text = $this->_db->quote($text);
        
        $sql="UPDATE texts
                SET $column=$text";
                
        return $this->_db->exec($sql); 
                
    }
    
    
    public function editMainText($index_txt1,$index_txt2){
        
        

        $index_txt1 = $this->_db->quote($index_txt1);
        $index_txt2 = $this->_db->quote($index_txt2);
        
        $sql="UPDATE texts
                SET
                    index_txt1 = $index_txt1,
                    index_txt2 = $index_txt2";
                
        return $this->_db->exec($sql); 
                
    }
    
   
    
    
    
 } 