<?php

namespace App\Exceptions;

class UsernameUpdateException extends \Exception
{
    public function __construct(string $message = "Failed to update username", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
