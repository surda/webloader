<?php declare(strict_types=1);

namespace Surda\WebLoader\Filters;

use CssMin;

class CssMinFilter
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

        return CssMin::minify($code, array("remove-last-semicolon"));
    }
}
