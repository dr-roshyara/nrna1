<?php

namespace App\Jobs;

use App\Contexts\Membership\Application\Commands\MemberImportCommand;
use App\Contexts\Membership\Application\Services\MemberImportService;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\MemberImportJob;
use App\Models\MembershipType;
use App\Models\Organisation;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessMemberImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600; // 1 hour ceiling for 50k-row files
    public int $tries   = 1;    // No retry — partial imports are dangerous

    public function __construct(
        public readonly string $importJobId,
    ) {}

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

    private function getImportService(): MemberImportService
    {
        return app(MemberImportService::class);
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
                [$batchImported, $batchSkipped, $batchErrors] = $this->processChunk($chunk, $org);
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
            [$batchImported, $batchSkipped, $batchErrors] = $this->processChunk($chunk, $org);
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
     * Process one chunk via DDD MemberImportService
     * Returns [imported, skipped, errors].
     */
    private function processChunk(array $chunk, Organisation $org): array
    {
        $commands = array_map(function ($row) {
            return new MemberImportCommand(
                email: $row['email'],
                firstName: $row['firstName'],
                lastName: $row['lastName'],
                membershipTypeId: null,
                geoUnitId: null,
            );
        }, $chunk);

        $result = $this->getImportService()->import(
            TenantId::fromString($org->id),
            $commands,
            MembershipTypeId::fromString($this->getDefaultMembershipTypeId($org))
        );

        return [$result->imported, $result->skipped, $result->errors];
    }

    /**
     * Get the default membership type ID for the organisation
     * Falls back to global membership type if organisation-specific one doesn't exist
     */
    private function getDefaultMembershipTypeId(Organisation $org): string
    {
        // First, try organisation-specific membership type
        $type = MembershipType::where('organisation_id', $org->id)
            ->where('grants_voting_rights', true)
            ->first();

        // Fall back to global membership type
        if (!$type) {
            $type = MembershipType::whereNull('organisation_id')
                ->where('grants_voting_rights', true)
                ->first();
        }

        if (!$type) {
            throw new \RuntimeException("No default membership type found for organisation {$org->id}");
        }

        return $type->id;
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
