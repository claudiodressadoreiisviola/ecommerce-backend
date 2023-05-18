<?php

require_once __DIR__ . "/../common/connect.php";

Class Carrello
{
    // I dati inerenti al carrello
    public $utente = 0;
    public $listavarianti = [];

    public $stato = "Inviato";
    public $attivo = true;
    

    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
    }

    public function ottieniCarrello($utente)
    {
        // Ottengo gli elementi nel carrello
        $sql = "SELECT v.`id` AS 'Variante', p.`id` AS 'Prodotto', p.`nome` AS 'Nome prodotto', v.`nome` AS 'Nome variante', c.quantita AS 'Quantità'
        FROM carrello c
        INNER JOIN variante v ON c.`variante` = v.`id`
        INNER JOIN prodotto p ON p.`id` = v.`prodotto`
        WHERE c.`utente` = :utente";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo e lo ritorno
        return $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function svuotaCarrello($utente)
    {
        // Elimino tutti i record nel carrello che corrispondono con un determinato utente
        $sql = "DELETE FROM `carrello`
        WHERE `carrello`.`utente` = :utente";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();
    }

    public function rimuoviElemento($utente, $variante)
    {
        $sql = "DELETE FROM `carrello`
        WHERE `carrello`.`utente` = :utente AND `carrello`.`variante` = :variante";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':variante', $variante, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();
    }

    public function aggiungiElemento($utente, $variante, $quantita)
    {
        $sql = "INSERT INTO `carrello` ( utente, variante, quantita )
                VALUES ( :utente, :variante, :quantita )";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':variante', $variante, PDO::PARAM_INT);
        $stmt->bindValue(':quantita', $quantita, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();
    }

    public function cambiaQuantita($utente, $variante, $quantita)
    {
        $sql = "UPDATE `carrello`
                SET `quantita` = :quantita
                WHERE `carrello`.`utente` = :utente, `carrello`.`variante` = :variante";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':variante', $variante, PDO::PARAM_INT);
        $stmt->bindValue(':quantita', $quantita, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();
    }
}
?>