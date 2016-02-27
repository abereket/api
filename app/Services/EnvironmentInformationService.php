<?php

namespace App\Services;

class EnvironmentInformationService {
    /**
     * returns the APP_ENV variable of the environment file
     * @return mixed
     */
    public function getUrl(){
        return env('APP_ENV');
    }
}


