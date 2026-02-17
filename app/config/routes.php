<?php

use app\controllers\ApiDonsController;
use app\controllers\ApiExampleController;
use app\controllers\DashboardController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', [ DashboardController::class, 'index' ]);

	$router->get('/dons', function() use ($app) {
		$controller = new \app\controllers\DonController($app->db(), $app);
		$controller->index();
	});

	// Besoins CRUD - pages simples
	$router->get('/besoins', function() use ($app) {
		$controller = new \app\controllers\BesoinController($app->db(), $app);
		$controller->index();
	});

	$router->post('/besoins/create', function() use ($app) {
		$controller = new \app\controllers\BesoinController($app->db(), $app);
		$controller->create($_POST);
	});

	$router->get('/besoins/delete', function() use ($app) {
		$id = $_GET['id'] ?? null;
		if ($id) {
			$controller = new \app\controllers\BesoinController($app->db(), $app);
			$controller->delete($id);
		} else {
			header('Location: ' . $app->get('flight.base_url') . 'besoins');
			exit;
		}
	});

	$router->get('/besoins/edit', function() use ($app) {
		$id = $_GET['id'] ?? null;
		if ($id) {
			$controller = new \app\controllers\BesoinController($app->db(), $app);
			$controller->edit($id);
		} else {
			header('Location: ' . $app->get('flight.base_url') . 'besoins');
			exit;
		}
	});

	$router->post('/besoins/update', function() use ($app) {
		$controller = new \app\controllers\BesoinController($app->db(), $app);
		$controller->update($_POST);
	});

	// Villes CRUD
	$router->get('/villes', function() use ($app) {
		$controller = new \app\controllers\VilleController($app->db(), $app);
		$controller->index();
	});

	$router->post('/villes/create', function() use ($app) {
		$controller = new \app\controllers\VilleController($app->db(), $app);
		$controller->create($_POST);
	});

<<<<<<< Updated upstream
=======
	// === V2 : Achats ===
	$router->get('/achats', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->index();
	});

	$router->post('/achats/create', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->create($_POST);
	});

	// === V2 : Simulation (page Ajax) ===
	$router->get('/simulation', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->simulation();
	});

	// Distribution / Reset API
	$router->post('/api/achats/distribute', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->apiDistribute();
	});

	$router->post('/api/achats/reset', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->apiReset();
	});

	// === V2 : Récapitulation (page Ajax) ===
	$router->get('/recapitulation', function() use ($app) {
		$controller = new \app\controllers\AchatController($app->db(), $app);
		$controller->recapitulation();
	});

	// Configuration applicative (modifier frais de gestion)
	$router->get('/config', function() use ($app) {
		$controller = new \app\controllers\ConfigController($app);
		$controller->index();
	});

	$router->post('/config/update', function() use ($app) {
		$controller = new \app\controllers\ConfigController($app);
		$controller->update($_POST);
	});

>>>>>>> Stashed changes
	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]+', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]+', [ ApiExampleController::class, 'updateUser' ]);

		// Dons CRUD
		$router->get('/dons', [ ApiDonsController::class, 'getDons' ]);
		$router->get('/dons/@id:[0-9]+', [ ApiDonsController::class, 'getDon' ]);
		$router->post('/dons', [ ApiDonsController::class, 'createDon' ]);
		$router->put('/dons/@id:[0-9]+', [ ApiDonsController::class, 'updateDon' ]);
		$router->delete('/dons/@id:[0-9]+', [ ApiDonsController::class, 'deleteDon' ]);

		// V2 API - Simulation
		$router->post('/achats/simuler', function() use ($router) {
			$app = \Flight::app();
			$controller = new \app\controllers\AchatController($app->db(), $app);
			$controller->apiSimuler();
		});

		$router->post('/achats/valider', function() use ($router) {
			$app = \Flight::app();
			$controller = new \app\controllers\AchatController($app->db(), $app);
			$controller->apiValider();
		});

		// V2 API - Récapitulation
		$router->get('/recap', function() use ($router) {
			$app = \Flight::app();
			$controller = new \app\controllers\AchatController($app->db(), $app);
			$controller->apiRecap();
		});
	});
	
}, [ SecurityHeadersMiddleware::class ]);
