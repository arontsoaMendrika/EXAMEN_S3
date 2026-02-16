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
		$app->render('dons', []);
	});

	// Besoins CRUD - pages simples
	$router->get('/besoins', function() use ($app) {
		$controller = new \app\controllers\BesoinController($app->db());
		$controller->index();
	});

	$router->post('/besoins/create', function() use ($app) {
		$controller = new \app\controllers\BesoinController($app->db());
		$controller->create($_POST);
	});

	$router->get('/besoins/delete', function() use ($app) {
		$id = $_GET['id'] ?? null;
		if ($id) {
			$controller = new \app\controllers\BesoinController($app->db());
			$controller->delete($id);
		} else {
			header('Location: /besoins');
			exit;
		}
	});

	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);

		// Dons CRUD
		$router->get('/dons', [ ApiDonsController::class, 'getDons' ]);
		$router->get('/dons/@id:[0-9]', [ ApiDonsController::class, 'getDon' ]);
		$router->post('/dons', [ ApiDonsController::class, 'createDon' ]);
		$router->put('/dons/@id:[0-9]', [ ApiDonsController::class, 'updateDon' ]);
		$router->delete('/dons/@id:[0-9]', [ ApiDonsController::class, 'deleteDon' ]);
	});
	
}, [ SecurityHeadersMiddleware::class ]);
