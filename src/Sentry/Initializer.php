<?php

namespace Oneup\Contao\Sentry;

use Contao\BackendUser;
use Contao\Config;
use Contao\FrontendUser;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\User;
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

        if (!is_array($GLOBALS['CONTAO_SENTRY'])) {
            $GLOBALS['CONTAO_SENTRY'] = [];
        }

        if (!defined('BE_USER_LOGGED_IN')) {
            define('BE_USER_LOGGED_IN', false);
        }
    }

    public function initializePHP()
    {
        if (false === $this->useSentry) {
            return;
        }

        if (true === $this->enablePHP && '' !== $this->dsn) {
            $client = new Raven_Client($this->dsn);

            if ($this->hasAuthenticatedUser()) {
                $client->user_context($this->getUserData());
            }

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

    public function initializeJS(PageModel $objPage, LayoutModel $objLayout, PageRegular $objPageRegular)
    {
        if (false === $this->useSentry) {
            return;
        }

        if (true === $this->enableJS && '' !== $this->publicDsn) {
            $GLOBALS['CONTAO_SENTRY'] = array_merge(
                ['<script type="text/javascript" src="https://cdn.ravenjs.com/3.22.1/raven.min.js"></script>'],
                $GLOBALS['CONTAO_SENTRY']
            );

            $GLOBALS['CONTAO_SENTRY'] = array_merge(
                $GLOBALS['CONTAO_SENTRY'],
                [
                    sprintf(
                        "<script type=\"text/javascript\">Raven.config('%s').install();</script>",
                        $this->publicDsn
                    )
                ]
            );

            $GLOBALS['SENTRY'] = '';

            foreach ($GLOBALS['CONTAO_SENTRY'] as $item) {
                $GLOBALS['SENTRY'] .= sprintf("%s\n", trim($item));
            }
        }
    }

    protected function hasAuthenticatedUser()
    {
        $authenticated = false;

        if ('FE' === TL_MODE) {
            $authenticated = FrontendUser::getInstance()->authenticate();
        }

        if ('BE' === TL_MODE) {
            $authenticated = BackendUser::getInstance()->authenticate();
        }

        return $authenticated;
    }

    protected function getUserData()
    {
        $user = null;
        $data = [];

        if ('FE' === TL_MODE) {
            $user = FrontendUser::getInstance()->getData();
        }

        if ('BE' === TL_MODE) {
            $user = BackendUser::getInstance()->getData();
        }

        if (is_array($user)) {
            $data = [
                'id' => $user['id'],
                'username' => $user['username'],
                'name' => array_key_exists('name', $user) ? $user['name'] : sprintf('%s %s', $user['firstname'], $user['lastname']),
                'email' => $user['email'],
            ];
        }

        return $data;
    }
}
