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
        $emails = array_column($chunk, 'email');

        // Map email → existing User (platform-wide)
        $existingUsers = User::whereIn('email', $emails)
            ->select('id', 'email')
            ->get()
            ->keyBy('email');

        // Map email → existing member record for THIS org (for update path)
        $existingMembers = DB::table('members')
            ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
            ->join('users', 'organisation_users.user_id', '=', 'users.id')
            ->where('members.organisation_id', $org->id)
            ->whereIn('users.email', $emails)
            ->select('users.email', 'members.id as member_id')
            ->get()
            ->keyBy('email');

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = [];

        // Resolve default voting membership type for the org (used when none specified in CSV)
        $defaultType = MembershipType::where('organisation_id', $org->id)
            ->where('grants_voting_rights', true)
            ->first();

        DB::transaction(function () use (
            $chunk, $org, $existingUsers, $existingMembers, $initiatedBy, $defaultType,
            &$imported, &$updated, &$skipped, &$errors
        ) {
            foreach ($chunk as $row) {
                $status = in_array($row['status'], ['active', 'expired', 'suspended', 'ended'])
                    ? $row['status'] : 'active';

                $feesStatus = in_array($row['feesStatus'], ['paid', 'unpaid', 'partial', 'exempt'])
                    ? $row['feesStatus'] : 'unpaid';

                $joinedAt  = $this->parseDate($row['joinedAt']) ?? now();
                $expiresAt = $this->parseDate($row['expiresAt']);

                // ── UPDATE path: member already exists in this org ────────────
                if (isset($existingMembers[$row['email']])) {
                    $memberId = $existingMembers[$row['email']]->member_id;

                    $updateData = ['status' => $status, 'fees_status' => $feesStatus, 'updated_at' => now()];

                    // Only overwrite expires_at / joined_at when the CSV provides a value
                    if ($expiresAt !== null) {
                        $updateData['membership_expires_at'] = $expiresAt;
                    }
                    if (!empty($row['joinedAt'])) {
                        $updateData['joined_at'] = $joinedAt;
                    }
                    if (!empty($row['membershipNumber'])) {
                        $updateData['membership_number'] = $row['membershipNumber'];
                    }

                    DB::table('members')->where('id', $memberId)->update($updateData);
                    $updated++;
                    continue;
                }

                // ── CREATE path: new member ───────────────────────────────────
                $name = trim("{$row['firstName']} {$row['lastName']}") ?: $row['email'];

                // Reuse existing platform user or create a new one
                if (isset($existingUsers[$row['email']])) {
                    $userId = $existingUsers[$row['email']]->id;
                } else {
                    $userId = (string) Str::uuid();

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
                }

                // user_organisation_roles (idempotent)
                $hasOrgRole = DB::table('user_organisation_roles')
                    ->where('user_id', $userId)
                    ->where('organisation_id', $org->id)
                    ->exists();

                if (!$hasOrgRole) {
                    DB::table('user_organisation_roles')->insert([
                        'id'              => (string) Str::uuid(),
                        'user_id'         => $userId,
                        'organisation_id' => $org->id,
                        'role'            => 'voter',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }

                // organisation_users (idempotent)
                $orgUserId = DB::table('organisation_users')
                    ->where('user_id', $userId)
                    ->where('organisation_id', $org->id)
                    ->value('id');

                if (!$orgUserId) {
                    $orgUserId = (string) Str::uuid();
                    DB::table('organisation_users')->insert([
                        'id'              => $orgUserId,
                        'user_id'         => $userId,
                        'organisation_id' => $org->id,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }

                // members record
                $membershipNumber = $row['membershipNumber'] ?: ('M' . strtoupper(Str::random(8)));

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

        return [$imported + $updated, $skipped, $errors];
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
