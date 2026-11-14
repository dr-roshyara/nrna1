<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A display name as resolved (v3.11 §9 "returned to the caller"): the value; its origin; the requested locale; the CLDR
 * locale that held it and the CLDR release (CLDR origin only); the form used and whether a requested short form fell
 * back to the standard form; and, for a platform fallback, the default locale used.
 */
final readonly class ResolvedDisplayName
{
    public function __construct(
        public string $value,
        public DisplayNameOrigin $origin,
        public string $requestedLocale,
        public ?string $resolvedLocale,
        public ?string $release,
        public string $form,
        public bool $shortFellBack,
        public ?string $fallbackLocale,
    ) {
    }
}
