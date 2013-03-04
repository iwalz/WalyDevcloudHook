<?php

return array(
    'bjyauthorize' => array(
        'guards'                => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'WalyDevcloudHook\\Controller\\HookController', 'roles' => array())
            )
        )
    ),
    'devcloud_hook' => array(
        'logger' => array(
            'loglevel' => \Zend\Log\Logger::DEBUG,
            'logdir' => realpath(__DIR__ . '/../logs')
        ),
        'settings' => array(
            'version' => \ZendService\ZendServerAPI\Version::ZS56,
            'name' => 'api',
            'key' => 'd99446d17dbef49273e9463bc5fc81be999bc54670ee2deb483e93acb6e9b2e9',
            'host' => 'iwalz.my.phpcloud.com',
            'port' => 10082,
            'timeout' => 60
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
