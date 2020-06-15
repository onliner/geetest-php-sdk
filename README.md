GeeTest PHP SDK
---------------

This is a PHP library that wraps up the server-side verification step required
to process responses from the [GeeTest](https://www.geetest.com) service. 

[![Version][version-badge]][version-link]
[![Total Downloads][downloads-badge]][downloads-link]
[![Php][php-badge]][php-link]
[![License][license-badge]](LICENSE)
[![Build Status][build-badge]][build-link]

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require onliner/geetest-php-sdk:^1.0
```

or add this code line to the `require` section of your `composer.json` file:

```
"onliner/geetest-php-sdk": "^1.0"
```

Usage
-----

### Step 1. Register captcha request.

```php
const ID = '58f984fb3d1f7f4732a74b3cda273eed';
const KEY = '58f984fb3d1f7f4732a74b3cda273eed';

$geetest = new GeeTest(ID, KEY);
$register = $geetest->register()->toArray();
```

### Step 2. Normal mode of captcha validation.

```php
$geetest = new GeeTest(ID, KEY);
$geetest->validate($challenge, $validate, $seccode, true);
```

### Step 2. Fallback mode of captcha validation.

```php
$geetest = new GeeTest(ID, KEY);
$geetest->validate($challenge, $validate, $seccode, false);
```

License
-------

Released under the [MIT license](LICENSE).


[version-badge]:    https://img.shields.io/packagist/v/onliner/geetest-php-sdk.svg
[version-link]:     https://packagist.org/packages/onliner/geetest-php-sdk
[downloads-link]:   https://packagist.org/packages/onliner/geetest-php-sdk
[downloads-badge]:  https://poser.pugx.org/onliner/geetest-php-sdk/downloads.png
[php-badge]:        https://img.shields.io/badge/php-7.2+-brightgreen.svg
[php-link]:         https://www.php.net/
[license-badge]:    https://img.shields.io/badge/license-MIT-brightgreen.svg
[build-link]:       https://github.com/onliner/geetest-php-sdk/actions?workflow=test
[build-badge]:      https://github.com/onliner/geetest-php-sdk/workflows/test/badge.svg
