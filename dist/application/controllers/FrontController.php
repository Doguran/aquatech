<?php
class FrontController {
	protected $_controller, $_action, $_params, $_body, $_cat_id, $_product_id;
	static $_instance;

	public static function getInstance() {
		if(!(self::$_instance instanceof self)) 
			self::$_instance = new self();
		return self::$_instance;
	}
	private function __construct(){
	   
        $request = explode('?', trim($_SERVER['REQUEST_URI'],'?'));
        
		//$request = $_SERVER['REQUEST_URI'];
		$splits = explode('/', trim($request[0],'/'));
        //Какой сontroller использовать?
		$this->_controller = !empty($splits[0]) ? ucfirst($splits[0]).'Controller' : 'IndexController';
		//Какой action использовать?
        if(!empty($splits[1]) and is_numeric($splits[1]) and !empty($splits[2])){
            $this->_action = "showAction";
            $this->_params = array("id"=>$splits[1],"url"=>$splits[2]);
        }else{
            $this->_action = !empty($splits[1]) ? $splits[1].'Action' : 'indexAction';
    		//Есть ли параметры и их значения?
    		if(!empty($splits[2])){
    			$keys = $values = array();
    				for($i=2, $cnt = count($splits); $i<$cnt; $i++){
    					if($i % 2 == 0){
    						//Чётное = ключ (параметр)
    						$keys[] = $splits[$i];
    					}else{
    						//Значение параметра;
    						$values[] = $splits[$i];
    					}
    				}
    			$this->_params = @array_combine($keys, $values);
    
            }
        }
	}
	
	
	
	public function route() {
	
	
		if(class_exists($this->getController())) {
			$rc = new ReflectionClass($this->getController());
			if($rc->implementsInterface('IController')) {
				if($rc->hasMethod($this->getAction())) {
				    if($this->_params !== false){
				      $controller = $rc->newInstance();
					$method = $rc->getMethod($this->getAction());
					$method->invoke($controller);  
				    }else{
				       throw new Exception("Params");
				    }
					
				} else {
					throw new Exception("Action");
				}
			} else {
				throw new Exception("Interface");
			}
		} else {
           throw new Exception("Controller");	
		}
	}
	

	public function getParams() {
		return $this->_params;
	}
	public function getController() {
		return $this->_controller;
	}
	public function getAction() {
		return $this->_action;
	}
	public function getBody() {
		return $this->_body;
	}
	public function setBody($body) {
		$this->_body = $body;
	}
    
    
    
    //эти методы нужны для рисования меню
    public function getCatId() {
		return $this->_cat_id;
	}
	public function setCatId($cat_id) {
		$this->_cat_id = $cat_id;
	}
    public function getProductId() {
		return $this->_product_id;
	}
	public function setProductId($product_id) {
		$this->_product_id = $product_id;
	}
    

    
}	