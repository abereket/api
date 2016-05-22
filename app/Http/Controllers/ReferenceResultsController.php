<?php
namespace App\Http\Controllers;

use App\Services\ReferenceResultsService;
use Illuminate\Http\Request;

class ReferenceResultsController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
        $rules = ['user_id'=>'required|integer','reference_id'=>'required|integer','skill_id'=>'required|integer',
                  'comments'=>'string|max:256','skill_value'=>'string|max:10'];
        $this->validate($request,$rules);
        $referenceResultService = new ReferenceResultsService();
        $referenceResult = $referenceResultService->create($request);

        return response()->json($referenceResult);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$id){
        $rules = ['user_id'=>'integer','reference_id'=>'integer','skill_id'=>'integer',
                  'comments'=>'sometimes|required|string|max:256','skill_value'=>'sometimes|required|string|max:10'];
        $this->validate($request,$rules);
        $referenceResultService  = new ReferenceResultsService();
        $referenceResult = $referenceResultService->update($request,$id);

        return response()->json($referenceResult);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $referenceResultService = new ReferenceResultsService();
        $referenceResult = $referenceResultService->retrieve($request);

        return response()->json($referenceResult);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
        $referenceResultService = new ReferenceResultsService();
        $referenceResult = $referenceResultService->retrieveOne($id);

        return response()->json($referenceResult);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id){
        $referenceResultService = new ReferenceResultsService();
        $referenceResult = $referenceResultService->delete($id);

        return response()->json($referenceResult);
    }
}