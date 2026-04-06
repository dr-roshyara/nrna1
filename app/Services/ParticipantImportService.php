<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\OrganisationParticipant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrganisationUserImport;

class ParticipantImportService
{
    private const VALID_TYPES = ['staff', 'guest', 'election_committee'];

    public function __construct(private Organisation $organisation) {}

    // ── Template ──────────────────────────────────────────────────────────────

    public function downloadTemplate()
    {
        $headers = ['email', 'participant_type', 'role', 'expires_at', 'permissions'];

        $sample = [
            ['john.doe@example.com',   'staff',              'coordinator', '',           ''],
            ['guest@conference.com',   'guest',              'observer',    '2099-12-31', '{"view":true}'],
            ['committee@example.com',  'election_committee', 'scrutineer',  '',           ''],
        ];

        return Excel::download(
            new class($headers, $sample) implements
                \Maatwebsite\Excel\Concerns\FromArray,
                \Maatwebsite\Excel\Concerns\WithHeadings
            {
                public function __construct(
                    private array $headings,
                    private array $data,
                ) {}

                public function headings(): array { return $this->headings; }
                public function array(): array    { return $this->data; }
            },
            'participant_import_template.xlsx'
        );
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview($file): array
    {
        $rows    = Excel::toArray(new OrganisationUserImport(), $file)[0] ?? [];
        $preview = [];
        $invalid = 0;

        foreach ($rows as $index => $row) {
            $rowNumber  = $index + 2;
            $validation = $this->validateRow($row);

            if (! $validation['valid']) {
                $invalid++;
            }

            $preview[] = [
                'row'              => $rowNumber,
                'email'            => $row['email'] ?? '',
                'participant_type' => $row['participant_type'] ?? '',
                'role'             => $row['role'] ?? '',
                'expires_at'       => $row['expires_at'] ?? '',
                'status'           => $validation['valid'] ? '✅ Valid' : '❌ Invalid',
                'errors'           => $validation['errors'],
            ];
        }

        $total = count($rows);

        return [
            'preview' => $preview,
            'stats'   => [
                'total'   => $total,
                'valid'   => $total - $invalid,
                'invalid' => $invalid,
            ],
        ];
    }

    // ── Import ────────────────────────────────────────────────────────────────

    public function import($file): array
    {
        $rows    = Excel::toArray(new OrganisationUserImport(), $file)[0] ?? [];
        $created = 0;
        $updated = 0;
        $skipped = 0;

        DB::transaction(function () use ($rows, &$created, &$updated, &$skipped) {
            foreach ($rows as $row) {
                $validation = $this->validateRow($row);

                if (! $validation['valid']) {
                    $skipped++;
                    continue;
                }

                $user = User::where('email', trim($row['email']))->first();

                $expiresAt   = $this->parseDate($row['expires_at'] ?? '');
                $permissions = $this->parsePermissions($row['permissions'] ?? '');

                $existing = OrganisationParticipant::withoutGlobalScopes()
                    ->where('organisation_id', $this->organisation->id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'participant_type' => trim($row['participant_type']),
                        'role'             => trim($row['role'] ?? '') ?: null,
                        'expires_at'       => $expiresAt,
                        'permissions'      => $permissions,
                        'assigned_at'      => $existing->assigned_at ?? now(),
                    ]);
                    $updated++;
                } else {
                    OrganisationParticipant::create([
                        'organisation_id'  => $this->organisation->id,
                        'user_id'          => $user->id,
                        'participant_type' => trim($row['participant_type']),
                        'role'             => trim($row['role'] ?? '') ?: null,
                        'expires_at'       => $expiresAt,
                        'permissions'      => $permissions,
                        'assigned_at'      => now(),
                    ]);
                    $created++;
                }
            }
        });

        return compact('created', 'updated', 'skipped');
    }

    // ── Validation ────────────────────────────────────────────────────────────

    private function validateRow(array $row): array
    {
        $errors      = [];
        $email       = trim($row['email'] ?? '');
        $type        = trim($row['participant_type'] ?? '');
        $expiresAt   = trim($row['expires_at'] ?? '');
        $permissions = trim($row['permissions'] ?? '');

        // ── Email ─────────────────────────────────────────────────────────────
        if (empty($email)) {
            $errors[] = 'Email is required. Please provide a valid email address.';
        } elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format: '{$email}'. Expected format: name@example.com";
        } elseif (! User::where('email', $email)->exists()) {
            $errors[] = "User '{$email}' does not exist on the platform. Please invite this person first (Organisation → Members → Invite) or ask them to register before importing.";
        }

        // ── Participant type ──────────────────────────────────────────────────
        if (empty($type)) {
            $errors[] = 'participant_type is required. Choose one: ' . implode(', ', self::VALID_TYPES);
        } elseif (! in_array($type, self::VALID_TYPES, true)) {
            $errors[] = "Invalid participant_type: '{$type}'. Must be one of: \""
                . implode('", "', self::VALID_TYPES) . "\". "
                . "Use: staff (employees/administrators), guest (temporary participants), election_committee (vote officials/scrutineers).";
        }

        // ── Expiry date ───────────────────────────────────────────────────────
        if (! empty($expiresAt)) {
            $parsed = strtotime($expiresAt);
            if ($parsed === false) {
                $errors[] = "Invalid date format: '{$expiresAt}'. Please use YYYY-MM-DD format (e.g. " . date('Y') . "-12-31).";
            } elseif ($parsed <= strtotime('today')) {
                $errors[] = "expires_at must be a future date. Today is " . date('Y-m-d') . ", provided: {$expiresAt}.";
            }
        }

        // ── Permissions ───────────────────────────────────────────────────────
        if (! empty($permissions)) {
            json_decode($permissions);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = "Invalid JSON in permissions: '{$permissions}'. Example valid JSON: {\"key\":\"value\"}";
            }
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function parseDate(string $value): ?\Carbon\Carbon
    {
        $value = trim($value);

        if (empty($value)) {
            return null;
        }

        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function parsePermissions(string $value): ?array
    {
        $value = trim($value);

        if (empty($value)) {
            return null;
        }

        $decoded = json_decode($value, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : null;
    }
}
