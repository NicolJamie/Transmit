<?php

namespace NicolJamie\Transmit;

use League\Flysystem\File;
use League\Flysystem\Filesystem;

class Library
{
    /**
     * Boot constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->config = config('transmit');

        $this->fileSystem = new \Illuminate\Filesystem\Filesystem;
    }

    /**
     * purge
     * @param null $folder
     *
     * @return bool
     */
    public function purge($folder = null)
    {
        if (is_dir(
            public_path(is_null($folder) ? 'compile' : $folder))
        ) {
            return $this->fileSystem->deleteDirectory(
                public_path(is_null($folder) ? 'compile' : $folder)
            );
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
        if (mkdir(
            public_path(is_null($folder) ? 'compile' : $folder), 0777) === false
        ) {
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
    public function compile($folder = null)
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

            $this->fileSystem->copyDirectory($value , public_path(
                is_null($folder) ? 'compile' : $folder) . '/' . $end
            );
        }

        return $directory;
    }

    /**
     * unpack
     * @return bool
     */
    public function unpack()
    {
        foreach ($this->config['directories'] as $key => $value) {
            $explode = explode('/', $value);
            $end = end($explode);

            $this->fileSystem->copyDirectory(
                public_path('compile/' . $end) , public_path('/' . $end)
            );
        }

        return true;
    }

    /**
     * mainJs
     * @return array
     */
    public static function mainJs()
    {
        $js = config('transmit.jsMinify');
        $index = array_keys($js);

        return $index;
    }
}