# ✅ Verification Plan: Ensure Nepal Users Get नेपाली (np) Automatically

## 1. Code Path Verification

### Step 1: Timezone Mapping in LocationController
**File:** `app/Http/Controllers/LocationController.php` (line 84)

```php
'Asia/Kathmandu' => 'np',
```

✅ **Status:** Mapping exists

### Step 2: Supported Locales in Domain
**File:** `app/Domain/Locale/ValueObjects/Locale.php` (line 9)

```php
private const SUPPORTED = ['de', 'en', 'np'];
```

✅ **Status:** np is supported

### Step 3: Country Code to Locale Mapping
**File:** `app/Domain/Locale/Policies/LocalePolicy.php` (line 11)

```php
'NP' => 'np',
```

✅ **Status:** Nepal country code maps to np

### Step 4: API Endpoint Exists
**File:** `routes/api.php` (line 43)

```php
Route::match(['GET', 'POST'], '/detect-location', [LocationController::class, 'detect'])
```

✅ **Status:** Endpoint registered

### Step 5: Auto-detection in app.js
**File:** `resources/js/app.js` (lines 47-93)

```javascript
fetch('/api/detect-location', {
    method: 'POST',
    body: JSON.stringify({ timezone: Intl.DateTimeFormat().resolvedOptions().timeZone })
})
```

✅ **Status:** Sends browser timezone to API

---

## 2. Testing: Simulate Nepal User

### Test 1: API Endpoint (Curl)

**Command:**
```bash
curl -X POST http://localhost:8000/api/detect-location \
  -H "Content-Type: application/json" \
  -d '{"timezone":"Asia/Kathmandu"}'
```

**Expected Response:**
```json
{
    "locale": "np",
    "timezone": "Asia/Kathmandu",
    "location": null,
    "ip": "127.0.0.1",
    "decision": {
        "source": "timezone",
        "browser_timezone": "Asia/Kathmandu",
        "org_language": null,
        "detected_country": null
    }
}
```

**Verification:** ✅ If `"locale": "np"` and `"source": "timezone"` → **WORKING**

---

### Test 2: Browser Simulation (Manual)

**Steps:**

1. **Clear all stored preferences:**
   ```javascript
   localStorage.removeItem('preferred_locale');
   document.cookie = 'locale=; max-age=0; path=/';
   ```

2. **Change system timezone to Asia/Kathmandu:**
   - **Windows:** Settings → Time & Language → Date & time → Change timezone → UTC+5:45
   - **Mac:** System Preferences → Date & Time → Time zone → Kathmandu
   - **Linux:** `timedatectl set-timezone Asia/Kathmandu`

3. **Reload page:**
   ```javascript
   location.reload();
   ```

4. **Check that page is in Nepali:**
   - Menu items should be in नेपाली
   - All UI text should be in Nepali
   - Page title should reflect Nepali language

5. **Verify in console:**
   ```javascript
   window.__localeDebug.info()
   ```

   **Expected output:**
   ```
   Browser Timezone: Asia/Kathmandu
   localStorage preferred_locale: (empty or np)
   Cookie locale: np
   API Response:
   - Detected Locale: np
   - Decision Source: timezone
   ```

**Verification:** ✅ If page is in Nepali → **WORKING**

---

### Test 3: Programmatic Unit Test

**File:** `tests/Unit/Application/Locale/DetectLocaleUseCaseTest.php` (line 195)

**Test Case:** `nepali_country_maps_to_np()`

```php
public function nepali_country_maps_to_np()
{
    $request = $this->createMockRequest('103.20.30.40', 'Asia/Kathmandu', null);
    $this->mockGeoProvider->expects($this->any())
        ->method('getCountryCode')
        ->with('103.20.30.40')
        ->willReturn('NP');

    $locale = $this->useCase->execute($request, null);

    $this->assertEquals('np', $locale->value());
}
```

**Run Test:**
```bash
php artisan test tests/Unit/Application/Locale/DetectLocaleUseCaseTest.php::nepali_country_maps_to_np
```

**Expected:** ✅ Test passes

---

## 3. Real-World Testing: Nepal User Journey

### Scenario 1: Fresh User from Nepal (No Cached Preferences)

**What happens:**
1. User in Nepal opens website
2. Browser reports timezone: `Asia/Kathmandu`
3. `app.js` sends POST to `/api/detect-location` with timezone
4. `LocationController` maps `Asia/Kathmandu` → `np`
5. `app.js` checks for existing cookie
6. No cookie exists (fresh user) → sets locale to `np`
7. Page renders in Nepali

**Expected:** ✅ Page in Nepali

---

### Scenario 2: Nepal User Who Previously Switched to German

**What happens:**
1. User in Nepal opens website
2. `localStorage` contains `preferred_locale: 'de'`
3. `app.js` auto-detection runs but finds cookie/localStorage
4. Page renders in German

**Solution:** User clicks 🔄 (reset button)
1. Preferences cleared
2. Page reloads
3. Auto-detection runs fresh
4. Page renders in Nepali

**Expected:** ✅ After reset button → Page in Nepali

---

### Scenario 3: Nepal User with Organization Setting

