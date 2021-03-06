<?php
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 10/12/15
 * Time: 00:00
 */

namespace App\Services;
use App\Models\Agency;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination;

class UsersService extends Base
{
    /**
     * @param $request
     * @param string $email
     * @param string $type
     * @return array|static
     */
    public function create($request,$email='',$type='')
    {
        $user = User::create(['uuid'=>Uuid::uuid(),'first_name'=>$request->json()->get('first_name'),
                              'last_name'=>$request->json()->get('last_name'),'email'=>$request->json()->get('email',$email),
                              'password'=>hash('sha512',$request->json()->get('password')),
                              'type'=>$request->json()->get('type',$type),'invited_by'=>$request->json()->get('invited_by')]);

        if($user->type != 'agency' and $type!='recruiter'){
            $invitedBy = $this->setInvitedBy($user);
            $emailVerification = new EmailVerificationsService();
            $code = $emailVerification->create($user->type,$user->id);

            //Send user activation email
            $emailService = new EmailsService();
            $from         = "info@zemployee.com";
            $subject      = " ";
            $body         = "Please click the link below to active your account " . $code;
            //can be a function for setting templeteId
            $templateId   = "45bd4441-12f8-4b18-82dd-03256f261876";
            $emailService->send($user->email, $from, $subject, $body,$invitedBy,$code,$templateId);
        }
        if($type and $email){
            return $user;
        }
        $user=$this->buildCreateSuccessMessage('success',$user);
        return $user;
    }

    /**
     * @param $request
     * @return mixed
     */
    public function retrieve($request){
        $limit      =   ($request->input('per_page'))?$request->input('per_page'):15;
        $firstName  =   $request->input('first_name');
        $lastName   =   $request->input('last_name');
        $email      =   $request->input('email');
        $type       =   $request->input('type');
        $invitedBy  =   $request->input('invited_by');
        $verified   =   $request->input('verified');
        $orderBy    =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy     =   ($request->input('sort_by'))?$request->input('sort_by') :'DESC';
        $search     =   $this->searchValueExists($firstName,$lastName,$email,$type,$invitedBy,$verified);
        if($search){
            $valError = $this->buildEmptyErrorResponse(parent::HTTP_404);
            return $valError;
        }
        $user  = new User();

        if($firstName){
           $user = $user->where('first_name', 'like','%'.$firstName.'%');
        }
        if($lastName){
            $user=$user->where('last_name', 'like','%'.$lastName.'%');
        }
        if($email){
           $user = $user->where('email','like','%'.$email.'%');
        }
        if($type){
           $user = $user->where('type','=', $type);
        }
        if($invitedBy){
            $user = $user->where('invited_by','=', $invitedBy);
        }
        if($verified){
           $user = $user->where('verified','=',$verified);
        }
        $user = $user->orderby($orderBy,$sortBy)->Paginate($limit);

        $user = $this->buildRetrieveResponse($user->toArray());

        if(!empty($user['results'])){
            $user = $this->hidePassword($user);
        }
        $user = $this->buildRetrieveSuccessMessage("success",$user);
        return $user;
    }

    /** takes user id as a parameter and returns the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function retrieveOne($user_id)
    {
        $user          =     User::find($user_id);
        $valError      =     $this->validateRetrieveOne($user);
        if($valError){
            $valError  =     $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user          = $this->buildRetrieveOneSuccessMessage('success',$user);
        return $user;
    }

    /**
     * takes user id as a parameter and updates the corresponding user
     * @param $request
     * @param $user_id
     * @return mixed
     */
    public function update($request, $user_id)
    {
        $user           = User::find($user_id);
        $valError       = $this->validateUpdate($user);
        if($valError){
            $valError   = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }

        $user->first_name  =   ($request->json()->get('first_name'))?($request->json()->get('first_name')):$user->first_name;
        $user->last_name   =   ($request->json()->get('last_name'))?($request->json()->get('last_name')):$user->last_name;
        $user->password    =   ($request->json()->get('password'))?hash('sha512',($request->json()->get('password'))):$user->password;
        $user->type        =   ($request->json()->get('type'))?($request->json()->get('type')):$user->type;
        $user->verified    =   ($request->json()->get('verified'))?($request->json()->get('verified')):$user->verified;
        $user->save();
        $user=$this->buildUpdateSuccessMessage('success',$user);
        return $user;

    }

