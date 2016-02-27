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
        return ['message' => $successMessage, 'code' => self::HTTP_201, 'results' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildUpdateSuccessMessage($successMessage, $entity)
    {
        return ['message' => $successMessage, 'code' =>self::HTTP_200, 'results' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveSuccessMessage($successMessage,$entity){

        return ['message' => $successMessage, 'code' =>self::HTTP_200, 'results' => $entity];
    }
    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveOneSuccessMessage($successMessage, $entity)
    {
        return ['message' => $successMessage, 'code' => self::HTTP_200, 'results' => $entity];
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildEmailVerificationSuccessMessage($successMessage,$entity){
       return ['message' => $successMessage,'code' =>self::HTTP_200, 'results' =>$entity];
    }

    /**
     * @param $message
     * @param $code
     * @return array
     */
    protected function failureMessage($message,$code)
    {
        return ['message'=>$message,'code'=>$code];
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
}
?>