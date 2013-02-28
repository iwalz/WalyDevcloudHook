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

        if ($request->isPost()) {
            $json = $request->getPost('payload');
            if (!$json) {

                return $viewModel;
            }

            $payload = new PayloadAdapter($json);
            file_put_contents('/tmp/request', $json);

            $github = new Github($payload->parse());
            $github->cloneRepository();
            $github->checkoutCommit();

            $zdpack = new Zdpack();
            $zdpack->create('test', '/tmp');

            $xml = simplexml_load_file('/tmp/test/deployment.xml');
            unset($xml->parameters);
            unset($xml->eula);
            unset($xml->dependencies);
            file_put_contents('/tmp/test/deployment.xml', (string)$xml->asXML());
            $zdpack->copyFolder($github->getProjectDirectory(), '/tmp/test/data');
            $file = $zdpack->pack('/tmp/test', '/tmp');

            $zdpack->deleteFolder('/tmp/test');
            $zdpack->deleteFolder($github->getProjectDirectory());

            $deployment = new Deployment();
            $config = $deployment->getPluginManager()->get('config');
            $app = $deployment->applicationDeploy(
                $file,
                "http://" . $config->getHost() . '/' . $payload->getRepository()->getName()
            );
            $deployment->waitForStableState($app->getId());

            unlink($file);
        }

        return $viewModel;
    }
}
