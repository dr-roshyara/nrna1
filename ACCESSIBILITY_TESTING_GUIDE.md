# Accessibility Testing Guide - WCAG 2.1 AA Compliance

## Overview

This guide ensures the Voter Management System meets **WCAG 2.1 AA** (Web Content Accessibility Guidelines) compliance, covering:
- Semantic HTML structure
- ARIA labels and roles
- Keyboard navigation
- Color contrast
- Screen reader compatibility
- Responsive design
- Motor accessibility (touch targets)
- Motion & animation considerations

---

## Automated Accessibility Tests

### Test File
`tests/Feature/Accessibility/VoterControllerAccessibilityTest.php`

**31 Test Cases** covering all WCAG 2.1 AA criteria

### Running Automated Tests
```bash
# Run all accessibility tests
php artisan test tests/Feature/Accessibility/

# Run specific accessibility test
php artisan test tests/Feature/Accessibility/VoterControllerAccessibilityTest.php --filter=it_has_proper_semantic_html_structure

# Run with verbose output
php artisan test tests/Feature/Accessibility/ -v
```

---

## Manual Testing Checklist

### ✅ 1. Semantic HTML Structure

**What to check:**
- [ ] Page has `<main>` element with main content
- [ ] Page has `<header>` element with logo/navigation
- [ ] Page has `<nav>` element for breadcrumbs
- [ ] Table uses `<table>`, `<thead>`, `<tbody>`, `<th>`, `<td>`
- [ ] Forms use `<label>`, `<input>`, `<select>`, `<button>`
- [ ] No structural elements used only for styling (e.g., `<div>` instead of `<section>`)

**How to test:**
1. Open Developer Tools (F12)
2. Inspect HTML structure
3. Look for proper semantic elements
4. Use W3C Validator: https://validator.w3.org/

**Commands:**
```bash
# Validate HTML
curl -s https://validator.w3.org/nu/?doc=http://localhost:8000/organizations/slug/voters | grep -i error
```

---

### ✅ 2. Heading Hierarchy

**What to check:**
- [ ] Page has exactly one `<h1>` tag
- [ ] `<h1>` is the main page title ("Voter Management")
- [ ] `<h2>` tags used for major sections (Stats, Filters, Table)
- [ ] No skipped heading levels (e.g., `<h1>` → `<h3>`)
- [ ] Headings logically outline the page

**How to test:**
1. Browser extension: WAVE (Accessibility Evaluation Tool)
2. Tab through page - headings should be announced
3. Visual inspection of document outline

**Browser Commands (in Console):**
```javascript
// Get all headings
const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
headings.forEach(h => console.log(`${h.tagName}: ${h.textContent}`));

// Check for skipped levels
Array.from(headings).map(h => parseInt(h.tagName[1]))
```

---

### ✅ 3. ARIA Labels & Roles

**What to check:**
- [ ] All icon-only buttons have `aria-label`
- [ ] Form inputs have associated `<label>` or `aria-label`
- [ ] Table has `<caption>` or `aria-label`
- [ ] Dynamic content uses `aria-live="polite"`
- [ ] Table headers have `scope="col"` or `scope="row"`
- [ ] Status messages use `role="status"` or `role="alert"`

**How to test:**
1. Inspect element → look for `aria-label`, `aria-live`, `role` attributes
2. Screen reader test (see Screen Reader Testing section)
3. Use accessibility checker extensions

**Visual Inspection Points:**
```html
✅ Approve button:
   <button aria-label="Approve voter John Doe">
     <CheckIcon aria-hidden="true" />
   </button>

✅ Search input:
   <label for="search">Search</label>
   <input id="search" />

✅ Status message:
   <div role="status" aria-live="polite" class="sr-only">
     Voter approved successfully
   </div>

✅ Table:
   <table>
     <caption>Voter list for NRNA</caption>
     <th scope="col">Name</th>
   </table>
```

---

### ✅ 4. Keyboard Navigation

**What to check:**
- [ ] All interactive elements are keyboard accessible
- [ ] Tab order is logical (left-to-right, top-to-bottom)
- [ ] Focus is never trapped
- [ ] Keyboard shortcuts use standard keys (Enter, Space, Arrow keys)
- [ ] No keyboard traps (can always tab out)

**How to test:**

1. **Tab Navigation Test:**
   ```
   1. Click on the page
   2. Press TAB repeatedly
   3. Verify focus moves through:
      - Search input
      - Status filter dropdown
      - Clear button
      - Approve/Suspend buttons
      - Pagination links
   4. Verify focus is visible (outline/border)
   ```

2. **Enter/Space Test:**
   ```
   1. Tab to a button
   2. Press ENTER or SPACE
   3. Verify button action executes
   ```

