<?php

class Model_Main extends Model {
    
    function check_user($key) {
        
        $this->db->connect();
        
        $res = mysql_query('SELECT * FROM user_keys');
        
        if ($res) {
            
           while ($row = mysql_fetch_assoc($res)) {
               
               if ($key == $row['enter_key']) {
                   
                   $_SESSION['barcode-service']['rights'] = $row['rights'];
                   $_SESSION['barcode-service']['user_name'] = $row['login'];
                   
                   $data = true;
                   
                   break;
                   
               }
               
           }
            
        } else {
            
            $data = false;
            
        }
        
        /*$salt = '93hdjd47';
        
        $res = mysql_query('SELECT * FROM user_keys');
        
        if ($res) {
            
           while ($row = mysql_fetch_assoc($res)) {
               
               if (md5($key.$salt) == $row['hash']) {
                   
                   $_SESSION['barcode-service']['rights'] = $row['rights'];
                   
                   $data = true;
                   
                   break;
                   
               }
               
           }
            
        } else {
            
            $data = false;
            
        }*/
        
        $this->db->close();
        
        return $data;
        
    }
    
}

?>