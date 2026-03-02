## 🎯 WHO IS RESPONSIBLE: The Error Handling Chain

Each layer has specific responsibilities. Here's the complete **responsibility chain**:

```
┌─────────────────────────────────────────────────────────────────┐
│                    ERROR HANDLING CHAIN                          │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  LAYER 1: DemoElectionResolver                           │    │
│  │  🔍 Checks: Election availability                        │    │
│  │  🎯 Shows: "No demo election available"                  │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  LAYER 2: VoterSlugService                               │    │
│  │  🔍 Checks: Can create voting session                   │    │
│  │  🎯 Shows: "Cannot start voting session"                │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  LAYER 3: Middleware Chain                               │    │
│  │  ├─ VerifyVoterSlug                                      │    │
│  │  ├─ ValidateVoterSlugWindow                              │    │
│  │  └─ VerifyVoterSlugConsistency                           │    │
│  │  🎯 Shows: Permission/validation errors                  │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  LAYER 4: Controllers                                    │    │
│  │  🔍 Checks: Business logic                               │    │
│  │  🎯 Shows: Application-specific errors                   │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  LAYER 5: Frontend (Vue Components)                      │    │
│  │  🎯 Displays errors to user                              │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## 📋 COMPLETE RESPONSIBILITY MATRIX

### LAYER 1: **DemoElectionResolver** (Service Layer)

**Responsible for:** Finding the correct demo election

```php
// app/Services/DemoElectionResolver.php
class DemoElectionResolver
{
    public function getDemoElectionForUser(User $user): Election
    {
        // Check if organisation exists
        if ($user->organisation_id && !Organisation::find($user->organisation_id)) {
            throw new OrganisationNotFoundException(
                'Your organisation (ID: ' . $user->organisation_id . ') does not exist in the system.'
            );
        }
        
        // Try org-specific demo
        $orgDemo = Election::where('type', 'demo')
            ->where('organisation_id', $user->organisation_id)
            ->where('is_active', true)
            ->first();
        
        if ($orgDemo) {
            return $orgDemo;
        }
        
        // Try global demo
        $globalDemo = Election::where('type', 'demo')
            ->where('organisation_id', 1)  // Platform
            ->where('is_active', true)
            ->first();
        
        if ($globalDemo) {
            return $globalDemo;
        }
        
        // ❌ NOTHING FOUND - Clear error
        throw new NoDemoElectionException(
            'No demo election is available. Please run: php artisan demo:setup' .
            ($user->organisation_id ? ' --org=' . $user->organisation_id : '')
        );
    }
}
```

### LAYER 2: **VoterSlugService** (Service Layer)

**Responsible for:** Creating valid voting sessions

```php
// app/Services/VoterSlugService.php
class VoterSlugService
{
    public function getOrCreateActiveSlug(User $user): VoterSlug
    {
        // VALIDATION 1: User has organisation
        if (!$user->organisation_id) {
            throw new InvalidVoterException(
                'Your account is not linked to any organisation. ' .
                'Please contact your administrator to assign you to an organisation.'
            );
        }
        
        // VALIDATION 2: Organisation exists
        if (!Organisation::find($user->organisation_id)) {
            throw new OrganisationNotFoundException(
                'Your organisation (ID: ' . $user->organisation_id . ') no longer exists. ' .
                'Please contact support.'
            );
        }
        
        // Get election from session or default
        $electionId = session('selected_election_id');
        $election = Election::find($electionId);
        
        if (!$election) {
            throw new NoElectionSelectedException(
                'No election selected. Please choose an election from the dashboard.'
            );
        }
        
        // VALIDATION 3: Election belongs to user's org (or platform)
        if ($election->organisation_id !== 1 && 
            $election->organisation_id !== $user->organisation_id) {
            throw new UnauthorizedElectionException(
                'You do not have permission to vote in election "' . $election->name . '". ' .
                'This election is only for members of organisation #' . $election->organisation_id . '.'
            );
        }
        
        // Create slug
        return $this->createSlug($user, $election);
    }
}
```

### LAYER 3: **Middleware Chain** (HTTP Layer)

**Responsible for:** Validating each request

#### A. `VerifyVoterSlug` Middleware
```php
// app/Http/Middleware/VerifyVoterSlug.php
public function handle($request, $next)
{
    $slugParam = $request->route('vslug');
    
    // Find slug WITHOUT global scope first
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->where('slug', $slugParam)
        ->first();
    
    if (!$voterSlug) {
        Log::warning('Invalid voter slug accessed', ['slug' => $slugParam]);
        
        // ❌ CLEAR ERROR MESSAGE
        return redirect()->route('dashboard')
            ->with('error', 'Your voting link is invalid. Please start again from the dashboard.');
    }
    
    // Check ownership
    if ($voterSlug->user_id !== auth()->id()) {
        Log::warning('User accessed another user\'s voting session');
        
        return redirect()->route('dashboard')
            ->with('error', 'This voting link belongs to another user. Please use your own link.');
    }
    
    // Check active status
    if (!$voterSlug->is_active) {
        return redirect()->route('dashboard')
            ->with('error', 'Your voting session has been deactivated. Please start a new session.');
    }
    
    $request->attributes->set('voter_slug', $voterSlug);
    return $next($request);
}
```

#### B. `ValidateVoterSlugWindow` Middleware
```php
// app/Http/Middleware/ValidateVoterSlugWindow.php
public function handle($request, $next)
{
    $voterSlug = $request->attributes->get('voter_slug');
    
    if ($voterSlug->expires_at->isPast()) {
        $voterSlug->update(['is_active' => false]);
        
        return redirect()->route('dashboard')
            ->with('error', 'Your voting session has expired. Sessions expire after ' . 
                   config('voting.session_expiry_minutes', 30) . ' minutes.');
    }
    
    return $next($request);
}
```

#### C. `VerifyVoterSlugConsistency` Middleware
```php
// app/Http/Middleware/VerifyVoterSlugConsistency.php
public function handle($request, $next)
{
    $voterSlug = $request->attributes->get('voter_slug');
    $user = auth()->user();
    
    // Check election exists
    $election = $voterSlug->election;
    if (!$election) {
        Log::critical('Voter slug references missing election', [
            'slug_id' => $voterSlug->id,
            'election_id' => $voterSlug->election_id
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'The election for your voting session no longer exists. Please contact support.');
    }
    
    // GOLDEN RULE: Check organisation consistency
    $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
    $electionIsPlatform = $election->organisation_id === 1;
    $userIsPlatform = $voterSlug->organisation_id === 1;
    
    if (!$orgsMatch && !$electionIsPlatform && !$userIsPlatform) {
        $errorMsg = 'Organisation mismatch detected. ';
        $errorMsg .= 'Your organisation: ' . $voterSlug->organisation_id . ', ';
        $errorMsg .= 'Election organisation: ' . $election->organisation_id . '. ';
        $errorMsg .= 'Please contact your administrator.';
        
        Log::error('Organisation mismatch', [
            'user_id' => $user->id,
            'slug_org' => $voterSlug->organisation_id,
            'election_org' => $election->organisation_id
        ]);
        
        return redirect()->route('dashboard')->with('error', $errorMsg);
    }
    
    // Check user still belongs to same org
    if ($user->organisation_id !== $voterSlug->organisation_id && 
        $voterSlug->organisation_id !== 1) {
        
        return redirect()->route('dashboard')
            ->with('error', 'Your organisation affiliation has changed since this voting session was created. ' .
                   'Please start a new voting session.');
    }
    
    $request->attributes->set('election', $election);
    return $next($request);
}
```

### LAYER 4: **Controllers** (Application Layer)

**Responsible for:** Handling business logic and exceptions

```php
// app/Http/Controllers/Election/ElectionController.php
public function startDemo()
{
    $user = auth()->user();
    
    try {
        // This can throw:
        // - OrganisationNotFoundException
        // - NoDemoElectionException
        $demoElection = $this->demoResolver->getDemoElectionForUser($user);
        
        session([
            'selected_election_id' => $demoElection->id,
            'selected_election_type' => 'demo',
        ]);
        
        // This can throw:
        // - InvalidVoterException
        // - OrganisationNotFoundException
        // - NoElectionSelectedException
        // - UnauthorizedElectionException
        $slug = $this->slugService->getOrCreateActiveSlug($user);
        
        return redirect()->route('slug.demo-code.create', ['vslug' => $slug->slug])
            ->with('success', '🎮 Demo mode activated! Testing election ready.');
            
    } catch (OrganisationNotFoundException $e) {
        Log::error('Organisation missing', ['user_id' => $user->id]);
        return redirect()->route('dashboard')
            ->with('error', 'Your organisation is not properly configured. Please contact support.');
            
    } catch (NoDemoElectionException $e) {
        // Helpful message with command to run
        $cmd = $user->organisation_id 
            ? "php artisan demo:setup --org={$user->organisation_id}"
            : "php artisan demo:setup";
            
        return redirect()->route('dashboard')
            ->with('error', 'No demo election found. Please run: ' . $cmd);
            
    } catch (InvalidVoterException | NoElectionSelectedException $e) {
        return redirect()->route('dashboard')->with('error', $e->getMessage());
        
    } catch (UnauthorizedElectionException $e) {
        return redirect()->route('dashboard')->with('error', $e->getMessage());
        
    } catch (\Exception $e) {
        Log::error('Unexpected error in demo start', [
            'user_id' => $user->id,
            'error' => $e->getMessage()
        ]);
        
        return redirect()->route('dashboard')
            ->with('error', 'An unexpected error occurred. Please try again or contact support.');
    }
}
```

### LAYER 5: **Frontend** (Vue Components)

**Responsible for:** Displaying errors to users

```vue
<!-- resources/js/Layouts/ElectionLayout.vue -->
<template>
  <div>
    <!-- Global Error Display -->
    <div v-if="errorMessage" class="fixed top-4 right-4 z-50 max-w-md">
      <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-lg shadow-lg">
        <div class="flex items-start">
          <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="text-red-800 font-medium">{{ errorMessage }}</p>
            <button @click="clearError" class="text-red-600 text-sm mt-2 hover:text-red-800">
              Dismiss
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Success Message -->
    <div v-if="successMessage" class="fixed top-4 right-4 z-50 max-w-md">
      <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded-lg shadow-lg">
        <p class="text-green-800">{{ successMessage }}</p>
      </div>
    </div>
    
    <!-- Main Content -->
    <slot />
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const errorMessage = computed(() => page.props.flash?.error || null)
const successMessage = computed(() => page.props.flash?.success || null)

const clearError = () => {
  router.reload({ data: { clear_error: true } })
}
</script>
```

## 🎯 FOR YOUR SPECIFIC CASE (User with org_id = 2)

### If No Demo Election Exists:
```php
// Error shown:
"No demo election found. Please run: php artisan demo:setup --org=2"
```

### If Demo Election Exists But Has Wrong Org:
```php
// Middleware catches it:
"Organisation mismatch detected. Your organisation: 2, Election organisation: 1. Please contact your administrator."
```

### If Everything Works:
```php
// Success message:
"🎮 Demo mode activated! Testing election ready."
```

## 📋 SUMMARY: Who Shows What

| Error Type | Who Detects | Who Shows Error |
|------------|-------------|-----------------|
| No demo election | DemoElectionResolver | Controller → Frontend |
| Organisation doesn't exist | DemoElectionResolver | Controller → Frontend |
| User has no org | VoterSlugService | Controller → Frontend |
| Wrong election access | VoterSlugService | Controller → Frontend |
| Invalid voter slug | VerifyVoterSlug | Middleware → Frontend |
| Expired session | ValidateVoterSlugWindow | Middleware → Frontend |
| Organisation mismatch | VerifyVoterSlugConsistency | Middleware → Frontend |
| Election missing | VerifyVoterSlugConsistency | Middleware → Frontend |
| User org changed | VerifyVoterSlugConsistency | Middleware → Frontend |
| Unexpected errors | try/catch | Controller → Frontend |

This way, **every possible error** is caught at the right layer and shown to the user with a clear, actionable message! 🎯

## ✅ YES! A Centralized Error Handler is PERFECT!

We can create a **single, unified error handling system** that catches ALL exceptions and shows appropriate user messages. Here's the complete solution:

## 🏛️ CENTRALIZED ERROR HANDLING ARCHITECTURE

```
┌─────────────────────────────────────────────────────────────────┐
│                    CENTRALIZED ERROR HANDLER                     │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              Custom Exceptions Layer                      │    │
│  │  • OrganisationException                                  │    │
│  │  • ElectionException                                      │    │
│  │  • VoterSlugException                                     │    │
│  │  • VotingException                                         │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              Exception Handler (app/Exceptions/Handler.php) │    │
│  │  • Catches ALL exceptions                                 │    │
│  │  • Maps to user-friendly messages                         │    │
│  │  • Logs for debugging                                     │    │
│  │  • Returns consistent response                            │    │
│  └─────────────────────────────────────────────────────────┘    │
│                           ↓                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │              Frontend Display (Vue Component)             │    │
│  │  • Shows error message                                    │    │
│  │  • Provides helpful actions                               │    │
│  │  • Styled consistently                                    │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
```

## 📦 STEP 1: Create Custom Exception Classes

```php
<?php
// app/Exceptions/VotingException.php

namespace App\Exceptions;

use Exception;

abstract class VotingException extends Exception
{
    protected string $userMessage;
    protected string $logLevel = 'error';
    protected ?array $context = null;
    
    public function getUserMessage(): string
    {
        return $this->userMessage ?? 'An unexpected error occurred. Please try again.';
    }
    
    public function getLogLevel(): string
    {
        return $this->logLevel;
    }
    
    public function getContext(): ?array
    {
        return $this->context;
    }
}
```

```php
<?php
// app/Exceptions/OrganisationException.php

namespace App\Exceptions;

class OrganisationException extends VotingException
{
    public static function notFound(int $orgId): self
    {
        $e = new self("Organisation with ID {$orgId} not found");
        $e->userMessage = 'Your organisation is not properly configured. Please contact support.';
        $e->logLevel = 'error';
        $e->context = ['organisation_id' => $orgId];
        return $e;
    }
    
    public static function userNotAssigned(): self
    {
        $e = new self('User has no organisation assigned');
        $e->userMessage = 'Your account is not linked to any organisation. Please contact your administrator.';
        $e->logLevel = 'warning';
        return $e;
    }
    
    public static function organisationChanged(int $oldOrg, int $newOrg): self
    {
        $e = new self("User organisation changed from {$oldOrg} to {$newOrg}");
        $e->userMessage = 'Your organisation affiliation has changed. Please start a new voting session.';
        $e->logLevel = 'warning';
        $e->context = ['old_org' => $oldOrg, 'new_org' => $newOrg];
        return $e;
    }
}
```

```php
<?php
// app/Exceptions/ElectionException.php

namespace App\Exceptions;

class ElectionException extends VotingException
{
    public static function noDemoElection(?int $orgId = null): self
    {
        $cmd = $orgId ? "php artisan demo:setup --org={$orgId}" : "php artisan demo:setup";
        
        $e = new self('No demo election available');
        $e->userMessage = 'No demo election is available. Please run: <code>' . $cmd . '</code>';
        $e->logLevel = 'warning';
        $e->context = ['organisation_id' => $orgId];
        return $e;
    }
    
    public static function notFound(int $electionId): self
    {
        $e = new self("Election ID {$electionId} not found");
        $e->userMessage = 'The election associated with your session no longer exists. Please contact support.';
        $e->logLevel = 'error';
        $e->context = ['election_id' => $electionId];
        return $e;
    }
    
    public static function notActive(string $electionName): self
    {
        $e = new self("Election '{$electionName}' is not active");
        $e->userMessage = "The election '{$electionName}' is not currently active. Please check back later.";
        $e->logLevel = 'info';
        return $e;
    }
    
    public static function organisationMismatch(int $electionOrg, int $userOrg): self
    {
        $e = new self("Election org {$electionOrg} does not match user org {$userOrg}");
        $e->userMessage = 'You do not have permission to vote in this election. ' .
                          'This election is only for members of organisation #' . $electionOrg . '.';
        $e->logLevel = 'warning';
        $e->context = ['election_org' => $electionOrg, 'user_org' => $userOrg];
        return $e;
    }
}
```

```php
<?php
// app/Exceptions/VoterSlugException.php

namespace App\Exceptions;

class VoterSlugException extends VotingException
{
    public static function notFound(string $slug): self
    {
        $e = new self("Voter slug '{$slug}' not found");
        $e->userMessage = 'Your voting link is invalid. Please start again from the dashboard.';
        $e->logLevel = 'warning';
        $e->context = ['slug' => $slug];
        return $e;
    }
    
    public static function expired(): self
    {
        $minutes = config('voting.session_expiry_minutes', 30);
        $e = new self('Voter slug expired');
        $e->userMessage = 'Your voting session has expired. Sessions expire after ' . $minutes . ' minutes.';
        $e->logLevel = 'info';
        return $e;
    }
    
    public static function wrongUser(int $slugUserId, int $currentUserId): self
    {
        $e = new self("User {$currentUserId} attempted to use slug belonging to user {$slugUserId}");
        $e->userMessage = 'This voting link belongs to another user. Please use your own link.';
        $e->logLevel = 'warning';
        $e->context = ['slug_user' => $slugUserId, 'current_user' => $currentUserId];
        return $e;
    }
    
    public static function deactivated(): self
    {
        $e = new self('Voter slug deactivated');
        $e->userMessage = 'Your voting session has been deactivated. Please start a new session.';
        $e->logLevel = 'info';
        return $e;
    }
}
```

## 🎯 STEP 2: Central Exception Handler

```php
<?php
// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        // Don't report these to error tracking services
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log with appropriate level based on exception type
            if ($e instanceof VotingException) {
                Log::channel('voting')->{$e->getLogLevel()}($e->getMessage(), $e->getContext() ?? []);
            } else {
                Log::error($e->getMessage(), ['exception' => get_class($e)]);
            }
        });

        $this->renderable(function (Throwable $e, $request) {
            // Only handle Inertia requests (SPA)
            if ($request->inertia()) {
                return $this->handleInertiaException($e, $request);
            }
            
            // Let Laravel handle API/other requests normally
            return null;
        });
    }
    
    protected function handleInertiaException(Throwable $e, $request)
    {
        $statusCode = $this->getStatusCode($e);
        $userMessage = $this->getUserMessage($e);
        $redirectTo = $this->getRedirectRoute($e);
        
        // Log if needed
        if (!($e instanceof VotingException)) {
            Log::error('Unhandled exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl()
            ]);
        }
        
        // For AJAX requests from Vue components
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $userMessage,
                'redirect' => $redirectTo ? route($redirectTo) : null
            ], $statusCode);
        }
        
        // For Inertia page loads
        if ($redirectTo) {
            return redirect()->route($redirectTo)
                ->with('error', $userMessage);
        }
        
        // Stay on current page but show error
        return back()->with('error', $userMessage);
    }
    
    protected function getUserMessage(Throwable $e): string
    {
        if ($e instanceof VotingException) {
            return $e->getUserMessage();
        }
        
        // Default messages for common exceptions
        return match (get_class($e)) {
            \Illuminate\Database\Eloquent\ModelNotFoundException::class => 
                'The requested resource was not found.',
                
            \Illuminate\Auth\AuthenticationException::class =>
                'You must be logged in to access this page.',
                
            \Illuminate\Auth\Access\AuthorizationException::class =>
                'You do not have permission to perform this action.',
                
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class =>
                'The page you requested does not exist.',
                
            default =>
                config('app.debug') 
                    ? $e->getMessage() 
                    : 'An unexpected error occurred. Please try again or contact support.'
        };
    }
    
    protected function getStatusCode(Throwable $e): int
    {
        return match (get_class($e)) {
            \Illuminate\Auth\AuthenticationException::class => 401,
            \Illuminate\Auth\Access\AuthorizationException::class => 403,
            \Illuminate\Database\Eloquent\ModelNotFoundException::class,
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => 404,
            default => 500,
        };
    }
    
    protected function getRedirectRoute(Throwable $e): ?string
    {
        return match (get_class($e)) {
            \Illuminate\Auth\AuthenticationException::class => 'login',
            \Illuminate\Auth\Access\AuthorizationException::class => 'dashboard',
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => 'dashboard',
            default => null,
        };
    }
}
```

## 🚀 STEP 3: Simplified Service Code

Now your services become **much cleaner**:

```php
<?php
// app/Services/DemoElectionResolver.php

