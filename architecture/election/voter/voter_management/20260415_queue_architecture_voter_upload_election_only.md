## Complete Architecture: Voter Import with Auto-Registration & Queued Invitations

### Overview

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         VOTER IMPORT SYSTEM — COMPLETE ARCHITECTURE                    │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                       │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────────────────┐│
│  │   ADMIN     │───▶│   IMPORT    │───▶│   QUEUE     │───▶│        VOTER            ││
│  │   Upload    │    │  Processing │    │   Workers   │    │   Receives Email        ││
│  │   CSV       │    │             │    │             │    │   Sets Password         ││
│  └─────────────┘    └─────────────┘    └─────────────┘    └─────────────────────────┘│
│                                                                                       │
│  Time: < 1 second      < 1 second        Background          When voter clicks        │
│                        (DB writes)       (email sends)        link in email            │
│                                                                                       │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### Phase 1: Database Schema

```php
// database/migrations/xxxx_xx_xx_create_voter_invitations_table.php
Schema::create('voter_invitations', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->string('token', 64)->unique();
    $table->string('email_status')->default('pending'); // pending, sent, failed
    $table->text('email_error')->nullable();
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('used_at')->nullable();
    $table->timestamp('expires_at');
    $table->timestamps();
    
    $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
    
    $table->unique(['election_id', 'user_id']);
    $table->index(['email_status', 'expires_at']);
});

// database/migrations/xxxx_xx_xx_add_language_to_organisations.php
Schema::table('organisations', function (Blueprint $table) {
    $table->string('language')->default('de')->after('type'); // de, en, np
});
```

### Phase 2: Models

```php
// app/Models/VoterInvitation.php
class VoterInvitation extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'election_id', 'user_id', 'organisation_id',
        'token', 'email_status', 'email_error',
        'sent_at', 'used_at', 'expires_at',
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }
    
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
    
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }
    
    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }
}
```

### Phase 3: VoterImportService (Core Logic)

```php
// app/Services/VoterImportService.php
namespace App\Services;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\VoterInvitation;
use App\Jobs\SendVoterInvitation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class VoterImportService
{
    private const BATCH_SIZE = 100;
    
    public function __construct(
        private Election $election,
        private ?VoterEligibilityService $eligibilityService = null
    ) {}
    
    /**
     * Parse CSV with format: firstname;lastname;email
     */
    private function parseRow(array $row): array
    {
        // Handle both comma and semicolon separators
        if (count($row) === 1 && str_contains($row[0], ';')) {
            $row = explode(';', $row[0]);
        }
        
        return [
            'firstname' => trim($row[0] ?? ''),
            'lastname'  => trim($row[1] ?? ''),
            'email'     => trim($row[2] ?? ''),
        ];
    }
    
    /**
     * Preview import - validate without creating records
     */
    public function preview(UploadedFile $file): array
    {
        $rows = $this->parseFile($file);
        $preview = [];
        $stats = ['new' => 0, 'existing' => 0, 'invalid' => 0];
        
        foreach ($rows as $index => $row) {
            $data = $this->parseRow($row);
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
    
    /**
     * Import voters - create users and dispatch invitation jobs
     */
    public function import(UploadedFile $file): array
    {
        $rows = $this->parseFile($file);
        $results = [
            'created' => 0,
            'existing' => 0,
            'errors' => [],
            'invitations' => 0,
        ];
        
        $organisation = $this->election->organisation;
        
        foreach ($rows as $index => $row) {
            $data = $this->parseRow($row);
            $email = $data['email'];
            
            // Validation
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $results['errors'][] = "Row {$index}: Invalid email - {$email}";
                continue;
            }
            
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
                    'firstname' => $data['firstname'] ?: null,
                    'lastname' => $data['lastname'] ?: null,
                    'password' => bcrypt(Str::random(32)),
                    'organisation_id' => $organisation->id,
                ]
            );
            
            $isNewUser = $user->wasRecentlyCreated;
            
            // Link to organisation
            OrganisationUser::firstOrCreate(
                [
                    'organisation_id' => $organisation->id,
                    'user_id' => $user->id,
                ],
                ['status' => 'active']
            );
            
            // Assign to election (skip if already assigned)
            $membership = ElectionMembership::firstOrCreate(
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
                $invitation = VoterInvitation::firstOrCreate(
                    [
                        'election_id' => $this->election->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'organisation_id' => $organisation->id,
                        'token' => Str::random(64),
                        'email_status' => 'pending',
                        'expires_at' => now()->addDays(7),
                    ]
                );
                
                // Only dispatch if newly created and not already sent
                if ($invitation->wasRecentlyCreated) {
                    SendVoterInvitation::dispatch($invitation);
                    $results['invitations']++;
                }
            }
            
            $results[$isNewUser ? 'created' : 'existing']++;
        }
        
        return $results;
    }
    
    private function parseFile(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $rows = [];
        
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip header row
            fgetcsv($handle, 0, ';');
            
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (!empty($row[0]) || !empty($row[1]) || !empty($row[2])) {
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }
        
        return $rows;
    }
    
    public function downloadTemplate(): BinaryFileResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="voter_import_template.csv"',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            fputcsv($file, ['firstname', 'lastname', 'email'], ';');
            fputcsv($file, ['Niraj', 'Adhikari', 'niraj@example.com'], ';');
            fputcsv($file, ['John', 'Doe', 'john@example.com'], ';');
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
```

