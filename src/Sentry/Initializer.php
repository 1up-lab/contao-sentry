<?php

namespace Oneup\Contao\Sentry;

use Contao\Config;
use Raven_Client;
use Raven_ErrorHandler;

class Initializer
{
    protected $useSentry;
    protected $dsn;
    protected $publicDsn;
    protected $enablePHP;
    protected $enableJS;
    protected $registerExceptionHandler;
    protected $registerErrorHandler;
    protected $registerShutdownFunction;

    public function __construct()
    {
        $this->useSentry = (bool) Config::get('useSentry');
        $this->dsn = (string) Config::get('sentryDSN');
        $this->publicDsn = (string) Config::get('sentryPublicDSN');
        $this->enablePHP = (bool) Config::get('sentryEnablePHP');
        $this->enableJS = (bool) Config::get('sentryEnableJs');
        $this->registerExceptionHandler = (bool) Config::get('sentryPHPRegisterExceptionHandler');
        $this->registerErrorHandler = (bool) Config::get('sentryPHPRegisterErrorHandler');
        $this->registerShutdownFunction = (bool) Config::get('sentryPHPRegisterShutdownFunction');
        $GLOBALS['CONTAO_SENTRY'] = '';
    }

    public function initialize()
    {
        if (false === $this->useSentry) {
            return;
        }

        if (true === $this->enablePHP && '' !== $this->dsn) {
            $client = new Raven_Client($this->dsn);
            $errorHandler = new Raven_ErrorHandler($client);

            if (true === $this->registerExceptionHandler) {
                $errorHandler->registerExceptionHandler();
            }

            if (true === $this->registerErrorHandler) {
                $errorHandler->registerErrorHandler();
            }

            if (true === $this->registerShutdownFunction) {
                $errorHandler->registerShutdownFunction();
            }
        }

        if (true === $this->enableJS && '' !== $this->publicDsn) {
            $GLOBALS['CONTAO_SENTRY'] .= "<script type=\"text/javascript\">https://cdn.ravenjs.com/3.10.0/raven.min.js</script>\n";
            $GLOBALS['CONTAO_SENTRY'] .= sprintf("<script type=\"text/javascript\">Raven.config('%s').install();</script>\n", $this->publicDsn);
        }
    }
}
