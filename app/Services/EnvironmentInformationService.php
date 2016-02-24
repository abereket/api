<?php

namespace App\Services;

class EnvironmentInformationService {
    public function getUrl(){
        return env('APP_ENV');
    }
}


