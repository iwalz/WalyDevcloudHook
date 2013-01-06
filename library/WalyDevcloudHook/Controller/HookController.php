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
        }

        return false;
    }
}
