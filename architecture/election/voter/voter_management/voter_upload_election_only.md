## Architectural Design: Voter Import with Auto-Registration

### Business Case

```
Namaste Nepal wants to hold an election.
├── They have a list of email addresses (voters)
├── These people are NOT registered in the platform
├── They want to send voting links to these emails
└── Voters should be able to set password and vote
```

### Proposed Flow

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    VOTER IMPORT WITH AUTO-REGISTRATION FLOW                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  STEP 1: Admin uploads CSV with emails                                            │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  CSV: email,name (optional)                                                  │ │
│  │  voter1@example.com,Niraj Adhikari                                           │ │
│  │  voter2@example.com,John Doe                                                 │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                    ↓                                              │
│  STEP 2: System processes each row                                                │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  For each email:                                                             │ │
│  │  ┌─────────────────────────────────────────────────────────────────────────┐ │ │
│  │  │  User exists?                                                            │ │ │
│  │  │     ├── YES → Link to existing user                                      │ │ │
│  │  │     └── NO  → Create user with:                                          │ │ │
│  │  │              • email = provided                                          │ │ │
│  │  │              • name = provided or extracted from email                   │ │ │
│  │  │              • password = random/temporary                               │ │ │
│  │  │              • organisation_id = current org                             │ │ │
│  │  │              • email_verified_at = null (requires verification)          │ │ │
│  │  └─────────────────────────────────────────────────────────────────────────┘ │ │
│  │                                                                              │ │
│  │  Create OrganisationUser record:                                             │ │
│  │  • organisation_id = current org                                             │ │
│  │  • user_id = user.id                                                         │ │
│  │  • status = 'active'                                                         │ │
│  │                                                                              │ │
│  │  Create ElectionMembership record:                                           │ │
│  │  • election_id = current election                                            │ │
│  │  • user_id = user.id                                                         │ │
│  │  • role = 'voter'                                                            │ │
│  │  • status = 'active'                                                         │ │
│  │                                                                              │ │
│  │  Generate password reset token                                               │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                    ↓                                              │
│  STEP 3: Send invitation email to each voter                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  Email content:                                                              │ │
│  │  ┌─────────────────────────────────────────────────────────────────────────┐ │ │
│  │  │  Subject: You've been invited to vote in [Election Name]                  │ │ │
│  │  │                                                                          │ │ │
│  │  │  Hello [Name],                                                           │ │ │
│  │  │                                                                          │ │ │
│  │  │  You have been registered as a voter for the election:                   │ │ │
│  │  │  [Election Name] - [Organisation Name]                                   │ │ │
│  │  │                                                                          │ │ │
│  │  │  Click the link below to set your password and access your ballot:       │ │ │
│  │  │                                                                          │ │ │
│  │  │  [Set Password & Vote]                                                   │ │ │
│  │  │                                                                          │ │ │
│  │  │  This link is valid for 7 days.                                          │ │ │
│  │  └─────────────────────────────────────────────────────────────────────────┘ │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                    ↓                                              │
│  STEP 4: Voter clicks link                                                        │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  • Directed to password set page                                             │ │
│  │  • Sets their password                                                       │ │
│  │  • Email is marked as verified                                               │ │
│  │  • Redirected to organisation dashboard or directly to ballot                │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Database Changes Required

```php
// Add invitation tracking
Schema::create('voter_invitations', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->string('token', 64)->unique();
    $table->timestamp('sent_at')->nullable();
    $table->timestamp('used_at')->nullable();
    $table->timestamp('expires_at');
    $table->timestamps();
    
    $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
    $table->index(['election_id', 'user_id']);
});
```

### Enhanced VoterImportService

