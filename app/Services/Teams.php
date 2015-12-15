<?php
namespace App\Services;
use App\Models\Team;

class Teams {
    public function create($name,$category,$agency_id){
        Team::create(['name'=>$name,'category'=>$category,'agency_id'=>$agency_id]);
        $team=Team::all();
        return $team;
    }
}
?>