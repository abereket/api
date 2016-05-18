<?php
namespace App\Http\Controllers;
use App\Services\ReferencesService;
use Illuminate\Http\Request;

class ReferenceController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
     $rules = ['user_id'=>'required|integer','candidate_id'=>'required|integer','first_name'=>'required|string|max:50',
               'last_name'=>'required|string|max:60','email'=>'required|email|max:60','company_with_candidate'=>'required|string|max:100',
               'position'=>'required|string|max:100','relationship'=>'required|string|max:30|in:Peer,Academic,Professor,Manager',
               'contact_mobile'=>'required|string|max:20'];
     $this->validate($request,$rules);
     $referenceService = new ReferencesService();
     $reference = $referenceService->create($request);

     return response()->json($reference);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$id){
        $rules = ['user_id'=>'integer','candidate_id'=>'integer','first_name'=>'sometimes|string|max:50',
                  'last_name'=>'sometimes|string|max:60','email'=>'sometimes|email|max:60',
                  'company_with_candidate'=>'sometimes|string|max:100',
                  'position'=>'sometimes|string|max:100',
                  'relationship'=>'sometimes|required|string|max:30|in:Peer,Academic,Professor,Manager',
                  'contact_mobile'=>'sometimes|required|string|max:20'];
        $this->validate($request,$rules);
        $referenceService = new ReferencesService();
        $reference = $referenceService->update($request,$id);
        return response()->json($reference);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){
        $referenceService = new ReferencesService();
        $reference = $referenceService->retrieve($request);

        return response()->json($reference);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
        $referenceService = new ReferencesService();
        $reference = $referenceService->retrieveOne($id);

        return response()->json($reference);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id){
        $referenceService = new ReferencesService();
        $reference = $referenceService->delete($id);

        return response()->json($reference);
    }
}