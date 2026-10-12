<?php
require_once "../db.php";
require_once "../crud/clients_crud.php";
require_once "../crud/materials_crud.php";
require_once "../crud/sales_crud.php";

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

function getAllSalesApi($pdo){
    $allSales = getAllSales($pdo);
    echo json_encode($allSales, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function getSaleApi($pdo, $saleId){
    $sale = getSale($pdo, $saleId);

    if ($sale) {
        $sale["items"] = getSaleDetails($pdo, $saleId);
        echo json_encode($sale, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['mensagem' => 'Sale not found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function insertSaleApi($pdo, $dados){
    $clientId = $dados["client_id"] ?? null;
    $items = $dados["items"] ?? null;

    if (!$clientId || !$items) {
        http_response_code(400);
        echo json_encode(['mensagem' => 'client_id and items are required'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    if (!getClient($pdo, $clientId)) {
        http_response_code(404);
        echo json_encode(['mensagem' => 'Client not found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return;
    }

    $total = 0;
    $saleLines = [];
    $requestedQuantities = [];

    foreach ($items as $item) {
        $materialId = $item["material_id"] ?? null;
        $quantity = $item["quantity"] ?? 0;

        $material = getMaterial($pdo, $materialId);

        if (!$material) {
            http_response_code(404);
            echo json_encode(['mensagem' => 'Material not found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        if ($quantity < 1) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'quantity must be at least 1'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        $requestedQuantities[$materialId] = ($requestedQuantities[$materialId] ?? 0) + $quantity;

        if ($requestedQuantities[$materialId] > $material["current_stock"]) {
            http_response_code(409);
            echo json_encode(['mensagem' => 'Not enough stock for ' . $material["name"]], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            return;
        }

        $total = $total + $material["sale_price"] * $quantity;
        $saleLines[] = [
            'material_id' => $materialId,
            'quantity' => $quantity,
            'unit_price' => $material["sale_price"]
        ];
    }

 
}

$method = $_SERVER["REQUEST_METHOD"];
$dados = json_decode(file_get_contents("php://input"), true);

if ($method === "GET") {
    if (isset($_GET["id"])) {
        getSaleApi($pdo, $_GET["id"]);
    } else {
        getAllSalesApi($pdo);
    }
}

if ($method === "POST") {
    insertSaleApi($pdo, $dados);
}

?>