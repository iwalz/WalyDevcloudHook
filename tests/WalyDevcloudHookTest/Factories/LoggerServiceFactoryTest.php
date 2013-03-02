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
                    'logdir' => __DIR__ . '/../../../logs'
                )
            )
        );

        $serviceManager = new \Zend\ServiceManager\ServiceManager();
        $serviceManager->setService('Config', $config);
        $loggerServiceFactory = new \WalyDevcloudHook\Factories\LoggerServiceFactory();
        $logger = $loggerServiceFactory->createService($serviceManager);
        $this->assertTrue($logger instanceof \Zend\Log\Logger);

        $assertLogger = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream(realpath(__DIR__ . '/../../../logs').'/trigger-'.date('Y-m-d').'.log');
        $priority = new \Zend\Log\Filter\Priority(\Zend\Log\Logger::DEBUG);
        $writer->addFilter($priority);
        $assertLogger->addWriter($writer);

        $this->assertEquals($assertLogger, $logger);
    }
}
