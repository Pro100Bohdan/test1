<?php

class Controller_Admin extends Controller {
    
    function __construct() {
        
        $this->model = new Model_Admin();
        $this->view  = new View();
        
    }
    
    function action_users() {
        
        $data = $this->model->get_users();
        $this->view->generate('users.html', 'template-admin.html', $data);
        
    }
    
    function action_saving_invoices() {
        
        $invoice = json_decode($_POST['json_data'], true);
        $data = $this->model->save_invoice($invoice);
        
        print json_encode($data); 
    }
    
    // Редактор пользователя
    function action_user_edit($id) {
        
        $data = $this->model->get_user($id);
        $this->view->generate('user-edit.html', 'template-admin.html', $data);
        
    }
    
    // Добавиление нового пользователя
    function action_new_user() {
        
        $user = json_decode($_POST['json_data'], true);
        $data = $this->model->new_user($user);
        print json_encode($data);
        
    }
    
    function action_settings() {
        
        if ($_SESSION['barcode-service']['rights'] == '200') {
        
            $data = $this->model->get_keys();
            $this->view->generate('settings.html', 'template-admin.html', $data);
        
        } else {
            
            header('Location: /');
            
        }
            
    }
    
    function action_key_edit($id) {
        
        $data = $this->model->get_key($id);
        @$this->view->generate('edit-key.html', 'template-admin.html', $data);
        
    }
    
