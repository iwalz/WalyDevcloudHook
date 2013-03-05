<?php

return array(
    'devcloud_hook' => array(
        'logger' => array(
            'loglevel' => \Zend\Log\Logger::DEBUG,
            'logdir' => realpath(__DIR__ . '/../../vendor/iwalz/waly-devcloud-hook/logs')
        ),
        'settings' => array(
            'version' => \ZendService\ZendServerAPI\Version::ZS56,
            'name' => '',
            'key' => '',
            'host' => '.my.phpcloud.com',
            'port' => 10082,
            'timeout' => 60
        )
    )
);
