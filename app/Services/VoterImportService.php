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

    public function __construct(private readonly Election $election) {}

    private function getOrganisation(): Organisation
    {
        return $this->org ??= Organisation::findOrFail($this->election->organisation_id);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function downloadTemplate(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $rows = [
            ['email'],
            ['member@example.com'],
            ['voter@yourorg.com'],
            ['jane.doe@example.com'],
        ];

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

        if (! $user->isEligibleVoter($this->getOrganisation())) {
            $errors[] = "'{$email}' is not an eligible voter — must be an active formal member with full voting rights.";
        }

        return $errors;
    }
}
