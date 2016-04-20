<?php
namespace App\Services;

use App\Models\Documents;
use App\Models\User;

class DocumentsService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
        $user = User::find($request->json()->get('user_id'));
        $valError = $this->validateCreate($user);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }

        //@TODO.
        //1. Add extension to documents table. And make it come from request. and store it in db

        //2. Change type to file_type, make it enum. for now make it have one value (resume)

        //3. Postman/Request will give you file_contents .. This is actual file bytes. For security frontend will base64_encode it,
        // all u need to do is base64_decode it and upload the file to AWS

        //4. Once document is uploaded to ASW, u will get the path and store it to db. The path is going to be, bucket_name/

        //5. Upload the file to AWS. Upload to the following information...
        // Bucket name: zemployee-dev
        //Path in S3: /Resumes

        $document = Documents::create(['user_id'=>$request->json()->get('user_id'),
                                       'name'=>$request->json()->get('name'),
                                       'path'=>$request->json()->get('path'),
                                       'type'=>$request->json()->get('type')]);
        $document = $this->buildCreateSuccessMessage('success',$document);
        return $document;
    }

    /**
     * @param $id
     * @return array
     */
    public function retrieveOne($id){
        $document = Documents::find($id);
        $valError = $this->validateRetrieveOne($document);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $document = $this->buildRetrieveOneSuccessMessage('success',$document);
        return $document;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id){
        $document = Documents::find($id);
        $valError = $this->validateDelete($document);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $document->delete();
        $document = $this->buildDeleteSuccessMessage('success');
        return $document;
    }
    /**
     * @param $user
     * @return array
     */
    protected function validateCreate($user){
        $errors = array();
        if(!$user){
            $errors['user_id'] = 'Please provide a valid user id.The value you entered not exists';
        }
        return $errors;
    }

    /**
     * @param $document
     * @return array
     */
    protected function validateRetrieveOne($document){
        $errors = array();
        if(!$document){
            $errors['document_id'] = 'please provide a valid document id';
        }
        return $errors;
    }

    /**
     * @param $document
     * @return array
     */
    protected function validateDelete($document){
        $errors = array();
        if(!$document){
            $errors['document_id'] = 'please provide a valid document id';
        }
        return $errors;
    }

}