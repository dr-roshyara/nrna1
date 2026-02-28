# Language Settings

**Available languages and how to change them**

This guide explains how to change your language and what languages are supported.

---

## 📋 Table of Contents

1. [Supported Languages](#supported-languages)
2. [How to Change Language](#how-to-change-language)
3. [What Gets Translated](#what-gets-translated)
4. [Language-Specific Features](#language-specific-features)
5. [Troubleshooting](#troubleshooting)

---

## Supported Languages

### Available Languages

The Election Management System is available in **3 languages**:

| Language | Code | Native Name | Status |
|----------|------|------------|--------|
| **English** | en | English | ✅ Fully supported |
| **German** | de | Deutsch | ✅ Fully supported |
| **Nepali** | np | नेपाली | ✅ Fully supported |

### Why These Languages?

✅ **English** - International business language
✅ **German** - Primary language for German organizations
✅ **Nepali** - Language for Nepali organizations

### Default Language

- **Default:** English (en)
- **Auto-detect:** Browser language preferences (if available)
- **Your preference:** Saved for next login

---

## How to Change Language

### Method 1: Using Language Menu (Easiest)

**Location:** Top right corner of page

```
┌──────────────────────────────────────────────┐
│ Election Management System    [EN ▼] [Menu] │
│                                ↑ Click here
└──────────────────────────────────────────────┘
```

**Steps:**
1. Click the **language dropdown** (shows "EN", "DE", or "NP")
2. Menu opens with language options:
   ```
   [ English ]
   [ Deutsch (German) ]
   [ नेपाली (Nepali) ]
   ```
3. Click your preferred language
4. Page immediately changes to that language
5. Your preference is saved

**Example:**
```
Current: English
1. Click [EN ▼]
2. Click [Deutsch]
3. Page updates to German immediately
4. All text now in German
5. Next time you login, German is default
```

### Method 2: Browser Language Settings

**If language dropdown doesn't appear:**

**Chrome:**
1. Settings → Languages and input → Languages
2. Add your language to the top
3. Language automatically applied to websites

**Firefox:**
1. Settings → General → Language
2. Select your preferred language
3. Some websites detect this setting

**Safari:**
1. Preferences → General → Language
2. Select from dropdown
3. Website language adjusts

### Method 3: Account Settings

**Long-term preference:**

**Steps:**
1. Click your **profile menu** (top right)
2. Select **"Settings"** or **"Preferences"**
3. Find **"Language"** option
4. Select from dropdown
5. Save changes
6. Language applies to all sessions

---

## What Gets Translated

### Fully Translated Elements

✅ **Everything in the voter list:**
- Page titles
- Column headers (Name, Status, etc.)
- Button labels ([Approve], [Suspend])
- Menu items
- Messages and notifications
- Form labels and placeholders
- Dialogs and confirmations
- Help text and instructions
- Error messages

✅ **Voter management interface:**
- Status indicators (Approved, Pending, Suspended)
- Statistics cards
- Search and filter descriptions
- Table data labels
- Live region announcements

✅ **Documentation:**
- This help guide (translated online)
- Tooltips and hints
- Context-sensitive help

### Partially Translated Elements

⚠️ **Voter data (not translated):**
- **Voter names** - Keep original name
- **User IDs** - System identifier (not translatable)
- **Dates** - May be formatted by system locale
- **Custom fields** - organisation-specific data

**Why:**
- Voter information is personal data
- Should not change to respect privacy
- Names are part of identity
- IDs are technical identifiers

### Browser UI Elements

❓ **Browser elements (not translated by us):**
- Browser menu (File, Edit, etc.)
- Browser buttons (refresh, back)
- Scrollbar labels
- System notifications

**Why:**
- These are browser features
- Depend on your operating system language
- Not controlled by election system

---

## Language-Specific Features

### Nepali (नेपाली)

**Special features:**
- ✅ Full Unicode support for Devanagari script
- ✅ Nepali date formatting
- ✅ Nepali number formatting (if applicable)
- ✅ Right-to-left text support (if needed)

**Example:**
```
English:  February 23, 2026
Nepali:   फेब्रुअरी २३, २०२६
```

### German (Deutsch)

**Special features:**
- ✅ Proper German noun capitalization
- ✅ German date format (DD.MM.YYYY)
- ✅ German number format (1.000,50)
- ✅ Umlauts and special characters (ä, ö, ü, ß)

**Example:**
```
English:  Approve Voter
German:   Wähler Genehmigen

English:  1,000 voters
German:   1.000 Wähler
```

### English

**Standard features:**
- ✅ English date format (MM/DD/YYYY)
- ✅ English number format (1,000.50)
- ✅ Latin characters only

**Example:**
```
English:  March 1, 2026
          1,500 voters
```

---

## Language-Specific Usage Tips

### Working in Nepali

**Tips for Nepali users:**

**Tip 1: Keyboard input**
- Ensure Nepali keyboard layout enabled
- Windows: Settings → Input Method
- Mac: System Preferences → Keyboard → Input Sources
- Add Nepali (Devanagari) keyboard

**Tip 2: Searching in Nepali**
- Search supports Nepali characters
- Type name in Devanagari script
- Example: "राज सिंह" (Raj Singh)
- System finds matches

**Tip 3: Copy/paste from Nepali text**
- Can paste Nepali voter names
- System preserves characters
- Search and filter work correctly

### Working in German

**Tips for German users:**

**Tip 1: Special characters**
- Website supports ä, ö, ü, ß
- No special handling needed
- Type normally

**Tip 2: Sorting with umlauts**
- Names with ä sort after a
- Names with ö sort after o
- Standard German sorting rules
- Example: "Äußer" sorts as "Auesser"

**Tip 3: German keyboard**
- Windows: Language bar shows DE
- Mac: Input source shows Deutsch
- Type ä: ['] + A (or Alt + key)
- Type ö: ['] + O (or Alt + key)
- Type ü: ['] + U (or Alt + key)
- Type ß: ['] + S (or Alt + S)

### Working in English

**Tips for English users:**

**Tip 1: Standard input**
- Use standard US/UK keyboard
- No special character handling needed

**Tip 2: Date format**
- Displayed as: MM/DD/YYYY
- Example: 02/23/2026
- Matches US standard

**Tip 3: Numbers**
- Thousands separator: comma (,)
- Decimal separator: period (.)
- Example: 1,500.50

---

## Translating Content

### If Translation is Missing

**If you see English text when language is set to German:**

1. ✅ **Likely causes:**
   - Translation not yet completed
   - New feature (may not be translated immediately)
   - Custom field from organisation

2. **What to do:**
   - This is normal during rollout
   - Check back after updates
   - Report missing translation to support

3. **How to report:**
   ```
   Email: translations@yourplatform.com

   Subject: Missing German Translation

   What text: "Approve Voter"
   Where: Voter list page
   Current: Shows English
   Expected: Should show German
   ```

### Browser Translation

**Alternative: Use browser translation**

If system doesn't have your language:

**Chrome:**
1. Right-click page
2. Select "Translate to [Your Language]"
3. Page translates automatically
4. Quality varies (automated translation)

**Firefox:**
1. Click translate icon in address bar
2. Select your language
3. Page translates automatically

**Safari:**
1. Menu → Translate → [Your Language]
2. Page translates

⚠️ **Note:** Browser translation is automated, may be less accurate than professional translation.

---

## Language and Localization Features

### Dates

**How dates display:**

```
English (en):
Monday, February 23, 2026
02/23/2026

German (de):
Montag, 23. Februar 2026
23.02.2026

Nepali (np):
सोमबार, फेब्रुअरी २३, २०२६
२०२६-०२-२३
```

### Numbers

**How numbers display:**

```
English (en):
1,500.50 voters
1,000 approved

German (de):
1.500,50 Wähler
1.000 genehmigt

Nepali (np):
१,५००.५० मतदाता
१,००० स्वीकृत
```

### Time

**How time displays:**

```
English (en):
2:30 PM EST
02:30 (24-hour)

German (de):
14:30 Uhr CET
14:30 (24-hour)

Nepali (np):
२:३० अपरान्ह
१४:३०
```

---

## Troubleshooting

### ❌ "Page still in English after changing language"

**Problem:** Changed language to German, but page stayed English

**Causes & Solutions:**

**1. Cache not cleared**
- Solution: Clear browser cache
- Settings → Privacy → Clear browsing data
- Reload page

**2. Old page still loaded**
- Solution: Hard refresh page
- Ctrl+Shift+R (or Cmd+Shift+R on Mac)
- Forces full page reload

**3. Language setting didn't save**
- Solution: Try again
- Click language dropdown
- Select language again
- Wait for page reload

**4. Browser doesn't support language**
- Solution: Use different browser
- Try Chrome, Firefox, Safari
- All should support all languages

---

### ❌ "Special characters not displaying correctly"

**Problem:** Seeing boxes (□□□) instead of Nepali text

**Causes:**
- Font doesn't support characters
- Browser encoding issue
- System language not set up

**Solutions:**

**1. Install language support (Windows)**
- Settings → Time & Language → Language
- Click [+] to add language
- Download language pack
- Restart computer

**2. Install language support (Mac)**
- System Preferences → Language & Region
- Click [+] to add language
- Download additional fonts
- Restart computer

**3. Try different browser**
- Chrome usually handles all scripts well
- Firefox also supports Unicode well
- Try Chrome if Safari has issues

**4. Update browser**
- Outdated browsers may lack support
- Update to latest version
- Install any pending updates

---

### ❌ "Can't type in my language"

**Problem:** Want to search for "राज" (Nepali) but can't type it

**Causes:**
- Language keyboard not enabled
- Input method not selected
- Wrong keyboard layout

**Solutions:**

**Enable Nepali keyboard (Windows):**
1. Settings → Time & Language → Language
2. Click your language
3. Click "Options"
4. Download language pack if needed
5. Keyboard now available in taskbar

**Enable Nepali keyboard (Mac):**
1. System Preferences → Keyboard → Input Sources
2. Click [+]
3. Select "Nepali" or "Devanagari"
4. Click "Add"
5. Switch input source in top menu bar

**Enable German keyboard (if needed):**
1. Windows: Settings → Time & Language
2. Mac: System Preferences → Keyboard
3. Select "German" or "Deutsch"
4. Switch to German keyboard
5. Now type German characters (ä, ö, ü)

---

### ❌ "Numbers showing in wrong format"

**Problem:** System shows "1.500,50" but I expect "1,500.50"

**This is correct!**

**Explanation:**
- Different languages use different number formats
- German: 1.500,50 (period for thousands, comma for decimals)
- English: 1,500.50 (comma for thousands, period for decimals)
- Nepali: १,५००.५० (varies by convention)

**The system shows what's correct for your language.**

**If you need English numbers:**
1. Change language to English
2. Numbers format changes to 1,500.50

---

## Next Steps

👉 **Need help with the system?** Go to [Getting Started](./01-getting-started.md)

👉 **Having general issues?** Go to [Tips & Troubleshooting](./07-tips-troubleshooting.md)

👉 **Need accessibility in your language?** Go to [Accessibility Guide](./08-accessibility.md)

---

## 🆘 Need Help?

- **Can't see your language?** Contact support@yourplatform.com
- **Character display issues?** See [Troubleshooting](#troubleshooting)
- **Missing translation?** Report to translations@yourplatform.com
- **Keyboard input help?** See [Language-Specific Usage Tips](#language-specific-usage-tips)

---

## Language Support Policy

**We support:**
✅ English, German, Nepali (full support)

**Requesting new language:**
- Email: translations@yourplatform.com
- Tell us: Which language you need
- Impact: How many users would use it
- Timeline: When you need it by

**Translation process:**
1. Assessment (1 week)
2. Translation (2-4 weeks depending on scope)
3. Testing (1 week)
4. Deployment (1-2 weeks)
5. Total: 5-8 weeks typical

---

**Happy voting! 🗳️**
