<?php
namespace WalyDevcloudHook\Hook\Github;

use WalyDevcloudHook\Hook\Github\Payload\PayloadInterface;

class Payload implements PayloadInterface
{
    protected $pusher = null;
    protected $repository = null;
    protected $forced = null;
    protected $headCommit = null;
    protected $after = null;
    protected $deleted = null;
    protected $ref = null;
    protected $commits = array();
    protected $compare = null;
    protected $before = null;
    protected $created = null;
}
