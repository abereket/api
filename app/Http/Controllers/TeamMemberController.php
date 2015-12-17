<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\TeamMembers;
use Illuminate\Http\Request;


class TeamMemberController extends Controller
{
    /**
     * calls the create method in the Services.TeamMembers
     * @param Request $request
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

    public function retrieve(){

        $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                           array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count=count($team_member);

        return response()->json(["status"=>"success","code"=>"200","count"=>$count,"results"=>$team_member]);
    }

    /**
     * calls the retrieveOne method in Services.TeamMembers
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
     * calls the update method in the Services.TeamMembers
     * @param Request $request
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_member_id){

        $rules=['id' =>'required|max:11','userId'=>'required|max:11','teamId'=>'required|max:11'];
        $this->validate($request,$rules);

        $teamMemberService = new TeamMembers();
        $teamMember = $teamMemberService->update($request->id,$request->userId,$request->teamId,$team_member_id);
        if($teamMember!==null){

            return response()->json(["status" => "success", "code" => "200", "results" => $teamMember]);
        }
        return response()->json(["message"=>"The entry you want to be updated not found"]);
    }

    /**
     * calls the delete method in Services.TeamMembers
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