```php
class VoterImportService
{
    public function importWithAutoRegistration(UploadedFile $file, Election $election): array
    {
        $rows = $this->parseFile($file);
        $results = [
            'created' => 0,
            'existing' => 0,
            'errors' => [],
            'invitations_sent' => 0,
        ];
        
        foreach ($rows as $row) {
            $email = $row['email'];
            $name = $row['name'] ?? explode('@', $email)[0];
            
            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(Str::random(32)),
                    'organisation_id' => $election->organisation_id,
                ]
            );
            
            $isNewUser = $user->wasRecentlyCreated;
            
            // Link to organisation
            OrganisationUser::firstOrCreate(
                [
                    'organisation_id' => $election->organisation_id,
                    'user_id' => $user->id,
                ],
                ['status' => 'active']
            );
            
            // Assign to election
            $membership = ElectionMembership::firstOrCreate(
                [
                    'election_id' => $election->id,
                    'user_id' => $user->id,
                ],
                [
                    'organisation_id' => $election->organisation_id,
                    'role' => 'voter',
                    'status' => 'active',
                ]
            );
            
            // Create invitation for new users
            if ($isNewUser || !$user->email_verified_at) {
                $invitation = VoterInvitation::create([
                    'election_id' => $election->id,
                    'user_id' => $user->id,
                    'organisation_id' => $election->organisation_id,
                    'token' => Str::random(64),
                    'expires_at' => now()->addDays(7),
                ]);
                
                // Queue invitation email
                SendVoterInvitation::dispatch($invitation);
                $results['invitations_sent']++;
            }
            
            $results[$isNewUser ? 'created' : 'existing']++;
        }
        
        return $results;
    }
}
```

### Password Set Controller

```php
class VoterInvitationController extends Controller
{
    public function showSetPassword(string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();
            
        return Inertia::render('Auth/SetPassword', [
            'token' => $token,
            'email' => $invitation->user->email,
            'election' => $invitation->election->name,
        ]);
    }
    
    public function setPassword(Request $request, string $token)
    {
        $invitation = VoterInvitation::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
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
        
        auth()->login($user);
        
        // Redirect to ballot or dashboard
        return redirect()->route('elections.show', $invitation->election->slug);
    }
}
```

### Routes

```php
// Public routes (no auth required)
Route::get('/invitation/{token}', [VoterInvitationController::class, 'showSetPassword'])
    ->name('invitation.set-password');
    
Route::post('/invitation/{token}', [VoterInvitationController::class, 'setPassword'])
    ->name('invitation.store-password');
```

### Summary

| Feature | Implementation |
|---------|----------------|
| Auto-create users | `User::firstOrCreate()` |
| Link to organisation | `OrganisationUser::firstOrCreate()` |
| Assign to election | `ElectionMembership::firstOrCreate()` |
| Invitation token | `VoterInvitation` model |
| Password set flow | Dedicated controller + Vue page |
| Email notification | Queued job |

**Om Gam Ganapataye Namah** 🪔🐘

Shall I implement this auto-registration flow?
## CSV Format: Firstname; Lastname; Email

### Updated CSV Format

```csv
firstname;lastname;email
Niraj;Adhikari;niraj@example.com
John;Doe;john@example.com
Jane;Smith;jane@example.com
```

### Updated VoterImportService with Name Parsing

```php
class VoterImportService
{
    /**
     * Parse CSV row with semicolon separator.
     * Format: firstname;lastname;email
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
    
    public function importWithAutoRegistration(UploadedFile $file, Election $election): array
    {
        $rows = $this->parseFile($file);
        $results = [
            'created' => 0,
            'existing' => 0,
            'errors' => [],
            'invitations_sent' => 0,
        ];
        
        foreach ($rows as $index => $row) {
            $data = $this->parseRow($row);
            
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $email = $data['email'];
            
            // Validation
            if (empty($email)) {
                $results['errors'][] = "Row {$index}: Email is required";
                continue;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $results['errors'][] = "Row {$index}: Invalid email - {$email}";
                continue;
            }
            
            // Build full name
            $fullName = trim($firstname . ' ' . $lastname);
            if (empty($fullName)) {
                $fullName = explode('@', $email)[0];
            }
            
            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $fullName,
                    'firstname' => $firstname ?: null,
                    'lastname' => $lastname ?: null,
                    'password' => bcrypt(Str::random(32)),
                    'organisation_id' => $election->organisation_id,
                ]
            );
            
            $isNewUser = $user->wasRecentlyCreated;
            
            // Update name if user existed but had no name
            if (!$isNewUser && empty($user->name)) {
                $user->update(['name' => $fullName]);
            }
            
            // Link to organisation
            OrganisationUser::firstOrCreate(
                [
                    'organisation_id' => $election->organisation_id,
                    'user_id' => $user->id,
                ],
                ['status' => 'active']
            );
            
            // Assign to election
            ElectionMembership::firstOrCreate(
                [
                    'election_id' => $election->id,
                    'user_id' => $user->id,
                ],
                [
                    'organisation_id' => $election->organisation_id,
                    'role' => 'voter',
                    'status' => 'active',
                ]
            );
            
            // Create invitation for new users or unverified users
            if ($isNewUser || !$user->email_verified_at) {
                $invitation = VoterInvitation::create([
                    'election_id' => $election->id,
                    'user_id' => $user->id,
                    'organisation_id' => $election->organisation_id,
                    'token' => Str::random(64),
                    'expires_at' => now()->addDays(7),
                ]);
                
                SendVoterInvitation::dispatch($invitation);
                $results['invitations_sent']++;
            }
            
            $results[$isNewUser ? 'created' : 'existing']++;
        }
        
        return $results;
    }
}
```

