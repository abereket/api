<?php
namespace App\Services;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Pagination;
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
     * @param $request
     * @return array|string
     */
    public function retrieve($request){
        $teamId      =   $request->input('teamId');
        $limit       =   ($request->input('per_page'))?$request->input('per_page'):15;
        $order_by    =   ($request->input('order_by'))?$request->input('order_by'):'updated_at';
        if($teamId){
            $teamMember = TeamMember::where('team_id',$teamId)->get();
            if($teamMember->count()) {
                foreach ($teamMember as $teamMember) {
                    $userId[] = $teamMember->user_id;
                }
                $user = User::whereIn('id',$userId)->orderby($order_by)->Paginate($limit);

                $user = $this->buildRetrieveResponse($user->toArray());
                $user = $this->buildRetrieveSuccessMessage("success",$user);
                return $user;
            }else{
                return "There is no corresponding data related to your  teamId ";
            }
        }
        return 'You are advised to enter teamId of the values you want to be searched';
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
            $errors  =   "The value you entered not exists.please enter a valid user id";
        }
        $team        =   Team::find($teamId);
        if(!$team){
            $errors  = "The value you entered not exists.please enter a valid team id";
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
          $errors    =  "The value you entered not exists.please enter a valid user id";
        }
        }
        if($teamId){
        $team         =   Team::find($teamId);
        if(!$team ){
          $errors    =   "The value you entered not exists.please enter a valid team id";
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