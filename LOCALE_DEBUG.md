# 🌍 Locale Detection Debugging Guide

If your language isn't being detected correctly, use these browser console commands to diagnose the issue.

## Quick Start

Open your browser's developer console (Press `F12` or `Ctrl+Shift+I`) and run:

```javascript
window.__localeDebug.info()
```

This shows your browser timezone, stored preferences, and tests the API endpoint.

---

## Detailed Debug Commands

### 1. Show Debug Info

```javascript
window.__localeDebug.print()
```

**Output shows:**
- Your browser's timezone (e.g., `Asia/Kathmandu`)
- Stored language preference in localStorage
- Stored language preference in cookie
- Browser's Accept-Language header

### 2. Test the API Directly

```javascript
window.__localeDebug.testApi()
```

**Expected response for Nepal (if everything works):**
```json
{
    "locale": "np",
    "timezone": "Asia/Kathmandu",
    "decision": {
        "source": "timezone"
    }
}
```

### 3. Clear All Stored Preferences and Re-detect

```javascript
window.__localeDebug.clear()
```

Then reload the page. The system will auto-detect your location based on browser timezone.

---

## Troubleshooting

### Problem: Language stays German (de) instead of Nepali (np)

**Step 1: Check your browser timezone**
```javascript
window.__localeDebug.print()
```

Look for: `Browser Timezone: Asia/Kathmandu`

If it shows something else, your device settings may be wrong:
- **Windows:** Settings → Time & Language → Date & time → Change timezone
- **Mac:** System Preferences → Date & Time → Time zone
- **Linux:** Use `timedatectl set-timezone Asia/Kathmandu`

**Step 2: Check if preferences are cached**
```javascript
window.__localeDebug.print()
```

Look for:
- `localStorage preferred_locale: de` ← If this shows, preferences are cached
- `Cookie locale: de` ← Same issue

**Solution:** Clear preferences and reload
```javascript
window.__localeDebug.clear()
// Reload page automatically
```

**Step 3: Test the API**
```javascript
window.__localeDebug.testApi()
```

If `source` is `timezone` and `locale` is `np`, the detection works but preferences override it. Do Step 2.

---

## Common Scenarios

### "I'm in Nepal but get German"

| Likely Cause | Check | Fix |
|---|---|---|
| Cached preference | `window.__localeDebug.print()` | `window.__localeDebug.clear()` |
| Browser timezone wrong | `Intl.DateTimeFormat().resolvedOptions().timeZone` | Update device settings |
| localStorage corrupted | localStorage in DevTools (F12) | Clear site data |
| Organization admin set language | Check page footer or settings | Ask admin or use Reset button 🔄 |

### "API test shows correct locale but page stays German"

1. The API is working correctly
2. A cached preference or organization setting is overriding it
3. **Solution:** Click the 🔄 button in the language selector or run `window.__localeDebug.clear()`

### "Browser timezone shows Asia/Kathmandu but API returns 'en'"

This shouldn't happen, but if it does:
1. The geo-detection API failed or returned null
2. Check server logs: `tail -f storage/logs/laravel.log | grep detect-location`
3. The fallback chain is: timezone → org language → IP geolocation → Accept-Language header → English

---

## Reset Button

Language selector has a 🔄 button that:
1. Clears all stored preferences
2. Clears localStorage
3. Clears cookies
4. Reloads page to trigger auto-detection

Use this when:
- You changed device timezone and page didn't update
- You want to re-detect location
- You suspect cached preferences are wrong

---

## For Developers

### Manual API Test with curl

```bash
curl -X POST http://localhost:8000/api/detect-location \
  -H "Content-Type: application/json" \
  -d '{"timezone":"Asia/Kathmandu"}'
```

**Expected response:**
```json
{
    "locale": "np",
    "timezone": "Asia/Kathmandu",
    "decision": {
        "source": "timezone",
        "browser_timezone": "Asia/Kathmandu",
        "org_language": null,
        "detected_country": null
    }
}
```

### Priority Chain (Server-side)

```
Priority 1: Organization default_language (if user authenticated)
Priority 2: Browser timezone (IANA timezone → locale mapping)
Priority 3: IP geolocation (via GeoLocationService)
Priority 4: Accept-Language header
Priority 5: English fallback
```

### Supported Locales

- `de` (Deutsch) - Germany, Austria, Switzerland, Liechtenstein, Luxembourg, Belgium
- `en` (English) - United Kingdom, Ireland, UTC, GMT
- `np` (नेपाली) - Nepal

New locales can be added in:
- `app/Http/Controllers/LocationController.php` - timezone mapping
- `app/Domain/Locale/Policies/LocalePolicy.php` - country code mapping
- `app/Domain/Locale/ValueObjects/Locale.php` - supported locales list

---

## Still Not Working?

1. Run `window.__localeDebug.info()` and share the output
2. Share server logs: `grep detect-location storage/logs/laravel.log`
3. Check that Laravel is running and `/api/detect-location` endpoint exists
4. Verify browser allows cookies: Settings → Privacy

---

**Last Updated:** 2026-05-01  
**Version:** 1.0
