<?php
namespace WalyDevcloudHook\Hook\Github\Adapter;

use WalyDevcloudHook\Hook\Github\Payload\Commit;

class CommitAdapter implements AdapterInterface
{
    protected $commit = null;

    public function __construct($commitData)
    {
        $this->commit = new Commit();
        foreach ($commitData->modified as $modified) {
            $this->commit->addModified((string)$modified);
        }
        foreach ($commitData->added as $added) {
            $this->commit->addAdded((string)$added);
        }
        foreach ($commitData->removed as $removed) {
            $this->commit->addRemoved((string)$removed);
        }

        $author = new \WalyDevcloudHook\Hook\Github\Payload\Author();
        $author->setEmail((string)$commitData->author->email);
        $author->setUsername((string)$commitData->author->username);
        $author->setName((string)$commitData->author->name);
        $this->commit->setAuthor($author);

        $committer = new \WalyDevcloudHook\Hook\Github\Payload\Committer();
        $committer->setName((string)$commitData->committer->name);
        $committer->setUsername((string)$commitData->committer->username);
        $committer->setEmail((string)$commitData->committer->email);
        $this->commit->setCommitter($committer);

        $this->commit->setTimestamp((string)$commitData->timestamp);
        $this->commit->setUrl((string)$commitData->url);
        $this->commit->setId((string)$commitData->id);
        $this->commit->setDistinct((bool)$commitData->distinct);
        $this->commit->setMessage((string)$commitData->message);
    }

    public function parse()
    {
        return $this->commit;
    }
}

