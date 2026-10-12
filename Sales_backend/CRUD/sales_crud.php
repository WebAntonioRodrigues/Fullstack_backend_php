<?php

function insertSale($pdo, $clientId, $total){
    $sql = "insert into sales (client_id, total) values (:client_id, :total)";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'client_id' => $clientId,
        'total' => $total
    ]);

    $newSaleId = $pdo->lastInsertId();

    return $newSaleId;
}

function insertSaleDetail($pdo, $saleId, $materialId, $quantity, $unitPrice){
    $sql = "insert into sales_detail (sale_id, material_id, quantity, unit_price) values (:sale_id, :material_id, :quantity, :unit_price)";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'sale_id' => $saleId,
        'material_id' => $materialId,
        'quantity' => $quantity,
        'unit_price' => $unitPrice
    ]);
}

function getSale($pdo, $saleId){
    $sql = "select id, client_id, sale_date, total from sales where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['id' => $saleId]);
    $sale = $cmd->fetch();
    return $sale;
}

function getSaleDetails($pdo, $saleId){
    $sql = "select material_id, quantity, unit_price from sales_detail where sale_id = :sale_id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['sale_id' => $saleId]);
    $details = $cmd->fetchAll();
    return $details;
}

function getAllSales($pdo){
    $cmdRead = $pdo->query("select id, client_id, sale_date, total from sales");
    $allSales = $cmdRead->fetchAll();
    return $allSales;
}

?>