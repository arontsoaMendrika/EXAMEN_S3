<?php

namespace app\controllers;

use app\models\Ville;
use flight\Engine;

class VilleController {
    private $db;
    protected Engine $app;

    public function __construct($db, $app = null) {
        $this->db = $db;
        $this->app = $app ?? \Flight::app();
    }

    public function index() {
        $villeModel = new Ville($this->db);
        $villes = $villeModel->getAll();
        $base_url = $this->app->get('flight.base_url');
        require __DIR__ . '/../views/villes.php';
    }

    public function create($data) {
        $villeModel = new Ville($this->db);
        $villeModel->create($data['nom']);
        header('Location: ' . $this->app->get('flight.base_url') . 'villes');
        exit;
    }
}
