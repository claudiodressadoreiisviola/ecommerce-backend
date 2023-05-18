<?php
require __DIR__ . '/../../model/carrello.php';
require __DIR__ . '/../../model/sessione.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->variante) || empty($data->quantita)) {
    http_response_code(400);
    echo json_encode(array("message" => "Parametri non corretti o insufficienti"));
    exit();
}

$carrello = new Carrello();
$sessione = new Sessione();

$sessione->ottieniSessione();

$carrello->aggiungiElemento($sessione->UserID, $data->variante, $data->quantita);

echo json_encode(array("message" => "Prodotto aggiunto con successo!"));