<?php

/**
 * path
 * Full path of the CDN EndPoint
 * @param $path
 *
 * @return string
 * @throws Exception
 */
function path($path)
{
    if (env('APP_ENV') === 'local') {
        return public_path();
    }

    $space = \NicolJamie\Spaces\Space::boot();

    return $space->config['endPoint'] . env('APP_ENV') . $path;
}


function jsPath()
{

}