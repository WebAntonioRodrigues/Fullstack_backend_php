<?php

function insertClient($pdo, $name, $taxId, $email, $phone, $address){
    $sql = "insert into clients (name, tax_id, email, phone, address) values (:name, :tax_id, :email, :phone, :address)";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'name' => $name,
        'tax_id' => $taxId,
        'email' => $email,
        'phone' => $phone,
        'address' => $address
    ]);

    $newClientId = $pdo->lastInsertId();

    return $newClientId;
}

function getClient($pdo, $clientId){
    $sql = "select id, name, tax_id, email, phone, address from clients where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['id' => $clientId]);
    $client = $cmd->fetch();
    return $client;
}

function getAllClients($pdo){
    $cmdRead = $pdo->query("select id, name, tax_id, email, phone, address from clients");
    $allClients = $cmdRead->fetchAll();
    return $allClients;
}

function updateClient($pdo, $clientId, $name, $taxId, $email, $phone, $address){
    $sql = "update clients set name = :name, tax_id = :tax_id, email = :email, phone = :phone, address = :address where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute([
        'name' => $name,
        'tax_id' => $taxId,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'id' => $clientId
    ]);
}

function deleteClient($pdo, $clientId){
    $sql = "delete from clients where id = :id";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['id' => $clientId]);

    $deletedClients = $cmd->rowCount();

    return $deletedClients;
}

?>