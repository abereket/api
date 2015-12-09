<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
class AgencyController extends Controller{

    /**
     * creates an agency
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create()
    {
        $agency = array("uuid" => "12659-adfad-7671", "name" => "Arch Software Solutions", "description" => "Staffing company based in California",
                        "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 1,
                        "firstName" => "Amanuel", "lastName" => "Yohannes", "email" => "kibret@example.com"));

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

        $agency =array(array("id"=>1,"uuid" => "12659-adfad-7671", "name" => "Arch Software Solutions", "description" => "Staffing company based in California",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 1,
                             "firstName" => "Amanuel", "lastName" => "Yohannes", "email" => "kibret@example.com")),array("id"=>2,"uuid" => "12659-adfad-7672", "name" => "Accenture", "description" => "Staffing company based in Idaho",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 2,
                             "firstName" => "Kibret", "lastName" => "Bereket", "email" => "kibret@example.com")));

         foreach($agency as $agencies){
             if(in_array($agency_id,$agencies)){
                 if($agencies['id']==$agency_id) {
                     return response()->json(["status" => "success", "code" => 200, "results" => $agencies]);
                 }
             }
         }
        return response()->json(["message"=>"The entry you want not found"]);
    }

    /**
     * take agency id as parameter and updates the corresponding agency
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($agency_id){

        $agency =array(array("id"=>1,"uuid" => "12659-adfad-7671", "name" => "Arch Software Solutions", "description" => "Staffing company based in California",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 1,
                             "firstName" => "Amanuel", "lastName" => "Yohannes", "email" => "kibret@example.com")),array("id"=>2,"uuid" => "12659-adfad-7672", "name" => "Accenture", "description" => "Staffing company based in Idaho",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 2,
                             "firstName" => "Kibret", "lastName" => "Bereket", "email" => "kibret@example.com")));

        foreach($agency as $agencies)
        {
            if(in_array($agency_id,$agencies)){
                if($agencies['id']==$agency_id) {
                    $agencies['name'] = "Arch software solutions Updated";
                    $agencies['description'] = "Staffing company based in California Updated";
                    $agencies['user']['id'] = 10;

                    return response()->json(["status" => "success", "code" => 200, "results" => $agencies]);
                }
            }
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
        $agency =array(array("id"=>1,"uuid" => "12659-adfad-7671", "name" => "Arch Software Solutions", "description" => "Staffing company based in California",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 1,
                             "firstName" => "Amanuel", "lastName" => "Yohannes", "email" => "kibret@example.com")),array("id"=>2,"uuid" => "12659-adfad-7672", "name" => "Accenture", "description" => "Staffing company based in Idaho",
                             "createdAt" => date("Y-m-d H:i:s"), "updatedAt" => date("Y-m-d H:i:s"), "user" => array("id" => 2,
                             "firstName" => "Kibret", "lastName" => "Bereket", "email" => "kibret@example.com")));

        foreach($agency as $agencies){
            if(in_array($agency_id,$agencies)){
                if($agencies['id']==$agency_id) {
                    unset($agencies);

                    return response()->json(["status" => "success", "code" => "204"]);
                }
            }
        }
        return response()->json(["message"=>"The entry you want to be deleted not found"]);
    }

}
?>