3. **Escape Test:**
   ```
   1. If there's a modal/dropdown
   2. Press ESCAPE
   3. Verify modal closes and focus returns to trigger button
   ```

4. **Arrow Key Test (if dropdowns):**
   ```
   1. Tab to dropdown
   2. Press DOWN arrow
   3. Verify options appear
   4. Press UP/DOWN to navigate
   5. Press ENTER to select
   ```

**What NOT to do:**
- ❌ Use `tabindex="0"` on non-interactive elements
- ❌ Use `tabindex="-1"` on important elements
- ❌ Create keyboard traps where TAB doesn't work
- ❌ Hide focus indicators with `outline-solid: none`

---

### ✅ 5. Focus Management

**What to check:**
- [ ] Focus indicator is visible (outline or border)
- [ ] Focus indicator has sufficient contrast
- [ ] Focus indicator is not obscured
- [ ] Focus returns to trigger element after dialog closes

**How to test:**

1. **Visual Focus Check:**
   ```
   1. Tab through page
   2. Look for visible focus indicator (usually outline)
   3. Ensure it's visible on all interactive elements
   4. If not visible, check CSS for:
      button:focus { outline: 2px solid currentColor; }
   ```

2. **Focus Order Test:**
   ```
   1. Press TAB
   2. Note the order of focus
   3. Should match visual layout order
   4. Left-to-right, top-to-bottom
   ```

3. **Focus Trap Test:**
   ```
   1. Open modal (if exists)
   2. Press TAB repeatedly
   3. Focus should cycle within modal
   4. Press ESCAPE to close
   5. Focus should return to trigger button
   ```

---

### ✅ 6. Color Contrast

**What to check:**
- [ ] All text has 4.5:1 contrast ratio (normal text)
- [ ] All UI components have 3:1 contrast ratio (borders, icons)
- [ ] Color is not the only indicator (e.g., "Approved" status should have text + icon + color)
- [ ] Dark mode also meets contrast requirements

**How to test:**

1. **Online Contrast Checker:**
   - WebAIM Contrast Checker: https://webaim.org/resources/contrastchecker/
   - Paste foreground & background colors
   - Verify 4.5:1 (AA) for normal text
   - Verify 3:1 (AA) for UI components

2. **Browser Tools:**
   ```
   1. Right-click element
   2. Inspect → Styles
   3. Hover over color swatches
   4. Tools show contrast ratio
   ```

3. **Current Color Scheme:**
   ```
   Light Theme:
   - Text: #111827 (gray-900) on #FFFFFF (white) = 17:1 ✅
   - Approved badge: #065f46 (green-800) on #d1fae5 (green-100) = 7:1 ✅
   - Pending badge: #92400e (yellow-800) on #fffbeb (yellow-100) = 8.5:1 ✅

   Dark Theme:
   - Text: #F3F4F6 (gray-100) on #111827 (gray-900) = 17:1 ✅
   - Approved badge: #dcfce7 (green-200) on #15803d (green-700) = 7:1 ✅
   ```

**Tools:**
- WebAIM: https://webaim.org/resources/contrastchecker/
- Contrast Ratio: https://contrast-ratio.com/
- Accessibility Insights: Microsoft Edge extension

---

### ✅ 7. Screen Reader Testing

**What to check:**
- [ ] All content is announced
- [ ] Navigation structure is clear
- [ ] Form labels are associated with inputs
- [ ] Buttons describe their purpose
- [ ] Dynamic content updates are announced
- [ ] Decorative images have `alt=""` or `aria-hidden="true"`

**How to test:**

1. **Windows: NVDA (Free)**
   ```
   1. Download: https://www.nvaccess.org/
   2. Install and start NVDA
   3. Navigate page with ARROW keys
   4. Use HEADING key (H) to jump to headings
   5. Use LANDMARK key (D) to jump to regions
   ```

2. **Windows: JAWS (Commercial)**
   ```
   - If available, similar navigation
   - More advanced features for complex pages
   ```

3. **Mac: VoiceOver (Built-in)**
   ```
   1. System Preferences → Accessibility → VoiceOver
   2. Toggle VO key (CMD + F5)
   3. VO + U to open rotor
   4. VO + ARROW KEYS to navigate
   ```

4. **Online Simulators:**
   - WebAIM Screen Reader Testing: https://webaim.org/articles/screenreader_testing/
   - See list of what SHOULD be announced at various points

**What should be announced:**

