<?php

function insertPaymentType($pdo, $name){
    $sql = "insert into payment_types (name) values (:name)";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['name' => $name]);

    $newPaymentTypeId = $pdo->lastInsertId();

    return $newPaymentTypeId;
}

function getPaymentTypeByName($pdo, $name){
    $sql = "select id, name from payment_types where name = :name";
    $cmd = $pdo->prepare($sql);
    $cmd->execute(['name' => $name]);
    $paymentType = $cmd->fetch();
    return $paymentType;
}

function getAllPaymentTypes($pdo){
    $cmdRead = $pdo->query("select id, name from payment_types order by name");
    $allPaymentTypes = $cmdRead->fetchAll();
    return $allPaymentTypes;
}

?>