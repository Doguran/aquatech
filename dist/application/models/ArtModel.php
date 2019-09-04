<?php

class ArtModel{
    
    protected $_db;
    
    
    public function __construct(){
        
        $this->_db = DBConnect::run();
        
    }
    
    public function getArt($art_id){
        
        $id = $this->_db->quote($art_id);
                
        $sql = "SELECT id,title,txt,data,anons
                FROM news
                WHERE id = $id and vid = 1";
        $stmt = $this->_db->query($sql); 
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    public function getArtList(){
        

        
        $sql = "SELECT id,title 
                FROM news 
                WHERE vid = 1
                ORDER BY parser_id DESC";
                
        $stmt = $this->_db->query($sql); 
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
       
    public function deleteArticle($id){
        $id = $this->_db->quote($id);
        $sql = "DELETE 
                FROM article 
                WHERE id = $id
                LIMIT 1";
        $stmt = $this->_db->exec($sql); 
    }
    
    public function insertArticle($name,$text,$title,$keywords,$seo_desc) {
        $name     = $this->_db->quote($name);
        $text     = $this->_db->quote($text);
        $title    = $this->_db->quote($title);
        $keywords = $this->_db->quote($keywords);
        $seo_desc = $this->_db->quote($seo_desc);
        
        $sql = "INSERT INTO article (h1,text,title,keywords,description)
                VALUES ($name,$text,$title,$keywords,$seo_desc)";
        
        $result = $this->_db->exec($sql); 
        return $this->_db->lastInsertId();
    }
    
    public function updateArticle($id,$name,$text,$title,$keywords,$seo_desc) {
        $id       = $this->_db->quote($id);
        $name     = $this->_db->quote($name);
        $text     = $this->_db->quote($text);
        $title    = $this->_db->quote($title);
        $keywords = $this->_db->quote($keywords);
        $seo_desc = $this->_db->quote($seo_desc);
        
        $sql="UPDATE article
                SET h1=$name,
                    text=$text,
                    title=$title,
                    keywords=$keywords,
                    description=$seo_desc
                    
              WHERE id=$id";
        return $this->_db->exec($sql);
        
    }
    
    
    
}