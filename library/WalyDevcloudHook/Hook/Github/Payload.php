<?php
namespace WalyDevcloudHook\Hook\Github;

use WalyDevcloudHook\Hook\Github\Payload\PayloadInterface,
    WalyDevcloudHook\Hook\Github\Payload\Pusher,
    WalyDevcloudHook\Hook\Github\Payload\Repository,
    WalyDevcloudHook\Hook\Github\Payload\HeadCommit,
    WalyDevcloudHook\Hook\Github\Payload\Commit;

class Payload implements PayloadInterface
{
    /**
     * @var Payload\Pusher
     */
    protected $pusher = null;
    /**
     * @var Payload\Repository
     */
    protected $repository = null;
    protected $forced = null;
    /**
     * @var Payload\HeadCommit
     */
    protected $headCommit = null;
    protected $after = null;
    protected $deleted = null;
    protected $ref = null;
    protected $commits = array();
    protected $compare = null;
    protected $before = null;
    protected $created = null;

    public function setAfter($after)
    {
        $this->after = $after;
    }

    public function getAfter()
    {
        return $this->after;
    }

    public function setBefore($before)
    {
        $this->before = $before;
    }

    public function getBefore()
    {
        return $this->before;
    }

    public function addCommits(Commit $commits)
    {
        $this->commits[] = $commits;
    }

    public function getCommits()
    {
        return $this->commits;
    }

    public function setCompare($compare)
    {
        $this->compare = $compare;
    }

    public function getCompare()
    {
        return $this->compare;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setForced($forced)
    {
        $this->forced = $forced;
    }

    public function getForced()
    {
        return $this->forced;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\HeadCommit $headCommit
     */
    public function setHeadCommit(HeadCommit $headCommit)
    {
        $this->headCommit = $headCommit;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\HeadCommit
     */
    public function getHeadCommit()
    {
        return $this->headCommit;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\Pusher $pusher
     */
    public function setPusher(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\Pusher
     */
    public function getPusher()
    {
        return $this->pusher;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param \WalyDevcloudHook\Hook\Github\Payload\Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \WalyDevcloudHook\Hook\Github\Payload\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

}
