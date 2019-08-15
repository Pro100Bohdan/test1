<?php

class Controller_error extends Controller {
    
    function __construct() {
        
        //$this->model = new Model_Main();
        $this->view  = new View();
        
    }
    
    function action_index() {
        
        //$data = $this->model->get_table();
        @$this->view->generate(null, 'error.html', $data);
        
    }
    
}

?>