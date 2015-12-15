<?php
namespace App\Services;
use App\Models\Team;

class Teams {
    public function create($name,$category,$agency_id){
         $team=Team::create(['name'=>$name,'category'=>$category,'agency_id'=>$agency_id]);
         $team=Team::where('id',$team->id)->first();
         unset($team['deleted_at']);
         return $team;
    }

    public function retrieveOne($team_id){
        $team=Team::find($team_id);
        unset($team['deleted_at']);
        return $team;
    }
    public function update($name,$category,$agency_id,$team_id){
        $team = Team::find($team_id);
        if($team){
            $team->name=$name;
            $team->category=$category;
            $team->agency_id=$agency_id;
            $team->save();
            unset($team['deleted_at']);
            return $team;
        }
        return $team;
    }
    public function delete($team_id){
        $team=Team::find($team_id);
        if($team){
            $team->delete();
            $team='success';
            return $team;
        }
        return $team;
    }
}
?>