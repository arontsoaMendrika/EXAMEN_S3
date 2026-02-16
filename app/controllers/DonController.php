<?php

namespace app\controllers;

use app\models\Don;
use app\models\Ville;

class DonController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $donModel = new Don($this->db);
        $villeModel = new Ville($this->db);
        $dons = $donModel->getAll();
        $villes = $villeModel->getAll();
        require __DIR__ . '/../views/dons.php';
    }

    public function create($data) {
        $donModel = new Don($this->db);
        $donModel->create($data);
        header('Location: /dons');
        exit;
    }
}
