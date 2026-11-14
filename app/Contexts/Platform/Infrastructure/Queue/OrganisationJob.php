<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * The base class of EVERY organisation job (PBDIGIT-345 task 7; E-2; tactical design §3.10 Q-3 … Q-6; ISO-6).
 * - the envelope carries the organisation id (a UUID), never a locator or a connection: the registry is read again at
 *   every attempt (Q-5);
 * - the queue connection is fixed to `organisation` at construction (Q-3); dispatch only through
 *   OrganisationJobDispatcher (no Dispatchable trait on purpose);
 * - the middleware is FINAL: EstablishOrganisationContext always runs (Q-4);
 * - identifiers only: no SerializesModels, no Model properties (Q-6; architecture test).
 * In handle(), read the organisation's data through $this->organisation()->connection only.
 */
abstract class OrganisationJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public const CONNECTION = 'organisation';
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    public readonly string $organisationId;

    public function __construct(TenantId $organisation)
    {
        if (preg_match(self::UUID, $organisation->value()) !== 1) {
            throw new \InvalidArgumentException('An organisation job carries an organisation id (a lower-case UUID).');
        }
        $this->organisationId = $organisation->value();
        $this->onConnection(self::CONNECTION);
    }

    /** @return list<object> */
    final public function middleware(): array
    {
        return [new EstablishOrganisationContext()];
    }

    /** The context EstablishOrganisationContext bound for THIS attempt. */
    final protected function organisation(): OrganisationContext
    {
        return app(OrganisationContext::class);
    }

    public static function isUuid(string $value): bool
    {
        return preg_match(self::UUID, $value) === 1;
    }
}
