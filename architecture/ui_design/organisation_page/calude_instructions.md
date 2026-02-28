## 📋 **CLAUDE CLI PROMPT INSTRUCTIONS - organisation Landing Page**


```
## CONTEXT: organisation Landing Page After Registration

We have a multi-tenant voting platform where organisations register and need a comprehensive landing page after successful registration. The page should serve as the central hub for association management with focus on German Vereinsrecht compliance.

## CURRENT STATE
- organisation registration is complete
- User lands at `/organisations/{slug}` after registration
- Current page has basic stats (members, elections) and a support section
- Need to transform this into a full-featured organisation dashboard

## REQUIREMENTS

### Core Functionality
Create a professional organisation landing page with:

1. **organisation Header**
   - organisation name and metadata
   - Status badges (Active, Verified)
   - Member since date
   - Quick actions menu

2. **Your 3 Primary Action Buttons** (CRITICAL)
   - 📤 **Mitgliederliste importieren** - CSV/Excel upload with template download
   - 👤 **Wahlleiter bestellen** - With legal context (gemäß §26 BGB)
   - 🗳️ **Wahl bestellen** - Multiple election types (Vorstand, Beisitzer, Kassenprüfer)

3. **Member Management Section**
   - Member count statistics (total, active, new, exited)
   - Member import/export functionality
   - Region distribution chart (for regional voting)
   - Recent member activity feed

4. **Election Management Section**
   - Active elections with progress
   - Upcoming elections
   - Past elections with results
   - Election templates

5. **Legal Compliance Dashboard** (German Vereinsrecht)
   - Election officer status
   - Deputy election officer status
   - Election rules version
   - Compliance checklist (Wahlausschreibung, Wählerverzeichnis, etc.)

6. **Document Templates**
   - Wahlordnung Muster (PDF)
   - Mitgliederliste Vorlage (Excel)
   - Wahlprotokoll Vorlage (Word)
   - Satzung Muster (PDF)

7. **Support Section**
   - Email contact
   - Phone contact
   - Handbuch link
   - Webinar booking

## TECHNICAL REQUIREMENTS

### Frontend Stack
- Vue 3 + Inertia.js
- Tailwind CSS for styling
- Vue-i18n for translations (DE/EN/NP)
- Inertia Link components for navigation
- Form handling with Inertia useForm

### Component Structure

```
resources/js/Pages/organisation/Show.vue (Main page)
resources/js/Components/organisation/
├── OrganizationHeader.vue
├── MemberManagement/
│   ├── MemberImportModal.vue
│   ├── MemberStats.vue
│   ├── MemberDistributionChart.vue
│   └── MemberActivityFeed.vue
├── ElectionManagement/
│   ├── ElectionTypeTabs.vue
│   ├── ElectionCreationModal.vue
│   ├── ActiveElections.vue
│   └── PastElections.vue
├── LegalCompliance/
│   ├── ElectionOfficerCard.vue
│   ├── ComplianceChecklist.vue
│   └── LegalStatusBadge.vue
├── Documents/
│   └── DocumentTemplateList.vue
└── Support/
    └── SupportSection.vue
