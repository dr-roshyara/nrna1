# नेपाली साधारण शब्द शब्दावली - Nepali Common Words Glossary

**Objective:** Replace uncommon/formal Nepali words with everyday Nepali equivalents. If no common Nepali exists, use English (Roman script).

**Date Created:** 2026-05-01

---

## Priority 1: Highest Frequency (597+ instances)

### गर्नुहोस् → Varies by context

**Frequency:** 597 instances  
**Issue:** Formal imperative - sounds like commands to officials

| Context | Uncommon | Common Alternative | Example |
|---------|----------|-------------------|---------|
| Button labels | गर्नुहोस् | गर or English | "Save", "Cancel", "Delete" |
| Help text | गर्नुहोस् | गर | "यहाँ क्लिक गर" (Click here) |
| Long phrases | ...गर्नुहोस् | ...गर | "खाता बनाउनुहोस्" → "खाता बनाउ" |
| Instructions | ...गर्नुहोस् | ...गर | "दर्ता गर्नुहोस्" → "दर्ता गर" |

**Action Plan:**
- Button labels: Change `गर्नुहोस्` → Use English (Save, Cancel, Delete)
- Help text: Change `गर्नुहोस्` → `गर`
- Long phrases: Shorten by removing formality

**Examples of transformation:**
- ❌ "खाता बनाउनुहोस्" → ✅ "खाता बनाउ"
- ❌ "दर्ता गर्नुहोस्" → ✅ "दर्ता गर"
- ❌ "प्रविष्ट गर्नुहोस्" → ✅ "भर" (simple action)

---

## Priority 2: High Frequency (80+ instances)

### आवश्यक (required/necessary) → लाग्छ, चाहिन्छ, or छ

**Frequency:** 86 instances  
**Issue:** Formal/bureaucratic word

| Uncommon | Common | Context | Example |
|----------|--------|---------|---------|
| आवश्यक छ | लाग्छ | Need/requirement | "पासवर्ड लाग्छ" (Password is needed) |
| आवश्यक छ | चाहिन्छ | Want/need | "नाम चाहिन्छ" (Name is needed) |
| आवश्यक | सच (essential) | Critical field | "(सच)" as indicator |
| आवश्यक छैन | छैन | Not needed | Simply say "छैन" |

**Action Plan:** Replace `आवश्यक` with `लाग्छ` or `चाहिन्छ` or simply mark with asterisk

---

### पुष्टि (confirm) → तपाई, सहमत, or English "Confirm"

**Frequency:** 84 instances  
**Issue:** Formal/technical word

| Uncommon | Common | Context | Example |
|----------|--------|---------|---------|
| पुष्टि गर्नुहोस् | Confirm | Button | "Confirm" (English) |
| पुष्टि गर्नुहोस् | ठीक छ | Agreement | "ठीक छ" (Okay/Agree) |
| पासवर्ड पुष्टि | पासवर्ड दोबारा | Confirm password | "पासवर्ड दोबारा भर" |
| पुष्टि आवश्यक | दोबारा भर चाहिन्छ | Required again | Clear instruction |

**Action Plan:** Replace `पुष्टि` with context-specific words or English "Confirm"

---

### कृपया (please) → Remove entirely

**Frequency:** 66 instances  
**Issue:** Overly formal, sounds bureaucratic, not natural in Nepali UI

| Uncommon | Fix | Example |
|----------|-----|---------|
| कृपया लगइन गर्नुहोस् | लगइन गर | (Just the action) |
| कृपया आफ्नो नाम भर्नुहोस् | आफ्नो नाम भर | (Direct instruction) |
| कृपया यहाँ क्लिक गर्नुहोस् | यहाँ क्लिक गर | (Direct) |

**Action Plan:** Remove all `कृपया` prefixes - they're not necessary in modern UI

---

## Priority 3: Medium Frequency (40+ instances)

### प्रविष्ट (enter/input) → भर (fill)

**Frequency:** 42 instances  
**Issue:** Formal/technical, not everyday word

| Uncommon | Common | Context | Example |
|----------|--------|---------|---------|
| प्रविष्ट गर्नुहोस् | भर | Input field | "नाम भर" (Enter name) |
| प्रविष्ट | भर | Instruction | "ईमेल भर" (Fill email) |
| प्रविष्ट | Type | Tech savvy context | "Type your password" |

**Action Plan:** Replace `प्रविष्ट` with `भर` in all contexts

---

## Priority 4: Lower Frequency (10+ instances)

### प्रतीक (symbol) → चिन्ह

**Frequency:** 11 instances  
**Issue:** Formal for everyday use

