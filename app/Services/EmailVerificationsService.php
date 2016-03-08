<?php
namespace App\Services;

use App\Models\EmailVerification;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailVerificationsService extends Base{
    /**
     * @param $verificationType
     * @param $userId
     * @return string
     */
    public function create($verificationType,$userId){

        $emailVerification = EmailVerification::create(['verification_type'=>$verificationType,'token'      =>bin2hex(openssl_random_pseudo_bytes(16)),
                                                        'user_id'           =>$userId,          'expired_at'=>date("Y-m-d H:i:s",(time()+(15*60)))]);

        $code = base64_encode($emailVerification->token.':'.strtotime($emailVerification->expired_at).':'.$emailVerification->verification_type);
        return $code;
    }

    /**
     * @param $code
     * @return array|string
     */
    public function update($code)
    {
        list($token,$expired_at,$verification_type) = $this->decomposeCode($code);
        if (time() > $expired_at) {
            $valError['invalid_token'] = "Your token has no longer valid";
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }

        $expiredAt = date("Y-m-d H:i:s",(int)$expired_at);
        $emailVerification = EmailVerification::where('token','=',$token)
                                               ->where('expired_at','=',$expiredAt)
                                               ->where('verification_type','=',$verification_type)
                                               ->get()
                                               ->first();

        $valError = $this->validateUpdate($emailVerification);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $emailVerification->is_verified = 1;
        $emailVerification->save();
        $user = User::find($emailVerification->user_id);
        if(!$user){
            $valError['deleted_user'] = "The user you want to activate is already deleted";
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user->verified = 1;
        $user->save();
        $entity["updated"] ="your account is successfully updated";
        $user = $this->buildEmailVerificationSuccessMessage("success",$entity);
        return $user;
    }

    /**
     * @param $code
     * @return array
     */
    public function retrieveOne($code){
        list($token,$expired_at,$verification_type) = $this->decomposeCode($code);
        if(time() > $expired_at){
            $valError['invalid_token'] = "Your token has no longer valid";
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $expiredAt = date("Y-m-d H:i:s",(int)$expired_at);
        $emailVerification = EmailVerification::where('token','=',$token)
                                                ->where('expired_at','=',$expiredAt)
                                                ->where('verification_type','=',$verification_type)
                                                ->get()
                                                ->first();
        $valError = $this->validateRetrieveOne($emailVerification);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $user = User::find($emailVerification->user_id);
        unset($user->uuid,$user->password,$user->type,$user->invited_by,$user->verified,$user->created_at,$user->updated_at,$user->deleted_at);
        unset($emailVerification->token,$emailVerification->user_id,$emailVerification->is_verified,
              $emailVerification->created_at,$emailVerification->updated_at,$emailVerification->deleted_at);
        $emailVerification->user = $user;
        $emailVerification = $this->buildRetrieveOneSuccessMessage("success",$emailVerification);
        return $emailVerification;
    }

    /**
     * This method performs business class validation for Email verifications update method
     * @param $emailVerification
     * @return array|string
     */
    protected function validateUpdate($emailVerification){
        $errors = array();
        if(!$emailVerification){
            $errors['not_activate'] = 'You can not activate your account';
        }
        return $errors;
    }

    /**
     * This method performs business class validation for Email verifications retrieveOne method
     * @param $emailVerification
     * @return array|string
     */
    protected function validateRetrieveOne($emailVerification){
        $errors = array();
        if(!$emailVerification){
            $errors['token'] = "The email verification and user information you are looking is not found.Please enter a valid token";
            return $errors;
        }
        if($emailVerification->is_verified == 1){
            $errors['verified']= "the email is already verified";
        }
        return $errors;
    }

    /**
     * @param $code
     * @return array
     */
    protected function decomposeCode($code) {
        $code = base64_decode($code);
        $code = explode(':', $code);
        $token = isset($code[0])?$code[0]:'';
        $expired_at = isset($code[1])?$code[1]:'';
        $verification_type = isset($code[2])?$code[2]:'';

        return array($token, $expired_at, $verification_type);
    }

}