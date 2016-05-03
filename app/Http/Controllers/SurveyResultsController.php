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
                  'survey_skill_id'=>'required|integer', 'rating'=>'required|string|max:50',
                  'years_of_experience'=>'integer'];
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
                  'rating'=>'sometimes|required|string|max:50', 'years_of_experience'=>'Integer'];
        $this->validate($request,$rules);

        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->update($request,$id);
        return response()->json($surveyResult);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->retrieve($request);
        return response()->json($surveyResult);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->retrieveOne($id);
        return response()->json($surveyResult);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id){
        $surveyResultService = new SurveyResultsService();
        $surveyResult = $surveyResultService->delete($id);
        return response()->json($surveyResult);
    }
}