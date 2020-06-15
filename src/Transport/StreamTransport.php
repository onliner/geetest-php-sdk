<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Transport;

use Onliner\GeeTest\Contract\Request;
use Onliner\GeeTest\Contract\Transport;

/**
 * StreamTransport sends HTTP messages using streams.
 *
 * @see http://php.net/manual/en/book.stream.php
 */
class StreamTransport implements Transport
{
    /**
     * @var int the maximum number of seconds to allow request to be executed.
     */
    private $timeout;

    /**
     * Initialize a new StreamTransport instance.
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
        $contextOptions = $this->contextOptions($request->method(), $request->data());

        $context = stream_context_create($contextOptions);
        /** @var resource $stream */
        $stream = fopen($request->endpoint(), 'rb', false, $context);
        $response = stream_get_contents($stream);
        fclose($stream);

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $method the HTTP method.
     * @param array<mixed> $data the request data.
     *
     * @return array<mixed> the list of context options.
     */
    private function contextOptions(string $method, array $data): array
    {
        $content = http_build_query($data);
        $headers = [
            'Content-type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($content),
        ];

        return [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $headers),
                'content' => $content,
                'ignore_errors' => true,
                'timeout' => $this->timeout,
            ],
        ];
    }
}
