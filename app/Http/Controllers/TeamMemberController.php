<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

class TeamMemberController extends Controller
{
    /**
     * creates the team member
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
      $team_member=array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2,'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s'));

        return response()->json(["status"=>"success","code"=>200,"results"=>$team_member]);
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

        $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                           array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

         foreach($team_member as $team_members){
             if(in_array($team_member_id,$team_members))
             {
                 if($team_members['id']==$team_member_id) {
                     return response()->json(["status" => "success", "code" => "200", "results" => $team_members]);
                 }
             }
         }
        return response()->json(["message"=>"The entry you want not found"]);
    }

    /**
     * accepts team member id as parameter and updates the corresponding team member
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($team_member_id){

        $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                           array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        foreach($team_member as $team_members){
            if(in_array($team_member_id,$team_members))
            {
                if($team_members['id']==$team_member_id) {
                    $team_members['teamId']=4;
                    $team_members['userId']=5;
                    return response()->json(["status" => "success", "code" => "200", "results" => $team_members]);
                }
            }
        }
        return response()->json(["message"=>"The entry you want to be updated not found"]);
    }

    /**
     * accepts team member id as parameter and soft deletes the corresponding team member
     * @param $team_member_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_member_id){

             $team_member=array(array('id'=>1,'uuid'=>'12659-adfad-7671','teamId'=>1,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                                array('id'=>2,'uuid'=>'12659-adfad-7672','teamId'=>2,'userId'=>2, 'createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        foreach($team_member as $team_members){
            if(in_array($team_member_id,$team_members))
            {
                if($team_members['id']==$team_member_id) {
                    unset($team_members);
                    return response()->json(["status" => "success", "code" => "204"]);
                }
            }
        }
        return response()->json(["message"=>"The entry you want to be updated not found"]);
    }

}