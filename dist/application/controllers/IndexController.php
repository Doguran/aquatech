<?php
class IndexController implements IController {
	public function indexAction() {
	   
        $fc = FrontController::getInstance();

        $CategoryController = new CategoryController();
        $catModel = new CatModel();


        $model = new FileModel();
        $model->categories = $catModel->getCatListForIndex();
        $model->table = $CategoryController->showIndex($catModel->indexCatId);
        $model->cat_name = $catModel->getCatName($catModel->indexCatId);
		
        //выводим все
		$output = $model->render(DEFAULT_FILE);
		
		$fc->setBody($output);
	}
}
