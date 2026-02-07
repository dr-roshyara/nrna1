# 🔧 Translation Troubleshooting Decision Tree

**Follow the flowchart to diagnose and fix localization issues**

---

## START: What's Your Problem?

```
Is your issue related to:
├─ Language selection / persistence? → See: LANGUAGE PERSISTENCE
├─ Translations showing as keys? → See: MISSING TRANSLATIONS
├─ Performance / slowness? → See: PERFORMANCE
├─ Adding new language? → See: ADDING LANGUAGES
├─ Middleware / backend? → See: BACKEND ISSUES
└─ Something else? → See: GENERAL CHECKLIST
```

---

## 🔴 LANGUAGE PERSISTENCE

**"My language selection doesn't survive navigation or page reload"**

```
START: Language resets after navigation or refresh
│
├─→ Does language reset on SAME PAGE after reload (F5)?
│   │
│   ├─ YES: Problem is PERSISTENCE (not component)
│   │   │
│   │   ├─→ Check 1: Is cookie being SET?
│   │   │   Open DevTools (F12) → Application → Cookies
│   │   │   Look for: 'locale' cookie with value like 'en'
│   │   │   │
│   │   │   ├─ NOT FOUND: Cookie not being set
│   │   │   │   │
│   │   │   │   └─→ FIX:
│   │   │   │       1. Open browser console (F12 → Console)
│   │   │   │       2. Run: document.cookie
│   │   │   │       3. Change language in selector
│   │   │   │       4. Run: document.cookie again
│   │   │   │       5. If 'locale' not added → ElectionHeader bug
│   │   │   │          Check handleLanguageChange() method
│   │   │   │
│   │   │   ├─ FOUND (e.g., locale=en):
│   │   │   │   │
│   │   │   │   └─→ FIX: Go to Check 2
│   │   │   │
│   │   │   └─ FOUND BUT MISSING EXPIRY:
│   │   │       │
│   │   │       └─→ FIX:
│   │   │           In ElectionHeader.switchLanguage():
│   │   │           const date = new Date();
│   │   │           date.setFullYear(date.getFullYear() + 1);
│   │   │           document.cookie = `locale=${locale}; expires=${date.toUTCString()}; path=/`;
│   │   │
│   │   │
│   │   └─→ Check 2: Is Laravel SetLocale middleware reading cookie?
│   │       │
│   │       ├─→ Run diagnostic:
│   │       │   1. Kill dev server: Ctrl+C
│   │       │   2. Run: php artisan cache:clear
│   │       │   3. Run: php artisan config:clear
│   │       │   4. Start server: npm run dev
│   │       │   5. Try changing language again
│   │       │   │
│   │       │   ├─ WORKS NOW: Cache was the culprit
│   │       │   │   SUCCESS ✅
│   │       │   │
│   │       │   ├─ STILL BROKEN: Continue to Check 3
│   │       │   │
│   │       │   └─ ERROR ON CACHE CLEAR: See BACKEND ISSUES section
│   │       │
│   │       └─→ Check 3: Is SetLocale middleware in correct position?
│   │           │
│   │           └─→ Run:
│   │               grep -n "SetLocale\|EncryptCookies\|StartSession\|HandleInertia" app/Http/Kernel.php
│   │               │
│   │               Expected order:
│   │               33: EncryptCookies::class,        ← Cookies readable
│   │               35: StartSession::class,          ← Session available
│   │               40: SetLocale::class,             ← ⭐ MUST be here (after 33 & 35)
│   │               41: HandleInertiaRequests::class, ← Share with Vue
│   │               │
│   │               ├─ CORRECT ORDER: Go to Check 4
│   │               │
│   │               └─ WRONG ORDER (SetLocale too early):
│   │                   │
│   │                   └─→ FIX:
│   │                       1. Open: app/Http/Kernel.php
│   │                       2. Remove SetLocale from global $middleware array
│   │                       3. Add to 'web' => [ ... ] group AFTER StartSession
│   │                       4. Save
│   │                       5. Run: php artisan cache:clear
│   │                       6. Test
│   │
│   │
│   ├─ NO (resets on NAVIGATION to different page):
│   │   Problem is NOT persistence, but PROP PASSING
│   │   │
│   │   └─→ Go to: PROP NOT PASSED section (below)
│   │
│   │
│   └─→ Check 4: Is locale shared by Inertia?
│       │
│       ├─→ Verify in browser:
│       │   1. Open page
│       │   2. F12 → Console
│       │   3. Run: this.$page.props.locale
│       │   │
│       │   ├─ Shows 'de' / 'en' / 'np': Go to Check 5
│       │   │
│       │   ├─ Shows 'undefined': HandleInertiaRequests not sharing
│       │   │   │
│       │   │   └─→ FIX:
│       │   │       1. Open: app/Http/Middleware/HandleInertiaRequests.php
│       │   │       2. Find: public function share(Request $request): array
│       │   │       3. Add in the return array:
│       │   │          'locale' => app()->getLocale(),
│       │   │       4. Save
│       │   │       5. Clear cache: php artisan cache:clear
│       │   │       6. Test
│       │   │
│       │   └─ ERROR in console: Check GENERAL CHECKLIST
│       │
│       │
│       └─→ Check 5: Does page have ElectionHeader with locale prop?
│           │
│           ├─→ Run in console:
│           │   document.body.innerHTML.includes('ElectionHeader')
│           │   │
│           │   ├─ false (component not rendered):
│           │   │   │
│           │   │   └─→ FIX:
│           │   │       1. Open your Vue page component
│           │   │       2. Add import:
│           │   │          import ElectionHeader from '@/Components/Header/ElectionHeader.vue'
│           │   │       3. Add in template:
│           │   │          <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />
│           │   │       4. Save and reload
│           │   │
│           │   └─ true (component exists):
│           │       │
│           │       ├─→ Check: Does component have :locale prop?
│           │       │   View page source (Ctrl+U) and search for <ElectionHeader
│           │       │   │
│           │       │   ├─ Shows: <ElectionHeader ... :locale="en">
│           │       │   │   │
│           │       │   │   └─ SUCCESS ✅ (prop is being passed)
│           │       │   │       Language should persist now
│           │       │   │
│           │       │   ├─ Shows: <ElectionHeader ...> (NO :locale)
│           │       │   │   │
│           │       │   │   └─→ FIX:
│           │       │   │       1. Open your page component
│           │       │   │       2. Add :locale to ElectionHeader:
│           │       │   │          <ElectionHeader
│           │       │   │            :isLoggedIn="true"
│           │       │   │            :locale="$page.props.locale"
│           │       │   │          />
│           │       │   │       3. Save
│           │       │   │       4. Refresh page
│           │       │   │
│           │       │   └─ Shows nothing / error: See GENERAL CHECKLIST
│           │       │
│           │       └─→ STILL NOT WORKING:
│           │           Check: Has ElectionHeader received props update?
│           │           Add debug in ElectionHeader.vue mounted():
│           │           console.log('ElectionHeader locale prop:', this.locale)
│           │           console.log('$page.props.locale:', this.$page.props.locale)
│           │
│           └─ Still not working: See GENERAL CHECKLIST
```

