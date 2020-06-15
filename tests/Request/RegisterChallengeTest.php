<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests\Request;

use Onliner\GeeTest\Request\RegisterChallenge;
use Onliner\GeeTest\Tests\TestCase;

class RegisterChallengeTest extends TestCase
{
    public function testCreate(): void
    {
        $request = new RegisterChallenge('captcha-id', '12345', '127.0.0.1');

        $this->assertInstanceOf('Onliner\GeeTest\Request\RegisterChallenge', $request);

        $this->assertSame('POST', $request->method());
        $this->assertSame('https://api.geetest.com/register.php', $request->endpoint());
        $this->assertSame([
            'gt' => 'captcha-id',
            'new_captcha' => 1,
            'json_format' => 1,
            'user_id' => '12345',
            'ip_address' => '127.0.0.1',
        ], $request->data());
    }
}
