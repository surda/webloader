<?php declare(strict_types=1);

namespace Surda\WebLoader\Utils;

class Utils
{
    /**
     * @param string $path
     * @return string
     * @author https://github.com/janmarek/WebLoader
     */
    public static function normalizePath(string $path): string
    {
        $path = strtr($path, '\\', '/');
        $root = (strpos($path, '/') === 0) ? '/' : '';
        $pieces = explode('/', trim($path, '/'));
        $res = array();
        foreach ($pieces as $piece) {
            if ($piece === '.' || $piece === '') {
                continue;
            }
            if ($piece === '..') {
                array_pop($res);
            } else {
                array_push($res, $piece);
            }
        }

        return $root . implode('/', $res);
    }
}