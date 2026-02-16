<?php

namespace app\models;

class Ville {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $rows = $this->db->fetchAll("SELECT * FROM ville ORDER BY nom ASC");
        return $rows;
    }

    public function create($nom) {
        $this->db->runQuery("INSERT INTO ville (nom) VALUES (?)", [$nom]);
        return true;
    }
}
