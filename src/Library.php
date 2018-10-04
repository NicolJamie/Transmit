<?php

namespace NicolJamie\Transmit;

use League\Flysystem\File;
use League\Flysystem\Filesystem;
use NicolJamie\Spaces\Space;

class Library
{
    /**
     * Boot constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connection = Space::boot();

        $this->config = env('');

        $this->fileSystem = new \Illuminate\Filesystem\Filesystem;
    }

    /**
     * push
     * Complile assets from resources
     * @throws \Exception
     */
    public function push($env = 'staging')
    {
        try {
            $this->connection->directory([
                'pathToDirectory' => $this->compile(),
                'saveAs' => ''
            ], true);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $this->purge();
    }

    /**
     * purge
     * @return bool
     */
    public function purge()
    {
        return $this->fileSystem->deleteDirectory(storage_path('upload'));
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

        foreach ($this->config['directories'] as $directory) {
            $path = public_path($directory);

            if (is_dir($path)) {
                $toCompile[] = $path;
            }
        }

        if (!empty($toCompile)) {
            throw new \Exception('No directories to compile');
        }

        if (mkdir(public_path('upload'), 0777) === false) {
            throw new \Exception('Upload directory could not be created');
        }

        foreach ($toCompile as $value) {
            $this->fileSystem->copyDirectory(storage_path($value), storage_path('upload'));
        }

        return storage_path('upload');
    }
}