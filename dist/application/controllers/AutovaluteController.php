<?php
class AutovaluteController implements IController {
    

    
    public function indexAction() {

        
        
        //узнаем курс ЦБ
        $url = "http://www.cbr.ru/scripts/XML_daily.asp";
        $xml = file_get_contents($url);
        
        if($xml === false) exit("ошибочка");

        $feed = simplexml_load_string($xml);
        
        foreach($feed as $valute){
            
            //Helper::print_arr($valute);
            if($valute['ID'] == 'R01235')
            $dollar = $valute->Value;
            
            if($valute['ID'] == 'R01239')
            $evro = $valute->Value;
            
            
        }
        $dollar = str_replace(",",".",$dollar);
        $evro = str_replace(",",".",$evro);

        //пишем в файл
        $res = file_put_contents('valuta.data', $dollar.PHP_EOL.$evro, LOCK_EX);

        

    }
    



    
    
    
    
    
}
