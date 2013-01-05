<?php
namespace WalyDevcloudHook\Hook\Github\Payload;

class Repository implements PayloadInterface
{
    protected $name = null;
    protected $size = null;
    protected $createdAt = null;
    protected $hasWiki = null;
    protected $private = null;
    protected $watchers = null;
    protected $fork = null;
    protected $url = null;
    protected $language = null;
    protected $id = null;
    protected $pushedAt = null;
    protected $hasDownloads = null;
    protected $openIssues = null;
    protected $hasIssues = null;
    protected $forks = null;
    protected $description = null;
    protected $stargazers = null;
    protected $owner = null;
}
