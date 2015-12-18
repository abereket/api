<?php
namespace App\Services;
use App\Models\Team;

class TeamsService {
    /**
     * creates the team
     * @param $name
     * @param $category
     * @param $agency_id
     * @return static
     */
    public function create($name,$category,$agency_id){
         $team = Team::create(['name'=>$name,'category'=>$category,'agency_id'=>$agency_id]);
         $team = Team::where('id',$team->id)->first();
         unset($team['deleted_at']);
         return $team;
    }

    /**
     * retrieves a team
     * @param $team_id
     * @return mixed
     */
    public function retrieveOne($team_id){
         $team = Team::find($team_id);
         unset($team['deleted_at']);
         return $team;
    }

    /**
     * updates a team
     * @param $id
     * @param $name
     * @param $category
     * @param $agency_id
     * @param $team_id
     * @return mixed
     */
    public function update($id,$name,$category,$agency_id,$team_id){
        $team = Team::find($team_id);
        if($team){
            $team->id         =  $id;
            $team->name       =  $name;
            $team->category   =  $category;
            $team->agency_id  =  $agency_id;
            $team->save();
            unset($team['deleted_at']);
            return $team;
        }
        return $team;
    }

    /**
     * deletes a team
     * @param $team_id
     * @return mixed
     */
    public function delete($team_id){
        $team=Team::find($team_id);
        if($team){
            $team->delete();
        }
        return $team;
    }
}
?>