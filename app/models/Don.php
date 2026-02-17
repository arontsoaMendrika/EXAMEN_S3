<?php

namespace app\models;

use PDO;

class Don
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM dons ORDER BY date_don DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM dons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $ordre = $data['ordre'] ?? 'priority';
        $stmt = $this->db->prepare("INSERT INTO dons (nom, montant, date_don, ordre) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['nom'], $data['montant'], $data['date_don'], $ordre]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $ordre = $data['ordre'] ?? 'priority';
        $stmt = $this->db->prepare("UPDATE dons SET nom = ?, montant = ?, date_don = ?, ordre = ? WHERE id = ?");
        $stmt->execute([$data['nom'], $data['montant'], $data['date_don'], $ordre, $id]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM dons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}