### Phase 4: Queued Job

```php
// app/Jobs/SendVoterInvitation.php
namespace App\Jobs;

use App\Models\VoterInvitation;
use App\Mail\VoterInvitationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $backoff = [30, 60, 120];
    
    public function __construct(private VoterInvitation $invitation) {}
    
    public function handle(): void
    {
        // Skip if already sent
        if ($this->invitation->email_status === 'sent') {
            return;
        }
        
        try {
            $user = $this->invitation->user;
            $election = $this->invitation->election;
            $organisation = $election->organisation;
            
            $lang = $organisation->language ?? 'de';
            
            $content = $this->getLocalizedContent($lang, [
                'fullname' => $user->name,
                'election_name' => $election->name,
                'organisation_name' => $organisation->name,
            ]);
            
            $resetUrl = url("/invitation/{$this->invitation->token}");
            
            Mail::to($user->email)
                ->locale($lang)
                ->send(new VoterInvitationMail(
                    content: $content,
                    resetUrl: $resetUrl,
                    lang: $lang
                ));
            
            $this->invitation->update([
                'email_status' => 'sent',
                'sent_at' => now(),
                'email_error' => null,
            ]);
            
        } catch (\Exception $e) {
            $this->invitation->update([
                'email_status' => 'failed',
                'email_error' => $e->getMessage(),
            ]);
            
            throw $e; // Retry
        }
    }
    
    private function getLocalizedContent(string $lang, array $data): array
    {
        return match ($lang) {
            'en' => [
                'subject' => 'You have been invited to vote: ' . $data['election_name'],
                'greeting' => 'Hello ' . $data['fullname'] . ',',
                'body' => 'You have been registered as a voter for the following election:',
                'election_label' => 'Election:',
                'organisation_label' => 'Organisation:',
                'button_text' => 'Set Password & Vote',
                'expiry_note' => 'This link is valid for 7 days.',
                'ignore_note' => 'If you did not expect this invitation, please ignore this email.',
            ],
            'np' => [
                'subject' => 'तपाईंलाई मतदानको लागि आमन्त्रित गरिएको छ: ' . $data['election_name'],
                'greeting' => 'नमस्ते ' . $data['fullname'] . ',',
                'body' => 'तपाईंलाई निम्न निर्वाचनको लागि मतदाताको रूपमा दर्ता गरिएको छ:',
                'election_label' => 'निर्वाचन:',
                'organisation_label' => 'संगठन:',
                'button_text' => 'पासवर्ड सेट गर्नुहोस् र मतदान गर्नुहोस्',
                'expiry_note' => 'यो लिङ्क ७ दिनको लागि मान्य छ।',
                'ignore_note' => 'यदि तपाईंले यो आमन्त्रणको अपेक्षा गर्नुभएको थिएन भने, कृपया यो इमेललाई बेवास्ता गर्नुहोस्।',
            ],
            default => [
                'subject' => 'Sie wurden zur Wahl eingeladen: ' . $data['election_name'],
                'greeting' => 'Hallo ' . $data['fullname'] . ',',
                'body' => 'Sie wurden als Wähler für die folgende Wahl registriert:',
                'election_label' => 'Wahl:',
                'organisation_label' => 'Organisation:',
                'button_text' => 'Passwort festlegen & Abstimmen',
                'expiry_note' => 'Dieser Link ist 7 Tage gültig.',
                'ignore_note' => 'Falls Sie diese Einladung nicht erwartet haben, ignorieren Sie bitte diese E-Mail.',
            ],
        };
    }
}
```

