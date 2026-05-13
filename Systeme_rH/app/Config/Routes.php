<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'AuthController::form');
$routes->get('/login', 'AuthController::form');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {
	$routes->group('admin', ['filter' => 'role:admin'], static function (RouteCollection $routes) {
		$routes->get('dashboard', static fn () => view('admin/dashboard'));
		$routes->get('employes', static fn () => view('admin/employes'));
		$routes->get('employes/create', static fn () => redirect()->to('/admin/employes'));
		$routes->get('departements', static fn () => redirect()->to('/admin/dashboard'));
		$routes->get('types_conges', static fn () => redirect()->to('/admin/dashboard'));
		$routes->get('soldes', static fn () => redirect()->to('/admin/dashboard'));
	});

	$routes->group('rh', ['filter' => 'role:admin,rh'], static function (RouteCollection $routes) {
		$routes->get('dashboard', static fn () => view('rh/index'));
		$routes->get('demandes', static fn () => view('rh/index'));
		$routes->get('historique', static fn () => view('rh/index'));
		$routes->get('soldes', static fn () => view('rh/index'));
	});

	$routes->group('employe', ['filter' => 'role:employe'], static function (RouteCollection $routes) {
		$routes->get('dashboard', static fn () => view('employe/dashboard'));
		$routes->get('conges', static fn () => view('employe/index'));
		$routes->get('create', static fn () => view('employe/create'));
		$routes->post('store', static fn () => redirect()->to('/employe/dashboard')->with('success', 'Demande enregistrée'));
	});
});