<?php
namespace WalyDevcloudHook\Factories;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Logger;
use Zend\Log\Filter\Priority;
use Zend\Log\Writer\Stream;

class LoggerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $loggerConfig = $config['devcloud_hook']['logger'];
        $logger = new Logger();

        var_dump($loggerConfig['logdir']);
        chmod($loggerConfig['logdir'], 0755);
        $stream = new Stream($loggerConfig['logdir'].'/trigger-'.date('Y-m-d').'.log');
        $filter = new Priority($loggerConfig['loglevel']);
        $stream->addFilter($filter);
        $logger->addWriter($stream);

        return $logger;
    }
}
