<?php
namespace App\Services;
use App\Models\Agency;
use App\Models\Team;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Pagination;
class TeamsService extends Base{
    /**
     * @param $request
     * @return array|static
     */
    public function create($request){
         $valError        = $this->validateCreate($request->json()->get('agencyId'));
         if($valError){
             $valError    =  $this->failureMessage($valError,parent::HTTP_404);
             return $valError;
         }
         $team            = Team::create(['uuid'=>Uuid::uuid(),'name'=>$request->json()->get('name'),'category'=>$request->json()->get('category'),'agency_id'=>$request->json()->get('agencyId')]);

         $team=$this->buildCreateSuccessMessage("success",$team);
         return $team;
    }

    /**
     * @param $request
     * @return array|string
     */
    public function retrieve($request){
        $agencyId    =    $request->input('agencyId');
        $limit       =    ($request->input('per_page'))?$request->input('per_page'):15;
        $order_by    =    ($request->input('order_by'))?$request->input('order_by'):'updated_at';

        $valError = $this->validateRetrieve($agencyId);
        if($valError){
            $valError = $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $team = Team::where('agency_id',$agencyId)->orderby($order_by)->Paginate($limit);

        $team = $this->buildRetrieveResponse($team->toArray());
        $team = $this->buildRetrieveSuccessMessage("success",$team);
        return $team;
    }

    /**
     * retrieves a team
     * @param $team_id
     * @return mixed
     */
    public function retrieveOne($team_id){
         $team            =  Team::find($team_id);
         $valError        =  $this->validateRetrieveOne($team);
         if($valError){
             $valError    =  $this->failureMessage($valError,parent::HTTP_404);
             return $valError;
         }

         $team            =  $this->buildRetrieveOneSuccessMessage("success",$team);
         return $team;
    }

    /**
     * @param $request
     * @param $team_id
     * @return mixed
     */
    public function update($request,$team_id){
        $team             =    Team::find($team_id);
        $valError         =    $this->validateUpdate($team,$request->json()->get('agencyId'));
        if($valError){
            $valError     =    $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $team->name       =   ($request->json()->get('name'))?($request->json()->get('name')):$team->name;
        $team->category   =   ($request->json()->get('category'))?($request->json()->get('category')):$team->category;
        $team->agency_id  =   ($request->json()->get('agencyId'))?($request->json()->get('agencyId')):$team->agency_id;
        $team->save();

        $team             =   $this->buildUpdateSuccessMessage("success",$team);
        return $team;
    }

    /**
     * deletes a team
     * @param $team_id
     * @return mixed
     */
    public function delete($team_id){
        $team            =       Team::find($team_id);
        $valError        =       $this->validateDelete($team);
        if($valError){
            $valError    =       $this->failureMessage($valError,parent::HTTP_404);
            return $valError;
        }
        $team->delete();
        $team            =       $this->buildDeleteSuccessMessage("success");
        return $team;
    }

    /**
     * This method performs business class validation for teams  create method
     * @param $agencyId
     * @return array|string
     */
    protected function validateCreate($agencyId)
    {
        $errors = array();
            $agency = Agency::find($agencyId);
            if (!$agency) {
                $errors = "The value you entered not exists.please enter a valid agency id";
            }
        return $errors;
    }

    /**
     * This method performs business class validation for teams  retrieve method
     * @param $agencyId
     * @return array|string
     */
    protected function validateRetrieve($agencyId){
        $errors = array();
        if(!$agencyId){
            $errors ="You are advised to enter agencyId of the values you want to be searched";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for teams retrieveOne method
     * @param $team
     * @return array
     */
    protected function validateRetrieveOne($team){
        $errors        =    array();
        if(!$team){
            $errors  =    "Please provide a valid team";
        }
        return $errors;
    }

    /**
     * This method performs business class validation for teams update method
     * @param $team
     * @param $agencyId
     * @return array
     */
    protected function validateUpdate($team,$agencyId){
        $errors            =   array();
        if(!$team){
            $errors      =  "Please provide a valid team";
        }
        if($agencyId) {
            $agency        =   Agency::find($agencyId);
            if(!$agency){
                $errors  =   "The value you entered not exists.please enter a valid agency id";
            }
        }
        return $errors;
    }

    /**
     * This method performs business class validation for teams delete method
     * @param $team
     * @return array
     */
    protected function validateDelete($team){
        $errors       =  array();
        if(!$team){
            $errors =   "Please provide a valid team";
        }
        return $errors;
    }
}
?>