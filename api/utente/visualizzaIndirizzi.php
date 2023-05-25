<?php

require __DIR__ . '/../../model/sessione.php';
require __DIR__ . '/../../model/indirizzo.php';
header("Content-type: application/json; charset=UTF-8");

$indirizzo = new Indirizzo();
$sessione = new Sessione();

$sessione->ottieniSessione();

$result = $indirizzo->ottieniIndirizzi($sessione->UserID);

if (count($result) < 1)
{
    http_response_code(204);
    echo json_encode(array("message" => "Nessun indirizzo trovato!"));
}
else
{
    http_response_code(200);
    echo json_encode(array("indirizzi" => $result));
}
?>