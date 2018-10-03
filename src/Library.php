<?php

namespace NicolJamie\LaravelCDN;

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
    }

    /**
     * push
     * Complile assets from resources
     * @throws \Exception
     */
    public function push()
    {
        $this->connection->directory(
            $this->compile(), true
        );
    }
    
    public function purge()
    {
        //.. clear bucket
    }
    
    private function compile()
    {
        //.. fetch assets to push and complile
    }
}