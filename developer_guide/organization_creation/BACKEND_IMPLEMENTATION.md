# Backend Implementation Guide - Organization Creation API

## Quick Start

This guide covers implementing the backend endpoints and services for the Organization Creation Flow.

---

## Step 1: Create the Request Class

**File:** `app/Http/Requests/StoreOrganizationRequest.php`

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // User must be authenticated
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('organizations', 'name'),
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
            ],
            'address.street' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'address.city' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'address.zip' => [
                'required',
                'regex:/^\d{5}$/', // German postal code
            ],
            'address.country' => [
                'required',
                Rule::in(['DE']), // Only Germany for now
            ],
            'representative.name' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'representative.role' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],
            'representative.email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
            ],
            'accept_gdpr' => [
                'required',
                'boolean',
                'accepted', // Must be true
            ],
            'accept_terms' => [
                'required',
                'boolean',
                'accepted', // Must be true
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Eine Organisation mit diesem Namen existiert bereits.',
            'address.zip.regex' => 'Die Postleitzahl muss genau 5 Ziffern enthalten.',
            'accept_gdpr.accepted' => 'Sie müssen der DSGVO-Verarbeitung zustimmen.',
            'accept_terms.accepted' => 'Sie müssen den Nutzungsbedingungen zustimmen.',
        ];
    }

    /**
     * Get the validated input as a DTO (Data Transfer Object)
     */
    public function toDTO(): OrganizationCreateDTO
    {
        return new OrganizationCreateDTO(
            name: $this->validated('name'),
            email: $this->validated('email'),
            address: new AddressDTO(...$this->validated('address')),
            representative: new RepresentativeDTO(...$this->validated('representative')),
            gdprAccepted: $this->boolean('accept_gdpr'),
            termsAccepted: $this->boolean('accept_terms'),
        );
    }
}
```

---

## Step 2: Create DTOs

**File:** `app/DataTransferObjects/OrganizationCreateDTO.php`

```php
<?php

namespace App\DataTransferObjects;

class OrganizationCreateDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly AddressDTO $address,
        public readonly RepresentativeDTO $representative,
        public readonly bool $gdprAccepted,
        public readonly bool $termsAccepted,
    ) {
    }
}
```

**File:** `app/DataTransferObjects/AddressDTO.php`

```php
<?php

namespace App\DataTransferObjects;

class AddressDTO
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly string $zip,
        public readonly string $country = 'DE',
    ) {
    }

    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'zip' => $this->zip,
            'country' => $this->country,
        ];
    }
}
```

**File:** `app/DataTransferObjects/RepresentativeDTO.php`

```php
<?php

namespace App\DataTransferObjects;

class RepresentativeDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $role,
        public readonly ?string $email = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'role' => $this->role,
            'email' => $this->email,
        ];
    }
}
```

---

## Step 3: Create Service Layer

**File:** `app/Services/Organization/CreateOrganizationService.php`

```php
<?php

namespace App\Services\Organization;

