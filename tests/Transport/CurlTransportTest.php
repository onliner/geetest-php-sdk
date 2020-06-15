<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Tests\Transport;

use Onliner\GeeTest\Tests\TestCase;
use Onliner\GeeTest\Transport\CurlTransport;

class CurlTransportTest extends TestCase
{
    public function testCreate(): void
    {
        $transport = new CurlTransport(1);

        $this->assertInstanceOf('Onliner\GeeTest\Transport\CurlTransport', $transport);
    }

    public function testCurlOptions(): void
    {
        $transport = new CurlTransport(1);

        $expectedOptions = [
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'foo=1&bar=2',
            CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 1,
            CURLOPT_FAILONERROR => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
        ];
        $actualOptions = $this->invoke($transport, 'curlOptions', ['POST', ['foo' => 1, 'bar' => 2]]);

        $this->assertSame($expectedOptions, $actualOptions);
    }
}
