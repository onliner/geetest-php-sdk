<?php

declare(strict_types=1);

namespace Onliner\GeeTest;

use Onliner\GeeTest\Contract\Transport;
use Onliner\GeeTest\Request\RegisterChallenge;
use Onliner\GeeTest\Request\ValidateCaptcha;
use Onliner\GeeTest\Transport\CurlTransport;
use Onliner\GeeTest\Transport\StreamTransport;

/**
 * GeeTest captcha SDK.
 *
 * @see https://www.geetest.com/en
 */
class GeeTest
{
    /**
     * @const string the GeeTest API url.
     */
    const API_URL = 'https://api.geetest.com';
    /**
     * @const string the version number of the GeeTest PHP SDK.
     */
    const VERSION = '3.2.0';
    /**
     * @const string the value that is used as an additional input
     * to a one-way function that hashes captcha challenge.
     */
    const CHALLENGE_SALT = 'geetest';

    /**
     * @var string the captcha ID.
     */
    private $captchaId;
    /**
     * @var string the captcha private key.
     */
    private $captchaKey;
    /**
     * @var int describing the number of seconds to wait
     * while trying to connect to a GeeTest server.
     */
    private $timeout;
    /**
     * @var Transport the HTTP message transport.
     */
    private $transport;

    /**
     * Initialize a new GeeTest client.
     *
     * @param string $captchaId the captcha ID.
     * @param string $captchaKey the captcha private key.
     * @param int $timeout the HTTP connection timeout.
     */
    public function __construct(string $captchaId, string $captchaKey, int $timeout = 1)
    {
        $this->captchaId = $captchaId;
        $this->captchaKey = $captchaKey;
        $this->timeout = $timeout;
    }

    /**
     * Sets the HTTP message transport.
     *
     * @param Transport $transport the HTTP transport instance.
     *
     * @return self
     */
    public function setTransport(Transport $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Returns the HTTP message transport instance.
     *
     * @return Transport
     */
    public function getTransport(): Transport
    {
        if ($this->transport === null) {
            $this->transport = $this->getDefaultTransport();
        }

        return $this->transport;
    }

    /**
     * Registers captcha and return config for captcha client.
     *
     * @param string|null $userId the user ID. You can preprocess (e.g. hashed) it.
     * @param string|null $ipAddress the client IP address.
     *
     * @return RegisterResponse
     */
    public function register(string $userId = null, string $ipAddress = null): RegisterResponse
    {
        $request = new RegisterChallenge($this->captchaId, $userId, $ipAddress);
        $response = $this->getTransport()->send($request);

        return new RegisterResponse($this->captchaId, $this->captchaKey, $response['challenge'] ?? '');
    }

    /**
     * Validates captcha params.
     *
     * @param string $challenge
     * @param string $validate
     * @param string $seccode
     * @param bool $online
     *
     * @return bool whether the captcha has been passed.
     */
    public function validate(string $challenge, string $validate, string $seccode, bool $online): bool
    {
        if ($online) {
            return $this->successValidate($challenge, $validate, $seccode);
        } else {
            return $this->failValidate($challenge, $validate);
        }
    }

    /**
     * Validates captcha on server side.
     * This is normal mode of captcha verification.
     *
     * @param string $challenge
     * @param string $validate
     * @param string $seccode
     *
     * @return bool whether the validate is successful.
     */
    private function successValidate(string $challenge, string $validate, string $seccode): bool
    {
        if (!$this->checkValidate($challenge, $validate)) {
            return false;
        }

        $request = new ValidateCaptcha($this->captchaId, $challenge, $seccode);
        $response = $this->getTransport()->send($request);

        return md5($seccode) === ($response['seccode'] ?? '');
    }

    /**
     * Validates captcha locally.
     * This is fallback mode of captcha verification.
     *
     * @param string $challenge
     * @param string $validate
     *
     * @return bool whether the validate is successful.
     */
    private function failValidate(string $challenge, string $validate): bool
    {
        return md5($challenge) === $validate;
    }

    /**
     * Checks that the given validate value is correct.
     * This method should prevent send knowingly wrong values to a GeeTest server.
     *
     * @param string $challenge the captcha request ID.
     * @param string $validate the value to check.
     *
     * @return bool whether the validate value is correct.
     */
    private function checkValidate(string $challenge, string $validate): bool
    {
        return md5($this->captchaKey . self::CHALLENGE_SALT . $challenge) === $validate;
    }

    /**
     * Returns default HTTP transport.
     *
     * @return Transport
     */
    private function getDefaultTransport(): Transport
    {
        if (function_exists('curl_exec')) {
            return new CurlTransport($this->timeout);
        } else {
            return new StreamTransport($this->timeout);
        }
    }
}
