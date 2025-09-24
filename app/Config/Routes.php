
<?php
    
use CodeIgniter\Commands\Utilities\Routes;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage route

$routes->get('/', 'AuthController::loginform'); // Show create view
$routes->get('/register', 'AuthController::register'); // Show create view
$routes->post('/register', 'AuthController::registerUser'); // Handle registration
$routes->post('/login', 'AuthController::login'); // Handle login
$routes->get('/logout', 'AuthController::logout'); // Handle logout

$routes->get('/dashboard', 'ProductController::index'); // Show all products
$routes->post('products/store', 'ProductController::store');
$routes->post('products/update/(:num)', 'ProductController::update/$1');
$routes->post('products/delete/(:num)', 'ProductController::delete/$1');

$routes->get('/product_card', 'PageController::pc'); // Show product card view
$routes->get('/reports', 'PageController::reports'); // Show product card view