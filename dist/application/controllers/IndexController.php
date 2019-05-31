<?php
class IndexController implements IController {
	public function indexAction() {
	   
        $fc = FrontController::getInstance();
        
		$model = new FileModel();

		
        //выводим все
		$output = $model->render(DEFAULT_FILE);
		
		$fc->setBody($output);
	}
    
    
    


}
