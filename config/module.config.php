<?php

return array(
    'devcloud_hook' => array(
        'logger' => array(
            'loglevel' => \Zend\Log\Logger::DEBUG,
            'logdir' => realpath(__DIR__ . '/../logs')
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'logger' => 'WalyDevcloudHook\Factories\LoggerServiceFactory'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'WalyDevcloudHook\\Controller\\HookController' => 'WalyDevcloudHook\\Controller\\HookController'
        )
    ),
    'router' => array(
        'routes' => array(
            'devcloud_hook' => array(
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => array(
                    'route' => '/devcloud_hook',
                    'defaults' => array(
                        'controller' => 'WalyDevcloudHook\\Controller\\HookController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    )
);
