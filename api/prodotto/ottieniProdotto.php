<?php

require __DIR__ . '/../../model/prodotto.php';
header("Content-type: application/json; charset=UTF-8");

if (!isset($_GET["prodotto"]) || empty($_GET["prodotto"]))
{
    http_response_code(400);
    echo json_encode(array("message" => "Parametri non corretti o insufficienti"));
    die();
}

$prodotto = new Prodotto();

try
{
    $prodotto->popolaProdotto($_GET["prodotto"]);
}
catch (Exception $e)
{
    if ($e->getCode() == 404)
    {
        http_response_code(404);
        echo json_encode(array("message" => $e->getMessage()));
        die();
    }
    else
    {
        http_response_code(500);
        echo json_encode(array("message" => "Errore interno, contattare l'amministratore di sistema"));
        die();

    }
}


if ($prodotto->id > 0)
{
    http_response_code(200);
    echo json_encode(array("prodotto" => $prodotto));
    die();
}
else
{
    http_response_code(204);
    die();
}
?>