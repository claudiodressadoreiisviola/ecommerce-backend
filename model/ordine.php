<?php

require_once __DIR__ . "/../common/connect.php";
require_once __DIR__ . "/carrello.php";

Class Ordine
{
    // I dati inerenti all'ordine
    public $utente = 0;
    public $variante = 0;
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

    public function creaOrdine($utente)
    {
        $carrello = new Carrello();
        
        if ($carrello->numeroElementi($utente) > 0)
        {
            // Creo l'ordine
            $sql = "INSERT INTO ordine ( utente, stato, attivo )
                    VALUES ( :utente, 'Inviato', TRUE )";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);

            // Eseguo
            $stmt->execute();

            // Ottengo l'id dell'ordine appena creato
            $ordine = $stmt->lastInsertId();

            // Sposto i prodotti dal carrello all'ordine
            $sql = "INSERT INTO ordine ( ordine, variante, quantita )
                    SELECT :ordine, v.`id` AS 'variante', c.quantita AS 'quantita'
                    FROM carrello c
                    INNER JOIN variante v ON c.`variante` = v.`id`
                    INNER JOIN prodotto p ON p.`id` = v.`prodotto`
                    WHERE c.`utente` = :utente";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
            $stmt->bindValue(':ordine', $ordine, PDO::PARAM_INT);

            // Eseguo
            $stmt->execute();
        }
        else
        {
            throw new Exception("Carrello vuoto", 404);
        }
    }

    public function visualizzaOrdine($utente, $ordine)
    {
        $sql = "SELECT op.`variante` AS 'variante', p.`nome` AS 'nome prodotto', v.`nome` AS 'nome variante', op.`quantita` AS 'quantita'
                FROM `ordine_prodotto` op
                INNER JOIN `variante` v ON v.`id` = op.`variante`
                INNER JOIN `prodotto` p ON p.`id` = v.`prodotto`
                INNER JOIN `ordine` o ON o.`id` = op.`ordine`
                INNER JOIN `utente` u ON u.`id` = o.`utente`
                WHERE op.`ordine` = :ordine AND u.`id` = :utente";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':ordine', $ordine, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo e lo ritorno
        $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $risultato;
    }
}
?>