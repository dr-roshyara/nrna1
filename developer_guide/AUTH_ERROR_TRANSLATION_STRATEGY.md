# Authentication Error Translation Strategy

## Problem Statement
When users failed to login with invalid credentials, the system displayed the raw Laravel error key `auth.failed` instead of a properly translated, user-friendly error message in German, English, or Nepali.

**Example of the problem:**
```
Whoops! Something went wrong.
auth.failed
```

## Solution Overview
A multi-layered translation strategy has been implemented to translate backend error keys into user-friendly frontend messages in three languages: **English (en)**, **German (de)**, and **Nepali (np)**.

---

## Implementation Details

### 1. Updated ValidationErrors Component
**File:** `resources/js/Jetstream/ValidationErrors.vue`

**Changes:**
- Added a computed property `translatedErrors()` that intelligently translates backend error keys to frontend translation strings
- Implemented error mapping for common authentication errors
- Added fallback mechanism for unmapped errors
- Updated header to use translated text instead of hardcoded "Whoops!"

**Error Mapping Logic:**
```javascript
const errorMap = {
    'email': 'pages.auth.login.validation.email_required',
    'password': 'pages.auth.login.validation.password_required',
    'auth.failed': 'pages.auth.login.validation.credentials_invalid',
};
```

**Translation Fallback Chain:**
1. Check if error key exists in `errorMap` and translation exists
2. Try to auto-translate using pattern `pages.auth.login.validation.{field_name}`
3. Fall back to original error message if no translation found

### 2. Added Translation Keys

**Added to all language files** (`en.json`, `de.json`, `np.json`):
```json
"validation": {
    "errors_header": "Translated error header"
}
```

#### English Translation:
```json
"validation": {
    "errors_header": "Oops! Something went wrong."
}
```

#### German Translation:
```json
"validation": {
    "errors_header": "Oops! Es ist ein Fehler aufgetreten."
}
```

#### Nepali Translation:
```json
"validation": {
    "errors_header": "ओप्स! केहि गलत भयो।"
}
```

### 3. Existing Auth Error Translations

The following translations were already in place and now properly utilized:

#### English (pages.auth.login.validation):
- `email_required`: "Email is required"
- `password_required`: "Password is required"
- `credentials_invalid`: "These credentials do not match our records" ✓ **Used for auth.failed**

#### German (pages.auth.login.validation):
- `email_required`: "E-Mail-Adresse ist erforderlich"
- `password_required`: "Passwort ist erforderlich"
- `credentials_invalid`: "Diese Anmeldedaten stimmen nicht mit unseren Daten überein" ✓ **Used for auth.failed**

#### Nepali (pages.auth.login.validation):
- `email_required`: "ईमेल आवश्यक छ"
- `password_required`: "पासवर्ड आवश्यक छ"
- `credentials_invalid`: "यी प्रमाण पत्रहरु हाम्रो अभिलेखसँग मेल खाएनन्" ✓ **Used for auth.failed**

---

## User Experience Impact

### Before:
```
Whoops! Something went wrong.
auth.failed
```

### After (English):
```
Oops! Something went wrong.
• These credentials do not match our records
```

### After (German):
```
Oops! Es ist ein Fehler aufgetreten.
• Diese Anmeldedaten stimmen nicht mit unseren Daten überein
```

### After (Nepali):
```
ओप्स! केहि गलत भयो।
• यी प्रमाण पत्रहरु हाम्रो अभिलेखसँग मेल खाएनन्
```

---

## Error Code Mapping Reference

| Backend Error Key | Translation Key | Usage |
|------------------|-----------------|-------|
| `auth.failed` | `pages.auth.login.validation.credentials_invalid` | Invalid email/password combination |
| `email` (field) | `pages.auth.login.validation.email_required` | Email field validation error |
| `password` (field) | `pages.auth.login.validation.password_required` | Password field validation error |

---

## How It Works (Technical Flow)

