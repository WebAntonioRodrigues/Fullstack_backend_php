<?php
require_once "../db.php";
require_once "../crud/materials_crud.php";

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

function getAllMaterialsApi($pdo){
    $allMaterials = getAllMaterials($pdo);
    echo json_encode($allMaterials, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

function getMaterialApi($pdo, $materialId){
    $material = getMaterial($pdo, $materialId);

    if ($material) {
        echo json_encode($material, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode(['mensagem' => 'Material not found'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function insertMaterialApi($pdo, $dados){
    $materialRef = $dados["material_ref"] ?? null;
    $name = $dados["name"] ?? null;
    $category = $dados["category"] ?? null;
    $salePrice = $dados["sale_price"] ?? null;
    $currentStock = $dados["current_stock"] ?? 0;

    if ($materialRef && $name && $salePrice) {
        $newMaterialId = insertMaterial($pdo, $materialRef, $name, $category, $salePrice, $currentStock);
        http_response_code(201);
        echo json_encode(['mensagem' => 'Material criated successfully', 'id' => $newMaterialId], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(400);
        echo json_encode(['mensagem' => 'material_ref, name and sale_price are required'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function updateMaterialApi($pdo, $dados){
    $materialId = $dados["id"] ?? null;
    $materialRef = $dados["material_ref"] ?? null;
    $name = $dados["name"] ?? null;
    $category = $dados["category"] ?? null;
    $salePrice = $dados["sale_price"] ?? null;
    $currentStock = $dados["current_stock"] ?? null;

    if ($materialId && $materialRef && $name && $salePrice && $currentStock !== null) {
        updateMaterial($pdo, $materialId, $materialRef, $name, $category, $salePrice, $currentStock);
        echo json_encode(['mensagem' => 'Material updated successfully'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(400);
        echo json_encode(['mensagem' => 'id, material_ref, name, sale_price and current_stock required'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

function deleteMaterialApi($pdo, $dados){
    $materialId = $dados["id"] ?? null;

    $linhasRemovidas = deleteMaterial($pdo, $materialId);

    if ($linhasRemovidas > 0) {
        echo json_encode(['mensagem' => 'Material removed'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['mensagem' => 'No material removed (ID may not exist)'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

$method = $_SERVER["REQUEST_METHOD"];
$dados = json_decode(file_get_contents("php://input"), true);

if ($method === "GET") {
    if (isset($_GET["id"])) {
        getMaterialApi($pdo, $_GET["id"]);
    } else {
        getAllMaterialsApi($pdo);
    }
}

if ($method === "POST") {
    insertMaterialApi($pdo, $dados);
}

if ($method === "PUT") {
    updateMaterialApi($pdo, $dados);
}

if ($method === "DELETE") {
    deleteMaterialApi($pdo, $dados);
}

?>