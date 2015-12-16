<?php
namespace App\Services;
use App\Models\TeamMember;

class TeamMembers {

    public function create($user_id,$team_id){

        $teamMember=TeamMember::create(['user_id'=>$user_id,'team_id'=>$team_id]);
        $teamMember=TeamMember::where('id',$teamMember->id)->first();

        unset($teamMember['deleted_at']);

        return $teamMember;
    }

    public function retrieveOne($team_member_id){

        $teamMember=TeamMember::find($team_member_id);
        if($teamMember) {
            unset($teamMember['deleted_at']);
            return $teamMember;
        }
        return $teamMember;
    }

    public function update($id,$user_id,$team_id,$team_member_id){
      $teamMember=TeamMember::find($team_member_id);
        if($teamMember){
            $teamMember->id      =  $id;
            $teamMember->user_id =  $user_id;
            $teamMember->team_id =  $team_id;
            $teamMember->save();
            unset($teamMember['deleted_at']);
            return $teamMember;
        }
        return $teamMember;

    }
    public function delete($team_member_id){
        $teamMember=TeamMember::find($team_member_id);
        if($teamMember){
            $teamMember->delete();
            return $teamMember;
        }
        return $teamMember;
    }
}