```
User submits login form
        ↓
Laravel validates credentials
        ↓
If validation fails, returns error response with error key:
    Example: { errors: { 'auth.failed': 'These credentials do not match our records' } }
        ↓
Inertia.js passes errors to Vue component via $page.props.errors
        ↓
ValidationErrors.vue receives errors
        ↓
translatedErrors() computed property processes errors:
    1. Check errorMap for backend error key
    2. If found, use mapped translation key
    3. If not found, try auto-map pattern
    4. If still not found, use original message
        ↓
Vue template renders translated messages using {{ error }}
        ↓
User sees properly translated error message in their language!
```

---

## Adding More Error Translations

To add translations for additional backend errors:

### Step 1: Update the error mapping in ValidationErrors.vue
```javascript
const errorMap = {
    'email': 'pages.auth.login.validation.email_required',
    'password': 'pages.auth.login.validation.password_required',
    'auth.failed': 'pages.auth.login.validation.credentials_invalid',
    'throttled': 'pages.auth.login.validation.throttled',  // NEW
};
```

### Step 2: Add translations to all three language files

**en.json:**
```json
"pages": {
    "auth": {
        "login": {
            "validation": {
                "throttled": "Too many login attempts. Please try again later."
            }
        }
    }
}
```

**de.json:**
```json
"pages": {
    "auth": {
        "login": {
            "validation": {
                "throttled": "Zu viele Anmeldeversuche. Bitte versuchen Sie es später erneut."
            }
        }
    }
}
```

**np.json:**
```json
"pages": {
    "auth": {
        "login": {
            "validation": {
                "throttled": "धेरै लगइन प्रयासहरू। कृपया पछि प्रयास गर्नुहोस्।"
            }
        }
    }
}
```

---

## Internationalization (i18n) Best Practices

### Supported Languages
- **en** - English
- **de** - German (Deutsch)
- **np** - Nepali

### Language Detection Priority
1. Window variable (`window.__initialLocale`)
2. LocalStorage preference (`preferred_locale`)
3. Environment variable (`MIX_DEFAULT_LOCALE`)
4. Default fallback (German - de)

### Translation File organisation
```
resources/js/locales/
├── en.json                    # Global English translations
├── de.json                    # Global German translations
├── np.json                    # Global Nepali translations
└── pages/
    ├── Auth/
    │   ├── en.json            # Login/Register page translations
    │   ├── de.json
    │   └── np.json
    └── ... (other page translations)
```

---

## Testing Checklist

- [ ] Navigate to `/login`
- [ ] Try logging in with invalid credentials
- [ ] Verify error message is properly translated in current language
- [ ] Change language to English and retry - error should be in English
- [ ] Change language to German and retry - error should be in German
- [ ] Change language to Nepali and retry - error should be in Nepali
- [ ] Verify error header ("Oops! Something went wrong.") is also translated

---

## Files Modified

1. **`resources/js/Jetstream/ValidationErrors.vue`**
   - Added `translatedErrors` computed property
   - Updated template to use translated header

2. **`resources/js/locales/en.json`**
   - Added `validation.errors_header` key

3. **`resources/js/locales/de.json`**
   - Added `validation.errors_header` key

4. **`resources/js/locales/np.json`**
   - Added `validation.errors_header` key

---

## Security Considerations

✓ **Backend Error Details Not Exposed**: The translated error message is user-friendly and doesn't expose system details

✓ **Multi-Tenant Safe**: Translations work within tenant context (not cross-tenant)

✓ **CSRF Protected**: Login form includes CSRF protection (handled by Inertia.js)

✓ **Rate Limited**: Laravel Fortify provides built-in login throttling

---

## Future Enhancements

1. **Email Already Registered Error**
   - When user tries to register with existing email
   - Add to error mapping and translations

2. **Two-Factor Authentication Errors**
   - When 2FA verification fails
   - Add specific error messages

3. **Password Reset Errors**
   - When password reset link is invalid/expired
   - Add specific error messages

4. **Account Status Errors**
   - When account is suspended/inactive
   - Add specific error messages

---

## References

- Vue i18n Documentation: https://vue-i18n.intlify.dev/
- Laravel Fortify: https://laravel.com/docs/fortify
- Inertia.js Error Handling: https://inertiajs.com/validation

---

**Last Updated:** 2026-02-23
**Language Support:** English, German, Nepali
**Component:** LoginPages (Inertia.js + Vue 3)
