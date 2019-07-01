<?php
class AdmindetailController implements IController {
    
	public function __construct(){
        
        if(!ADMIN)
        throw new Exception("Нет доступа");
        
    }
    
    public function indexAction() {
        
        throw new Exception("Нет параметров");
		
	}
    
       
    public function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["id"])){
            
        $product_id = abs((int)$params["id"]); 
                   
        $model = new FileModel();
        $product_model = new ProductModel();
        $catModel = new CatModel();
        
        $product = $product_model->getProduct($product_id);
        
        //$AdmindetailModel = new AdmindetailModel();
        //$model->main_page = $AdmindetailModel->checkMainPage($product_id) ? " checked" : "";
        //$model->parametrs = $AdmindetailModel->drawParametrForAdminDetail($product["cat_id"],$product["id"]);
        //$model->parametrs = $AdmindetailModel->getParametrForDetailEdit($product["cat_id"],$product_id);
        
        //$model->bread = $catModel->getBreadCrumbs_product($product["cat_id"])." / <span>".$product["name"]."</span>";//перед списком категорийй вызываем именно это!
        $model->categories = $catModel->getCategories();
        //$model->catOption = $catModel->getCatOption($product["cat_id"]);
        $model->catOption = $catModel->getCatOption($product["cat_id"]);

            $model->id = $product["id"];
        $model->name = $product["name"];
        $model->sku = $product["sku"];
        $model->price = $product["price"];
        $model->old_price = $product["old_price"]>0 ? $product["old_price"] : "";
        $model->description = $product["shot_desc"];
        $model->thumb_img = $product["thumb_img"];
        $model->full_img = $product["full_img"];
        $model->cat_id = $product["cat_id"];
        //$model->compare = $product["compare"];
        $model->title = $product["title"];
        $model->keywords = $product["keywords"];
        $model->seo_desc = $product["seo_desc"];
        //$model->new  = $product["new"] ? " checked" : "";
        //$model->liders  = $product["liders"] ? " checked" : "";
        //$model->sale  = $product["sale"] ? " checked" : "";
        $model->complete  = $product["complete"];
        //$model->model  = $product["model"];
        //$model->yandex_cat  = $product["yandex_cat"];
        //$model->garant  = $product["garant"];
        //$model->valuta  = $product["valuta"];
        //$model->promo  = $product["promo"];
        $model->action  = "edit";
        
        
        //$ProductArrModel = new ProductArrModel();
        //$model->allProduct =$ProductArrModel->getAllProduct();
        
        //$model->sel_buy_together = $AdmindetailModel->getBuyTogether($product_id);

        //валютные махинации
        $ValuteModel = new ValuteModel();
        $valuteArr = $ValuteModel->viewValuta($product_id);
        if($valuteArr){
            if($valuteArr["evro"] > 0){
                $model->price = $valuteArr["evro"];
                $model->old_price = $valuteArr["old_evro"]>0 ? $valuteArr["old_evro"] : "";
                $model->valuta = "E";

            }else{
                $model->price = $valuteArr["dollar"];
                $model->old_price = $valuteArr["old_dollar"]>0 ? $valuteArr["old_dollar"] : "";
                $model->valuta = "D";
            }
        }else{
            $model->valuta = "R";
        }
        
        $model->cartAll = CartController::countCart();
        $model->findtoCart = CartController::findtoCart($product["id"]);
        
        //$textModel = new TextModel();
        //$model->contact = $textModel->getContact();
        
        //выводим все
		$output = $model->render("admindetail.tpl.php");
        $fc->setBody($output);
            
        }else{
            throw new Exception("Нет параметров");
        }
        
	}
    
    public function editAction() {
        //Helper::print_arr($_POST);
        $resData = array();
                
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            //инициализация пришедших переменных
            $name        = Helper::clearData($_POST['name']);
            $sku         = Helper::clearData($_POST['sku']);
            $price       = Helper::clearData($_POST['price'],"i");
            $old_price   = Helper::clearData($_POST['old_price'],"i");
            $description = Helper::clearData($_POST['description'],"html");
            //$promo       = Helper::clearData($_POST['promo'],"html");
            $thumb_img   = Helper::clearData($_POST['thumb_img']);
            $full_img    = Helper::clearData($_POST['full_img']);
            $old_cat_id  = Helper::clearData($_POST['cat_id'],"i");
            $new_cat_id  = Helper::clearData($_POST['new_cat_id'],"i");
            $product_id  = Helper::clearData($_POST['product_id'],"i");
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);
            $complete    = Helper::clearData($_POST['complete']);
            //$model       = Helper::clearData($_POST['model']);
            //$yandex_cat  = Helper::clearData($_POST['yandex_cat']);
            //$garant      = Helper::clearData($_POST['garant']);
            $valuta      = Helper::clearData($_POST['valuta']);
            if($title == "") $title = $name;


            
            //проверка на ошибки
            $error = array();
            if(!$name)       $error[] = "Не указанно название товара";
            if(!$sku)        $error[] = "Не указан артикул товара";
            if(!$old_cat_id) $error[] = "Ошибка old_cat_id";
            if(!$new_cat_id) $error[] = "Ошибка new_cat_id";
            if(!$product_id) $error[] = "Ошибка product_id";
            
            if(empty($error)){//если ошибок нет
                try{ 
                
                  if ($_FILES['photo']['size']>0){
                        //если есть новая фотка
                    $new_full_img =  Helper::uploadimg("images/product/");
                    if(!$new_full_img){throw new Exception('Ошибка загрузки изображения. Возможно файл слишком большой'); }
                    $new_thumb_img = Helper::create_small_copy($new_full_img,150,150,"images/product/","sm_" ) ? "sm_".$new_full_img : "sm_default.jpg";
                    }else{
                        $new_thumb_img = $thumb_img;
                        $new_full_img  = $full_img;
                    }
                    
                  $AdmindetailModel = new AdmindetailModel();
                  //$AdmindetailModel->getMainPage($product_id,$main_page);

                  //$AdmindetailModel->deleteProductParams($product_id);//удаляем параметры в любом случае!
                    
                  if($old_cat_id != $new_cat_id){//если категорию надо сменить
                    $AdmindetailModel->updateProductCategory($product_id,$new_cat_id);

//                    if($parametrs){
//                       foreach($parametrs as $k=>$v) {
//                           if($v == "") continue;
//                        $AdmindetailModel->updateParamsWhenChangingCategory($new_cat_id,$k,$v,$product_id);
//                       }
//                    }
                    
                  }else{//если категорию НЕ надо сменить
//                      if($parametrs){
//                           foreach($parametrs as $k=>$v) {
//                            $p = explode("|",$k);
//                            $AdmindetailModel->updateParams($p[0],$v,$product_id);
//                           }
//                      }
//                      if($parametrs){
//                          foreach ($parametrs as $k=>$v) {
//                              if($v == "") continue;
//                              $AdmindetailModel->addParams($k,$v,$product_id);
//                          }
//                      }
                 } 
//                  if($add_parametr){
//                        foreach($add_parametr as $k=>$v) {
//                        $p = explode("|",$k);
//                        $AdmindetailModel->addParams($new_cat_id,$p[1],$v,$product_id);
//                       }
//                  }
//                  if($new_parametr_name AND $new_parametr_val){
//                    $param = array_combine($new_parametr_name, $new_parametr_val);
//                    if($param){
//                       foreach ($param as $k=>$v) {
//                        $AdmindetailModel->addParams($new_cat_id,$k,$v,$product_id);
//                       }
//                    }
//                  }
                    
                    
                
                  
                  $result = $AdmindetailModel->updateProductTable($product_id,$name,$sku,$price,$old_price,$description,$new_thumb_img,$new_full_img,$title,$keywords,$seo_desc,$complete);

                    //валютные махинации
                    $ValuteModel = new ValuteModel();
                    $ValuteModel->deleteValuta($product_id);//удаляем строку из таблицы валют в любом случае!
                    if($valuta != "R"){//если не рубли
                        //вставляем данные в таблицу valuta
                        $ValuteModel->insertValute($product_id,$price,$old_price,$valuta);
                        //обновляем цену продукта
                        $ValuteModel->updateOnePrice($product_id,$price,$old_price,$valuta);
                    }

                    if ($_FILES['photo']['size']>0){
                    if($thumb_img!='sm_default.jpg' AND $full_img!='default.jpg'){//удаляем старые фотки
                            @unlink("images/product/".$thumb_img); 
                            @unlink("images/product/".$full_img);
                        }
                  }

                    $CatModel = new CatModel();
                    $predok_cat_id = $CatModel->getPredok($new_cat_id);
                  
                  
//                  $AdmindetailModel->delBuyTogether($product_id);
//                  if($buy_together){
//                    $AdmindetailModel->addBuyTogether($product_id,$buy_together);
//                  }
                      
                $resData["success"] = 1;
                $resData["cat"] = $new_cat_id;
                $resData["predok"] = $predok_cat_id["predok"];
                   
                                      
                }catch(Exception $e){
                    $resData["success"] = 0;
                    $resData["msg"] = $e->getMessage();
                    
                }
                
            }else{//если ошибки есть
                
                $resData["success"] = 0;
                $resData["msg"] = "";
                foreach($error as $val){
                    $resData["msg"] .= $val."<br />";
                }
                
            }
        }else{
           $resData["success"] = 0; 
           $resData["msg"] = "Нет данных";
        }
	   
        
     echo json_encode($resData);
        
    }
    
    
    public function deleteAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["id"])){
            
        $product_id = abs((int)$params["id"]); 
        $cat_id = abs((int)$params["cat"]);
        $CatModel = new CatModel();
        $predok_cat_id = $CatModel->getPredok($cat_id);
        header("Location: /category/show/id/$predok_cat_id[predok]/#table$cat_id");
        $AdmindetailModel = new AdmindetailModel();
        $AdmindetailModel->deleteProduct($product_id);
        
        }else{
            throw new Exception("Нет параметров");
        }
        
	}  
    
    public function addAction() {
        $fc = FrontController::getInstance();
        
  		$model = new FileModel();
        
		$catModel = new CatModel();
        $model->categories = $catModel->getCategories();
        $model->catOption = $catModel->getCatOptionForAdd(); 
        
        //$ProductArrModel = new ProductArrModel();
        //$model->allProduct =$ProductArrModel->getAllProduct();
                
        $model->cartAll = CartController::countCart();
        $model->name = null;
        $model->sku = null;
        $model->price = null;
        $model->old_price = null;
        $model->description = null;
        $model->thumb_img = "sm_default.jpg";
        $model->full_img = "default.jpg";
        $model->title = null;
        $model->keywords = null;
        $model->seo_desc = null;
        $model->complete  = null;
        $model->valuta  = "R";
        $model->action  = "insert";
        $model->id = null;
        $model->cat_id = null;

//        $textModel = new TextModel();
//        $model->contact = $textModel->getContact();
        
        //выводим все
		$output = $model->render("admindetail.tpl.php");
		
		$fc->setBody($output);
    }

    public function insertAction() {
        //Helper::print_arr($_POST);
        $resData = array();

        if($_SERVER["REQUEST_METHOD"]=='POST'){
            //инициализация пришедших переменных
            $name        = Helper::clearData($_POST['name']);
            $sku         = Helper::clearData($_POST['sku']);
            $price       = Helper::clearData($_POST['price'],"i");
            $old_price   = Helper::clearData($_POST['old_price'],"i");
            $complete    = Helper::clearData($_POST['complete']);
            $description = Helper::clearData($_POST['description']);
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);
            $new_cat_id  = Helper::clearData($_POST['new_cat_id'],"i");
            $valuta      = Helper::clearData($_POST['valuta']);
//            $url         = Helper::clearData($_POST['url']);
//            $url = !$url ? Helper::getChpu($name) : Helper::getChpu($url);
            if($title == "") $title = $name;

            //$parametrs = isset($_POST['parametrs']) ? $_POST['parametrs'] : false;

            //проверка на ошибки
            $error = array();
            if(!$name)       $error[] = "Не указанно название товара";
            if(!$sku)        $error[] = "Не указан артикул товара";

            if(empty($error)){//если ошибок нет
                try{

                    if ($_FILES['photo']['size']>0){
                        //если есть новая фотка
                        $new_full_img =  Helper::uploadimg("images/product/");
                        if(!$new_full_img){throw new Exception('Ошибка загрузки изображения. Возможно файл слишком большой'); }
                        $new_thumb_img = Helper::create_small_copy( $new_full_img,150,150,"images/product/","sm_" ) ? "sm_".$new_full_img : "sm_default.jpg";
                    }else{
                        $new_thumb_img = 'sm_default.jpg';
                        $new_full_img  = 'default.jpg';
                    }


                    $AdmindetailModel = new AdmindetailModel();
                    $product_id = $AdmindetailModel->addProductTable($name,$sku,$price,$old_price,$description,$new_thumb_img,$new_full_img,$title,$keywords,$seo_desc,$complete);

                    if($valuta != "R"){//если не рубли
                        //вставляем данные в таблицу valuta
                        $ValuteModel = new ValuteModel();
                        $ValuteModel->insertValute($product_id,$price,$old_price,$valuta);

                        //обновляем цену продукта
                        $ValuteModel->updateOnePrice($product_id,$price,$old_price,$valuta);

                    }


                    $AdmindetailModel->insertProductCategory($product_id,$new_cat_id);

                    $CatModel = new CatModel();
                    $predok_cat_id = $CatModel->getPredok($new_cat_id);



//                    if($parametrs){
//                        foreach ($parametrs as $k=>$v) {
//                            if($v == "") continue;
//                            $AdmindetailModel->addParams($k,$v,$product_id);
//                        }
//                    }



                    $resData["success"] = 1;
                    $resData["id"] = $product_id;
                    $resData["cat"] = $new_cat_id;
                    $resData["predok"] = $predok_cat_id["predok"];

                }catch(Exception $e){
                    $resData["success"] = 0;
                    $resData["msg"] = $e->getMessage();

                }

            }else{//если ошибки есть

                $resData["success"] = 0;
                $resData["msg"] = "";
                foreach($error as $val){
                    $resData["msg"] .= $val."<br />";
                }

            }
        }else{
            $resData["success"] = 0;
            $resData["msg"] = "Нет данных";
        }


        echo json_encode($resData);

    }


    public function getcatparamAction() {

        if($_SERVER["REQUEST_METHOD"]=='POST'){

            $cat_id = abs((int)$_POST["cat_id"]);
            $model = new FileModel();
            $AdmindetailModel = new AdmindetailModel();
            $model->parametrs = $AdmindetailModel->getParametrForDetailAdd($cat_id);
            $model->cat_id = $cat_id;
            //выводим все
            $data = $model->render('blocks/paraminput.tpl.php');
            echo json_encode($data);

        }

    }
    
    
    
}
