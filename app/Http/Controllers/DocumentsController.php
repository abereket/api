<?php
namespace App\Http\Controllers;

use App\Services\DocumentsService;
use Illuminate\Http\Request;

class DocumentsController extends Controller{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request){
        $rules = ['user_id'=>'required|integer','name'=>'required|string|max:60','path'=>'required|string|max:256','type'=>'required|string|max:15'];
        $this->validate($request,$rules);

        $documentService = new DocumentsService();
        $documents = $documentService->create($request);

        return response()->json($documents);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($id){
         $documentService = new DocumentsService();
         $documents = $documentService->retrieveOne($id);

         return response()->json($documents);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id){
         $documentService = new DocumentsService();
         $documents = $documentService->delete($id);

        return response()->json($documents);
    }
}