namespace App\Services;

use App\Models\Election;
use App\Models\User;
use App\Exceptions\ElectionException;
use App\Exceptions\OrganisationException;

class DemoElectionResolver
{
    public function getDemoElectionForUser(User $user): Election
    {
        // Validate organisation exists
        if (!$user->organisation_id) {
            throw OrganisationException::userNotAssigned();
        }
        
        // Try org-specific demo first
        $orgDemo = Election::where('type', 'demo')
            ->where('organisation_id', $user->organisation_id)
            ->where('is_active', true)
            ->first();
        
        if ($orgDemo) {
            return $orgDemo;
        }
        
        // Try global demo
        $globalDemo = Election::where('type', 'demo')
            ->where('organisation_id', 1)
            ->where('is_active', true)
            ->first();
        
        if ($globalDemo) {
            return $globalDemo;
        }
        
        // No demo available - throw with helpful message
        throw ElectionException::noDemoElection($user->organisation_id);
    }
}
```

```php
<?php
// app/Services/VoterSlugService.php

namespace App\Services;

use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Election;
use App\Exceptions\VoterSlugException;
use App\Exceptions\ElectionException;
use App\Exceptions\OrganisationException;

class VoterSlugService
{
    public function getOrCreateActiveSlug(User $user): VoterSlug
    {
        // Validate user has organisation
        if (!$user->organisation_id) {
            throw OrganisationException::userNotAssigned();
        }
        
        // Get selected election
        $electionId = session('selected_election_id');
        $election = Election::find($electionId);
        
        if (!$election) {
            throw ElectionException::notFound($electionId ?? 0);
        }
        
        // Validate election is active
        if (!$election->is_active) {
            throw ElectionException::notActive($election->name);
        }
        
        // Validate organisation access
        if ($election->organisation_id !== 1 && 
            $election->organisation_id !== $user->organisation_id) {
            throw ElectionException::organisationMismatch(
                $election->organisation_id, 
                $user->organisation_id
            );
        }
        
        // Create or get existing slug
        return VoterSlug::firstOrCreate([
            'user_id' => $user->id,
            'election_id' => $election->id,
        ], [
            'organisation_id' => $user->organisation_id,
            'slug' => $this->generateSlug(),
            'expires_at' => now()->addMinutes(config('voting.session_expiry_minutes', 30)),
            'is_active' => true,
        ]);
    }
    
