<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Pagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class AgenciesService extends Base
{
    /**
     * creates an agency
     * @param $request
     * @return array|static
     */
    public function create($request)
    {
        $user        =    User::find($request->json()->get('user_id'));
        $valError    =   $this->validateCreate($user);
        if ($valError) {
            $valError = $this->failureMessage($valError,Parent::HTTP_404);
            return $valError;
        }

        //create the agency
        $agency   =   Agency::create(['uuid' =>  Uuid::uuid(),'name'=>$request->json()->get('name'),'user_id'=>$request->json()->get('user_id'),'description'=>$request->json()->get('description')]);
        $user   =   User::find($agency->user_id);

        $emailVerification = new EmailVerificationsService();
        $code = $emailVerification->create('agency',$user->id);

        $emailService = new EmailsService();
        $from       =   "info@zemployee.com";
        $subject    =   " ";
        $body       =   "please activate your account";
        $templateId =   "67ae6661-bc1c-49ac-a70b-2205c9926b1b";
        $emailService->send($user->email,$from,$subject,$body,$user->first_name." ".$user->last_name,$code,$templateId);
        $agency=$this->buildCreateSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * @param $request
     * @return Agency|array
     */
    public function retrieve($request)
    {
        $limit          =   ($request->input('per_page'))?$request->input('per_page'):15;
        $name           =   $request->input('name');
        $description    =   $request->input('description');
        $orderBy        =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy         =   ($request->input('sort_by'))?$request->input('sort_by'):'DESC';

        $agency =  new Agency();
        if ($name) {
            $agency = $agency->where('name' , 'like', '%'.$name.'%');
        }
        if ($description) {
            $agency = $agency->where('description',  'like', '%'.$description.'%');
        }
        $agency = $agency->orderby($orderBy,$sortBy)->paginate($limit);

        $agency = $this->buildRetrieveResponse($agency->toArray());
        $agency = $this->buildRetrieveSuccessMessage("success",$agency);
        return $agency;
    }
    /**
     * retrieves an(one) agency
     * @param $agency_id
     * @return array
     */
    public function retrieveOne($agency_id)
    {
        $agency            =     Agency::find($agency_id);
        $valError          =     $this->validateRetrieveOne($agency);
        if($valError){
            $valError      =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $agency=$this->buildRetrieveOneSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * updates an agency
     * @param $request
     * @param $agency_id
     * @return mixed
     */
    public function update($request,$agency_id)
    {
        $agency        =     Agency::find($agency_id);
        $valError      =     $this->validateUpdate($agency, $request->json()->get('user_id'));
        if($valError){
            $valError  =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $agency->name         =   ($request->json()->get('name'))?($request->json()->get('name')):$agency->name;
        $agency->description  =   ($request->json()->get('description'))?($request->json()->get('description')):$agency->description;
        $agency->user_id      =   ($request->json()->get('user_id'))?($request->json()->get('user_id')):$agency->user_id;
        $agency->save();

        $agency               =    $this->buildUpdateSuccessMessage("success",$agency);
        return $agency;
    }

    /**
     * @param $agency_id
     * @return array
     */
    public function delete($agency_id){
        $agency           =   Agency::find($agency_id);
        $valError         =   $this->validateDelete($agency);
        if ($valError){
            $valError     =   $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $agency->delete();
        $agency           =   $this->buildDeleteSuccessMessage("success");
        return $agency;
    }

    /**
     * This method performs business class validation for agencies create method
     * @param $user
     * @return array
     */
    protected function validateCreate($user){
        $errors        =    array();
        if(!$user){
            $errors = "Please provide a valid user id.The value you entered not exists";
            return $errors;
        }
        $agency = Agency::where('user_id','=',$user->id)->first();
        if($agency and $user->verified ==1 ){
            $errors = "There is already an active agency for the user";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for agencies retrieveOne method
     * @param $agency
     * @return array
     */
    protected function validateRetrieveOne($agency){
        $errors       =    array();
        if(!$agency){
            $errors   =    "please provide a valid agency";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for agencies update method
     * @param $agency
     * @param $userId
     * @return array
     */
    protected function validateUpdate($agency, $userId){
        $errors        =  array();
        if(!$agency){
            $errors    =  "please provide a valid agency";
        }
        if($userId) {
            $user         =   User::find($userId);
            if(!$user){
                $errors  =  "The value you entered not exists.please enter a valid user id";
                return $errors;
            }
            $agency = Agency::where('user_id','=',$user->id)->first();
            if($agency and $user->verified ==1){
                $errors = "There is already an active agency for the user";
            }
        }
        return $errors;
    }

    /**
     * This method performs business class validation for agencies delete method
     * @param $agency
     * @return array
     */
    protected function validateDelete($agency) {
        $errors        =   array();
        if(!$agency){
            $errors    = "Please provide valid agency id";
        }
        return $errors;
    }
}

?>