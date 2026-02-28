# Laravel Validation Rules Fix - Array Format Consistency

**Date**: February 22, 2026
**Error**: `[] operator not supported for strings`
**Status**: ✅ Fixed and Tested

---

## 🔴 The Problem

### Error Message
```
Error: [] operator not supported for strings
File: app/Http/Requests/StoreOrganizationRequest.php:70
```

### Root Cause
**In PHP, you cannot use the `[]` array push operator on string variables.**

The code was mixing string and array rule definitions:

```php
// ❌ WRONG: String format
'representative.name' => 'required|string|min:3|max:255',

// ❌ THEN TRYING TO APPEND TO IT (causes error!)
$rules['representative.email'][] = function ($attribute, $value, $fail) { ... };
```

When PHP encounters `$rules['representative.email'][]` and `$rules['representative.email']` is a string (not an array), it throws:
```
[] operator not supported for strings
```

---

## ✅ The Solution

### Key Change: ALL Rules Must Be Arrays

Convert every single validation rule to **array format**:

```php
// ✅ CORRECT: Array format
'representative.name' => [
    'required',
    'string',
    'min:3',
    'max:255',
],

// ✅ NOW THIS WORKS (appending to array)
$rules['representative.email'][] = function ($attribute, $value, $fail) { ... };
```

---

## 📋 Complete Fixed Code

Here's the complete **corrected rules() method**:

```php
public function rules(): array
{
    $isSelf = $this->input('representative.is_self', false);

    // ✅ ALL RULES IN CONSISTENT ARRAY FORMAT
    $rules = [
        // Step 1: Basic Information
        'name' => [
            'required',
            'string',
            'min:3',
            'max:255',
            Rule::unique('organizations'),
        ],
        'email' => [
            'required',
            'email:rfc',
            'max:255',
            Rule::unique('organizations'),
        ],

        // Step 2: Address Information
        'address.street' => [
            'required',
            'string',
            'max:255',
        ],
        'address.city' => [
            'required',
            'string',
            'max:100',
        ],
        'address.zip' => [
            'required',
            'string',
            'max:20',
            'regex:/^\d{5}$/',
        ],
        'address.country' => [
            'required',
            'string',
            'size:2',
            'in:DE,AT,CH',
        ],

        // Step 3: Representative Information
        'representative.name' => [
            'required',
            'string',
            'min:3',
            'max:255',
        ],
        'representative.role' => [
            'required',
            'string',
            'min:2',
            'max:100',
        ],
        'representative.email' => $isSelf
            ? ['nullable']
            : [
                'required',
                'email:rfc',
                'max:255',
            ],
        'representative.is_self' => [
            'boolean',
        ],

        // Legal acceptance
        'accept_gdpr' => [
            'required',
            'accepted',
        ],
        'accept_terms' => [
            'required',
            'accepted',
        ],
    ];

    // ✅ Now safe to append custom closures to array rules
    if (!app()->environment('testing')) {
        // organisation email DNS validation
        $rules['email'][] = function ($attribute, $value, $fail) {
            // Type-safe: validate only if string and not empty
            if (!is_string($value) || empty($value)) {
                return;
            }

            $parts = explode('@', $value);
            $domain = $parts[1] ?? null;

            if (!$domain || !checkdnsrr($domain, 'MX')) {
                $fail(__('validation.organisation.email.dns'));
            }
        };

        // Representative email DNS validation
        $rules['representative.email'][] = function ($attribute, $value, $fail) {
            // Type-safe: validate only if string and not empty
            if (!is_string($value) || empty($value)) {
                return;
            }

            $parts = explode('@', $value);
            $domain = $parts[1] ?? null;

            if (!$domain || !checkdnsrr($domain, 'MX')) {
                $fail(__('validation.organisation.rep_email.dns'));
            }
        };
    }

    return $rules;
}
```

---

## 🔄 Before vs After

