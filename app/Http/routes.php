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

////Mapping of Agency controller

$app->group(['prefix'=>'api/v1','namespace'=>'App\Http\Controllers'],function($app){

//agency mapping
 $app->post('agencies','AgencyController@create');                  //creates agencies
 $app->get('agencies','AgencyController@retrieve');                 //retrieves the user
 $app->get('agencies/{agency_id}','AgencyController@retrieveOne');    //retrieves one user
 $app->put('agencies/{agency_id}','AgencyController@update') ;        //updates agencies
 $app->delete('agencies/{agency_id}','AgencyController@delete');      //soft deletes agency



});
