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
        $len = count($request->json()->get('emails'));
        $i = 0;
           do{
                $rules = ['emails'=>'required|array',"emails.$i.email"=>'required|email','teamId'=>'required|max:11|exists:teams,id,deleted_at,NULL'];
                $this->validate($request,$rules);
                $i++;
            } while($i < $len);
            $teamMemberService = new TeamMembersService();
            $teamMember = $teamMemberService->create($request);
            return response()->json($teamMember);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(){

        //You should always get team_id
        //Use the team_id to get all users in the team sorted by udpated_at.
        //Return array of users;

        $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                           array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count=count($team_member);

        return response()->json(["status"=>"success","code"=>parent::HTTP_200,"count"=>$count,"results"=>$team_member]);
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

        $rules=['userId'=>'max:11|exists:users,id,deleted_at,NULL','teamId'=>'max:11|exists:teams,id,deleted_at,NULL'];
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