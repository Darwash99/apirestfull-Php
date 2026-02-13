<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {
    private static $key = "clave_super_secretaasdasgw1t32e1tr654dg897s5431r32w1eg65s4g";

    public static function generate($userId) {
        return JWT::encode(["uid"=>$userId], self::$key, 'HS256');
    }

    public static function verify() {

        $headers = getallheaders();

        if (!isset($headers["Authorization"])) {
            http_response_code(401);
            echo json_encode(["error" => "Token requerido"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers["Authorization"]);

        try {
            JWT::decode($token, new Key(self::$key, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Token inválido"]);
            exit;
        }
    }
}
