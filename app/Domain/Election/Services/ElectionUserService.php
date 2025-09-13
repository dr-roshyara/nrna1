<?php

namespace App\Domain\Election\Services;

// app/Domain/Election/Services/ElectionUserService.php

use App\Domain\Election\Models\ElectionUser;
use App\Models\User;
use App\Services\ElectionContextService;

/**
 * Service for managing election users and their lifecycle
 */
class ElectionUserService
{
    /**
     * Import users from main application to election
     */
    public function importUsersFromMain(array $userIds): array
    {
        $imported = [];
        $errors = [];
        
        foreach ($userIds as $userId) {
            try {
                $mainUser = User::find($userId);
                if (!$mainUser) {
                    $errors[] = "User {$userId} not found";
                    continue;
                }
                
                $electionUser = ElectionUser::createFromMainUser($mainUser);
                $imported[] = $electionUser;
                
            } catch (\Exception $e) {
                $errors[] = "Failed to import user {$userId}: " . $e->getMessage();
            }
        }
        
        return [
            'imported' => $imported,
            'errors' => $errors,
            'count' => count($imported)
        ];
    }
    
    /**
     * Import users from CSV file
     */
    public function importUsersFromCsv(string $csvPath): array
    {
        $imported = [];
        $errors = [];
        
        if (!file_exists($csvPath)) {
            throw new \Exception("CSV file not found: {$csvPath}");
        }
        
        $csvData = array_map('str_getcsv', file($csvPath));
        $headers = array_shift($csvData);
        
        foreach ($csvData as $row) {
            try {
                $userData = array_combine($headers, $row);
                $electionUser = ElectionUser::create($userData);
                $imported[] = $electionUser;
                
            } catch (\Exception $e) {
                $errors[] = "Failed to import row: " . $e->getMessage();
            }
        }
        
        return [
            'imported' => $imported,
            'errors' => $errors,
            'count' => count($imported)
        ];
    }
    
    /**
     * Bulk approve voters
     */
    public function bulkApproveVoters(array $userIds, string $approverName): array
    {
        $approved = [];
        $errors = [];
        
        foreach ($userIds as $userId) {
            try {
                $electionUser = ElectionUser::find($userId);
                if (!$electionUser) {
                    $errors[] = "Election user {$userId} not found";
                    continue;
                }
                
                $electionUser->approveAsVoter($approverName, request()->ip());
                $approved[] = $electionUser;
                
            } catch (\Exception $e) {
                $errors[] = "Failed to approve user {$userId}: " . $e->getMessage();
            }
        }
        
        return [
            'approved' => $approved,
            'errors' => $errors,
            'count' => count($approved)
        ];
    }
    
    /**
     * Get election user statistics
     */
    public function getElectionUserStatistics(): array
    {
        return [
            'total_users' => ElectionUser::count(),
            'total_voters' => ElectionUser::where('is_voter', true)->count(),
            'approved_voters' => ElectionUser::whereNotNull('approved_at')->count(),
            'suspended_voters' => ElectionUser::whereNotNull('suspended_at')->count(),
            'users_voted' => ElectionUser::whereHas('votes')->count(),
            'candidates' => ElectionUser::where('has_candidacy', true)->count(),
            'committee_members' => ElectionUser::where('is_committee_member', true)->count()
        ];
    }
}
