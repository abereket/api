<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

class teamController extends Controller{
    /**
     * creates a team
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
        $team=array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team awesome','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s'));

        return response()->json(["status"=>"success","code"=>200,"results"=>$team]);
    }

    /**
     * retrieves a team
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve()
    {
        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                   array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count = count($team);
        return response()->json(["status"=>"success","code"=>200,"count"=>$count,"results"=>$team]);
    }

    /**
     * accepts team id as a parameter and retrieves the corresponding team
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($team_id)
    {

        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
              array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        foreach($team as $teams)
        {
            if(in_array($team_id,$teams)){
                return response()->json(["status"=>"success","code"=>"200","results"=>$teams]);
            }
        }
        return response()->json(["message"=>"The team you want to be returned not exists"]);
    }

    /**
     * accepts team id as a parameter and updates the corresponding team
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($team_id)
    {
        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                    array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        foreach($team as $teams)
        {
            if(in_array($team_id,$teams))
            {
              $teams['name']   =  'Team America';
              $teams['category']   =  'IT';
              $teams['agency_id']   =  2;

                return response()->json(["status"=>"success","code"=>200,"results" => $teams]);
            }
        }
        return response()->json(["message"=>"The entry you want to be updated is not found"]);

    }

    /**
     * accepts team id as a parameter and deletes the corresponding team
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_id)
    {
        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                    array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        foreach($team as $teams){
            if(in_array($team_id,$teams)){
                unset($teams);
                return response()->json(["status"=>"success","code"=>204]);
            }
        }
        return response()->json(["message"=>"The enetry you want to be deleted is not found"]);
    }
}