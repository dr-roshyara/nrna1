<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** Only the platform owner appoints or ends stewards and appoints matter reviewers (D-84, D-84a). Nothing was written. */
final class PlatformOwnerRequired extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Only the platform owner may make this roster write (D-84, D-84a).');
    }
}
