<?php
require_once __DIR__ . "/../models/Genero.php";
require_once __DIR__ . "/../config/database.php";

class GeneroController {
    private $genero;


    public function __construct() {
        $db = (new Database())->connect();
        $this->genero = new Genero($db);
    }

    public function index() {
        echo json_encode($this->genero->getAll());
    }
}
