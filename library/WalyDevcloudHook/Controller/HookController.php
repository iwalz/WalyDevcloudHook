<?php
namespace WalyDevcloudHook\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    WalyDevcloudHook\Hook\Github,
    WalyDevcloudHook\Hook\Github\Adapter\PayloadAdapter;

class HookController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $json = $request->getPost('payload');
            $payload = new PayloadAdapter($json);
            file_put_contents('/tmp/request', $json);

            $github = new Github($payload->parse());
            $github->cloneRepository();
            $github->checkoutCommit();

            $zdpack = new \ZendService\ZendServerAPI\Zdpack();
            $zdpack->create('test', '/tmp');

            $xml = simplexml_load_file('/tmp/test/deployment.xml');
            unset($xml->parameters);
            file_put_contents('/tmp/test/deployment.xml', (string)$xml->asXML());
            $zdpack->copyFolder($github->getProjectDirectory(), '/tmp/test/data');
            $file = $zdpack->pack('/tmp/test', '/tmp');

            $zdpack->deleteFolder('/tmp/test');
            $zdpack->deleteFolder($github->getProjectDirectory());

            #$deployment = new \ZendService\ZendServerAPI\Deployment();
            #$config = $deployment->getPluginManager()->get('config');
            #$deployment->applicationDeploy($file, "http://" . $config->getHost() . '/' . $payload->getRepository()->getName());

            #unlink($file);
        }

        return false;
    }
}
