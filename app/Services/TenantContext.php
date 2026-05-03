<?php

declare(strict_types=1);

namespace App\Services;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contracts\TenantContextInterface;
use App\Models\Organisation;
use App\Models\User;
use RuntimeException;

class TenantContext implements TenantContextInterface
{
    private ?Organisation $currentOrganisation = null;
    private ?User $currentUser = null;

    /**
     * Set the current tenant context
     */
    public function setContext(User $user, Organisation $organisation): void
    {
        if (!$user->belongsToOrganisation($organisation->id)) {
            throw new RuntimeException(
                "User {$user->id} does not belong to organisation {$organisation->id}"
            );
        }

        $this->currentUser = $user;
        $this->currentOrganisation = $organisation;

        session(['current_organisation_id' => $organisation->id]);
    }

    /**
     * Get the current organisation
     */
    public function getCurrentOrganisation(): Organisation
    {
        if (!$this->currentOrganisation) {
            $this->resolveFromSession();
        }

        if (!$this->currentOrganisation) {
            throw new RuntimeException('No tenant context set');
        }

        return $this->currentOrganisation;
    }

    /**
     * Get the current organisation ID
     */
    public function getCurrentOrganisationId(): string
    {
        return $this->getCurrentOrganisation()->id;
    }

    /**
     * Get the current user
     */
    public function getCurrentUser(): User
    {
        if (!$this->currentUser) {
            throw new RuntimeException('No user context set');
        }

        return $this->currentUser;
    }

    /**
     * Clear the tenant context
     */
    public function clear(): void
    {
        $this->currentOrganisation = null;
        $this->currentUser = null;
        session()->forget('current_organisation_id');
    }

    /**
     * Resolve context from session
     */
    private function resolveFromSession(): void
    {
        $organisationId = session('current_organisation_id');

        if (!$organisationId) {
            return;
        }

        $organisation = Organisation::find($organisationId);

        if ($organisation) {
            $this->currentOrganisation = $organisation;
        }
    }

    /**
     * Check if current context is platform
     */
    public function isPlatformContext(): bool
    {
        return $this->getCurrentOrganisation()->isPlatform();
    }

    /**
     * Check if current context is tenant
     */
    public function isTenantContext(): bool
    {
        return $this->getCurrentOrganisation()->isTenant();
    }

    /**
     * Get current tenant ID as domain value object.
     *
     * Resolution order:
     *   1. Explicit context set via setContext() (always wins)
     *   2. Authenticated user's organisation_id (HTTP requests after login)
     *   3. Session fallback (legacy / session-only flows)
     *
     * @throws RuntimeException When no tenant context can be determined
     */
    public function currentTenantId(): TenantId
    {
        // Tier 1: explicit context (setContext was called)
        if ($this->currentOrganisation !== null) {
            return TenantId::fromOrganisationId($this->currentOrganisation->id);
        }

        // Tier 2: authenticated user carries their organisation
        if (auth()->check()) {
            $orgId = auth()->user()->organisation_id ?? null;
            if ($orgId) {
                return TenantId::fromOrganisationId($orgId);
            }
        }

        // Tier 3: session fallback (CLI seeds, middleware-less tests)
        $orgId = session('current_organisation_id');
        if ($orgId) {
            return TenantId::fromOrganisationId((string) $orgId);
        }

        throw new RuntimeException(
            'No tenant context: setContext() was not called, no authenticated user with organisation_id, and no current_organisation_id in session'
        );
    }
}
