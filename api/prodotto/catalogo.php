<?php

require __DIR__ . '/../../model/prodotto.php';
header("Content-type: application/json; charset=UTF-8");

$prodotto = new Prodotto();

$result = $prodotto->catalogo();

if (count($result) > 0)
{
    http_response_code(200);
    echo json_encode(array("catalogo" => $result));
    die();
}
else
{
    http_response_code(204);
    die();
}
?>