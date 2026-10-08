<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once "db.php";

    $method = $_SERVER["REQUEST_METHOD"];

    if($method === "POST"){

        $inputData = json_decode(file_get_contents('php://input'), true);
        $name = $inputData["name"] ?? $_POST["name"] ?? null;
        $email = $inputData["email"] ?? $_POST["email"] ?? null;
        $password = $inputData["password"] ?? $_POST["password"] ?? null;
        $role = $inputData["role"] ?? $_POST["role"] ?? null;

        if($name && $email && $password && ($role === "admin" || $role === "employee")){
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "insert into users (name, email, password, role) values (?,?,?,?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$name, $email, $hashPassword, $role]);
            http_response_code(201);
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "name, email, password and a valid role (admin/employee) are required"]);
        }

    }

?>