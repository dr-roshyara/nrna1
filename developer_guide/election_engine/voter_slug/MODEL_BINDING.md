# Route Model Binding Guide

## Overview

Route model binding in `routes/web.php` is the **entry point** for all voter slug operations. It translates URL slugs into Eloquent model instances.

## The Problem We Solved

Before fix: Only searched `voter_slugs` table.
Result: Demo election URLs returned 404.

## The Solution

Search both tables in order:

```php
Route::bind('vslug', function (string $value) {
    // Step 1: Try to find a real VoterSlug (most common)
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->with('user')
        ->where('slug', $value)
        ->first();

    // Step 2: If not found, try DemoVoterSlug (fallback)
    if (!$voterSlug) {
        $voterSlug = DemoVoterSlug::withoutGlobalScopes()
            ->with('user')
            ->where('slug', $value)
            ->first();
    }

    // Step 3: Fail gracefully
    if (!$voterSlug) {
        abort(404, 'Voting link not found.');
    }

    return $voterSlug;
});
```

## Why This Order?

1. **VoterSlug first** - Real elections are more common
2. **DemoVoterSlug fallback** - Demo elections are optional testing feature
3. **Abort 404** - Clear error if neither model found

## Why withoutGlobalScopes()?

The `BelongsToTenant` trait adds a global scope that filters by `organisation_id`:

```php
// With global scope:
VoterSlug::where('slug', 'abc')  // Filtered by organisation_id!

// Without global scope:
VoterSlug::withoutGlobalScopes()
    ->where('slug', 'abc')  // Returns ALL slugs regardless of org
```

Demo elections have `organisation_id = NULL`, so the global scope would hide them.

## Why with('user')?

Eager load the related user immediately to avoid N+1 queries later:

```php
$voterSlug->user->name  // Already loaded, no extra query
```

## Testing the Binding

1. Create a real voter slug:
```bash
php artisan tinker
$slug = VoterSlug::first();
echo $slug->slug;  // e.g., "abc123xyz"
```

2. Visit the URL:
```
http://localhost:8000/v/abc123xyz/demo-code/create
```

3. If 404: Route binding failed to find the slug

4. Debug with:
```php
// In routes/web.php, temporarily add logging:
Log::info('Searching for slug: ' . $value);
$voterSlug = VoterSlug::withoutGlobalScopes()
    ->where('slug', $value)
    ->first();
Log::info('Found VoterSlug: ' . ($voterSlug ? 'yes' : 'no'));
```

## Common Issues

### Issue: 404 for Real Election Slug

Check:
1. Slug exists: `SELECT * FROM voter_slugs WHERE slug='abc123';`
2. withoutGlobalScopes() is used
3. first() returns model, not error

### Issue: 404 for Demo Election Slug

Check:
1. Slug exists: `SELECT * FROM demo_voter_slugs WHERE slug='abc123';`
2. DemoVoterSlug model imported
3. Fallback search is executed (real slug search came up empty)

### Issue: Wrong Model Type Returned

Check:
1. is_a($voterSlug, VoterSlug::class) vs is_a($voterSlug, DemoVoterSlug::class)
2. Verify which table actually contains the slug

## Accessing the Bound Model

Once bound, the model is available in route parameters:

```php
// In controller:
public function create(Request $request, VoterSlug|DemoVoterSlug $vslug)
{
    // $vslug is already the Eloquent model instance
    echo $vslug->slug;
    echo $vslug->user->name;
}

// In middleware:
$vslug = $request->route('vslug');  // Get the bound model
```

## Important Notes

- Binding happens **before** middleware
- Type hints in controllers must accept both: `VoterSlug|DemoVoterSlug`
- The bound model is available to all downstream middleware
- Binding errors (404) bypass all middleware

## Future Considerations

If you add more slug types in the future:

```php
Route::bind('vslug', function (string $value) {
    $voterSlug = VoterSlug::withoutGlobalScopes()
        ->where('slug', $value)->first();

    if (!$voterSlug) {
        $voterSlug = DemoVoterSlug::withoutGlobalScopes()
            ->where('slug', $value)->first();
    }

    // Add new types here
    if (!$voterSlug) {
        $voterSlug = AdminVoterSlug::withoutGlobalScopes()  // hypothetical
            ->where('slug', $value)->first();
    }

    if (!$voterSlug) abort(404);
    return $voterSlug;
});
```

The pattern scales: check most common first, then less common variants.
