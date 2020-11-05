<?php

$router->post('login', 'AuthController@login');
$router->get('logout', 'AuthController@logout');
$router->get('refresh', 'AuthController@refresh');

$router->group(['prefix' => 'real-states', 'middleware' => 'auth'], function () use ($router) {
    $router->get('', 'RealStateController@index');
    $router->get('{id}', 'RealStateController@show');
    $router->post('', 'RealStateController@store');
    $router->put('{id}', 'RealStateController@update');
    $router->delete('{id}', 'RealStateController@destroy');
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('', 'UserController@index');
    $router->get('{id}', 'UserController@show');
    $router->post('', 'UserController@store');
    $router->put('{id}', 'UserController@update');
    $router->delete('{id}', 'UserController@destroy');
});

$router->group(['prefix' => 'categories'], function () use ($router) {
    $router->get('', 'CategoryController@index');
    $router->get('{id}', 'CategoryController@show');
    $router->post('', 'CategoryController@store');
    $router->put('{id}', 'CategoryController@update');
    $router->delete('{id}', 'CategoryController@destroy');
    $router->get('{id}/real-states', 'CategoryController@realStates');
});