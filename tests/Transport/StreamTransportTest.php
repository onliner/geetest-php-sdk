<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests\Transport;

use Onliner\GeeTest\Tests\TestCase;
use Onliner\GeeTest\Transport\StreamTransport;

class StreamTransportTest extends TestCase
{
    public function testCreate(): void
    {
        $transport = new StreamTransport(1);

        $this->assertInstanceOf('Onliner\GeeTest\Transport\StreamTransport', $transport);
    }

    public function testContextOptions(): void
    {
        $transport = new StreamTransport(1);

        $expectedOptions = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\nContent-Length: 11",
                'content' => 'foo=1&bar=2',
                'ignore_errors' => true,
                'timeout' => 1,
            ],
        ];
        $actualOptions = $this->invoke($transport, 'contextOptions', ['POST', ['foo' => 1, 'bar' => 2]]);

        $this->assertSame($expectedOptions, $actualOptions);
    }
}