### Phase 5: Controller

```php
// app/Http/Controllers/Election/VoterImportController.php
namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Services\VoterImportService;
use Illuminate\Http\Request;

class VoterImportController extends Controller
{
    public function create(Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);
        
        return Inertia::render('Elections/Voters/Import', [
            'organisation' => $organisation,
            'election' => $election,
        ]);
    }
    
    public function preview(Request $request, Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);
        
        $service = new VoterImportService($election);
        $result = $service->preview($request->file('file'));
        
        return response()->json($result);
    }
    
    public function import(Request $request, Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);
        
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'confirmed' => 'required|accepted',
        ]);
        
        $service = new VoterImportService($election);
        $result = $service->import($request->file('file'));
        
        $message = sprintf(
            'Import completed: %d created, %d existing, %d invitations sent.',
            $result['created'],
            $result['existing'],
            $result['invitations']
        );
        
        if (!empty($result['errors'])) {
            $message .= ' ' . count($result['errors']) . ' errors.';
        }
        
        return redirect()
            ->route('elections.voters.index', [$organisation->slug, $election->slug])
            ->with('success', $message);
    }
    
    public function template(Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);
        
        $service = new VoterImportService($election);
        return $service->downloadTemplate();
    }
    
    public function progress(Organisation $organisation, Election $election)
    {
        $this->authorize('manageVoters', $election);
        
        $stats = \App\Models\VoterInvitation::where('election_id', $election->id)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN email_status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN email_status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN email_status = "failed" THEN 1 ELSE 0 END) as failed
            ')
            ->first();
            
        return response()->json($stats);
    }
}
```

### Phase 6: Invitation Controller (Public)

```php
// app/Http/Controllers/VoterInvitationController.php
namespace App\Http\Controllers;

use App\Models\VoterInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VoterInvitationController extends Controller
{
    public function showSetPassword(string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->with(['user', 'election', 'organisation'])
            ->firstOrFail();
            
        return Inertia::render('Auth/SetPassword', [
            'token' => $token,
            'email' => $invitation->user->email,
            'name' => $invitation->user->name,
            'election' => $invitation->election->name,
            'organisation' => $invitation->organisation->name,
        ]);
    }
    
    public function setPassword(Request $request, string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->with(['user', 'election'])
            ->firstOrFail();
            
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = $invitation->user;
        $user->update([
            'password' => bcrypt($request->password),
            'email_verified_at' => now(),
        ]);
        
        $invitation->update(['used_at' => now()]);
        
        Auth::login($user);
        
        return redirect()->route('elections.show', [
            'organisation' => $invitation->organisation->slug,
            'election' => $invitation->election->slug,
        ]);
    }
}
```

### Phase 7: Routes

```php
// routes/organisations.php
Route::prefix('/elections/{election:slug}')->group(function () {
    Route::prefix('/voters')->name('elections.voters.')->group(function () {
        // Import routes
        Route::get('/import', [VoterImportController::class, 'create'])
            ->name('import.create');
        Route::post('/import/preview', [VoterImportController::class, 'preview'])
            ->name('import.preview');
        Route::post('/import', [VoterImportController::class, 'import'])
            ->name('import');
        Route::get('/import/template', [VoterImportController::class, 'template'])
            ->name('import.template');
        Route::get('/import/progress', [VoterImportController::class, 'progress'])
            ->name('import.progress');
    });
});

// routes/web.php (public)
Route::get('/invitation/{token}', [VoterInvitationController::class, 'showSetPassword'])
    ->name('invitation.set-password');
Route::post('/invitation/{token}', [VoterInvitationController::class, 'setPassword'])
    ->name('invitation.store-password');
```

