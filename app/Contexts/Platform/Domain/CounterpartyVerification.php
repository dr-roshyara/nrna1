<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * PublicDigit's own verification of its commercial counterparty (D-175): who accepts the service terms and their
 * stated link to the organisation (e.g. a signed service agreement and a contact check). It is PublicDigit's
 * decision about whom it serves and creates no constitutional authority. Only the evidence reference is held here.
 */
final readonly class CounterpartyVerification
{
    public function __construct(public string $evidenceRef)
    {
        if (trim($evidenceRef) === '') {
            throw new \InvalidArgumentException('A counterparty verification needs its evidence reference.');
        }
    }
}