```

### Data Requirements (Props from Controller)

```php
return Inertia::render('organisation/Show', [
    'organisation' => [
        'id' => $organisation->id,
        'name' => $organisation->name,
        'email' => $organisation->email,
        'slug' => $organisation->slug,
        'created_at' => $organisation->created_at->format('Y-m-d H:i:s'),
        'verified_at' => $organisation->verified_at,
        'member_count' => $organisation->members()->count(),
        'active_member_count' => $organisation->members()->where('status', 'active')->count(),
        'new_members_30d' => $organisation->members()->where('created_at', '>=', now()->subDays(30))->count(),
        'exited_members_30d' => $organisation->members()->where('status', 'exited')->where('updated_at', '>=', now()->subDays(30))->count(),
    ],
    'electionStats' => [
        'total_elections' => $organisation->elections()->count(),
        'active_elections' => $organisation->elections()->where('status', 'active')->count(),
        'upcoming_elections' => $organisation->elections()->where('status', 'upcoming')->count(),
        'completed_elections' => $organisation->elections()->where('status', 'completed')->count(),
        'recent_elections' => $organisation->elections()->orderBy('created_at', 'desc')->limit(5)->get(),
    ],
    'legalStatus' => [
        'has_election_officer' => $organisation->hasElectionOfficer(),
        'election_officer' => $organisation->getElectionOfficer(),
        'officer_expires_at' => $organisation->election_officer_expires_at,
        'has_deputy_officer' => $organisation->hasDeputyElectionOfficer(),
        'deputy_officer' => $organisation->getDeputyElectionOfficer(),
        'election_rules_version' => $organisation->election_rules_version,
        'compliance_checks' => [
            'wahlausschreibung' => $organisation->hasWahlausschreibung(),
            'waehlerverzeichnis' => $organisation->hasWaehlerverzeichnis(),
            'briefwahl_versendet' => $organisation->hasBriefwahlVersendet(),
            'wahlhelfer_bestellt' => $organisation->hasWahlhelferBestellt(),
        ],
    ],
    'regionDistribution' => [
        ['region' => 'Berlin', 'count' => 87],
        ['region' => 'Bayern', 'count' => 69],
        ['region' => 'Baden-Württemberg', 'count' => 54],
        ['region' => 'Nordrhein-Westfalen', 'count' => 37],
    ],
    'recentActivity' => [
        ['action' => 'member_joined', 'user' => 'Hans Müller', 'timestamp' => now()->subHours(2)],
        ['action' => 'member_left', 'user' => 'Anna Schmidt', 'timestamp' => now()->subDays(1)],
        ['action' => 'election_created', 'election' => 'Vorstandswahl 2026', 'timestamp' => now()->subDays(2)],
    ],
    'documents' => [
        ['name' => 'Wahlordnung Muster', 'type' => 'pdf', 'size' => 245, 'url' => '/downloads/wahlordnung.pdf'],
        ['name' => 'Mitgliederliste Vorlage', 'type' => 'xlsx', 'size' => 128, 'url' => '/downloads/mitgliederliste.xlsx'],
        ['name' => 'Wahlprotokoll Vorlage', 'type' => 'docx', 'size' => 312, 'url' => '/downloads/wahlprotokoll.docx'],
        ['name' => 'Satzung Muster', 'type' => 'pdf', 'size' => 567, 'url' => '/downloads/satzung.pdf'],
    ],
    'canManage' => true, // Permission check
]);
```

## IMPLEMENTATION STEPS

### Step 1: Create the Main Page Component
Create `resources/js/Pages/organisation/Show.vue` with the full layout structure including all sections in the correct order.

### Step 2: Create Sub-Components
Create each of the sub-components in their respective directories with proper props and events.

### Step 3: Member Import Modal
Implement file upload functionality with:
- Drag & drop zone
- CSV/Excel validation
- Preview before import
- Progress indicator
- Success/error handling

### Step 4: Election Officer Appointment Flow
Create modal/form for:
- Selecting member as election officer
- Setting appointment date
- Setting expiry date (1 year default)
- Legal disclaimer acceptance
- Confirmation email trigger

### Step 5: Election Creation Wizard
Create multi-step form for:
- Step 1: Election type (Vorstand, Beisitzer, Kassenprüfer, Satzungsänderung)
- Step 2: Positions to elect (dynamic based on type)
- Step 3: Voter selection (all members, active only, manual)
- Step 4: Voting method (online only, hybrid, mail-in)
- Step 5: Timeline configuration
- Step 6: Confirmation

### Step 6: Translation Files
Update language files for all new UI elements in:
- `resources/js/locales/de.json`
- `resources/js/locales/en.json`
- `resources/js/locales/np.json`

### Step 7: Update Controller
Update the controller to provide all required props.

## DESIGN SPECIFICATIONS

### Color Scheme
- Primary Blue: `#2563eb` (buttons, links)
- Success Green: `#16a34a` (completed items)
- Warning Amber: `#d97706` (pending items)
- Error Red: `#dc2626` (errors, missing items)
- Gray scale: `#f9fafb` (background), `#f3f4f6` (cards), `#1f2937` (text)

