<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Ville;

use flight\Engine;

class BesoinController {
    private $db;
    protected Engine $app;

    public function __construct($db, $app = null) {
        $this->db = $db;
        $this->app = $app ?? \Flight::app();
    }

    public function index() {
        $besoinModel = new Besoin($this->db);
        $villeModel = new Ville($this->db);
        $besoins = $besoinModel->getAll();
        $villes = $villeModel->getAll();
        $base_url = $this->app->get('flight.base_url');
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
        header('Location: ' . $this->app->get('flight.base_url') . 'besoins');
        exit;
    }

    public function delete($id) {
        $besoinModel = new Besoin($this->db);
        $besoinModel->delete($id);
        header('Location: ' . $this->app->get('flight.base_url') . 'besoins');
        exit;
    }
}
