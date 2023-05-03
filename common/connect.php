<?php
class Database
{
    private $dbConnection = null;

    public function __construct()
    {
        $host = "gruppo1.violamarchesini.it";
        $port = "3306";
        $db   = "ecommerce";
        $user = "ecommerce";
        $pass = "sld63u890ww09f4";

        try {
            $this->dbConnection = new PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
