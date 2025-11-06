<?php

namespace App\Exceptions;

class InsufficientFundsError extends \Exception
{
    public function __construct(string $message = 'Insufficient funds for this withdrawal', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
