<?php 

// 🔥 CORS HEADERS
header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// 🔥 RESPONDER PRE-FLIGHT
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require "controllers/UserController.php";
require "controllers/TipoDocumentoController.php";
require "controllers/GeneroController.php";
require "controllers/MunicipioController.php";
require "controllers/DepartamentoController.php";
require "controllers/AuthController.php";
require "middleware/Auth.php";

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($requestUri, '/'));

$resource = $uri[0] ?? null;
$id = $uri[1] ?? null;

switch ($resource) {
    case "login":
        if ($method === "POST") {
            (new AuthController())->login(
                json_decode(file_get_contents("php://input"), true)
            );
        }
        break;

    case "pacientes":
        Auth::verify();
        $controller = new UserController();

        switch ($method) {
            case "GET":
                $id ? $controller->getByPaciente($id)
                    : $controller->index();
                break;

            case "POST":
                $controller->store(
                    json_decode(file_get_contents("php://input"), true)
                );
                break;

            case "PUT":
                $controller->update(
                    $id,
                    json_decode(file_get_contents("php://input"), true)
                );
                break;

            case "DELETE":
                $controller->destroy($id);
                break;
        }
        break;
    case "tipodocumento":
        Auth::verify(); // 🔐 PROTECCIÓN JWT
        $controller = new TipoDocumentoController();
        switch ($method) {
            case "GET":
                $controller->index();
                break;
        }
        break;
    case "generos":
        Auth::verify(); // 🔐 PROTECCIÓN JWT
        $controller = new GeneroController();
        switch ($method) {
            case "GET":
                $controller->index();
                break;
        }
        break;

    case "departamentos":
        Auth::verify(); // 🔐 PROTECCIÓN JWT
        $controller = new DepartamentoController();
        switch ($method) {
            case "GET":
                $controller->index();
                break;
        }
        break;
    case "municipios":
        Auth::verify(); // 🔐 PROTECCIÓN JWT
        $controller = new MunicipioController();
        switch ($method) {
            case "GET":
                if ($id) {
                    $controller->getByDepartamento($id);
                } else {
                    $controller->index();
                }
                break;
        }
        break;
}