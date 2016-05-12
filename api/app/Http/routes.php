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

	////////////////////
	//Общие методы
	///////////////////

//авторизация
$app->post('/auth/vk','UserController@auth');

	////////////////////
	//Виды спорта
	///////////////////

//получение списка видов спорта
$app->get('/sporttype/list', 'SportTypesController@getSportTypesList');

	////////////////////
	//Пользователь
	///////////////////
//получить инфо для пользователя
$app->get('user/{id}', 'UserController@getUser');

//получение видов спорта пользователя
$app->get('/user/{id}/sporttypes', 'UserController@getUserSportTypes');

//установить виды спорта для пользователя
$app->post('/user/{id}/sporttypes', 'UserController@setUserSportTypes');

//обновить информацию пользователя
$app->put('/user/{id}', 'UserController@updateUser');

	////////////////////
	//Календарь
	///////////////////

//создать календарь
$app->post('/calendar', 'CalendarController@addCalendar');
//получить календарь
$app->get('calendar/{id}', 'CalendarController@getCalendar');
//обновить календарь
$app->put('calendar/{id}', 'CalendarController@updateCalendar');
//создать событие календаря
$app->post('/calendar/{id}/event', 'CalendarController@createEvent');
//получить календарь пользователя
$app->get('/user/{id}/calendar', 'CalendarController@getUserCalendar');

//получить событие
//to-do проверка календаря
$app->get('/calendar/{calendar}/event/{event}', 'CalendarController@getEvent');

//получить события календаря
$app->get('/calendar/{calendar}/events', 'CalendarController@getCalendarEvents');

//обновить событие календаря
$app->put('/calendar/{calendar}/event/{event}', 'CalendarController@updateEvent');

