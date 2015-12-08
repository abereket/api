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
    return $app->welcome();
});

$app->group(['prefix'=>'api/v1','namespace'=>'App\Http\Controllers'],function($app){

    //users
    $app->post('users', 'UserController@create');                 //creates users
    $app->get('users',  'UserController@retrieve');               //retrieve users
    $app->get('users/{user_id}',  'UserController@retrieveOne');  //retrieve one
    $app->put('users/{user_id}',  'UserController@update');       //updates the user
    $app->delete('users/{user_id}','UserController@delete');      //deletes the user
});