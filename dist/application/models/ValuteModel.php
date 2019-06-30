<?php
class ValuteModel{
    
    protected $_db;
    
    public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        
        $this->_db = DBConnect::run();
        
    }
    
    //Обновить весь прайс
    public function updatePrice($dollar_rate,$evro_rate) { 
        
        
        //сначала доллары достаем из таблицы valuta ид товаров и цену в долларах, если она не равна нулю
        $sql = "SELECT product_id,dollar,old_dollar 
                FROM valuta 
                WHERE dollar > 0";
        
        
        //подготавливаем запрос
        $stmt = $this->_db->prepare('UPDATE product
                                SET price =  ?,
                                old_price = ?
                                WHERE id = ?');
                                
        foreach($this->_db->query($sql) as $val) {//обновляем цены таблицы продуктов
        
            $price = round($val["dollar"] * $dollar_rate, -1); 
            $old_price = round($val["old_dollar"] * $dollar_rate, -1);
            
            $stmt->execute(array($price,$old_price,$val["product_id"]));
            
            
        }
        
        //теперь евро достаем из таблицы valuta ид товаров и цену в евро, если она не равна нулю
        $sql = "SELECT product_id,evro,old_evro 
                FROM valuta 
                WHERE evro > 0";
        
        foreach($this->_db->query($sql) as $val) {//обновляем цены таблицы продуктов
        
            $price = round($val["evro"] * $evro_rate, -1); 
            $old_price = round($val["old_evro"] * $evro_rate, -1);
            
            $stmt->execute(array($price,$old_price,$val["product_id"]));
        }
        
    }
    
    //Вставить одну запись в таблицу valuta
    public function insertValute($product_id,$price,$old_price,$valuta) {
        
        $product_id = $this->_db->quote($product_id);
        $price = $this->_db->quote($price);
        $old_price = $this->_db->quote($old_price);
        
        if($valuta == "D"){
            $col = "dollar,old_dollar,evro,old_evro";
        }elseif ($valuta == "E"){
            $col = "evro,old_evro,dollar,old_dollar";
        }
        $sql = "INSERT INTO valuta (product_id,$col) VALUES($product_id,$price,$old_price,0,0)";
        return $this->_db->exec($sql);
        
    }
    
    //Обновляем цену одного товара в талице product
    public function updateOnePrice($product_id,$price,$old_price,$valuta) {
        
        //Узнаем курс магазина
        $valuta_arr = file("valuta.data",FILE_IGNORE_NEW_LINES);
        //$valuta_arr[0] - курс доллара
        //$valuta_arr[1] - курс евро
        if ($valuta == "D"){
            $price = $price * $valuta_arr[0];
            $old_price = $old_price * $valuta_arr[0];
        }else{
            $price = $price * $valuta_arr[1];
            $old_price = $old_price * $valuta_arr[1];
        }
        $product_id = $this->_db->quote($product_id);
        $price = $this->_db->quote($price);
        $old_price = $this->_db->quote($old_price);
        $sql = "UPDATE product
                        SET price =  $price,
                        old_price = $old_price
                        WHERE id = $product_id";
        return $this->_db->exec($sql);
        
        
    }
    
    
    //смотрим в какой валюте товар, если рубли, то возвращаем false, если в другой, то возвращаем массив со значениями
    public function viewValuta($product_id) {
        
        $product_id = $this->_db->quote($product_id);
        $sql = "SELECT dollar,
                       old_dollar,
                       evro,
                       old_evro
                FROM valuta
                WHERE product_id = $product_id";
        $stmt = $this->_db->query($sql); 
        return  $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    
    //удаляем строчку из таблицы валют
    public function deleteValuta($product_id) {
        $product_id = $this->_db->quote($product_id);
        $sql = "DELETE FROM valuta
                WHERE product_id = $product_id";
        return $this->_db->exec($sql);
        
    }
    
    
    
  
        
        
        
}
    
    
 