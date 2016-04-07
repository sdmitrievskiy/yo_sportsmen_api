<?php

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

$app->get('/', function () use ($app) {
    //return $app->welcome();
    return $app->version();
});

//авторизация
$app->post('/auth/vk','UserController@auth');

//получение списка видов спорта
$app->get('/sporttype/get', 'SportTypesController@getSportTypesList');

//получение видов спорта пользователя
$app->get('user/{id}/sporttypes', 'UserController@getUserSportTypes');

//установить виды спорта для пользователя
$app->post('user/{id}/sporttypes', 'UserController@setUserSportTypes');

$app->put('user/{id}', 'UserController@updateUser');

