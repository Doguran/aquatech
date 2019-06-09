<?php
class IndexController implements IController {
	public function indexAction() {
	   
        $fc = FrontController::getInstance();
        
		$model = new FileModel();

        $catModel = new CatModel();
        $model->categories = $catModel->getCatListForIndex();
        //echo $catModel->getIndexCatId();

		
        //выводим все
		$output = $model->render(DEFAULT_FILE);
		
		$fc->setBody($output);
	}
}
