<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests\Request;

use Onliner\GeeTest\Request\ValidateCaptcha;
use Onliner\GeeTest\Tests\TestCase;

class ValidateCaptchaTest extends TestCase
{
    public function testCreate(): void
    {
        $request = new ValidateCaptcha('captcha-id', 'f561aaf6ef0bf14d4208bb46a4ccb3ad', 'code');

        $this->assertInstanceOf('Onliner\GeeTest\Request\ValidateCaptcha', $request);

        $this->assertSame('POST', $request->method());
        $this->assertSame('https://api.geetest.com/validate.php', $request->endpoint());
        $this->assertSame([
            'captchaid' => 'captcha-id',
            'challenge' => 'f561aaf6ef0bf14d4208bb46a4ccb3ad',
            'seccode' => 'code',
            'timestamp' => time(),
            'json_format' => 1,
            'sdk' => 'php_3.2.0',
        ], $request->data());
    }
}
