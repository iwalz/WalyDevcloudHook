<?php

return array(
    'devcloud_hook' => array(
        'logger' => array(
            'loglevel' => \Zend\Log\Logger::DEBUG,
            'logdir' => realpath(__DIR__ . '/../logs')
        ),
        'settings' => array(
            'version' => \ZendService\ZendServerAPI\Version::ZS56,
            'name' => '',
            'key' => '',
            'host' => '.my.phpcloud.com',
            'port' => 10082,
            'timeout' => 60
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'logger' => 'WalyDevcloudHook\Service\LoggerServiceFactory'
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
