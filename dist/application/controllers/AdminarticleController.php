<?php
class AdminarticleController implements IController {
    
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
        
        $art_id = Helper::clearData($params["id"],"i"); 
        
        $model = new FileModel();
        
        $ArtObj = new ArtModel();
        $article = $ArtObj->getArt($art_id);
        
        if(!$article) throw new Exception("Нет такой статьи");
        
        $model->h1 = $article["h1"];
        $model->text = $article["text"];
        $model->title = $article["title"];
        $model->keywords = $article["keywords"];
        $model->seo_desc = $article["description"];
        $model->action = "edit";
        $model->id = $art_id;
        
        //$model->article_all_list = $ArtObj->getArtList(false);
        
        //выводим все
    	$output = $model->render("adminarticle.tpl.php");
        $fc->setBody($output);	  
          
     }else{
        
        throw new Exception("Нет параметров");
        
    }
    
	
        
}
    
    public function editAction() {

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            
            //инициализация пришедших переменных
            $name        = Helper::clearData($_POST['h1']);
//            $text        = QuillConvert::toHtml($_POST['text']);
            $text        = Helper::clearData($_POST['text'],"html");
            $id          = Helper::clearData($_POST['id'],"i");
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);
            
            if($title == "") $title = $name;
            
            $show_sidebar = isset($_POST['show_sidebar']) ? "1" : "0";
            
            //проверка на ошибки
            $error = array();
            if(!$name)       $error[] = "Не указанно название статьи";
            if(!$text)       $error[] = "Не указан текст статьи";
            
            if(empty($error)){//если ошибок нет
                $ArtModel = new ArtModel();
                $result = $ArtModel->updateArticle($id,$name,$text,$title,$keywords,$seo_desc);
                
            if($result)
                $this->ok_msg($id);
            else
                $this->not_ok_msg("Ничего не изменилось");
                
                
            }else{//если ошибки есть
                $er = "";
                foreach($error as $val){
                    $er .= $val."<br />";
                }
                $this->not_ok_msg($er);
                
            }
        }else{
           throw new Exception("Нет параметров");
        }
	   
    }
    
    
    public function deleteAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();
        
        if(isset($params["id"])){
            
        $id = Helper::clearData($params["id"],"i"); 
        header("Location: /article/show/page/1/");           
        $ArtModel = new ArtModel();
        $ArtModel->deleteArticle($id);
        
        }else{
            throw new Exception("Нет параметров");
        }
        
	}

    public function hidenAction() {
        $fc = FrontController::getInstance();
        $params = $fc->getParams();

        if(isset($params["id"])){

            $id = Helper::clearData($params["id"],"i");
            header("Location: /article/show/page/1/");
            $ArtModel = new ArtModel();
            $ArtModel->hidenArticle($id);

        }else{
            throw new Exception("Нет параметров");
        }

    }

    public function addAction() {
        $fc = FrontController::getInstance();
        
  		$model = new FileModel();


        $model->h1 = "";
        $model->text = "";
        $model->title = "";
        $model->keywords = "";
        $model->seo_desc = "";
        $model->id = "";
        $model->action = "insert";


        
        //выводим все
		$output = $model->render("adminarticle.tpl.php");
		
		$fc->setBody($output);
    }
    
    public function insertAction() {
        //Helper::print_arr($_POST);
        $resData = array();

        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
             //инициализация пришедших переменных

            //$text = Helper::quillToHtml($_POST['text']);

            $name        = Helper::clearData($_POST['h1']);
//            $text        = QuillConvert::toHtml($_POST['text']);
            $text        = Helper::clearData($_POST['text'],"html");
            $title       = Helper::clearData($_POST['title']);
            $keywords    = Helper::clearData($_POST['keywords']);
            $seo_desc    = Helper::clearData($_POST['seo_desc']);


            if($title == "") $title = $name;
            
            
                        
            //проверка на ошибки
            $error = array();
            if(!$name)       $error[] = "Не указанно название статьи";
            if(!$text)       $error[] = "Не указан текст статьи";
                        
            if(empty($error)){//если ошибок нет
               $ArtModel = new ArtModel();
               $art_id = $ArtModel->insertArticle($name,$text,$title,$keywords,$seo_desc); 
               if($art_id) $this->ok_msg($art_id);
                
            }else{//если ошибки есть
                $er = "";
                foreach($error as $val){
                    $er .= $val."<br />";
                }
                $this->not_ok_msg($er);
                
            }
        }else{
            
            throw new Exception("Нет параметров");
           
        }
	   
    }

    private function ok_msg($id) {
        $resData = array();
        $resData["success"] = 1;
        $resData["id"] = $id;
        echo json_encode($resData);


    }
    private function not_ok_msg($msg="") {

        $resData = array();
        $resData["success"] = 0;
        $resData["msg"] = $msg;
        echo json_encode($resData);

    }
    
    
    
}
