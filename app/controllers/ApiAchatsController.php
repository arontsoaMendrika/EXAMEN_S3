<?php

namespace app\controllers;

use Flight;

class ApiAchatsController
{
    public function reset()
    {
        $db = Flight::db();
        try {
            $db->exec("SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE achats; SET FOREIGN_KEY_CHECKS=1;");
            // Optionally, reset besoins statut to 'en_attente'
            $db->exec("UPDATE besoins SET statut = 'en_attente'");
            Flight::json(['success' => true, 'message' => 'Achats reset']);
        } catch (\Exception $e) {
            Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}