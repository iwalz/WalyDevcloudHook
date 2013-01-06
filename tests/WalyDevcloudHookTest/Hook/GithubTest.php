<?php
namespace WalyDevcloudHookTest;

use WalyDevcloudHook\Hook\Github\Adapter\CommitAdapter,
    WalyDevcloudHook\Hook\Github\Adapter\PayloadAdapter,
    WalyDevcloudHook\Hook\Github\Payload\Committer,
    WalyDevcloudHook\Hook\Github\Payload\Owner,
    WalyDevcloudHook\Hook\Github\Payload\Author,
    WalyDevcloudHook\Hook\Github;

class GithubTest extends \PHPUnit_Framework_TestCase
{
    public function testGithubCommitAdaption()
    {
        $json = json_decode(file_get_contents(__DIR__.'/../TestAssets/payload.json'));

        $commitAdapter = new CommitAdapter($json->head_commit);
        $commit = $commitAdapter->parse();

        $this->assertSame(array("composer.json"), $commit->getModified());
        $this->assertSame(array(), $commit->getAdded());
        $this->assertSame(array(), $commit->getRemoved());

        $author = new Author();
        $author->setName('ingo');
        $author->setUsername('iwalz');
        $author->setEmail('ingo.walz@googlemail.com');
        $this->assertEquals($author, $commit->getAuthor());

        $committer = new Committer();
        $committer->setName('ingo');
        $committer->setUsername('iwalz');
        $committer->setEmail('ingo.walz@googlemail.com');
        $this->assertEquals($committer, $commit->getCommitter());

        $this->assertSame("2013-01-04T16:30:15-08:00", $commit->getTimestamp());
        $this->assertSame(
            "https://github.com/iwalz/zf2-doctrine2-getting-started/commit/6346230ec7a1ecec861f7884070af5a37d3b72d9",
            $commit->getUrl()
        );
        $this->assertSame("6346230ec7a1ecec861f7884070af5a37d3b72d9", $commit->getId());
        $this->assertTrue($commit->getDistinct());
        $this->assertSame("Added doctrine orm and common include_path (Closed #1)", $commit->getMessage());

    }

    public function testPayload()
    {
        $payloadAdapter = new PayloadAdapter(file_get_contents(__DIR__.'/../TestAssets/payload.json'));
        $payload = $payloadAdapter->parse();

        $this->assertSame('none', $payload->getPusher()->getName());
        $this->assertSame('zf2-doctrine2-getting-started', $payload->getRepository()->getName());
        $this->assertSame(168, $payload->getRepository()->getSize());
        $this->assertSame('2012-08-12T06:26:17-07:00', $payload->getRepository()->getCreatedAt());
        $this->assertTrue($payload->getRepository()->getHasWiki());
        $this->assertFalse($payload->getRepository()->getPrivate());
        $this->assertSame(7, $payload->getRepository()->getWatchers());
        $this->assertFalse($payload->getRepository()->getFork());
        $this->assertSame('git://github.com/iwalz/zf2-doctrine2-getting-started', $payload->getRepository()->getUrl());
        $this->assertSame('PHP', $payload->getRepository()->getLanguage());
        $this->assertSame(5388613, $payload->getRepository()->getId());
        $this->assertSame('2013-01-04T16:30:30-08:00', $payload->getRepository()->getPushedAt());
        $this->assertTrue($payload->getRepository()->getHasDownloads());
        $this->assertSame(0, $payload->getRepository()->getOpenIssues());
        $this->assertTrue($payload->getRepository()->getHasIssues());
        $this->assertSame(2, $payload->getRepository()->getForks());
        $this->assertSame('This project combines the ZF2 SkeletonApplication and the doctrine2 getting started project', $payload->getRepository()->getDescription());
        $this->assertSame(7, $payload->getRepository()->getStargazers());

        $owner = new Owner();
        $owner->setName('iwalz');
        $owner->setEmail('ingo.walz@googlemail.com');
        $this->assertEquals($owner, $payload->getRepository()->getOwner());

        $this->assertSame('6346230ec7a1ecec861f7884070af5a37d3b72d9', $payload->getAfter());
        $this->assertFalse($payload->getDeleted());
        $this->assertSame('refs/heads/master', $payload->getRef());
        $this->assertSame('https://github.com/iwalz/zf2-doctrine2-getting-started/compare/e00126fbef13...6346230ec7a1', $payload->getCompare());
        $this->assertSame('e00126fbef13b3a30e86f7ad8811af95050d986a', $payload->getBefore());
        $this->assertFalse($payload->getCreated());
    }

    public function testGitClone()
    {
        $adapter = new PayloadAdapter(file_get_contents(__DIR__.'/../TestAssets/payload.json'));
        $payload = $adapter->parse();
        $github = new Github($payload);
        $github->cloneRepository();

        $this->assertTrue(is_dir($github->getProjectDirectory()));
        $zdpack = new \ZendService\ZendServerAPI\Zdpack();
        $zdpack->deleteFolder($github->getProjectDirectory());
    }

    public function testChdirs()
    {
        $adapter = new PayloadAdapter(file_get_contents(__DIR__.'/../TestAssets/payload.json'));
        $payload = $adapter->parse();
        $current_dir = getcwd();
        $github = new Github($payload);
        $new_dir = getcwd();

        $this->assertEquals($new_dir, sys_get_temp_dir());
        unset($github);
        $this->assertEquals($current_dir, getcwd());
    }
}
