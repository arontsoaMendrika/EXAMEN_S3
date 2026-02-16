<?php 

namespace app\models;

class Besoin {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Convertir les résultats Collection en tableaux
     */
    private function toArray($rows) {
        return array_map(function($row) {
            return is_array($row) ? $row : $row->getData();
        }, $rows);
    }

    private function normalizeRow($row) {
        // row may be array or Collection-like object
        $r = is_array($row) ? $row : $row->getData();
        // map DB schema to view-friendly keys
        if (isset($r['nom'])) $r['titre'] = $r['nom'];
        if (isset($r['type_besoin'])) $r['description'] = $r['type_besoin'];
        if (isset($r['prix'])) $r['prix_unitaire'] = $r['prix'];
        if (!isset($r['quantite']) && isset($r['quantite'])) $r['quantite'] = $r['quantite'];
        return $r;
    }
    
    private function rowToArray($row) {
        if (!$row || (is_object($row) && count($row) === 0)) return null;
        return is_array($row) ? $row : $row->getData();
    }
    
    /**
     * Créer un nouveau besoin
     */
    public function create($titre, $description, $quantite, $prix_unitaire, $categorie_id = null, $user_id = null, $ville = null) {
        $this->db->runQuery(
            "INSERT INTO besoins (titre, description, quantite, prix_unitaire, categorie_id, user_id, ville) VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$titre, $description, $quantite, $prix_unitaire, $categorie_id, $user_id, $ville]
        );
        return true;
    }

    /**
     * Récupérer tous les besoins
     */
    public function getAll() {
        $rows = $this->db->fetchAll("SELECT b.*, v.nom AS ville FROM besoins b LEFT JOIN sinistres s ON b.id = s.besoin_id LEFT JOIN ville v ON s.ville_id = v.id ORDER BY b.id DESC");
        $arr = $this->toArray($rows);
        return array_map([$this, 'normalizeRow'], $arr);
    }
    
    /**
     * Récupérer tous les besoins d'un utilisateur
     */
    public function findByUserId($user_id) {
        $rows = $this->db->fetchAll("
            SELECT b.*, v.nom AS ville
            FROM besoins b
            LEFT JOIN sinistres s ON b.id = s.besoin_id
            LEFT JOIN ville v ON s.ville_id = v.id
            WHERE b.user_id = ?
            ORDER BY b.id DESC
        ", [$user_id]);
        $arr = $this->toArray($rows);
        return array_map([$this, 'normalizeRow'], $arr);
    }

        /**
         * Récupérer tous les besoins par ville
         */
        public function findByVille($ville) {
            $rows = $this->db->fetchAll("
                SELECT b.*, v.nom AS ville
                FROM besoins b
                LEFT JOIN sinistres s ON b.id = s.besoin_id
                LEFT JOIN ville v ON s.ville_id = v.id
                WHERE v.nom = ?
                ORDER BY b.id DESC
            ", [$ville]);
            $arr = $this->toArray($rows);
            return array_map([$this, 'normalizeRow'], $arr);
        }

        /**
         * Supprimer un besoin
         */
        public function delete($id) {
            $this->db->runQuery("DELETE FROM besoins WHERE id = ?", [$id]);
            return true;
        }
}
