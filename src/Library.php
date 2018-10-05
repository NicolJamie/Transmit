<?php

namespace NicolJamie\Transmit;

use League\Flysystem\File;
use NicolJamie\Spaces\Space;
use League\Flysystem\Filesystem;

class Library
{
    /**
     * Boot constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connection = Space::boot();

        $this->config = config('transmit');

        $this->fileSystem = new \Illuminate\Filesystem\Filesystem;
    }

    /**
     * push
     * Complile assets from resources
     *
     * @param string $env
     *
     * @return bool|string
     */
    public function push($env = 'staging')
    {
        try {
            $this->connection->directory([
                'directory' => $this->compile(),
                'prefix' => $env
            ], true);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $this->purge();

        return true;
    }

    /**
     * fetch
     *
     * @param string $env
     *
     * @return bool|string
     */
    public function fetch($env = 'staging')
    {
        $directory = $this->directory();

        try {
            $this->connection->directory([
                'directory' => $directory,
                'prefix' => $env
            ], false);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $this->unpack();
        $this->purge();

        return true;
    }

    /**
     * purge
     * @return bool
     */
    public function purge()
    {
        if (is_dir(public_path('compile'))) {
            return $this->fileSystem->deleteDirectory(public_path('compile'));
        }
    }

    /**
     * directory
     * Creates the directory
     * @throws \Exception
     */
    public function directory()
    {
        if (mkdir(public_path('compile'), 0777) === false) {
            throw new \Exception('Upload directory could not be created');
        }

        return public_path('compile');
    }

    /**
     * compile
     * Compiles uploads into one folder
     * @return string
     * @throws \Exception
     */
    private function compile()
    {
        $toCompile = [];

        foreach ($this->config['directories'] as $key => $directory) {
            $path = public_path($directory);

            if (is_dir($path)) {
                $toCompile[] = $path;
            }
        }

        if (empty($toCompile)) {
            throw new \Exception('No directories to compile');
        }

        $this->purge();
        $directory = $this->directory();

        foreach ($toCompile as $key => $value) {
            $explode = explode('/', $value);
            $end = end($explode);

            $this->fileSystem->copyDirectory($value , public_path('compile') . '/' . $end);
        }

        return $directory;
    }

    /**
     * unpack
     * @return bool
     */
    private function unpack()
    {
        foreach ($this->config['directories'] as $key => $value) {
            $explode = explode('/', $value);
            $end = end($explode);

            $this->fileSystem->copyDirectory(public_path('compile/' . $end) , public_path('/' . $end));
        }

        return true;
    }
}