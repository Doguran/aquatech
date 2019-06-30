<?php
class ValuteController implements IController {
    
    public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        
    }
    
    public function indexAction() {
		$fc = FrontController::getInstance();
        
        $model = new FileModel();
        
        
        //узнаем курс ЦБ
        $url = "http://www.cbr.ru/scripts/XML_daily.asp";
        $xml = file_get_contents($url);
        
        //if($xml === false) exit("ошибочка");

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
        
        $model->dollar = $dollar;
        $model->evro = $evro;
        
        //узнаем курсы валют магазина
        $valuta_arr = file("valuta.data",FILE_IGNORE_NEW_LINES);
        $model->shop_dollar = $valuta_arr[0];
        $model->shop_evro = $valuta_arr[1];


        $output = $model->render("valute.tpl.php");
		
		$fc->setBody($output);
    }
    

    public function exchangeAction() {
        
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            $dollar = isset($_POST["dollar"]) ? str_replace(",",".",$_POST["dollar"]) : "";
            if($dollar=='' or !is_numeric($dollar)) {$errors[] = "Неккоректный курс доллара"; }
            
            $evro = isset($_POST["evro"]) ? str_replace(",",".",$_POST["evro"]) : "";
            if($evro=='' or !is_numeric($evro)) {$errors[] = "Неккоректный курс евро"; }
            
            
            if(empty($errors)){ //если ошибок нет
            
                 //пишем в файл
                 $res = file_put_contents('valuta.data', $dollar.PHP_EOL.$evro, LOCK_EX);
                 
                 //обновляем цены в базе
                 $ValuteModel = new ValuteModel();
                 $ValuteModel->updatePrice($dollar,$evro);
                 
//                 //обновляем YML
//                 $Yml = new YmlController();
//                 $Yml->indexAction();
                 
                 if($res)
                 header("Location: /valute/?ok=".urlencode("Курс магазина обновлен!"));
                 else
                 header("Location: /valute/?er=".urlencode("Ошибка записи в файл!"));
                 
            }else{
                $str = implode("<br />", $errors);
                header("Location: /valute/?er=".urlencode($str));
            }
            
       }
       
       
	   
       
    }

    
    
    
    
    
}
