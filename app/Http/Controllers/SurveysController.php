<?php
namespace App\Http\Controllers;

use App\Services\surveysService;
use Illuminate\Http\Request;

class SurveysController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
      $rules = ['job_id'=>'required|integer','user_id'=>'required|integer','name'=>'string|max:100'];
      $this->validate($request,$rules);

      $surveyService = new surveysService();
      $survey = $surveyService->create($request);
      return response()->json($survey);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$id){
        $rules = ['job_id'=>'required|integer','user_id'=>'integer',"name"=>'string|max:100'];
        $this->validate($request,$rules);
        
        $surveyService = new surveysService();
        $survey = $surveyService->update($request,$id);
        return response()->json($survey);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $surveyService = new surveysService();
        $survey = $surveyService->retrieve($request);
        return response()->json($survey);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
        $surveyService = new surveysService();
        $survey = $surveyService->retrieveOne($id);
        return response()->json($survey);
    }

    /**
     * @param $id
     * @return array|string
     */
    public function delete($id){
        $surveyService = new surveysService();
        $survey = $surveyService->delete($id);
        return $survey;
    }
}
