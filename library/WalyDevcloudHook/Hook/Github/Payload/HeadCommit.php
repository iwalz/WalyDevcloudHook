<?php
namespace WalyDevcloudHook\Hook\Github\Payload;

class HeadCommit implements PayloadInterface
{
    protected $modified = array();
    protected $added = array();
    protected $removed = array();
    protected $author = null;
    protected $timestamp = null;
    protected $url = null;
    protected $id = null;
    protected $distinct = null;
    protected $message = null;
    protected $committer = null;
}

