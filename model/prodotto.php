<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../common/$class.php";
});

require __DIR__ . "/variante.php";

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

Class Prodotto
{
    // I dati inerenti all'utente
    public $id = 0;
    public $nome = "";
    public $fornitore = 0;
    public $descrizione = "";
    public $prezzo = 0;
    public $varianti = [];
    

    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database;
        $this->connection = $this->database->getConnection();
    }

    public function rimuoviProdotto($prodotto, $utente)
    {
        // Rimuovo un determinato prodotto dal carrello di un determinato utente
        $sql = "DELETE FROM `carrello`
        WHERE `carrello`.`utente` = :utente AND `carrello`.`variante` = :prodotto";

        // Preparo la query e associo i bind ai parametri
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':utente', $utente, PDO::PARAM_INT);
        $stmt->bindValue(':variante', $prodotto, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();
    }

    public function popolaProdotto($prodotto)
    {
        // Ottengo tutti i dati rilevanti al prodotto
        $sql = "SELECT `p`.`id` AS `id`, `p`.`nome` AS `nome`, `p`.`fornitore` AS `fornitore`, `p`.`descrizione` AS `descrizione`, `p`.`prezzo` AS `prezzo`
        FROM `prodotto` `p`
        WHERE `p`.`id` = :prodotto";

        // Preparo la query e associo i bind ai parametri
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':prodotto', $prodotto, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo
        $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Associo i dati appena raccolti al prodotto
        $this->id = $risultato[0]["id"];
        $this->nome = $risultato[0]["nome"];
        $this->fornitore = $risultato[0]["fornitore"];
        $this->descrizione = $risultato[0]["descrizione"];
        $this->prezzo = $risultato[0]["prezzo"];

        $variante = new Variante();
        $this->varianti = $variante->catalogo($this->id);
    }

    public function catalogo()
    {
        // Ottengo gli indici di tutti i prodotti disponibili
        $sql = "SELECT `p`.`id` AS `id`
        FROM `prodotto` `p`
        WHERE `p`.`attivo` = 1";

        // Preparo la query e associo i bind ai parametri
        $stmt = $this->conn->prepare($sql);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo
        $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Creo un array per contenere il catalogo
        $catalogo = array();

        for ($i=0; $i < count($risultato); $i++) {
            // Creo una nuova istanza del prodotto
            $prodotto = new Prodotto();
            // Popolo l'istanza
            $prodotto->popolaProdotto($risultato[$i]["id"]);

            // Aggiungo il prodotto all'array del catalogo
            $catalogo[] = $prodotto
        }

        return $catalogo;
    }
}
?>