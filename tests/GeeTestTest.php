<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests;

use Onliner\GeeTest\Contract\Transport;
use Onliner\GeeTest\GeeTest;
use Onliner\GeeTest\Transport\CurlTransport;
use Onliner\GeeTest\Transport\StreamTransport;

class GeeTestTest extends TestCase
{
    public function testCreate(): void
    {
        $geetest = $this->getGeeTest();

        $this->assertInstanceOf('Onliner\GeeTest\GeeTest', $geetest);

        $this->assertSame('https://api.geetest.com', GeeTest::API_URL);
        $this->assertSame('3.2.0', GeeTest::VERSION);
    }

    public function testGetSetTransport(): void
    {
        $geetest = $this->getGeeTest();

        $this->assertInstanceOf('Onliner\GeeTest\Contract\Transport', $geetest->getTransport());

        $geetest->setTransport(new CurlTransport(1));
        $this->assertInstanceOf('Onliner\GeeTest\Transport\CurlTransport', $geetest->getTransport());

        $geetest->setTransport(new StreamTransport(1));
        $this->assertInstanceOf('Onliner\GeeTest\Transport\StreamTransport', $geetest->getTransport());
    }

    public function testSuccessRegister(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn([
            'challenge' => '8c7bf8d5320ca445872a7698f65589e4',
        ]);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $register = $geetest->register();

        $this->assertInstanceOf('Onliner\GeeTest\RegisterResponse', $register);
        $this->assertSame($register->success(), true);
    }

    public function testFailRegister(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn(null);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $register = $geetest->register();

        $this->assertInstanceOf('Onliner\GeeTest\RegisterResponse', $register);
        $this->assertSame($register->success(), false);
    }

    public function testSuccessCheckValidate(): void
    {
        $geetest = $this->getGeeTest();

        $challenge = '1a76d9da01e21b0178824dbed1425ecaiy';
        $validate = '40120ae87d4f96acf3cf6e2cda389288';
        $result = $this->invoke($geetest, 'checkValidate', [$challenge, $validate]);
        $this->assertTrue($result);
    }

    public function testFailCheckValidate(): void
    {
        $geetest = $this->getGeeTest();

        $result = $this->invoke($geetest, 'checkValidate', ['challenge', 'validate']);
        $this->assertFalse($result);
    }

    public function testSuccessOnlineValidate(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn([
            'seccode' => '486120b2511183671911df47bc7c8ee3',
        ]);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $challenge = '1a76d9da01e21b0178824dbed1425ecaiy';
        $validate = '40120ae87d4f96acf3cf6e2cda389288';
        $seccode = '727f1edd0455aeff265f9903d7ac097a|jordan';

        $check = $geetest->validate($challenge, $validate, $seccode, true);

        $this->assertTrue($check);
    }

    public function testFailOnlineValidate(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn(null);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $challenge = '1a76d9da01e21b0178824dbed1425ecaiy';
        $validate = '40120ae87d4f96acf3cf6e2cda389288';
        $seccode = '727f1edd0455aeff265f9903d7ac097a|jordan';

        $check = $geetest->validate($challenge, $validate, $seccode, true);

        $this->assertFalse($check);
    }

    public function testSuccessOfflineValidate(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn([
            'seccode' => 'af3ccef54fa323a4d26ecc1584a18d29',
        ]);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $challenge = '33e75ff09dd601bbe69f351039152189cf';
        $validate = 'a156214ca371e64ac6a3bdd3127c6687';
        $seccode = 'a156214ca371e64ac6a3bdd3127c6687|jordan';

        $check = $geetest->validate($challenge, $validate, $seccode, false);

        $this->assertTrue($check);
    }

    public function testFailOfflineValidate(): void
    {
        $transport = $this->getMockBuilder(Transport::class)->getMock();
        $transport->method('send')->willReturn(null);

        $geetest = $this->getGeeTest();
        $geetest->setTransport($transport);

        $challenge = '1a76d9da01e21b0178824dbed1425ecaiy';
        $validate = md5('fail-validate-key');
        $seccode = '727f1edd0455aeff265f9903d7ac097a|jordan';

        $check = $geetest->validate($challenge, $validate, $seccode, true);

        $this->assertFalse($check);
    }

    private function getGeeTest(): GeeTest
    {
        return new GeeTest('captcha-id', 'captcha-key');
    }
}
