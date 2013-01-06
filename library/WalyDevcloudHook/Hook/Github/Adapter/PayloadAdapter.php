<?php
namespace WalyDevcloudHook\Hook\Github\Adapter;

use WalyDevcloudHook\Hook\Github\Payload,
    WalyDevcloudHook\Hook\Github\Payload\Pusher,
    WalyDevcloudHook\Hook\Github\Payload\Repository,
    WalyDevcloudHook\Hook\Github\Payload\Owner;

class PayloadAdapter implements AdapterInterface
{
    protected $payload = null;

    public function __construct($jsonPayload)
    {
        $plainPayload = json_decode($jsonPayload);
        $this->payload = new Payload();

        $pusher = new Pusher();
        $pusher->setName((string)$plainPayload->pusher->name);
        $this->payload->setPusher($pusher);

        $repository = new Repository();
        $repository->setName((string)$plainPayload->repository->name);
        $repository->setSize((int)$plainPayload->repository->size);
        $repository->setCreatedAt((string)$plainPayload->repository->created_at);
        $repository->setHasWiki((bool)$plainPayload->repository->has_wiki);
        $repository->setPrivate((bool)$plainPayload->repository->private);
        $repository->setWatchers((int)$plainPayload->repository->watchers);
        $repository->setFork((bool)$plainPayload->repository->fork);
        $repository->setUrl((string)$plainPayload->repository->url);
        if (isset($plainPayload->repository->language)) {
            $repository->setLanguage((string)$plainPayload->repository->language);
        }
        $repository->setId((int)$plainPayload->repository->id);
        $repository->setPushedAt((string)$plainPayload->repository->pushed_at);
        $repository->setHasDownloads((bool)$plainPayload->repository->has_downloads);
        $repository->setOpenIssues((int)$plainPayload->repository->open_issues);
        $repository->setHasIssues((bool)$plainPayload->repository->has_issues);
        $repository->setForks((int)$plainPayload->repository->forks);
        $repository->setDescription((string)$plainPayload->repository->description);
        $repository->setStargazers((int)$plainPayload->repository->stargazers);
        $owner = new Owner();
        $owner->setEmail((string)$plainPayload->repository->owner->email);
        $owner->setName((string)$plainPayload->repository->owner->name);
        $repository->setOwner($owner);
        $this->payload->setRepository($repository);

        $this->payload->setForced((bool)$plainPayload->forced);

        $headCommitAdapter = new CommitAdapter($plainPayload->head_commit);
        $headCommit = $headCommitAdapter->parse();
        $this->payload->setHeadCommit($headCommit);

        $this->payload->setAfter((string)$plainPayload->after);
        $this->payload->setDeleted((bool)$plainPayload->deleted);
        $this->payload->setRef((string)$plainPayload->ref);
        $this->payload->setCompare((string)$plainPayload->compare);
        $this->payload->setBefore((string)$plainPayload->before);
        $this->payload->setCreated((bool)$plainPayload->created);

        foreach ($plainPayload->commits as $commit) {
            $commitAdapter = new CommitAdapter($commit);
            $this->payload->addCommits($commitAdapter->parse());
        }
    }

    public function parse()
    {
        return $this->payload;
    }
}