use App\DataTransferObjects\OrganizationCreateDTO;
use App\Mail\OrganizationVerificationEmail;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateOrganizationService
{
    /**
     * Create a new organization and setup initial member
     *
     * @throws \Exception
     */
    public function create(OrganizationCreateDTO $dto, User $createdBy): Organization
    {
        return DB::transaction(function () use ($dto, $createdBy) {
            // 1. Create organization
            $organization = new Organization();
            $organization->name = $dto->name;
            $organization->slug = Str::slug($dto->name);
            $organization->email = $dto->email;
            $organization->address = $dto->address->toArray();
            $organization->representative = $dto->representative->toArray();
            $organization->status = 'pending_verification';
            $organization->verification_token = Str::random(64);
            $organization->save();

            // 2. Create landlord admin user for organization
            $adminUser = new User();
            $adminUser->name = $dto->representative->name;
            $adminUser->email = $dto->representative->email ?? $dto->email;
            $adminUser->password = bcrypt(Str::random(32)); // Temporary, user must reset
            $adminUser->email_verified_at = now(); // Auto-verify
            $adminUser->save();

            // 3. Assign admin to organization
            $organization->admins()->attach($adminUser->id);

            // 4. Send verification email
            Mail::to($organization->email)->queue(
                new OrganizationVerificationEmail($organization)
            );

            // 5. Log creation
            activity()
                ->causedBy($createdBy)
                ->performedOn($organization)
                ->event('created')
                ->log('Organization created');

            return $organization;
        });
    }

    /**
     * Verify organization email
     */
    public function verifyEmail(Organization $organization, string $token): bool
    {
        if ($organization->verification_token !== $token) {
            return false;
        }

        $organization->update([
            'status' => 'active',
            'verification_token' => null,
            'email_verified_at' => now(),
        ]);

        return true;
    }
}
```

---

## Step 4: Create Controller

**File:** `app/Http/Controllers/Api/OrganizationController.php`

```php
<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\OrganizationCreateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Services\Organization\CreateOrganizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class OrganizationController extends Controller
{
    public function __construct(
        private CreateOrganizationService $organizationService
    ) {
    }

    /**
     * Create a new organization
     *
     * POST /api/organizations
     */
    public function store(StoreOrganizationRequest $request): JsonResponse
    {
        try {
            // Convert request to DTO
            $dto = $request->toDTO();

            // Create organization
            $organization = $this->organizationService->create($dto, $request->user());

            return response()->json(
                new OrganizationResource($organization),
                Response::HTTP_CREATED
            );
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Fehler beim Erstellen der Organisation',
                'error_code' => 'organization_creation_error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Verify organization email via link in email
     *
     * GET /api/organizations/{organization}/verify/{token}
     */
    public function verifyEmail(Organization $organization, string $token): JsonResponse
    {
        if ($this->organizationService->verifyEmail($organization, $token)) {
            return response()->json([
                'message' => 'Organisation erfolgreich bestätigt',
                'status' => 'verified',
            ]);
        }

        return response()->json([
            'message' => 'Ungültiger Verifizierungstoken',
            'error_code' => 'invalid_token',
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
```

---

## Step 5: Create Resource

**File:** `app/Http/Resources/OrganizationResource.php`

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            'verification_email_sent_to' => $this->email,
            'next_steps' => [
                'Überprüfe die Organisations-E-Mail',
                'Füge erste Mitglieder hinzu',
                'Erstelle deine erste Wahl',
            ],
        ];
    }
}
```

---

## Step 6: Create Mailable

**File:** `app/Mail/OrganizationVerificationEmail.php`

```php
<?php

namespace App\Mail;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganizationVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Organization $organization
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bestätigen Sie Ihre Organisation - Public Digit',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.organization.verification',
            with: [
                'organization' => $this->organization,
                'verificationUrl' => route('api.organizations.verify', [
                    'organization' => $this->organization->id,
                    'token' => $this->organization->verification_token,
                ]),
            ],
        );
    }
}
```

---

## Step 7: Update Routes

**File:** `routes/api.php`

```php
<?php

use App\Http\Controllers\Api\OrganizationController;

Route::middleware('auth:sanctum')->group(function () {
    // Create organization
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');

    // Verify organization email
    Route::get('/organizations/{organization}/verify/{token}', [OrganizationController::class, 'verifyEmail'])
        ->name('organizations.verify')
        ->withoutMiddleware('auth:sanctum'); // Allow unauthenticated verification
});
```

---

## Step 8: Update Organization Model

**File:** `app/Models/Organization.php`

Ensure model has these attributes:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'address',
        'representative',
        'status',
        'verification_token',
        'email_verified_at',
    ];

    protected $casts = [
        'address' => 'json',
        'representative' => 'json',
        'email_verified_at' => 'datetime',
    ];

    // Relations
    public function admins()
    {
        return $this->belongsToMany(User::class, 'organization_admins', 'organization_id', 'user_id');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status', 'pending_verification');
    }

    // Methods
    public function isVerified(): bool
    {
        return $this->status === 'active';
    }
}
```

---

## Step 9: Create Migration

**File:** `database/migrations/XXXX_XX_XX_create_organizations_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('email');
            $table->json('address'); // {street, city, zip, country}
            $table->json('representative'); // {name, role, email}
            $table->enum('status', ['pending_verification', 'active', 'suspended', 'deleted'])->default('pending_verification');
            $table->string('verification_token')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
```

---

## Step 10: Create Pivot Table for Organization Admins

**File:** `database/migrations/XXXX_XX_XX_create_organization_admins_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Unique constraint: one admin role per org per user
            $table->unique(['organization_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_admins');
    }
};
```

---

## Testing the API

### Using Postman/Insomnia

**Step 1: Authenticate**
```
POST http://localhost/api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}

Response:
{
  "token": "your-sanctum-token"
}
```

**Step 2: Create Organization**
```
POST http://localhost/api/organizations
Authorization: Bearer your-sanctum-token
Content-Type: application/json

{
  "name": "Test Verein e.V.",
  "email": "contact@test.de",
  "address": {
    "street": "Teststraße 1",
    "city": "München",
    "zip": "80331",
    "country": "DE"
  },
  "representative": {
    "name": "Max Mustermann",
    "role": "Vorsitzender",
    "email": "max@test.de"
  },
  "accept_gdpr": true,
  "accept_terms": true
}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Test Verein e.V.",
    "slug": "test-verein-ev",
    "email": "contact@test.de",
    "status": "pending_verification",
    "created_at": "2025-02-11T10:30:00Z",
    "verification_email_sent_to": "contact@test.de",
    "next_steps": [
      "Überprüfe die Organisations-E-Mail",
      "Füge erste Mitglieder hinzu",
      "Erstelle deine erste Wahl"
    ]
  }
}
```

---

## Error Handling

### Validation Errors (422)

```json
{
  "message": "The name field is required.",
  "errors": {
    "name": ["The name field is required."],
    "address.zip": ["The zip must be 5 digits."]
  }
}
```

### Duplicate Organization (409)

```json
{
  "message": "Eine Organisation mit diesem Namen existiert bereits.",
  "error_code": "organization_exists"
}
```

### Server Error (500)

```json
{
  "message": "Fehler beim Erstellen der Organisation",
  "error_code": "organization_creation_error"
}
```

---

## Database Seeding

**File:** `database/seeders/OrganizationSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::create([
            'name' => 'Demo Verein e.V.',
            'slug' => 'demo-verein-ev',
            'email' => 'admin@demo-verein.de',
            'address' => [
                'street' => 'Demostraße 123',
                'city' => 'München',
                'zip' => '80331',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Demo Admin',
                'role' => 'Vorsitzender',
                'email' => 'admin@demo-verein.de',
            ],
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $admin = User::where('email', 'admin@example.com')->first() ?? User::create([
            'name' => 'Demo Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $organization->admins()->attach($admin->id);
    }
}
```

---

## Checklist

- [ ] Create Request class with validation
- [ ] Create DTOs for data transfer
- [ ] Create Service layer for business logic
- [ ] Create Controller with API actions
- [ ] Create Resource for API responses
- [ ] Create Mailable for verification email
- [ ] Update routes (routes/api.php)
- [ ] Create/update Organization model
- [ ] Create database migration
- [ ] Create database seeder
- [ ] Run migrations: `php artisan migrate`
- [ ] Test API endpoints
- [ ] Add tests (unit + feature)
- [ ] Document API in README

---

## Common Errors

| Error | Cause | Solution |
|-------|-------|----------|
| 401 Unauthorized | No/invalid token | Add `Authorization: Bearer token` header |
| 422 Validation Error | Invalid field values | Check field validation rules |
| 409 Conflict | Organization name exists | Use unique name |
| 500 Server Error | Exception in service | Check logs: `tail -f storage/logs/laravel.log` |
| Email not sent | Mail not configured | Check `config/mail.php` |

---

**Document Version:** 1.0
**Last Updated:** February 11, 2025
