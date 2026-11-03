<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setAutoRoute(false);
$routes->get('/', 'AuthController::index');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    $routes->post('logout', 'AuthController::logout');
    $routes->get('dashboard', 'DashboardController::index');

    $routes->get('products', 'ProductController::index');
    $routes->get('products/create', 'ProductController::create');
    $routes->post('products', 'ProductController::store');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/(:num)/update', 'ProductController::update/$1');
    $routes->get('products/(:num)/image', 'ProductController::image/$1');
    $routes->post('products/(:num)/delete', 'ProductController::delete/$1', ['filter' => 'role:admin']);

    $routes->group('', ['filter' => 'role:admin'], static function (RouteCollection $routes): void {
        $routes->get('categories', 'CategoryController::index');
        $routes->get('categories/create', 'CategoryController::create');
        $routes->post('categories', 'CategoryController::store');
        $routes->get('categories/(:num)/edit', 'CategoryController::edit/$1');
        $routes->post('categories/(:num)/update', 'CategoryController::update/$1');
        $routes->post('categories/(:num)/delete', 'CategoryController::delete/$1');

        $routes->get('users', 'UserController::index');
        $routes->get('users/create', 'UserController::create');
        $routes->post('users', 'UserController::store');
        $routes->get('users/(:num)/edit', 'UserController::edit/$1');
        $routes->post('users/(:num)/update', 'UserController::update/$1');
        $routes->post('users/(:num)/delete', 'UserController::delete/$1');
    });
});
