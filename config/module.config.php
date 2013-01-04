<?php

return array(
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
