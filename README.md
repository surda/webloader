# WebLoader

[![Build Status](https://travis-ci.org/surda/webloader.svg?branch=master)](https://travis-ci.org/surda/webloader)
[![Licence](https://img.shields.io/packagist/l/surda/webloader.svg?style=flat-square)](https://packagist.org/packages/surda/webloader)
[![Latest stable](https://img.shields.io/packagist/v/surda/webloader.svg?style=flat-square)](https://packagist.org/packages/surda/webloader)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

## Installation

The recommended way to is via Composer:

```
composer require surda/webloader
```

After that you have to register extension in config.neon:

```yaml
extensions:
    webloader: WebLoader\Bridges\Nette\WebLoaderExtension
```

## Configuration

See [Machy8/webloader](https://github.com/Machy8/webloader) repository.

## Usage

Presenter

```php
use Surda\WebLoader\TWebLoader;

abstract class AnyPresenter extends Presenter
{
    use TWebLoader;
}
```
