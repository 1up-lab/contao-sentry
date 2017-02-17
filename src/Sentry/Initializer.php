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
    }

    public function initializePHP()
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
    }

    public function initializeJS()
    {
        if (false === $this->useSentry) {
            return;
        }

        if (true === $this->enableJS && '' !== $this->publicDsn) {
            if (null === $GLOBALS['TL_JAVASCRIPT']) {
                $GLOBALS['TL_JAVASCRIPT'] = [];
            }

            if (null === $GLOBALS['TL_BODY']) {
                $GLOBALS['TL_BODY'] = [];
            }

            // put in first place
            $GLOBALS['TL_JAVASCRIPT'] = array_merge(
                ['https://cdn.ravenjs.com/3.10.0/raven.min.js'],
                $GLOBALS['TL_JAVASCRIPT']
            );

            $GLOBALS['TL_BODY'] = array_merge(
                [sprintf(
                    '<script type="text/javascript">Raven.config(\'%s\').install();</script>',
                    $this->publicDsn
                )],
                $GLOBALS['TL_BODY']
            );
        }
    }
}
