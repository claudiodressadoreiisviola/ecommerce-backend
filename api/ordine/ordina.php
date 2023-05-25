<?php
require_once __DIR__ . '/../../model/ordine.php';
require_once __DIR__ . '/../../model/sessione.php';

header("Content-type: application/json; charset=UTF-8");

$ordine = new Ordine();
$sessione = new Sessione();

$sessione->ottieniSessione();
try
{
    $result = $ordine->creaOrdine($sessione->UserID);
}
catch (Exception $e)
{
    if ($e->getCode() == 404)
    {
        http_response_code(400);
        echo json_encode(array("message" => $e->getMessage()));
        die();
    }
    else
    {
        http_response_code(500);
        //echo json_encode(array("message" => "Errore interno, contattare l'amministratore di sistema"));
        echo json_encode(array("message" => $e->getMessage()));
        die();
    }
}

echo json_encode(array("ordine" => $result));