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

$router->group(['prefix' => 'books'], function () use ($router) {
    $router->get('/', 'BooksController@index');
    $router->get('/{id:[\d]+}', ['as' => 'books.show', 'uses' => 'BooksController@show']);
    $router->post('/', 'BooksController@store');
    $router->put('/{id:[\d]+}', 'BooksController@update');
    $router->delete('/{id:[\d]+}', 'BooksController@destroy');
});

$router->group(['prefix' => 'authors'], function () use ($router) {
    $router->get('/', 'AuthorsController@index');
    $router->get('/{id:[\d]+}', [
        'as' => 'authors.show',
        'uses' => 'AuthorsController@show'
    ]);
    $router->post('/', 'AuthorsController@store');
    $router->put('/{id:[\d]+}', 'AuthorsController@update');
    $router->delete('/{id:[\d]+}', 'AuthorsController@destroy');
});
