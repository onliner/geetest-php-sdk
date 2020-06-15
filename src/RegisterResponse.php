<?php

declare(strict_types=1);

namespace Onliner\GeeTest;

/**
 * Class RegisterResponse
 */
class RegisterResponse
{
    /**
     * @var string the captcha ID.
     */
    private $captchaId;
    /**
     * @var bool whether GeeTest server is available.
     */
    private $success;
    /**
     * @var string the hashed ID of captcha request,
     * generated when server SDK sends a request to GeeTest server.
     */
    private $challenge;

    /**
     * Initialize a new RegisterResponse instance.
     *
     * @param string $captchaId the captcha ID.
     * @param string $captchaKey the captcha private key.
     * @param string $challenge the ID of captcha request.
     */
    public function __construct(string $captchaId, string $captchaKey, string $challenge)
    {
        $this->captchaId = $captchaId;

        if (strlen($challenge) === 32) {
            $this->success = true;
            $this->challenge = md5($challenge . $captchaKey);
        } else {
            $this->success = false;
            $this->challenge = $this->makeChallenge();
        }
    }

    /**
     * @return bool the success value.
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * @return string the challenge value.
     */
    public function challenge(): string
    {
        return $this->challenge;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'gt' => $this->captchaId,
            'challenge' => $this->challenge,
            'new_captcha' => true,
        ];
    }

    /**
     * @return string the random challenge.
     */
    private function makeChallenge(): string
    {
        $rand = function () {
            return md5(strval(mt_rand(0, 100)));
        };

        return $rand() . substr($rand(), 0, 2);
    }
}
