# WCAG 2.1 AA Accessibility Checklist - Voter Management System

**Date**: ____________
**Tester**: ____________
**Browser/Device**: ____________
**Overall Result**: ☐ PASS ☐ FAIL

---

## 1️⃣ STRUCTURE & SEMANTICS

### Semantic HTML Elements
- [ ] Page has `<main>` element containing primary content
- [ ] Page has `<header>` element for header
- [ ] Page has `<nav>` element for breadcrumb navigation
- [ ] Sections use `<section>` or semantic container elements
- [ ] Forms use `<form>`, `<label>`, `<input>`, `<button>`
- [ ] Table uses `<table>`, `<thead>`, `<tbody>`, `<th>`, `<tr>`, `<td>`
- [ ] Content is in logical reading order (DOM order matches visual order)

### Heading Structure
- [ ] Page has exactly ONE `<h1>` tag
- [ ] H1 text: "Voter Management" ✓
- [ ] Major sections have `<h2>` tags:
  - [ ] "Filter Voters" section (if visible)
  - [ ] Statistics section (if applicable)
  - [ ] Voter list section
- [ ] No skipped heading levels (no `<h1>` then `<h3>`)
- [ ] Headings accurately describe section content
- [ ] No fake headings (styled divs instead of `<h1>`-`<h6>`)

---

## 2️⃣ NAVIGATION & LINKS

### Breadcrumb Navigation
- [ ] Breadcrumb present and visible
- [ ] Breadcrumb uses `<nav aria-label="Breadcrumb">`
- [ ] Each breadcrumb item is a link (not just text)
- [ ] Current page not linked in breadcrumb
- [ ] Clear visual separator between items

### Links
- [ ] All links have meaningful text (not "click here")
- [ ] Link purpose is clear from link text alone OR context
- [ ] Links are visually distinct from regular text
- [ ] Links have visible focus indicator
- [ ] No dead links (all links go somewhere)

---

## 3️⃣ KEYBOARD NAVIGATION

### Tab Navigation
- [ ] Can reach all interactive elements using TAB key
- [ ] Tab order is logical (left-to-right, top-to-bottom):
  1. [ ] Organization name link
  2. [ ] Search input
  3. [ ] Status filter dropdown
  4. [ ] Clear button
  5. [ ] Select all checkbox (if visible)
  6. [ ] First row of data
  7. [ ] Approve/Suspend buttons for each voter
  8. [ ] Pagination buttons
- [ ] No invisible elements receive focus
- [ ] Focus order matches visual order

### Keyboard Shortcuts
- [ ] ENTER activates buttons
- [ ] SPACE activates buttons/checkboxes
- [ ] ESCAPE dismisses any modals
- [ ] ARROW keys navigate dropdowns
- [ ] No keyboard traps (can always TAB out)

### Focus Indicators
- [ ] Focus indicator visible on all interactive elements
- [ ] Focus indicator has sufficient contrast (3:1 minimum)
- [ ] Focus indicator is not obscured
- [ ] Focus outline not removed (no `outline-solid: none` without replacement)

---

## 4️⃣ FORM ACCESSIBILITY

### Labels
- [ ] Search input has associated `<label>` tag
- [ ] Search label `for="search"` matches input `id="search"`
- [ ] Status filter has associated `<label>` tag
- [ ] Status label `for="status"` matches select `id="status"`
- [ ] All form fields have labels (not placeholders only)

### Input Fields
- [ ] Search input has type `"text"` or `"search"`
- [ ] Input fields are properly sized for their content
- [ ] Placeholder text (if used) does not convey required information
- [ ] Autocomplete suggestions work properly

### Error Handling
- [ ] Bulk approve with no voters shows error message
- [ ] Error message is visible and clear
- [ ] Error message has sufficient color contrast
- [ ] Error not conveyed by color alone

---

## 5️⃣ COLOR & CONTRAST

### Text Contrast
- [ ] Body text (gray-900 on white): 4.5:1 ratio ✓
- [ ] Page title: 4.5:1 ratio minimum
- [ ] All text readable on background color
- [ ] Links distinguishable from regular text
- [ ] Tested in:
  - [ ] Light mode
  - [ ] Dark mode
  - [ ] Windows High Contrast mode (if applicable)

### Status Indicators
- [ ] "Approved" badge: Has color AND text ("Approved") ✓
- [ ] "Pending" badge: Has color AND text ("Pending") ✓
- [ ] "Voted" badge: Has color AND text ("Voted") ✓
- [ ] Color is not the only way to distinguish status
- [ ] Status badges have 3:1 contrast minimum

### Buttons
- [ ] Approve button (green) has sufficient contrast
- [ ] Suspend button (red) has sufficient contrast
- [ ] Disabled buttons show disabled state clearly
- [ ] Active/selected state is clear

