<?php

class Database {
    
    public $host     = 'localhost'; 
    public $login    = 'bystron';
    public $password = 'bystron';
    public $base     = 'bystron';
    
    /*public $host     = 'yu315715.mysql.tools';
    public $login    = 'yu315715_db';
    public $password = 'M9Vz86Er';
    public $base     = 'yu315715_db';*/
    
    public $anotherDB;
    public $thisDB;
    
    /*
    *   Базовое подключение к БД на хостинге
    */
    
    function connect() {
        
        mysql_connect($this->host, $this->login, $this->password);
        mysql_select_db($this->base);
        mysql_query("SET CHARACTER SET 'utf8'");
        
    }
    
    /*
    *   Подключение к сторонним БД
    */
    
    function connectToThisDB()
    {
        $this->thisDB = mysql_connect($this->host, $this->login, $this->password);
        mysql_select_db($this->base, $this->thisDB);
        mysql_query("SET CHARACTER SET 'utf8'");
    }
    
    function connectToAnotherDB()
    {
        $this->anotherDB = mysql_connect('localhost', 'admin', 'admin123');
        mysql_select_db('barcode', $this->anotherDB);
        mysql_query("SET CHARACTER SET 'utf8'");
    }
    
    function close() {
        
        mysql_close();
        
    }
    
}

?>