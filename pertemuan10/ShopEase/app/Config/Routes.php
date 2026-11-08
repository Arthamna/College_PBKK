<?php
use CodeIgniter\Router\RouteCollection;
/** @var RouteCollection $routes */
$routes->get('/', 'ProductController::index');
$routes->get('products', 'ProductController::index');
$routes->get('products/(:num)', 'ProductController::show/$1');
$routes->get('products/(:num)/image', 'ProductController::image/$1');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::authenticate');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::store');
$routes->post('logout', 'AuthController::logout', ['filter'=>'shopAuth']);
$routes->group('', ['filter'=>'shopAuth:customer'], static function($routes) {
 $routes->get('cart','CartController::index');
 $routes->post('cart/(:num)','CartController::save/$1');
 $routes->post('cart/(:num)/remove','CartController::remove/$1');
 $routes->get('checkout','CheckoutController::index');
 $routes->post('checkout','CheckoutController::store');
 $routes->post('addresses','CheckoutController::address');
 $routes->get('orders','OrderController::index');
 $routes->get('orders/(:num)','OrderController::show/$1');
 $routes->post('orders/(:num)/payment','OrderController::submit/$1');
 $routes->get('orders/(:num)/proof','OrderController::proof/$1');
});
$routes->group('admin',['filter'=>'shopAuth:administrator,operator'],static function($routes) {
 $routes->get('orders','Admin\OrderController::index');
 $routes->get('orders/(:num)','Admin\OrderController::show/$1');
});
$routes->group('admin',['filter'=>'shopAuth:administrator'],static function($routes) {
 $routes->post('orders/(:num)/status','Admin\OrderController::change/$1');
 $routes->get('orders/(:num)/proof','Admin\OrderController::proof/$1');
 foreach (['categories','products'] as $kind) {
  $routes->get($kind,'Admin\CatalogController::index/'.$kind);
  $routes->get($kind.'/new','Admin\CatalogController::edit/'.$kind);
  $routes->get($kind.'/(:num)/edit','Admin\CatalogController::edit/'.$kind.'/$1');
  $routes->post($kind,'Admin\CatalogController::save/'.$kind);
  $routes->post($kind.'/(:num)','Admin\CatalogController::save/'.$kind.'/$1');
  $routes->post($kind.'/(:num)/remove','Admin\CatalogController::remove/'.$kind.'/$1');
 }
});
