<?php
namespace App\Services;
use App\Models\Agency;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamsService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
         $valError          = $this->validateCreate($request,$request->json()->get('agencyId'));
         if($valError){
            return $valError;
         }
         $team              = Team::create(['name'=>$request->json()->get('name'),'category'=>$request->json()->get('category'),'agency_id'=>$request->json()->get('agencyId')]);
         $valError          = $this->validateCreate($team,$request->json()->get('agencyId'));
         if($valError){
             $valError      = $this->failureMessage($valError,'404');
             return $valError;
         }

         $team=$this->buildCreateSuccessMessage("success",$team);
         return $team;
    }

    /**
     * retrieves a team
     * @param $team_id
     * @return mixed
     */
    public function retrieveOne($team_id){
         $team            =  Team::find($team_id);
         $valError        =  $this->validateRetrieveOne($team);
         if($valError){
             $valError    =  $this->failureMessage($valError,parent::HTTP_404);
             return $valError;
         }

         $team            =  $this->buildRetrieveOneSuccessMessage("success",$team);
         return $team;
    }

    /**
     * @param $request
     * @param $team_id
     * @return mixed
     */
    public function update($request,$team_id){
        $team             =    Team::find($team_id);
        $valError         =    $this->validateUpdate($team,$request->json()->get('agencyId'));
        if($valError){
            $valError     =    $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $team->name       =   ($request->json()->get('name'))?($request->json()->get('name')):$team->name;
        $team->category   =   ($request->json()->get('category'))?($request->json()->get('category')):$team->category;
        $team->agency_id  =   ($request->json()->get('agencyId'))?($request->json()->get('agencyId')):$team->agency_id;
        $team->save();

        $team             =   $this->buildUpdateSuccessMessage("success",$team);
        return $team;
    }

    /**
     * deletes a team
     * @param $team_id
     * @return mixed
     */
    public function delete($team_id){
        $team            =       Team::find($team_id);
        $valError        =       $this->validateDelete($team);
        if($valError){
            $valError    =       $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $team->delete();
        $team            =       $this->buildDeleteSuccessMessage("success");
        return $team;
    }

    /**
     * This method performs business class validation for teams  create method
     * @param $team
     * @param $agencyId
     * @return array
     */
    protected function validateCreate($team,$agencyId){
       $errors = array();
       if(!$team){
           $errors[]         =  array("message" => "Please provide a valid team");
       }
       if($agencyId){
           $agency           =  Agency::find($agencyId);
           if(!$agency){
               $message      =  array("message" => "The value you entered not exists.please enter a valid agency id");
               return $message;
           }
       }
       return $errors;
    }

    /**
     * This method performs business class validation for teams retrieveOne method
     * @param $team
     * @return array
     */
    protected function validateRetrieveOne($team){
        $errors        =    array();
        if(!$team){
            $errors[]  =    array("message" => "Please provide a valid team");
        }
        return $errors;
    }

    /**
     * This method performs business class validation for teams update method
     * @param $team
     * @param $agencyId
     * @return array
     */
    protected function validateUpdate($team,$agencyId){
        $errors            =   array();
        if(!$team){
            $errors[]      =   array("message" => "Please provide a valid team");
        }
        if($agencyId) {
            $agency        =   Agency::find($agencyId);
            if(!$agency){
                $message   =   array("message" => "The value you entered not exists.please enter a valid agency id");
                return $message;
            }
        }
        return $errors;
    }

    /**
     * This method performs business class validation for teams delete method
     * @param $team
     * @return array
     */
    protected function validateDelete($team){
        $errors       =  array();
        if(!$team){
            $errors[] =  array("message" => "Please provide a valid team");
        }
        return $errors;
    }
}
?>