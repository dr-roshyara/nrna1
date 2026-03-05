<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\User;
use RuntimeException;

class TenantContext
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
}
