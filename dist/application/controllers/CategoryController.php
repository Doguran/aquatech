<?php
class CategoryController implements IController {

    protected $_output;

	public function indexAction() {
		throw new Exception("Нет запроса");
	}

    public function viewmenuAction() {

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            $fc = FrontController::getInstance();
            $params = $fc->getParams();
            $model = new FileModel();

            if(isset($params["id"])){


                $cat_id = abs((int)$params["id"]);
                $fc->setCatId($cat_id);
                $catModel = new CatModel();
                $catModel->getBreadCrumbs($cat_id);



            }else{
                $catModel = new CatModel();
            }


            $model->categories = $catModel->getCategories();
            $output = $model->render('blocks/catmenu.tpl.php');
            $fc->setBody($output);


        }else{
            throw new Exception("Нет запроса");
        }
    }

    private function _drawTable($cat_id, $cat_name){

        $ProductObj = new ProductArrModel();
        //достаем товары
        $ProductArr = $ProductObj->getProduct($cat_id);

        if($ProductArr){
            //рисуем таблицу
            $model = new FileModel();
            $model->cat_name = $cat_name;
            $model->contentArr = $ProductArr;
            $model->cat_id = $cat_id;
            $this->_output .= $model->render(TABLE_PRODUCT);
        }



        //достаем субкатегории
        $SubCatArr = $ProductObj->getSubCat($cat_id);
        if ($SubCatArr) {

            foreach($SubCatArr as $val){
                $this->_drawTable($val["id"],$val["name"]);
            }
        }



    }

    public function showIndex($cat_id){
        $CatModel = new CatModel();
        $cat_name = $CatModel->getCatName($cat_id);
        $this->_drawTable($cat_id, $cat_name["name"]);
        return $this->_output;
    }

    
    public function showAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["id"])){
            
        $cat_id = abs((int)$params["id"]);




        //рисуем название главной категории
            $CatModel = new CatModel();
            $cat_name = $CatModel->getCatName($cat_id);




        //достаем товары этой категории - главной категории и присоединяем
            $this->_drawTable($cat_id, $cat_name["name"]);


        $model = new FileModel();
        //выводим все
        $model->categories = $CatModel->getCatListForCatPage($cat_id);
        $model->table = $this->_output;
        $model->cat_id = $cat_id;
        $model->cat_name = $cat_name["name"];
        $output = $model->render(CAT_PAGE);
        $fc->setBody($output);
            
        }else{
            throw new Exception("Нет параметров");
        }
        
		
        
	}
    
    
}
