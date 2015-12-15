<?php
namespace App\Services;
use App\Models\TeamMember;

class TeamMember {
    public function create($user_id,$team_id){
     $teamMember=TeamMember::create(['user_id'=>$user_id,'team_id'=>$team_id]);
        return $teamMember;
    }
}