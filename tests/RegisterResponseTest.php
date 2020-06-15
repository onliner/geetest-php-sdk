<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests;

use Onliner\GeeTest\RegisterResponse;

class RegisterResponseTest extends TestCase
{
    public function testCreate(): void
    {
        $geetest = new RegisterResponse('captcha-id', 'captcha-key', 'f561aaf6ef0bf14d4208bb46a4ccb3ad');

        $this->assertInstanceOf('Onliner\GeeTest\RegisterResponse', $geetest);
        $this->assertSame($geetest->success(), true);
        $this->assertSame($geetest->challenge(), '8c7bf8d5320ca445872a7698f65589e4');

        $this->assertSame([
            'success' => true,
            'gt' => 'captcha-id',
            'challenge' => '8c7bf8d5320ca445872a7698f65589e4',
            'new_captcha' => true,
        ], $geetest->toArray());
    }
}
