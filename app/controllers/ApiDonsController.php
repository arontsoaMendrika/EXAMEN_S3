<?php

namespace app\controllers;

use app\models\Don;
use Flight;

class ApiDonsController
{
    private $donModel;

    public function __construct()
    {
        $this->donModel = new Don(Flight::db());
    }

    public function getDons()
    {
        $dons = $this->donModel->getAll();
        Flight::json($dons);
    }

    public function getDon($id)
    {
        $don = $this->donModel->getById($id);
        if ($don) {
            Flight::json($don);
        } else {
            Flight::json(['error' => 'Don not found'], 404);
        }
    }

    public function createDon()
    {
        $data = Flight::request()->data;
        if (empty($data->nom) || empty($data->montant) || empty($data->date_don)) {
            Flight::json(['error' => 'Missing required fields'], 400);
            return;
        }
        $id = $this->donModel->create([
            'nom' => $data->nom,
            'montant' => $data->montant,
            'date_don' => $data->date_don
        ]);
        Flight::json(['id' => $id, 'message' => 'Don created'], 201);
    }

    public function updateDon($id)
    {
        $data = Flight::request()->data;
        if (empty($data->nom) || empty($data->montant) || empty($data->date_don)) {
            Flight::json(['error' => 'Missing required fields'], 400);
            return;
        }
        $rows = $this->donModel->update($id, [
            'nom' => $data->nom,
            'montant' => $data->montant,
            'date_don' => $data->date_don
        ]);
        if ($rows > 0) {
            Flight::json(['message' => 'Don updated']);
        } else {
            Flight::json(['error' => 'Don not found'], 404);
        }
    }

    public function deleteDon($id)
    {
        $rows = $this->donModel->delete($id);
        if ($rows > 0) {
            Flight::json(['message' => 'Don deleted']);
        } else {
            Flight::json(['error' => 'Don not found'], 404);
        }
    }
}