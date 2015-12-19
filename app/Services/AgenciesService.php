<?php
namespace App\Services;

use App\Models\Agency;
use App\Models\User;

class AgenciesService
{
    /**
     * creates an agency
     * @param $request
     * @return static
     */
    public function create($request)
    {
        $user=User::create(['id'=>$request->userId,'first_name'=>$request->firstName,'last_name'=>$request->lastName,'email'=>$request->email]);
        $agency=Agency::create(['name'=>$request->name,'user_id'=>$request->userId,'description'=>$request->input('description')]);
        //send invitation email to the user
        return $agency;
    }

    /**
     * retrieves an(one) agency
     * @param $agency_id
     * @return array
     */
    public function retrieveOne($agency_id)
    {
         $agency=Agency::find($agency_id);
            if($agency){
                unset($agency['deleted_at']);
                $user=User::find($agency->user_id);
                if($user) {

                    $obj=array('agency'=>$agency,
                               'user'=>array('id'=>$agency->user_id,'first_name'=>$user->first_name,'last_name'=>$user->last_name, 'email'=>$user->email));
                    return $obj;
                }
            }
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
        $agency=Agency::find($agency_id);
            if($agency){
                $user=User::find($agency->user_id);
                if($user) {
                    $user->id = $request->userId;
                    $user->save();
                    $agency->id           =   $request->id;
                    $agency->name         =   $request->name;
                    $agency->description  =   $request->input('description');
                    $agency->user_id      =   $request->userId;
                    $agency->save();



                }
                return $agency;
            }
        return $agency;
    }

    /**
     * deletes an agency
     * @param $agency_id
     * @return mixed
     */
    public function delete($agency_id){
        $agency=Agency::find($agency_id);
        if($agency){
            $agency->delete();
        }
        return $agency;
    }
}

?>