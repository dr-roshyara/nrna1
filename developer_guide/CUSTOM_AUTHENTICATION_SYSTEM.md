# Custom Authentication System Guide

**Last Updated:** 2026-02-23
**Version:** 1.0
**Language Support:** English, German, Nepali

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Key Features](#key-features)
4. [File Structure](#file-structure)
5. [Component Details](#component-details)
6. [How It Works](#how-it-works)
7. [Error Handling](#error-handling)
8. [Email Verification](#email-verification)
9. [Multi-Language Support](#multi-language-support)
10. [Customization Guide](#customization-guide)
11. [Testing & Debugging](#testing--debugging)
12. [Security Considerations](#security-considerations)

---

## Overview

This document explains the **custom authentication system** built for PUBLIC DIGIT. It replaces Laravel Fortify's default login flow with a specialized system that provides:

- ✅ **Three distinct error messages** - Different messages for unregistered email, wrong password, and missing fields
- ✅ **Professional email verification** - Branded HTML emails in 3 languages
- ✅ **Rate limiting** - 5 login attempts per minute per IP+email
- ✅ **Multi-language support** - Complete translations for English, German, and Nepali
- ✅ **Custom routes** - Full control over authentication endpoints

---

## Architecture

### High-Level Flow

```
User Registration
       ↓
Verification Email Sent
       ↓
User Clicks Verification Link
       ↓
Email Verified
       ↓
User Logs In
       ↓
Custom Login Controller
       ├─ Validate Form Fields
       ├─ Check Rate Limiting
       ├─ Check Email Exists in DB (NEW!)
       ├─ Verify Password
       └─ Authenticate User
       ↓
Dashboard
```

### Technology Stack

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Frontend** | Vue 3 + Inertia.js | Login/Register pages |
| **Backend** | Laravel 12 | Authentication logic |
| **Email** | Laravel Mail (Mailable) | Professional notifications |
| **Database** | MySQL/PostgreSQL | User storage |
| **Validation** | Laravel Validation | Form validation |
| **i18n** | Vue i18n + Laravel translations | Multi-language support |

### Authentication Flow Diagram

```
POST /login
    ↓
LoginController@store
    ├─ Validate email & password fields
    ├─ Check rate limits (5/minute)
    ├─ Query: User::where('email', $email)->first()
    │   ├─ User found? → Continue
    │   └─ Not found? → Return 'auth.email_not_registered' error
    ├─ Auth::attempt(['email' => $email, 'password' => $password])
    │   ├─ Match found? → Login successful
    │   └─ No match? → Return 'auth.failed' error
    ├─ Clear rate limit cache
    └─ Redirect to dashboard
```

---

## Key Features

### 1. Email Existence Checking

**Problem Solved:** Previously, system couldn't distinguish between "email doesn't exist" and "wrong password"

**Solution:** Custom `LoginController` checks email existence BEFORE password verification

```php
// Check if email exists
$user = User::where('email', $email)->first();
if (!$user) {
    throw ValidationException::withMessages([
        'email' => 'auth.email_not_registered',
    ]);
}

// Then check password
if (!Auth::attempt(['email' => $email, 'password' => $password])) {
    throw ValidationException::withMessages([
        'password' => 'auth.failed',
    ]);
}
```

### 2. Three Distinct Error Types

| Scenario | Error Key | Message |
|----------|-----------|---------|
| **Email not in database** | `auth.email_not_registered` | "This email is not registered. Please register first." |
| **Email exists but wrong password** | `auth.failed` | "These credentials do not match our records" |
| **Missing or invalid fields** | `email_required` | "Email is required" |
| **Too many attempts** | `auth.throttle` | "Too many login attempts. Please try again later." |

### 3. Professional Email Verification

**Features:**
- Branded HTML template with PUBLIC DIGIT colors
- Multi-language support (EN, DE, NP)
- Security messaging (60-min expiry, never asks for passwords)
- Mobile-responsive design
- Backup link for email clients that don't render buttons

### 4. Rate Limiting

```php
$maxAttempts = 5;      // 5 attempts allowed
$decayMinutes = 1;     // Per 1 minute window
// Unique key: email + IP address
```

---

## File Structure

### Core Authentication Files

```
app/
├── Http/
│   └── Controllers/
│       └── Auth/
│           ├── LoginController.php          ✨ NEW - Custom login handler
│           └── (other auth controllers...)
├── Models/
│   └── User.php                             (Modified - added email verification method)
├── Mail/
│   └── VerifyEmailMail.php                  ✨ NEW - Professional email mailable
└── Http/
    └── Requests/
        └── LoginRequest.php                 ✨ NEW - Login form request (optional)

routes/
├── web.php                                  (Modified - added custom auth routes)
└── (other route files...)

resources/
├── views/
│   ├── emails/
│   │   └── verify-email.blade.php          ✨ NEW - Email template
│   └── Auth/
│       ├── Login.vue                        (Unchanged - works with new routes)
│       ├── Register.vue
│       └── ForgotPassword.vue
├── lang/
│   ├── en/
│   │   ├── auth.php                         (Modified - added email_not_registered)
│   │   └── emails.php                       ✨ NEW - English email translations
│   ├── de/
│   │   ├── auth.php                         ✨ NEW - German auth translations
│   │   └── emails.php                       ✨ NEW - German email translations
│   └── np/
│       ├── auth.php                         ✨ NEW - Nepali auth translations
│       └── emails.php                       ✨ NEW - Nepali email translations
└── js/
    ├── Jetstream/
    │   └── ValidationErrors.vue             (Modified - enhanced error mapping)
    └── locales/
        ├── pages/
        │   └── Auth/
        │       ├── en.json                  (Modified - added error translations)
        │       ├── de.json                  (Modified - added error translations)
        │       └── np.json                  (Modified - added error translations)

config/
└── fortify.php                              (Modified - disabled built-in views)
```

**Legend:** ✨ = Newly created, (Modified) = Updated existing file

---

## Component Details

### 1. LoginController

**Location:** `app/Http/Controllers/Auth/LoginController.php`

**Responsibilities:**
- Display login page (`show()` method)
- Handle login submission (`store()` method)
- Handle logout (`destroy()` method)

**Key Methods:**

```php
public function show()
{
    // Renders Auth/Login Inertia component
    // Passes: canResetPassword, canRegister, status
}

public function store(Request $request)
{
    // 1. Validates email & password
    // 2. Checks rate limits
    // 3. Checks if email exists in DB
    // 4. Verifies password
    // 5. Logs user in
}

public function destroy(Request $request)
{
    // Logs user out and clears session
}
```

**Rate Limiting:**
```php
protected $maxAttempts = 5;      // Attempts per minute
protected $decayMinutes = 1;     // Time window

// Throttle key: strtolower(email) + IP address
```

---

### 2. VerifyEmailMail Mailable

**Location:** `app/Mail/VerifyEmailMail.php`

**Purpose:** Sends professional branded email verification

**Constructor Parameters:**
```php
__construct($user, $verificationUrl)
// $user - User model instance
// $verificationUrl - Signed URL for verification
```

**Handles:**
- Email subject (translatable)
- From address (MAIL_FROM_ADDRESS config)
- Template rendering with user data
- Language detection from app locale

---

### 3. Email Template

**Location:** `resources/views/emails/verify-email.blade.php`

**Features:**
- Responsive HTML/CSS
- PUBLIC DIGIT brand colors (blue #2563eb)
- Gradient header
- Professional button styling
- Security messaging
- Backup link for email clients
- Mobile-optimized

**Dynamic Variables:**
- `{{ $user->first_name }}` - User's first name
- `{{ $verificationUrl }}` - Verification link
- All text uses `{{ __('key') }}` for translations

---

### 4. ValidationErrors Component

**Location:** `resources/js/Jetstream/ValidationErrors.vue`

**Purpose:** Displays form validation errors with proper translations

**Error Translation Strategy:**

```javascript
const authErrorMap = {
    'auth.failed': 'pages.auth.login.validation.credentials_invalid',
    'auth.email_not_registered': 'pages.auth.login.validation.email_not_registered',
    'auth.throttle': 'pages.auth.login.validation.throttle',
};

// When backend returns: { email: 'auth.email_not_registered' }
// Component translates to: 'This email is not registered...'
```

---

## How It Works

### Login Flow (Step-by-Step)

**1. User Submits Form**
```html
POST /login
{
    email: "user@example.com",
    password: "password123",
    remember: true
}
```

**2. Controller Validates Input**
```php
// Validate form
$validated = $request->validate([
    'email' => 'required|string|email',
    'password' => 'required|string',
]);
```

**3. Rate Limit Check**
```php
// Check: 5 attempts per minute per email+IP
if (RateLimiter::tooManyAttempts($key, 5)) {
    throw ValidationException::withMessages([
        'email' => 'auth.throttle',
    ]);
}
```

**4. Email Existence Check** ⭐ KEY FEATURE
```php
$user = User::where('email', $validated['email'])->first();

if (!$user) {
    RateLimiter::hit($key);  // Count this attempt
    throw ValidationException::withMessages([
        'email' => 'auth.email_not_registered',
    ]);
}
```

**5. Password Verification**
```php
if (!Auth::attempt($validated, $request->boolean('remember'))) {
    RateLimiter::hit($key);  // Count this attempt
    throw ValidationException::withMessages([
        'password' => 'auth.failed',
    ]);
}
```

**6. Success**
```php
RateLimiter::clear($key);  // Clear rate limit
return redirect()->intended(route('electiondashboard'));
```

**7. Error Response to Frontend**
```json
{
    "errors": {
        "email": "auth.email_not_registered"
    }
}
```

**8. Frontend Translates Error**
- ValidationErrors component receives error message
- Maps backend error key to translation key
- Displays translated message to user

---

## Error Handling

### Error Flow Diagram

```
Backend throws ValidationException
        ↓
Inertia captures error
        ↓
Passes to $page.props.errors
        ↓
Vue component receives error
        ↓
ValidationErrors.vue processes:
    ├─ Check if error is auth.* pattern
    ├─ Map to translation key
    ├─ Look up translation
    └─ Display translated message
        ↓
User sees professional error message
```

### Error Mapping Examples

| Backend Error | Frontend Key | English | German |
|---------------|-------------|---------|--------|
| `auth.email_not_registered` | `pages.auth.login.validation.email_not_registered` | "This email is not registered. Please register first." | "Diese E-Mail-Adresse ist nicht registriert. Bitte registrieren Sie sich zuerst." |
| `auth.failed` | `pages.auth.login.validation.credentials_invalid` | "These credentials do not match our records" | "Diese Anmeldedaten stimmen nicht mit unseren Daten überein" |
| `auth.throttle` | `pages.auth.login.validation.throttle` | "Too many login attempts. Please try again later." | "Zu viele Anmeldeversuche. Bitte versuchen Sie es später erneut." |

---

## Email Verification

### Email Sending Flow

**1. User Registers**
```php
// In Fortify's registered user action
$user = User::create([...]);
// Fortify automatically calls sendEmailVerificationNotification()
```

**2. Custom Method Sends Email**
```php
// User.php - sendEmailVerificationNotification()
$verificationUrl = URL::temporarySignedRoute(
    'verification.verify',
    Carbon::now()->addMinutes(60),
    ['id' => $this->id, 'hash' => sha1($this->email)]
);

Mail::send(new VerifyEmailMail($this, $verificationUrl));
```

**3. Email Rendered**
- Template: `resources/views/emails/verify-email.blade.php`
- Language: App's current locale (en, de, np)
- Variables: User name, verification URL
- Styling: Inline CSS for email clients

**4. User Receives Email**
```
From: noreply@publicdigit.com (PUBLIC DIGIT)
Subject: Verify Your Email Address (or German/Nepali equivalent)

Body: Professional HTML with:
  - Brand colors
  - Verification button
  - Security messaging
  - Backup link
```

**5. User Clicks Verification Link**
```
GET /email/verify/{userId}/{hash}?expires=...&signature=...
```

**6. Email Verified**
```php
// Fortify automatically:
// 1. Verifies signature
// 2. Checks expiration
// 3. Sets email_verified_at
// 4. Redirects to dashboard
```

---

## Multi-Language Support

### Language Detection

**Priority Order:**
1. Window variable (`window.__initialLocale`)
2. LocalStorage (`preferred_locale`)
3. Environment variable (`MIX_DEFAULT_LOCALE`)
4. Default (German - `de`)

### Supported Languages

| Code | Language | Support |
|------|----------|---------|
| `en` | English | ✅ Complete |
| `de` | German (Deutsch) | ✅ Complete |
| `np` | Nepali | ✅ Complete |

### Translation Files

#### Backend (PHP)
```
resources/lang/
├── en/
│   ├── auth.php         # Auth messages (failed, throttle, email_not_registered)
│   └── emails.php       # Email template text
├── de/
│   ├── auth.php
│   └── emails.php
└── np/
    ├── auth.php
    └── emails.php
```

#### Frontend (JavaScript)
```
resources/js/locales/
├── pages/Auth/
│   ├── en.json          # Login/Register page translations
│   ├── de.json
│   └── np.json
└── (other language files)
```

### Example: Adding a New Translation

**German auth.php:**
```php
'custom_error' => 'Benutzerdefinierte Fehlermeldung',
```

**Email template (en.json):**
```json
{
  "hello": "Hello :name,"
}
```

**Login page (de.json):**
```json
{
  "messages": {
    "error": "Anmeldung fehlgeschlagen"
  }
}
```

---

## Customization Guide

### 1. Change Maximum Login Attempts

**File:** `app/Http/Controllers/Auth/LoginController.php`

```php
protected $maxAttempts = 5;      // Change to desired number
protected $decayMinutes = 1;     // Change time window
```

### 2. Customize Email Template

**File:** `resources/views/emails/verify-email.blade.php`

- Modify CSS in `<style>` section
- Change colors (search `#2563eb` for primary blue)
- Update logo and branding
- Edit HTML structure

### 3. Add New Error Type

**Step 1:** Add to backend auth language file
```php
// resources/lang/en/auth.php
'custom_error' => 'Your custom error message',
```

**Step 2:** Add to all language files (de, np)

**Step 3:** Throw error in LoginController
```php
throw ValidationException::withMessages([
    'field_name' => 'auth.custom_error',
]);
```

**Step 4:** Update ValidationErrors component mapping
```javascript
const authErrorMap = {
    'auth.custom_error': 'pages.auth.login.validation.custom_error',
};
```

**Step 5:** Add frontend translation
```json
// resources/js/locales/pages/Auth/en.json
{
  "validation": {
    "custom_error": "Your custom error message"
  }
}
```

### 4. Change Email Sender Address

**File:** `.env`
```bash
MAIL_FROM_ADDRESS=noreply@publicdigit.com
MAIL_FROM_NAME="PUBLIC DIGIT"
```

### 5. Modify Rate Limiting Logic

**File:** `app/Http/Controllers/Auth/LoginController.php`

```php
protected function throttleKey($request)
{
    // Default: email + IP
    return strtolower($request->input('email')) . '|' . $request->ip();

    // Custom: only IP
    // return $request->ip();

    // Custom: only email
    // return strtolower($request->input('email'));
}
```

---

## Testing & Debugging

### Manual Testing Scenarios

#### Test 1: Unregistered Email
```
Email: nonexistent@example.com
Password: anything
Expected: "This email is not registered. Please register first."
```

#### Test 2: Wrong Password
```
Email: registered@example.com
Password: WrongPassword123
Expected: "These credentials do not match our records"
```

#### Test 3: Empty Fields
```
Email: (empty)
Password: anything
Expected: "Email is required"
```

#### Test 4: Rate Limiting
```
1. Submit login 5 times with wrong password
2. On 6th attempt:
   Expected: "Too many login attempts. Please try again later."
```

#### Test 5: Email Verification
```
1. Register with new email
2. Check inbox
3. Email should have:
   - PUBLIC DIGIT branding
   - Blue gradient header
   - Verification button
   - Security messaging
4. Click verification link
5. Should be redirected to dashboard
```

#### Test 6: Multi-Language
```
1. Change site language to German
2. Perform Test 1
3. Error message should be in German
4. Repeat with Nepali
```

### Debug Tips

**Check Rate Limiter Cache:**
```bash
php artisan tinker
>>> Cache::get('throttle:email@example.com|192.168.1.1')
```

**Check User Email Verification Status:**
```bash
php artisan tinker
>>> User::find(1)->email_verified_at
```

**Test Email Sending:**
```bash
php artisan tinker
>>> Mail::send(new \App\Mail\VerifyEmailMail(User::find(1), 'http://test.url'))
```

**Check Translation Keys:**
```bash
php artisan tinker
>>> trans('auth.email_not_registered')
>>> trans('emails.verify_email_subject')
```

---

## Security Considerations

### ✅ Security Features

1. **Rate Limiting**
   - 5 attempts per minute per email+IP
   - Prevents brute force attacks
   - Clears on successful login

2. **Email Verification**
   - 60-minute link expiration
   - Signed URL with hash
   - Prevents unauthorized account activation

3. **Error Messages**
   - Don't reveal if email exists (during registration)
   - Consistent message for wrong credentials
   - No sensitive information in error messages

4. **Password Security**
   - Never displayed in errors or logs
   - Always hashed in database
   - Never sent via email

5. **Session Security**
   - Session invalidation on logout
   - CSRF protection (Inertia.js handles)
   - Secure cookie flags

### ⚠️ Security Best Practices

**When Customizing:**
1. Never log passwords
2. Never reveal if email exists in registration
3. Keep error messages generic for security
4. Validate input on both frontend and backend
5. Use HTTPS in production
6. Keep rate limits reasonable

**Mail Configuration:**
```bash
# .env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## Common Issues & Solutions

### Issue 1: "Route 'login' not found"
**Cause:** Routes not registered or cache not cleared
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
```

### Issue 2: Email not sending
**Cause:** Mail driver misconfigured or MAIL_FROM_ADDRESS not set
**Solution:**
```bash
# Check .env has MAIL_* variables
# Test with: php artisan tinker
>>> Mail::send(new \App\Mail\VerifyEmailMail(User::find(1), 'http://test'))
```

### Issue 3: Error message not translating
**Cause:** Translation key not found in language files
**Solution:**
1. Verify key exists in `resources/lang/{locale}/auth.php`
2. Verify key exists in `resources/js/locales/pages/Auth/{locale}.json`
3. Check error mapping in `ValidationErrors.vue`

### Issue 4: Rate limiting not working
**Cause:** Cache driver not configured
**Solution:**
```bash
# Check .env
CACHE_DRIVER=file  # or redis, memcached
```

---

## Related Documentation

- [Multi-Tenant Architecture](./MULTI_TENANT_ARCHITECTURE.md)
- [Error Translation Strategy](./AUTH_ERROR_TYPES_DOCUMENTATION.md)
- [Email Verification Standards](./AUTH_ERROR_TRANSLATION_STRATEGY.md)

---

## Support & Contributions

For questions or improvements:
1. Check this guide's troubleshooting section
2. Review code comments in relevant files
3. Consult Laravel/Inertia.js documentation
4. Contact development team

---

## Changelog

### Version 1.0 (2026-02-23)
- ✅ Custom LoginController implementation
- ✅ Email existence checking before password verification
- ✅ Three distinct error types
- ✅ Professional email verification template
- ✅ Multi-language support (EN, DE, NP)
- ✅ Rate limiting (5 attempts/minute)
- ✅ Complete documentation

---

**Made with ❤️ for PUBLIC DIGIT**
*Secure, Transparent, Accessible Online Voting*
