<?php

namespace App\Domain\Election\Exception;

final class InvalidElectionStateException extends \DomainException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
