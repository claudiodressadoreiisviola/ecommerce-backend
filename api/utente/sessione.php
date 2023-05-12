<?php

// Carico gli script di base
spl_autoload_register(function ($class) {
    require __DIR__ . "/../../common/$class.php";
});

// Importo la classe Sessione
require __DIR__ . "/../../model/sessione.php";

set_exception_handler("errorHandler::handleException");
set_error_handler("errorHandler::handleError");

$data = json_decode(file_get_contents("php://input"));

if (!isset($_GET['sessione']) || empty($_GET['sessione'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Informazioni inviate non corrette o insufficienti, contattare l'amministratore per maggiori informazioni"));
    die();
}

echo "test";

$sessione = new Sessione();

echo "test";

try {
    $sessione->ottieniSessione($_GET['sessione']);

    echo json_encode(array("ID" => $sessione->SessionID));
} catch (Exception $e) {
    echo json_encode(array("message" => $e->getMessage()));
}