**What happens:**
1. Organization admin set `default_language: 'np'`
2. User in Nepal opens website
3. `SetLocale` middleware (line 25-34):
   ```php
   if (auth()->check()) {
       $orgLocale = auth()->user()->currentOrganisation?->default_language;
       if ($orgLocale && $this->isValidLocale($orgLocale)) {
           app()->setLocale($orgLocale);  // Sets to 'np'
       }
   }
   ```
4. Page renders in Nepali (from org setting, not timezone detection)

**Expected:** ✅ Page in Nepali (enforced by org)

---

## 4. Monitoring: Verify Production Behavior

### Check 1: Server Logs

**Command:**
```bash
tail -f storage/logs/laravel.log | grep "Locale detection"
```

**Expected log entry:**
```
[2026-05-01 10:15:30] local.INFO: 🌍 Locale detection result {
    "final_locale":"np",
    "decision_chain":"timezone"
}
```

### Check 2: Database Activity

Track which locales are being set:

```bash
# If you're logging locale preferences somewhere
grep -i "locale.*np" storage/logs/laravel.log | wc -l
```

### Check 3: User Reports

Monitor for:
- ✅ Nepal users reporting correct Nepali language
- ⚠️ Nepal users reporting German instead of Nepali
- 📊 Track how many users from Nepal are setting `locale=np` cookie

---

## 5. Troubleshooting If It's Not Working

### Issue: API Returns 'en' Instead of 'np'

**Possible causes:**

1. **Timezone not being sent:**
   ```javascript
   // Check network tab (F12 → Network → detect-location)
   // POST body should include: {"timezone":"Asia/Kathmandu"}
   ```

2. **Browser timezone not set to Asia/Kathmandu:**
   ```javascript
   console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);
   // Should output: Asia/Kathmandu
   ```

3. **LocationController not handling timezone:**
   - Check `app/Http/Controllers/LocationController.php` line 32-33
   - Must call `$this->timezoneToLocale($browserTimezone)` BEFORE geo-detection

4. **Locale value object not supporting 'np':**
   - Check `app/Domain/Locale/ValueObjects/Locale.php` line 9
   - 'np' must be in `SUPPORTED` array

### Issue: API Returns 'np' But Page Shows German

**Possible causes:**

1. **Cached preference overriding:**
   - Check: `localStorage.getItem('preferred_locale')`
   - Check: `document.cookie`
   - Solution: Click reset button 🔄

2. **Organization forcing different language:**
   - Check: `window.__localeDebug.print()` → looks for org setting
   - Solution: Admin needs to change org `default_language`

3. **Middleware not applying locale:**
   - Check logs: `grep SetLocale storage/logs/laravel.log`
   - Verify middleware is in HTTP kernel

---

## 6. Summary Checklist

- [ ] Code path verified (all 5 components exist)
- [ ] API test with curl returns `"locale": "np"`
- [ ] Browser test with Asia/Kathmandu timezone shows Nepali
- [ ] Unit tests pass (`DetectLocaleUseCaseTest`)
- [ ] Middleware test pass (`SetLocaleMiddlewareTest`)
- [ ] Reset button works (clears and re-detects)
- [ ] Debug console accessible (`window.__localeDebug.info()`)
- [ ] Real Nepal user reports correct language OR
- [ ] Server logs show Nepal users getting `np` locale

---

## 7. Quick Verification Command

**One-liner to verify everything is in place:**

```bash
echo "=== Checking timezone mapping ===" && \
grep -n "Asia/Kathmandu.*np" app/Http/Controllers/LocationController.php && \
echo "✅ Timezone mapping exists" && \
echo "" && \
echo "=== Checking supported locales ===" && \
grep -n "SUPPORTED.*np" app/Domain/Locale/ValueObjects/Locale.php && \
echo "✅ np is supported" && \
echo "" && \
echo "=== Checking country mapping ===" && \
grep -n "'NP'.*np" app/Domain/Locale/Policies/LocalePolicy.php && \
echo "✅ Nepal country maps to np" && \
echo "" && \
echo "=== Running locale tests ===" && \
php artisan test tests/Unit/Application/Locale/DetectLocaleUseCaseTest.php --quiet && \
echo "✅ All locale tests pass"
```

**Expected output:**
```
=== Checking timezone mapping ===
84:            'Asia/Kathmandu' => 'np',
✅ Timezone mapping exists

=== Checking supported locales ===
9:    private const SUPPORTED = ['de', 'en', 'np'];
✅ np is supported

=== Checking country mapping ===
12:            'NP' => 'np',
✅ Nepal country maps to np

=== Running locale tests ===
✅ All locale tests pass
```

---

## Final Verification

To ensure Nepal users get नेपाली:

1. **For local testing:** Set timezone to Asia/Kathmandu and reload
2. **For API verification:** Use curl command in section 2
3. **For production:** Monitor logs and user reports
4. **For debugging:** Use `window.__localeDebug.info()`

All components are in place. The system WILL work for Nepal users IF:
- ✅ Browser timezone is set to Asia/Kathmandu
- ✅ No cached preferences override it
- ✅ No organization admin forced a different language

---

**Status:** Ready for Nepal user testing 🚀