    protected function generateSlug(): string
    {
        return uniqid() . '-' . bin2hex(random_bytes(8));
    }
}
```

## 🎨 STEP 4: Middleware That Throws Exceptions

```php
<?php
// app/Http/Middleware/VerifyVoterSlugConsistency.php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\VoterSlugException;
use App\Exceptions\ElectionException;
use App\Exceptions\OrganisationException;

class VerifyVoterSlugConsistency
{
    public function handle($request, Closure $next)
    {
        $voterSlug = $request->attributes->get('voter_slug');
        $user = auth()->user();
        
        if (!$voterSlug) {
            throw VoterSlugException::notFound($request->route('vslug'));
        }
        
        // Check ownership
        if ($voterSlug->user_id !== $user->id) {
            throw VoterSlugException::wrongUser($voterSlug->user_id, $user->id);
        }
        
        // Check active status
        if (!$voterSlug->is_active) {
            throw VoterSlugException::deactivated();
        }
        
        // Check expiration
        if ($voterSlug->expires_at->isPast()) {
            $voterSlug->update(['is_active' => false]);
            throw VoterSlugException::expired();
        }
        
        // Check election exists
        $election = $voterSlug->election;
        if (!$election) {
            throw ElectionException::notFound($voterSlug->election_id);
        }
        
        // Check organisation consistency
        if ($election->organisation_id !== 1 && 
            $election->organisation_id !== $voterSlug->organisation_id) {
            throw ElectionException::organisationMismatch(
                $election->organisation_id,
                $voterSlug->organisation_id
            );
        }
        
        // Check user still in same organisation
        if ($user->organisation_id !== $voterSlug->organisation_id && 
            $voterSlug->organisation_id !== 1) {
            throw OrganisationException::organisationChanged(
                $voterSlug->organisation_id,
                $user->organisation_id
            );
        }
        
        $request->attributes->set('election', $election);
        return $next($request);
    }
}
```

## 💻 STEP 5: Vue Component to Display Errors

```vue
<!-- resources/js/Components/GlobalErrorHandler.vue -->
<template>
  <div>
    <!-- Error Toast -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-x-full opacity-0"
      enter-to-class="transform translate-x-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-x-0 opacity-100"
      leave-to-class="transform translate-x-full opacity-0"
    >
      <div v-if="errorMessage" class="fixed top-4 right-4 z-50 max-w-md">
        <div class="bg-red-100 border-l-4 border-red-500 p-4 rounded-lg shadow-lg">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1">
              <p class="text-red-800 font-medium" v-html="formattedErrorMessage"></p>
              <div v-if="showAction" class="mt-3">
                <button
                  @click="executeAction"
                  class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                  v-html="actionText"
                />
              </div>
            </div>
            <button @click="dismissError" class="ml-4 text-red-500 hover:text-red-700">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Success Toast -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-x-full opacity-0"
      enter-to-class="transform translate-x-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-x-0 opacity-100"
      leave-to-class="transform translate-x-full opacity-0"
    >
      <div v-if="successMessage" class="fixed top-4 right-4 z-50 max-w-md">
        <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded-lg shadow-lg">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div class="flex-1">
              <p class="text-green-800 font-medium" v-html="successMessage"></p>
            </div>
            <button @click="dismissSuccess" class="ml-4 text-green-500 hover:text-green-700">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()
