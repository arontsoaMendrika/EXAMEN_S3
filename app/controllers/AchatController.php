<?php

namespace app\controllers;

use app\models\Achat;
use app\models\Besoin;
use app\models\Don;
use app\models\Ville;
use flight\Engine;

class AchatController {
    private $db;
    protected Engine $app;

    public function __construct($db, $app = null) {
        $this->db = $db;
        $this->app = $app ?? \Flight::app();
    }

    /**
     * Page Achat : liste avec filtre par ville, formulaire d'achat
     */
    public function index() {
        $achatModel = new Achat($this->db, $this->app);
        $villeModel = new Ville($this->db);
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);

        $villes = $villeModel->getAll();
        $besoins = $besoinModel->getAll();
        $dons = $donModel->getAll();
        $base_url = $this->app->get('flight.base_url');
        $frais_percent = $this->app->get('frais_achat_percent') ?? 5;

        $ville_id = $_GET['ville_id'] ?? null;
        $error = null;
        $success = null;

        if ($ville_id) {
            $achats = $achatModel->getByVille($ville_id);
        } else {
            $achats = $achatModel->getAll();
        }

        require __DIR__ . '/../views/achat.php';
    }

    /**
     * Créer un achat avec calcul de frais et gestion d'exceptions
     */
    public function create($data) {
        $achatModel = new Achat($this->db, $this->app);
        $base_url = $this->app->get('flight.base_url');

        try {
            $besoin_id = $data['besoin_id'] ?? null;
            $don_id = $data['don_id'] ?? null;
            $montant = floatval($data['montant'] ?? 0);

            if (!$besoin_id || !$don_id) {
                throw new \InvalidArgumentException("Veuillez sélectionner un besoin et un don.");
            }

            // Calcul avec frais (lève une exception si problème)
            $calcul = $achatModel->calculerAvecFrais($montant, $don_id);

            // Enregistrer le montant total (avec frais)
            $achatModel->create($besoin_id, $don_id, $calcul['montant_total']);

            header('Location: ' . $base_url . 'achats?success=1');
            exit;

        } catch (\InvalidArgumentException $e) {
            header('Location: ' . $base_url . 'achats?error=' . urlencode($e->getMessage()));
            exit;
        } catch (\OverflowException $e) {
            header('Location: ' . $base_url . 'achats?error=' . urlencode($e->getMessage()));
            exit;
        } catch (\Exception $e) {
            header('Location: ' . $base_url . 'achats?error=' . urlencode("Erreur inattendue: " . $e->getMessage()));
            exit;
        }
    }

    /**
     * Page Simulation (Ajax)
     */
    public function simulation() {
        $besoinModel = new Besoin($this->db);
        $donModel = new Don($this->db);
        $besoins = $besoinModel->getAll();
        $dons = $donModel->getAll();
        // ensure 'statut' is available for each besoin if present in DB
        foreach ($besoins as &$b) {
            if (!isset($b['statut'])) $b['statut'] = 'en_attente';
        }
        unset($b);
        $base_url = $this->app->get('flight.base_url');
        $frais_percent = $this->app->get('frais_achat_percent') ?? 5;
        require __DIR__ . '/../views/simulation.php';
    }

    /**
     * API Ajax : simuler un achat (sans sauvegarder)
     */
    public function apiSimuler() {
        $achatModel = new Achat($this->db, $this->app);
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
            $besoin_id = $data['besoin_id'] ?? null;
            $don_id = $data['don_id'] ?? null;
            $quantite = intval($data['quantite'] ?? 0);

            if (!$besoin_id || !$don_id) {
                throw new \InvalidArgumentException("Sélectionnez un besoin et un don.");
            }

            $result = $achatModel->simuler($besoin_id, $don_id, $quantite);
            echo json_encode(['success' => true, 'data' => $result]);

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

        /**
         * API Ajax : simuler une distribution selon un mode (priority|asc|proportional)
         */
        public function apiDistribute() {
            $achatModel = new Achat($this->db, $this->app);
            header('Content-Type: application/json');

            try {
                $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
                $mode = $data['mode'] ?? 'priority';
                $besoin_id = $data['besoin_id'] ?? null;
                $result = $achatModel->distribuer($mode, $besoin_id);
                echo json_encode($result);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        }

        /**
         * API Ajax : reset DB partial (supprime achats et exécute SQL initial si présent)
         */
        public function apiReset() {
            header('Content-Type: application/json');
            try {
                $app = $this->app;
                $db = $this->db;
                // Supprimer tous les achats
                $db->runQuery("DELETE FROM achats");

                // Chercher un fichier SQL de reset dans public/
                $candidates = [__DIR__ . '/../../public/2026_02_16_14_24.sql', __DIR__ . '/../../public/2026_02_16_14_31.sql'];
                $executed = false;
                foreach ($candidates as $file) {
                    if (file_exists($file)) {
                        $sql = file_get_contents($file);
                        // Split statements
                        $stmts = array_filter(array_map('trim', explode(';', $sql)));
                        foreach ($stmts as $s) {
                            if ($s === '') continue;
                            $db->runQuery($s);
                        }
                        $executed = true;
                        break;
                    }
                }

                echo json_encode(['success' => true, 'reset_sql_executed' => $executed]);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        }

    /**
     * API Ajax : valider (enregistrer) la simulation
     */
    public function apiValider() {
        $achatModel = new Achat($this->db, $this->app);
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
            $besoin_id = $data['besoin_id'] ?? null;
            $don_id = $data['don_id'] ?? null;
            $quantite = intval($data['quantite'] ?? 0);

            if (!$besoin_id || !$don_id) {
                throw new \InvalidArgumentException("Sélectionnez un besoin et un don.");
            }

            // Calcul complet
            $result = $achatModel->simuler($besoin_id, $don_id, $quantite);

            // Sauvegarde
            $achatModel->create($besoin_id, $don_id, $result['montant_total']);

            echo json_encode(['success' => true, 'message' => 'Achat enregistré avec succès.', 'data' => $result]);

        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Page Récapitulation
     */
    public function recapitulation() {
        $achatModel = new Achat($this->db, $this->app);
        $recap = $achatModel->getRecapitulatif();
        $base_url = $this->app->get('flight.base_url');
        $frais_percent = $this->app->get('frais_achat_percent') ?? 5;
        require __DIR__ . '/../views/recapitulation.php';
    }

    /**
     * API Ajax : récapitulatif (sans actualiser la page)
     */
    public function apiRecap() {
        $achatModel = new Achat($this->db);
        header('Content-Type: application/json');

        try {
            $recap = $achatModel->getRecapitulatif();

            $total_besoin = array_sum(array_column($recap, 'montant_total_besoin'));
            $total_satisfait = array_sum(array_column($recap, 'montant_satisfait'));
            $total_restant = array_sum(array_column($recap, 'montant_restant'));

            echo json_encode([
                'success' => true,
                'data' => $recap,
                'totaux' => [
                    'total_besoin' => $total_besoin,
                    'total_satisfait' => $total_satisfait,
                    'total_restant' => $total_restant
                ]
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
