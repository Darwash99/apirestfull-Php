<?php
class Municipio {
    private $conn;
    private $table = "municipios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->query("
            SELECT m.*, d.nombre as departamento
            FROM municipios m
            JOIN departamentos d ON m.departamento_id = d.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDepartamento($departamento_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM municipios
            WHERE departamento_id = ?
        ");
        $stmt->execute([$departamento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
