<?php

namespace app\controllers;

use app\models\Ville;

class VilleController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $villeModel = new Ville($this->db);
        $villes = $villeModel->getAll();
        require __DIR__ . '/../views/villes.php';
    }

    public function create($data) {
        $villeModel = new Ville($this->db);
        $villeModel->create($data['nom']);
        header('Location: /villes');
        exit;
    }
}
