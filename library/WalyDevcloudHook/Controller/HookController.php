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
            $payload = new PayloadAdapter($request->getPost('payload'));
            $github = new Github($payload->parse());
            $github->cloneRepository();
            $github->checkoutCommit();

            $zdpack = new \ZendService\ZendServerAPI\Zdpack();
            $zdpack->create('test', '/tmp');

            $xml = simplexml_load_file('/tmp/test/deployment.xml');
            unset($xml->parameters);
            file_put_contents('/tmp/test/deployment.xml', (string)$xml->asXML());
            $zdpack->copyFolder($github->getProjectDirectory(), '/tmp/test/data');
            $zdpack->pack('/tmp/test', '/tmp/deploy.zpk');
        }

        return false;
    }
}
