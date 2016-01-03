<?php
namespace App\Services;
use App\Models\Agency;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamsService {
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
         $valError = $this->validateCreate($request,$request->json()->get('agencyId'));
         if($valError){
            return $valError;
         }
         $team = Team::create(['name'=>$request->json()->get('name'),'category'=>$request->json()->get('category'),'agency_id'=>$request->json()->get('agencyId')]);
         $valError = $this->validateCreate($team,$request->json()->get('agencyId'));
         if($valError){
             return $valError;
         }
         return $team;
    }

    /**
     * retrieves a team
     * @param $team_id
     * @return mixed
     */
    public function retrieveOne($team_id){
         $team = Team::find($team_id);
         $valError = $this->validateRetrieveOne($team);
         if($valError){
             return $valError;
         }
         return $team;
    }

    /**
     * @param $request
     * @param $team_id
     * @return mixed
     */
    public function update($request,$team_id){
        $team = Team::find($team_id);
        $valError = $this->validateUpdate($team,$request->json()->get('agencyId'));
        if($valError){
            return $valError;
        }
        $team->name       =  ($request->json()->get('name'))?($request->json()->get('name')):$team->name;
        $team->category   =  ($request->json()->get('category'))?($request->json()->get('category')):$team->category;
        $team->agency_id  =  ($request->json()->get('agencyId'))?($request->json()->get('agencyId')):$team->agency_id;
        $team->save();

        return $team;
    }

    /**
     * deletes a team
     * @param $team_id
     * @return mixed
     */
    public function delete($team_id){
        $team = Team::find($team_id);
        $valError = $this->validateDelete($team);
        if($valError){
            return $valError;
        }
        $team->delete();
        return $team;
    }

    protected function validateCreate($team,$agencyId){
       $errors = array();
       if(!$team){
           $errors[] = array("message" => "Please provide a valid team");
       }
       if($agencyId){
           $agencyService =   new AgenciesService();
           $agency        =   $agencyService->retrieveOne($agencyId);
           if(!$agency instanceof Agency){
               $message = array("message" => "The value you entered not exists.please enter a valid agency id");
               return $message;
           }
       }
       return $errors;
    }
    protected function validateRetrieveOne($team){
        $errors = array();
        if(!$team){
            $errors[] = array("message" => "Please provide a valid team");
        }
        return $errors;
    }
    protected function validateUpdate($team,$agencyId){
        $errors = array();
        if(!$team){
            $errors[] = array("message" => "Please provide a valid team");
        }
        if($agencyId) {
            $agencyService =  new AgenciesService();
            $agency        =  $agencyService->retrieveOne($agencyId);
            if (!$agency instanceof Agency) {
                $message = array("message" => "The value you entered not exists.please enter a valid agency id");
                return $message;
            }
        }
        return $errors;
    }
    protected function validateDelete($team){
        $errors = array();
        if(!$team){
            $errors[] = array("message" => "Please provide a valid team");
        }
        return $errors;
    }
}
?>