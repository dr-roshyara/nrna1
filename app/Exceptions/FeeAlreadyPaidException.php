<?php

namespace App\Exceptions;

use DomainException;

class FeeAlreadyPaidException extends DomainException
{
    public function __construct(string $message = 'This fee has already been paid.')
    {
        parent::__construct($message);
    }
}
