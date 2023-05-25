<?php

require_once __DIR__ . "/../common/connect.php";

Class Indirizzo
{
    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
    }

    public function aggiungiIndirizzo($utente, $via, $civico, $comune, $provincia, $cap)
    {
        $sql = "INSERT INTO indirizzo ( utente, via, civico, comune, provincia, cap )
                VALUES ( :utente, :via, :civico, :comune, :provincia, :cap )";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':via', $via, PDO::PARAM_STR);
        $stmt->bindValue(':civico', $civico, PDO::PARAM_STR);
        $stmt->bindValue(':comune', $comune, PDO::PARAM_STR);
        $stmt->bindValue(':provincia', $provincia, PDO::PARAM_STR);
        $stmt->bindValue(':cap', $cap, PDO::PARAM_STR);

        // Eseguo
        $stmt->execute();
    }

    public function ottieniIndirizzi($utente)
    {
        $sql = "SELECT i.id, i.via, i.civico, i.comune, i.provincia, i.cap
                FROM indirizzo i
                WHERE i.utente = :utente";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>