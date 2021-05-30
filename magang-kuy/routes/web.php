<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');

    $router->get('category', 'CategoryController@index');
    $router->post('category', 'CategoryController@create');
    $router->get('category/{id}', 'CategoryController@show');
    $router->post('category/{id}', 'CategoryController@update');
    $router->delete('category/{id}', 'CategoryController@destroy');

    $router->get('job', 'JobController@index');
    $router->post('job', 'JobController@create');
    $router->get('job/by', 'JobController@show');
    $router->post('job/{id}', 'JobController@update');
    $router->delete('job/{id}', 'JobController@destroy');

    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->post('/logout', 'UserController@logout');
        $router->post('/index', 'UserController@me');

        $router->post('apply', 'ApplicationController@index');
        $router->post('apply/store', 'ApplicationController@store');
    });
});
