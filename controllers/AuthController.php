<?php
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../models/Users.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {

    private $userModel;
    private $key = "clave_super_secretaasdasgw1t32e1tr654dg897s5431r32w1eg65s4g";

    public function __construct() {
        $db = (new Database())->connect();
        $this->userModel = new User($db);
    }

    public function login($data) {
        if (!filter_var($data["name"])) {
            http_response_code(400);
            echo json_encode(["error" => "Usuario inválido"]);
            return;
        }

        $user = $this->userModel->findByEmail($data["name"]);

        if (!$user || !password_verify($data["password"], $user["password"])) {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales incorrectas"]);
            return;
        }

        $payload = [
            "iss" => "apirest",
            "iat" => time(),
            "exp" => time() + 3600,
            "uid" => $user["id"]
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        echo json_encode(["token" => $token]);
    }


}