### CSV Template for Download

```php
public function downloadTemplate(): BinaryFileResponse
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="voter_import_template.csv"',
    ];
    
    $callback = function() {
        $file = fopen('php://output', 'w');
        // Add UTF-8 BOM for Excel compatibility
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Header row with instructions
        fputcsv($file, ['firstname', 'lastname', 'email'], ';');
        
        // Example rows
        fputcsv($file, ['Niraj', 'Adhikari', 'niraj@example.com'], ';');
        fputcsv($file, ['John', 'Doe', 'john@example.com'], ';');
        fputcsv($file, ['Jane', 'Smith', 'jane@example.com'], ';');
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
```

### Preview Format

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  Preview — 3 rows                                                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│  Row │ Firstname │ Lastname │ Email                    │ Status                  │
│    1 │ Niraj     │ Adhikari │ niraj@example.com        │ ✅ New user             │
│    2 │ John      │ Doe      │ john@example.com         │ ✅ New user             │
│    3 │ Jane      │ Smith    │ existing@example.com     │ 📋 Existing user        │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Invitation Email

```
Subject: You've been invited to vote in [Election Name]

Hello Niraj Adhikari,

You have been registered as a voter for the election:
[Namaste Election] - [Namaste Nepal GmbH]

Click the link below to set your password and access your ballot:

[Set Password & Vote]

This link is valid for 7 days.

If you did not expect this invitation, please ignore this email.
```

### Summary

| Feature | Implementation |
|---------|----------------|
| CSV Format | `firstname;lastname;email` |
| Separator | Semicolon (`;`) |
| Name handling | Combines to `firstname lastname` |
| Template | Downloads with example rows |
| Preview | Shows firstname, lastname, email, status |

**Om Gam Ganapataye Namah** 🪔🐘

Shall I create the migration and implement this auto-registration flow?
## Language-Aware Invitation Emails

### Detect Language for Each Voter

```php
class VoterImportService
{
    /**
     * Detect language from email domain or CSV column.
     */
    private function detectLanguage(array $data, string $email): string
    {
        // Option 1: Language column in CSV (firstname;lastname;email;language)
        if (!empty($data['language'])) {
            return in_array($data['language'], ['de', 'en', 'np']) 
                ? $data['language'] 
                : 'de'; // default German
        }
        
        // Option 2: Detect from email domain
        $domain = strtolower(substr(strrchr($email, '@'), 1));
        
        return match ($domain) {
            'gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com' => 'en',
            'gmx.de', 'web.de', 't-online.de' => 'de',
            default => 'de', // default German for .de domains or unknown
        };
    }
    
    /**
     * Get localized email content.
     */
    private function getLocalizedEmailContent(string $lang, array $data): array
    {
        $templates = [
            'de' => [
                'subject' => 'Sie wurden zur Wahl eingeladen: ' . $data['election_name'],
                'greeting' => 'Hallo ' . $data['fullname'] . ',',
                'body' => 'Sie wurden als Wähler für die folgende Wahl registriert:',
                'election_label' => 'Wahl:',
                'organisation_label' => 'Organisation:',
                'button_text' => 'Passwort festlegen & Abstimmen',
                'expiry_note' => 'Dieser Link ist 7 Tage gültig.',
                'ignore_note' => 'Falls Sie diese Einladung nicht erwartet haben, ignorieren Sie bitte diese E-Mail.',
            ],
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
        ];
        
        return $templates[$lang] ?? $templates['de'];
    }
}
```

