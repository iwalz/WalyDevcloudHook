<?php
namespace WalyDevcloudHook\Hook;

use WalyDevcloudHook\Hook,
    WalyDevcloudHook\Hook\Github\Payload;

class Github
{
    protected $payload = null;
    protected $directory = null;
    protected $currentDirectory = null;
    protected $projectDirectory = null;

    public function __construct(Payload $payload)
    {


        ob_start();
        $available = system('git --help');
        if (false === $available) {
            throw new \RuntimeException('Git needs to be in your $PATH');
        }
        ob_end_clean();

        $this->payload = $payload;
        $this->currentDirectory = getcwd();
        $this->setDirectory(sys_get_temp_dir());
    }

    public function cloneRepository()
    {
        $repositoryUrl = $this->payload->getRepository()->getUrl();
        $repository = str_replace('https://github.com/', '', $repositoryUrl);
        $cmd = 'git clone';
        $cmd .= ' --branch=' . $this->payload->getBranch();
        $cmd .= ' --depth=100 --quiet ' . $repositoryUrl;
        $cmd .= ' ' . $repository;

        $cmd = escapeshellcmd($cmd);

        $result = system($cmd);

        if (false !== $repository) {
            $this->projectDirectory = $this->directory . DIRECTORY_SEPARATOR . $repository;
        }

        return $result !== false ? true : false;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
        chdir($this->directory);
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }

    public function __destruct()
    {
        chdir($this->currentDirectory);
    }
}

