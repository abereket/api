<?php
namespace App\Services;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;

class TeamMembersService {
    /**
     * @param $request
     * @return static
     */
    public function create($request){
        $valError    =  $this->validateCreate($request,$request->json()->get('userId'),$request->json()->get('teamId'));
        if($valError){
            return $valError;
        }
        $teamMember  =  TeamMember::create(['user_id'=>$request->json()->get('userId'),'team_id'=>$request->json()->get('teamId')]);
        $valError    =  $this->validateCreate($teamMember,$request->json()->get('userId'),$request->json()->get('teamId'));
        if($valError){
            return $valError;
        }

        return $teamMember;
    }

    /**
     * retrieves a team member
     * @param $team_member_id
     * @return mixed
     */
    public function retrieveOne($team_member_id){

       $teamMember  =  TeamMember::find($team_member_id);
       $valError    =  $this->validateRetrieveOne($teamMember);
       if($valError){
           return $valError;
       }

       return $teamMember;
    }

    /**
     * @param $request
     * @param $team_member_id
     * @return mixed
     */
    public function update($request,$team_member_id){

      $teamMember =  TeamMember::find($team_member_id);
      $valError   =  $this->validateUpdate($teamMember,$request->json()->get('userId'),$request->json()->get('teamId'));
      if($valError) {
           return $valError;
      }
      $teamMember->user_id =  ($request->json()->get('userId'))?($request->json()->get('userId')):$teamMember->user_id;
      $teamMember->team_id =  ($request->json()->get('teamId'))?($request->json()->get('teamId')):$teamMember->team_id;
      $teamMember->save();

      return $teamMember;
    }

    /**
     * deletes the team member
     * @param $team_member_id
     * @return mixed
     */
    public function delete($team_member_id){
        $teamMember  =  TeamMember::find($team_member_id);
        $valError    =  $this->validateDelete($teamMember);
        if($valError){
            return $valError;
        }
        $teamMember->delete();

        return $teamMember;
    }

    protected function validateCreate($teamMember,$userId,$teamId){
        $errors = array();
        if(!$teamMember){
          $errors[] = array("message" => "Please provide a valid teamMember");
        }
        if($userId){
           $userService   =   new UsersService();
           $user          =   $userService->retrieveOne($userId);
           if(!$user instanceof User){
              $message = array("message" => "The value you entered not exists.please enter a valid user id");
              return $message;
           }
        }
        if($teamId){
           $teamService =  new TeamsService();
           $team        =  $teamService->retrieveOne($teamId);
           if(!$team instanceof Team){
              $message = array("message" =>  "The value you entered not exists.please enter a valid team id");
              return $message;
           }
        }
        return $errors;
    }
    protected function validateRetrieveOne($teamMember){
        $errors = array();
        if(!$teamMember){
          $errors[] = array("message" => "Please provide a valid teamMember");
        }
        return $errors;
    }
    protected function validateUpdate($teamMember,$userId,$teamId){
        $errors = array();
        if(!$teamMember){
           $errors[] = array("message" => "Please provide a valid teamMember");
        }
        if($userId){
        $userService  =  new UsersService();
        $user         =  $userService->retrieveOne($userId);
        if(!$user instanceof User){
          $message=array("message"=>"The value you entered not exists.please enter a valid user id");
          return $message;
        }
        }
        if($teamId){
        $teamService  =   new TeamsService();
        $team         =   $teamService->retrieveOne($teamId);
        if(!$team instanceof Team){
          $message=array("message"=>"The value you entered not exists.please enter a valid team id");
          return $message;
        }
        }
        return $errors;
    }
    protected function validateDelete($teamMember){
        $errors = array();
        if(!$teamMember){
           $errors[] = array("message" => "Please provide a valid teamMember");
        }
        return $errors;
    }
}