<?php
namespace App\Services;
use App\Models\TeamMember;

class TeamMembers {
    public function create($user_id,$team_id){
     $teamMember=TeamMember::create(['user_id'=>$user_id,'team_id'=>$team_id]);
      $teamMember=TeamMember::where('id',$teamMember->id)->get()->first();
    }
}