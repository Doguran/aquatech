<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03.09.19
 * Time: 12:05
 */

class ParsernewsModel {

    protected $_db;

    public function __construct(){
        if(!ADMIN)
        throw new Exception("Нет доступа");
        $this->_db = DBConnect::run();

    }

    public function getParseId(){
        $sql = "SELECT parser_id
        FROM news";
        $stmt = $this->_db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function insertNews($pid,$title,$anons,$data,$post){

        $pid = $this->_db->quote($pid);
        $title = $this->_db->quote($title);
        $anons = $this->_db->quote($anons);
        $data = $this->_db->quote($data);
        $post = $this->_db->quote($post);

        $sql = "INSERT INTO news (parser_id,title,anons,data,txt)
                VALUES ($pid,$title,$anons,$data,$post)";
        $result = $this->_db->exec($sql);

    }

}