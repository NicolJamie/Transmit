<?php

namespace NicolJamie\Transmit;

use NicolJamie\Spaces\Space;

class Transmit extends Compression
{
    /**
     * Transmit constructor.
     */
    public function __construct()
    {
        $this->connection = Space::boot();

        $this->library = new Library();
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
                'directory' => $this->library->compile(),
                'prefix' => $env
            ], true);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $this->library->purge();

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
        $directory = $this->library->directory();

        try {
            $this->connection->directory([
                'directory' => $directory,
                'prefix' => $env
            ], false);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        $unpack === false ?: $this->library->unpack();
        $this->library->purge();

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
        $this->library->purge();
        $this->library->purge('compile_production');

        // .. fetch staging assets into 'compile'
        $this->fetch('staging', true);

        // .. compile into production folder
        $this->library->compile('compile_production');

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
        $this->library->purge();
        $this->library->purge('compile_production');

        return true;
    }
}