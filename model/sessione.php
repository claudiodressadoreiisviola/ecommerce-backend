<?php

spl_autoload_register(function ($class) {
    require __DIR__ . "/../common/$class.php";
});

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

Class Sessione
{
    // I dati inerenti alla sessione: l'id della sessione, l'utente e la scadenza
    public $SessionID = "";
    public $UserID = "";

    private Database $database;
    private PDO $connection;

    public function __construct()
    {
        // Inizializzo il database
        $this->database = new Database;
        $this->connection = $this->database->getConnection();
    }

    public function creaSessione($email, $password)
    {
        // Creo una nuova sessione
        $sql = "INSERT INTO sessione ( `id`, `utente` )
        SELECT UUID() AS 'id', `utente`.`id` AS 'utente'
        FROM `utente`
        WHERE `utente`.`email` = :email AND `utente`.`password` = :password";

        if ($stmt->rowCount() == 0)
        {
            throw new Exception("Credenziali sbagliate", 1);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        // Ottengo i dati della sessione appena creata
        $sql = "SELECT `sessione`.`id` AS `id`, `utente`.`nome` AS `nome`
        FROM `sessione`
        INNER JOIN `utente` ON `utente`.`id` = `sessione`.`utente`
        WHERE CURRENT_TIMESTAMP() < `sessione`.`scadenza` AND `sessione`.`attivo` = TRUE";


        $stmt = $this->connection->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se abbiamo un risultato
        if(count($result) == 1)
        {
            // Popolo le proprietà della classe con i dati appena trovati
            $this->SessionID = $result[0]["id"];
            $this->UserID = $result[0]["utente"];

            setcookie("sessione", $this->SessionID, time()+60*60*24*30, '/');
        }
        else
        {
            throw new Exception("Numero di sessioni attive non corretto", 1);
        }
    }

    public function ottieniSessione()
    {
        // Ottengo la sessione memorizzata nel computer tramite cookie
        $sessione = $_COOKIE['sessione'];

        // Ottengo la sessione valida
        $sql = "SELECT `sessione`.`id` AS `id`, `sessione`.`utente` AS `utente`
        FROM `sessione`
        WHERE `sessione`.`id` = :session AND CURRENT_TIMESTAMP() < `sessione`.`scadenza` AND `sessione`.`attivo` = TRUE";


        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':sessione', $sessione, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se abbiamo un risultato
        if(count($result) == 1)
        {
            // Popolo le proprietà della classe con i dati appena trovati
            $this->SessionID = $result[0]["id"];
            $this->UserID = $result[0]["utente"];

            $this->rinnova();
        }
        else
        {
            throw new Exception("Numero di sessioni non corretto", 1);
        }
    }

    public function rinnova()
    {
        $sessione = $_COOKIE['sessione'];

        $sql = "UPDATE sessione
        SET scadenza = CURRENT_TIMESTAMP()+60*60*24*30
        WHERE `sessione`.`id` = :sessione";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':sessione', $sessione, PDO::PARAM_STR);

        $stmt->execute();

        setcookie("sessione", $this->SessionID, time()+60*60*24*30, '/');
    }
}
?>