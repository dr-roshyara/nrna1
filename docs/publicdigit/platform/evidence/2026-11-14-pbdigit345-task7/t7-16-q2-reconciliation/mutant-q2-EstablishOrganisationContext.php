<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Queue;

use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use Closure;

/**
 * The job middleware of E-2 (PBDIGIT-345 task 7; tactical design §3.10 Q-4, Q-5; C-2; ISO-6), on EVERY attempt:
 * 1. the job must have arrived on connection `organisation`: inline (sync, dispatchSync) or another connection →
 *    refused by an exception (never run "centrally" or inline);
 * 2. the organisation id must be present; then it is resolved through the REGISTRY again (a retry re-resolves): no id,
 *    or not routable → the job is FAILED at once with the reason (no retry) and does not run;
 * 3. the organisation's own connection is established and bound for this attempt only;
 * 4. finally (success or failure): the context is forgotten and the connection purged.
 * An unavailable registry or database throws, so the worker retries later (fail closed, D-178 §13.6).
 */
final class EstablishOrganisationContext
{
    public function handle(object $job, Closure $next): mixed
    {
        if (!$job instanceof OrganisationJob) {
            throw new OrganisationJobRefused('not-an-organisation-job');
        }
        if ($job->job === null || $job->job->getConnectionName() !== OrganisationJob::CONNECTION) {
            throw new OrganisationJobRefused('wrong-connection');
        }
        $id = (new \ReflectionProperty(OrganisationJob::class, 'organisationId'))->isInitialized($job) ? $job->organisationId : '';
        if (!OrganisationJob::isUuid($id)) {
            $job->fail(new OrganisationJobRefused('no-organisation-id'));

            return null;
        }
        try {
            $resolved = app(ResolutionService::class)->resolve($id);
        } catch (OrganisationNotRoutable $e) {
            return $next($job);
        }
        $factory = app(OrganisationConnectionFactory::class);
        $context = $factory->establish($resolved);
        app()->instance(OrganisationContext::class, $context);
        try {
            return $next($job);
        } finally {
            app()->forgetInstance(OrganisationContext::class);
            $factory->release($context);
        }
    }
}
