<?php
namespace WalyDevcloudHook\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    WalyDevcloudHook\Hook\Github;

class HookController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $github = new Github($request->getPost('payload'));
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
