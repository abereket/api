<?php

namespace App\Services;

abstract class Base
{

    const HTTP_404 = 404;
    const HTTP_401 = 401;
    const HTTP_200 = 200;
    const HTTP_201 = 201;

    /**
     * @param $successMessage
     * @return array
     */
    protected function buildDeleteSuccessMessage($successMessage)
    {
        return ['message' => $successMessage, 'code' => self::HTTP_200];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildCreateSuccessMessage($successMessage, $entity)
    {
        $entity = $this->buildSuccessResponse($entity);
        $entity = $this->hidePassword($entity);
        return ['message' => $successMessage, 'code' => self::HTTP_201, 'data' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildUpdateSuccessMessage($successMessage, $entity)
    {
        $entity = $this->buildSuccessResponse($entity);
        $entity = $this->hidePassword($entity);
        return ['message' => $successMessage, 'code' =>self::HTTP_200, 'data' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveSuccessMessage($successMessage,$entity){
        return ['message' => $successMessage, 'code' =>self::HTTP_200, 'data' => $entity];
    }
    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveOneSuccessMessage($successMessage, $entity)
    {
        $entity = $this->buildSuccessResponse($entity);
        $entity = $this->hidePassword($entity);
        return ['message' => $successMessage, 'code' => self::HTTP_200, 'data' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildEmailVerificationSuccessMessage($successMessage,$entity){
       $entity = $this->buildSuccessResponse($entity);
       return ['message' => $successMessage,'code' =>self::HTTP_200, 'data' =>$entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildAuthenticateSuccessMessage($successMessage, $entity)
    {   $entity=['results'=>[$entity]];
        $entity=$this->hidePassword($entity);
        return ['message' => $successMessage, 'code' => self::HTTP_200, 'data' => $entity];
    }
    /**
     * @param $errors
     * @param $code
     * @return array
     */
    protected function failureMessage($errors,$code)
    {
        $message = "Please fix your errors";
        return ['message'=>$message,'code'=>$code,'errors'=>[$errors]];
    }

    /**
     * @param array $input
     * @return array
     */
    public function buildRetrieveResponse(array $input)
    {
        return [
            'total'         => isset($input['total'])?$input['total']:0,
            'per_page'      => isset($input['per_page'])?$input['per_page']:15,
            'current_page'  => isset($input['current_page'])?$input['current_page']:1,
            'last_page'     => isset($input['last_page'])?$input['last_page']:0,
            'results'       => isset($input['data'])?$input['data']:[],
        ];
    }

    /**
     * @param $entity
     * @return array
     */
    protected  function buildSuccessResponse($entity){
        return [
             'total'        => count($entity),
             'per_page'     => 1,
             'current_page' => 1,
             'last_page'    => 1,
             'results'      => (is_array($entity) || count($entity)>1)?$entity:[$entity]
        ];
    }

    /**
     * @return bool
     */
    protected function searchValueExists()
    {
        foreach (func_get_args() as $arg)
            if (empty($arg))
                continue;
            else
                return false;
        return true;
    }

    /**
     * @param $code
     * @return array
     */
    protected function buildEmptyErrorResponse($code){
        $message = 'Please fix your errors';
        $errors['search_parameter'] = "There should be search parameters";
        return ['message'=>$message,'code'=>$code,'errors'=>[$errors]];
    }

    /**
     * @param $user
     * @return mixed
     */
    protected function hidePassword($user){
        foreach($user['results'] as $results){
            unset($results['password']);
            $result[] = $results;
        }
        $user['results'] = $result;
        return $user;
    }

    /**
     * @param $user
     * @return string
     */
    protected function setInvitedBy($user){
        if($user->invited_by!=null){
            $userInv= User::find($user->invited_by);
            $invitedBy = $userInv->first_name." ".$userInv->last_name;
        }
        $invitedBy=(isset($invitedBy))?$invitedBy:'Zemployee Admin';
        return $invitedBy;
    }
}
?>