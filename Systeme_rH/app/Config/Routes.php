<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->get('logout', 'AuthController::logout');

// Employe
$routes->group('employe', ['filter' => ['auth', 'role:employe']], static function ($routes) {
	$routes->get('dashboard', 'employeController::dashboard');
	$routes->get('create', 'employeController::create');
	$routes->post('store', 'employeController::store');
	$routes->get('conges', 'employeController::conges');
	$routes->post('conges/(:num)/cancel', 'employeController::cancel/$1');
});

// RH
$routes->group('rh', ['filter' => ['auth', 'role:rh']], static function ($routes) {
	$routes->get('dashboard', 'RhController::dashboard');
	$routes->get('demandes', 'RhController::index');
	$routes->post('demandes/(:num)/status', 'RhController::updateStatus/$1');
	$routes->get('historique', 'RhController::historique');
	$routes->get('soldes', 'RhController::soldes');
});

// Admin
$routes->group('admin', ['filter' => ['auth', 'role:admin']], static function ($routes) {
	$routes->get('/', 'AdminController::dashboard');
	$routes->get('dashboard', 'AdminController::dashboard');
	$routes->get('employes', 'AdminController::employes');
	$routes->post('employes', 'AdminController::storeEmploye');
	$routes->post('employes/(:num)/update', 'AdminController::updateEmploye/$1');
	$routes->post('employes/(:num)/delete', 'AdminController::deleteEmploye/$1');
	$routes->post('employes/(:num)/toggle', 'AdminController::toggleEmploye/$1');
	$routes->get('departements', 'AdminController::departements');
	$routes->get('types_conges', 'AdminController::typesConges');
	$routes->get('soldes', 'AdminController::soldes');
});