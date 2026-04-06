<?php

namespace App\Jobs;

use App\Models\MemberImportJob;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessMemberImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600; // 1 hour ceiling for 50k-row files
    public int $tries   = 1;    // No retry — partial imports are dangerous

    public function __construct(public readonly string $importJobId) {}

    public function handle(): void
    {
        $importJob = MemberImportJob::find($this->importJobId);

        if (!$importJob) {
            return;
        }

        $importJob->update(['status' => 'processing', 'started_at' => now()]);

        try {
            $this->process($importJob);
        } catch (\Throwable $e) {
            $importJob->markFailed($e->getMessage());
        }
    }

    public function failed(\Throwable $e): void
    {
        MemberImportJob::find($this->importJobId)?->markFailed($e->getMessage());
    }

    // ── Core processing ──────────────────────────────────────────────────────

    private function process(MemberImportJob $importJob): void
    {
        $path = $importJob->file_path;

        if (!Storage::disk('local')->exists($path)) {
            $importJob->markFailed("Import file not found: {$path}");
            return;
        }

        $fullPath  = Storage::disk('local')->path($path);
        $handle    = fopen($fullPath, 'r');
        $delimiter = $this->detectDelimiter(fgets($handle));
        rewind($handle);

        // First pass: read headers
        $rawHeaders = fgetcsv($handle, 0, $delimiter);
        if (!$rawHeaders) {
            $importJob->markFailed('CSV file is empty or unreadable.');
            fclose($handle);
            return;
        }

        $normHeaders  = array_map([$this, 'normalise'], $rawHeaders);
        $emailIdx     = $this->findIdx($normHeaders, ['email']);
        $firstNameIdx = $this->findIdx($normHeaders, ['firstname', 'vorname', 'givenname']);
        $lastNameIdx  = $this->findIdx($normHeaders, ['lastname', 'nachname', 'surname', 'familyname']);
        $memberNoIdx  = $this->findIdx($normHeaders, ['membershipnumber', 'membernumber', 'number']);
        $joinedAtIdx  = $this->findIdx($normHeaders, ['joinedat', 'joined', 'joindate']);
        $statusIdx    = $this->findIdx($normHeaders, ['status']);
        $feesIdx      = $this->findIdx($normHeaders, ['feesstatus', 'fees', 'feestatus']);
        $expiresIdx   = $this->findIdx($normHeaders, ['expiresat', 'expires', 'expirydate', 'expiry']);

        if ($emailIdx === false) {
            $importJob->markFailed('Email column not found in CSV.');
            fclose($handle);
            return;
        }

        // Count rows for progress (second pass)
        $totalRows = 0;
        while (fgetcsv($handle, 0, $delimiter) !== false) {
            $totalRows++;
        }
        rewind($handle);
        fgetcsv($handle, 0, $delimiter); // skip header again
        $importJob->update(['total_rows' => $totalRows]);

        // Third pass: process in chunks of 500
        $org       = Organisation::find($importJob->organisation_id);
        $chunkSize = 500;
        $chunk     = [];
        $imported  = 0;
        $skipped   = 0;
        $errors    = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $email = isset($row[$emailIdx]) ? trim($row[$emailIdx]) : '';

            if ($email === '') {
                $skipped++;
                $rowNumber++;
                continue;
            }

            $firstName = ($firstNameIdx !== false && isset($row[$firstNameIdx])) ? trim($row[$firstNameIdx]) : '';
            $lastName  = ($lastNameIdx  !== false && isset($row[$lastNameIdx]))  ? trim($row[$lastNameIdx])  : '';

            if ($firstName === '' && $lastName === '') {
                $firstName = Str::before($email, '@');
            }

            $chunk[] = [
                'rowNumber'        => $rowNumber,
                'email'            => $email,
                'firstName'        => $firstName,
                'lastName'         => $lastName,
                'membershipNumber' => $memberNoIdx !== false ? trim($row[$memberNoIdx] ?? '') : '',
                'joinedAt'         => $joinedAtIdx  !== false ? trim($row[$joinedAtIdx]  ?? '') : '',
                'status'           => $statusIdx    !== false ? trim($row[$statusIdx]    ?? '') : '',
                'feesStatus'       => $feesIdx      !== false ? trim($row[$feesIdx]      ?? '') : '',
                'expiresAt'        => $expiresIdx   !== false ? trim($row[$expiresIdx]   ?? '') : '',
            ];
            $rowNumber++;

            if (count($chunk) >= $chunkSize) {
                [$batchImported, $batchSkipped, $batchErrors] = $this->insertChunk($chunk, $org, $importJob->initiated_by);
                $imported += $batchImported;
                $skipped  += $batchSkipped;
                $errors    = array_merge($errors, $batchErrors);
                $chunk     = [];

                $importJob->update([
                    'processed_rows' => $imported + $skipped,
                    'imported_count' => $imported,
                    'skipped_count'  => $skipped,
                    'error_log'      => array_slice($errors, -200),
                ]);
            }
        }

        // Final partial chunk
        if (!empty($chunk)) {
            [$batchImported, $batchSkipped, $batchErrors] = $this->insertChunk($chunk, $org, $importJob->initiated_by);
            $imported += $batchImported;
            $skipped  += $batchSkipped;
            $errors    = array_merge($errors, $batchErrors);
        }

        fclose($handle);
        Storage::disk('local')->delete($path);

        $importJob->update([
            'status'         => 'completed',
            'completed_at'   => now(),
            'processed_rows' => $imported + $skipped,
            'imported_count' => $imported,
            'skipped_count'  => $skipped,
            'error_log'      => array_slice($errors, -200),
        ]);
    }

    /**
     * Bulk-insert one chunk — creates users, user_organisation_roles,
     * organisation_users, and members records.
     * Returns [imported, skipped, errors].
     */
    private function insertChunk(array $chunk, Organisation $org, string $initiatedBy): array
    {
        $emails   = array_column($chunk, 'email');
        $existing = User::whereIn('email', $emails)->pluck('email')->flip()->all();

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // Resolve default voting membership type for the org (used when none specified in CSV)
        $defaultType = MembershipType::where('organisation_id', $org->id)
            ->where('grants_voting_rights', true)
            ->first();

        DB::transaction(function () use (
            $chunk, $org, $existing, $initiatedBy, $defaultType,
            &$imported, &$skipped, &$errors
        ) {
            foreach ($chunk as $row) {
                if (isset($existing[$row['email']])) {
                    $skipped++;
                    $errors[] = ['row' => $row['rowNumber'], 'email' => $row['email'], 'reason' => 'already exists'];
                    continue;
                }

                $userId = (string) Str::uuid();
                $name   = trim("{$row['firstName']} {$row['lastName']}") ?: $row['email'];

                // 1. Create user
                DB::table('users')->insert([
                    'id'                => $userId,
                    'organisation_id'   => $org->id,
                    'name'              => $name,
                    'first_name'        => $row['firstName'] ?: null,
                    'last_name'         => $row['lastName']  ?: null,
                    'region'            => '',
                    'email'             => $row['email'],
                    'password'          => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                // 2. Create user_organisation_roles (required FK for election_memberships)
                DB::table('user_organisation_roles')->insert([
                    'id'              => (string) Str::uuid(),
                    'user_id'         => $userId,
                    'organisation_id' => $org->id,
                    'role'            => 'voter',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // 3. Create organisation_users
                $orgUserId = (string) Str::uuid();
                DB::table('organisation_users')->insert([
                    'id'              => $orgUserId,
                    'user_id'         => $userId,
                    'organisation_id' => $org->id,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // 4. Create member record
                $membershipNumber = $row['membershipNumber'] ?: ('M' . strtoupper(Str::random(8)));

                $status = in_array($row['status'], ['active', 'expired', 'suspended', 'ended'])
                    ? $row['status'] : 'active';

                $feesStatus = in_array($row['feesStatus'], ['paid', 'unpaid', 'partial', 'exempt'])
                    ? $row['feesStatus'] : 'unpaid';

                $joinedAt  = $this->parseDate($row['joinedAt']) ?? now();
                $expiresAt = $this->parseDate($row['expiresAt']);

                DB::table('members')->insert([
                    'id'                    => (string) Str::uuid(),
                    'organisation_id'       => $org->id,
                    'organisation_user_id'  => $orgUserId,
                    'membership_type_id'    => $defaultType?->id,
                    'membership_number'     => $membershipNumber,
                    'status'                => $status,
                    'fees_status'           => $feesStatus,
                    'joined_at'             => $joinedAt,
                    'membership_expires_at' => $expiresAt,
                    'created_by'            => $initiatedBy,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

                $imported++;
            }
        });

        return [$imported, $skipped, $errors];
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function parseDate(string $value): ?Carbon
    {
        if ($value === '') {
            return null;
        }
        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function detectDelimiter(string $line): string
    {
        return substr_count($line, ';') > substr_count($line, ',') ? ';' : ',';
    }

    private function normalise(string $header): string
    {
        return strtolower(preg_replace('/[-_\s]/', '', trim($header)));
    }

    private function findIdx(array $normHeaders, array $candidates): int|false
    {
        foreach ($candidates as $c) {
            $idx = array_search($c, $normHeaders, true);
            if ($idx !== false) {
                return $idx;
            }
        }
        return false;
    }
}
