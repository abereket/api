<?php
/**
 * Created by PhpStorm.
 * User: kibretbereket
 * Date: 10/12/15
 * Time: 00:00
 */

namespace App\Services;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination;

class UsersService extends Base
{
    /**
     * takes request as a parameter and  creates a user
     * @param $request
     * @return mixed
     */
    public function create($request,$email='',$type='')
    {
        $user = new User();
        $user->uuid         =  Uuid::uuid();
        $user->first_name   =  $request->json()->get('firstName');
        $user->last_name    =  $request->json()->get('lastName');
        $user->email        =  ($request->json()->get('email'))?$request->json()->get('email'):$email;
        $user->password     =  hash('sha512',$request->json()->get('password'));
        $user->type         =  ($request->json()->get('type'))?($request->json()->get('type')):$type;
        $user->save();

        $emailVerification = new EmailVerificationsService();
        $code = $emailVerification->create($user->type,$user->id);

        //Send user activation email
        $emailService = new EmailsService();
        $from         = "info@zemployee.com";
        $subject      = "Agency, please active your email";
        $body         = "Please click the link below to active your account " . $code;

        $emailService->send($user->email, $from, $subject, $body);

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
        $limit      =   ($request->input('per_page'))?($request->input('per_page')):15;
        $firstName  =   $request->input('firstName');
        $lastName   =   $request->input('lastName');
        $email      =   $request->input('email');
        $type       =   $request->input('type');
        $verified   =   $request->input('verified');
        $order_by   =  ($request->input('order_by'))? ($request->input('order_by')):'updated_at';

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
           $user = $user->where('type','like','%'.$type.'%');
        }
        if($verified){
           $user = $user->where('verified','=',$verified);
        }
        $user = $user->orderby($order_by)->Paginate($limit);

        $user = $this->buildRetrieveResponse($user->toArray());
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

        $user->first_name  =   ($request->json()->get('firstName'))?($request->json()->get('firstName')):$user->first_name;
        $user->last_name   =   ($request->json()->get('lastName'))?($request->json()->get('lastName')):$user->last_name;
        $user->password    =   ($request->json()->get('password'))?hash('sha512',($request->json()->get('password'))):$user->password;
        $user->type        =   ($request->json()->get('type'))?($request->json()->get('type')):$user->type;
        $user->verified    =   ($request->json()->get('verified'))?($request->json()->get('verified')):$user->verified;
        $user->save();
        unset($user['password']);

        
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
            ->get()
            ->first();
        return $user;
        //if (!$user) {
            //echo 'not allowed';
            //throw new Exception("Please provide valid username and password");
        //}

        //return $user;
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
     * This method performs business class validation for users retrieveOne method
     * @param $user
     * @return array
     */
    protected function validateRetrieveOne($user){
        $errors       = array();
        if(!$user){
            $errors   = "please provide a valid user";
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
            $errors  = "please provide a valid user";
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
            $errors  =  "please provide a valid user";
        }
       return $errors;
    }
}
