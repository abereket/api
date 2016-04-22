<?php
namespace App\Services;

use App\Models\Documents;
use App\Models\User;
use Aws\Laravel\AwsServiceProvider;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
            $documentFileName = $request->json()->get('name').'.'.$request->json()->get('extension');
            $s3 = Storage::disk('s3');
            $filePath = '/Resumes/' .$documentFileName ;
            $contents = base64_decode($request->json()->get('document_bytes'));
            $s3->put($filePath, $contents);

        $document = Documents::create(['user_id'=>$request->json()->get('user_id'),
                                       'name'=>$request->json()->get('name'),
                                       'path'=>'zemployee-dev/Resumes/'.$documentFileName,
                                       'file_type'=>"resume",
                                       'extension' => $request->json()->get('extension')]);
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