### Typography
- Headings: `text-2xl` to `text-4xl`, font-bold
- Body: `text-base` text-gray-700
- Small text: `text-sm` text-gray-500

### Spacing
- Container: `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`
- Section spacing: `mb-8` to `mb-12`
- Card padding: `p-6`

### Card Design
- Background: white
- Border: `border border-gray-200`
- Border radius: `rounded-xl`
- Shadow: `shadow-xs` with `hover:shadow-lg` transition

### Button Styles
Primary: `bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg`
Secondary: `border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg`
Tertiary: `text-blue-600 hover:text-blue-700 text-sm font-medium`

## ACCESSIBILITY REQUIREMENTS
- Proper heading hierarchy (h1, h2, h3)
- ARIA labels for icon buttons
- Focus indicators on all interactive elements
- Color contrast meets WCAG AA standards
- Screen reader announcements for dynamic content

## MOBILE RESPONSIVENESS
- Stack layout on mobile (1 column)
- Touch-friendly buttons (min 44x44px)
- Collapsible sections for complex content
- Responsive grids (1 → 2 → 3 → 4 columns)

## DELIVERABLES

1. ✅ Complete `Show.vue` page component
2. ✅ All 10+ sub-components
3. ✅ Updated translation files
4. ✅ Member import modal with file upload
5. ✅ Election officer appointment flow
6. ✅ Election creation wizard
7. ✅ Updated controller with all props
8. ✅ Documentation of the page structure

## SUCCESS CRITERIA
- [ ] Page loads in < 2 seconds
- [ ] All 3 primary buttons functional
- [ ] Member import works with CSV validation
- [ ] Election officer can be appointed
- [ ] Election can be created through wizard
- [ ] Legal compliance dashboard shows correct status
- [ ] Mobile layout is usable
- [ ] All translations display correctly
- [ ] No accessibility violations
- [ ] All links and buttons work

## TIMELINE
- Phase 1: Main page structure + 3 primary buttons: 2 hours
- Phase 2: Member management: 1.5 hours
- Phase 3: Election management: 2 hours
- Phase 4: Legal compliance: 1 hour
- Phase 5: Polish + testing: 1.5 hours
- Total: ~8 hours

## IMPLEMENTATION NOTES

### File Upload Handling
```javascript
// Use Inertia's file upload
const form = useForm({
    file: null,
});

const submit = () => {
    form.post(route('organisations.members.import', props.organisation.slug), {
        onSuccess: () => {
            // Show success message
            // Refresh member stats
        },
    });
};
```

### Modal Management
```javascript
const showMemberImportModal = ref(false);
const showElectionOfficerModal = ref(false);
const showElectionWizard = ref(false);

// Use teleport for modals
<Teleport to="body">
    <MemberImportModal 
        v-if="showMemberImportModal"
        :organisation="organisation"
        @close="showMemberImportModal = false"
        @imported="handleImported"
    />
</Teleport>
```

### Date Formatting with i18n
```javascript
import { format, formatDistanceToNow } from 'date-fns';
import { de, enUS, np } from 'date-fns/locale';

const localeMap = { de, en: enUS, np };
const formatDate = (date) => {
    return format(new Date(date), 'PPP', { 
        locale: localeMap[locale.value] 
    });
};
```

## START IMPLEMENTATION

Begin by creating the main `Show.vue` page component with the full structure, then implement each sub-component following the specifications above. Use Tailwind CSS classes exactly as specified for consistency.

The page should feel professional, trustworthy, and guide the organisation admin through their next steps while maintaining full legal compliance for German associations.
```
write your plan in the same folder of this file and work in plan mode 
