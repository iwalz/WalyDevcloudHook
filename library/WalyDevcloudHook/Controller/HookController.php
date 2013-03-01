<?php
namespace WalyDevcloudHook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ZendService\ZendServerAPI\Zdpack;
use ZendService\ZendServerAPI\Deployment;
use WalyDevcloudHook\Hook\Github;
use WalyDevcloudHook\Hook\Github\Adapter\PayloadAdapter;

class HookController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setTerminal(false);
        $logger = $this->serviceLocator->get('logger');

        if ($request->isPost()) {
            $json = $request->getPost('payload');
            if (!$json) {

                return $viewModel;
            }

            $payload = new PayloadAdapter($json);
            $hookData = $payload->parse();

            $github = new Github($hookData);
            $github->cloneRepository();
            $github->checkoutCommit();
            $logger->info('Clone repository "' . $github->getRepository() . '" ('.$github->getHeadCommit()->getId().')');

            $tmpDir = rtrim(sys_get_temp_dir(), '/');

            $zdpack = new Zdpack();
            $zdpack->create('test', $tmpDir);

            $xml = simplexml_load_file($tmpDir.'/test/deployment.xml');
            unset($xml->parameters);
            unset($xml->eula);
            unset($xml->dependencies);
            file_put_contents($tmpDir.'/test/deployment.xml', (string)$xml->asXML());
            $logger->debug('Generated deployment.xml');
            $zdpack->copyFolder($github->getProjectDirectory(), $tmpDir.'/test/data');
            $file = $zdpack->pack($tmpDir.'/test', $tmpDir);
            if ($file) {
                $logger->debug('Generated .zpk');
            }

            $zdpack->deleteFolder($tmpDir.'/test');
            $zdpack->deleteFolder($github->getProjectDirectory());
            $logger->debug('Deleted tmp folder');

            $deployment = new Deployment();
            $config = $deployment->getPluginManager()->get('config');
            $app = $deployment->applicationDeploy(
                $file,
                "http://" . $config->getHost() . '/' . $payload->getRepository()->getName()
            );
            $deployment->waitForStableState($app->getId());
            $logger->debug('Application ' . $app->getId() . ' successful deployed');

            unlink($file);
        }

        return $viewModel;
    }
}
