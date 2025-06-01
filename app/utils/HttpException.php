<?php
namespace Giftia\utils;

class HttpException extends \Exception
{
    protected int $statusCode;

    public function __construct(string $message, int $statusCode = 500, \Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, 0, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
