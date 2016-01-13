<?php
namespace App\Services;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;

class TeamMembersService extends Base{
    /**
     * @param $request
     * @return static
     */
    public function create($request){
        $valError     =  $this->validateCreate($request->json()->get('userId'),$request->json()->get('teamId'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $teamMember   =  TeamMember::create(['user_id'=>$request->json()->get('userId'),'team_id'=>$request->json()->get('teamId')]);

        $teamMember  =  $this->buildCreateSuccessMessage("success",$teamMember);
        return $teamMember;
    }

    /**
     * retrieves a team member
     * @param $team_member_id
     * @return mixed
     */
    public function retrieveOne($team_member_id){

       $teamMember   =  TeamMember::find($team_member_id);
       $valError     =  $this->validateRetrieveOne($teamMember);
       if($valError){
           $valError = $this->failureMessage($valError,parent::HTTP_404);
           return $valError;
       }

       $teamMember   =  $this->buildRetrieveOneSuccessMessage("success",$teamMember);
       return $teamMember;
    }

    /**
     * @param $request
     * @param $team_member_id
     * @return mixed
     */
    public function update($request,$team_member_id){

      $teamMember    =  TeamMember::find($team_member_id);
      $valError      =  $this->validateUpdate($teamMember,$request->json()->get('userId'),$request->json()->get('teamId'));
      if($valError) {
          $valError = $this->failureMessage($valError,parent::HTTP_404);
          return $valError;
      }
      $teamMember->user_id  =  ($request->json()->get('userId'))?($request->json()->get('userId')):$teamMember->user_id;
      $teamMember->team_id  =  ($request->json()->get('teamId'))?($request->json()->get('teamId')):$teamMember->team_id;
      $teamMember->save();

      $teamMember           =   $this->buildUpdateSuccessMessage("success",$teamMember);
      return $teamMember;
    }

    /**
     * deletes the team member
     * @param $team_member_id
     * @return mixed
     */
    public function delete($team_member_id){
        $teamMember    =  TeamMember::find($team_member_id);
        $valError      =  $this->validateDelete($teamMember);
        if($valError){
            $valError  = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $teamMember->delete();
        $teamMember    =    $this->buildDeleteSuccessMessage("success");
        return $teamMember;
    }

    /**
     * This method performs business class validation for Team members create method
     * @param $userId
     * @param $teamId
     * @return array|string
     */
    protected function validateCreate($userId,$teamId){
        $errors = array();
        $user        =   User::find($userId);
        if(!$user){
            $message  =   "The value you entered not exists.please enter a valid user id";
            return $message;
        }
        $team        =   Team::find($teamId);
        if(!$team){
            $message  = "The value you entered not exists.please enter a valid team id";
            return $message;
        }
        return $errors;
    }

    /**
     * This method performs business class validation for Team members retrieveOne method
     * @param $teamMember
     * @return array
     */
    protected function validateRetrieveOne($teamMember){
        $errors         =    array();
        if(!$teamMember){
          $errors     =     "Please provide a valid teamMember";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for Team members Update method
     * @param $teamMember
     * @param $userId
     * @param $teamId
     * @return array
     */
    protected function validateUpdate($teamMember,$userId,$teamId){
        $errors = array();
        if(!$teamMember){
           $errors  =   "Please provide a valid teamMember";
        }
        if($userId){
        $user         =   User::find($userId);
        if(!$user){
          $message    =  "The value you entered not exists.please enter a valid user id";
          return $message;
        }
        }
        if($teamId){
        $team         =   Team::find($teamId);
        if(!$team ){
          $message    =   "The value you entered not exists.please enter a valid team id";
          return $message;
        }
        }
        return $errors;
    }

    /**
     * This method performs business class validation for Team members delete method
     * @param $teamMember
     * @return array
     */

    protected function validateDelete($teamMember){
        $errors = array();
        if(!$teamMember){
           $errors =  "Please provide a valid teamMember";
        }
        return $errors;
    }
}