---

## 🔴 MISSING TRANSLATIONS

**"Page shows translation keys instead of text (e.g., 'pages.welcome.title' instead of 'Welcome to Public Digit')"**

```
START: Translation key showing as raw text
│
├─→ Is this happening on EVERY page or just SOME pages?
│   │
│   ├─ EVERY PAGE:
│   │   Vue I18n is not configured properly
│   │   │
│   │   └─→ FIX:
│   │       1. Check: resources/js/i18n.js exists
│   │       2. Check: resources/js/app.js imports i18n
│   │          import i18n from './i18n'
│   │       3. Check: app.use(i18n)
│   │       4. Run: npm run dev
│   │       5. Refresh browser
│   │
│   ├─ SOME PAGES:
│   │   │
│   │   └─→ Go to: Check 1 (below)
│   │
│   │
│   └─→ Check 1: Does the translation key exist in ALL locale files?
│       │
│       ├─→ Run:
│       │   grep "welcome.title" resources/js/locales/*.json
│       │   │
│       │   ├─ Found in: en.json, de.json, np.json
│       │   │   │
│       │   │   ├─→ Check 2: Is the key spelled correctly in template?
│       │   │   │   │
│       │   │   │   └─ If using: $t('pages.welcome.title')
│       │   │   │      But file has: "title": "value"
│       │   │   │      → Missing "pages" and "welcome" levels!
│       │   │   │      → Add them to locale files
│       │   │   │
│       │   │   └─ If spelled correctly: Go to Check 3
│       │   │
│       │   ├─ Found in: en.json, de.json (BUT NOT np.json)
│       │   │   │
│       │   │   └─→ FIX:
│       │   │       1. Open: resources/js/locales/np.json
│       │   │       2. Add the missing key with Nepali translation
│       │   │       3. Maintain same structure as en.json
│       │   │       4. Save
│       │   │       5. Run: npm run dev
│       │   │       6. Select Nepali language
│       │   │       7. Refresh
│       │   │
│       │   └─ NOT found in any file:
│       │       │
│       │       └─→ FIX:
│       │           1. Open: resources/js/locales/de.json (start here)
│       │           2. Add the key and translation
│       │           3. Copy structure to: en.json
│       │           4. Copy structure to: np.json
│       │           5. Run: npm run dev
│       │           6. Test all 3 languages
│       │
│       │
│       └─→ Check 3: Is locale file valid JSON?
│           │
│           ├─→ Run:
│           │   cat resources/js/locales/en.json | jq empty
│           │   │
│           │   ├─ No error: JSON is valid, go to Check 4
│           │   │
│           │   └─ Error (JSON invalid):
│           │       │
│           │       └─→ FIX:
│           │           1. Look at error message carefully
│           │           2. Common issues:
│           │              - Missing comma: { "a": 1 "b": 2 }
│           │              - Unmatched quotes: "value'
│           │              - Trailing comma: { "a": 1, }
│           │           3. Use: https://jsonlint.com/ to validate
│           │           4. Fix syntax errors
│           │           5. Run: npm run dev
│           │           6. Refresh
│           │
│           │
│           └─→ Check 4: Is Vue I18n finding the translations?
│               │
│               ├─→ In browser console, run:
│               │   this.$i18n.t('pages.welcome.title')
│               │   │
│               │   ├─ Shows translated text: Vue I18n works!
│               │   │   Problem is in component template
│               │   │   │
│               │   │   └─→ Check template has:
│               │   │       {{ $t('pages.welcome.title') }}
│               │   │       (not {{ $t("pages.welcome.title") }} with double quotes)
│               │   │
│               │   ├─ Shows key back: Vue I18n doesn't have key
│               │   │   │
│               │   │   └─→ FIX:
│               │   │       1. Check key is added to locale file
│
                        │   2. Run: npm run dev (rebuild)
│               │       3. Clear browser cache (Ctrl+Shift+Delete)
│               │       4. Refresh page
│               │
│               └─ Shows undefined or error:
│                   │
│                   └─→ See: GENERAL CHECKLIST section
```

