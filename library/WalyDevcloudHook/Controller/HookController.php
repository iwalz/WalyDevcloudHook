<?php
namespace WalyDevcloudHook\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class HookController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            var_dump($request->getPost('payload'));
            file_put_contents('/tmp/request', $request->getPost('payload'));
        }

        return false;
    }
}
