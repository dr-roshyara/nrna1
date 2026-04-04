I appreciate the mentorship! Yes, I'll continue with the TDD approach. I've written the feature tests for the controller and the unit tests for the job. Now I need to create the implementation that will make these tests pass.

Let me create the necessary files:

## 1. First, let's create the migration for the import jobs table:

```sql
-- migration for member_import_jobs table
CREATE TABLE member_import_jobs (
    id CHAR(36) PRIMARY KEY,
    organisation_id CHAR(36) NOT NULL,
    initiated_by CHAR(36) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    total_rows INT DEFAULT 0,
    processed_rows INT DEFAULT 0,
    imported_count INT DEFAULT 0,
    skipped_count INT DEFAULT 0,
    error_log JSON NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    FOREIGN KEY (initiated_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_organisation_status (organisation_id, status)
);
```

## 2. Create the MemberImportJob model:

```php
<?php
// app/Models/MemberImportJob.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberImportJob extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'organisation_id',
        'initiated_by',
        'file_path',
        'original_filename',
        'status',
        'total_rows',
        'processed_rows',
        'imported_count',
        'skipped_count',
        'error_log',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'error_log' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_rows' => 'integer',
        'processed_rows' => 'integer',
        'imported_count' => 'integer',
        'skipped_count' => 'integer',
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function getProgressAttribute(): int
    {
        if ($this->total_rows === 0) {
            return 0;
        }
        return (int) round(($this->processed_rows / $this->total_rows) * 100);
    }
}
```

## 3. Create the ProcessMemberImportJob:

