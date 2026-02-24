# Accessibility Guide

**Using the system with assistive technologies**

This guide explains how to use the voter management system with screen readers, keyboard only, and other accessibility features.

---

## 📋 Table of Contents

1. [Keyboard Navigation](#keyboard-navigation)
2. [Screen Reader Support](#screen-reader-support)
3. [Visual Accessibility](#visual-accessibility)
4. [Mobile Accessibility](#mobile-accessibility)
5. [Accessibility Features](#accessibility-features)
6. [Troubleshooting](#troubleshooting)

---

## Keyboard Navigation

### Why Keyboard Navigation Matters

✅ **Benefits:**
- Faster than mouse for power users
- Accessibility for those with motor disabilities
- Works when mouse is unavailable
- Reduces eye strain (no precise targeting needed)

### Basic Keyboard Controls

**Navigation:**
```
Tab             → Move to next interactive element
Shift + Tab     → Move to previous interactive element
Arrow Keys      → Navigate within lists, menus, dialogs
Home/End        → Jump to first/last item
Page Up/Down    → Scroll page
```

**Activation:**
```
Enter/Space     → Click button, activate link
Escape          → Close dialog, cancel action
Alt + Letter    → Access menu items (varies by browser)
```

**Page Controls:**
```
Ctrl+F          → Find on page (browser search)
F5              → Refresh page
Ctrl+L          → Focus address bar
Ctrl+T          → New tab
```

### Step-by-Step: Using Voter List with Keyboard Only

**Task: Find and approve a voter**

```
Step 1: Login
- Ctrl+L → Go to address bar
- Type URL → https://yourplatform.com
- Enter → Go to page
- Tab → Move to email field
- Type email
- Tab → Move to password field
- Type password
- Tab → Move to "Remember Me" checkbox (optional)
- Tab → Move to [Sign In] button
- Enter → Login

Step 2: Navigate to Voters
- Tab → Navigate menu
- Arrow Keys → Find "Voters" option
- Enter → Select

Step 3: Find Voter
- Tab → Focus on search box
- Type "john smith"
- Enter → Search
- Arrow Keys → Navigate results
- Tab → Focus on first matching voter

Step 4: Approve Voter
- Tab → Move to [Approve] button
- Enter → Click [Approve]
- Tab → Navigate dialog
- Arrow Keys → Focus [Confirm] button
- Enter → Confirm approval
```

### Keyboard Shortcuts for Common Tasks

| Task | Shortcut |
|------|----------|
| Focus search box | Tab (usually 2-3 times) |
| Search for voter | Type term + Enter |
| Navigate search results | Arrow Down / Arrow Up |
| Approve voter | Tab + Enter |
| Close dialog | Escape |
| Find on page | Ctrl+F |
| Refresh page | F5 |

### Tips for Keyboard Users

**Tip 1: Use Tab order**
- Website designed for logical Tab order
- Top to bottom, left to right
- You'll reach all interactive elements

**Tip 2: Use arrow keys in dropdowns**
- Click dropdown with Enter
- Use Arrow Up/Down to navigate
- Press Enter to select
- Press Escape to close

**Tip 3: Screen reader users get extra help**
- Screen readers announce button purposes
- Labels help you know what each field is for

**Tip 4: Focus visible always**
- Website shows where keyboard focus is
- Look for outline or highlight
- Helps you know where you are

---

## Screen Reader Support

### What's a Screen Reader?

**Screen reader** = Software that reads web pages aloud

**Common screen readers:**
- NVDA (Windows, free)
- JAWS (Windows, commercial)
- VoiceOver (Mac/iOS, built-in)
- TalkBack (Android, built-in)

### Setting Up NVDA (Free Option)

**Download:**
1. Go to nvaccess.org
2. Click "Download"
3. Download latest version
4. Run installer
5. Restart your computer

**First use:**
1. Open NVDA
2. Open your browser
3. Go to election platform
4. NVDA will read the page aloud

### Screen Reader Navigation

**Commands vary by screen reader, but typically:**

```
NVDA (Windows):
Insert+Tab       → Read page summary
Insert+Down      → Read from current position
Insert+Up        → Read previous
Insert+Right     → Read next
Insert+End       → Read current line
Insert+H         → Heading navigation
Insert+F         → Form field navigation
Insert+T         → Table navigation
```

### Voter List Accessibility Features

**Page announces:**
```
"Election Management System
Organization: Election2024
Voters page loaded
Navigation menu, 5 items
Main content region"
```

**Table structure:**
```
Screen reader announces:
- Table caption: "Voter list for [Organization]"
- Column headers: "S.N., User ID, Name, Status, Approved By, Actions"
- Each row: "Row 1 of 3: John Smith, Pending, —"
```

**Interactive elements:**
```
Buttons announce:
- "[Approve button] for John Smith"
- "[Suspend button] for Jane Doe"
- "(Grayed out) Approve already completed"
```

### Status Updates

**Live region announcements:**
```
When you approve a voter, you hear:
"Voter approved. John Smith's status changed to approved."

When data updates:
"Voter list updated. 500 voters shown."
```

### Tips for Screen Reader Users

**Tip 1: Use heading navigation**
- Press H key to jump between headings
- Faster than reading entire page
- Helps you orient quickly

**Tip 2: Use form mode**
- Screen readers have form mode
- Jump between form fields with Tab
- Much faster than reading everything

**Tip 3: Use table navigation**
- Table with T navigation
- Jump between cells
- Hear row and column headers

**Tip 4: Use landmark navigation**
- Navigation, main, complementary regions
- Jump between sections
- Skip repetitive content

---

## Visual Accessibility

### High Contrast Mode

**Windows built-in high contrast:**
1. Settings → Ease of Access → High Contrast
2. Turn on high contrast
3. Web page switches to high contrast colors

**Browser extensions:**
- High Contrast (Chrome)
- Stylus (Chrome, Firefox) - Custom color schemes

**Within the website:**
- Status colors tested for 4.5:1 contrast
- All text readable on background
- Green/red uses symbols, not just color

### Dark Mode

**Many browsers support dark mode:**
```
Browser dark mode often auto-applies to websites
Reduces eye strain in low light
Website respects your preference
```

**Enable dark mode:**
- Chrome: Settings → Appearance → Dark
- Firefox: Add-ons → Find dark theme
- Safari: System Preferences → General → Dark

### Text Resizing

**Browser text zoom:**
```
Ctrl + Plus (+)      → Increase text size
Ctrl + Minus (-)     → Decrease text size
Ctrl + 0             → Reset to normal
```

**System-level zoom:**
```
200% zoom support:
- Website reflows properly
- No horizontal scrolling
- All content accessible
```

**Supported browsers:**
- All modern browsers support zoom
- Zoom persists for that website
- Different sites can have different zoom levels

### Color Contrast

**Website uses these color pairs:**

| Element | Foreground | Background | Ratio |
|---------|-----------|-----------|-------|
| **Text** | Black | White | 21:1 ✅ |
| **Status: Approved** | Green | White | 4.5:1 ✅ |
| **Status: Pending** | Orange | White | 4.5:1 ✅ |
| **Status: Suspended** | Red | White | 4.5:1 ✅ |
| **Buttons** | Black | Gray | 7:1 ✅ |
| **Links** | Blue | White | 8.5:1 ✅ |

**All tested to WCAG 2.1 AA standard** (4.5:1 minimum)

### Focus Indicators

**Keyboard focus visible:**
```
When using Tab to navigate, you see:
- Outline around focused element
- Color change on button
- Clear indication of where you are
- Never hidden or too faint
```

**Example:**
```
[Approve]  ← No focus (outline not visible)

[Approve]  ← With focus (blue outline visible)
```

---

## Mobile Accessibility

### Touch Target Size

**All buttons are minimum 44×44 pixels:**
- Fingers are not precise
- Larger targets easier to tap
- No accidental misclicks
- Work gloves don't interfere

### Mobile Screen Reader

**VoiceOver (iPhone/iPad):**
1. Settings → Accessibility → VoiceOver
2. Turn on VoiceOver
3. Swipe right to navigate
4. Double-tap to activate
5. Use two-finger Z for undo

**TalkBack (Android):**
1. Settings → Accessibility → TalkBack
2. Turn on TalkBack
3. Tap to explore
4. Double-tap to activate
5. Swipe down for menu

### Responsive Design

**Website works at all sizes:**
```
320px (iPhone SE):
- Single column layout
- Large buttons
- Text readable without zoom

768px (iPad):
- Two-column layout
- Optimized spacing
- Table still accessible

1024px+ (Desktop):
- Full multi-column layout
- All features visible
- Optimized for mouse
```

### Touch-Friendly Navigation

**Mobile version features:**
- No hover states (mobile has no hover)
- Touch targets large (44×44px minimum)
- No small, hard-to-tap buttons
- Swipe alternatives to scrolling

---

## Accessibility Features

### Skip Links

**Skip to main content:**
- First link on every page
- Press Tab immediately after loading
- Skips navigation, goes to content
- Saves time for keyboard users

**How to use:**
```
1. Page loads
2. Press Tab immediately
3. First link: "Skip to main content"
4. Press Enter
5. Focus jumps to main content area
```

### Semantic HTML

**Website uses proper HTML structure:**
```
<main>              - Page main content area
<nav>               - Navigation region
<header>            - Page header
<footer>            - Page footer
<table>             - Data tables (not for layout)
<label>             - Form field labels
<h1>, <h2>, ...     - Headings (proper hierarchy)
```

**Why this matters:**
- Screen readers understand structure
- Keyboard users can navigate by region
- Content makes semantic sense
- Easy to parse programmatically

### ARIA Labels

**ARIA = Accessible Rich Internet Applications**

**Example ARIA usage:**
```
Button: [✓]
aria-label="Approve voter John Smith"
→ Screen reader announces: "Approve voter John Smith button"

Without ARIA: "Button" (meaningless)
```

**All icon buttons have ARIA labels:**
- Action icons describe what they do
- User knows purpose before clicking
- Works with keyboard and screen reader

### Form Accessibility

**All form fields accessible:**

```
<label for="search">Search by name, ID...</label>
<input id="search" type="text">

- Label associated with input
- Clicking label focuses input
- Screen reader announces label
- Form mode navigation works
```

**Form validation:**
```
Error message included:
- aria-describedby points to error message
- Screen reader reads error
- Focus moves to error
- User knows what went wrong
```

---

## Troubleshooting

### ❌ "Screen reader not reading page"

**Problem:** NVDA/JAWS not announcing content

**Solutions:**
1. ✅ Make sure screen reader is running
2. ✅ Make sure browser focus is on page (click page)
3. ✅ Try pressing Insert+Home (NVDA) to read from start
4. ✅ Try refreshing page
5. ✅ Check if page is in "application mode" (try pressing Alt+A)

---

### ❌ "Focus outline not visible"

**Problem:** Can't see where keyboard focus is

**Solutions:**
1. ✅ Check high contrast mode
2. ✅ Check browser zoom level (very large zoom may hide outline)
3. ✅ Try different browser
4. ✅ Try pressing Tab to highlight next element

---

### ❌ "Buttons not announcing correctly"

**Problem:** Screen reader says "button" but not what button does

**Solutions:**
1. ✅ Website has ARIA labels (should announce purpose)
2. ✅ Try different screen reader (NVDA vs JAWS may vary)
3. ✅ Check if using browser reader (different from software reader)
4. ✅ Report to support if truly unlabeled

---

### ❌ "Text too small even with zoom"

**Problem:** Zoomed to 200% but text still too small

**Solutions:**
1. ✅ Use system-level zoom (not browser zoom)
2. ✅ Use operating system text size settings
3. ✅ Use high contrast mode (often increases size too)
4. ✅ Try browser extension for additional text sizing
5. ✅ Consider using screen reader instead

---

### ❌ "Mobile touch targets too small"

**Problem:** Hard to tap buttons on phone

**Solutions:**
1. ✅ All buttons are 44×44px minimum (large enough)
2. ✅ If still hard, try holding phone differently
3. ✅ Try zooming (pinch zoom on phone)
4. ✅ Use device accessibility settings
5. ✅ Consider using screen reader

---

## Accessibility Standards

### WCAG 2.1 AA Compliance

**Website tested for:**

| Criterion | Status | Notes |
|-----------|--------|-------|
| **1.4.3 Contrast** | ✅ Pass | All text 4.5:1 minimum |
| **2.1.1 Keyboard** | ✅ Pass | All features keyboard accessible |
| **2.1.2 No Keyboard Trap** | ✅ Pass | Can always Tab away |
| **2.4.3 Focus Order** | ✅ Pass | Logical Tab order |
| **2.4.7 Focus Visible** | ✅ Pass | Always see focus |
| **3.2.1 On Focus** | ✅ Pass | No unexpected changes |
| **4.1.2 Name, Role, Value** | ✅ Pass | All elements labeled |
| **4.1.3 Status Messages** | ✅ Pass | Live region updates |

**Testing performed:**
- Automated scanning (axe, Lighthouse)
- Manual keyboard testing
- Screen reader testing
- Color contrast verification
- Visual inspection

---

## Reporting Accessibility Issues

**Found an accessibility problem?**

**What to report:**
1. What you were trying to do
2. What assistive technology you use
3. What went wrong
4. Steps to reproduce
5. Screenshots if helpful

**Where to report:**
- Email: accessibility@yourplatform.com
- Form: [Accessibility feedback form on site]

**Example:**
```
Subject: Voter list table not readable with NVDA

Using NVDA on Windows 10, when I navigate the voter list table:
- Table headers not announced properly
- Can't tell which column is which
- Row headers missing

Steps to reproduce:
1. Open voter list page
2. Start NVDA
3. Press Insert+T for table mode
4. Navigate table

Expected: Hear row and column headers
Actual: Hear only data, no headers
```

---

## Accessibility Resources

**Learning more:**
- WebAIM: webaim.org (Screen reader guides)
- WCAG Guidelines: w3.org/WAI/WCAG21 (Full standard)
- NVDA: nvaccess.org (Free screen reader)
- JAWS: freedomscientific.com (Commercial screen reader)

---

## Next Steps

👉 **Need general help?** Go to [Getting Started](./01-getting-started.md)

👉 **Tips and troubleshooting?** Go to [Tips & Troubleshooting](./07-tips-troubleshooting.md)

👉 **Change language?** Go to [Language Settings](./09-language-settings.md)

---

## 🆘 Need Help?

- **Keyboard navigation help?** See [Keyboard Navigation](#keyboard-navigation)
- **Screen reader not working?** See [Troubleshooting](#troubleshooting)
- **Visual accessibility?** See [Visual Accessibility](#visual-accessibility)
- **Still stuck?** Contact accessibility@yourplatform.com

---

**Happy voting! 🗳️**
