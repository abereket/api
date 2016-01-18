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
            $valError = "Your token has no longer valid";
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
        $user->verified = 1;
        $user->save();
        $user = $this->buildEmailVerificationSuccessMessage("success","your account is successfully updated");
        return $user;
    }

    /**
     * This method performs business class validation for Email verifications update method
     * @param $emailVerification
     * @return array|string
     */
    protected function validateUpdate($emailVerification){
        $errors = array();
        if(!$emailVerification){
            $errors = "You can not activate your account";
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