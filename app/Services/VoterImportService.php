<?php

namespace App\Services;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VoterImportService
{
    private ?Organisation $org = null;

    public function __construct(
        private readonly Election $election,
        private readonly VoterEligibilityService $eligibilityService
    ) {}

    private function getOrganisation(): Organisation
    {
        return $this->org ??= Organisation::findOrFail($this->election->organisation_id);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $organisation = $this->getOrganisation();
        $isElectionOnly = !$organisation->uses_full_membership;

        if ($isElectionOnly) {
            // Election-only mode: firstname;lastname;email
            $rows = [
                ['firstname', 'lastname', 'email'],
                ['John', 'Doe', 'john@example.com'],
                ['Jane', 'Smith', 'jane@example.com'],
                ['Miguel', 'Garcia', 'miguel@example.com'],
            ];
        } else {
            // Full membership mode: email only
            $rows = [
                ['email'],
                ['member@example.com'],
                ['voter@yourorg.com'],
                ['jane.doe@example.com'],
            ];
        }

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row, ';');
            }
            fclose($handle);
        }, 'voter-import-template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(UploadedFile $file): array
    {
        $rows    = $this->parseFile($file);
        $preview = [];
        $valid   = 0;
        $invalid = 0;

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $errors = $this->validateRow($row);

            if (empty($errors)) {
                $valid++;
                $status = '✅ Valid';
            } else {
                $invalid++;
                $status = '❌ Invalid';
            }

            $preview[] = [
                'row'    => $rowNum,
                'email'  => $row['email'] ?? '',
                'status' => $status,
                'errors' => $errors,
            ];
        }

        return [
            'preview' => $preview,
            'stats'   => [
                'total'   => count($rows),
                'valid'   => $valid,
                'invalid' => $invalid,
            ],
        ];
    }

    // ── Import ────────────────────────────────────────────────────────────────

    public function import(UploadedFile $file): array
    {
        $rows    = $this->parseFile($file);
        $userIds = [];
        $skipped = 0;

        foreach ($rows as $row) {
            $errors = $this->validateRow($row);

            if (! empty($errors)) {
                $skipped++;
                continue;
            }

            $user = User::where('email', trim($row['email']))->first();
            if (! $user) {
                $skipped++;
                continue;
            }

            $userIds[] = $user->id;
        }

        if (empty($userIds)) {
            return ['created' => 0, 'already_existing' => 0, 'skipped' => $skipped];
        }

        $result = ElectionMembership::bulkAssignVoters(
            $userIds,
            $this->election->id,
            auth()->id()
        );

        return [
            'created'          => $result['success']          ?? 0,
            'already_existing' => $result['already_existing'] ?? 0,
            'skipped'          => $skipped + ($result['invalid'] ?? 0),
        ];
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    private function parseFile(UploadedFile $file): array
    {
        $rows = Excel::toArray(new \App\Imports\OrganisationUserImport(), $file);
        return $rows[0] ?? [];
    }

    private function validateRow(array $row): array
    {
        $errors = [];
        $org = $this->getOrganisation();

        $email = trim($row['email'] ?? '');

        if ($email === '') {
            $errors[] = 'Email is required.';
            return $errors;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "'{$email}' is not a valid email address.";
            return $errors;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $errors[] = "User '{$email}' does not exist in the platform.";
            return $errors;
        }

        // Use VoterEligibilityService to support both membership modes
        if (! $this->eligibilityService->isEligibleVoter($org, $user)) {
            if ($org->isElectionOnly()) {
                $errors[] = "'{$email}' is not an active member of this organisation.";
            } else {
                $errors[] = "'{$email}' is not an eligible voter — must be an active formal member with full voting rights.";
            }
        }

        return $errors;
    }

    // ── Election-Only Preview ─────────────────────────────────────────────────

    public function previewElectionOnly(UploadedFile $file): array
    {
        $rows = $this->parseFileElectionOnly($file);
        $preview = [];
        $stats = ['new' => 0, 'existing' => 0, 'invalid' => 0];

        foreach ($rows as $index => $row) {
            $data = $this->parseRowElectionOnly($row);
            $email = $data['email'];

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $preview[] = [
                    'row' => $index + 1,
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'email' => $email,
                    'status' => 'invalid',
                    'reason' => 'Invalid or empty email',
                ];
                $stats['invalid']++;
                continue;
            }

            $user = User::where('email', $email)->first();
            $status = $user ? 'existing' : 'new';

            $preview[] = [
                'row' => $index + 1,
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $email,
                'status' => $status,
                'reason' => $user ? 'Existing user' : 'Will be created',
            ];

            $stats[$status]++;
        }

        return ['preview' => $preview, 'stats' => $stats];
    }

    // ── Election-Only Import ──────────────────────────────────────────────────

    public function importElectionOnly(UploadedFile $file): array
    {
        $rows = $this->parseFileElectionOnly($file);

        $results = [
            'created' => 0,
            'existing' => 0,
            'errors' => [],
            'invitations' => 0,
        ];

        $organisation = $this->election->organisation;

        foreach ($rows as $index => $row) {
            try {
                $data = $this->parseRowElectionOnly($row);
                $email = $data['email'];

                // Validation
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $results['errors'][] = "Row " . ($index + 1) . ": Invalid email — {$email}";
                    continue;
                }

                // Wrap in transaction for data integrity
                DB::transaction(function () use ($data, $email, $organisation, &$results) {
                    // Build name
                    $fullName = trim($data['firstname'] . ' ' . $data['lastname']);
                    if (empty($fullName)) {
                        $fullName = explode('@', $email)[0];
                    }

                    // Find or create user
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $fullName,
                            'first_name' => $data['firstname'] ?: null,
                            'last_name' => $data['lastname'] ?: null,
                            'password' => bcrypt(\Illuminate\Support\Str::random(32)),
                            'organisation_id' => $organisation->id,
                        ]
                    );

                    $isNewUser = $user->wasRecentlyCreated;

                    // Link to organisation
                    \App\Models\OrganisationUser::firstOrCreate(
                        [
                            'organisation_id' => $organisation->id,
                            'user_id' => $user->id,
                        ],
                        ['status' => 'active']
                    );

                    // Assign to election
                    ElectionMembership::firstOrCreate(
                        [
                            'election_id' => $this->election->id,
                            'user_id' => $user->id,
                        ],
                        [
                            'organisation_id' => $organisation->id,
                            'role' => 'voter',
                            'status' => 'active',
                        ]
                    );

                    // Create invitation for new users OR existing unverified users
                    if ($isNewUser || !$user->email_verified_at) {
                        $invitation = \App\Models\VoterInvitation::firstOrCreate(
                            [
                                'election_id' => $this->election->id,
                                'user_id' => $user->id,
                            ],
                            [
                                'organisation_id' => $organisation->id,
                                'token' => \Illuminate\Support\Str::random(64),
                                'email_status' => 'pending',
                                'expires_at' => now()->addDays(7),
                            ]
                        );

                        // Only dispatch if newly created and not already sent
                        if ($invitation->wasRecentlyCreated) {
                            \App\Jobs\SendVoterInvitation::dispatch($invitation);
                            $results['invitations']++;
                        }
                    }

                    $results[$isNewUser ? 'created' : 'existing']++;
                });

            } catch (\Exception $e) {
                $results['errors'][] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return $results;
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function parseRowElectionOnly(array $row): array
    {
        // Handle both comma and semicolon separators
        if (count($row) === 1 && str_contains($row[0] ?? '', ';')) {
            $row = explode(';', $row[0]);
        }

        // Handle 2-column or 3-column format
        if (count($row) === 2) {
            return [
                'firstname' => trim($row[0] ?? ''),
                'lastname' => '',
                'email' => trim($row[1] ?? ''),
            ];
        }

        return [
            'firstname' => trim($row[0] ?? ''),
            'lastname' => trim($row[1] ?? ''),
            'email' => trim($row[2] ?? ''),
        ];
    }

    private function parseFileElectionOnly(UploadedFile $file): array
    {
        $extension = $file->getClientOriginalExtension();

        // Use Excel library for xlsx/xls files
        if (in_array($extension, ['xlsx', 'xls'])) {
            $excelRows = Excel::toArray(new \App\Imports\OrganisationUserImport(), $file);
            return $excelRows[0] ?? [];
        }

        // CSV/TXT files - unified parsing
        return $this->parseCsvContent($file);
    }

    private function parseCsvContent(UploadedFile $file): array
    {
        $content = $this->getFileContent($file);
        $lines = explode("\n", trim($content));

        if (count($lines) < 2) {
            return [];
        }

        $rows = [];
        // Detect delimiter from header
        $header = $lines[0];
        $delimiter = str_contains($header, ';') ? ';' : ',';

        // Skip header, parse data rows
        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if (empty($line)) continue;

            $row = str_getcsv($line, $delimiter);
            if (!empty($row[0]) || !empty($row[1] ?? null) || !empty($row[2] ?? null)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    private function getFileContent(UploadedFile $file): string
    {
        $path = $file->getRealPath();

        // Handle fake files in tests - getRealPath() may return false
        if ($path === false || !file_exists($path)) {
            return $file->get();
        }

        return file_get_contents($path);
    }
}