```php
<?php
// app/Jobs/ProcessMemberImportJob.php

namespace App\Jobs;

use App\Models\User;
use App\Models\MemberImportJob;
use App\Models\Organisation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessMemberImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour for large imports
    public $tries = 3;

    public function __construct(
        public string $importJobId
    ) {}

    public function handle(): void
    {
        $importJob = MemberImportJob::find($this->importJobId);
        
        if (!$importJob) {
            Log::error("Import job not found: {$this->importJobId}");
            return;
        }

        // Mark as processing
        $importJob->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);

        try {
            $this->processImport($importJob);
        } catch (\Exception $e) {
            $importJob->update([
                'status' => 'failed',
                'error_log' => array_merge($importJob->error_log ?? [], [
                    [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'time' => now()->toDateTimeString(),
                    ]
                ]),
                'completed_at' => now(),
            ]);
            
            Log::error("Import job {$this->importJobId} failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function processImport(MemberImportJob $importJob): void
    {
        $filePath = $importJob->file_path;
        
        if (!Storage::disk('local')->exists($filePath)) {
            throw new \Exception("Import file not found: {$filePath}");
        }

        // Read and parse CSV
        $contents = Storage::disk('local')->get($filePath);
        $lines = explode("\n", trim($contents));
        
        if (empty($lines)) {
            throw new \Exception("CSV file is empty");
        }

        // Detect delimiter
        $firstLine = $lines[0];
        $delimiter = $this->detectDelimiter($firstLine);
        
        // Parse headers
        $headers = $this->parseCsvLine($firstLine, $delimiter);
        $normalisedHeaders = array_map([$this, 'normaliseHeader'], $headers);
        
        // Find column indices
        $emailIndex = $this->findColumnIndex($normalisedHeaders, 'email');
        $firstNameIndex = $this->findColumnIndex($normalisedHeaders, ['firstname', 'firstname', 'vorname']);
        $lastNameIndex = $this->findColumnIndex($normalisedHeaders, ['lastname', 'nachname', 'surname']);
        
        if ($emailIndex === false) {
            throw new \Exception("Email column not found in CSV");
        }

        // Count total rows (excluding header)
        $totalRows = count($lines) - 1;
        $importJob->update(['total_rows' => $totalRows]);

        $importedCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Process in chunks of 100 to manage memory
        $chunkSize = 100;
        $chunks = array_chunk(array_slice($lines, 1), $chunkSize);
        
        foreach ($chunks as $chunkIndex => $chunk) {
            DB::transaction(function () use ($chunk, $headers, $emailIndex, $firstNameIndex, $lastNameIndex, $importJob, &$importedCount, &$skippedCount, &$errors, $delimiter) {
                $usersToInsert = [];
                $organisationId = $importJob->organisation_id;
                
                foreach ($chunk as $lineIndex => $line) {
                    $line = trim($line);
                    if (empty($line)) {
                        $skippedCount++;
                        continue;
                    }

                    $values = $this->parseCsvLine($line, $delimiter);
                    
                    // Get email
                    $email = isset($values[$emailIndex]) ? trim($values[$emailIndex]) : '';
                    
                    if (empty($email)) {
                        $skippedCount++;
                        $errors[] = [
                            'row' => ($chunkIndex * 100) + $lineIndex + 2,
                            'email' => 'missing',
                            'reason' => 'Empty email',
                        ];
                        continue;
                    }

                    // Check if user already exists
                    $existingUser = User::where('email', $email)->first();
                    
                    if ($existingUser) {
                        // Just attach to organisation if not already attached
                        $org = Organisation::find($organisationId);
                        if (!$org->users()->where('user_id', $existingUser->id)->exists()) {
                            $org->users()->attach($existingUser->id, ['role' => 'member']);
                            $importedCount++;
                        } else {
                            $skippedCount++;
                            $errors[] = [
                                'row' => ($chunkIndex * 100) + $lineIndex + 2,
                                'email' => $email,
                                'reason' => 'Already a member',
                            ];
                        }
                        continue;
                    }

                    // Get names
                    $firstName = '';
                    $lastName = '';
                    
                    if ($firstNameIndex !== false && isset($values[$firstNameIndex])) {
                        $firstName = trim($values[$firstNameIndex]);
                    }
                    
                    if ($lastNameIndex !== false && isset($values[$lastNameIndex])) {
                        $lastName = trim($values[$lastNameIndex]);
                    }
                    
                    if (empty($firstName) && empty($lastName)) {
                        $nameParts = explode('@', $email);
                        $firstName = $nameParts[0];
                    }

                    // Prepare user data
                    $usersToInsert[] = [
                        'id' => (string) Str::uuid(),
                        'email' => $email,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'name' => trim($firstName . ' ' . $lastName),
                        'email_verified_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert users
                if (!empty($usersToInsert)) {
                    User::insert($usersToInsert);
                    
                    // Attach to organisation
                    $userIds = array_column($usersToInsert, 'id');
                    $org = Organisation::find($organisationId);
                    
                    foreach ($userIds as $userId) {
                        $org->users()->attach($userId, ['role' => 'member']);
                    }
                    
                    $importedCount += count($usersToInsert);
                }
            });

            // Update progress
            $processedRows = min(($chunkIndex + 1) * $chunkSize, $totalRows);
            $importJob->update([
                'processed_rows' => $processedRows,
                'imported_count' => $importedCount,
                'skipped_count' => $skippedCount,
                'error_log' => array_slice($errors, -100), // Keep last 100 errors
            ]);
        }

        // Mark as completed
        $importJob->update([
            'status' => 'completed',
            'completed_at' => now(),
            'imported_count' => $importedCount,
            'skipped_count' => $skippedCount,
            'error_log' => $errors,
        ]);

        // Delete the file
        Storage::disk('local')->delete($filePath);
    }

    private function detectDelimiter(string $line): string
    {
        $commaCount = substr_count($line, ',');
        $semicolonCount = substr_count($line, ';');
        return $semicolonCount > $commaCount ? ';' : ',';
    }

    private function parseCsvLine(string $line, string $delimiter = ','): array
    {
        $result = [];
        $current = '';
        $insideQuotes = false;
        $length = strlen($line);
        
        for ($i = 0; $i < $length; $i++) {
            $char = $line[$i];
            
            if ($char === '"') {
                $insideQuotes = !$insideQuotes;
            } elseif ($char === $delimiter && !$insideQuotes) {
                $result[] = trim($current);
                $current = '';
            } else {
                $current .= $char;
            }
        }
        
        $result[] = trim($current);
        return $result;
    }

    private function normaliseHeader(string $header): string
    {
        return strtolower(preg_replace('/[-_\s]/', '', trim($header)));
    }

    private function findColumnIndex(array $headers, $search): int|false
    {
        $searches = is_array($search) ? $search : [$search];
        
        foreach ($searches as $needle) {
            $index = array_search($needle, $headers);
            if ($index !== false) {
                return $index;
            }
        }
        
        return false;
    }
}
```