    /**
     * @param $userName
     * @param $password
     * @return mixed
     * @throws Exception
     */
    public function authenticate($userName, $password)
    {
        $password = hash('sha512', $password);
        $user = User::where('email', '=', $userName)
                     ->where('password', '=', $password)
                     ->where('verified', '=', 1)
                     ->first();
        if(!$user) {
            $valError['incorrect_pattern'] = "Your user name or password may be incorrect";
            $user = $this->failureMessage($valError,parent::HTTP_401);
            return $user;
        }
        $user= $this->buildAuthenticateSuccessMessage('success',$user);
        return $user;
    }

    /**
     * takes user id as a parameter and deletes the corresponding user
     * @param $user_id
     * @return mixed
     */
    public function delete($user_id){
        $user          =       User::find($user_id);
        $valError      =       $this->validateDelete($user);
        if($valError){
            $valError  =       $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user->delete();
        $user = $this->buildDeleteSuccessMessage('success');
        return $user;
    }

    /**
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function deleteUsers()
    {
        $user = User::withTrashed()
                    ->get();

        if ($user->count()) {
            foreach ($user as $users) {
                $users->forceDelete();
            }
        }


        $users = [
            '{"first_name":"Test", "last_name":"Admin","email":"admin@zemployee.com","password": "admin", "type": "zemployee", "invited_by": "0", "verified": "1"}',
            '{"first_name": "Test", "last_name": "Agency","email": "agency@zemployee.com", "password": "agency", "type": "agency", "invited_by": "0", "verified": "1"}',
            '{"first_name": "Test", "last_name": "Recruiter","email": "rec@zemployee.com", "password": "rec", "type": "recruiter", "invited_by": "0", "verified": "1"}',
            '{"first_name": "Test", "last_name": "Candidate","email": "can@zemployee.com", "password": "can", "type": "candidate", "invited_by": "0", "verified": "1"}',
            '{"first_name": "Test", "last_name": "Reference","email": "ref@zemployee.com", "password": "ref", "type": "candidate", "invited_by": "0", "verified": "1"}'
        ];
        foreach ($users as $user) {
            $user = json_decode($user, true);
            $user['password'] = hash('sha512', $user['password']);
            User::create($user);
        }
        $agencyUser = User::where("type","agency")->first();
        $agency = '{"name" : "TestAgency1","user_id" :"'.$agencyUser->id.'", "description" : "Test Agency"}';
        $agency = json_decode($agency, true);
        $agency = Agency::create($agency);
        $user = User::all();
        $user[] = $agency;
        $user = $this->buildRetrieveOneSuccessMessage("success",$user);
        return $user;
    }



    /**
     * This method performs business class validation for users retrieveOne method
     * @param $user
     * @return array
     */
    protected function validateRetrieveOne($user){
        $errors       = array();
        if(!$user){
            $errors['user_id']   = "please provide a valid user id";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for users update method
     * @param $user
     * @return array
     */
    protected function validateUpdate($user){
        $errors        = array();
        if(!$user){
            $errors['user_id']  = "please provide a valid user id";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for users delete method
     * @param $user
     * @return array
     */
    protected function validateDelete($user){
        $errors        =  array();
        if(!$user){
            $errors['user_id']  =  "please provide a valid user id";
        }
       return $errors;
    }

    /**
     * @param $user
     * @return string
     */
    protected function setInvitedBy($user){
        if($user->invited_by!=null){
            $userInv= User::find($user->invited_by);
            $invitedBy = $userInv->first_name." ".$userInv->last_name;
        }
        $invitedBy=(isset($invitedBy))?$invitedBy:'Zemployee Admin';
        return $invitedBy;
    }
}
