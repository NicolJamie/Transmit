<?php

namespace NicolJamie\Transmit;

use League\Flysystem\File;
use NicolJamie\Spaces\Space;
use League\Flysystem\Filesystem;

class Library extends Compression
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
     * @param string $env
     * @param bool $unpack
     *
     * @return bool|string
     * @throws \Exception
     */
    public function fetch($env = 'staging', $unpack = true)
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

        $unpack === false ?: $this->unpack();
        $this->purge();

        return true;
    }

    /**
     * deploy
     * Push assets from staging to production
     * @return bool|string
     * @throws \Exception
     */
    public function deploy()
    {
        //.. purge 'compile' and 'compile_production'
        $this->purge();
        $this->purge('compile_production');
        
        // .. fetch staging assets into 'compile'
        $this->fetch('staging', true);

        // .. compile into production folder
        $this->compile('compile_production');

        // .. compress JS
        $this->js();

//        try {
//            $this->connection->directory([
//                'directory' => $this->compile(),
//                'prefix' => 'production'
//            ], true);
//        } catch (\Exception $exception) {
//            return $exception->getMessage();
//        }

        // ..purge 'compile' and 'compile_production'
        $this->purge();
        $this->purge('compile_production');

        return true;
    }

    /**
     * purge
     * @param null $folder
     *
     * @return bool
     */
    public function purge($folder = null)
    {
        if (is_dir(public_path(is_null($folder) ? 'compile' : $folder))) {
            return $this->fileSystem->deleteDirectory(public_path(is_null($folder) ? 'compile' : $folder));
        }
    }

    /**
     * directory
     * Created the directory
     * @param null $folder
     *
     * @return string
     * @throws \Exception
     */
    public function directory($folder = null)
    {
        if (mkdir(public_path(is_null($folder) ? 'compile' : $folder), 0777) === false) {
            throw new \Exception('Upload directory could not be created');
        }

        return public_path(is_null($folder) ? 'compile' : $folder);
    }

    /**
     * compile
     * Compiles uploads into one folder
     * @return string
     * @throws \Exception
     */
    private function compile($folder = null)
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

            $this->fileSystem->copyDirectory($value , public_path(is_null($folder) ? 'compile' : $folder) . '/' . $end);
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