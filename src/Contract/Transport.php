<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Contract;

/**
 * Interface Transport
 */
interface Transport
{
    /**
     * Sends a request to the server and returns the response.
     *
     * @param Request $request the request to be sent.
     *
     * @return array<mixed>|null the response from the server.
     */
    public function send(Request $request): ?array;
}
