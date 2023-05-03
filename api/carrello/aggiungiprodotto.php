<?php
require __DIR__ . '/../../model/carrello.php';
require __DIR__ . '/../../model/sessione.php';
header("Content-type: application/json; charset=UTF-8");

$componentiURL = parse_url($_SERVER["REQUEST_URI"]);
parse_str($componentiURL['query'], $parametri);

if (isset($parametri['prodotto']) == false || isset($parametri['quantita']) == false) {
    http_response_code(400);
    echo json_encode(array("message" => "Parametri non corretti o insufficienti"));
    exit();
}

$carrello = new Carrello();
$sessione = new Sessione();

$sessione->ottieniSessione();

$carrello->aggiungiElemento($sessione->UserID, $parametri['prodotto'], $parametri['quantita']);