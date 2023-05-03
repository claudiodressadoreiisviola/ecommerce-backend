<?php
require __DIR__ . '/../../model/carrello.php';
require __DIR__ . '/../../model/sessione.php';
header("Content-type: application/json; charset=UTF-8");

$carrello = new Carrello();
$sessione = new Sessione();

$sessione->ottieniSessione();

$carrello->svuotaCarrello();