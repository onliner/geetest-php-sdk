<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Request;

use Onliner\GeeTest\Contract\Request;
use Onliner\GeeTest\GeeTest;

/**
 * Class RegisterChallenge
 */
class RegisterChallenge implements Request
{
    /**
     * @var string the captcha ID.
     */
    private $captchaId;
    /**
     * @var string|null the user ID.
     */
    private $userId;
    /**
     * @var string|null the client IP address.
     */
    private $ipAddress;

    /**
     * Initialize a new RegisterChallenge request.
     *
     * @param string $captchaId
     * @param string|null $userId
     * @param string|null $ipAddress
     */
    public function __construct(string $captchaId, ?string $userId, ?string $ipAddress)
    {
        $this->captchaId = $captchaId;
        $this->userId = $userId;
        $this->ipAddress = $ipAddress;
    }

    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function endpoint(): string
    {
        return GeeTest::API_URL . '/register.php';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        $data = [
            'gt' => $this->captchaId,
            'new_captcha' => 1,
            'json_format' => 1,
        ];

        if ($this->userId) {
            $data['user_id'] = $this->userId;
        }

        if ($this->ipAddress) {
            $data['ip_address'] = $this->ipAddress;
        }

        return $data;
    }
}
