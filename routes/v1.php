<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/**
 *
 */
$router->group(['prefix' => 'v1'], function () use ($router) {

    $router->group(['prefix' => 'auth'], function () use ($router) {

        $router->post('login', 'V1\UserController@authenticate');

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->post('logout', 'V1\UserController@logout');
            $router->patch('refresh', 'V1\UserController@refresh');
        });
    });

    $router->group(['prefix' => 'books', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'V1\BookController@index');
        $router->get('{id}', 'V1\BookController@show');
        $router->post('/', 'V1\BookController@store');
        $router->put('{id}', 'V1\BookController@update');
        $router->delete('{id}', 'V1\BookController@destroy');
    });
});
