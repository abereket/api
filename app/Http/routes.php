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

    $app->post('teams', 'TeamController@create');                                //creates teams
    $app->get('teams','TeamController@retrieve');                                //retrieves the team
    $app->get('teams/{team_id}','TeamController@retrieveOne');                   //retrieves one team
    $app->put('teams/{team_id}','TeamController@update') ;                       //updates teams
    $app->delete('teams/{team_id}','TeamController@delete');                     //soft deletes team

    $app->post('team-members','TeamMemberController@create');                     //creates team members
    $app->get('team-members','TeamMemberController@retrieve');                    //retrieves team members
    $app->get('team-members/{id}','TeamMemberController@retrieveOne');            //retrieves one team member
    $app->put('team-members/{id}','TeamMemberController@update');                 //updates team member
    $app->delete('team-members/{id}','TeamMemberController@delete');              //soft deletes a team member

    $app->post('agencies','AgencyController@create');                             //creates agencies
    $app->get('agencies','AgencyController@retrieve');                            //retrieves agencies
    $app->get('agencies/{id}','AgencyController@retrieveOne');                    //retrieves one agency
    $app->put('agencies/{id}','AgencyController@update');                         //updates an agency
    $app->delete('agencies/{id}','AgencyController@delete');                      //soft deletes an agency

    $app->post('users','UserController@create');                                  //creates users
    $app->get('users','UserController@retrieve');                                 //retrieves users
    $app->get('users/{id}','UserController@retrieveOne');                         //retrieves one user
    $app->put('users/{id}','UserController@update');                              //updates a user
    $app->delete('users/{id}','UserController@delete');                           //soft deletes a user

    $app->put('email-verification/{code}','EmailVerificationController@update');       //updates email verification
    $app->get('email-verification/{code}','EmailVerificationController@retrieveOne');  //retrieveOne email verification

    $app->post('jobs','JobController@create');                                     //creates jobs
    $app->put('jobs/{id}','JobController@update');                                 //updates jobs
    $app->delete('jobs/{id}','JobController@delete');                              //deletes jobs
    $app->get('jobs/{id}','JobController@retrieveOne');                            //retrieve one job
    $app->get('jobs','JobController@retrieve');                                    //retrieve job

    $app->post('surveys','SurveysController@create');                               //creates surveys
    $app->put('surveys/{id}','SurveysController@update');                           //updates surveys
    $app->get('surveys','SurveysController@retrieve');                              //searches surveys
    $app->get('surveys/{id}','SurveysController@retrieveOne');                      //retrieves one survey
    $app->delete('surveys/{id}','SurveysController@delete');                        //deletes a survey

    $app->post('survey-results','SurveyResultsController@create');                  //creates surveys
    $app->put('survey-results/{id}','SurveyResultsController@update');              //updates surveys
    $app->get('survey-results','SurveyResultsController@retrieve');                 //searches surveys
    $app->get('survey-results/{id}','SurveyResultsController@retrieveOne');         //retrieves one survey
    $app->delete('survey-results/{id}','SurveyResultsController@delete');           //deletes a survey

    $app->post('survey-skills','SurveySkillsController@create');                    //creates survey skills
    $app->put('survey-skills','SurveySkillsController@update');                     //updates survey skills
    $app->get('survey-skills','SurveySkillsController@retrieve');                   //searches survey skills
    $app->get('survey-skills/{id}','SurveySkillsController@retrieveOne');           //retrieves one survey skills
    $app->delete('survey-skills/{id}','SurveySkillsController@delete');             //deletes survey skills

    $app->post('authenticate','IsAuthenticatedController@authenticate');           //retrieves a user if authenticated
    $app->delete('users','TestController@deleteUsers');                                 //deletes all users
});
