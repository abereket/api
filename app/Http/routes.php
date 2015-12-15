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

////Mapping of Team controller

$app->group(['prefix'=>'api/v1','namespace'=>'App\Http\Controllers'],function($app){

//team mapping

    $app->post('teams', 'TeamController@create');                        //creates teams
    $app->get('teams','TeamController@retrieve');                        //retrieves the team
    $app->get('teams/{team_id}','TeamController@retrieveOne');           //retrieves one team
    $app->put('teams/{team_id}','TeamController@update') ;               //updates teams
    $app->delete('teams/{team_id}','TeamController@delete');             //soft deletes team


});
