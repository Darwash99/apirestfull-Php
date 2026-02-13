<?php
class Paciente {
    private $conn;
    private $table = "pacientes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll($buscar = null, $pagina = 1, $limit = 10) {
        $offset = ($pagina - 1) * $limit;
        $sql = "
            SELECT p.*, 
                td.nombre as tipo_documento,
                g.nombre as genero,
                d.nombre as departamento,
                m.nombre as municipio
            FROM pacientes p
            JOIN tipos_documento td ON p.tipo_documento_id = td.id
            JOIN genero g ON p.genero_id = g.id
            JOIN departamentos d ON p.departamento_id = d.id
            JOIN municipios m ON p.municipio_id = m.id
            WHERE (:search = '' OR 
                p.nombre1 LIKE :searchLike OR 
                p.apellido1 LIKE :searchLike OR 
                p.correo LIKE :searchLike)
            ORDER BY p.id DESC
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->conn->prepare($sql);
        $searchLike = "%$buscar%";
        $stmt->bindParam(':search', $buscar, PDO::PARAM_STR);
        $stmt->bindParam(':searchLike', $searchLike, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM pacientes
            WHERE (:search = '' OR 
                nombre1 LIKE :searchLike OR 
                apellido1 LIKE :searchLike OR 
                correo LIKE :searchLike)
        ");

        $countStmt->bindValue(':search', $buscar, PDO::PARAM_STR);
        $countStmt->bindValue(':searchLike', $searchLike, PDO::PARAM_STR);
        $countStmt->execute();

        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        return [
            "data" => $data,
            "total" => (int)$total,
            "pagina" => (int)$pagina,
            "limit" => (int)$limit
        ];
    }

    public function create($data) {
        $sql = "
            INSERT INTO pacientes
            (tipo_documento_id, numero_documento, nombre1, nombre2,
             apellido1, apellido2, genero_id, departamento_id,
             municipio_id, correo)
            VALUES
            (:tipo_documento_id, :numero_documento, :nombre1, :nombre2,
             :apellido1, :apellido2, :genero_id, :departamento_id,
             :municipio_id, :correo)
        ";

        $stmt = $this->conn->prepare($sql);
        $data = [
            ":tipo_documento_id" => $data["tipo_documento_id"],
            ":numero_documento" => $data["numero_documento"],
            ":nombre1" => $data["nombre1"],
            ":nombre2" => $data["nombre2"] ?? null,
            ":apellido1" => $data["apellido1"],
            ":apellido2" => $data["apellido2"] ?? null,
            ":genero_id" => $data["genero_id"],
            ":departamento_id" => $data["departamento_id"],
            ":municipio_id" => $data["municipio_id"],
            ":correo" => $data["correo"]
        ];
        return $stmt->execute($data);
    }

    public function update($id, $data) {
        $sql = "
            UPDATE pacientes SET
                tipo_documento_id = :tipo_documento_id,
                numero_documento = :numero_documento,
                nombre1 = :nombre1,
                nombre2 = :nombre2,
                apellido1 = :apellido1,
                apellido2 = :apellido2,
                genero_id = :genero_id,
                departamento_id = :departamento_id,
                municipio_id = :municipio_id,
                correo = :correo
            WHERE id = :id
        ";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ":tipo_documento_id" => $data["tipo_documento_id"],
            ":numero_documento"  => $data["numero_documento"],
            ":nombre1"           => $data["nombre1"],
            ":nombre2"           => $data["nombre2"] ?? null,
            ":apellido1"         => $data["apellido1"],
            ":apellido2"         => $data["apellido2"] ?? null,
            ":genero_id"         => $data["genero_id"],
            ":departamento_id"   => $data["departamento_id"],
            ":municipio_id"      => $data["municipio_id"],
            ":correo"            => $data["correo"],
            ":id"                => $id
        ];

        return $stmt->execute($params);
    }

    public function getByPaciente($paciente_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM pacientes
            WHERE id = ?
        ");
        $stmt->execute([$paciente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
