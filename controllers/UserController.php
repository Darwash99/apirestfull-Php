<?php
require_once __DIR__ . "/../models/Paciente.php";
require_once __DIR__ . "/../config/database.php";

class UserController {
    private $paciente;

    public function __construct() {
        $db = (new Database())->connect();
        $this->paciente = new Paciente($db);
    }

    public function index() {
        $buscar = $_GET['buscar'] ?? '';
        $pagina = $_GET['pagina'] ?? 1;
        $limit = $_GET['limit'] ?? 10;

        $result = $this->paciente->getAll($buscar, $pagina, $limit);

        echo json_encode($result);
    }

    public function store($data) {
        $error = $this->validate($data);
        if ($error) {
            http_response_code(400);
            echo json_encode(["error" => $error]);
            return;
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["error" => "Correo inválido"]);
            return;
        }
        try {
            $this->paciente->create($data);
            echo json_encode(["message" => "Paciente creado"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear paciente: " . $e->getMessage()]);
        }
    }

    public function update($id, $data) {
        try {
            $existing = $this->paciente->getByPaciente($id);
            if (!$existing) {
                http_response_code(404);
                echo json_encode(["error" => "Paciente no encontrado"]);
                return;
            }
            
            $error = $this->validate($data);
            if ($error) {
                http_response_code(400);
                echo json_encode(["error" => $error]);
                return;
            }

            $this->paciente->update($id, $data);

            echo json_encode(["message" => "Paciente actualizado correctamente"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar paciente: " . $e->getMessage()]);
            return;
        }
    }

    public function getByPaciente($id) {
        $paciente = $this->paciente->getByPaciente($id);
        echo json_encode($paciente);
    }

    public function destroy($id) {
        $this->paciente->delete($id);
        echo json_encode(["message" => "Paciente eliminado"]);
    }

    private function validate($data) {
        if (empty($data['nombre1'])) {
            return "El nombre es obligatorio";
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return "Correo inválido";
        }
        return null;
    }
}