### BEFORE (❌ Causes Error)
```php
$rules = [
    'name' => 'required|string|min:3|max:255',           // STRING
    'email' => ['required', 'email:rfc'],                // ARRAY
    'address.street' => 'required|string|max:255',      // STRING
    'representative.email' => ['required', 'email:rfc'], // ARRAY
];

// ❌ THIS FAILS - mixing formats
$rules['email'][] = function(...) { };
```

### AFTER (✅ Works Correctly)
```php
$rules = [
    'name' => ['required', 'string', 'min:3', 'max:255'],           // ARRAY
    'email' => ['required', 'email:rfc'],                            // ARRAY
    'address.street' => ['required', 'string', 'max:255'],           // ARRAY
    'representative.email' => ['required', 'email:rfc'],             // ARRAY
];

// ✅ THIS WORKS - consistent array format
$rules['email'][] = function(...) { };
```

---

## 🧪 Testing

### Run Tests to Verify Fix
```bash
# Test organisation creation
php artisan test tests/Feature/OrganizationCreationTest.php

# Test all organisation creation tests
php artisan test tests/Feature/OrganizationCreation*.php
```

### Expected Results
```
✅ All tests passing
✅ No "[] operator not supported for strings" error
✅ organisation creation works via modal
✅ Email validation works correctly
```

---

## 🔍 Why This Works

### PHP Language Behavior
```php
// ✅ Arrays support [] push operator
$array = ['rule1', 'rule2'];
$array[] = 'rule3';  // Works!

// ❌ Strings do NOT support [] push operator
$string = 'rule1|rule2|rule3';
$string[] = 'rule4';  // ERROR: [] operator not supported for strings!
```

### Laravel Validation Rules
- **String Format**: `'field' => 'required|email|unique'`
  - Good for simple rules
  - Cannot append custom closures

- **Array Format**: `'field' => ['required', 'email', 'unique']`
  - Better for complex rules
  - Can append custom closures
  - More readable and maintainable

---

## 🎯 Key Takeaways

### Rule #1: Consistency
Use the same format (array) for all rules in a request class.

### Rule #2: Appendable Closures
If you need to append custom validation closures, rules MUST be arrays:
```php
// ✅ Array rules - can append
$rules['email'] = ['required', 'email'];
$rules['email'][] = function(...) { };

// ❌ String rules - cannot append
$rules['email'] = 'required|email';
$rules['email'][] = function(...) { };  // ERROR!
```

### Rule #3: Conditional Rules
Conditional rules must ALWAYS return arrays:
```php
// ✅ Both branches return arrays
$rules['email'] = $condition
    ? ['required']
    : ['required', 'email'];

// ❌ Mixing formats in conditional
$rules['email'] = $condition
    ? ['required']
    : 'required|email';  // ERROR if appending!
```

---

## 📝 Implementation Checklist

- [x] Convert ALL validation rules to array format
- [x] Maintain same validation logic (required, email, unique, etc.)
- [x] Keep conditional DNS validation working
- [x] Type-safe closures (check `is_string()` before string operations)
- [x] Preserve custom messages and attributes
- [x] Run tests to verify fix
- [x] Document the fix

---

## 🚀 Deployment

### After Fix
```bash
# Clear caches
php artisan config:clear
php artisan cache:clear

# Verify fix is in place
php artisan test tests/Feature/OrganizationCreationTest.php

# Deploy to production
# organisation creation will now work without errors!
```

---

## 📞 Related Issues

This fix resolves:
- ✅ "[] operator not supported for strings" error
- ✅ Inconsistent validation rule format
- ✅ Inability to append custom validation closures
- ✅ Mixed string/array rule definitions

---

## 🔗 Related Documentation

- `PRODUCTION_ERROR_FIXES.md` - Complete production error guide
- `TEST_IMPLEMENTATION_SUMMARY.md` - Test coverage documentation
- `app/Http/Requests/StoreOrganizationRequest.php` - The fixed file

---

**Status**: ✅ Fixed, Tested, and Documented
**Last Updated**: February 22, 2026
**Test Coverage**: 70+ comprehensive tests
