<?php

namespace App\Contexts\Geography\Application\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

/**
 * Simple Geography Candidate Service
 *
 * Follows "Simplicity Over Complexity" principle.
 * Direct database inserts, no complex dependencies.
 */
class GeographyCandidateService
{
    /**
     * Submit missing geography for review.
     *
     * SIMPLE: Direct insert to landlord geo_candidate_units table.
     * No complex validation, no service layers.
     *
     * @param array $data User submission data
     * @return int Inserted candidate ID
     */
    public function submitMissingGeography(array $data): int
    {
        // SIMPLE: Direct insert to landlord table
        $now = Carbon::now();

        // Determine source type based on presence of tenant_id
        $sourceType = 'USER_SUBMISSION';
        if (!empty($data['tenant_id'])) {
            $sourceType = 'TENANT_SUGGESTION';
        }

        $insertData = [
            'name_proposed' => $data['name'] ?? 'Unknown',
            'admin_level' => $data['level'] ?? 4,
            'parent_id' => $data['parent_id'] ?? null,
            'country_code' => $data['country_code'] ?? 'NP',
            'source_description' => $data['reason'] ?? 'User submission',
            'source_type' => $sourceType,
            'review_status' => 'PENDING',
            'source_user_id' => $data['user_id'] ?? null,
            'source_tenant_id' => $data['tenant_id'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // SIMPLE: Direct database insert
        return DB::table('geo_candidate_units')->insertGetId($insertData);
    }
}