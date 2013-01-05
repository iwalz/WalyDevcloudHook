<?php
namespace WalyDevcloudHook\Hook\Github\Adapter;

use WalyDevcloudHook\Hook\Github\Payload\Commit;

class CommitAdapter implements AdapterInterface
{
    protected $commit = null;

    public function __construct($commitData)
    {
        $this->commit = new Commit()
    }

    public function parse()
    {
        return $this->commit;
    }
}

