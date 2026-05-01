# 🎨 BEFORE ANYTHING: READ UI_GUIDELINES.md

All UI/Vue changes must follow the design system in `.claude/UI_GUIDELINES.md`.

**Quick checklist before editing any .vue file:**
1. Read the Rule Levels (MUST/SHOULD/MAY)
2. Check `design-system.exceptions.json` for approved deviations
3. Use semantic tokens (primary, success, danger, neutral)
4. Use canonical components (<Button>, <Card>, etc.)
5. Run `npm run design-check` after changes
6. Report violations before/after in your response

**The design system is executable.** It's not aspirational. Don't work around it.

---

# 🏗️ Backend Architecture: Laravel with Discipline

**Laravel is the framework. Use it, don't fight it.**

But use it with **discipline**:

```
Infrastructure layer  → Laravel features allowed
Application layer    → Laravel features limited
Domain layer         → No Laravel dependencies
```

---

# Layer Rules with Laravel Pragmatism

## Layer 1: Infrastructure (Laravel allowed)

| Feature | Status | Use Case |
|---------|--------|----------|
| Facades | ✅ Allowed | Cache, Log, Queue, config |
| Eloquent | ✅ Allowed | Read models, simple writes |
| Route model binding | ✅ Allowed | API controllers only |
| Eloquent events | ✅ Allowed | Cache invalidation, logging |
| SoftDeletes | ✅ Allowed | Read models |
| Traits | ✅ Allowed | Testing, infrastructure reuse |

## Layer 2: Application (Limited)

| Feature | Status | Alternative |
|---------|--------|-------------|
| Facades | ❌ Banned | Constructor injection |
| Eloquent | ❌ Banned | Repository interface |
| Route binding | ❌ Banned | Explicit `findOrFail()` |
| Events | ✅ Allowed | Domain events only |
| DTOs | ✅ Required | No arrays |

## Layer 3: Domain (No Laravel)

| Feature | Status |
|---------|--------|
| Anything Laravel | ❌ Completely banned |
| Facades, Eloquent, Events | ❌ Forbidden |
| Pure PHP only | ✅ Required |

---

# Revised Folder Structure

```
app/

# Domain - Pure PHP, no framework dependencies
Domain/
    User/
        Entity/
            User.php              # Business logic, no Eloquent
            UserId.php            # Value object
        Event/
            UserRegistered.php    # Domain event
        Repository/
            UserRepository.php    # Interface only
        Exception/
            UserAlreadyExists.php # Business exception

# Application - Orchestration, limited framework use
Application/
    User/
        Command/
            RegisterUserCommand.php
            ChangePasswordCommand.php
        DTO/
            RegisterUserDto.php   # readonly class
        Handler/
            # Query handlers for reads

# Infrastructure - Laravel allowed freely
Infrastructure/
    Persistence/
        Eloquent/
            UserModel.php          # Eloquent model
        Repository/
            EloquentUserRepository.php  # Implements interface
    Cache/
        RedisCache.php
    Queue/
        LaravelQueueAdapter.php

# Interface - HTTP layer
Http/
    Controllers/
        UserController.php
    Requests/
        RegisterUserRequest.php    # FormRequest, creates DTO
    Resources/
        UserResource.php           # API transformation

# Read Models - Simple, Eloquent allowed
ReadModels/
    UserReadModel.php              # Direct Eloquent queries
```

---

# Revised Rules (15 → 10 Pragmatic Rules)

## Rule 1: Framework Boundaries

**Domain layer: Zero Laravel dependencies.**
**Application layer: Limited Laravel.**
**Infrastructure: Laravel allowed freely.**

```php
// Domain - Pure PHP only
final class User {
    public function __construct(
        private readonly UserId $id,
        private readonly Email $email
    ) {}
}

// Application - Repository interface, no Eloquent
final class RegisterUserCommand {
    public function __construct(
        private readonly UserRepository $repository  // Interface
    ) {}
}

// Infrastructure - Eloquent allowed
final class EloquentUserRepository implements UserRepository {
    public function __construct(
        private readonly UserModel $model  // Eloquent allowed here
    ) {}
}
```

---

## Rule 2: Facades in Infrastructure Only

**Facades allowed in Infrastructure. Forbidden in Application.**

