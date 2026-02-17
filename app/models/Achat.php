<?php

namespace app\models;

class Achat {
    private $db;
    private $app;

    public function __construct($db, $app = null) {
        $this->db = $db;
        $this->app = $app ?? null;
    }

    public function getAll() {
        return $this->db->fetchAll(
            "SELECT a.*, b.nom AS besoin_nom, b.type_besoin, b.prix AS prix_unitaire, b.quantite AS quantite_besoin, 
                    d.nom AS donateur_nom, d.montant AS don_montant
             FROM achats a
             JOIN besoins b ON a.besoin_id = b.id
             JOIN dons d ON a.don_id = d.id
             ORDER BY a.date_achat DESC"
        );
    }

    /**
     * Filtre les achats par ville via les sinistres
     */
    public function getByVille($ville_id) {
        return $this->db->fetchAll(
            "SELECT a.*, b.nom AS besoin_nom, b.type_besoin, b.prix AS prix_unitaire, b.quantite AS quantite_besoin,
                    d.nom AS donateur_nom, d.montant AS don_montant, v.nom AS ville_nom
             FROM achats a
             JOIN besoins b ON a.besoin_id = b.id
             JOIN dons d ON a.don_id = d.id
             JOIN sinistres s ON s.besoin_id = b.id
             JOIN ville v ON v.id = s.ville_id
             WHERE v.id = ?
             ORDER BY a.date_achat DESC", [$ville_id]
        );
    }

    /**
     * Calcule le montant avec frais (5% de frais de gestion)
     * @throws \InvalidArgumentException si le montant est négatif
     * @throws \OverflowException si le montant dépasse le don disponible
     */
    public function calculerAvecFrais($montant, $don_id) {
        if ($montant <= 0) {
            throw new \InvalidArgumentException("Le montant doit être supérieur à 0.");
        }

        // Vérifier le solde du don
        $don = $this->db->fetchRow("SELECT montant FROM dons WHERE id = ?", [$don_id]);
        if (!$don) {
            throw new \InvalidArgumentException("Don introuvable.");
        }

        $total_achats = $this->db->fetchField(
            "SELECT COALESCE(SUM(montant), 0) FROM achats WHERE don_id = ?", [$don_id]
        );
        $solde = $don['montant'] - $total_achats;

        // Pourcentage configurable (valeur en pourcent, ex: 5)
        $percent = 5; // défaut
        if ($this->app && method_exists($this->app, 'get')) {
            $cfg = $this->app->get('frais_achat_percent');
            if (is_numeric($cfg)) {
                $percent = floatval($cfg);
            }
        }
        $rate = $percent / 100.0;
        $frais = $montant * $rate;
        $montant_total = $montant + $frais;

        if ($montant_total > $solde) {
            throw new \OverflowException(
                "Solde insuffisant. Disponible: " . number_format($solde, 2, ',', ' ') . " Ar, " .
                "Requis (frais inclus): " . number_format($montant_total, 2, ',', ' ') . " Ar."
            );
        }

        return [
            'montant_base' => $montant,
            'frais' => $frais,
            'montant_total' => $montant_total,
            'solde_restant' => $solde - $montant_total
        ];
    }

    public function create($besoin_id, $don_id, $montant) {
        $this->db->runQuery(
            "INSERT INTO achats (besoin_id, don_id, montant, date_achat) VALUES (?, ?, ?, CURDATE())",
            [$besoin_id, $don_id, $montant]
        );
    }

    /**
     * Récapitulatif des besoins : total, satisfait, reste
     */
    public function getRecapitulatif() {
        return $this->db->fetchAll(
            "SELECT b.id, b.nom, b.type_besoin, b.prix, b.quantite AS quantite_totale,
                    (b.prix * b.quantite) AS montant_total_besoin,
                    COALESCE(SUM(a.montant), 0) AS montant_satisfait,
                    (b.prix * b.quantite) - COALESCE(SUM(a.montant), 0) AS montant_restant
             FROM besoins b
             LEFT JOIN achats a ON a.besoin_id = b.id
             GROUP BY b.id
             ORDER BY b.nom"
        );
    }

    /**
     * Simulation : calcule sans sauvegarder
     */
    public function simuler($besoin_id, $don_id, $quantite) {
        $besoin = $this->db->fetchRow("SELECT * FROM besoins WHERE id = ?", [$besoin_id]);
        if (!$besoin) {
            throw new \InvalidArgumentException("Besoin introuvable.");
        }
        if ($quantite <= 0) {
            throw new \InvalidArgumentException("La quantité doit être supérieure à 0.");
        }

        $montant = $besoin['prix'] * $quantite;
        $calcul = $this->calculerAvecFrais($montant, $don_id);

        return array_merge($calcul, [
            'besoin_nom' => $besoin['nom'],
            'prix_unitaire' => $besoin['prix'],
            'quantite' => $quantite,
            'statut' => 'en_attente' // statut simulé
        ]);
    }

