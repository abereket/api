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
        $valError     =  $this->validateCreate($request->json()->get('team_id'));
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $emails=$request->json()->get('emails');
        for($i=0;$i<count($emails);$i++){
            $user = User::where('email', $emails[$i]['email'])->first();
            if (!$user) {
                $userService = new UsersService();
                $user = $userService->create($request,$emails[$i]['email'],'recruiter');
            }
            $teamMember = TeamMember::create(['user_id' => $user->id, 'team_id' => $request->json()->get('team_id')]);
            $id[]=$teamMember->id;
        }
        $teamMember = TeamMember::whereIn('id',$id)->get();
        $teamMember = $this->buildCreateSuccessMessage("success", $teamMember);
        return $teamMember;
    }

    /**
     * @param $request
     * @return array|string
     */
    public function retrieve($request){
        $teamId      =   $request->input('team_id');
        $limit       =   ($request->input('per_page'))?$request->input('per_page'):15;
        $orderBy    =   ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy     =  ($request->input('sort_by'))?$request->input('sort_by'):'DESC';

        $valError = $this->validateRetrieve($teamId);
        if($valError){
            $valError =$this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $teamMember = TeamMember::where('team_id',$teamId)->get();
        if(!$teamMember->count()) {
            $valError['team_id'] = "There is no corresponding data related to your teamId ";
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        foreach ($teamMember as $teamMember) {
            $userId[] = $teamMember->user_id;
        }
        $user = User::whereIn('id',$userId)->orderby($orderBy,$sortBy)->Paginate($limit);

        $user = $this->buildRetrieveResponse($user->toArray());
        $user = $this->buildRetrieveSuccessMessage("success",$user);
        return $user;


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
      $valError      =  $this->validateUpdate($teamMember,$request->json()->get('user_id'),$request->json()->get('team_id'));
      if($valError) {
          $valError = $this->failureMessage($valError,parent::HTTP_404);
          return $valError;
      }
      $teamMember->user_id  =  ($request->json()->get('user_id'))?($request->json()->get('user_id')):$teamMember->user_id;
      $teamMember->team_id  =  ($request->json()->get('team_id'))?($request->json()->get('team_id')):$teamMember->team_id;
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
    protected function validateCreate($teamId){
        $errors = array();
        $team        =   Team::find($teamId);
        if(!$team){
            $errors['team_id']  = "The value you entered not exists.please enter a valid team id";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for Team members retrieve method
     * @param $teamId
     * @return array|string
     */
    protected function validateRetrieve($teamId){
        $errors = array();
        if(!$teamId){
            $errors['team_id'] = 'You are advised to enter teamId of the values you want to be searched';
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
          $errors['team_member_id']     =     "Please provide a valid teamMember id";
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
           $errors['team_member_id']  =   "Please provide a valid teamMember";
        }
        if($userId){
        $user         =   User::find($userId);
        if(!$user){
          $errors['user_id']    =  "The value you entered not exists.please enter a valid user id";
        }
        }
        if($teamId){
        $team         =   Team::find($teamId);
        if(!$team ){
          $errors['team_id']    =   "The value you entered not exists.please enter a valid team id";
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
           $errors['team_member_id'] =  "Please provide a valid teamMember id";
        }
        return $errors;
    }
}