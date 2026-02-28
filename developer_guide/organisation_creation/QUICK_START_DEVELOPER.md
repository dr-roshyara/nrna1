# organisation Creation - Quick Start for Developers

**Start here** if you're new to the organisation creation system.

---

## 🚀 Quick Navigation

| Need | File | Location |
|------|------|----------|
| **Architecture overview** | README.md | `/developer_guide/organisation_creation/` |
| **Duplicate prevention details** | DUPLICATE_PREVENTION_GUIDE.md | `/developer_guide/organisation_creation/` |
| **Backend implementation** | BACKEND_IMPLEMENTATION.md | `/developer_guide/organisation_creation/` |
| **Key components list** | IMPLEMENTATION_CHECKLIST.md | `/developer_guide/organisation_creation/` |

---

## 🎯 What Does organisation Creation Do?

Users click "Create organisation" → Fill 4-step form → organisation created with user as admin.

```
Dashboard Welcome Page
  ↓
User clicks "Create organisation" card
  ↓
Modal opens with education overlay
  ↓
User reads FAQ (optional)
  ↓
User clicks "Start" → Form appears
  ↓
Step 1: organisation Name & Email
  ↓
Step 2: organisation Address
  ↓
Step 3: Representative (defaulted to "I am the representative")
  ↓
Step 4: Accept GDPR & Terms
  ↓
Submit POST /api/organizations
  ↓
organisation created, user attached as admin
  ↓
Redirect to organisation dashboard
```

---

## 📁 File Structure

```
resources/js/
├── Composables/
│   └── useOrganizationCreation.js           # State management ⭐
│
├── Components/organisation/
│   ├── OrganizationCreateModal.vue          # Main modal container
│   │
│   └── Steps/
│       ├── EducationSection.vue              # FAQ accordion
│       ├── FormInput.vue                     # Reusable input
│       ├── OrganizationStepBasicInfo.vue     # Step 1
│       ├── OrganizationStepAddress.vue       # Step 2
│       ├── OrganizationStepRepresentative.vue # Step 3 ⭐
│       └── FormNavigation.vue                # Buttons
│
└── Pages/Welcome/
    └── Dashboard.vue                         # Integration point

app/Http/Controllers/Api/
└── OrganizationController.php                # Backend API ⭐

database/migrations/
└── 2026_02_23_000245_*.php                   # Constraint migration

tests/
├── Unit/
│   └── Controllers/OrganizationControllerTest.php
└── Feature/
    └── OrganizationCreationTest.php
```

**⭐ = Most important files to understand**

---

## ⚡ Key Concepts at a Glance

### The Composable: `useOrganizationCreation`

Where ALL state lives. Think of it as the brain of the form.

```javascript
import { useOrganizationCreation } from '@/Composables/useOrganizationCreation'

// In any component:
const {
  currentStep,           // 0 (education), 1-3 (form steps)
  formData,             // User-entered data
  validationErrors,     // Error messages
  isSubmitting,         // Loading state
  openModal,            // Open modal
  closeModal,           // Close modal
  nextStep,             // Go to next step
  submitForm,           // Submit to API
  validateStep,         // Check if step is valid
} = useOrganizationCreation()
```

### The Modal: Three Views

1. **Education Overlay** (Step 0)
   - Shows FAQ about organisation creation
   - User reads "What is an organisation?" etc.
   - Click "Start" to proceed

2. **Form Steps** (Steps 1-3)
   - Step 1: Name & Email
   - Step 2: Address
   - Step 3: Representative + Acceptance

3. **Success State**
   - Show confirmation
   - Redirect to organisation page

---

## 🔒 The Three-Layer Protection (MUST KNOW!)

### Layer 1: UI Default
**File:** `useOrganizationCreation.js` line 37
```javascript
is_self: true,  // Checkbox is CHECKED by default
```
→ User doesn't see email field unless they uncheck the box

