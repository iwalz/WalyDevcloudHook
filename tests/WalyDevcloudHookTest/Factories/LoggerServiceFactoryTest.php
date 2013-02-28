<?php
namespace WalyDevcloudHookTest\Factories;

use PHPUnit_Framework_TestCase;

class LoggerServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testLoggerServiceFactory()
    {
        $config = array(
            'devcloud_hook' => array(
                'logger' => array(
                    'loglevel' => \Zend\Log\Logger::DEBUG,
                    'logdir' => realpath(__DIR__ . '/../../../../logs')
                )
            )
        );
        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('Config', $config);
        $loggerServiceFactory = new \WalyDevcloudHook\Factories\LoggerServiceFactory();
        $logger = $loggerServiceFactory->createService($serviceManager);

    }
}
