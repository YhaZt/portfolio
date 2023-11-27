<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('api/tasks', 'Tasks::index' , ['filter' => 'cors']);

$routes->post('addtasks', 'Tasks::create');
