<?php
namespace WalyDevcloudHookTest;

use WalyDevcloudHook\Hook\Github\Adapter\CommitAdapter;

class GithubTest extends \PHPUnit_Framework_TestCase
{
    public function testGithubCommitAdaption()
    {
        $data = new \stdClass();
        $data->modified = array("composer.json");
        $data->added = array();
        $data->author = new \stdClass();
        $data->author->name = "ingo";
        $data->author->username = "iwalz";
        $data->author->email = "ingo.walz@googlemail.com";
        $data->timestamp = "2013-01-04T16:30:15-08:00";
        $data->removed = array();
        $data->url = "https://github.com/iwalz/zf2-doctrine2-getting-started/commit/6346230ec7a1ecec861f7884070af5a37d3b72d9";
        $data->id= "6346230ec7a1ecec861f7884070af5a37d3b72d9";
        $data->distinct = true;
        $data->message = "Added doctrine orm and common include_path (Closed #1)";
        $data->committer = new \stdClass();
        $data->committer->name = "ingo";
        $data->committer->username = "iwalz";
        $data->committer->email= "ingo.walz@googlemail.com";

        $commitAdapter = new CommitAdapter($data);
        $commit = $commitAdapter->parse();

        $this->assertSame(array("composer.json"), $commit->getModified());
        $this->assertSame(array(), $commit->getAdded());
        $this->assertSame(array(), $commit->getRemoved());

        $author = new \WalyDevcloudHook\Hook\Github\Payload\Author();
        $author->setName('ingo');
        $author->setUsername('iwalz');
        $author->setEmail('ingo.walz@googlemail.com');
        $this->assertEquals($author, $commit->getAuthor());

        $committer = new \WalyDevcloudHook\Hook\Github\Payload\Committer();
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
}
