<?php
namespace App;

use Laravel\Lumen\Application as LumenApplication;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Application extends LumenApplication
{

    protected function registerLogBindings()
    {
        $this->singleton('Psr\Log\LoggerInterface', function () {
            return new Logger('lumen', $this->getMonologHandler());
        });
    }

    /**
     * @return array|\Monolog\Handler\AbstractHandler
     */
    protected function getMonologHandler()
    {
        $d  =   date('Y-m-d');
        $handlers       = [];
        $handlers[]     =   (new StreamHandler(storage_path("logs/ln_info_{$d}.log"), Logger::INFO))->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[]     =   (new StreamHandler(storage_path("logs/ln_warning_{$d}.log"), Logger::WARNING))->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[]     =   (new StreamHandler(storage_path("logs/ln_error_{$d}.log"), Logger::ERROR))->setFormatter(new LineFormatter(null, null, true, true));
        $handlers[]     =   (new StreamHandler(storage_path("logs/ln_critical_{$d}.log"), Logger::CRITICAL))->setFormatter(new LineFormatter(null, null, true, true));

        return $handlers;
    }
}


