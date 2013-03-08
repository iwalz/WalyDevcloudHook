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

    public function indexAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $this->logger = $this->serviceLocator->get('logger');
        $this->config = $this->serviceLocator->get('Config');

        if ($request->isPost()) {
            $json = $request->getPost('payload');
            if (!$json) {
                $response->setStatusCode(400);

                return $response;
            }
            $settings = $this->config['devcloud_hook']['settings'];
            set_time_limit($settings['timeout']);

            $payload = new PayloadAdapter($json);
            $hookData = $payload->parse();

            $github = new Github($hookData);
            $github->setDirectory(sys_get_temp_dir() . '/repo/');
            $github->cloneRepository();
            $github->checkoutCommit();
            $this->logger->info(
                'Clone repository "' . $hookData->getRepository()->getName() .
                '" ('.$hookData->getHeadCommit()->getId().')'
            );

            $file = $this->prepareZpk($github);

            $deployment = new Deployment($settings);
            $config = $deployment->getPluginManager()->get('config');
            $app = $deployment->applicationGetStatus()->getApplicationInfoByName($hookData->getRepository()->getName());

            if ($app !== false) {
                $deployment->applicationRemove($app->getId());
                $deployment->waitForRemoved($app->getId());
                $this->logger->debug('Application ' . $app->getId() . ' successful removed');
            }

            $app = $deployment->applicationDeploy(
                $file,
                "http://" . $config->getHost() . '/' . $hookData->getRepository()->getName(),
                false,
                true,
                $hookData->getRepository()->getName()
            );
            $this->logger->debug('Application ' . $app->getId() . ' successful deployed');
        }
        $response->setStatusCode(400);

        return $response;
    }

    protected function prepareZpk(Github $github)
    {
        $payload = $github->getPayload();
        $projectName = $payload->getRepository()->getName();
        $tmpDir = rtrim(sys_get_temp_dir(), '/');
        $projectDir = $tmpDir . '/' . $projectName;

        $zdpack = new Zdpack();
        $zdpack->create($projectName, $tmpDir);
        $zdpack->deleteFolder($projectDir.'/data');
        mkdir($projectDir.'/data', 0755);

        $xml = simplexml_load_file($projectDir.'/deployment.xml');
        unset($xml->parameters);
        unset($xml->eula);
        unset($xml->dependencies);
        file_put_contents($projectDir.'/deployment.xml', (string)$xml->asXML());
        $this->logger->debug('Generated deployment.xml');
        $zdpack->deleteFolder($github->getProjectDirectory().'/.git');
        $zdpack->copyFolder($github->getProjectDirectory(), $projectDir.'/data');
        unlink($tmpDir.'/'.$projectName.'.zpk');

        $file = $zdpack->pack($projectDir, $tmpDir);
        if ($file) {
            $this->logger->debug('Generated .zpk - ' . $file);
        }

        $zdpack->deleteFolder($projectDir);
        $zdpack->deleteFolder($github->getProjectDirectory());
        $this->logger->debug('Deleted tmp folder');

        return (string)$file;
    }
}