### Phase 8: Queue Configuration

```env
# .env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:table
php artisan migrate
```

### Summary

| Component | File | Purpose |
|-----------|------|---------|
| Migration | `create_voter_invitations_table` | Invitation tracking |
| Migration | `add_language_to_organisations` | Email language |
| Model | `VoterInvitation` | Invitation data |
| Service | `VoterImportService` | CSV parsing, user creation |
| Job | `SendVoterInvitation` | Queued email delivery |
| Controller | `VoterImportController` | Admin import UI |
| Controller | `VoterInvitationController` | Public password set |
| Routes | `organisations.php` + `web.php` | Endpoints |
| Queue | Database driver | Background processing |

**Om Gam Ganapataye Namah** 🪔🐘

This is the complete, production-ready architecture for voter import with auto-registration and queued email invitations.
## Auto-Start Queue Worker During Import

Yes! Use Laravel's `queue:work` with `--stop-when-empty` to automatically start and stop a worker for the import job.

### Option 1: Sync Dispatch with Queue Worker (Simplest)

```php
// app/Services/VoterImportService.php

public function import(UploadedFile $file): array
{
    $rows = $this->parseFile($file);
    $results = [
        'created' => 0,
        'existing' => 0,
        'errors' => [],
        'invitations' => 0,
    ];
    
    $organisation = $this->election->organisation;
    $invitations = [];
    
    foreach ($rows as $index => $row) {
        // ... create user and invitation logic
        
        if ($shouldSendInvitation) {
            $invitations[] = $invitation;
        }
    }
    
    // Dispatch all invitations as a batch
    if (!empty($invitations)) {
        // Option A: Dispatch individual jobs (they queue)
        foreach ($invitations as $invitation) {
            SendVoterInvitation::dispatch($invitation);
        }
        
        // Option B: Start a worker process that stops when empty
        if ($this->shouldStartWorker()) {
            $this->startQueueWorker();
        }
    }
    
    return $results;
}

private function shouldStartWorker(): bool
{
    // Check if queue worker is already running
    $output = shell_exec('ps aux | grep "queue:work" | grep -v grep');
    return empty($output);
}

private function startQueueWorker(): void
{
    $command = PHP_BINARY . ' ' . base_path('artisan') . ' queue:work --stop-when-empty --timeout=300 > /dev/null 2>&1 &';
    
    if (PHP_OS_FAMILY === 'Windows') {
        pclose(popen('start /B ' . $command, 'r'));
    } else {
        exec($command);
    }
}
```

### Option 2: Synchronous Processing for Small Batches

```php
// app/Services/VoterImportService.php

public function import(UploadedFile $file): array
{
    $rows = $this->parseFile($file);
    $invitationCount = 0;
    
    // ... create users logic
    
    // If less than 50 invitations, send synchronously
    if ($invitationCount <= 50) {
        foreach ($invitations as $invitation) {
            SendVoterInvitation::dispatchSync($invitation);
        }
    } else {
        // Large batch: dispatch to queue and start worker
        foreach ($invitations as $invitation) {
            SendVoterInvitation::dispatch($invitation);
        }
        $this->startQueueWorker();
    }
    
    return $results;
}
```

### Option 3: Use Laravel Bus Batch (Recommended)

