<?php
require_once __DIR__ . "/../models/Departamento.php";
require_once __DIR__ . "/../config/database.php";

class DepartamentoController {
    private $departamento;


    public function __construct() {
        $db = (new Database())->connect();
        $this->departamento = new Departamento($db);
    }

    public function index() {
        echo json_encode($this->departamento->getAll());
    }
}
