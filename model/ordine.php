<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../common/$class.php";
});

require __DIR__ . "/variante.php";

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

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
        $this->database = new Database;
        $this->connection = $this->database->getConnection();
    }

    public function creaOrdine($utente)
    {
        // Creo l'ordine
        $sql = "INSERT INTO ordine (  )";


        $sql = "SELECT v.`id` AS 'Variante', p.`id` AS 'Prodotto', p.`nome` AS 'Nome prodotto', v.`nome` AS 'Nome variante', c.quantita AS 'Quantità'
        FROM carrello c
        INNER JOIN variante v ON c.`variante` = v.`id`
        INNER JOIN prodotto p ON p.`id` = v.`prodotto`
        WHERE c.`utente` = :utente";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo e lo ritorno
        return $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function visualizzaOrdine($utente, $ordine)
    {
        $sql = "SELECT op.`variante` AS 'Variante', p.`nome` AS 'Nome prodotto', v.`nome` AS 'Nome variante', op.`quantita` AS 'Quantità'
        FROM `ordine_prodotto` op
        INNER JOIN `variante` v ON v.`id` = op.`variante`
        INNER JOIN `prodotto` p ON p.`id` = v.`prodotto`
        INNER JOIN `ordine` o ON o.`id` = op.`ordine`
        INNER JOIN `utente` u ON u.`id` = o.`utente`
        WHERE op.`ordine` = :ordine AND u.`id` = :utente";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':ordine', $ordine, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo e lo ritorno
        return $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>