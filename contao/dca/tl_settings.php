<?php

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'defaultChmod',
    'defaultChmod;{sentry_legend:hide},useSentry',
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'useSentry';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'sentryEnablePHP';

$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['useSentry'] = 'sentryDSN,sentryPublicDSN,sentryEnablePHP,sentryEnableJs';
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['sentryEnablePHP'] = 'sentryPHPRegisterExceptionHandler,sentryPHPRegisterErrorHandler,sentryPHPRegisterShutdownFunction';

$GLOBALS['TL_DCA']['tl_settings']['fields'] = array_merge(
    $GLOBALS['TL_DCA']['tl_settings']['fields'],
    [
        'useSentry' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['useSentry'],
            'inputType' => 'checkbox',
            'eval' => [
                'submitOnChange' => true,
                'tl_class' => 'm12',
            ],
        ],
        'sentryDSN' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryDSN'],
            'inputType' => 'text',
            'eval' => [
                'rgxp' => 'url',
                'tl_class' => 'long',
            ],
        ],
        'sentryPublicDSN' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryPublicDSN'],
            'inputType' => 'text',
            'eval' => [
                'rgxp' => 'url',
                'tl_class' => 'long'
            ],
        ],
        'sentryEnableJs' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryEnableJs'],
            'inputType' => 'checkbox',
            'eval' => [
                'tl_class' => 'm12',
            ],
        ],
        'sentryEnablePHP' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryEnablePHP'],
            'inputType' => 'checkbox',
            'eval' => [
                'submitOnChange' => true,
                'tl_class' => 'm12',
            ],
        ],
        'sentryPHPRegisterExceptionHandler' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryPHPRegisterExceptionHandler'],
            'inputType' => 'checkbox',
            'default' => '1',
            'eval' => [
                'tl_class' => 'm12',
            ],
        ],
        'sentryPHPRegisterErrorHandler' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryPHPRegisterErrorHandler'],
            'inputType' => 'checkbox',
            'default' => '1',
            'eval' => [
                'tl_class' => 'm12',
            ],
        ],
        'sentryPHPRegisterShutdownFunction' => [
            'label' => &$GLOBALS['TL_LANG']['tl_settings']['sentryPHPRegisterShutdownFunction'],
            'inputType' => 'checkbox',
            'default' => '1',
            'eval' => [
                'tl_class' => 'm12',
            ],
        ],
    ]
);
