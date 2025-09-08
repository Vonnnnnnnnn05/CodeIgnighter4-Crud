
<?php
    
use CodeIgniter\Commands\Utilities\Routes;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage route

$routes->get('/', 'ProductController::index'); // Show create view
$routes->post('products/store', 'ProductController::store');
$routes->post('products/update/(:num)', 'ProductController::update/$1');
$routes->post('products/delete/(:num)', 'ProductController::delete/$1');
