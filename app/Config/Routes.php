<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/accounts', 'Account::index');
$routes->post('/accounts/transfer', 'Account::transfer');

service('auth')->routes($routes);