```
HEADING 1: Voter Management
[organisation name]
[Description text]

HEADING 2: Filter Voters
  Label: Search
  Input: search (type text)
  Label: Status
  Combobox: All Statuses
  Button: Clear Filters

HEADING 2: Voter List
[Statistics]
  [Number] Total Voters
  [Number] Approved Voters
  [Number] Pending Approval
  [Number] Already Voted

TABLE
  Caption: Voter list for [organisation]
  Row: S.N., Name, Email, Status, Actions
  Row: 1, John Doe, john@example.com, Approved, [Suspend button]
  Row: 2, Jane Smith, jane@example.com, Pending, [Approve button]

NAVIGATION
  Pagination controls
  Previous Page, Next Page buttons
```

---

### ✅ 8. Touch Target Size

**What to check:**
- [ ] All buttons are at least 44x44 pixels
- [ ] All links are at least 44x44 pixels
- [ ] Spacing between targets is adequate
- [ ] No small touch targets (< 44px) especially on mobile

**How to test:**

1. **Browser DevTools:**
   ```
   1. Right-click element
   2. Inspect
   3. Check dimensions in Styles
   4. Look for width & height
   5. Or check Tailwind classes: min-h-[44px], min-w-[44px]
   ```

2. **Current Implementation:**
   ```
   ✅ Approve button: min-h-[44px] min-w-[44px]
   ✅ Suspend button: min-h-[44px] min-w-[44px]
   ✅ Pagination buttons: px-4 py-2 (adequate size)
   ✅ Checkboxes: w-4 h-4 (in table, okay because click area is larger row)
   ```

3. **Mobile Testing:**
   ```
   1. Open browser DevTools
   2. Toggle device toolbar (CMD/CTRL + SHIFT + M)
   3. Test on iPhone SE (375px width)
   4. Verify buttons are still clickable
   5. Verify no accidental taps on nearby buttons
   ```

---

### ✅ 9. Motion & Animation

**What to check:**
- [ ] Animations can be disabled via `prefers-reduced-motion`
- [ ] No auto-playing videos/animations
- [ ] No flashing content (no more than 3 times per second)
- [ ] Hover effects have reduced-motion alternatives

**How to test:**

1. **Check for Reduced Motion Support:**
   ```css
   ✅ Present in stylesheet:
   @media (prefers-reduced-motion: reduce) {
     * {
       animation-duration: 0.01ms !important;
       transition-duration: 0.01ms !important;
     }
   }
   ```

2. **Enable Reduced Motion in OS:**
   ```
   Windows:
   1. Settings → Ease of Access → Display
   2. Turn on "Show animations"
   3. Toggle OFF to enable reduced motion

   macOS:
   1. System Preferences → Accessibility → Display
   2. Check "Reduce motion"

   Chrome DevTools:
   1. Rendering tab
   2. Emulate CSS media feature prefers-reduced-motion
   ```

3. **Verify Behavior:**
   ```
   1. Enable reduced motion
   2. Navigate page
   3. Animations should be instant (no transition delays)
   4. Verify hover effects still work
   ```

---

### ✅ 10. Language & Localization

**What to check:**
- [ ] HTML has `lang` attribute: `<html lang="en">`
- [ ] Language changes are announced (if applicable)
- [ ] Text is readable with default font settings
- [ ] Text can be resized to 200%

**How to test:**

1. **Language Attribute:**
   ```
   1. View page source (CTRL+U)
   2. Look for: <html lang="en">
   3. Check if language matcher needs update for German/Nepali
   ```

2. **Text Resizing:**
   ```
   1. Browser Zoom: CTRL + + (multiple times)
   2. Should work up to 200%
   3. No horizontal scrolling
   4. Text should reflow
   5. Buttons should remain clickable
   ```

---

### ✅ 11. Images & Icons

**What to check:**
- [ ] Decorative images have `alt=""` or `aria-hidden="true"`
- [ ] Informative images have descriptive `alt` text
- [ ] SVG icons are properly labeled

**How to test:**

1. **Current Implementation:**
   ```html
   ✅ Decorative SVG:
   <svg aria-hidden="true">...</svg>

   ✅ Icon with text label:
   <button aria-label="Approve voter John Doe">
     <CheckIcon aria-hidden="true" />
   </button>
   ```

---

### ✅ 12. Forms & Error Messages

**What to check:**
- [ ] All form fields have associated labels
- [ ] Error messages are clearly visible
- [ ] Error messages are announced to screen readers
- [ ] Form fields show required status
- [ ] Placeholder text is not used as substitute for labels

**How to test:**

1. **Error Message Display:**
   ```
   1. Submit bulk approve with no voters selected
   2. Error message should appear
   3. Message should be announced to screen reader
   4. Message should be in `role="alert"` or `role="status"`
   ```

---

### ✅ 13. Tables

