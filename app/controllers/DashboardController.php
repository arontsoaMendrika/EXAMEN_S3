<?php

namespace app\controllers;

use flight\Engine;

class DashboardController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function index() {
		$db = $this->app->db();

		// Query to get cities with their disasters (sinistres)
		$query = "
			SELECT v.nom AS ville, r.nom AS region, b.nom AS besoin, b.type_besoin, b.prix, b.quantite
			FROM ville v
			JOIN region r ON v.region_id = r.id
			JOIN sinistres s ON v.id = s.ville_id
			JOIN besoins b ON s.besoin_id = b.id
			ORDER BY v.nom, b.nom
		";

		$sinistres = $db->fetchAll($query);

		$this->app->render('accueil', [ 'sinistres' => $sinistres ]);
	}
}