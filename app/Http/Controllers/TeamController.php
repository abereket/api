<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Services\TeamsService;
use Illuminate\Http\Request;

class teamController extends Controller{
    /**
     * validates the user input and calls the create method in Services.TeamsService
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['name'=>'required|max:50','category'=>'max:75','agencyId'=>'required'];
        $this->validate($request,$rules);
        $teamService = new TeamsService();
        $team=$teamService->create($request->name,$request->input('category'),$request->agencyId);

        return response()->json(["status"=>"success","code"=>parent::HTTP_200,"results"=>$team]);
    }

    public function retrieve()
    {
        $team=array(array('id'=>1,'uuid'=>'12659-adfad-767','name'=>'Team one','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')),
                   array('id'=>2,'uuid'=>'12659-adfad-768','name'=>'Team two','agency_id'=>1,'category'=>'Financing','createdAt'=>date('Y-m-d H:i:s'),'updatedAt'=>date('Y-m-d H:i:s')));

        $count = count($team);
        return response()->json(["status"=>"success","code" => parent::HTTP_200,"count"=>$count,"results" => $team]);
    }

    /**
     * calls the retrieveOne method in Services.TeamsService
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($team_id)
    {
        $temService = new TeamsService();
        $team=$temService->retrieveOne($team_id);
          if($team == null) {
              return response()->json(["message"=>"The team you want to be returned not exists"]);
          }
           return response()->json(["status" => "success", "code" => parent::HTTP_200, "results" => $team]);
    }

    /**
     * validates the user input and calls the update method in the Services.TeamsService
     * @param Request $request
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_id)
    {
        $rules=['id' => 'required|max:11', 'name'=>'required|max:50', 'category'=>'max:75', 'agencyId'=>'required'];
        $this->validate($request,$rules);

        $teamService =   new TeamsService();
        $team        =   $teamService->update($request->id,$request->name,$request->category,$request->agencyId,$team_id);

        if($team == null)
        {
            return response()->json(["message"=>"The entry you want to be updated is not found"]);
        }
        return response()->json(["status" => "success", "code" => parent::HTTP_200, "results" => $team]);


    }

    /**
     * calls the delete method in the Services.teams
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($team_id)
    {
        $teamService=new TeamsService();
        $team=$teamService->delete($team_id);
        if(!$team){
            return response()->json(["message"=>"The entry you want to be deleted is not found"]);
        }
        return response()->json(["status" => "success", "code" => parent::HTTP_204]);
    }
}