### Using Tools
- [ ] Tested with WebAIM Contrast Checker
- [ ] Tested with browser DevTools
- [ ] No colors fail contrast test

---

## 6️⃣ RESPONSIVE DESIGN & MOBILE

### Responsive Behavior
- [ ] Layout works at 320px width (iPhone SE)
- [ ] Layout works at 768px width (tablet)
- [ ] Layout works at 1024px width (desktop)
- [ ] Text resizable to 200% without loss of function
- [ ] No horizontal scrolling (except for data tables)
- [ ] No content hidden without explanation

### Touch Targets (Mobile)
- [ ] All buttons are at least 44x44 pixels
- [ ] All links are at least 44x44 pixels
- [ ] Adequate spacing between touch targets (8px minimum)
- [ ] No accidental activation of nearby buttons
- [ ] Checkboxes in table have large click area

### Mobile Testing Checklist
- [ ] Tested on mobile device OR browser mobile view
- [ ] Portrait orientation works
- [ ] Landscape orientation works
- [ ] All functions accessible on mobile
- [ ] Text input method works (keyboard appears)

---

## 7️⃣ SCREEN READER TESTING

### Screen Reader Setup
- [ ] Using: ☐ NVDA ☐ JAWS ☐ VoiceOver ☐ TalkBack
- [ ] Screen reader started successfully
- [ ] Testing with: ☐ Firefox ☐ Chrome ☐ Edge ☐ Safari

### Page Announcement
- [ ] Page title announced
- [ ] Main landmark announced (e.g., "Main")
- [ ] Navigation landmark announced for breadcrumb
- [ ] Page structure logical when navigated

### Navigation Testing
- [ ] Can navigate by headings (H key in NVDA)
- [ ] Can navigate by form controls (F key)
- [ ] Can navigate by buttons (B key)
- [ ] Can navigate by landmarks (D key)

### Content Announcement
- [ ] Organization name announced
- [ ] Page description announced
- [ ] Commission member notice (if visible) announced
- [ ] Section headers announced properly
- [ ] Form labels announced with inputs

### Table Announcement
- [ ] Table caption announced: "Voter list for [Org Name]"
- [ ] Column headers announced as headers
- [ ] Row data associated with headers
- [ ] Each voter row readable (Header + Data pairs)
- [ ] Actions column readable

### Button Announcement
- [ ] Approve button: "Approve voter John Doe" (or similar)
- [ ] Suspend button: "Suspend voter Jane Smith"
- [ ] Bulk operations buttons announced correctly
- [ ] Disabled buttons announced as disabled

### Dynamic Content
- [ ] Status updates announced (e.g., "Voter approved")
- [ ] Pagination changes announced
- [ ] Filter results changes announced
- [ ] Error messages announced as alerts

---

## 8️⃣ ARIA & SEMANTICS

### ARIA Labels
- [ ] Icon-only buttons have `aria-label`
- [ ] Table has `<caption>` or `aria-label="Voter list"`
- [ ] Dynamic regions have `aria-live="polite"`
- [ ] Status messages have `role="status"`
- [ ] Buttons describe their action clearly

### ARIA Attributes
- [ ] `aria-label` used correctly (not redundant)
- [ ] `aria-hidden="true"` on decorative SVG icons
- [ ] No ARIA attributes on plain text
- [ ] No conflicting ARIA information

### Table ARIA
- [ ] All `<th>` cells have `scope="col"` attribute
- [ ] Table has logical header structure
- [ ] No missing headers for data cells

---

## 9️⃣ MOTION & ANIMATION

### Animations
- [ ] No auto-playing videos/animations
- [ ] No content flashes more than 3 times per second
- [ ] Animations serve a purpose (not just decoration)
- [ ] Animation can be paused/stopped

### Reduced Motion Support
- [ ] CSS includes `@media (prefers-reduced-motion: reduce)`
- [ ] Animations disabled when reduced motion enabled
- [ ] Transitions work without motion
- [ ] Tested with OS reduced motion setting enabled:
  - [ ] Windows: Settings > Ease of Access > Display
  - [ ] macOS: System Preferences > Accessibility > Display
  - [ ] DevTools emulation: ☐ Yes ☐ No

---

## 🔟 IMAGES & ICONS

### SVG Icons
- [ ] Decorative SVGs have `aria-hidden="true"`
- [ ] SVG icons used alongside text labels
- [ ] No information conveyed by icon alone
- [ ] Icons clear and understandable

### Status Badges
- [ ] Color + text used for status indication
- [ ] Icon (if any) decorative (`aria-hidden="true"`)
- [ ] Text alternative present ("Approved", "Pending", etc.)

---

## 1️⃣1️⃣ LANGUAGE & LOCALIZATION

