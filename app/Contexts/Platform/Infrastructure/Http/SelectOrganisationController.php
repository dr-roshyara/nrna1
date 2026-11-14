<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Http;

use App\Contexts\Platform\Application\EnterOrganisation;
use App\Contexts\Platform\Application\OrganisationEntryRefused;
use App\Contexts\Platform\Application\Ports\BindingsUnavailable;
use App\Contexts\Platform\Application\Ports\SignedInAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * E-1 selection after login (PBDIGIT-345 task 6): POST `organisation_id`. Accepted only for an organisation the
 * signed-in account is CURRENTLY bound to, not legacy and operational; then kept in the session as a request to work
 * in it (the session id is regenerated: no fixation). Refused → 403 and any earlier selection cleared.
 * The list to choose from is Identity's question (the person's own bindings, D-178 L-10): not here.
 */
final class SelectOrganisationController
{
    public function __invoke(Request $request, EnterOrganisation $entry, SignedInAccount $account): Response
    {
        $requested = $request->input('organisation_id');
        try {
            $selected = $entry->select($account->accountId(), is_string($requested) ? $requested : '');
        } catch (OrganisationEntryRefused $e) {
            $request->session()->forget(OrganisationEntry::SESSION_KEY);
            Log::info('platform.organisation_selection.refused', ['reason' => $e->reason->value]);

            return response('Organisation access refused.', Response::HTTP_FORBIDDEN);
        } catch (BindingsUnavailable) {
            return response('Service unavailable.', Response::HTTP_SERVICE_UNAVAILABLE);
        }
        $request->session()->regenerate();
        $request->session()->put(OrganisationEntry::SESSION_KEY, $selected->value());

        return response()->noContent();
    }
}
