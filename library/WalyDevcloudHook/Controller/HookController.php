<?php
namespace WalyDevcloudHook\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZendService\ZendServerAPI\Zdpack;
use ZendService\ZendServerAPI\Deployment;
use WalyDevcloudHook\Hook\Github;
use WalyDevcloudHook\Hook\Github\Adapter\PayloadAdapter;

class HookController extends AbstractActionController
{
    /**
     * @var \Zend\Log\Logger
     */
    protected $logger = null;
    protected $config = null;

    public function __construct(ServiceLocatorInterface $serviceLocator = null)
    {
        if ($serviceLocator !== null) {
            $this->serviceLocator = $serviceLocator;
        }

        $this->logger = $this->serviceLocator->get('logger');
        $this->config = $this->serviceLocator->get('Config');
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTerminal(false);

        if ($request->isPost()) {
            $json = $request->getPost('payload');
            if (!$json) {

                return $viewModel;
            }
            $settings = $this->config['devcloud_hook']['settings'];
            set_time_limit($settings['timeout']);

            $payload = new PayloadAdapter($json);
            $hookData = $payload->parse();

            $github = new Github($hookData);
            $github->cloneRepository();
            $github->checkoutCommit();
            $this->logger->info(
                'Clone repository "' . $hookData->getRepository()->getName() .
                '" ('.$hookData->getHeadCommit()->getId().')'
            );

            $file = $this->prepareZpk($github);

            $deployment = new Deployment($settings);
            $config = $deployment->getPluginManager()->get('config');
            $app = $deployment->applicationGetStatus()->getApplicationInfoByName("test");

            if ($app !== false) {
                $deployment->applicationRemove($app->getId());
                $deployment->waitForRemoved($app->getId());
                $this->logger->debug('Application ' . $app->getId() . ' successful removed');
            }

            $app = $deployment->applicationDeploy(
                $file,
                "http://" . $config->getHost() . '/' . $hookData->getRepository()->getName(),
                false,
                true
            );
            $this->logger->debug('Application ' . $app->getId() . ' successful deployed');
        }

        return $viewModel;
    }

    protected function prepareZpk(Github $github)
    {
        $tmpDir = rtrim(sys_get_temp_dir(), '/');

        $zdpack = new Zdpack();
        $zdpack->create('test', $tmpDir);
        $zdpack->deleteFolder($tmpDir.'/test/data');
        mkdir($tmpDir.'/test/data', 0755);

        $xml = simplexml_load_file($tmpDir.'/test/deployment.xml');
        unset($xml->parameters);
        unset($xml->eula);
        unset($xml->dependencies);
        file_put_contents($tmpDir.'/test/deployment.xml', (string)$xml->asXML());
        $this->logger->debug('Generated deployment.xml');
        $zdpack->copyFolder($github->getProjectDirectory(), $tmpDir.'/test/data');
        unlink($tmpDir.'/test.zpk');
        $zdpack->deleteFolder($tmpDir.'/test/data/.git');
        $file = $zdpack->pack($tmpDir.'/test', $tmpDir);
        if ($file) {
            $this->logger->debug('Generated .zpk - ' . $file);
        }

        $zdpack->deleteFolder($tmpDir.'/test');
        $zdpack->deleteFolder($github->getProjectDirectory());
        $this->logger->debug('Deleted tmp folder');

        return (string)$file;
    }
}
