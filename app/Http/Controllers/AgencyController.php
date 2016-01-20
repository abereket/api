<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Agencies;
use App\Services\AgenciesService;
use Illuminate\Http\Request;




class AgencyController extends Controller{

    //public function __construct(Request $request){
        //$this->Middleware('authenticate');
    //}
    /**
     * creates an agency
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['name'     =>     'required|max:50',
                'userId'   =>     'required|max:11|integer'];
        $this->validate($request,$rules);
        $agencyService = new AgenciesService();
        $agency = $agencyService->create($request);

        return response()->json($agency);
    }

    /**
     * retrieves agencies
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve(Request $request)
    {
        $agencyService = new AgenciesService();
        $agency        =$agencyService->retrieve($request);
        return response()->json($agency);

    }

    /**
     * This method retrieves a user corresponding to the given user id
     * @param $agency_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($agency_id)
    {
        $agencyService = new AgenciesService();
        $agency  = $agencyService->retrieveOne($agency_id);
        return response()->json($agency);
    }

    /**
     * updates an agency
     * @param Request $request
     * @param $agency_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$agency_id){

          $rules=['name'        =>     'max:50',  'userId'   => 'max:11|integer'];

          $this->validate($request,$rules);
          $agencyService=new AgenciesService();
          $agency=$agencyService->update($request,$agency_id);
          return response()->json($agency);
    }

    /**
     * takes agency id as parameter and deletes the corresponding agency
     * @param $agency_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($agency_id)
    {
        $agencyService = new AgenciesService();
        $agency=$agencyService->delete($agency_id);
        return response()->json($agency);

    }


}
?>