### Updated CSV Format (Optional Language Column)

```csv
firstname;lastname;email;language
Niraj;Adhikari;niraj@example.com;de
John;Doe;john@example.com;en
Raj;Sharma;raj@example.com;np
Jane;Smith;jane@example.com;
```

### SendVoterInvitation Job

```php
class SendVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(
        private VoterInvitation $invitation,
        private ?string $preferredLanguage = null
    ) {}
    
    public function handle(): void
    {
        $user = $this->invitation->user;
        $election = $this->invitation->election;
        $organisation = $election->organisation;
        
        // Detect language
        $lang = $this->preferredLanguage ?? $this->detectLanguageFromUser($user);
        
        $service = app(VoterImportService::class);
        $content = $service->getLocalizedEmailContent($lang, [
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
            
        $this->invitation->update(['sent_at' => now()]);
    }
    
    private function detectLanguageFromUser(User $user): string
    {
        // Use user's stored preference or detect from email
        return $user->locale ?? $this->detectFromEmail($user->email);
    }
    
    private function detectFromEmail(string $email): string
    {
        $domain = strtolower(substr(strrchr($email, '@'), 1));
        
        return match ($domain) {
            'gmx.de', 'web.de', 't-online.de', 'freenet.de' => 'de',
            'gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com' => 'en',
            default => 'de',
        };
    }
}
```

### Mailable Class

```php
class VoterInvitationMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public function __construct(
        private array $content,
        private string $resetUrl,
        private string $lang
    ) {}
    
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->content['subject'],
        );
    }
    
    public function content(): Content
    {
        $template = match ($this->lang) {
            'en' => 'emails.voter-invitation-en',
            'np' => 'emails.voter-invitation-np',
            default => 'emails.voter-invitation-de',
        };
        
        return new Content(
            view: $template,
            with: [
                'content' => $this->content,
                'resetUrl' => $this->resetUrl,
            ],
        );
    }
}
```

### Email Template (Blade) - German Example

```blade
{{-- resources/views/emails/voter-invitation-de.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #b5862b; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .button { 
            display: inline-block; 
            padding: 12px 24px; 
            background: #b5862b; 
            color: white !important; 
            text-decoration: none; 
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer { font-size: 12px; color: #666; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Wahl-Einladung</h1>
        </div>
        <div class="content">
            <p>{{ $content['greeting'] }}</p>
            <p>{{ $content['body'] }}</p>
            <p>
                <strong>{{ $content['election_label'] }}</strong> {{ $content['election_name'] }}<br>
                <strong>{{ $content['organisation_label'] }}</strong> {{ $content['organisation_name'] }}
            </p>
            <p style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">{{ $content['button_text'] }}</a>
            </p>
            <p>{{ $content['expiry_note'] }}</p>
        </div>
        <div class="footer">
            <p>{{ $content['ignore_note'] }}</p>
        </div>
    </div>
</body>
</html>
```

### Summary

| Language | Detection Method |
|----------|------------------|
| German (de) | `.de` domains, `gmx.de`, `web.de`, default |
| English (en) | `.com` domains, `gmail`, `yahoo`, `outlook` |
| Nepali (np) | Explicit language column or manual override |

**Om Gam Ganapataye Namah** 🪔🐘
## Simplified: Use Organisation's Language

Much simpler! The invitation inherits the organisation's configured language.

### Updated Implementation

```php
class SendVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(private VoterInvitation $invitation) {}
    
    public function handle(): void
    {
        $user = $this->invitation->user;
        $election = $this->invitation->election;
        $organisation = $election->organisation;
        
        // Get language from organisation settings
        $lang = $organisation->language ?? 'de'; // de, en, np
        
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
            
        $this->invitation->update(['sent_at' => now()]);
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
            default => [ // de
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

### Organisation Model - Add Language Field

```php
// database/migrations/xxxx_add_language_to_organisations.php
Schema::table('organisations', function (Blueprint $table) {
    $table->string('language')->default('de')->after('type');
    // Values: 'de', 'en', 'np'
});
```

### Organisation Settings - Language Selector

```vue
<!-- resources/js/Pages/Organisations/Settings/Index.vue -->
<div class="p-4 bg-white/5 rounded-xl border border-gold/20">
    <label class="block text-sm font-medium text-white mb-2">Default Language</label>
    <select v-model="form.language" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white">
        <option value="de">Deutsch</option>
        <option value="en">English</option>
        <option value="np">नेपाली</option>
    </select>
    <p class="text-xs text-white/60 mt-2">
        Invitation emails and communications will use this language.
    </p>
