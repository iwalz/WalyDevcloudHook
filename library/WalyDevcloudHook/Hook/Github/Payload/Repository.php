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
    /**
     * @var Owner
     */
    protected $owner = null;

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setFork($fork)
    {
        $this->fork = $fork;
    }

    public function getFork()
    {
        return $this->fork;
    }

    public function setForks($forks)
    {
        $this->forks = $forks;
    }

    public function getForks()
    {
        return $this->forks;
    }

    public function setHasDownloads($hasDownloads)
    {
        $this->hasDownloads = $hasDownloads;
    }

    public function getHasDownloads()
    {
        return $this->hasDownloads;
    }

    public function setHasIssues($hasIssues)
    {
        $this->hasIssues = $hasIssues;
    }

    public function getHasIssues()
    {
        return $this->hasIssues;
    }

    public function setHasWiki($hasWiki)
    {
        $this->hasWiki = $hasWiki;
    }

    public function getHasWiki()
    {
        return $this->hasWiki;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOpenIssues($openIssues)
    {
        $this->openIssues = $openIssues;
    }

    public function getOpenIssues()
    {
        return $this->openIssues;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\Owner $owner
     */
    public function setOwner(Owner $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    public function setPrivate($private)
    {
        $this->private = $private;
    }

    public function getPrivate()
    {
        return $this->private;
    }

    public function setPushedAt($pushedAt)
    {
        $this->pushedAt = $pushedAt;
    }

    public function getPushedAt()
    {
        return $this->pushedAt;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setStargazers($stargazers)
    {
        $this->stargazers = $stargazers;
    }

    public function getStargazers()
    {
        return $this->stargazers;
    }

    public function setUrl($url)
    {
        $this->url = str_replace('https', 'git', $url);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setWatchers($watchers)
    {
        $this->watchers = $watchers;
    }

    public function getWatchers()
    {
        return $this->watchers;
    }

}
