<?php

namespace App\Services;

abstract class Base
{

    const HTTP_404 = 404;
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

    protected function buildAuthenticateSuccessMessage($successMessage, $entity)
    {   $entity=['results'=>[$entity]];
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
            'total'         => $input['total'],
            'per_page'      => $input['per_page'],
            'current_page'  => $input['current_page'],
            'last_page'     => $input['last_page'],
            'results'       => $input['data'],
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
}
?>