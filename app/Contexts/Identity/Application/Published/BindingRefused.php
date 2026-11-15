<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Published;

/**
 * I-TDD-02 §12 C-I6 (published language): a redemption of O's claim for R was refused because the redeeming person is
 * already bound to another of O's references (INV-BND-2, D-113). O learns of the refusal only: which R, nothing about
 * the person, their other reference or any other organisation.
 */
final readonly class BindingRefused
{
    public function __construct(public string $organisationId, public string $personRef)
    {
    }
}
