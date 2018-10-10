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
            return url()->asset($path) . '?v=' . uniqid('');
        }

        $space = \NicolJamie\Spaces\Space::boot();

        return $space->config['endPoint'] . '/' .  env('APP_ENV') . '/' . $path;
    }

    /**
     * js
     * Renders JS onto page
     * @throws \Exception
     */
    public static function js()
    {
        $js = config('transmit.jsMinify');
        $index = array_keys($js);

        if (in_array(env('APP_ENV'), ['local', 'staging'])) {
            self::jsPath($index[0], $js[$index[0]]);
        }

        self::jsPath($index[0]);
    }

    /**
     * jsPath
     * @param $path
     * @param array $include
     *
     * @return string
     * @throws \Exception
     */
    public static function jsPath($path, $includes = [])
    {
        if (in_array(env('APP_ENV'), ['local', 'staging'])) {
            echo self::renderJs(self::path('js/' . $path));

            foreach ($includes as $include) {
                echo self::renderJs(self::path('js/' . $include));
            }
        }

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

    /**
     * renderJs
     * @param string $path
     *
     * @return string
     */
    private static function renderJs($path = '')
    {
        return "<script src='{$path}'></script>";
    }
}