| Uncommon | Common | Example |
|----------|--------|---------|
| प्रतीक | चिन्ह | "विशेष चिन्ह" (special character) |

**Action Plan:** Replace `प्रतीक` with `चिन्ह`

---

### अन्तिम नाम (final name) → थर (surname)

**Frequency:** 6 instances  
**Issue:** Awkward phrasing

| Uncommon | Common | Example |
|----------|--------|---------|
| अन्तिम नाम | थर | "आफ्नो थर" (Your surname) |

**Action Plan:** Replace `अन्तिम नाम` with `थर`

---

## Priority 5: When No Common Nepali Exists → Use English

### Technical Terms → English (Roman Script)

| Uncommon Nepali | English Alternative | Context |
|-----------------|-------------------|---------|
| प्रमाणीकरण | Auth or Login | Keep as is or use English |
| डेटाबेस | Database | Use English |
| API | API | Use English |
| Configuration | Configuration | Use English |
| Dashboard | Dashboard | Use English |
| Settings | Settings | Use English |
| Profile | Profile | Use English |
| Save | Save | Use English for buttons |
| Cancel | Cancel | Use English for buttons |
| Delete | Delete | Use English for buttons |

---

## Master Replacement Table

| Priority | Uncommon Word | Common Equivalent | Frequency | Status |
|----------|---------------|-------------------|-----------|--------|
| 1 | गर्नुहोस् | गर / English | 597 | 🔴 TO DO |
| 2 | आवश्यक | लाग्छ / चाहिन्छ | 86 | 🔴 TO DO |
| 2 | पुष्टि | Confirm / ठीक छ | 84 | 🔴 TO DO |
| 2 | कृपया | (remove) | 66 | 🔴 TO DO |
| 3 | प्रविष्ट | भर | 42 | 🔴 TO DO |
| 4 | प्रतीक | चिन्ह | 11 | 🔴 TO DO |
| 4 | अन्तिम नाम | थर | 6 | 🔴 TO DO |

---

## Implementation Strategy

### Phase 1: Remove Formality (No risk)
1. Remove all `कृपया` prefixes
2. Shorten `गर्नुहोस्` → `गर` in help text

### Phase 2: High Frequency Replacements
1. Replace `आवश्यक` with `लाग्छ`/`चाहिन्छ`
2. Replace `पुष्टि गर्नुहोस्` with "Confirm" button
3. Replace `प्रविष्ट` with `भर`

### Phase 3: Polish
1. Replace `प्रतीक` → `चिन्ह`
2. Replace `अन्तिम नाम` → `थर`
3. Audit remaining uncommon words

### Phase 4: Testing
- Switch to Nepali language
- Test all forms and buttons
- Verify readability for non-technical users

---

## Examples of "Before & After"

### Example 1: Registration Form

**BEFORE (Uncommon):**
```
खाता बनाउनुहोस्
कृपया आफ्नो पहिलो र दोस्रो नाम प्रविष्ट गर्नुहोस्
अन्तिम नाम प्रविष्ट गर्नुहोस्
पासवर्ड आवश्यक छ
खाता बनाउनुहोस्
```

**AFTER (Common):**
```
खाता बनाउ
आफ्नो नाम भर
आफ्नो थर भर
पासवर्ड चाहिन्छ
Create Account
```

### Example 2: Help Text

**BEFORE:**
```
कृपया एक शक्तिशाली पासवर्ड बनाउनुहोस्। कम्तिमा 8 अक्षर आवश्यक छ।
बडो अक्षर, साना अक्षर, संख्या र प्रतीकहरूको मिश्रण प्रयोग गर्नुहोस्।
```

**AFTER:**
```
मजबुत पासवर्ड बनाउ। कम्तिमा 8 अक्षर चाहिन्छ।
अक्षर, अंक र चिन्ह मिलाउ।
```

---

## Notes for Implementation

1. **Button Labels:** Use English for universally understood actions (Save, Cancel, Delete, Submit)
2. **Help Text:** Use common Nepali, remove formality
3. **Placeholders:** Short, direct, no formality
4. **Error Messages:** Clear and simple language
5. **Dialog Text:** Conversational tone, not bureaucratic

---

## Verification Checklist

After making changes, check that:
- [ ] No `कृपया` in UI
- [ ] No `गर्नुहोस्` in button labels
- [ ] Help text uses `भर`, `लाग्छ`, `चाहिन्छ`
- [ ] Form feels "human" not "official"
- [ ] Technical terms in English are clear
- [ ] All text is scannable (short lines)

---

**Generated:** 2026-05-01  
**Status:** Ready for implementation  
**Next Step:** Begin Phase 1 replacements
