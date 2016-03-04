<?php
namespace App\Http\Controllers;

use App\Services\SurveySkillsService;
use Illuminate\Http\Request;

class SurveySkillsController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
        $rules =['user_id'=>'required|integer','survey_id'=>'required|integer','skill_name'=>'required|string|max:50'];
        $this->validate($request,$rules);

        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->create($request);
        return response()->json($surveySkills);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$id){
        $rules =['user_id'=>'integer','survey_id'=>'integer','skill_name'=>'sometimes|required|string|max:50'];
        $this->validate($request,$rules);

        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->update($request,$id);
        return response()->json($surveySkills);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->retrieve($request);
        return response()->json($surveySkills);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->retrieveOne($id);
        return response()->json($surveySkills);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id){
        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->delete($id);
        return response()->json($surveySkills);
    }
}