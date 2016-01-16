<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\TeamMembersService;
use Illuminate\Http\Request;


class TeamMemberController extends Controller
{
    /**
     * calls the create method in the Services.TeamMembersService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['userId'=>'required|max:11','teamId'=>'required|max:11|exists:teams,id,deleted_at,Null'];
        $this->validate($request,$rules);

        $teamMemberService = new TeamMembersService();
        $teamMember = $teamMemberService->create($request);
        return response()->json($teamMember);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request){

        //You should always get team_id
        //Use the team_id to get all users in the team sorted by udpated_at.
        //Return array of users;
        $teamMemberService = new TeamMembersService();
        $teamMember=$teamMemberService->retrieve($request);
        return response()->json($teamMember);
    }

    /**
     * calls the retrieveOne method in Services.TeamMembersService
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($team_member_id){

        $teamMemberService = new TeamMembersService();
        $teamMember=$teamMemberService->retrieveOne($team_member_id);
        return response()->json($teamMember);
    }

    /**
     * calls the update method in the Services.TeamMembersService
     * @param Request $request
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_member_id){

        $rules=['userId'=>'max:11','teamId'=>'max:11|exists:teams,id,deleted_at,Null'];
        $this->validate($request,$rules);

        $teamMemberService = new TeamMembersService();
        $teamMember = $teamMemberService->update($request,$team_member_id);

        return response()->json($teamMember);
    }

    /**
     * calls the delete method in Services.TeamMembersService
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_member_id){

        $teamMemberService = new TeamMembersService();
        $teamMember = $teamMemberService->delete($team_member_id);
        return response()->json($teamMember);
    }

}