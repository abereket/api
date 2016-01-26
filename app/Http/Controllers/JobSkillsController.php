<?php
namespace App\Http\Controllers;

use App\Services\JobSkillsService;
use Illuminate\Http\Request;

class JobSkillsController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){

        $len = count($request->json()->get('names'));
        $i = 0;
        do{
            $rules = ['job_id'=>'required|integer','names'=>'array|required',"names.$i.name"=>'required|string|max:50'];
            $this->validate($request,$rules);
            $i++;
        }while($i < $len);

        $jobSkillService = new JobSkillsService();
        $jobSkill = $jobSkillService->create($request);

        return response()->json($jobSkill);
    }

    /**
     * @param $jobSKillId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($jobSKillId){
        $jobSkillService = new JobSkillsService();
        $jobSkill = $jobSkillService->delete($jobSKillId);

        return response()->json($jobSkill);
    }

    /**
     * @param $jobSkillId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($jobSkillId){
        $jobSkillService = new JobSkillsService();
        $jobSkill = $jobSkillService->retrieveOne($jobSkillId);

        return response()->json($jobSkill);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $jobSkillService = new JobSkillsService();
        $jobSkill = $jobSkillService->retrieve($request);

        return response()->json($jobSkill);
    }
}