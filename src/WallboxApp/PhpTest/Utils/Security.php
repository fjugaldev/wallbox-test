<?php


namespace WallboxApp\PhpTest\Utils;

/**
 * Class Security
 * @package WallboxApp\PhpTest\Utils
 */
class Security
{
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var null
     */
    protected $token = null;

    /**
     * Security constructor.
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->setHeaders($headers);
        $this->setToken(
            isset($this->getHeaders()['Authorization']) ? $this->getHeaders()['Authorization'] : null);
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * Returns if the Authorization header is present on header request.
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAuthorizationHeader(): bool
    {
        try {
            return array_key_exists('Authorization', $this->getHeaders());

        } catch (\Exception $e) {
            throw new \Exception(
                "An error has occurred trying to validate Authorization header" . $e->getMessage());
        }
    }

    /**
     * Validates if the passed token is valid.
     *
     * @return bool
     * @throws \Exception
     */
    public function isValidToken(): bool
    {
        try {
            // Validates if token exists
            if (is_null($this->getToken())) return false;

            // Generate a human date based on unix timestamp
            $dateToken = new \DateTime();
            $timestamp = $this->decodeToken();
            if (!$this->isTimestamp($timestamp)) return false;
            $dateToken->setTimestamp($this->decodeToken());

            // Return true or false if the token meets validation requirements.
            return date_diff($dateToken, new \DateTime())->format('%h') < 1;

        } catch (\Exception $e) {
            throw new \Exception("The token is not valid or is fake. Error: " . $e->getMessage());
        }
    }

    /**
     * Decode the passed token.
     *
     * @return string
     * @throws \Exception
     */
    protected function decodeToken(): string
    {
        try {
            return base64_decode($this->getToken());

        } catch (\Exception $e) {
            throw new \Exception("An error has occurred trying to decode the token" . $e->getMessage());
        }
    }

    /**
     * Validates if value is a timestamp value.
     *
     * @param  string $timestamp
     * @return bool
     * @throws \Exception
     */
    protected function isTimestamp($timestamp): bool
    {
        try {
            $check = (is_int($timestamp) OR is_float($timestamp))
                ? $timestamp
                : (string) (int) $timestamp;
            return  ($check === $timestamp)
                AND ( (int) $timestamp <=  PHP_INT_MAX)
                AND ( (int) $timestamp >= ~PHP_INT_MAX);

        } catch (\Exception $e) {
            throw new \Exception(
                "An error has occurred trying to validate if decoded token is a timestamp value.");
        }
    }
}
