<?php
namespace WalyDevcloudHook\Hook;

use Gitonomy\Git\Admin;
use Gitonomy\Git\Repository;
use WalyDevcloudHook\Hook;
use WalyDevcloudHook\Hook\Github\Payload;
use ZendService\ZendServerAPI\Zdpack;

class Github
{
    /**
     * @var Payload
     */
    protected $payload = null;
    /**
     * @var string
     */
    protected $directory = null;
    /**
     * @var Repository
     */
    protected $repository = null;
    /**
     * @var Admin
     */
    protected $admin = null;
    /**
     * @var string
     */
    protected $projectDir = null;
    /**
     * @var array
     */
    protected $config = null;

    /**
     * @param Payload $payload   The payload
     * @param string $directory  The directory where to save the repo to
     */
    public function __construct(Payload $payload, $directory = null)
    {
        $this->payload = $payload;
        $this->setDirectory($directory ?: sys_get_temp_dir());
        $this->admin = new Admin();
    }

    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Clone repository to soecified directory
     */
    public function cloneRepository()
    {
        $targetDir = $this->getDirectory() . DIRECTORY_SEPARATOR . $this->payload->getRepository()->getName();

        if (is_dir($targetDir)) {
            $zdpack = new Zdpack();
            $zdpack->deleteFolder($targetDir);
        }

        $this->repository = $this->admin->cloneTo(
            $targetDir,
            $this->payload->getRepository()->getUrl(),
            false
        );
        $this->projectDir = $this->repository->getPath();
    }

    /**
     * Checkout the commit that is specified in the payload
     */
    public function checkoutCommit()
    {
        $this->repository->getCommit($this->payload->getHeadCommit()->getId());
    }

    /**
     * Set the directory
     *
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Set the deployment config
     *
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get the deployment config
     *
     * @return array
     */
    public function getConfig()
    {
        if ($this->config === null) {
            if (is_file($this->projectDir . '/deployment.json')) {
                $this->config = json_decode(file_get_contents($this->projectDir . '/deployment.json'));
            }
        }
        return $this->config;
    }

    /**
     * Get the directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param Admin $admin
     */
    public function setAdmin(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getProjectDirectory()
    {
        return $this->projectDir;
    }
}

