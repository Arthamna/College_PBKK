<?php

declare(strict_types=1);

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ---- Public storefront
$routes->get('/', 'HomeController::index');
$routes->get('products', 'CatalogController::index');
$routes->get('products/(:segment)', 'CatalogController::show/$1');
$routes->get('media/product/(:num)', 'MediaController::product/$1');

// ---- Guests only
$routes->group('', ['filter' => 'guest'], static function (RouteCollection $routes): void {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::authenticate');
    $routes->get('register', 'AuthController::register');
    $routes->post('register', 'AuthController::store');
});

$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// ---- Customers
$routes->group('', ['filter' => 'auth:customer'], static function (RouteCollection $routes): void {
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add/(:num)', 'CartController::add/$1');
    $routes->post('cart/update/(:num)', 'CartController::update/$1');
    $routes->post('cart/remove/(:num)', 'CartController::remove/$1');

    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout', 'CheckoutController::place');
    $routes->post('addresses', 'CheckoutController::storeAddress');

    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders/(:num)/payment', 'OrderController::uploadProof/$1');
    $routes->post('orders/(:num)/cancel', 'OrderController::cancel/$1');
});

// Payment proofs: owner or staff (ownership enforced in the controller)
$routes->get('media/proof/(:num)', 'MediaController::proof/$1', ['filter' => 'auth']);

// ---- Staff: administrator + operator
$routes->group('admin', [
    'filter'    => 'auth:administrator,operator',
    'namespace' => 'App\Controllers\Admin',
], static function (RouteCollection $routes): void {
    $routes->get('/', 'DashboardController::index');

    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders/(:num)/ship', 'ShipmentController::ship/$1');
    $routes->post('orders/(:num)/delivered', 'ShipmentController::delivered/$1');

    $routes->get('products', 'ProductController::index');
    $routes->post('products/(:num)/stock', 'ProductController::updateStock/$1');
});

// ---- Administrator only
$routes->group('admin', [
    'filter'    => 'auth:administrator',
    'namespace' => 'App\Controllers\Admin',
], static function (RouteCollection $routes): void {
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/new', 'CategoryController::new');
    $routes->post('categories', 'CategoryController::create');
    $routes->get('categories/(:num)/edit', 'CategoryController::edit/$1');
    $routes->post('categories/(:num)', 'CategoryController::update/$1');
    $routes->post('categories/(:num)/delete', 'CategoryController::delete/$1');

    $routes->get('products/new', 'ProductController::new');
    $routes->post('products', 'ProductController::create');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/(:num)', 'ProductController::update/$1');
    $routes->post('products/(:num)/delete', 'ProductController::delete/$1');

    $routes->get('customers', 'CustomerController::index');

    $routes->post('orders/(:num)/verify', 'PaymentController::verify/$1');
    $routes->post('orders/(:num)/reject', 'PaymentController::reject/$1');
    $routes->post('orders/(:num)/cancel', 'OrderController::cancel/$1');

    $routes->get('reports', 'ReportController::index');
});
