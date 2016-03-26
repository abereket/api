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
        $is_fulfilled =  $request->json()->get('is_fulfilled');
        $is_closed    =  $request->json()->get('is_closed');
        $is_active    =  $request->json()->get('is_active');
        $is_fulfilled =  (true === $is_fulfilled or false === $is_fulfilled)?[true,false]:['',''];
        $is_closed    =  (true === $is_closed or false === $is_closed)?[true,false]:['',''];
        $is_active    =  (true === $is_active or false === $is_active)?[true,false]:['',''];

        $rules = ['user_id'=>'required|integer','title'=>'required|string|max:50','company_name'=>'string|max:50',
                  'link'=>'string|max:256','is_fulfilled'=>"boolean|in:$is_fulfilled[0],$is_fulfilled[1]",
                  'is_closed'=>"boolean|in:$is_closed[0],$is_closed[1]",'is_active'=>"boolean|in:$is_active[0],$is_active[1]",
                  'city'=>'string|max:50','state'=>'string|max:2','zipCode'=>'max:10'];
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
        $is_fulfilled =  $request->json()->get('is_fulfilled');
        $is_closed    =  $request->json()->get('is_closed');
        $is_active    =  $request->json()->get('is_active');
        $is_fulfilled =  (true === $is_fulfilled or false === $is_fulfilled)?[true,false]:['',''];
        $is_closed    =  (true === $is_closed or false === $is_closed)?[true,false]:['',''];
        $is_active    =  (true === $is_active or false === $is_active)?[true,false]:['',''];

        $rules = ['user_id'=>'required|integer','title'=>'sometimes|required|string|max:50','company_name'=>'sometimes|required|string|max:50',
                  'link'=>'sometimes|required|string|max:256','is_fulfilled'=>"boolean|in:$is_fulfilled[0],$is_fulfilled[1]",
                  'is_closed'=>"boolean|in:$is_closed[0],$is_closed[1]",'is_active'=>"boolean|in:$is_active[0],$is_active[1]",
                  'city'=>'sometimes|required|string|max:50','state'=>'sometimes|required|string|max:2',
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