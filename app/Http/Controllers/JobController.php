<?php
namespace App\Http\Controllers;

use App\Services\JobsServices;
use Illuminate\Http\Request;

class JobController extends Controller{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){

         if($request->json()->get('is_closed')===true or $request->json()->get('is_closed')===false){
             $array=[true,false];
         }else{
             $array = ['',''];
         }

        $rules = ['user_id'=>'required|integer','title'=>'required|string|max:50','company_name'=>'string|max:50',
                  'link'=>'string|max:256','is_fulfilled'=>'boolean','is_closed'=>"boolean|in:$array[0],$array[1]",'is_active'=>'boolean','city'=>'string|max:50','state'=>'string|max:2','zipCode'=>'max:10'];
        $this->validate($request,$rules);

        $jobService = new JobsServices();
        $job = $jobService->create($request);

        return response()->json($job);
    }

    /**
     * @param Request $request
     * @param $jobId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$jobId){
        $rules = ['user_id'=>'required|integer','title'=>'sometimes|required|string|max:50','company_name'=>'sometimes|required|string|max:50',
                  'link'=>'sometimes|required|string|max:256','is_fulfilled'=>'boolean','is_closed'=>'boolean','is_active'=>'boolean','city'=>'sometimes|required|string|max:50','state'=>'sometimes|required|string|max:2',
                  'zip_code'=>'sometimes|required|max:10'];
        $this->validate($request,$rules);

        $jobService = new JobsServices();
        $job = $jobService->update($request,$jobId);

        return response()->json($job);
    }

    /**
     * @param $jobId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($jobId){
        $jobService = new JobsServices();
        $job = $jobService->delete($jobId);

        return response()->json($job);
    }

    /**
     * @param $jobId
     * @return array|string
     */
    public function retrieveOne($jobId){
        $jobService = new JobsServices();
        $job=$jobService->retrieveOne($jobId);

        return response()->json($job);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $jobService = new JobsServices();
        $job = $jobService->retrieve($request);

        return response()->json($job);
    }
}