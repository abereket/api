<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Agencies;
use Illuminate\Http\Request;


class AgencyController extends Controller{

    /**
     * creates an agency
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $rules=['name'        =>     'required|max:50',  'userId'   => 'required',
                'firstName'   =>     'required|max:50',  'lastName'  => 'required|max:50','email'      =>  'required|max:60'];
        $this->validate($request,$rules);
        $agencyService = new Agencies();
        $agency = $agencyService->create($request);

        return response()->json(["status" => "success", "code" => 200, "results" => $agency]);
    }

    /**
     * retrieves agencies
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieve()
    {
        $agency =array(array("id"=>1,"uuid" => "12659-adfad-7671", "name" => "Arch Software Solutions", "description" => "Staffing company based in California",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 1,
                             "firstName" => "Amanuel", "lastName" => "Yohannes", "email" => "kibret@example.com")),array("id"=>2,"uuid" => "12659-adfad-7672", "name" => "Accenture", "description" => "Staffing company based in Idaho",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 2,
                             "firstName" => "Kibret", "lastName" => "Bereket", "email" => "kibret@example.com")));

        $count=count($agency);
        return response()->json(["status" => "success","code" => 200,"count"=>$count,"results" => $agency]);
    }

    /**
     * takes agency id as parameter and retrieves the corresponding agency
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveOne($agency_id)
    {

        $agencyService = new Agencies();
        $agency  = $agencyService->retrieveOne($agency_id);

         if($agency){
             return response()->json(["status" => "success", "code" => 200, "results" => [$agency]]);
         }

        return response()->json(["message"=>"The entry you want not found"]);
    }

    /**
     * take agency id as parameter and updates the corresponding agency
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request,$agency_id){

          $rules=['id'    =>  'required|max:11','name'        =>     'required|max:50',  'userId'   => 'required'];

          $this->validate($request,$rules);
          $agencyService=new Agencies();
          $agency=$agencyService->update($request,$agency_id);
                if($agency!==null) {
                    return response()->json(["status" => "success", "code" => 200, "results" => $agency]);
                }
        return response()->json(["message"=>"There is no entry found to be updated"]);
    }

    /**
     * takes agency id as parameter and deletes the corresponding agency
     * @param $agency_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($agency_id)
    {
        $agencyService = new Agencies();
        $agency=$agencyService ->delete($agency_id);
        if($agency){
            return response()->json(["status" => "success", "code" => "204"]);
        }
        return response()->json(["message"=>"The entry you want to be deleted not found"]);



    }

}
?>