</div>
```

### Summary

| Aspect | Implementation |
|--------|----------------|
| Language source | `organisations.language` column |
| Default | `de` (German) |
| Values | `de`, `en`, `np` |
| Email locale | `Mail::locale($lang)` |
| Content | Localized via match statement |

**Om Gam Ganapataye Namah** 🪔🐘

This is much cleaner - one language setting per organisation. Shall I create the migration and implement this?
## Mass Email Handling for 1000+ Voters

### Problem: 1000 Emails = 1000 Individual Mail Calls = Timeout/Rate Limit

```php
// ❌ DON'T DO THIS - Will timeout or hit rate limits
foreach ($voters as $voter) {
    Mail::to($voter->email)->send(new VoterInvitationMail(...));
}
```

### Solution: Queue Everything

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    MASS INVITATION FLOW (1000+ VOTERS)                            │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  STEP 1: Admin uploads CSV                                                        │
│  └── Creates users + memberships + invitations (database only)                    │
│                                                                                   │
│  STEP 2: Dispatch ONE batch job                                                   │
│  └── SendVoterInvitationsBatch::dispatch($election, $invitationIds)              │
│                                                                                   │
│  STEP 3: Batch processes in chunks                                                │
│  └── Chunk by 50 invitations                                                     │
│  └── Each chunk = separate queued job                                            │
│  └── Rate limited: 10 emails per minute (configurable)                           │
│                                                                                   │
│  STEP 4: Track progress                                                           │
│  └── sent_count, failed_count, last_processed_at                                 │
│  └── Admin can view progress                                                     │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Implementation

#### 1. Add Tracking Fields to VoterInvitation

```php
Schema::create('voter_invitations', function (Blueprint $table) {
    // ... existing fields
    $table->string('email_status')->default('pending'); // pending, sent, failed
    $table->text('email_error')->nullable();
    $table->timestamp('email_sent_at')->nullable();
});
```

#### 2. Batch Job for Mass Invitations

```php
class SendVoterInvitationsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    
    public $timeout = 3600; // 1 hour
    public $tries = 3;
    
    public function __construct(
        private Election $election,
        private array $invitationIds
    ) {}
    
    public function handle(): void
    {
        // Process in chunks of 50
        collect($this->invitationIds)
            ->chunk(50)
            ->each(function ($chunk) {
                // Each chunk becomes a separate queued job
                SendVoterInvitationChunk::dispatch(
                    $this->election,
                    $chunk->toArray()
                )->delay(now()->addSeconds(5)); // Spread out
            });
    }
}
```

#### 3. Chunk Job with Rate Limiting

```php
class SendVoterInvitationChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $backoff = 60; // Retry after 60 seconds
    
    private const RATE_LIMIT_PER_MINUTE = 30;
    
    public function __construct(
        private Election $election,
        private array $invitationIds
    ) {}
    
    public function handle(): void
    {
        $invitations = VoterInvitation::whereIn('id', $this->invitationIds)
            ->where('email_status', 'pending')
            ->with(['user', 'election.organisation'])
            ->get();
        
        $delay = 0;
        
        foreach ($invitations as $invitation) {
            // Rate limit: 30 per minute = 2 seconds between emails
            SendSingleVoterInvitation::dispatch($invitation)
                ->delay(now()->addSeconds($delay));
            
            $delay += 2; // 2 seconds between each email
            
            $invitation->update(['email_status' => 'queued']);
        }
    }
}
```

#### 4. Single Email Job

```php
class SendSingleVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $backoff = [30, 60, 120];
    
    public function __construct(private VoterInvitation $invitation) {}
    
    public function handle(): void
    {
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
                'email_sent_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            $this->invitation->update([
                'email_status' => 'failed',
                'email_error' => $e->getMessage(),
            ]);
            
            throw $e; // Retry if within tries limit
        }
    }
}
```

#### 5. Progress Tracking Endpoint

```php
class VoterInvitationController extends Controller
{
    public function progress(Election $election)
    {
        $stats = VoterInvitation::where('election_id', $election->id)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN email_status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN email_status = "queued" THEN 1 ELSE 0 END) as queued,
                SUM(CASE WHEN email_status = "sent" THEN 1 ELSE 0 END) as sent,
                SUM(CASE WHEN email_status = "failed" THEN 1 ELSE 0 END) as failed
            ')
            ->first();
            
