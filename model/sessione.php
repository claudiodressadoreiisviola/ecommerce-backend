<?php

require_once __DIR__ . "/../common/connect.php";

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
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
    }

    public function creaSessione($email, $password)
    {
        // Creo una nuova sessione
        $sql = "INSERT INTO sessione ( `id`, `utente` )
                SELECT UUID() AS 'id', `utente`.`id` AS 'utente'
                FROM `utente`
                WHERE `utente`.`email` = :email AND `utente`.`password` = :password";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() == 0)
        {
            return 1;
        }

        // Ottengo i dati della sessione appena creata
        $sql = "SELECT `sessione`.`id` AS 'id'
                FROM `sessione`
                INNER JOIN `utente` u ON u.`id` = `sessione`.`utente`
                WHERE CURRENT_DATE() < `sessione`.`scadenza` AND u.`email` = :email";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Se abbiamo un risultato
        if(count($result) > 0)
        {
            // Popolo le proprietà della classe con i dati appena trovati
            $this->SessionID = $result[0]["id"];

            setcookie("sessione", $this->SessionID, time()+60*60*24*30, '/');

            return 0;
        }
        else
        {
            return 2;
        }
    }

    public function ottieniSessione()
    {
        if (isset($_COOKIE['sessione']))
        {
            $sessione = $_COOKIE['sessione'];
        }
        else
        {
            http_response_code(401);
            echo json_encode(array("message" => "Necessario eseguire il login per usare questo servizio!"));
            die();
        }
        

        // Ottengo la sessione valida
        $sql = "SELECT `sessione`.`id` AS `id`, `sessione`.`utente` AS `utente`
                FROM `sessione`
                WHERE `sessione`.`id` = :sessione AND CURRENT_DATE() < `sessione`.`scadenza` AND `sessione`.`attivo` = TRUE
                LIMIT 1";


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
            http_response_code(401);
            echo json_encode(array("message" => "Sessione non valida, esegui il login nuovamente"));
            die();
        }
    }

    public function rinnova()
    {
        $sessione = $this->SessionID;

        $sql = "UPDATE sessione
        SET scadenza = DATE_ADD(CURRENT_DATE(), INTERVAL 1 MONTH)
        WHERE `sessione`.`id` = :sessione";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':sessione', $sessione, PDO::PARAM_STR);

        $stmt->execute();

        setcookie("sessione", $this->SessionID, time()+60*60*24*30, '/');
    }
}
?>