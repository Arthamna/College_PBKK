<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setAutoRoute(FALSE);
$routes->get('/', 'CatalogController::index');
$routes->get('products/(:num)', 'CatalogController::show/$1');
$routes->get('products/(:num)/image', 'ProductImageController::show/$1');

$routes->get('cart', 'CartController::index');
$routes->post('cart/add', 'CartController::add');
$routes->post('cart/update/(:num)', 'CartController::update/$1');
$routes->post('cart/remove/(:num)', 'CartController::remove/$1');
$routes->post('cart/clear', 'CartController::clear');

$routes->get('admin/login', 'Admin\AuthController::login');
$routes->post('admin/login', 'Admin\AuthController::authenticate');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'admin_auth'], static function ($routes)
{
	$routes->get('/', static function ()
	{
		return redirect()->to('/admin/products');
	});
	$routes->post('logout', 'AuthController::logout');
	$routes->get('products', 'ProductController::index');
	$routes->get('products/create', 'ProductController::create');
	$routes->post('products', 'ProductController::store');
	$routes->get('products/(:num)/edit', 'ProductController::edit/$1');
	$routes->post('products/(:num)/update', 'ProductController::update/$1');
	$routes->post('products/(:num)/delete', 'ProductController::delete/$1');
});