### Layer 2: Backend Validation
**File:** `OrganizationController.php` line 59 & 73
```php
// Check 1: Email must not match user's email (line 59)
if (strtolower($representativeEmail) === strtolower($user->email)) {
    // Skip - don't attach again
}

// Check 2: User must not already be attached (line 73)
$isAlreadyMember = $organisation->users()
    ->where('users.id', $representativeUser->id)
    ->exists();
if (!$isAlreadyMember) {
    // Safe to attach
}
```
→ Even if UI fails, backend prevents duplicate

### Layer 3: Database Constraint
**File:** Migration `2026_02_23_000245_*.php`
```sql
ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)
```
→ Database rejects any duplicate emails automatically

**Why three layers?** Defense in depth. If one fails, two more protect you.

---

## 🧪 Quick Testing

### Test 1: Does the form validation work?

```bash
# Open browser console
# Click "Create organisation" → Start → Skip validation

# Should see error messages
# Try with valid data → Should proceed
```

### Test 2: Does default UI work?

```bash
# Open modal
# Look at Step 3 (Representative)
# Checkbox "I am the representative" should be CHECKED ✓
# Email field should be HIDDEN ✓
```

### Test 3: Does code prevent duplicates?

```bash
# Create organisation with self as representative
# Go to /members/index
# Verify user appears ONCE with role "admin" (not twice)
```

### Test 4: Does database constraint work?

```bash
php artisan tinker
> User::create(['email' => 'test@example.com', 'name' => 'Test', 'password' => bcrypt('secret')])
> User::create(['email' => 'test@example.com', 'name' => 'Duplicate', 'password' => bcrypt('secret')])
# Should throw: SQLSTATE[23000]: Integrity constraint violation
```

---

## 🐛 Common Issues & Fixes

| Issue | Cause | Fix |
|-------|-------|-----|
| Modal doesn't open | Component not registered | Check Welcome.vue imports `OrganizationCreateModal` |
| Form validation always fails | Binding issue | Verify v-model is correctly bound to formData |
| Email field always visible | is_self is false | Change line 37 in useOrganizationCreation.js to `is_self: true` |
| Users still duplicated | Code checks missing | Verify lines 59 & 73 in OrganizationController.php exist |
| Database constraint error | Not applied | Run `php artisan migrate` |
| Translations missing | Key not in all files | Add to de.json, en.json, np.json |

---

## 📝 Making Changes

### Want to add a new field to Step 1?

1. **Add to formData** in `useOrganizationCreation.js`:
```javascript
basic: {
  name: '',
  email: '',
  phone: '',  // ← Add here
}
```

2. **Add to validation** in `validateStep()`:
```javascript
case 1:
  if (!formData.basic.phone?.trim()) {
    errors.phone = 'Phone required'
  }
  break;
```

3. **Add to component** `OrganizationStepBasicInfo.vue`:
```vue
<FormInput
  :value="data.phone"
  label="Phone"
  @input="$emit('update:phone', $event)"
  :error="errors.phone"
/>
```

4. **Add to API payload** in `submitForm()`:
```javascript
const payload = {
  // ...
  phone: formData.basic.phone.trim(),
}
```

5. **Update backend** `OrganizationController.php`:
```php
// In store() method
$organisation->update([
  // ...
  'phone' => $request->phone,
]);
```

### Want to add a new validation rule?

1. Add to `validateStep()` in the appropriate case:
```javascript
if (some_condition) {
  errors.field = 'Error message'
}
```

2. Add translation keys to all three language files:
```json
{
  "organisation": {
    "form": {
      "field_error": "German error message"
    }
  }
}
```

3. Use in validation:
```javascript
errors.field = this.$t('organisation.form.field_error')
```

---

## 🔐 Security Checklist

Before deployment, verify:

- [ ] All inputs are trimmed: `.trim()`
- [ ] All emails are lowercase: `.toLowerCase()`
- [ ] All emails validated with regex
- [ ] No direct SQL (use Query Builder or Eloquent)
- [ ] CSRF token included (handled by useCsrfRequest)
- [ ] Authentication required (middleware 'auth')
- [ ] Authorization checked (user must own organisation)
- [ ] Error messages don't leak information
- [ ] Validation runs on both frontend AND backend
- [ ] Sensitive data not logged

---

## 📚 Understanding the Data Flow

