<?php

namespace App\Services;
use laravel\lumen\Application;

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
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => self::HTTP_200);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildCreateSuccessMessage($successMessage, $entity)
    {
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => self::HTTP_201, 'results' => $entity);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildUpdateSuccessMessage($successMessage, $entity)
    {
        $message = array();
        $message[] = array('message' => $successMessage, 'code' =>self::HTTP_200, 'results' => $entity);
        return $message;
    }

    /**
     * @param $successMessage
     * @param $entity
     * @return array
     */
    protected function buildRetrieveOneSuccessMessage($successMessage, $entity)
    {
        $message = array();
        $message[] = array('message' => $successMessage, 'code' => HTTP_200, 'results' => $entity);
        return $message;
    }

    /**
     * @param $message
     * @param $code
     * @return array
     */
    protected function failureMessage($message,$code)
    {
        $message = array("message"=>$message,'code'=>$code);
        return $message;
    }
}
?>