<?php

function insertMaterial($pdo, $materialRef, $name, $category, $salePrice, $currentStock){
    $sql = "insert into material (material_ref, name, category, sale_price, current_stock) values (:material_ref, :name, :category, :sale_price, :current_stock)";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'material_ref' => $materialRef,
        'name' => $name,
        'category' => $category,
        'sale_price' => $salePrice,
        'current_stock' => $currentStock
    ]);

    $newMaterialId = $pdo->lastInsertId();

    return $newMaterialId;
}

function getMaterial($pdo, $materialId){
    $sql = "select id, material_ref, name, category, sale_price, current_stock from material where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['id' => $materialId]);
    $material = $cmd->fetch();
    return $material;
}

function getAllMaterials($pdo){
    $cmdRead = $pdo->query("select id, material_ref, name, category, sale_price, current_stock from material");
    $allMaterials = $cmdRead->fetchAll();
    return $allMaterials;
}

function updateMaterial($pdo, $materialId, $materialRef, $name, $category, $salePrice, $currentStock){
    $sql = "update material set material_ref = :material_ref, name = :name, category = :category, sale_price = :sale_price, current_stock = :current_stock where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'material_ref' => $materialRef,
        'name' => $name,
        'category' => $category,
        'sale_price' => $salePrice,
        'current_stock' => $currentStock,
        'id' => $materialId
    ]);
}

function deleteMaterial($pdo, $materialId){
    $sql = "delete from material where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['id' => $materialId]);

    $deletedMaterials = $cmd->rowCount();

    return $deletedMaterials;
}

function decreaseStock($pdo, $materialId, $quantity){
    $sql = "update material set current_stock = current_stock - :quantity where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'quantity' => $quantity,
        'id' => $materialId
    ]);
}

?>