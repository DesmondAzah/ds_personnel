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

$router->get('/personnel', 'PersonnelController@getPersonnelList');
$router->get('/{id}/personnel', 'PersonnelController@getPersonnelRequest');
$router->get('/personnel/{personnel}', 'PersonnelController@getPersonnel');
$router->post('/personnel', 'PersonnelController@addPersonnel');
$router->put('/personnel/{personnel}', 'PersonnelController@updatePersonnel');
$router->delete('/personnel/{personnel}', 'PersonnelController@deletePersonnel');
$router->get("/allPersonnel", "PersonnelController@getAllPersonnel");

$router->get('/personnelDetail/{personnel}', 'PersonnelController@getPersonnelDetails');
$router->post('/personnel/details', 'PersonnelController@addPersonnelDetail');
$router->put('/personnel/details/{id}', 'PersonnelController@updatePersonnelDetails');



