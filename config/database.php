<?php
class Database {
    private $host = "127.0.0.1";
    private $db = "sinergia";
    private $user = "root";
    private $pass = "";
    private $port = "3307";

    public function connect() {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};dbname={$this->db}",
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]//para retornar el error de labase de datos
        );
    }
}
