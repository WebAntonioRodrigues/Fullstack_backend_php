<?php
    session_start();
    header("Content-Type: application/json; charset=UTF-8");
    require_once "db.php";

    $method = $_SERVER["REQUEST_METHOD"];

    if($method === "POST"){

        $inputData = json_decode(file_get_contents('php://input'), true);
        $email = $inputData["email"] ?? $_POST["email"] ?? null;
        $password = $inputData["password"] ?? $_POST["password"] ?? null;

        if($email && $password){
            $stmt = $pdo->prepare("select * from users where email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if($user && password_verify($password, $user["password"])){
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                echo json_encode(["message" => "Successful login", "role" => $user["role"]]);
            } else {
                http_response_code(401);
                echo json_encode(["error" => "Invalid credentials"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "email and password are required"]);
        }

    }

?>