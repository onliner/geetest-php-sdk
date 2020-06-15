<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Contract;

/**
 * Interface Request
 */
interface Request
{
    /**
     * @return string the HTTP method.
     */
    public function method(): string;

    /**
     * @return string the API endpoint url.
     */
    public function endpoint(): string;

    /**
     * @return array<mixed> the request data.
     */
    public function data(): array;
}
