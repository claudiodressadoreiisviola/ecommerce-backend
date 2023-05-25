<?php

// Carico gli script di base
spl_autoload_register(function ($class) {
    require __DIR__ . "/../../common/$class.php";
});

// Importo la classe Sessione
require __DIR__ . "/../../model/sessione.php";

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "Informazioni inviate non corrette o insufficienti, contattare l'amministratore per maggiori informazioni"));
    die();
}

$sessione = new Sessione();

try
{
    $result = $sessione->creaSessione($data->email, $data->password);

    if ($result == 0)
    {
        http_response_code(200);
        echo json_encode(array("ID" => $sessione->SessionID));
    }
    else if ($result == 1)
    {
        http_response_code(401);
        echo json_encode(array("message" => "Credenziali non corrette!"));
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message" => "Errore interno, contattare l'amministratore di sistema"));
    }
    
} catch (Exception $e) {
    echo json_encode(array("message" => $e->getMessage()));
}