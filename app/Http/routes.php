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

//team members controller mapping

$app->group(['prefix'=>'api/v1','namespace'=>'App\Http\Controllers'],function($app){

    $app->post('team-members','TeamMemberController@create');              //creates team members
    $app->get('team-members','TeamMemberController@retrieve');             //retrieves team members
    $app->get('team-members/{id}','TeamMemberController@retrieveOne');     //retrieves one team member
    $app->put('team-members/{id}','TeamMemberController@update');          //updates team member
    $app->delete('team-members/{id}','TeamMemberController@delete');       //soft deletes a team member

});