const dismissed = ref(false)

const errorMessage = computed(() => {
  if (dismissed.value) return null
  return page.props.flash?.error || null
})

const successMessage = computed(() => {
  return page.props.flash?.success || null
})

const formattedErrorMessage = computed(() => {
  if (!errorMessage.value) return ''
  
  // Check if message contains a command to run
  if (errorMessage.value.includes('php artisan')) {
    return errorMessage.value.replace(
      /(php artisan [^<\s]+)/g,
      '<code class="bg-red-200 px-2 py-1 rounded text-red-900">$1</code>'
    )
  }
  
  return errorMessage.value
})

const showAction = computed(() => {
  return errorMessage.value?.includes('php artisan')
})

const actionText = computed(() => {
  if (!errorMessage.value) return ''
  const match = errorMessage.value.match(/php artisan [^<\s]+/)
  return match ? `▶️ Run ${match[0]}` : ''
})

const executeAction = () => {
  if (!errorMessage.value) return
  
  const match = errorMessage.value.match(/php artisan ([^<\s]+)/)
  if (match) {
    // Copy command to clipboard
    navigator.clipboard.writeText(`php artisan ${match[1]}`)
    
    // Show toast that command was copied
    alert('Command copied to clipboard! Run it in your terminal.')
  }
}

const dismissError = () => {
  dismissed.value = true
  // Optionally clear the flash message on server
  router.reload({ data: { clear_error: true } })
}

const dismissSuccess = () => {
  router.reload({ data: { clear_success: true } })
}
</script>
```

## 🎯 THE BENEFITS

| Before | After |
|--------|-------|
| ❌ Error handling scattered everywhere | ✅ Centralized in one place |
| ❌ Inconsistent error messages | ✅ Consistent, user-friendly messages |
| ❌ Exceptions lost or mis-logged | ✅ Proper logging with context |
| ❌ Hard to maintain | ✅ Easy to add new error types |
| ❌ No helpful actions | ✅ Can suggest commands to run |

## 🚀 IMPLEMENTATION SUMMARY

1. **Create custom exception classes** for each domain
2. **Configure the Exception Handler** to catch and format them
3. **Throw exceptions** from services/middleware instead of returning redirects
4. **Create a Vue component** to display errors beautifully
5. **Add the component** to your layout

Now **every error** in your system is handled consistently with:
- ✅ User-friendly messages
- ✅ Proper logging
- ✅ Helpful actions (like showing the command to run)
- ✅ Consistent styling
- ✅ Easy to debug