```php
// ✅ Infrastructure - Allowed
final class CacheUserRepository implements UserRepository {
    public function find(UserId $id): ?User {
        return Cache::remember($key, 3600, function() use ($id) {
            return $this->inner->find($id);
        });
    }
}

// ❌ Application - Forbidden
final class RegisterUserCommand {
    public function execute(RegisterUserDto $dto): void {
        Cache::put('user_' . $dto->getId(), $dto); // NO
    }
}
```

---

## Rule 3: Route Model Binding Only in API Controllers

**Allowed for simple read endpoints. Forbidden for write operations.**

```php
// ✅ GET endpoint - allowed
Route::get('/api/users/{user}', function (User $user) {
    return UserResource::make($user);
});

// ❌ POST/PUT/DELETE - use explicit findOrFail
Route::put('/api/users/{id}', function (string $id, UpdateUserRequest $request) {
    $user = User::findOrFail($id);  // Explicit
    // ...
});
```

---

## Rule 4: DTOs for Application Layer (No Arrays)

**Application layer never accepts arrays.**

```php
// ✅ Correct
final class RegisterUserCommand {
    public function execute(RegisterUserDto $dto): UserId { ... }
}

// ✅ FormRequest creates DTO
final class RegisterUserRequest extends FormRequest {
    public function getDto(): RegisterUserDto {
        return new RegisterUserDto(
            Email::fromString($this->get('email')),
            $this->get('name')
        );
    }
}
```

---

## Rule 5: Read Model vs Write Model (CQRS Light)

**Use Eloquent directly for reads. Use Repository + DTO for writes.**

```php
// ✅ Reads - Eloquent directly
final class UserController {
    public function index(): AnonymousResourceCollection {
        return UserResource::collection(User::paginate());
    }
    
    public function show(User $user): UserResource {
        return UserResource::make($user);
    }
}

// ✅ Writes - Repository pattern
final class UpdateUserCommand {
    public function __construct(private readonly UserRepository $repository) {}
    public function execute(UpdateUserDto $dto): void {
        $user = $this->repository->find($dto->getUserId());
        $user->changeEmail($dto->getEmail());
        $this->repository->save($user);
    }
}
```

---

## Rule 6: Value Objects for Domain Concepts

**Email, UserId, Money are Value Objects. Not strings, not ints.**

```php
final readonly class Email {
    private function __construct(private string $value) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$value}");
        }
    }
    
    public static function fromString(string $value): self {
        return new self($value);
    }
    
    public function toString(): string {
        return $this->value;
    }
}
```

---

## Rule 7: Domain Events + Eloquent Events Coexist

| Event Type | Use For |
|------------|---------|
| Domain events | Business logic (OrderPlaced → UpdateInventory) |
| Eloquent events | Infrastructure (saved → ClearCache) |

```php
// ✅ Domain event - business logic
final class Order {
    public function complete(): void {
        $this->record(new OrderCompleted($this->id));
    }
}

// ✅ Eloquent event - infrastructure
class Product extends Model {
    protected static function booted(): void {
        static::saved(function (Product $product) {
            Cache::forget('product_' . $product->id);
        });
    }
}
```

---

## Rule 8: Exceptions by Layer

| Layer | Exception Type | User sees message? |
|-------|---------------|-------------------|
| Domain | `DomainException` | ✅ Yes |
| Application | `ApplicationException` | ✅ Yes |
| Infrastructure | `RuntimeException` | ❌ No (500 error) |

```php
// Domain
final class UserAlreadyExistsException extends DomainException {
    public function __construct(Email $email) {
        parent::__construct("User {$email->toString()} already exists");
    }
}

// Application
final class InvalidCredentialsException extends ApplicationException {
    public function __construct() {
        parent::__construct('Invalid email or password');
    }
}

// Infrastructure - logs only
throw new RuntimeException('Database connection failed');
```

---

## Rule 9: Repository Pattern for Aggregates Only

**Do not create repositories for every table. Only for aggregates.**

```php
// ✅ Aggregate - has repository
final class Order { ... }  // OrderRepository exists
final class User { ... }   // UserRepository exists

// ✅ Simple entity - no repository needed
final class LogEntry { ... }  // Use Eloquent directly

// ✅ Many-to-many - no repository
// Use relationship methods: $user->roles()->attach()
```

---

## Rule 10: Final Classes in Domain Only

| Layer | Final? |
|-------|--------|
| Domain | ✅ All classes final |
| Application | ✅ Command/Handler classes final |
| Infrastructure | ⚠️ Optional (flexibility needed) |
| HTTP | ⚠️ Controllers can be non-final |

---

# Decision Tree: When to Use Each Style

