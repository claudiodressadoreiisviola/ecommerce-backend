<?php

require __DIR__ . "connect.php";

class ErrorHandler
{
    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database;
        $this->connection = $this->database->getConnection();
    }

    public static function handleException(Throwable $e): void
    {
        $sql = "INSERT INTO `exception` ( code, message, file, line )
        VALUES ( :code, :message, :file, :line )";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':code', $e->getCode(), PDO::PARAM_INT);
        $stmt->bindValue(':message', $e->getMessage(), PDO::PARAM_STR);
        $stmt->bindValue(':file', $e->getFile(), PDO::PARAM_STR);
        $stmt->bindValue(':line', $e->getLine(), PDO::PARAM_INT);

        $stmt->execute();

        $errorid = $this->conn->lastInsertId();

        http_response_code(500);
        echo json_encode(array("message"=>"Errore durante l'elaborazione della sua richiesta, per favore comunichi all'amministratore questo codice: {$errorid}"));
        die();
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
?>