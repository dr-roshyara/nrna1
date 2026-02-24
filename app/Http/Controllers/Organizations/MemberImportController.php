<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MemberImportController extends Controller
{
    /**
     * Show the member import page for an organization.
     *
     * GET /organizations/{slug}/members/import
     */
    public function create(string $slug)
    {
        $organization = Organization::where('slug', $slug)
            ->firstOrFail();

        // Check if current user is a member of this organization
        $isMember = $organization->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'Sie haben keinen Zugriff auf diese Organisation.');
        }

        return Inertia::render('Organizations/Members/Import', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
            ],
        ]);
    }

    /**
     * Handle member import from CSV/Excel data.
     *
     * POST /organizations/{slug}/members/import
     */
    public function store(Request $request, string $slug)
    {
        $organization = Organization::where('slug', $slug)
            ->firstOrFail();

        // Check if current user is a member of this organization
        $isMember = $organization->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'Sie haben keinen Zugriff auf diese Organisation.');
        }

        // Validate request data - frontend sends headers and rows
        $validated = $request->validate([
            'headers' => 'required|array',
            'rows' => 'required|array|min:1',
            'fileName' => 'nullable|string',
        ]);

        // Transform rows to standard format based on headers
        // Headers might be: ['Email', 'First Name', 'Last Name']
        // Rows are objects with values for each header
        $members = [];
        $emailIndex = -1;
        $firstNameIndex = -1;
        $lastNameIndex = -1;

        // Find column indices (case-insensitive)
        foreach ($validated['headers'] as $index => $header) {
            $lowerHeader = strtolower(trim($header));
            if ($lowerHeader === 'email') {
                $emailIndex = $index;
            } elseif (in_array($lowerHeader, ['first name', 'firstname', 'first_name'])) {
                $firstNameIndex = $index;
            } elseif (in_array($lowerHeader, ['last name', 'lastname', 'last_name'])) {
                $lastNameIndex = $index;
            }
        }

        // Check required columns
        if ($emailIndex === -1) {
            return response()->json([
                'success' => false,
                'message' => 'Email column is required but not found.',
            ], 422);
        }

        // Build members array from rows
        foreach ($validated['rows'] as $row) {
            $email = isset($row[$emailIndex]) ? trim((string)$row[$emailIndex]) : null;

            if (!$email) {
                continue; // Skip empty rows
            }

            $firstName = $firstNameIndex !== -1 && isset($row[$firstNameIndex]) ? trim((string)$row[$firstNameIndex]) : '';
            $lastName = $lastNameIndex !== -1 && isset($row[$lastNameIndex]) ? trim((string)$row[$lastNameIndex]) : '';

            if (empty($firstName) && empty($lastName)) {
                $firstName = $email; // Use email as name if no names provided
            }

            $members[] = [
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
            ];
        }

        if (empty($members)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid member data found in the file.',
            ], 422);
        }

        // Re-validate on server side
        $emails = array_column($members, 'email');
        $duplicateEmails = array_diff_assoc($emails, array_unique($emails));

        if (!empty($duplicateEmails)) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate email addresses found in import data.',
            ], 422);
        }

        // Check for existing emails
        $existingEmails = User::whereIn('email', $emails)->pluck('email')->toArray();
        if (!empty($existingEmails)) {
            return response()->json([
                'success' => false,
                'message' => 'Some email addresses already exist in the system.',
                'existing_emails' => $existingEmails,
            ], 422);
        }

        // Import members
        $importedCount = 0;
        $errors = [];

        foreach ($members as $index => $member) {
            try {
                // Create user
                $user = User::create([
                    'name' => trim($member['first_name'] . ' ' . $member['last_name']),
                    'email' => $member['email'],
                    'password' => bcrypt('temp_password_' . uniqid()),
                    'email_verified_at' => now(),
                ]);

                // Attach to organization
                $organization->users()->attach($user->id, [
                    'role' => 'voter',
                    'assigned_at' => now(),
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index + 1,
                    'email' => $member['email'],
                    'error' => $e->getMessage(),
                ];
            }
        }

        if ($importedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => "$importedCount members imported successfully.",
                'imported_count' => $importedCount,
                'errors' => $errors,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No members could be imported.',
                'errors' => $errors,
            ], 422);
        }
    }
}
