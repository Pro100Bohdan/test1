<?php

class Controller_Main extends Controller {
    
    function __construct() {
        
        $this->model = new Model_Main();
        $this->view  = new View();
        
    }
    
    function action_index() {
        
        //$data = $this->model->get_table();
        @$this->view->generate(null, 'login.html', $data);
        
    }
    
    function action_login() {
        
        if (isset($_POST['key'])) {
            
            $key = $_POST['key'];
            
            $data = $this->model->check_user($key);
            
            if ($data == true) {
                
                header('Location: /admin/packages');
                
            } else {
                
                header('Location: /error');
                
            }
            
        }
        
    }
    
    function action_logout() {
        
        session_destroy();
        
        header('Location: /');
        
    }
    
}

?>