    function action_delete_package()
    {
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $package = json_decode($_POST['json_data'], true);
            $data = $this->model->deletePackage($package);

            print json_encode($data);
            
        }
    }
    
    function action_save_key() {
        
        $user = json_decode($_POST['json_data'], true);
        $data = $this->model->save($user);
        
        print json_encode($data);    
    }
    
    function action_generate_key() {
        
        $data = $this->model->random();
        print json_encode($data);
        
    }
    
    function action_out_archive()
    {
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
            $package = json_decode($_POST['json_data'], true);
            $data = $this->model->out_archive($package);

            print json_encode($data); 
        }
    }
    
    function action_packages($tool, $table, $param) {
        
        $date1 = str_replace('-', '', $_POST['date-1']);
        $date2 = str_replace('-', '', $_POST['date-2']);
        
        $page = $_GET['page'] - 1;
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150' || $_SESSION['barcode-service']['rights'] == '100') {
            
            if ($table == null || $table == 'first-table-type') {
                $data = $this->model->get_packages($tool, $date1, $date2);
                $data['table'] = 'table-1.html';
            } else {
                $data = $this->model->get_invoices($tool, $page);
                $data['table'] = 'table-2.html';
            }
            
            $data['table-type'] = ['/admin/packages/'.$tool.'/second-table-type/?page=1', '/admin/packages/'.$tool.'/first-table-type/?page=1']; // ссылки для выбора типа таблиц
            $data['paginator-link'] = '/admin/packages/'.$tool.'/'.$table.'/'.$param; // ссылки для гинации
            $data['active-page'] = $page;
            
            $data['limiter'] = [$page + 5, $page - 5];
            
            
            
            if ($tool == 'Steel-Packing' || $tool == 'Laser-Marking' || $tool == 'Grooving') {
                
                @$this->view->generate('packages-2table.html', 'template-admin.html', $data);
                
            } else {
                
                @$this->view->generate('packages.html', 'template-admin.html', $data);
                
            }
            
            
        } else if ($_SESSION['barcode-service']['rights'] == '150') {
            
            $data = $this->model->get_packages($tool, $date1, $date2);
            @$this->view->generate('packages.html', 'template.html', $data);
            
        } else if ($_SESSION['barcode-service']['rights'] == '100') {
            
            $data = $this->model->get_packages($tool, $date1, $date2);
            @$this->view->generate('package-cut.html', 'template-admin.html', $data);
            
        } else {
            
            header('Location: /');
            
        }
        
    } 
    
    function action_edit($id) {
        
        if ($_SESSION['barcode-service']['rights'] == '200') {
        
            $data = $this->model->get_package($id);
            @$this->view->generate('edit-package.html', 'template-admin.html', $data);
            
        } else if ($_SESSION['barcode-service']['rights'] == '150') {
            
            $data = $this->model->get_package($id);
            @$this->view->generate('edit-package.html', 'template-admin.html', $data);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_orders($package) {
        
        if ($_SESSION['barcode-service']['rights'] == '200') {
        
            $data = $this->model->get_orders($package);
            @$this->view->generate('orders.html', 'template-admin.html', $data);
            
        } else if ($_SESSION['barcode-service']['rights'] == '150') {
            
            $data = $this->model->get_orders($package);
            @$this->view->generate('orders.html', 'template.html', $data);
            
        } else if ($_SESSION['barcode-service']['rights'] == '100') {
            
            $data = $this->model->get_orders($package);
            @$this->view->generate('orders-cut.html', 'template-admin.html', $data);
            
        } else {
            
            header('Location: /');
            
        }
        
    } 
    
    function action_new_order() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150' || $_SESSION['barcode-service']['rights'] == '100') {
        
            $new = json_decode(stripslashes($_POST['json_data']), true);

            $data = $this->model->new_order($new);
            
            print json_encode($data);
            
        }
        
    }
    
    /*function action_download($id, $filename) {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100') {
        
            $data = $this->model->get_file($id, $filename);

            if ($data == true) { 

                header('Location: /' . $filename . '.csv');

            } else {

                echo $data;

            }
            
        } else {
            
            header('Location: /');
            
        }
            
    }*/
    
    function action_download($id, $filename) {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $data = $this->model->get_file($id, $filename);
            
            $this->view->generate(null, 'excel.html', $data);
            
        }

            
    }
    
    function action_user_stat($user) {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $data = $this->model->get_user_statistic_2($user);

            $this->view->generate('user-statistic.html', 'template-admin.html', $data);
            
        }
        
    }
    
    function action_user_stat_orders($user, $tool) {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $data = $this->model->get_user_statistic_order_2($user, $tool);

            $this->view->generate('user-statistic-order.html', 'template-admin.html', $data);
            
        }
        
    }
    
    function action_statistic_invoices() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100' || $_SESSION['barcode-service']['rights'] == '150') {
            
            $data = $this->model->get_statistic();

            $this->view->generate('statistic.html', 'template-admin.html', $data);
            
        }
    }
    
    function action_statistic_orders() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '100' || $_SESSION['barcode-service']['rights'] == '150') {
            
            $data = $this->model->get_statistic_orders();

            $this->view->generate('statistic-orders.html', 'template-admin.html', $data);
            
        }
    }
    
    ###############################################################################################################################
    
    function action_archive() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $data = $this->model->get_archive();
            
            $this->view->generate('archive.html', 'template-admin.html', $data);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_scan_barcode() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150' || $_SESSION['barcode-service']['rights'] == '100') {
        
            $scaned = json_decode(stripslashes($_POST['json_data']), true);

            $data = json_encode($this->model->scan_barcode($scaned)); 

            print $data;
            
        }
        
    }
    
    /*function action_scan_part_number() {
        
        $scaned = json_decode(stripslashes($_POST['json_data']), true);
        
        $data = json_encode($this->model->scan_part_number($scaned));
        
    }*/
    
    function action_to_archive() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $package = json_decode($_POST['json_data'], true);

            $data = $this->model->to_archive($package);
            
            print json_encode($data);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_new_package() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150' || $_SESSION['barcode-service']['rights'] == '100') {
        
            $new = json_decode(stripslashes($_POST['json_data']), true);

            $data = json_encode($this->model->new_package($new));
            
            print $data;
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_delete_order() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $id = $_POST['id'];

            print $data = $this->model->delete($id);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_redaction_order() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $new = json_decode($_POST['json_data'], true);

            $data = $this->model->redaction_order($new);
            
            print json_encode($data);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_redaction_package() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $new = json_decode(stripslashes($_POST['json_data']), true);

            print $data = $this->model->redaction_package($new);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_redaction($id) {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $data = $this->model->get_order($id);
            @$this->view->generate('redaction.html', 'template-admin.html', $data);
            
        } else {
            
            header('Location: /');
            
        }
        
    }
    
    function action_get_barcode() {
        
        if ($_SESSION['barcode-service']['rights'] == '200' || $_SESSION['barcode-service']['rights'] == '150') {
        
            $order = json_decode(stripslashes($_POST['json_data']), true);

            print json_encode($this->model->get_barcode($order));
            
        } else {
            
            header('Location: /');
            
        }
          
    }
    
    function action_category() {
        
        $data = $this->model->get_category();
        @$this->view->generate('category.html', 'template-admin.html', $data);
        
    }
    
    function action_print($order) {
        
        $data = $this->model->get_order_info($order);
        @$this->view->generate( null, 'print.html', $data);
        
    }
    
}

?>