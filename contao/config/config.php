<?php

$GLOBALS['TL_HOOKS']['initializeSystem'][] = ['Oneup\Contao\Sentry\Initializer', 'initializePHP'];
$GLOBALS['TL_HOOKS']['generatePage'][] = ['Oneup\Contao\Sentry\Initializer', 'initializeJS'];
