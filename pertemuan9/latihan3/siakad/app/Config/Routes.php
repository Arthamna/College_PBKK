<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setAutoRoute(FALSE);
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'auth'], static function (RouteCollection $routes)
{
	$routes->get('/', 'Dashboard::index');
	$routes->get('mahasiswa', 'Mahasiswa::index');
	$routes->get('mahasiswa/create', 'Mahasiswa::create');
	$routes->post('mahasiswa', 'Mahasiswa::store');
	$routes->get('mahasiswa/(:num)/edit', 'Mahasiswa::edit/$1');
	$routes->post('mahasiswa/(:num)/update', 'Mahasiswa::update/$1');
	$routes->post('mahasiswa/(:num)/delete', 'Mahasiswa::delete/$1');
});
