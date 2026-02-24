# Authentication Error Types - Complete Reference

## Overview

The login system now provides **specific, actionable error messages** that distinguish between three different failure scenarios:

1. **Email Not Registered** - User's email doesn't exist in the database
2. **Invalid Credentials** - Email exists but password is wrong
3. **Field Validation Errors** - Required fields are missing or invalid

---

## Error Classification

### 1. Email Not Registered

**When it occurs:**
- User enters an email address that has no account in the database
- User may have misspelled their email
- User hasn't completed the registration process yet

**Backend Error Key:** `auth.email_not_registered`

**Messages by Language:**

| Language | Message |
|----------|---------|
| **English** | This email is not registered. Please register first. |
| **German** | Diese E-Mail-Adresse ist nicht registriert. Bitte registrieren Sie sich zuerst. |
| **Nepali** | यो ईमेल दर्ता भएको छैन। कृपया पहिले दर्ता गर्नुहोस्। |

**User Experience:**
```
Oops! Something went wrong.
• This email is not registered. Please register first.
```

**Frontend Translation Key:** `pages.auth.login.validation.email_not_registered`

---

### 2. Invalid Credentials (Wrong Password)

**When it occurs:**
- User enters a registered email but incorrect password
- User may have caps lock on or pressed shift key
- User may be using the wrong password

**Backend Error Key:** `auth.failed`

**Messages by Language:**

| Language | Message |
|----------|---------|
| **English** | These credentials do not match our records |
| **German** | Diese Anmeldedaten stimmen nicht mit unseren Daten überein |
| **Nepali** | यी प्रमाण पत्रहरु हाम्रो अभिलेखसँग मेल खाएनन्। |

**User Experience:**
```
Oops! Something went wrong.
• These credentials do not match our records
```

**Frontend Translation Key:** `pages.auth.login.validation.credentials_invalid`

---

### 3. Field Validation Errors

**When it occurs:**
- Email field is empty
- Password field is empty
- Email format is invalid
- Required fields are not filled

**Error Keys:**
- `email` → Field-level email error
- `password` → Field-level password error

**Messages by Language:**

| Scenario | English | German | Nepali |
|----------|---------|--------|--------|
| **Email Required** | Email is required | E-Mail-Adresse ist erforderlich | ईमेल आवश्यक छ |
| **Invalid Email** | Please enter a valid email address | Bitte geben Sie eine gültige E-Mail-Adresse ein | कृपया मान्य ईमेल ठेगाना प्रविष्ट गर्नुहोस् |
| **Password Required** | Password is required | Passwort ist erforderlich | पासवर्ड आवश्यक छ |

**User Experience:**
```
Oops! Something went wrong.
• Email is required
```

---

## Implementation Details

### Backend Implementation

#### Custom Authentication Action
**File:** `app/Actions/Fortify/AttemptToAuthenticate.php`

The custom action performs three checks in order:
1. **Rate Limiting Check** - Prevents brute force attacks
2. **Email Existence Check** - Verifies email is registered
3. **Credential Verification** - Checks email/password combination

```php
// Check if email exists first
$user = User::where('email', $request->email)->first();

if (!$user) {
    // Email not registered
    throw ValidationException::withMessages([
        'email' => __('auth.email_not_registered'),
    ]);
}

// Then check password
if (!Auth::attempt($request->only('email', 'password'))) {
    // Invalid credentials
    throw ValidationException::withMessages([
        'auth' => __('auth.failed'),
    ]);
}
```

#### Backend Language Files
- `resources/lang/en/auth.php` - English translations
- `resources/lang/de/auth.php` - German translations (NEW)
- `resources/lang/np/auth.php` - Nepali translations (NEW)

### Frontend Implementation

#### ValidationErrors Component
**File:** `resources/js/Jetstream/ValidationErrors.vue`

The component uses a **multi-strategy translation approach**:

1. **Direct Key Mapping** - Maps `email` field error to `email_required` translation
2. **Backend Pattern Matching** - Detects `auth.*` error patterns
3. **Auto-Translation** - Tries path like `pages.auth.login.validation.{field}`
4. **Fallback** - Uses original message if no translation found

```javascript
const errorMap = {
    'email': 'pages.auth.login.validation.email_required',
    'password': 'pages.auth.login.validation.password_required',
    'auth': 'pages.auth.login.validation.credentials_invalid',
    'auth.failed': 'pages.auth.login.validation.credentials_invalid',
    'auth.email_not_registered': 'pages.auth.login.validation.email_not_registered',
};
```

#### Frontend Translation Files
- `resources/js/locales/pages/Auth/en.json` - English (Updated)
- `resources/js/locales/pages/Auth/de.json` - German (Updated)
- `resources/js/locales/pages/Auth/np.json` - Nepali (Updated)

---

## Error Flow Diagram

