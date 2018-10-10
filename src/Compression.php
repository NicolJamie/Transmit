<?php

namespace NicolJamie\Transmit;

class Compression
{
    /**
     * config
     * @var \Illuminate\Config\Repository|mixed
     */
    public $config;

    /**
     * FileSystme
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $fileSystem;

    /**
     * js
     * Compiles JS into one file
     */
    public function js()
    {
        $main = Help::mainJs();
        $includes = config('transmit.jsMinify')[$main[0]];
        $jsPath = public_path('compile_production/js/');

        // .. create base temp file
        $temp = str_replace('.js', '', $main[0]) . '_tmp.js';

        // .. create temp js file
        touch($jsPath . $temp);

        // .. open resource
        $js = fopen($jsPath . $temp, 'w+');

        // .. put main file
        $this->put($js, $jsPath . $main[0]);

        // .. compile into one file
        foreach ($includes as $include) {
            $this->put($js, $jsPath . $include);
        }

        // .. close off file
        fclose($js);

        // .. remove old file and rename compiled
        unlink($jsPath . $main[0]);
        rename($jsPath . $temp, $jsPath . $main[0]);
    }

    /**
     * put
     * @param $to
     * @param $from
     */
    private function put($to, $from)
    {
        fwrite($to, file_get_contents($from));
    }
}