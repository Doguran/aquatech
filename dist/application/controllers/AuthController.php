<?php
class AuthController implements IController {
	public function indexAction() {
	   
		if (isset($_POST['auth_email']) and isset($_POST['auth_pass'])) {
		  
          $email =  Helper::clearData($_POST["auth_email"],'email');
          $pass =  trim($_POST["auth_pass"]);
          
          if($email == "" || mb_strlen($pass,'utf8')<6 ){
            //пишем неверный логин или пароль
            $this->not_ok_msg("Неверный логин или пароль");
          }else{
            
            $pass = Helper::cryptoPass($pass);
            $authObj = new AuthModel();
            $auth = $authObj->authUser($email,$pass);
            
            if(!$auth){
               //пишем неверный логин или пароль
               $this->not_ok_msg("Неверный логин или пароль");
            }else{
                
                //заводим юзера
                $_SESSION['user']['id'] = $auth["id"];
                $_SESSION['user']['name'] = $auth["name"];
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['status'] = $auth["status"];
                $_SESSION['user']['ip'] = $_SERVER['REMOTE_ADDR'];
                if($auth["status"] == "admin"){
                    $_SESSION['KCFINDER'] = array();
                    $_SESSION['KCFINDER']['disabled'] = false;
                }
                $this->ok_msg();
                
            }
          }
        }
	}
    
    
    public function modalAction() {
        
      $model = new FileModel();
      echo $model->render("auth.tpl.php");
      
      		
	}
    
     
    
    
    public function editpassAction() {
        
      if(isset($_SESSION["user"]["id"])) { 
        
          if($_SERVER["REQUEST_METHOD"]=='POST'){
            $old_pass =  trim($_POST["old_pass"]);
            $new_pass =  trim($_POST["new_pass"]);
            $new_pass2 =  trim($_POST["new_pass2"]);
            
            if(mb_strlen($old_pass,'utf8')<6 || mb_strlen($new_pass,'utf8')<6 || $new_pass != $new_pass2){
                $this->not_ok_msg("Это ошибка, попробуйте еще раз");
            }else{
                
              $email = $_SESSION["user"]["email"]; 
              $pass  = Helper::cryptoPass($old_pass); 
              $authObj = new AuthModel();
              $user = $authObj->authUser($email,$pass);
              
              if($user["id"] != $_SESSION["user"]["id"]){
                $this->not_ok_msg("Неверный пароль");
              }else{
                $new_pass  = Helper::cryptoPass($new_pass);
                if($authObj->editPass($new_pass)){
                    $this->ok_msg("Пароль успешно изменен.");
                }else{
                    $this->not_ok_msg("Ничего не поменялось");
                }
                
              }
                
            }
            
          }else{
            
            $model = new FileModel();
            echo $model->render("editpass.tpl.php");
            
          }
      
        }else{
            throw new Exception("Нет доступа");   
        }
      }
    
    
    public function logoutAction() {
        
      $_SESSION = array();
      unset($_COOKIE[session_name()]);
      session_destroy();
      $url = isset($_GET['url']) ? $_GET['url']:'';
      header("Location: http://".$_SERVER['HTTP_HOST'].$url);
      exit;
      		
	}
    
    private function ok_msg($msg="") {
        $resData = array();
        $resData["success"] = 1;
        $resData["msg"] = $msg;
        echo json_encode($resData);
        
		
	}
    private function not_ok_msg($msg="") {
        
        $resData = array();
        $resData["success"] = 0;
        $resData["msg"] = $msg;
        echo json_encode($resData);
		
	}
}
