<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

use App\Contexts\Platform\Application\Ports\OrganisationWorkDispatch;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 8 (E-3): one organisation's share of a scheduled operation = one OrganisationJob of the configured
 * class, constructed with the organisation id only and queued through OrganisationJobDispatcher (Task 7). The class
 * must extend OrganisationJob and take exactly the organisation id.
 */
final class FanOutJobDispatch implements OrganisationWorkDispatch
{
    /** @param class-string<OrganisationJob> $jobClass */
    public function __construct(private readonly string $jobClass, private readonly OrganisationJobDispatcher $dispatcher)
    {
        if (!is_subclass_of($jobClass, OrganisationJob::class)) {
            throw new OrganisationDispatchRefused('A fanned-out operation is an OrganisationJob.');
        }
        $parameters = (new \ReflectionClass($jobClass))->getConstructor()?->getParameters() ?? [];
        if (count($parameters) !== 1 || (string) $parameters[0]->getType() !== TenantId::class) {
            throw new OrganisationDispatchRefused('A fanned-out job is constructed with the organisation id only.');
        }
    }

    public function dispatchFor(TenantId $organisation): void
    {
        $this->dispatcher->dispatch(new ($this->jobClass)($organisation));
    }
}
