<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait HasAuditFields
{
    /**
     * Initialize the trait
     */
    protected function initializeHasAuditFields()
    {
        // Only merge fillable if columns exist in the table
        if (Schema::hasColumn($this->getTable(), 'approvedBy')) {
            $this->mergeFillable(['approvedBy', 'suspendedBy']);
        }

        // Only add casts if columns exist
        $dateColumns = [
            'suspended_at',
            'voting_started_at',
            'vote_submitted_at',
            'vote_completed_at',
            'voter_registration_at'
        ];

        $casts = [];
        foreach ($dateColumns as $column) {
            if (Schema::hasColumn($this->getTable(), $column)) {
                $casts[$column] = 'datetime';
            }
        }

        if (!empty($casts)) {
            $this->mergeCasts($casts);
        }
    }

    /**
     * Get audit trail for this user
     */
    public function getAuditTrail(): array
    {
        return [
            'approved_by' => $this->approvedBy ?? null,
            'suspended_by' => $this->suspendedBy ?? null,
            'suspended_at' => $this->suspended_at ?? null,
            'voting_started_at' => $this->voting_started_at ?? null,
            'vote_submitted_at' => $this->vote_submitted_at ?? null,
            'vote_completed_at' => $this->vote_completed_at ?? null,
            'voter_registration_at' => $this->voter_registration_at ?? null,
        ];
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        // Check if the columns exist first
        if (!Schema::hasColumn($this->getTable(), 'can_vote') ||
            !Schema::hasColumn($this->getTable(), 'suspended_at')) {
            return false;
        }

        return $this->can_vote === 0 && $this->suspended_at !== null;
    }
}