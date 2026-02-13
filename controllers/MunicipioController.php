<?php
require_once __DIR__ . "/../models/Municipio.php";
require_once __DIR__ . "/../config/database.php";

class MunicipioController {
    private $municipio;


    public function __construct() {
        $db = (new Database())->connect();
        $this->municipio = new Municipio($db);
    }

    public function index() {
        echo json_encode($this->municipio->getAll());
    }

    public function getByDepartamento($id) {
        $municipios = $this->municipio->getByDepartamento($id);
        echo json_encode($municipios);
    }
}
