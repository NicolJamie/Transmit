<?php

namespace NicolJamie\Transmit;

class Help
{
    /**
     * path
     * Full path of the CDN
     * @param $path
     *
     * @return string
     * @throws \Exception
     */
    public static function path($path)
    {
        if (env('APP_ENV') === 'local') {
            return url()->asset($path);
        }

        $space = \NicolJamie\Spaces\Space::boot();

        return $space->config['endPoint'] . '/' .  env('APP_ENV') . '/' . $path;
    }

    /**
     * jsPath
     * @param $path
     *
     * @return mixed
     */
    public static function jsPath($path)
    {
        return self::path('js/' . $path);
    }

    /**
     * imagePath
     * @param $path
     *
     * @return string
     * @throws \Exception
     */
    public static function imagePath($path)
    {
        return self::path('image/' . $path);
    }

    /**
     * cssPath
     * @param $path
     *
     * @return string
     * @throws \Exception
     */
    public static function cssPath($path)
    {
        return self::path('css/' . $path);
    }
}