<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\TeamMembers;
use Illuminate\Http\Request;


class TeamMemberController extends Controller
{
    /**
     * creates the team member
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['userId'=>'required|max:11','teamId'=>'required|max:11'];
        $this->validate($request,$rules);

        $teamMemberService = new TeamMembers();
        $teamMember = $teamMemberService->create($request->userId,$request->teamId);
        return response()->json(["status"=>"success","code"=>200,"results"=>$teamMember]);
    }

    /**
     * retrieves the team member
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(){

        $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                           array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count=count($team_member);

        return response()->json(["status"=>"success","code"=>"200","count"=>$count,"results"=>$team_member]);
    }

    /**
     * accepts team member id as parameter and retrieves the corresponding team member
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($team_member_id){

        $teamMemberService = new TeamMembers();

        $teamMember=$teamMemberService->retrieveOne($team_member_id);

        if($teamMember!==null){
             return response()->json(["status" => "success", "code" => "200", "results" => $teamMember]);
        }

        return response()->json(["message"=>"The entry you want not found"]);
    }

    /**
     * accepts team member id as parameter and updates the corresponding team member
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_member_id){

        $rules=['userId'=>'required|max:11','teamId'=>'required|max:11'];
        $this->validate($request,$rules);

        $teamMemberService = new TeamMembers();
        $teamMember = $teamMemberService->update($request->userId,$request->teamId,$team_member_id);
        if($teamMember!==null){

            return response()->json(["status" => "success", "code" => "200", "results" => $teamMember]);
        }
        return response()->json(["message"=>"The entry you want to be updated not found"]);
    }

    /**
     * accepts team member id as parameter and soft deletes the corresponding team member
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_member_id){

        $teamMemberService = new TeamMembers();
        $teamMember = $teamMemberService->delete($team_member_id);
        if($teamMember) {
            return response()->json(["status" => "success", "code" => "204"]);
        }
        return response()->json(["message"=>"The entry you want to be deleted not found"]);
    }

}