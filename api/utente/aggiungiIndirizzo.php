<?php
require __DIR__ . '/../../model/sessione.php';
require __DIR__ . '/../../model/indirizzo.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->via) || empty($data->civico) || empty($data->comune) || empty($data->provincia) || empty($data->cap)) {
    http_response_code(400);
    echo json_encode(array("message" => "Parametri non corretti o insufficienti"));
    exit();
}

$indirizzo = new Indirizzo();
$sessione = new Sessione();

$sessione->ottieniSessione();

$indirizzo->aggiungiIndirizzo($sessione->UserID, $data->via, $data->civico, $data->comune, $data->provincia, $data->cap);

echo json_encode(array("message" => "Indirizzo aggiunto con successo!"));