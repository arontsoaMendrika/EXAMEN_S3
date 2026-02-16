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
    public function create($titre, $description, $categorie_id, $user_id) {
        $this->db->runQuery(
            "INSERT INTO besoins (titre, description, categorie_id, user_id) VALUES (?, ?, ?, ?)",
            [$titre, $description, $categorie_id, $user_id]
        );
        return true;
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
}