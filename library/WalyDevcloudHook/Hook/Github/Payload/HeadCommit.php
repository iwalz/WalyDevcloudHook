<?php
namespace WalyDevcloudHook\Hook\Github\Payload;

class HeadCommit implements PayloadInterface
{
    protected $modified = array();
    protected $added = array();
    protected $removed = array();
    /**
     * @var Author
     */
    protected $author = null;
    protected $timestamp = null;
    protected $url = null;
    protected $id = null;
    protected $distinct = null;
    protected $message = null;
    /**
     * @var Committer
     */
    protected $committer = null;

    public function addAdded($added)
    {
        $this->added[] = $added;
    }

    public function getAdded()
    {
        return $this->added;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\Author $author
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\Committer $committer
     */
    public function setCommitter(Committer $committer)
    {
        $this->committer = $committer;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\Committer
     */
    public function getCommitter()
    {
        return $this->committer;
    }

    public function setDistinct($distinct)
    {
        $this->distinct = $distinct;
    }

    public function getDistinct()
    {
        return $this->distinct;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function addModified($modified)
    {
        $this->modified[] = $modified;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function addRemoved($removed)
    {
        $this->removed[] = $removed;
    }

    public function getRemoved()
    {
        return $this->removed;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

}

