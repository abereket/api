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
        $len = count($request->json()->get('skill_names'));
        $i   = 0;
        do{
            $rules =['user_id'=>'required|integer','survey_id'=>'required|integer','skill_names'=>'required|array',
                     "skill_names.$i.skill_name"=>'required|string|max:50'];
            $this->validate($request,$rules);
            $i++;
        }while($i<$len);


        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->create($request);
        return response()->json($surveySkills);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request){
        $len = count($request->json()->get('skill_names'));
        $i   = 0;
        do{
            $rules =['user_id'=>'required|integer','survey_id'=>'required|integer','skill_names'=>'required|array',
                "skill_names.$i.skill_name"=>'required|string|max:50'];
            $this->validate($request,$rules);
            $i++;
        }while($i<$len);

        $surveySkillService = new SurveySkillsService();
        $surveySkills = $surveySkillService->update($request);
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