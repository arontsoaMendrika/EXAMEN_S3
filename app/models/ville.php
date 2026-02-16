<?php

namespace app\models;

class Ville {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $rows = $this->db->fetchAll("SELECT * FROM villes ORDER BY nom ASC");
        return $rows;
    }

    public function create($nom) {
        $this->db->runQuery("INSERT INTO villes (nom) VALUES (?)", [$nom]);
        return true;
    }
}
