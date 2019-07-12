<?php declare(strict_types=1);

namespace Surda\WebLoader\Filters;

use InvalidArgumentException;
use Surda\WebLoader\Utils\Utils;

/**
 * @author https://github.com/janmarek/WebLoader
 */
class CssUrlFilter
{
    /** @var string */
    private $docRoot;

    /** @var string */
    private $basePath;

    /**
     * @param string $docRoot  web document root
     * @param string $basePath base path
     * @throws InvalidArgumentException
     */
    public function __construct(string $docRoot, string $basePath = '/')
    {
        $this->docRoot = Utils::normalizePath($docRoot);

        if (!is_dir($this->docRoot)) {
            throw new InvalidArgumentException('Given document root is not directory.');
        }

        $this->basePath = $basePath;
    }


    /**
     * Make relative url absolute
     *
     * @param string $url     image url
     * @param string $quote   single or double quote
     * @param string $cssFile absolute css file path
     * @return string
     */
    private function absolutizeUrl(string $url, string $quote, string $cssFile): string
    {
        // is already absolute
        if (preg_match('/^([a-z]+:\/)?\//', $url) !== 0) {
            return $url;
        }

        $cssFile = Utils::normalizePath($cssFile);

        // inside document root
        if (strncmp($cssFile, $this->docRoot, strlen($this->docRoot)) === 0) {
            $path = $this->basePath . substr(dirname($cssFile), strlen($this->docRoot)) . DIRECTORY_SEPARATOR . $url;
        } else {
            // outside document root we don't know
            return $url;
        }

        $path = $this->cannonicalizePath($path);

        return $quote === '"' ? addslashes($path) : $path;
    }

    /**
     * @param string $path
     * @return string path
     */
    private function cannonicalizePath(string $path): string
    {
        $path = strtr($path, DIRECTORY_SEPARATOR, '/');
        $pathArr = array();
        foreach (explode('/', $path) as $i => $name) {
            if ($name === '.' || ($name === '' && $i > 0)) continue;
            if ($name === '..') {
                array_pop($pathArr);
                continue;
            }
            $pathArr[] = $name;
        }

        return implode('/', $pathArr);
    }


    /**
     * @param string $code
     * @param string $file
     * @return string
     */
    public function __invoke(string $code, string $file = ''): string
    {
        $regexp = '~
			(?<![a-z])
			url\(                                     ## url(
				\s*                                   ##   optional whitespace
				([\'"])?                              ##   optional single/double quote
				(?!data:)                             ##   keep data URIs
				(   (?: (?:\\\\.)+                    ##     escape sequences
					|   [^\'"\\\\,()\s]+              ##     safe characters
					|   (?(1)   (?!\1)[\'"\\\\,() \t] ##       allowed special characters
						|       ^                     ##       (none, if not quoted)
						)
					)*                                ##     (greedy match)
				)
				(?(1)\1)                              ##   optional single/double quote
				\s*                                   ##   optional whitespace
			\)                                        ## )
		~xs';
        $self = $this;

        return (string) preg_replace_callback($regexp, function ($matches) use ($self, $file) {
            return "url('" . $self->absolutizeUrl($matches[2], $matches[1], $file) . "')";
        }, $code);
    }
}
