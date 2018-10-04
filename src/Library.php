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
     * purge
     * @return bool
     */
    public function purge()
    {
        if (is_dir(public_path('upload'))) {
            return $this->fileSystem->deleteDirectory(public_path('upload'));
        }
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

        if (mkdir(public_path('upload'), 0777) === false) {
            throw new \Exception('Upload directory could not be created');
        }

        foreach ($toCompile as $key => $value) {
            $this->fileSystem->copyDirectory($value, public_path('upload'));
        }

        return storage_path('upload');
    }
}