    /**
     * Distribuer les dons selon une stratégie (simulation, sans sauvegarde)
     * mode: 'priority' | 'asc' | 'proportional'
     */
    public function distribuer($mode = 'priority', $besoin_filter = null) {
        // Récupérer besoins et dons avec soldes restants
        // If a specific besoin is requested, filter to that besoin only
        if ($besoin_filter) {
            $besoins = $this->db->fetchAll("SELECT b.id, b.nom, b.prix, b.quantite, (b.prix * b.quantite) AS montant_total_besoin FROM besoins b WHERE b.id = ? ORDER BY b.id ASC", [$besoin_filter]);
        } else {
            $besoins = $this->db->fetchAll("SELECT b.id, b.nom, b.prix, b.quantite, (b.prix * b.quantite) AS montant_total_besoin FROM besoins b ORDER BY b.id ASC");
        }
        $dons = $this->db->fetchAll("SELECT d.id, d.nom, d.montant, COALESCE((SELECT SUM(a.montant) FROM achats a WHERE a.don_id = d.id),0) AS deja_utilise, d.date_don FROM dons d ORDER BY d.date_don ASC");

        // Convert to arrays
        $bes = is_array($besoins) ? $besoins : [];
        $donArr = is_array($dons) ? $dons : [];

        // Compute remaining for each don
        foreach ($donArr as &$dd) {
            $dd['restant'] = $dd['montant'] - $dd['deja_utilise'];
            if ($dd['restant'] < 0) $dd['restant'] = 0;
        }
        unset($dd);

        // Prepare besoins list according to mode
        $needList = [];
        foreach ($bes as $b) {
            // Find linked ville (if any) via sinistres
            $villeRow = $this->db->fetchRow("SELECT v.nom AS ville_nom FROM sinistres s JOIN ville v ON v.id = s.ville_id WHERE s.besoin_id = ? LIMIT 1", [$b['id']]);
            $ville_nom = $villeRow['ville_nom'] ?? null;
            $needList[] = [
                'id' => $b['id'],
                'nom' => $b['nom'],
                'montant_total' => floatval($b['montant_total_besoin']),
                'restant' => floatval($b['montant_total_besoin']),
                'ville_nom' => $ville_nom
            ];
        }

        if ($mode === 'asc') {
            // asc : petit besoin -> grand besoin
            usort($needList, function($a, $b) { return $a['montant_total'] <=> $b['montant_total']; });
        } elseif ($mode === 'desc') {
            // desc (selon spécification utilisateur): celui qui a la plus petite demande reçoit en premier
            usort($needList, function($a, $b) { return $a['montant_total'] <=> $b['montant_total']; });
        } elseif ($mode === 'priority') {
            // priority: keep order as in DB (id asc)
        } elseif ($mode === 'proportional') {
            // proportional handled differently below
        }

        $allocations = []; // besoin_id => list of {don_id, amount}

        if ($mode === 'proportional') {
            // Total need
            $totalNeed = array_sum(array_column($needList, 'montant_total'));
            $totalDon = array_sum(array_column($donArr, 'restant'));
            if ($totalNeed <= 0 || $totalDon <= 0) {
                return ['success' => false, 'error' => 'Aucun besoin ou aucun don disponible.'];
            }

            // For each need, compute allocation target = min(need, totalDon * need/totalNeed)
            foreach ($needList as $need) {
                $target = ($totalDon * ($need['montant_total'] / $totalNeed));
                // arrondir vers le bas (floor) comme demandé
                $remainingNeed = min($need['montant_total'], floor($target));
                $toAllocate = $remainingNeed;
                // allocate from dons sequentially by date
                foreach ($donArr as &$don) {
                    if ($toAllocate <= 0) break;
                    if ($don['restant'] <= 0) continue;
                    $take = min($don['restant'], $toAllocate);
                    // ensure integer/floor allocation
                    $take = floor($take);
                    $don['restant'] -= $take;
                    $toAllocate -= $take;
                    $allocations[$need['id']][] = ['don_id' => $don['id'], 'montant' => $take];
                }
                unset($don);
            }
        } else {
            // Sequential allocation: iterate needs in order, consume donations in order
            foreach ($needList as $need) {
                $remainingNeed = $need['restant'];
                if ($remainingNeed <= 0) continue;
                foreach ($donArr as &$don) {
                    if ($remainingNeed <= 0) break;
                    if ($don['restant'] <= 0) continue;
                    $take = min($don['restant'], $remainingNeed);
                    // arrondir vers le bas
                    $take = floor($take);
                    $don['restant'] -= $take;
                    $remainingNeed -= $take;
                    $allocations[$need['id']][] = ['don_id' => $don['id'], 'montant' => $take];
                }
                unset($don);
            }
        }

        // Build summary
        $summary = [];
        foreach ($needList as $need) {
            $alloc = $allocations[$need['id']] ?? [];
            $sumAlloc = array_sum(array_map(function($a){ return $a['montant']; }, $alloc));
            // Determine statut: comble if fully satisfied, en_attente otherwise
            $statut = ($sumAlloc >= $need['montant_total']) ? 'comble' : 'en_attente';

            $summary[] = [
                'besoin_id' => $need['id'],
                'besoin_nom' => $need['nom'],
                'ville_nom' => $need['ville_nom'] ?? null,
                'montant_total' => $need['montant_total'],
                'montant_attribue' => $sumAlloc,
                'statut' => $statut,
                'allocations' => $alloc
            ];
        }

        return ['success' => true, 'mode' => $mode, 'summary' => $summary, 'dons' => $donArr];
    }
}
