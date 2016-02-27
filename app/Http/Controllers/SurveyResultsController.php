<?php
namespace App\Http\Controllers;

use App\Services\SurveyResultsService;
use Illuminate\Http\Request;

class SurveyResultsController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
        $rules = ['user_id'=>'required|integer','job_id'=>'required|integer','survey_id'=>'required|integer',
                  'rating'=>'required|string|max:50', 'years_of_experience'=>'smallInteger'];
        $this->validate($request,$rules);

        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->create($request);
        return response()->json($surveyResult);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$id){
        $rules = ['user_id'=>'integer','job_id'=>'integer','survey_id'=>'integer',
                  'rating'=>'string|max:50', 'years_of_experience'=>'smallInteger'];
        $this->validate($request,$rules);

        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->update($request,$id);
        return response()->json($surveyResult);
    }
    public function retrieve(Request $request){
        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->retrieve($request);
        return response()->json($surveyResult);
    }
}