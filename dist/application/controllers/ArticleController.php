<?php
class ArticleController implements IController {
    
	public function indexAction() {
		throw new Exception("Нет запроса"); 
    }
    
    public function showAction() {
    $fc = FrontController::getInstance();
    $params = $fc->getParams();
    
   if(!isset($params["id"]) and !isset($params["page"]))
   throw new Exception("Нет параметра запроса");
    
    $model = new FileModel();
    
    $model->article_title = "Новости";

    if(isset($params["id"])){
            
        $art_id = Helper::clearData($params["id"],"i"); 
                   
        $ArtObj = new ArtModel();
        $model->article_list = $ArtObj->getArtList();
        $article = $ArtObj->getArt($art_id);
        
        if(!$article) throw new Exception("Нет такой статьи");
        
        $model->h1 = $article["title"];
        $model->text = $article["txt"];
        $model->title = $article["title"];
        $model->keywords = null;
        $model->seo_desc = $article["anons"];
        $model->id = $art_id;

            
     }elseif(isset($params["page"])){
        $page = Helper::clearData($params["page"],"i"); 
        
        if($page<1){
            throw new Exception("Нет параметра запроса");
        }
        
        $pagination = new PaginationModel("news", 12, $page);
        
        $model->h1 = $model->title = $model->seo_desc = $model->keywords = $model->article_title;
        
        $model->article_all_list = $pagination->resultpage;
        $model->paginator = $pagination->displayPaging();
        
        
    }
    
	//выводим все
    	$output = $model->render("article.tpl.php");
        $fc->setBody($output);	
        
	}
    
    
}
