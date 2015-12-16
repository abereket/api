<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;

class Agencies
{

    public function create($request)
    {
        $user=User::create(['id'=>$request->userId,'first_name'=>$request->firstName,'last_name'=>$request->lastName,'email'=>$request->email]);
        $agency=Agency::create(['name'=>$request->name,'user_id'=>$request->userId,'description'=>$request->description]);
        //send invitation email to the user
        return $agency;
    }

    public function retrieveOne($agency_id)
    {
         $agency=Agency::find($agency_id);
            if($agency){
                $user=User::find($agency->user_id);
                if($user) {
                    unset($agency['deleted_at']);

                    $obj=array('agency'=>$agency,
                               'user'=>array('id'=>$agency->user_id,'first_name'=>$user->first_name,'last_name'=>$user->last_name, 'email'=>$user->email));
                    return $obj;
                }
            }
    }


    public function update($request,$agency_id)
    {
        $agency=Agency::find($agency_id);
            if($agency){
                $user=User::find($agency->user_id);
                if($user) {
                    $agency->id           =   $request->id;
                    $agency->name         =   $request->name;
                    $agency->description  =   $request->description;
                    $agency->user_id      =   $request->userId;
                    $agency->save();
                    $user->id = $request->userId;
                    $user->save();

                }
                return $agency;
            }
        return $agency;
    }

    public function delete($agency_id){
        $agency=Agency::find($agency_id);
        if($agency){
            $agency->delete();
            return $agency;
        }
        return $agency;
    }
}

?>