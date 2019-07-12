<?php declare(strict_types=1);

namespace Surda\WebLoader\Filters;

use JShrink\Minifier;

class JsMinFilter
{
    /**
     * @param string $code
     * @param string $file
     * @return string
     */
    public function __invoke(string $code, string $file = ''): string
    {
        if (strpos($file, '.min.') !== FALSE) {
            return $code;
        }

        return (string) Minifier::minify($code);
    }
}
