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
        $besoins = $besoinModel->getAll();
        $villes = $villeModel->getAll();
        require __DIR__ . '/../views/besoins.php';
    }

    public function create($data) {
        $besoinModel = new Besoin($this->db);
        $titre = $data['titre'] ?? '';
        $description = $data['description'] ?? '';
        $quantite = $data['quantite'] ?? null;
        $prix_unitaire = $data['prix_unitaire'] ?? null;
        $ville = $data['ville'] ?? null;

        $besoinModel->create($titre, $description, $quantite, $prix_unitaire, null, null, $ville);
        header('Location: /besoins');
        exit;
    }

    public function delete($id) {
        $besoinModel = new Besoin($this->db);
        $besoinModel->delete($id);
        header('Location: /besoins');
        exit;
    }
}