---

## 🔴 PROP NOT PASSED

**"Language changes on home page, but not on login/register (page-specific issue)"**

```
START: ElectionHeader works on some pages, not others
│
├─→ Step 1: List all Vue pages that should have language selector
│   Typical pages:
│   - Welcome.vue ✓
│   - Login.vue ✗
│   - Register.vue ✗
│   - Dashboard.vue ✗
│   - ElectionPage.vue ✗
│   - SelectElection.vue ✗
│
├─→ Step 2: For each page WITHOUT language switching:
│   │
│   ├─→ Check: Does page component import ElectionHeader?
│   │   │
│   │   ├─ NO:
│   │   │   │
│   │   │   └─→ FIX:
│   │   │       1. Open: resources/js/Pages/Auth/Login.vue (example)
│   │   │       2. Add to <script setup>:
│   │   │          import ElectionHeader from '@/Components/Header/ElectionHeader.vue'
│   │   │       3. Save
│   │   │       4. Refresh
│   │   │
│   │   ├─ YES:
│   │   │   │
│   │   │   └─→ Check: Is ElectionHeader in template?
│   │   │       │
│   │   │       ├─ NO:
│   │   │       │   │
│   │   │       │   └─→ FIX:
│   │   │       │       1. Open: resources/js/Pages/Auth/Login.vue
│   │   │       │       2. Add to <template>:
│   │   │       │          <ElectionHeader
│   │   │       │            :isLoggedIn="false"
│   │   │       │            :locale="$page.props.locale"
│   │   │       │          />
│   │   │       │       3. Usually goes FIRST in template
│   │   │       │       4. Save and refresh
│   │   │       │
│   │   │       ├─ YES, but NO :locale prop:
│   │   │       │   │
│   │   │       │   └─→ FIX:
│   │   │       │       BEFORE:
│   │   │       │       <ElectionHeader :isLoggedIn="false" />
│   │   │       │
│   │   │       │       AFTER:
│   │   │       │       <ElectionHeader
│   │   │       │         :isLoggedIn="false"
│   │   │       │         :locale="$page.props.locale"
│   │   │       │       />
│   │   │       │
│   │   │       │       Save and refresh
│   │   │       │
│   │   │       └─ YES, with :locale prop:
│   │   │           │
│   │   │           ├─→ Clear cache:
│   │   │           │   php artisan cache:clear
│   │   │           │   npm run dev
│   │   │           │   Refresh
│   │   │           │
│   │   │           └─ STILL NO LUCK:
│   │   │               Run diagnostic in console:
│   │   │               console.log('Props:', this.$page.props)
│   │   │               console.log('Locale:', this.$page.props.locale)
│   │   │
│   │   └─ Could not determine: Go to GENERAL CHECKLIST
│   │
│   └─→ Step 3: If ALL pages are fixed, test:
│       1. Navigate to home page
│       2. Change language to English
│       3. Navigate to login page
│       4. Should stay in English
│       5. Go to register page
│       6. Should still be English
```

