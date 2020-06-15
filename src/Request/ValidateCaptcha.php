<?php

declare(strict_types=1);

namespace Onliner\GeeTest\Request;

use Onliner\GeeTest\Contract\Request;
use Onliner\GeeTest\GeeTest;

/**
 * Class ValidateCaptcha
 */
class ValidateCaptcha implements Request
{
    /**
     * @var string the captcha ID.
     */
    private $captchaId;
    /**
     * @var string the hashed ID of captcha request.
     */
    private $challenge;
    /**
     * @var string the secret code.
     */
    private $seccode;

    /**
     * Initialize a new ValidateCaptcha request.
     *
     * @param string $captchaId
     * @param string $challenge
     * @param string $seccode
     */
    public function __construct(string $captchaId, string $challenge, string $seccode)
    {
        $this->captchaId = $captchaId;
        $this->challenge = $challenge;
        $this->seccode = $seccode;
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
        return GeeTest::API_URL . '/validate.php';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'captchaid' => $this->captchaId,
            'challenge' => $this->challenge,
            'seccode' => $this->seccode,
            'timestamp' => time(),
            'json_format' => 1,
            'sdk' => sprintf('php_%s', GeeTest::VERSION),
        ];
    }
}
