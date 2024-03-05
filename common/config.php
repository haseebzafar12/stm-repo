<?php

class Database
{
    private $host = "localhost";
    private $db_name = "stm";
    private $username = "root";
    private $password = "";
    public $conn;
    
    public function getConnection()
    {
        $this->conn = null;
        try {
            $oldErrorReporting = error_reporting(E_ERROR | E_PARSE);
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, array(
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
            ));
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