---

## 🔴 BACKEND ISSUES

**"Laravel errors / SetLocale middleware problems"**

```
START: Backend / server-side localization problems
│
├─→ Error Type: "Session store not set on request"
│   │
│   └─→ FIX:
│       PROBLEM: SetLocale trying to access session before it's available
│       │
│       SOLUTION:
│       1. Open: app/Http/Middleware/SetLocale.php
│       2. Ensure ALL session accesses have guard:
│          if ($request->hasSession()) {
│              $request->session()->put('locale', $locale);
│          }
│       3. NOT like this:
│          $request->session()->put('locale', $locale);  // ❌
│       4. Save
│       5. Run: php artisan cache:clear
│       6. Test
│
├─→ Error Type: "Class SetLocale not found"
│   │
│   └─→ FIX:
│       1. Verify file exists:
│          ls app/Http/Middleware/SetLocale.php
│       2. If NOT found:
│          Create it with proper content
│       3. Verify namespace:
│          grep "namespace" app/Http/Middleware/SetLocale.php
│          Should show: namespace App\Http\Middleware;
│       4. Run: composer dump-autoload
│       5. Run: php artisan cache:clear
│
├─→ Error Type: Locale not changing despite cookie being set
│   │
│   └─→ FIX:
│       DIAGNOSIS:
│       1. Add temporary logging to SetLocale.php:
│          \Log::debug('SetLocale check', [
│              'cookie' => $request->cookie('locale'),
│              'hasCookie' => $request->hasCookie('locale'),
│          ]);
│       2. Change language in browser
│       3. Check logs:
│          tail -f storage/logs/laravel.log
│       4. Look for debug output
│       5. If cookie not shown, then EncryptCookies hasn't decrypted it
│          → Check middleware order in Kernel.php
│
├─→ Error Type: .env not being read
│   │
│   └─→ FIX:
│       1. Run: php artisan config:clear
│       2. Run: php artisan cache:clear
│       3. Verify .env has:
│          APP_LOCALE=de
│       4. Run: php artisan config:show | grep locale
│       5. Should display your locale setting
│
├─→ Still not working:
│   │
│   └─→ RUN FULL RESET:
│       1. php artisan cache:clear
│       2. php artisan config:clear
│       3. php artisan route:clear
│       4. composer dump-autoload
│       5. npm run dev
│       6. Refresh browser (Ctrl+Shift+Delete to clear browser cache)
│       7. Test again
```

---

## 🟡 GENERAL CHECKLIST

**When you can't figure out which section applies to you:**

