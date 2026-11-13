<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/** Refused, never answered partially (v3.11 §6): beyond the committed position, τ in the future, or τ not closable now. */
final class KnowledgeNotAvailable extends \RuntimeException
{
}