### Language Declaration
- [ ] HTML has `lang="en"` attribute
- [ ] Language correct for content language
- [ ] Tested languages: ☐ English ☐ German ☐ Nepali

### Text & Readability
- [ ] Text is clear and simple
- [ ] Abbreviations explained on first use
- [ ] No unnecessarily complex language
- [ ] Technical terms defined if needed

### Character Encoding
- [ ] Special characters display correctly
- [ ] German umlauts (ä, ö, ü) display correctly
- [ ] Nepali characters display correctly
- [ ] No "mojibake" or character corruption

---

## 1️⃣2️⃣ RESIZE & ZOOM

### Text Resizing
- [ ] Text readable when resized to 200%
- [ ] Browser zoom to 200% works: ☐ Yes ☐ No
- [ ] No horizontal scrolling at 200% zoom (for text)
- [ ] Layout reflows properly at larger text sizes

### Page Scaling
- [ ] Layout works at 125% zoom
- [ ] Layout works at 150% zoom
- [ ] Layout works at 200% zoom
- [ ] Functionality not lost at any zoom level

---

## 1️⃣3️⃣ FORMS & VALIDATION

### Form Structure
- [ ] Form fields grouped logically
- [ ] Related fields in `<fieldset>` (if applicable)
- [ ] Required fields marked clearly
- [ ] Instructions provided for complex fields

### Error Prevention & Handling
- [ ] Error messages visible and clear
- [ ] Error messages announce to screen readers
- [ ] Error messages identify problem field
- [ ] Error recovery is easy
- [ ] Test: Bulk approve with empty selection
  - [ ] Error displayed: ☐ Yes ☐ No
  - [ ] Error announced: ☐ Yes ☐ No

---

## 1️⃣4️⃣ DATA TABLE

### Table Structure
- [ ] Table has meaningful `<caption>` or `aria-label`
- [ ] Column headers (`<th`) have `scope="col"`
- [ ] Row headers (if any) have `scope="row"`
- [ ] No merged cells (if possible)
- [ ] Simple, logical table structure

### Table Navigation
- [ ] Headers announced when navigating with screen reader
- [ ] Each data cell associated with header
- [ ] Row and column relationship clear
- [ ] Table readable and usable

### Table Features
- [ ] Sort functionality works with keyboard
- [ ] Pagination controls accessible
- [ ] Checkboxes work for selection
- [ ] Actions (approve/suspend) accessible

---

## 1️⃣5️⃣ PAGINATION

### Navigation
- [ ] Previous button accessible
- [ ] Next button accessible
- [ ] Page numbers accessible (if shown)
- [ ] First/Last page buttons accessible
- [ ] Disabled buttons marked as disabled

### Announcement
- [ ] Current page indicated
- [ ] Total pages shown
- [ ] Navigation options clear

---

## 1️⃣6️⃣ OVERALL ASSESSMENT

### General Usability
- [ ] Page is intuitive and easy to use
- [ ] Navigation is logical
- [ ] Purpose of page is clear
- [ ] Users can accomplish main tasks

### Accessibility Impression
- [ ] Would be usable by:
  - [ ] Keyboard-only users
  - [ ] Screen reader users
  - [ ] Motor disability users
  - [ ] Visual impairment users
  - [ ] Cognitive disability users
  - [ ] Deaf/hard of hearing users

### Issues Found
- [ ] No issues found
- [ ] Issues found (list below):

**Issues & Recommendations:**
```
1. Issue: ___________________________________
   Severity: ☐ Critical ☐ Major ☐ Minor
   Fix: ____________________________________

2. Issue: ___________________________________
   Severity: ☐ Critical ☐ Major ☐ Minor
   Fix: ____________________________________

3. Issue: ___________________________________
   Severity: ☐ Critical ☐ Major ☐ Minor
   Fix: ____________________________________
```

---

## FINAL VERIFICATION

### WCAG 2.1 AA Level Achieved?
- [ ] **YES** - All criteria met
- [ ] **NO** - Issues found (see above)

### Areas Needing Attention
- [ ] None - Full compliance
- [ ] Follow-ups needed:
  1. ___________________________________
  2. ___________________________________
  3. ___________________________________

### Recommended Next Steps
- [ ] Deploy with current accessibility level
- [ ] Fix issues and retest
- [ ] Schedule accessibility audit with specialist
- [ ] Implement continuous accessibility testing

---

## SIGN-OFF

**Tester Name**: _________________________

**Date**: _________________________

**Signature**: _________________________

**Approved by**: _________________________

---

## Notes

```
Additional comments, observations, or recommendations:

_________________________________________________________________

_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

**Document Version**: 1.0
**Last Updated**: February 23, 2026
**Next Review**: February 23, 2027
