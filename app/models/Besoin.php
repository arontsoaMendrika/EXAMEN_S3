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
        $rows = $this->db->fetchAll("SELECT b.*, c.nom as categorie_nom FROM besoins b LEFT JOIN categorie c ON b.categorie_id = c.id ORDER BY b.id DESC");
        return $this->toArray($rows);
    }
    
    /**
     * Récupérer tous les besoins d'un utilisateur
     */
    public function findByUserId($user_id) {
        $rows = $this->db->fetchAll("
            SELECT b.*, c.nom as categorie_nom 
            FROM besoins b
            LEFT JOIN categorie c ON b.categorie_id = c.id
            WHERE b.user_id = ?
            ORDER BY b.id DESC
        ", [$user_id]);
        
        return $this->toArray($rows);
    }

        /**
         * Récupérer tous les besoins par ville
         */
        public function findByVille($ville) {
            $rows = $this->db->fetchAll("
                SELECT b.*, c.nom as categorie_nom 
                FROM besoins b
                LEFT JOIN categorie c ON b.categorie_id = c.id
                WHERE b.ville = ?
                ORDER BY b.id DESC
            ", [$ville]);
            return $this->toArray($rows);
        }

        /**
         * Supprimer un besoin
         */
        public function delete($id) {
            $this->db->runQuery("DELETE FROM besoins WHERE id = ?", [$id]);
            return true;
        }
}
