<?php

namespace NicolJamie\Transmit;

use NicolJamie\Spaces\Space;

class Transmit extends Library
{
    public function __construct()
    {
        parent::__construct();

        $this->connection = Space::boot();

        $this->purge();
    }

    /**
     * push
     *
     * @param string $env
     *
     * @return bool|\Exception
     */
    public function push($env = 'staging', $directory = null)
    {
        try {
            //.. compile and and upload directory
            return $this->connection->directory([
                'directory' => is_null($directory) ? $this->compile() : $directory,
                'prefix' => $env
            ]);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * fetch
     *
     * @param string $env
     *
     * @return bool|\Exception
     */
    public function fetch($env = 'staging')
    {
        try {
            //.. download directory
            $download = $this->connection->directory([
                'directory' => $this->directory(),
                'prefix' => $env,
            ], false);
        } catch (\Exception $exception) {
            return $exception;
        }

        //.. unpack the compiled directory
        $this->unpack();

        return $download;
    }

    /**
     * deploy
     *
     * @return bool
     * @throws \Exception
     */
    public function deploy()
    {
        //.. get staging files
        $this->fetch();
        //.. compile everything for production
        $directory = $this->compile('compile_production');

        //.. compress js code
        $this->minifyJs();

        //.. push assets to production
        $this->push('production', $directory);

        //.. purge compile
        $this->purge('compile_production');

        return true;
    }

    public function __destruct()
    {
        $this->purge();
    }
}
