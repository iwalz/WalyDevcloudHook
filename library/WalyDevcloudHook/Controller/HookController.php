<?php
namespace WalyDevcloudHook\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class HookController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        var_dump((string)$request);
        file_put_contents('/tmp/request', $request);
    }
}