## 4. Update the MemberImportController:

```php
<?php
// app/Http/Controllers/Organisations/MemberImportController.php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Models\MemberImportJob;
use App\Models\Organisation;
use App\Jobs\ProcessMemberImportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemberImportController extends Controller
{
    public function store(Request $request, Organisation $organisation)
    {
        $this->authorize('update', $organisation);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        
        // Store the file
        $path = $file->storeAs(
            "imports/{$organisation->id}",
            time() . '_' . $file->getClientOriginalName(),
            'local'
        );

        // Create import job record
        $importJob = MemberImportJob::create([
            'organisation_id' => $organisation->id,
            'initiated_by' => auth()->id(),
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        // Dispatch job
        ProcessMemberImportJob::dispatch($importJob->id);

        return response()->json([
            'job_id' => $importJob->id,
            'status_url' => route('organisations.members.import.status', [
                'organisation' => $organisation->slug,
                'job' => $importJob->id,
            ]),
        ], 202);
    }

    public function status(Organisation $organisation, MemberImportJob $job)
    {
        // Ensure job belongs to this organisation
        if ($job->organisation_id !== $organisation->id) {
            abort(403);
        }

        return response()->json([
            'status' => $job->status,
            'progress' => $job->progress,
            'total_rows' => $job->total_rows,
            'processed_rows' => $job->processed_rows,
            'imported_count' => $job->imported_count,
            'skipped_count' => $job->skipped_count,
            'error_log' => $job->error_log,
            'created_at' => $job->created_at,
            'started_at' => $job->started_at,
            'completed_at' => $job->completed_at,
        ]);
    }
}
```

## 5. Add routes:

```php
// routes/web.php or routes/api.php

Route::post('/organisations/{organisation}/members/import', [MemberImportController::class, 'store'])
    ->name('organisations.members.import.store');
Route::get('/organisations/{organisation}/members/import/{job}/status', [MemberImportController::class, 'status'])
    ->name('organisations.members.import.status');
```

## 6. Update the frontend composable to handle async import:

```javascript
// resources/js/composables/useMemberImport.js - add status checking

const checkImportStatus = async (jobId) => {
  try {
    const response = await csrfRequest.get(
      `/organisations/${organisation.value.slug}/members/import/${jobId}/status`
    );
    return response.data;
  } catch (error) {
    console.error('Failed to check import status:', error);
    throw error;
  }
};

const pollImportStatus = async (jobId, onProgress, onComplete, onError) => {
  let completed = false;
  let attempts = 0;
  const maxAttempts = 360; // 1 hour (10 sec * 360)
  
  while (!completed && attempts < maxAttempts) {
    try {
      const status = await checkImportStatus(jobId);
      
      if (onProgress) {
        onProgress(status);
      }
      
      if (status.status === 'completed') {
        completed = true;
        if (onComplete) {
          onComplete(status);
        }
        break;
      } else if (status.status === 'failed') {
        completed = true;
        if (onError) {
          onError(new Error(status.error_log?.[0]?.message || 'Import failed'));
        }
        break;
      }
      
      // Wait 10 seconds before next poll
      await new Promise(resolve => setTimeout(resolve, 10000));
      attempts++;
      
    } catch (error) {
      if (onError) {
        onError(error);
      }
      break;
    }
  }
  
  if (attempts >= maxAttempts) {
    if (onError) {
      onError(new Error('Import timed out'));
    }
  }
};

// Update submitImport to handle file upload only
const submitImport = async (file) => {
  try {
    const formData = new FormData();
    formData.append('file', file);
    
    const response = await csrfRequest.post(
      `/organisations/${organisation.value.slug}/members/import`,
      formData,
      {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      }
    );
    
    return response.data; // { job_id, status_url }
  } catch (error) {
    console.error('Import submission failed:', error);
    throw error;
  }
};
```

This architecture now:
1. Handles files up to 10MB (configurable)
2. Processes up to 50,000 members in chunks of 100
3. Uses queue jobs to prevent timeouts
4. Provides real-time progress updates
5. Handles duplicate emails gracefully
6. Supports semicolon-delimited CSV with E-Mail column
7. Deletes files after processing
8. Maintains error logs for failed rows

The tests I wrote should now pass with this implementation. Would you like me to run the tests or make any adjustments?