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
        //$model->old_price = $product["old_price"]>0 ? $product["old_price"] : "";
        $model->description = $product["shot_desc"];
        //$model->thumb_img = $product["thumb_img"];
        //$model->full_img = $product["full_img"];
        $model->cat_id = $product["cat_id"];
        //$model->compare = $product["compare"];
        $model->title = $product["title"];
        $model->keywords = $product["keywords"];
        $model->seo_desc = $product["seo_desc"];



        if($product["full_img"]){
            $imgArr = json_decode($product["full_img"], true);;
            $model->full_img = implode(",",$imgArr["img"]);
        }else{
            $model->full_img = false;
        }



        $model->action  = "edit";

        $predok = $catModel->getPredok($product["cat_id"]);
        $arrayName = $catModel->getCatName($predok);
        $model->img_dir_name = Helper::getChpu($arrayName["name"]);
        $model->predok_cat_id = $predok;

        //$ProductArrModel = new ProductArrModel();
        //$model->allProduct =$ProductArrModel->getAllProduct();
        
        //$model->sel_buy_together = $AdmindetailModel->getBuyTogether($product_id);


        
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
        //Helper::print_arr($_POST); exit;
        $resData = array();
                
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            
            //инициализация пришедших переменных
            $name        = Helper::clearData($_POST['name']);
            $sku         = Helper::clearData($_POST['sku']);
            $price       = Helper::clearData($_POST['price'],"i");
            $description = Helper::clearData($_POST['description'],"html");
            $full_img    = Helper::clearData($_POST['full_img']);
            $old_cat_id  = Helper::clearData($_POST['cat_id'],"i");
            $new_cat_id  = Helper::clearData($_POST['new_cat_id'],"i");
            $product_id  = Helper::clearData($_POST['product_id'],"i");
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);
            $dirName     = Helper::clearData($_POST['img_dir_name']);
            $predok     = Helper::clearData($_POST['predok_cat_id']);

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

                    if($full_img){
                        $imgArr = explode(",",$full_img);
                        $new_full_img  = json_encode(array("img" => $imgArr));
                    }
                    if ($_FILES['photo']['size']>0){
                        //если есть новая фотка
                        $new_full_img =  Helper::uploadimg("imgProduct".DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR);
                        if(!$new_full_img){throw new Exception('Ошибка загрузки изображения. Возможно файл слишком большой'); }
                        if(isset($imgArr) AND is_array($imgArr)){
                            foreach ($imgArr AS $value){
                                @unlink("imgProduct".DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR.$value);
                            }
                        }
                        $new_full_img =  json_encode(array("img" => array($new_full_img)));
                    }
                    
                  $AdmindetailModel = new AdmindetailModel();

                    
                  if($old_cat_id != $new_cat_id){//если категорию надо сменить
                    $AdmindetailModel->updateProductCategory($product_id,$new_cat_id);

                  }
//
                  $result = $AdmindetailModel->updateProductTable($product_id,$name,$sku,$price,$description,$new_full_img,$title,$keywords,$seo_desc);


                $resData["success"] = 1;
                $resData["cat"] = $new_cat_id;
                $resData["predok"] = $predok;
                   
                                      
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
        header("Location: /category/show/id/$predok_cat_id/#table$cat_id");
        $AdmindetailModel = new AdmindetailModel();
        $AdmindetailModel->deleteProduct($product_id,$predok_cat_id);
        
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
        $model->full_img = false;
        $model->title = null;
        $model->keywords = null;
        $model->seo_desc = null;
        $model->complete  = null;
        $model->valuta  = "R";
        $model->action  = "insert";
        $model->id = null;
        $model->cat_id = null;
        $model->img_dir_name = null;
        $model->predok_cat_id = null;
        $model->img_dir_name = null;
        $model->predok_cat_id = null;

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
            $description = Helper::clearData($_POST['description']);
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);
            $new_cat_id  = Helper::clearData($_POST['new_cat_id'],"i");

            if($title == "") $title = $name;

            //$parametrs = isset($_POST['parametrs']) ? $_POST['parametrs'] : false;

            //проверка на ошибки
            $error = array();
            if(!$name)       $error[] = "Не указанно название товара";
            if(!$sku)        $error[] = "Не указан артикул товара";

            if(empty($error)){//если ошибок нет
                try{
                    $CatModel = new CatModel();
                    $predok = $CatModel->getPredok($new_cat_id);
                    $arrayName = $CatModel->getCatName($predok);
                    $dirName = Helper::getChpu($arrayName["name"]);
                    if ($_FILES['photo']['size']>0){
                        //если есть новая фотка
                        $new_full_img =  Helper::uploadimg("imgProduct".DIRECTORY_SEPARATOR.$dirName.DIRECTORY_SEPARATOR);
                        if(!$new_full_img){throw new Exception('Ошибка загрузки изображения. Возможно файл слишком большой'); }
                        $new_full_img =  json_encode(array("img" => array($new_full_img)));
                    }else{

                        $new_full_img  = null;
                    }


                    $AdmindetailModel = new AdmindetailModel();
                    $product_id = $AdmindetailModel->addProductTable($name,$sku,$price,$description,$new_full_img,$title,$keywords,$seo_desc);




                    $AdmindetailModel->insertProductCategory($product_id,$new_cat_id);



//                    if($parametrs){
//                        foreach ($parametrs as $k=>$v) {
//                            if($v == "") continue;
//                            $AdmindetailModel->addParams($k,$v,$product_id);
//                        }
//                    }



                    $resData["success"] = 1;
                    $resData["id"] = $product_id;
                    $resData["cat"] = $new_cat_id;
                    $resData["predok"] = $predok;

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
