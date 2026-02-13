<?php
require_once __DIR__ . "/../models/TipoDocumento.php";
require_once __DIR__ . "/../config/database.php";

class TipoDocumentoController {
    private $tipo_documento;


    public function __construct() {
        $db = (new Database())->connect();
        $this->tipo_documento = new TipoDocumento($db);
    }

    public function index() {
        echo json_encode($this->tipo_documento->getAll());
    }
}
