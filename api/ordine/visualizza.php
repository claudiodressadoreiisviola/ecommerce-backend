<?php
require __DIR__ . '/../../model/ordine.php';
require __DIR__ . '/../../model/sessione.php';
header("Content-type: application/json; charset=UTF-8");

if (!isset($_GET['ordine']) || empty($_GET['ordine']) || !isset($_GET['sessione']) || empty($_GET['sessione']))
{
    http_response_code(400);
    echo json_encode(array("message" => "Parametri non corretti"));
    die();
}

$ordine = new Ordine();
$sessione = new Sessione();

$sessione->ottieniSessione($_GET['sessione']);

$result = $ordine->visualizzaOrdine($sessione->UserID, $_GET['ordine']);

echo json_encode(array("ordine" => $result));