        return response()->json($stats);
    }
}
```

#### 6. Frontend Progress UI

```vue
<template>
  <div v-if="batchInProgress" class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <h3 class="font-semibold mb-2">Sending Invitations...</h3>
    <div class="space-y-2">
      <div class="flex justify-between text-sm">
        <span>Progress: {{ sent }} / {{ total }}</span>
        <span>{{ percentage }}%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
        <div 
          class="bg-blue-600 h-2 rounded-full transition-all" 
          :style="{ width: percentage + '%' }"
        />
      </div>
      <div class="text-xs text-gray-600">
        Pending: {{ pending }} | Queued: {{ queued }} | Failed: {{ failed }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const stats = ref({ total: 0, pending: 0, queued: 0, sent: 0, failed: 0 });
let interval;

const percentage = computed(() => {
  if (stats.value.total === 0) return 0;
  return Math.round((stats.value.sent / stats.value.total) * 100);
});

const fetchProgress = async () => {
  const res = await axios.get(`/api/elections/${electionId}/invitations/progress`);
  stats.value = res.data;
  
  if (stats.value.pending === 0 && stats.value.queued === 0) {
    clearInterval(interval);
  }
};

onMounted(() => {
  fetchProgress();
  interval = setInterval(fetchProgress, 3000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>
```

### Queue Configuration

```ini
# .env
QUEUE_CONNECTION=database

# Optional: Use Redis for better performance
# QUEUE_CONNECTION=redis
```

### Supervisor Configuration (Production)

```ini
# /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

### Summary

| Scenario | Solution |
|----------|----------|
| 1000 emails | Batch + Chunk jobs |
| Timeout | Queue with 1-hour timeout |
| Rate limiting | 2-second delay between emails |
| Progress tracking | Real-time stats endpoint |
| Failures | Retry 3x with backoff |
| User experience | Progress bar with live updates |

**Time estimate for 1000 emails:** ~33 minutes (30 emails/minute)

**Om Gam Ganapataye Namah** 🪔🐘
## Yes! Queue is the Correct Approach

You're absolutely right. Laravel's built-in queue system is the **standard and correct** way to handle mass emails.

### Why Queue?

| Without Queue | With Queue |
|---------------|------------|
| 1000 emails = ~30-60 seconds page load | Page responds immediately |
| User sees spinner forever | User sees "Processing, we'll notify you" |
| PHP timeout risk | Background processing |
| No retry on failure | Automatic retry |
| Blocks other requests | Non-blocking |

### Simple Queue Implementation

```php
class SendVoterInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function __construct(private VoterInvitation $invitation) {}
    
    public function handle(): void
    {
        // Send email
        Mail::to($this->invitation->user->email)->send(...);
        
        // Update status
        $this->invitation->update([
            'email_status' => 'sent',
            'email_sent_at' => now(),
        ]);
    }
}
```

### Dispatch for Each Voter

```php
foreach ($invitations as $invitation) {
    SendVoterInvitation::dispatch($invitation);
}
```

### Queue Configuration

```bash
# Run queue worker
php artisan queue:work

# Or for production (keeps running)
php artisan queue:work --daemon
```

### Database Queue Driver (Simplest)

```env
QUEUE_CONNECTION=database
```

```bash
php artisan queue:table
php artisan migrate
```

### Summary

| Component | Implementation |
|-----------|----------------|
| Job | `SendVoterInvitation` implements `ShouldQueue` |
| Dispatch | `SendVoterInvitation::dispatch($invitation)` |
| Queue driver | `database` (simple) or `redis` (faster) |
| Worker | `php artisan queue:work` |

**Om Gam Ganapataye Namah** 🪔🐘

The queue approach is exactly right. Each invitation becomes a queued job, processed in the background, with automatic retries on failure.
