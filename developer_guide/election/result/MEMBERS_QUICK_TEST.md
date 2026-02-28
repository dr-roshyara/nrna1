# Members Index Page - Quick Test Guide

## Access the Page

### URL
```
http://localhost:8000/members/index
```

### Requirements
- ✅ Must be logged in
- ✅ User must belong to an organisation
- ✅ Session must have `current_organisation_id` set

## Manual Testing Checklist

### Page Load
- [ ] Page loads without errors
- [ ] organisation header displays correctly (shows org name)
- [ ] Stats cards show numbers (total members, admins, voters)
- [ ] Filter section visible with Name, Email, and Role inputs
- [ ] Table displays with proper headers
- [ ] Pagination controls show (if more than 20 members)

### Filters
- [ ] Type member name → results filter (300ms debounce)
- [ ] Type member email → results filter
- [ ] Select role from dropdown → results filter
- [ ] Clear all filters → shows all members

### Sorting
- [ ] Click "ID" header → sorts by ID (desc → asc → desc)
- [ ] Click "Name" header → sorts by name (desc → asc → desc)
- [ ] Click "Email" header → sorts by email (desc → asc → desc)
- [ ] Click "Role" header → sorts by role (desc → asc → desc)
- [ ] Click "Member Since" header → sorts by assigned_at
- [ ] Sort icon changes direction (up/down)

### Table Data
- [ ] All member names display
- [ ] All email addresses display
- [ ] Member regions show correctly
- [ ] Role badges display with correct colors:
  - Admin: Red badge
  - Commission: Blue badge
  - Voter: Green badge
- [ ] Member Since dates formatted correctly (e.g., "Feb 23, 2025")

### Pagination
- [ ] Top pagination shows "Page X of Y"
- [ ] Previous button disabled on first page
- [ ] Next button disabled on last page
- [ ] Previous button works (goes to prior page)
- [ ] Next button works (goes to next page)
- [ ] Bottom pagination mirrors top pagination

### Responsive Design (Mobile)
- [ ] On mobile (< 768px):
  - Filters stack vertically
  - Stats cards stack vertically
  - Table scrolls horizontally if needed
  - Pagination buttons still readable

### Browser DevTools
- [ ] Network tab shows GET /members/index
- [ ] Response status: 200
- [ ] Response contains organisation data
- [ ] Response contains members array with pagination
- [ ] Console has no JavaScript errors
- [ ] Console shows no Vue warnings

## Error Testing

### No organisation
**Setup**: User with no organisation_id and no session organisation
```
Expected: 403 error "No organisation selected"
```

### Not a Member
**Setup**: Try to access organisation user is not member of
```
Expected: 403 error "You do not have access to this organisation"
```

### Invalid organisation ID
**Setup**: Try with ?org_id=99999
```
Expected: 404 error (organisation not found)
```

## Database Verification

### Check organisation Members
```bash
php artisan tinker
```

```php
$org = organisation::first();
$members = $org->users()->get();
$members->each(fn($m) => echo $m->name . ' (' . $m->pivot->role . ')' . PHP_EOL);
```

### Check Role Distribution
```php
$org = organisation::first();
echo "Admins: " . $org->admins()->count() . PHP_EOL;
echo "Commission: " . $org->commissionMembers()->count() . PHP_EOL;
echo "Voters: " . $org->voters()->count() . PHP_EOL;
```

## API Response Structure

### Expected Controller Response
```php
Inertia::render('Members/Index', [
    'members' => $members,              // LengthAwarePaginator
    'organisation' => [...],            // org data
    'filters' => [...],                 // applied filters
    'currentUser' => $user,             // logged-in user
    'stats' => [...]                    // counts
])
```

### Members Paginator Properties
- `data`: Array of 20 members (max)
- `current_page`: Current page number
- `last_page`: Total pages
- `per_page`: 20
- `total`: Total members
- `prev_page_url`: Previous page link (or null)
- `next_page_url`: Next page link (or null)

## Performance Testing

### Page Load Time
```
Target: < 1000ms
Measure: DevTools Performance tab
```

### Filter Response
```
Target: < 300ms debounce
Measure: Type in filter field, watch network tab
```

### Sort Performance
```
Target: < 100ms
Measure: Click sort header, check network timing
```

## Code Review Points

### Security
- [ ] organisation membership verified on server
- [ ] No SQL injection in LIKE queries
- [ ] Session organisation_id validated
- [ ] 403 errors returned for unauthorized access

### Performance
- [ ] Only needed columns selected (no SELECT *)
- [ ] Pivot table used efficiently
- [ ] Pagination prevents loading too much data
- [ ] No N+1 queries in transformation

### UX
- [ ] All filters work together
- [ ] Sort indicators clear
- [ ] Date formatting consistent
- [ ] Role colors distinguishable
- [ ] Pagination obvious

## Troubleshooting

### Page shows blank
```
Solution: Ensure user is logged in and belongs to an org
Check: auth()->user()->organisation_id
```

### Members not showing
```
Solution: Verify user is member of organisation
Check: $org->users()->where('id', auth()->id())->exists()
```

### Filters not working
```
Solution: Check if debounce is too slow
Verify: Lodash is loaded (check devtools console)
Check: Network tab shows correct query params
```

### Sorting broken
```
Solution: Verify field names match allowed list
Check: 'field' in ['id','name','email','role','assigned_at','created_at']
```

### Stats incorrect
```
Solution: Verify organisation relationships are correct
Check: $org->users()->count() vs $org->admins()->count() + other roles
```

## Success Criteria

✅ All checklist items completed
✅ No console errors
✅ No network errors
✅ No database errors
✅ Page responsive on all screen sizes
✅ All filters working
✅ All sorts working
✅ Pagination working
✅ Security checks passed
✅ Performance acceptable

## Screenshots to Take

1. Full page load (desktop view)
2. Mobile view (responsive)
3. Filter in action
4. Sort in action
5. Pagination next/previous
6. Role badge colors
7. organisation header with stats
8. Error state (if accessible)

## Related Routes to Test

After Members index works, test:
- [ ] POST /organizations (create organisation)
- [ ] GET /organizations/{slug} (view organisation)
- [ ] POST /organizations/{slug}/members/import (import members)

## Notes

- This page integrates with existing `ElectionLayout` component
- Uses same Tailwind CSS system as rest of app
- Compatible with existing Vue 3 + Inertia.js setup
- No new dependencies required
- Follows Laravel naming conventions
- Follows Vue composition patterns used in app