```
Starting new feature:

Is this simple CRUD?
    ↓ YES → Use Eloquent + Controller (Laravel way)
    ↓ NO

Does it have complex business rules?
    ↓ YES → Use Domain + Command + Repository (Your rules)
    ↓ NO → Use Laravel way

Is this only for reading data?
    ↓ YES → Use Eloquent + Resource (Laravel way)
    ↓ NO

Is this a write operation with validation?
    ↓ YES → Use FormRequest + Command + Repository
```

---

# When to Apply Full Clean Architecture

| Project Type | Apply Full Rules? |
|--------------|-------------------|
| Internal admin panel | ❌ Overkill |
| Simple blog | ❌ Not needed |
| E-commerce with complex pricing | ✅ Yes |
| Banking/fintech | ✅ Yes |
| Multi-year enterprise system | ✅ Yes |
| API with 50+ endpoints | ⚠️ Only for complex endpoints |
| Solo developer side project | ❌ No |

---

**The revised rules:**
- Keep the architectural rigor
- Stop fighting the framework
- Use Laravel where it shines (reads, simple CRUD)
- Apply Clean Architecture only where needed (complex writes, business logic)

---

## The Golden Rule

> **Use Laravel for what Laravel is good at.**
> **Use Clean Architecture for what you need to protect.**
> **Know the difference.**  
---


## **⚠️ LARAVEL 11 + INERTIA 2.0 MIGRATION RULES (CRITICAL)**

### **Framework Versions**
- **Laravel:** 8 → 11 (Major version jump)
- **Inertia.js:** 1.0 → 2.0 (Breaking changes)
- **Date:** Post-migration (Inertia 1.0 behavior NO LONGER works)

### **CRITICAL: Form Submissions with Inertia 2.0**

**❌ WRONG (Inertia 1.0 way - BROKEN NOW):**
```javascript
// DO NOT USE - This returns 302 redirects instead of JSON
import { useCsrfRequest } from './useCsrfRequest';
const csrfRequest = useCsrfRequest();
await csrfRequest.post('/endpoint', data);
```

**✅ RIGHT (Inertia 2.0 way - USE THIS):**
```javascript
// ALWAYS use for form submissions on Inertia pages
import { router } from '@inertiajs/vue3';
router.post('/endpoint', data, {
  preserveState: true,
  preserveScroll: true,
  onSuccess: (page) => { /* handle */ },
  onError: (errors) => { /* handle */ },
  onFinish: () => { /* cleanup */ }
});
```

### **Why This Matters:**

| Aspect | Raw Fetch ❌ | Inertia Router ✅ |
|--------|------------|---------------|
| **CSRF Tokens** | Manual setup needed | Automatic (meta tags) |
| **Headers** | `Accept`, `X-Requested-With` not set | Automatic |
| **Redirects** | Returns HTML (breaks app) | Handled correctly |
| **Flash Messages** | Not available | Via `page.props.flash` |
| **Status Codes** | Returns 302 instead of 200 | Returns 200 on success |

### **Backend Controller Pattern:**

```php
// For Inertia 2.0: Simple redirects with flash messages
public function store(Request $request)
{
    try {
        $resource = Model::create($request->validated());

        // ✅ Inertia 2.0: Redirect with flash
        return redirect()->route('resource.show', $resource)
            ->with('success', 'Created successfully!');

    } catch (\Exception $e) {
        // ✅ Return to form with errors
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

### **Key Rules:**

1. **On Inertia Pages:** ALWAYS use `router.post()` for form submissions
2. **Raw Fetch Only:** For standalone API endpoints (not on Inertia pages)
3. **Never Check `wantsJson()`:** Inertia 2.0 handles this automatically
4. **Always Use Callbacks:** `onSuccess`, `onError`, `onFinish` for proper state management
5. **No Manual CSRF:** Inertia 2.0 meta tags handle it automatically

### **Common Pitfall - Status 302 Redirects:**

If you see `HTTP 302 Found` with HTML response:
1. You're using raw `fetch` instead of `router.post()`
2. Controller is returning `redirect()` (correct) but frontend expects JSON (wrong approach)
3. **Fix:** Switch to `router.post()` - Inertia handles redirects properly

---

### **RULE 9: TESTING STRATEGY**
```
- Unit tests MUST test tenant isolation logic
- Always test with multiple TenantId values
- Mock repositories MUST enforce tenant boundaries
- Integration tests MUST use actual tenant database connections
- Test tenant switching scenarios explicitly
```
