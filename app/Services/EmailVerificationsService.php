<?php
namespace App\Services;

use App\Models\EmailVerification;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class EmailVerificationsService{

    public function create($verificationType,$userId){

        $emailVerification = EmailVerification::create(['verification_type'=>$verificationType,'token'      =>bin2hex(openssl_random_pseudo_bytes(16)),
                                                        'user_id'           =>$userId,          'expired_at'=>date("Y-m-d H:i:s",(time()+(15*60)))]);

        //$valError=$this->validateCreate($emailVerification);
        //if($valError){
          //return $valError;
        //}
        $userService = new UsersService();
        $user=$userService->retrieveOne($emailVerification->user_id);
        $code =base64_encode($emailVerification->token.':'.strtotime($emailVerification->expired_at).':'.$emailVerification->verificaton_type);
        //send the email
        //Mail::send($code,array('url'=>'www.zemployee.com/email-verification','code'=>$code),function($message) use ($user){
            //$message->to($user->email)->subject('activate your account');
        //});
    }

    public function update($request,$code)
    {
        $code = base64_decode($code);
        $code = explode(':', $code);
        $token = isset($code[0])?$code[0]:'';
        $expired_at = isset($code[1])?$code[1]:'';
        $verification_type = isset($code[2])?$code[2]:'';
        $expiredAt = date("Y-m-d H:i:s",$expired_at);
        if (time() > $expired_at) {
            $message = array("message" => "Your token has been expired");
            return $message;
        }

        $emailVerification = EmailVerification::where('token','=',$token)
                                               ->where('expired_at','=',$expiredAt)
                                               ->where('verification_type','=',$verification_type)
                                               ->get();

        //return $emailVerification;
        $valError = $this->validateUpdate($emailVerification);
        if ($valError) {
            return $valError;
        }
        $emailVerification->is_verified = 1;
        $emailVerification->save();
        $userService = new UsersService();
        $request->input('verified', 1);
        $user = $userService->update($request, $emailVerification->user_id);
        return ("success");
    }
        //protected function validateCreate($emailVerification){
        //$errors = array();
        //if(!$emailVerification){
            //$errors[]=array("message"=>"Failed to create email verification");
        //}
        //return $errors;
        //}
        protected function validateUpdate($emailVerification){
          $errors = array();
            if(!$emailVerification){
              $errors[] = array("message" => "you can not activate your account");
          }
            return $errors;
        }


}