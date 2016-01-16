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
        $rules=['name'=>'required|max:50','category'=>'max:75','agencyId'=>'required|exists:agencies,id,deleted_at,NULL'];
        $this->validate($request,$rules);
        $teamService = new TeamsService();
        $team=$teamService->create($request);

        return response()->json($team);
    }

    public function retrieve(Request $request)
    {
        $teamService = new TeamsService();
        $team = $teamService->retrieve($request);
        return response()->json($team);
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
        return response()->json($team);
    }

    /**
     * validates the user input and calls the update method in the Services.TeamsService
     * @param Request $request
     * @param $team_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$team_id)
    {
        $rules=['name'=>'max:50', 'category'=>'max:75', 'agencyId'=>'max:11|exists:agencies,id,deleted_at,NULL'];
        $this->validate($request,$rules);

        $teamService =   new TeamsService();
        $team        =   $teamService->update($request,$team_id);
        return response()->json($team);
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
        return response()->json($team);
    }
}