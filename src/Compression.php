<?php

namespace NicolJamie\Transmit;

use Illuminate\Support\Facades\Storage;

class Compression
{
    public function __construct()
    {
        $this->config = config('transmit');

        $this->fileSystem = new \Illuminate\Filesystem\Filesystem;
    }

    public function js($main = '', $includes = [])
    {
        // .. create base temp file
        $temp = 'js/' . str_replace('.js', '', $main) . '_tmp.js';

        touch(public_path('compiled_production/' . $temp));
    }
}