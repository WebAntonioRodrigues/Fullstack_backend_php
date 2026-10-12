<?php
require_once "../db.php";
require_once "../crud/clients_crud.php";

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

function getAllClientsApi($pdo){
    $allClients = getAllClients($pdo);
    echo json_encode($allClients, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function getClientApi($pdo, $clientId){
    $client = getClient($pdo, $clientId);

    if ($client) {
        echo json_encode($client, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['mensagem' => 'Client not found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function insertClientApi($pdo, $dados){
    $name = $dados["name"] ?? null;
    $taxId = $dados["tax_id"] ?? null;
    $email = $dados["email"] ?? null;
    $phone = $dados["phone"] ?? null;
    $address = $dados["address"] ?? null;

    if ($name && $taxId) {
        $newClientId = insertClient($pdo, $name, $taxId, $email, $phone, $address);
        http_response_code(201);
        echo json_encode(['mensagem' => 'Cliente created successfully', 'id' => $newClientId], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(400);
        echo json_encode(['mensagem' => 'name and tax_id are required'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function updateClientApi($pdo, $dados){
    $clientId = $dados["id"] ?? null;
    $name = $dados["name"] ?? null;
    $taxId = $dados["tax_id"] ?? null;
    $email = $dados["email"] ?? null;
    $phone = $dados["phone"] ?? null;
    $address = $dados["address"] ?? null;

    if ($clientId && $name && $taxId) {
        updateClient($pdo, $clientId, $name, $taxId, $email, $phone, $address);
        echo json_encode(['mensagem' => 'Cliente updated successfully'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(400);
        echo json_encode(['mensagem' => 'id, name and tax_id are required'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function deleteClientApi($pdo, $dados){
    $clientId = $dados["id"] ?? null;

    $linhasRemovidas = deleteClient($pdo, $clientId);

    if ($linhasRemovidas > 0) {
        echo json_encode(['mensagem' => 'Client removed successfully'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['mensagem' => 'No client removed (ID may not exist)'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

$method = $_SERVER["REQUEST_METHOD"];
$dados = json_decode(file_get_contents("php://input"), true);

if ($method === "GET") {
    if (isset($_GET["id"])) {
        getClientApi($pdo, $_GET["id"]);
    } else {
        getAllClientsApi($pdo);
    }
}

if ($method === "POST") {
    insertClientApi($pdo, $dados);
}

if ($method === "PUT") {
    updateClientApi($pdo, $dados);
}

if ($method === "DELETE") {
    deleteClientApi($pdo, $dados);
}

?>