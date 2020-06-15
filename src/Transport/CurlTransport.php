<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Transport;

use Onliner\GeeTest\Contract\Request;
use Onliner\GeeTest\Contract\Transport;

/**
 * CurlTransport sends HTTP messages using cURL.
 *
 * @see http://php.net/manual/en/book.curl.php
 */
class CurlTransport implements Transport
{
    /**
     * @var int the maximum number of seconds to allow request to be executed.
     */
    private $timeout;

    /**
     * Initialize a new CurlTransport instance.
     *
     * @param int $timeout the HTTP request timeout.
     */
    public function __construct(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @inheritDoc
     */
    public function send(Request $request): ?array
    {
        /** @var resource $conn */
        $conn = curl_init($request->endpoint());

        $options = $this->curlOptions($request->method(), $request->data());

        curl_setopt_array($conn, $options);

        $response = curl_exec($conn);
        curl_close($conn);

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $method the HTTP method.
     * @param array<mixed> $data the request data.
     *
     * @return array<int, mixed> the list of cURL options.
     */
    private function curlOptions(string $method, array $data): array
    {
        return [
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Content-type: application/x-www-form-urlencoded',
            ],
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FAILONERROR => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => false,
        ];
    }
}
