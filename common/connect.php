<?php

class Database
{
    private $dbConnection = null;

    public function __construct()
    {
        $host = "claudiodressadore.net";
        $port = "3306";
        $db   = "ecommerce";
        $user = "ecommerce";
        $pass = "amnso0d89h33a";

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
