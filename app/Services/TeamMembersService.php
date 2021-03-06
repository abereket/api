<?php
namespace App\Services;
use App\Models\Agency;
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
                list($admin,$orgName) = $this->adminName($request->json()->get('team_id'));
                $options=array('orgName'=>$orgName,'userName'=>$emails[$i]['email']);
                $emailVerification = new EmailVerificationsService();
                $code = $emailVerification->create($user['type'],$user['id']);
                $emailService = new EmailsService();
                $from         = "info@zemployee.com";
                $subject      = " ";
                $body         = "Please click the link below to active your account " . $code;
                $templateId   = "74d011a3-034b-47d4-b6f7-cc14528f94b4";
                $emailService->send($emails[$i], $from, $subject, $body,$admin,$code,$templateId,$options);
            }
            $teamMember = TeamMember::create(['user_id' => $user->id, 'team_id' => $request->json()->get('team_id')]);
            $id[]=$teamMember->id;
        }
        $teamMember = TeamMember::whereIn('id',$id)->get();
        foreach($teamMember as $teamMemberEntity){
            $teamMembers[] = $teamMemberEntity;
        }
        $teamMember = $this->buildCreateSuccessMessage("success", $teamMembers);
        return $teamMember;
    }

    /**
     * @param $request
     * @return array|string
     */
    public function retrieve($request){
        $teamId      =    $request->input('team_id');
        $limit       =    ($request->input('per_page'))?$request->input('per_page'):15;
        $orderBy     =    ($request->input('order_by'))?$request->input('order_by'):'created_at';
        $sortBy      =    ($request->input('sort_by'))?$request->input('sort_by'):'DESC';

        $valError = $this->validateRetrieve($teamId);
        if($valError){
            $valError =$this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $teamMember = TeamMember::where('team_id',$teamId)->get();
        if(!$teamMember->count()) {
            $teamMember = $this->buildRetrieveResponse((array)$teamMember);
            $teamMember = $this->buildRetrieveSuccessMessage('success',$teamMember);
            return $teamMember;
        }
        foreach ($teamMember as $teamMember) {
            $userId[] = $teamMember->user_id;
        }
        $user = User::whereIn('id',$userId)->orderby($orderBy,$sortBy)->Paginate($limit);
        $user = $this->buildRetrieveResponse($user->toArray());
        if(!empty($user['results'])){
            $user = $this->hidePassword($user);
        }
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
     *  This method performs business class validation for Team members create method
     * @param $teamId
     * @return array
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

    /**
     * @param $teamId
     * @return array
     */
    protected function adminName($teamId){
        $team   =  Team::find($teamId);
        $agency =  Agency::find($team->agency_id);
        $user   =  User::find($agency->user_id);
        $admin  = $user->first_name." ".$user->last_name;
        $orgName = $agency->name;
        return array($admin,$orgName);
    }

}