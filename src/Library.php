<?php

namespace NicolJamie\Transmit;

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
    }

    /**
     * push
     * Complile assets from resources
     * @throws \Exception
     */
    public function push($env = 'staging')
    {
        return $this->connection->directory([
            'pathToDirectory' => $this->compile(),
            'saveAs' => ''
        ], true);
    }

    public function purge()
    {
        //.. clear bucket
    }

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

        //.. get all files from config in public.
        //.. render into file
        //.. fetch assets to push and complile
    }
}