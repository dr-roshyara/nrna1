<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Http;

use App\Contexts\Platform\Application\EnterOrganisation;
use App\Contexts\Platform\Application\OrganisationEntryRefused;
use App\Contexts\Platform\Application\Ports\BindingsUnavailable;
use App\Contexts\Platform\Application\Ports\SignedInAccount;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\OrganisationRoutingRefused;
use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * The HTTP entry E-1 (PBDIGIT-345 task 6; route middleware `organisation.entry`, after the session has started).
 * On EVERY request: the session's selection is checked again in full by EnterOrganisation (ISO-3 current binding,
 * no conflicting id in route, body or header, not legacy, registry-routable: ISO-2); then the organisation's own
 * connection is established and bound FOR THIS REQUEST ONLY; afterwards it is unbound and released.
 * - refused → 403, the selection cleared, nothing read from any database; logged as the reason only (ISO-10);
 * - Identity or the organisation's database unavailable → 503, the selection kept (fail closed, D-178 §13.6).
 * Registry routes only: never combined with the legacy TenantContext (T-10 part 3).
 */
final class OrganisationEntry
{
    /** The selection: a REQUEST to work in an organisation, never proof. Distinct from the legacy session key. */
    public const SESSION_KEY = 'platform.organisation_selection';

    /** Where a request can carry an organisation id; any that differs from the selection is refused (ISO-3). */
    private const ROUTE_PARAMETERS = ['organisation', 'organisation_id', 'organisationId'];
    private const BODY_FIELDS = ['organisation_id', 'organisationId'];
    private const HEADERS = ['X-Organisation-Id', 'X-Tenant-Id'];

    public function __construct(
        private readonly EnterOrganisation $entry,
        private readonly SignedInAccount $account,
        private readonly OrganisationConnectionFactory $factory,
        private readonly Container $container,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $selection = $request->session()->get(self::SESSION_KEY);
        try {
            $resolved = $this->entry->enter($this->account->accountId(), is_string($selection) ? $selection : null, self::carriedIds($request));
            $context = $this->factory->establish($resolved);
        } catch (OrganisationEntryRefused $e) {
            $request->session()->forget(self::SESSION_KEY);
            Log::info('platform.organisation_entry.refused', ['reason' => $e->reason->value]);

            return response('Organisation access refused.', Response::HTTP_FORBIDDEN);
        } catch (BindingsUnavailable | OrganisationRoutingRefused | \Illuminate\Database\QueryException $e) {
            Log::warning('platform.organisation_entry.unavailable', ['kind' => $e::class]);

            return response('Service unavailable.', Response::HTTP_SERVICE_UNAVAILABLE);
        }

        $this->container->instance(OrganisationContext::class, $context);
        try {
            return $next($request);
        } finally {
            $this->container->forgetInstance(OrganisationContext::class);
            $this->factory->release($context);
        }
    }

    /** @return list<string> */
    private static function carriedIds(Request $request): array
    {
        $ids = [];
        foreach (self::ROUTE_PARAMETERS as $name) {
            $ids[] = $request->route()?->parameter($name);
        }
        foreach (self::BODY_FIELDS as $name) {
            $ids[] = $request->input($name);
        }
        foreach (self::HEADERS as $name) {
            $ids[] = $request->headers->get($name);
        }

        return array_values(array_map(static fn ($id): string => is_scalar($id) ? (string) $id : '<non-scalar>',
            array_filter($ids, static fn ($id): bool => $id !== null && $id !== '')));
    }
}
