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
            set_time_limit(60);

            $payload = new PayloadAdapter($json);
            $hookData = $payload->parse();

            $github = new Github($hookData);
            $github->cloneRepository();
            $github->checkoutCommit();
            $logger->info('Clone repository "' . $hookData->getRepository()->getName() . '" ('.$hookData->getHeadCommit()->getId().')');

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
            $logger->debug('Generated deployment.xml');
            $zdpack->copyFolder($github->getProjectDirectory(), $tmpDir.'/test/data');
            unlink($tmpDir.'/test.zpk');
            $zdpack->deleteFolder($tmpDir.'/test/data/.git');
            $file = $zdpack->pack($tmpDir.'/test', $tmpDir);
            if ($file) {
                $logger->debug('Generated .zpk - ' . $file);
            }

            $zdpack->deleteFolder($tmpDir.'/test');
            $zdpack->deleteFolder($github->getProjectDirectory());
            $logger->debug('Deleted tmp folder');

            $deployment = new Deployment(
                array(
                    'version' => \ZendService\ZendServerAPI\Version::ZS56,
                    'name' => 'api',
                    'key' => 'd99446d17dbef49273e9463bc5fc81be999bc54670ee2deb483e93acb6e9b2e9',
                    'host' => 'iwalz.my.phpcloud.com',
                    'port' => 10082
                )
            );
            $config = $deployment->getPluginManager()->get('config');
            $app = $deployment->applicationGetStatus()->getApplicationInfoByName("http://" . $config->getHost() . '/' . $hookData->getRepository()->getName());
            $logger->debug($app);

            if ($app !== false) {
                $deployment->applicationRemove($app->getId());
                $logger->debug('Application ' . $app->getId() . ' successful removed');
            }
            $app = $deployment->applicationDeploy(
                (string)$file,
                "http://" . $config->getHost() . '/' . $hookData->getRepository()->getName(),
                false,
                true
            );
            $logger->debug('Application ' . $app->getId() . ' successful deployed');
        }

        return $viewModel;
    }
}
