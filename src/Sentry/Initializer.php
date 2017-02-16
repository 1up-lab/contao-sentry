<?php

namespace Oneup\Contao\Sentry;

class Initializer
{
    public function initialize()
    {
        $client = new \Raven_Client('https://xxx@sentry.io/xxx');
        $client->install();
    }
}
