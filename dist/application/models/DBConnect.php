<?php

class DBConnect {
        private static $_instance=null;
        private function __construct() {} 
        private function __clone() {}
         
        public static function run() {
             
            if (!isset(self::$_instance)) {
                try {
                    self::$_instance = new PDO(DB_CONN, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                } catch (PDOException $e) {
                    
                    exit("Ошибка соединения с базой данных");
                } 
            }
            return self::$_instance;    
        }
 
        final public function __destruct() {
            self::$_instance = null;
        } 
}