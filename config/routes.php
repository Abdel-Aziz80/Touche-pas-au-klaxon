<?php
declare(strict_types=1);

/** @var \Buki\Router\Router $router */

$router->get('/', 'HomeController@index');

// Auth
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Trips
$router->get('/trips', 'TripController@index');
$router->get('/trips/create', 'TripController@create')->middleware('AuthMiddleware');
$router->post('/trips', 'TripController@store')->middleware('AuthMiddleware');
$router->get('/trips/{id}/edit', 'TripController@edit')->middleware('AuthMiddleware');
$router->post('/trips/{id}/update', 'TripController@update')->middleware('AuthMiddleware');
$router->post('/trips/{id}/delete', 'TripController@destroy')->middleware('AuthMiddleware');

// Admin
$router->group('admin', function($router) {
    $router->get('/', 'AdminController@dashboard');
    $router->get('/agencies', 'AgencyController@index');
    $router->get('/agencies/create', 'AgencyController@create');
    $router->post('/agencies', 'AgencyController@store');
    $router->get('/agencies/{id}/edit', 'AgencyController@edit');
    $router->post('/agencies/{id}/update', 'AgencyController@update');
    $router->post('/agencies/{id}/delete', 'AgencyController@destroy');

    $router->get('/users', 'AdminController@users');
    $router->get('/trips', 'AdminController@trips');
})->middleware('AdminMiddleware');
