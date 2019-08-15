<?php

class Model {
    
    public $db;
    
    function __construct() {
        
        $this->db = new Database();
        
    }
    
    public function get_data() {
        
    }
    
    /*
    *   Делает человекопонятную дату из метки времени
    */
    
    function getTime($time)
    {
        foreach (getdate($time) as $key => $value)
        {
            $arr[] = strlen($value) == 1 ? '0'.$value : $value;
        }
        
        return $arr[3].'.'.$arr[5].'.'.$arr[6].' '.$arr[2].':'.$arr[1]; // возвращает строку нормальную с нулями!
    }
    
}

?>