```
Run through each item:

[ ] STEP 1: Browser setup
    [ ] Open DevTools (F12)
    [ ] Go to: Application → Cookies
    [ ] Check if 'locale' cookie exists
    [ ] Check if it has your language (de/en/np)

    If missing:
    - Change language in selector
    - Cookie should appear
    - If not, problem is in ElectionHeader.switchLanguage()

[ ] STEP 2: Console check
    [ ] Open: F12 → Console
    [ ] Run: this.$page.props.locale
    [ ] Should show: de, en, or np
    [ ] If undefined, see BACKEND ISSUES
    [ ] If different from selected language, refresh cache:
        - php artisan cache:clear
        - npm run dev

[ ] STEP 3: Network check
    [ ] Open: F12 → Network
    [ ] Reload page (F5)
    [ ] Click on first HTML request
    [ ] Go to: Request Headers
    [ ] Look for: Cookie: locale=en
    [ ] If missing, cookie isn't being sent
        - Check cookie has correct path (/)
        - Check cookie isn't expired

[ ] STEP 4: Component check
    [ ] Open page source: Ctrl+U
    [ ] Search for: <ElectionHeader
    [ ] Should show: <ElectionHeader ... :locale="en">
    [ ] If :locale not there, add to component
    [ ] If component not there, add import and template

[ ] STEP 5: Locale file check
    [ ] Open: resources/js/locales/en.json
    [ ] Search for: pages.welcome
    [ ] Should have keys: title, description, etc.
    [ ] Check all three locale files have SAME keys:
        grep "title" resources/js/locales/*.json
    [ ] If missing in one file, add it

[ ] STEP 6: Middleware check
    [ ] Run:
        grep -n "SetLocale\|EncryptCookies\|StartSession\|HandleInertia" \
        app/Http/Kernel.php
    [ ] Should show:
        EncryptCookies (low line number)
        StartSession (higher)
        SetLocale (even higher)
        HandleInertiaRequests (highest)
    [ ] If order wrong, fix it

[ ] STEP 7: Vue I18n check
    [ ] Check: resources/js/i18n.js exists
    [ ] Check: app.js imports it:
        import i18n from './i18n'
    [ ] Check: app.js uses it:
        .use(i18n)
    [ ] If any missing, add them
    [ ] Run: npm run dev

[ ] STEP 8: Cache clear
    [ ] php artisan cache:clear
    [ ] php artisan config:clear
    [ ] php artisan route:clear
    [ ] composer dump-autoload
    [ ] npm run dev
    [ ] Ctrl+Shift+Delete in browser (clear all cache)

[ ] STEP 9: Final test
    [ ] Reload page
    [ ] Change language
    [ ] Language should change immediately
    [ ] Press F5 (refresh)
    [ ] Language should persist
    [ ] Click different page link
    [ ] Language should still be selected

    If all working: SUCCESS ✅
    If any step fails: Go back to that step and diagnose deeper
```

---

## 🟢 SUCCESS INDICATORS

You've fixed the issue when:

```
✅ Language changes immediately when selecting from dropdown
✅ Page reloads (F5) and language stays the same
✅ Navigate to different page, language is remembered
✅ Browser console shows: this.$page.props.locale = 'en'
✅ DevTools Cookies shows: locale=en with future expiry date
✅ Page source (Ctrl+U) shows: <ElectionHeader ... :locale="en">
✅ No console errors (F12 → Console should be clean)
✅ $t('key.name') shows translated text, not the key itself
✅ All 3 languages work: de, en, np
✅ Mobile and desktop both respect language choice
```

---

## 📞 When All Else Fails

If you've gone through the entire decision tree and still stuck:

**Provide this information:**

```
1. Current problem description:
   (Exact steps to reproduce)

2. What you've tried:
   (List fixes you've already attempted)

3. Current state:
   [ ] Language changes in dropdown? Y/N
   [ ] Changes show on page? Y/N
   [ ] Persist after refresh? Y/N
   [ ] Persist across pages? Y/N
   [ ] Cookie visible in DevTools? Y/N
   [ ] Console errors? Y/N (copy if yes)

4. Configuration:
   [ ] APP_LOCALE in .env: ___________
   [ ] Laravel version: ___________
   [ ] Node version: npm -v = ___________

5. Browser console output:
   this.$page.props.locale = ___________
   document.cookie = ___________
   this.$i18n.locale = ___________

6. Middleware order:
   (output of: grep -n SetLocale app/Http/Kernel.php)
   Line ___ : SetLocale::class

7. Error message (if any):
   (copy full error from F12 console or Laravel logs)
```

---

## 🔗 Related Documentation

- See: [TRANSLATION_ARCHITECTURE.md](./TRANSLATION_ARCHITECTURE.md) — Full technical overview
- See: [TRANSLATION_QUICK_REFERENCE.md](./TRANSLATION_QUICK_REFERENCE.md) — Code snippets and patterns
- Laravel docs: https://laravel.com/docs/localization
- Vue I18n docs: https://vue-i18n.intlify.dev/

---

**Version:** 1.0
**Last Updated:** February 2026

