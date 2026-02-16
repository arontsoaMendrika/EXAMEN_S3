<?php

namespace app\controllers;

use app\models\Don;
use app\models\Ville;

use flight\Engine;

class DonController {
    private $db;
    protected Engine $app;

    public function __construct($db, $app = null) {
        $this->db = $db;
        $this->app = $app ?? \Flight::app();
    }

    public function index() {
        $donModel = new Don($this->db);
        $villeModel = new Ville($this->db);
        $dons = $donModel->getAll();
        $villes = $villeModel->getAll();
        $base_url = $this->app->get('flight.base_url');
        require __DIR__ . '/../views/dons.php';
    }

    public function create($data) {
        $donModel = new Don($this->db);
        $donModel->create($data);
        header('Location: ' . $this->app->get('flight.base_url') . 'dons');
        exit;
    }
}