```php
// app/Services/VoterImportService.php

use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use App\Jobs\SendVoterInvitation;

public function import(UploadedFile $file): array
{
    // ... create users and invitations logic
    
    $jobs = [];
    foreach ($invitations as $invitation) {
        $jobs[] = new SendVoterInvitation($invitation);
    }
    
    if (!empty($jobs)) {
        $batch = Bus::batch($jobs)
            ->then(function (Batch $batch) {
                // All jobs completed
                \Log::info('Voter invitations batch completed', [
                    'election_id' => $this->election->id,
                    'total' => $batch->totalJobs,
                ]);
            })
            ->catch(function (Batch $batch, \Throwable $e) {
                // First failure
                \Log::error('Voter invitations batch failed', [
                    'election_id' => $this->election->id,
                    'error' => $e->getMessage(),
                ]);
            })
            ->finally(function (Batch $batch) {
                // Always runs
            })
            ->name('Voter Import: ' . $this->election->name)
            ->dispatch();
            
        // Start worker for this batch
        $this->ensureWorkerRunning();
    }
    
    return $results;
}

private function ensureWorkerRunning(): void
{
    $cacheKey = 'queue_worker_' . $this->election->id;
    
    if (!\Cache::has($cacheKey)) {
        \Cache::put($cacheKey, true, 3600); // 1 hour
        
        $command = PHP_BINARY . ' ' . base_path('artisan') . ' queue:work --stop-when-empty --timeout=600';
        
        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen('start /B ' . $command, 'r'));
        } else {
            exec($command . ' > /dev/null 2>&1 &');
        }
    }
}
```

### Option 4: Artisan Command for Import (Most Robust)

```php
// app/Console/Commands/ImportVotersCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Election;
use App\Services\VoterImportService;
use Illuminate\Http\UploadedFile;

class ImportVotersCommand extends Command
{
    protected $signature = 'voters:import 
                            {election : Election ID} 
                            {file : Path to CSV file}
                            {--queue : Process invitations via queue}';
    
    protected $description = 'Import voters from CSV file';
    
    public function handle(): int
    {
        $election = Election::findOrFail($this->argument('election'));
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error('File not found: ' . $filePath);
            return self::FAILURE;
        }
        
        $file = new UploadedFile(
            $filePath,
            basename($filePath),
            'text/csv',
            null,
            true
        );
        
        $service = new VoterImportService($election);
        $result = $service->import($file);
        
        $this->info(sprintf(
            'Import completed: %d created, %d existing, %d invitations.',
            $result['created'],
            $result['existing'],
            $result['invitations']
        ));
        
        if ($this->option('queue')) {
            $this->info('Invitations queued. Run: php artisan queue:work --stop-when-empty');
        }
        
        return self::SUCCESS;
    }
}
```

Then call from controller:

```php
// app/Http/Controllers/Election/VoterImportController.php

public function import(Request $request, Organisation $organisation, Election $election)
{
    $this->authorize('manageVoters', $election);
    
    $request->validate([
        'file' => 'required|file|mimes:csv,txt|max:10240',
        'confirmed' => 'required|accepted',
    ]);
    
    // Save file temporarily
    $path = $request->file('file')->storeAs(
        'imports',
        $election->id . '_' . time() . '.csv'
    );
    
    $fullPath = storage_path('app/' . $path);
    
    // Dispatch import command (runs in background)
    \Artisan::call('voters:import', [
        'election' => $election->id,
        'file' => $fullPath,
        '--queue' => true,
    ]);
    
    // Start worker if not running
    $this->ensureWorkerRunning();
    
    return redirect()
        ->route('elections.voters.index', [$organisation->slug, $election->slug])
        ->with('info', 'Import started. Invitations will be sent shortly.');
}
```

### Summary

| Option | Best For | Implementation |
|--------|----------|----------------|
| `dispatchSync` | < 50 invitations | Immediate sending |
| `dispatch` + worker start | 50-500 invitations | Auto-start worker |
| Bus Batch | 500+ invitations | Batch tracking + worker |
| Artisan Command | Production | Most robust |

**Recommended for Production:**

```php
// Use Bus Batch with worker auto-start
Bus::batch($jobs)->dispatch();
if ($jobsCount > 50) {
    exec('php artisan queue:work --stop-when-empty --timeout=600 > /dev/null 2>&1 &');
}
```

**Om Gam Ganapataye Namah** 🪔🐘
