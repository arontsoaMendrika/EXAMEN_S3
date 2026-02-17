<?php

namespace app\controllers;

use flight\Engine;

class ConfigController {
    private $app;

    public function __construct($app = null) {
        $this->app = $app ?? \Flight::app();
    }

    // Affiche la page de configuration
    public function index() {
        $base_url = $this->app->get('flight.base_url');
        $frais_percent = $this->app->get('frais_achat_percent') ?? 5;
        require __DIR__ . '/../views/config.php';
    }

    // Met à jour le fichier settings.json avec le nouveau pourcentage
    public function update($data) {
        $base_url = $this->app->get('flight.base_url');
        $val = $data['frais_achat_percent'] ?? null;

        if (!is_numeric($val)) {
            header('Location: ' . $base_url . 'config?error=' . urlencode('Valeur invalide'));
            exit;
        }

        $num = floatval($val);
        if ($num < 0 || $num > 100) {
            header('Location: ' . $base_url . 'config?error=' . urlencode('Le pourcentage doit être entre 0 et 100'));
            exit;
        }

        $settingsPath = __DIR__ . '/../config/settings.json';
        $payload = ['frais_achat_percent' => $num];
        $written = @file_put_contents($settingsPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($written === false) {
            header('Location: ' . $base_url . 'config?error=' . urlencode('Impossible d\'écrire le fichier de configuration (vérifier les permissions)'));
            exit;
        }

        header('Location: ' . $base_url . 'config?success=1');
        exit;
    }
}
