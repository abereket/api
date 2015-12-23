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
        //@TODO - fix: always create an entity using a service. Call user service from here
        //@TODO - fix: add validation here, if user is not create, do not create a user.
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

        //@TODO - kibret: find should only return agency with null deleted_at. If agency is deleted, you should not get it.
         $agency=Agency::find($agency_id);
        //@TODO - check negative case first
        if($agency){
                unset($agency['deleted_at']);  //@TODO - kibret: unset not needed, it is always null
                $user=User::find($agency->user_id);
                if($user) { //@TODO - kibret: negative check first and positive at last

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

        //@TODO - kibret: follow the same stragey. Avoid nested if/else. First perform all negative cases and finally do postive cases

        if($agency){
            $user=User::find($agency->user_id);
            if($user) {
                //@TODO - kibret: this is update agency API, you are not updating user.
                $user->id = $request->userId; //@TODO - kibret: wrong
                $user->save();              // @TODO - //@TODO - kibret:
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
        //@TODO - kibret: what do you do if you don't find agency. Negative test first.
        if($agency){
            $agency->delete();
        }
        return $agency;
    }
}

?>