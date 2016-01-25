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
        $rules = ['user_id'=>'required|integer','tittle'=>'required|string|max:50','company_name'=>'string|max:50','type'=>'string|max:50',
                  'link'=>'string|max:256','city'=>'string|max:50','state'=>'string|max:2','zipCode'=>'max:10'];
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
        $rules = ['user_id'=>'required|integer','tittle'=>'string|max:50','company_name'=>'string|max:50','type'=>'string|max:50',
                  'link'=>'string|max:256','city'=>'string|max:50','state'=>'string|max:2','zip_code'=>'max:10'];
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