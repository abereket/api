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
        $rules = ['userId'=>'required|integer','title'=>'required|string|max:50','companyName'=>'string|max:50','type'=>'string|max:50',
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
        $rules = ['userId'=>'required|integer','tittle'=>'string|max:50','companyName'=>'string|max:50','type'=>'string|max:50',
                  'link'=>'string|max:256','city'=>'string|max:50','state'=>'string|max:2','zipCode'=>'max:10'];
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
    public function retrieveOne($jobId){
        $jobService = new JobsServices();
        $job=$jobService->rerieveOne($jobId);

        return $job;
    }
}