<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\Teams;
use Illuminate\Http\Request;

class teamController extends Controller{
    /**
     * validates the user input and calls the create method in Services.Teams
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['name'=>'required|max:50','category'=>'max:75','agencyId'=>'required'];
        $this->validate($request,$rules);
        $teamService = new Teams();
        $team=$teamService->create($request->name,$request->input('category'),$request->agencyId);

        return response()->json(["status"=>"success","code"=>200,"results"=>$team]);
    }

    public function retrieve()
    {
        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                   array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count = count($team);
        return response()->json(["status"=>"success","code"=>200,"count"=>$count,"results"=>$team]);
    }

    /**
     * calls the retrieveOne method in Services.Teams
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($team_id)
    {
        $temService = new Teams();
        $team=$temService->retrieveOne($team_id);
          if($team!==null) {
              return response()->json(["status" => "success", "code" => "200", "results" => $team]);
          }
        return response()->json(["message"=>"The team you want to be returned not exists"]);
    }

    /**
     * validates the user input and calls the update method in the Services.Teams
     * @param Request $request
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_id)
    {
        $rules=['id' => 'required|max:11', 'name'=>'required|max:50', 'category'=>'max:75', 'agencyId'=>'required'];
        $this->validate($request,$rules);

        $teamService =   new Teams();
        $team        =   $teamService->update($request->id,$request->name,$request->category,$request->agencyId,$team_id);

        if($team!==null)
        {
            return response()->json(["status" => "success", "code" => 200, "results" => $team]);
        }

        return response()->json(["message"=>"The entry you want to be updated is not found"]);

    }

    /**
     * calls the delete method in the Services.teams
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_id)
    {
        $teamService=new Teams();
        $team=$teamService->delete($team_id);
        if($team){
            return response()->json(["status" => "success", "code" => 204]);
        }
        return response()->json(["message"=>"The enetry you want to be deleted is not found"]);
    }
}