```
User Types Email
    ↓
@input event
    ↓
FormInput emits value
    ↓
Component receives and updates formData
    ↓
Composable reactive state updates
    ↓
validateStep() recalculates errors
    ↓
Errors display below field
    ↓
User fixes
    ↓
Errors clear
    ↓
nextStep() becomes enabled
```

---

## 🌍 Translations

All text is in three languages. When you see:

```vue
{{ $t('organisation.form.title', { fallback: 'Create organisation' }) }}
```

This looks for the key in the current language file:
- German: `resources/js/locales/pages/Dashboard/welcome/de.json`
- English: `resources/js/locales/pages/Dashboard/welcome/en.json`
- Nepali: `resources/js/locales/pages/Dashboard/welcome/np.json`

**To add new text:**
1. Add key to all three files (same key, different text)
2. Use in component with fallback
3. Test in all three languages

---

## 🚨 Critical Rules (DON'T BREAK THESE!)

1. **Never trust frontend validation alone**
   - Always validate on backend too

2. **Never attach user twice**
   - Check if already attached before attach()

3. **Always check email case-insensitively**
   - Use strtolower() for comparison

4. **Always make migrations idempotent**
   - Check if constraint exists before adding

5. **Always run tests after changes**
   - Especially duplicate prevention tests

---

## 🔗 Related Documentation

| Document | Purpose |
|----------|---------|
| README.md | Full architecture & design patterns |
| DUPLICATE_PREVENTION_GUIDE.md | Triple-layer protection deep dive |
| BACKEND_IMPLEMENTATION.md | API endpoint details |
| IMPLEMENTATION_CHECKLIST.md | Implementation progress tracking |
| ../MEMBERS_IMPLEMENTATION_COMPLETE.md | Members list page (related feature) |

---

## 💡 Pro Tips

### Tip 1: Debugging Form State
```javascript
// In component
console.log('Current step:', currentStep.value)
console.log('Form data:', formData)
console.log('Errors:', validationErrors)
```

### Tip 2: Testing with Cypress
```javascript
cy.visit('/dashboard')
cy.contains('Organisation erstellen').click()
cy.get('[role="dialog"]').should('be.visible')
```

### Tip 3: Database Verification
```bash
php artisan tinker
> organisation::first()->users()->with('pivot')->get()
```

### Tip 4: Checking Translations
```bash
# In browser console
window.$i18n.global.messages  # See all messages
```

---

## ❓ Frequently Asked Questions

**Q: Why is is_self checked by default?**
A: To prevent accidental duplicate members. Users don't see the email field unless they explicitly uncheck.

**Q: What happens if user enters their own email?**
A: Three layers catch it:
1. UI hides field (most common case prevented)
2. Backend email check prevents (if UI fails)
3. Database constraint prevents (if code fails)

**Q: Can I modify the form validation?**
A: Yes, in `validateStep()` method in useOrganizationCreation.js

**Q: How do I add a new language?**
A: Add a new file in `resources/js/locales/pages/Dashboard/welcome/{lang_code}.json` with same structure as existing files.

**Q: What if the API endpoint changes?**
A: Update the URL in `submitForm()` in useOrganizationCreation.js

**Q: Can users edit organisation details after creation?**
A: Not yet. That's a future feature.

---

## 🎓 Learning Path

1. **Start:** Read this file (you're here)
2. **Understand:** Review README.md architecture
3. **Deep Dive:** Read DUPLICATE_PREVENTION_GUIDE.md
4. **Implement:** Make a small change (add field, validation, etc.)
5. **Test:** Write a test for your change
6. **Deploy:** Follow the deployment checklist

---

## 📞 Getting Help

| Question | Answer Location |
|----------|-----------------|
| What files do I need to know? | See File Structure section |
| How does validation work? | See Understanding the Data Flow section |
| How do I prevent duplicates? | See The Three-Layer Protection section |
| How do I add a field? | See Making Changes section |
| Why isn't my translation showing? | See Translations section & check Troubleshooting |

---

**Last Updated:** February 23, 2026
**Version:** 1.0.0
**Status:** Production Ready

Good luck! 🚀
