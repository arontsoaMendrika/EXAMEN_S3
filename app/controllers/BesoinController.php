<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Ville;

class BesoinController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        $besoinModel = new Besoin($this->db);
        $villeModel = new Ville($this->db);
        $besoins = $besoinModel->findByVille(''); // Par défaut, toutes les villes
        $villes = $villeModel->getAll();
        require __DIR__ . '/../views/besoins.php';
    }

    public function create($data) {
        $besoinModel = new Besoin($this->db);
        $besoinModel->create(
            $data['titre'],
            $data['description'],
            $data['categorie_id'],
            $data['user_id'],
            $data['ville']
        );
        header('Location: /besoins');
        exit;
    }
}
