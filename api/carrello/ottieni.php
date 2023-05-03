<?php
require __DIR__ . '/../../model/carrello.php';
require __DIR__ . '/../../model/sessione.php';
header("Content-type: application/json; charset=UTF-8");

$componentiURL = parse_url($_SERVER["REQUEST_URI"]);
parse_str($componentiURL['query'], $parametri);

$carrello = new Carrello();
$sessione = new Sessione();

$sessione->ottieniSessione();


echo json_encode($carrello->ottieniCarrello($sessione->UserID));
http_response_code(201);