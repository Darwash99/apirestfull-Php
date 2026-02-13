<?php

class User {

    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByEmail($email) {

        $sql = "SELECT * FROM {$this->table} WHERE name = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["email" => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT id, name, email FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
