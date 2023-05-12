<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../common/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

Class Variante
{
    // I dati inerenti all'utente
    public $id = 0;
    public $nome = "";
    public $descrizione = "";
    public $prezzo = 0;
    

    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
    }

    public function popolaVariante($variante)
    {
        // Ottengo tutti i dati rilevanti al prodotto
        $sql = "SELECT `p`.`id` AS `id`, `p`.`nome` AS `nome`, `p`.`descrizione` AS `descrizione`, `p`.`prezzo` AS `prezzo`
        FROM `variante` `p`
        WHERE `p`.`id` = :variante";

        // Preparo la query e associo i bind ai parametri
        $stmt = $this->connnection->prepare($sql);
        $stmt->bindValue(':variante', $variante, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo
        $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Associo i dati appena raccolti al prodotto
        $this->id = $risultato[0]["id"];
        $this->nome = $risultato[0]["nome"];
        $this->descrizione = $risultato[0]["descrizione"];
        $this->prezzo = $risultato[0]["prezzo"];
    }

    public function catalogo($prodotto)
    {
        // Ottengo gli indici di tutti i prodotti disponibili
        $sql = "SELECT `p`.`id` AS `id`
        FROM `variante` `p`
        WHERE `p`.`prodotto` = :prodotto";

        // Preparo la query e associo i bind ai parametri
        $stmt = $this->connnection->prepare($sql);
        $stmt->bindValue(':prodotto', $prodotto, PDO::PARAM_INT);

        // Eseguo
        $stmt->execute();

        // Metto il risultato in un array associativo
        $risultato = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Creo un array per contenere il catalogo
        $catalogo = array();

        for ($i=0; $i < count($risultato); $i++) { 
            // Creo una nuova istanza della variante
            $variante = new Variante();
            // Popolo l'istanza
            $variante->popolaVariante($risultato[$i]["id"]);

            // Aggiungo il variante all'array del catalogo
            $catalogo[] = $variante;
        }

        return $catalogo;
    }
}
?>