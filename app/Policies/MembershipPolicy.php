<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

/**
 * MembershipPolicy
 *
 * Role-based access control for all membership management actions.
 * Roles are sourced exclusively from user_organisation_roles.
 *
 * Role hierarchy:
 *   owner      (100) — all actions, including membership type management
 *   admin       (80) — approve/reject applications, record fees, renew members
 *   commission  (60) — view applications only
 *   voter       (40) — no management access
 *   member      (20) — self-renewal only
 */
class MembershipPolicy
{
    // ── viewApplications ─────────────────────────────────────────────────────

    public function viewApplications(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin', 'commission']);
    }

    // ── approveApplication ───────────────────────────────────────────────────

    public function approveApplication(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    // ── rejectApplication ────────────────────────────────────────────────────

    public function rejectApplication(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    // ── manageMembershipTypes ────────────────────────────────────────────────

    /**
     * Only the organisation owner may define fee structures and membership types.
     */
    public function manageMembershipTypes(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner']);
    }

    // ── recordFeePayment ─────────────────────────────────────────────────────

    public function recordFeePayment(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    // ── initiateRenewal ──────────────────────────────────────────────────────

    /**
     * Admins/owners can renew any member.
     * A member may renew their own membership (isSelf = true).
     */
    public function initiateRenewal(User $user, Organisation $organisation, bool $isSelf = false): bool
    {
        if ($this->hasRole($user, $organisation, ['owner', 'admin'])) {
            return true;
        }

        // Self-service: members may renew themselves only
        if ($isSelf && $this->hasRole($user, $organisation, ['member'])) {
            return true;
        }

        return false;
    }

    // ── update ───────────────────────────────────────────────────────────────

    /**
     * Only organisation owners and admins may update organisation settings.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    // ── manageMembership ─────────────────────────────────────────────────────

    /**
     * Only organisation owners and admins may manage membership settings.
     */
    public function manageMembership(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    // ── Internal helper ──────────────────────────────────────────────────────

    private function hasRole(User $user, Organisation $organisation, array $roles): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', $roles)
            ->exists();
    }
}
