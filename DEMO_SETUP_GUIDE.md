# Demo Election Setup Commands

Two separate Artisan commands now manage different types of demo elections:

## 1. Public Demo Setup (Anonymous, No Persistence)

Command:
```bash
php artisan public-demo:setup
```

This creates a **public demo election** for the PublicDigit organisation:
- ✅ Anonymous voting (no user tracking)
- ✅ No vote persistence to database
- ✅ Session-based demo data only
- ✅ Public access without authentication
- ✅ Access via: `/public-demo/start`

**Features:**
- 3 national posts (President, Vice President, Secretary General)
- 13 candidates total
- No regional posts (public demo only)
- No user login required

**Options:**
```bash
php artisan public-demo:setup --force    # Reset existing demo
php artisan public-demo:setup --clean    # Delete without confirmation
```

---

## 2. Organisation Demo Setup (Authenticated, With Persistence)

Command:
```bash
php artisan demo:setup --org=<organisation-slug>
```

This creates an **organisation-specific demo election**:
- ✅ Authenticated users only
- ✅ Vote persistence to database
- ✅ Organisation-scoped data
- ✅ Access via: `/organisations/{slug}/demo/start`

**Features:**
- 2 national posts (President, General Secretary)
- 8 regional posts (2 per region: Europe, America, Asia, Africa)
- 30 candidates total
- Regional filtering based on voter location
- Vote persistence with organisation context

**Examples:**
```bash
# Setup for Namaste Nepal GmbH
php artisan demo:setup --org=namaste-nepal-gmbh

# Reset existing demo
php artisan demo:setup --org=namaste-nepal-gmbh --force

# Delete without confirmation
php artisan demo:setup --org=namaste-nepal-gmbh --clean

# Find organisation slug first
php artisan tinker --execute="echo App\Models\Organisation::all()->pluck('slug', 'name');"
```

---

## Current Setup Status

### Public Demo
- ✅ Created: `public-demo-election`
- ✅ Candidates: 13
- ✅ Access: http://localhost:8000/public-demo/start

### Namaste Nepal GmbH (Your Organisation)
- ✅ Created: `demo-election-namaste-nepal-gmbh`
- ✅ Candidates: 30
- ✅ Access: http://localhost:8000/organisations/namaste-nepal-gmbh/demo/start

---

## How They Work

### Public Demo Flow (Stateless)
1. User visits `/public-demo/start` (no login required)
2. Votes stored temporarily in **session only**
3. Results aggregated from session data
4. No database persistence
5. **Results visible to all users** (public voting statistics)

### Organisation Demo Flow (Persistent)
1. User logs in and clicks "Try Demo"
2. Routes to `/organisations/{slug}/demo/start`
3. Votes saved to **demo_votes** table with `organisation_id`
4. Results stored in **demo_results** table
5. **Results visible only to authenticated users** of that organisation
6. Voting data persists across sessions

---

## Key Differences

| Feature | Public Demo | Org Demo |
|---------|------------|---------|
| **Authentication** | None | Required (login) |
| **Vote Persistence** | Session only | Database (demo_votes) |
| **Results** | Public | Organisation-scoped |
| **Scope** | Platform-wide | Organisation-specific |
| **Regional Posts** | No | Yes |
| **Access URL** | `/public-demo/start` | `/organisations/{slug}/demo/start` |
| **Use Case** | Platform showcase | Training for users |

---

## Data Models Used

### Public Demo
- Uses `demo_votes`, `demo_candidacies`, `demo_posts` tables
- `organisation_id = PublicDigit` platform org
- No user association (anonymous)

### Organisation Demo
- Uses same `demo_votes`, `demo_candidacies`, `demo_posts` tables
- `organisation_id = specific organisation`
- User-scoped through voter_slugs
- Vote persistence guaranteed

---

## Resetting Demo Elections

To reset a demo election (delete all votes and recreate candidates):

```bash
# Public demo
php artisan public-demo:setup --force

# Organisation demo
php artisan demo:setup --org=namaste-nepal-gmbh --force
```

This will:
1. Delete the existing demo election and all related posts/candidates
2. Recreate fresh demo posts with new candidates
3. Generate new random candidate names

---

## Testing Your Setup

### Test Public Demo
```bash
# Should show candidates
curl http://localhost:8000/public-demo/start
```

### Test Organisation Demo
```bash
# Must be logged in, then:
curl http://localhost:8000/organisations/namaste-nepal-gmbh/demo/start
```

Or visit in browser after logging in.
