<?php declare(strict_types=1);

namespace Surda\WebLoader;

use Surda\WebLoader\Filters\CssUrlFilter;
use Surda\WebLoader\Filters\CssMinFilter;
use Surda\WebLoader\Filters\JsMinFilter;
use WebLoader\Engine;
use WebLoader\Exception as WebLoaderException;

trait InjectWebLoader
{
    /** @var Engine */
    private $webLoaderEngine;

    public function injectWebLoaderEngine(Engine $webLoader): void
    {
        $this->webLoaderEngine = $webLoader;
        $documentRoot = $this->webLoaderEngine->getCompiler()->getDocumentRoot();

        try {
            $this->webLoaderEngine->addCssFilter('cssUrlFilter', function (string $code, string $file) use ($documentRoot) {
                $filter = new CssUrlFilter($documentRoot);

                return $filter($code, $file);
            }, TRUE);
        }
        catch (WebLoaderException $e) {

        }

        try {
            $this->webLoaderEngine->addCssFilter('cssMinFilter', function (string $code, string $file) {
                $filter = new CssMinFilter();

                return $filter($code, $file);
            }, TRUE);
        }
        catch (WebLoaderException $e) {

        }

        try {
            $this->webLoaderEngine->addJsFilter('jsMinFilter', function (string $code, string $file) {
                $filter = new JsMinFilter();

                return $filter($code, $file);
            }, TRUE);
        }
        catch (WebLoaderException $e) {

        }

        $this->onStartup[] = function () {
            $this->template->webloaderFilesCollectionRender = $this->webLoaderEngine->getFilesCollectionRender();
            $this->template->webloaderFilesCollectionsContainerRender = $this->webLoaderEngine->getFilesCollectionsContainerRender();
        };
    }
}