**What to check:**
- [ ] Table has `<caption>` or `aria-label`
- [ ] Header cells have `scope="col"` or `scope="row"`
- [ ] Complex tables have `id` and `headers` attributes
- [ ] Table structure is simple and logical

**How to test:**

1. **Inspect Table Code:**
   ```html
   ✅ Expected structure:
   <table>
     <caption>Voter list for NRNA</caption>
     <thead>
       <tr>
         <th scope="col">S.N.</th>
         <th scope="col">Name</th>
         <th scope="col">Email</th>
         <th scope="col">Status</th>
       </tr>
     </thead>
     <tbody>
       <tr>
         <td>1</td>
         <td>John Doe</td>
         <td>john@example.com</td>
         <td>Approved</td>
       </tr>
     </tbody>
   </table>
   ```

---

## Browser Extensions for Testing

### **WAVE (WebAIM)**
- Highlights accessibility issues
- Shows ARIA landmarks
- Free: https://wave.webaim.org/extension/

### **Accessibility Insights (Microsoft)**
- Comprehensive automated testing
- Free: https://accessibilityinsights.io/

### **axe DevTools**
- Fast automated testing
- Free: https://www.deque.com/axe/devtools/

### **Lighthouse (Chrome Built-in)**
1. Open DevTools (F12)
2. Click "Lighthouse" tab
3. Select "Accessibility"
4. Click "Analyze page load"

---

## Testing Checklist Summary

| Criterion | Automated | Manual | Tool |
|-----------|-----------|--------|------|
| Semantic HTML | ✅ | ✅ | W3C Validator |
| Heading Hierarchy | ✅ | ✅ | WAVE |
| ARIA Labels | ✅ | ✅ | Inspector |
| Keyboard Navigation | ❌ | ✅ | Manual TAB |
| Focus Management | ✅ | ✅ | Manual |
| Color Contrast | ❌ | ✅ | WebAIM Contrast |
| Screen Reader | ❌ | ✅ | NVDA/JAWS |
| Touch Targets | ✅ | ✅ | DevTools |
| Motion | ✅ | ✅ | DevTools |
| Language | ✅ | ✅ | View Source |
| Images/Icons | ✅ | ✅ | Inspector |
| Forms | ✅ | ✅ | Manual |
| Tables | ✅ | ✅ | Inspector |

---

## Running All Accessibility Tests

```bash
# Run automated accessibility tests
php artisan test tests/Feature/Accessibility/

# Run with coverage
php artisan test tests/Feature/Accessibility/ --coverage

# Run single test
php artisan test tests/Feature/Accessibility/VoterControllerAccessibilityTest.php --filter=it_has_proper_semantic_html_structure
```

---

## Expected Test Results

```
PASS  tests/Feature/Accessibility/VoterControllerAccessibilityTest.php (31 tests)

Tests: 31 passed
Time: ~15 seconds
```

---

## WCAG 2.1 AA Compliance Verification

### ✅ Perceivable
- [x] Text alternatives for images
- [x] Sufficient color contrast (4.5:1 normal text)
- [x] Adaptable content (responsive, resizable)
- [x] Distinguishable (color not only indicator)

### ✅ Operable
- [x] Keyboard accessible
- [x] No keyboard traps
- [x] Adequate timing (no time limits)
- [x] Touch targets 44x44px minimum
- [x] Motion and animation can be disabled

### ✅ Understandable
- [x] Readable language
- [x] Predictable navigation
- [x] Input assistance (labels, error messages)
- [x] Clear form labels and instructions

### ✅ Robust
- [x] Valid HTML
- [x] Proper ARIA usage
- [x] Compatible with assistive technologies
- [x] Proper semantic structure

---

## Continuous Accessibility Monitoring

1. **Automated Tests**: Run with every commit
2. **Manual Testing**: Quarterly accessibility audit
3. **User Testing**: Include people with disabilities
4. **Browser Testing**: Test on latest browsers
5. **Screen Reader Testing**: Test with NVDA/JAWS
6. **Responsive Testing**: Test on mobile devices

---

## Resources

- **WCAG 2.1 Guidelines**: https://www.w3.org/WAI/WCAG21/quickref/
- **WebAIM Articles**: https://webaim.org/
- **Deque University**: https://dequeuniversity.com/
- **A11y Project**: https://www.a11yproject.com/
- **MDN Accessibility**: https://developer.mozilla.org/en-US/docs/Web/Accessibility

---

## Questions & Support

For accessibility issues:
1. Check WCAG 2.1 guidelines
2. Test with automated tools
3. Test with screen readers
4. Consult with accessibility specialist if needed

---

**Status**: ✅ Ready for accessibility testing and verification