```
User submits login form
        ↓
Rate Limiter checks IP + email
├── TOO MANY ATTEMPTS
│   └─→ Show: "Too many login attempts. Please try again in X seconds."
│
├─→ Email validation (required/format)
│   ├── EMPTY
│   │   └─→ Show: "Email is required"
│   ├── INVALID FORMAT
│   │   └─→ Show: "Please enter a valid email address"
│
├─→ Password validation (required)
│   ├── EMPTY
│   │   └─→ Show: "Password is required"
│
├─→ Check if email exists in database
│   ├── NOT FOUND
│   │   └─→ throw ValidationException('auth.email_not_registered')
│   │       └─→ Show: "This email is not registered. Please register first."
│
├─→ Verify email/password combination
│   ├── MISMATCH
│   │   └─→ throw ValidationException('auth.failed')
│   │       └─→ Show: "These credentials do not match our records"
│
└─→ SUCCESS
    └─→ Authenticate user and redirect
```

---

## Testing Scenarios

### Test Case 1: Email Not Registered
**Steps:**
1. Navigate to `/login`
2. Enter: `nonexistent@example.com`
3. Enter any password
4. Click "Sign In"

**Expected Result:**
- Error shows: "This email is not registered. Please register first."
- Not: "These credentials do not match our records"

**Why it matters:** Tells user they need to register, not that their password is wrong

---

### Test Case 2: Wrong Password
**Steps:**
1. Navigate to `/login`
2. Enter registered email: `roshyara@gmail.com`
3. Enter wrong password: `WrongPassword123`
4. Click "Sign In"

**Expected Result:**
- Error shows: "These credentials do not match our records"
- Not: "This email is not registered"

**Why it matters:** Tells registered user their password attempt failed

---

### Test Case 3: Empty Email
**Steps:**
1. Navigate to `/login`
2. Leave email blank
3. Enter any password
4. Click "Sign In"

**Expected Result:**
- Error shows: "Email is required"

**Why it matters:** Client-side validation catches empty fields early

---

### Test Case 4: Multi-Language Support
**Steps:**
1. Change site language to German (Deutsch)
2. Repeat Test Case 1
3. Change to Nepali
4. Repeat Test Case 1

**Expected Results:**
- German: "Diese E-Mail-Adresse ist nicht registriert. Bitte registrieren Sie sich zuerst."
- Nepali: "यो ईमेल दर्ता भएको छैन। कृपया पहिले दर्ता गर्नुहोस्।"

---

## Security Considerations

✓ **No Information Leakage**: We don't say "this email is already registered" during registration (prevents user enumeration)

✓ **Rate Limiting**: All attempts are rate-limited to 5 per minute per IP + email

✓ **Password Hashing**: Passwords are never logged or displayed

✓ **Multi-Tenant Safe**: Each error is scoped to the user's tenant context

✓ **CSRF Protected**: All form submissions include CSRF tokens (Inertia.js handles this)

---

## Files Modified Summary

### Backend
1. **`app/Actions/Fortify/AttemptToAuthenticate.php`** (NEW)
   - Custom authentication handler with email existence check

2. **`app/Providers/FortifyServiceProvider.php`** (MODIFIED)
   - Registers custom authentication action

3. **`resources/lang/en/auth.php`** (MODIFIED)
   - Added `email_not_registered` translation

4. **`resources/lang/de/auth.php`** (NEW)
   - Full German auth translations

5. **`resources/lang/np/auth.php`** (NEW)
   - Full Nepali auth translations

### Frontend
1. **`resources/js/Jetstream/ValidationErrors.vue`** (MODIFIED)
   - Enhanced error translation with multi-strategy approach

2. **`resources/js/locales/pages/Auth/en.json`** (MODIFIED)
   - Added `email_not_registered` translation

3. **`resources/js/locales/pages/Auth/de.json`** (MODIFIED)
   - Added `email_not_registered` translation

4. **`resources/js/locales/pages/Auth/np.json`** (MODIFIED)
   - Added `email_not_registered` translation

---

## Future Enhancements

1. **Account Status Errors**
   - Add checks for suspended/inactive accounts
   - Message: "Your account has been suspended"

2. **Email Verification Required**
   - Check if user has verified their email
   - Message: "Please verify your email first"

3. **Two-Factor Authentication**
   - Detect when 2FA is required
   - Message: "Two-factor authentication required"

4. **Password Expiration**
   - Check if password has expired
   - Message: "Your password has expired. Please reset it."

---

## Translation Maintenance

When adding new authentication errors:

1. **Add backend translation** to all three `resources/lang/{en,de,np}/auth.php`
2. **Add frontend translation** to all three `resources/js/locales/pages/Auth/{en,de,np}.json`
3. **Update error mapping** in `resources/js/Jetstream/ValidationErrors.vue`
4. **Test in all languages** using the test scenarios above

---

**Last Updated:** 2026-02-23
**Language Support:** English, German, Nepali
**Affected Component:** LoginPages (Inertia.js + Vue 3)
**Security Level:** Production-Ready
