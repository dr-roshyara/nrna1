# 📋 Demo Election Setup Guide

A complete guide to create a demo election with candidates and post items for testing the voting system.

---

## **Quick Start (5 minutes)**

### **1. Create Demo Election**

```bash
php artisan tinker
```

```php
use App\Models\Election;

$election = Election::create([
    'name' => 'Demo Election 2024',
    'slug' => 'demo-election-2024',
    'type' => 'demo',
    'is_active' => true,
    'description' => 'Public demo election - test the voting system without registration',
    'start_date' => now()->format('Y-m-d'),
    'end_date' => now()->addDays(365)->format('Y-m-d'),
]);

echo "✅ Election created: ID {$election->id}\n";
```

### **2. Create Post/Posts (Positions)**

```php
use App\Models\Post;

$post = Post::create([
    'election_id' => $election->id,
    'title' => 'President',
    'description' => 'Vote for the next President',
    'min_votes' => 1,
    'max_votes' => 1,
    'position_order' => 1,
]);

echo "✅ Post created: ID {$post->id}\n";
```

### **3. Create Candidates**

```php
use App\Models\Candidate;

$candidates = [
    [
        'name' => 'Alice Johnson',
        'description' => 'Education & Healthcare Advocate',
        'party' => 'Progressive Party',
        'photo_url' => 'https://via.placeholder.com/200?text=Alice+Johnson',
    ],
    [
        'name' => 'Bob Smith',
        'description' => 'Economic Development Specialist',
        'party' => 'Economic Growth Party',
        'photo_url' => 'https://via.placeholder.com/200?text=Bob+Smith',
    ],
    [
        'name' => 'Carol Williams',
        'description' => 'Community Services Leader',
        'party' => 'Community First Party',
        'photo_url' => 'https://via.placeholder.com/200?text=Carol+Williams',
    ],
];

foreach ($candidates as $index => $data) {
    Candidate::create([
        'post_id' => $post->id,
        'name' => $data['name'],
        'description' => $data['description'],
        'party' => $data['party'],
        'photo_url' => $data['photo_url'],
        'position_order' => $index + 1,
    ]);
}

echo "✅ Created 3 candidates\n";
```

### **4. Test the Election**

```bash
# Visit in browser:
http://localhost:8000/election/demo/start
```

---

## **Detailed Step-by-Step**

### **Method 1: Using Artisan Tinker (Interactive)**

#### **Step 1: Open Tinker**
```bash
php artisan tinker
```

#### **Step 2: Create Election**

```php
use App\Models\Election;

$election = Election::create([
    'name' => 'City Council Election',
    'slug' => 'city-council-2024',
    'type' => 'demo',
    'is_active' => true,
    'description' => 'Elect your city council representatives',
    'start_date' => '2024-01-01',
    'end_date' => '2025-01-01',
]);

dd($election);
```

**Output:**
```
Election {
  id: 1
  name: "City Council Election"
  slug: "city-council-2024"
  type: "demo"
  is_active: true
}
```

Save the `election_id` for next steps.

#### **Step 3: Create Posts (Multiple Positions)**

```php
use App\Models\Post;

// Position 1: Mayor
$mayor = Post::create([
    'election_id' => 1,
    'title' => 'Mayor',
    'description' => 'Elect your city mayor',
    'min_votes' => 1,
    'max_votes' => 1,
    'position_order' => 1,
]);

// Position 2: City Council Member
$council = Post::create([
    'election_id' => 1,
    'title' => 'City Council Member',
    'description' => 'Elect up to 3 council members',
    'min_votes' => 0,
    'max_votes' => 3,
    'position_order' => 2,
]);

echo "✅ Created 2 post positions\n";
```

#### **Step 4: Create Candidates for Each Position**

##### **For Mayor (Post ID 1):**

```php
use App\Models\Candidate;

// Candidate 1
Candidate::create([
    'post_id' => 1,
    'name' => 'Sarah Anderson',
    'description' => 'Former teacher with 15 years in education',
    'party' => 'Progressive Party',
    'photo_url' => 'https://via.placeholder.com/200?text=Sarah+Anderson',
    'position_order' => 1,
]);

// Candidate 2
Candidate::create([
    'post_id' => 1,
    'name' => 'Michael Chen',
    'description' => 'Business owner with economic expertise',
    'party' => 'Economic Alliance',
    'photo_url' => 'https://via.placeholder.com/200?text=Michael+Chen',
    'position_order' => 2,
]);

// Candidate 3
Candidate::create([
    'post_id' => 1,
    'name' => 'Diana Brown',
    'description' => 'Community activist and organizer',
    'party' => 'Community First',
    'photo_url' => 'https://via.placeholder.com/200?text=Diana+Brown',
    'position_order' => 3,
]);

echo "✅ Created 3 candidates for Mayor\n";
```

##### **For City Council (Post ID 2):**

```php
// Council Candidate 1
Candidate::create([
    'post_id' => 2,
    'name' => 'James Rodriguez',
    'description' => 'Parks & Recreation Director',
    'party' => 'Progressive Party',
    'photo_url' => 'https://via.placeholder.com/200?text=James+Rodriguez',
    'position_order' => 1,
]);

// Council Candidate 2
Candidate::create([
    'post_id' => 2,
    'name' => 'Lisa Martinez',
    'description' => 'Public Health Advocate',
    'party' => 'Economic Alliance',
    'photo_url' => 'https://via.placeholder.com/200?text=Lisa+Martinez',
    'position_order' => 2,
]);

// Council Candidate 3
Candidate::create([
    'post_id' => 2,
    'name' => 'Robert Wilson',
    'description' => 'Infrastructure & Transportation Expert',
    'party' => 'Community First',
    'photo_url' => 'https://via.placeholder.com/200?text=Robert+Wilson',
    'position_order' => 3,
]);

// Council Candidate 4
Candidate::create([
    'post_id' => 2,
    'name' => 'Emily Taylor',
    'description' => 'Environmental Protection Officer',
    'party' => 'Green Initiative',
    'photo_url' => 'https://via.placeholder.com/200?text=Emily+Taylor',
    'position_order' => 4,
]);

echo "✅ Created 4 candidates for City Council\n";
```

#### **Step 5: Verify Everything**

```php
use App\Models\Election;

$election = Election::with('posts.candidates')->find(1);

echo "Election: {$election->name}\n";
echo "Posts: {$election->posts->count()}\n";
echo "Total Candidates: {$election->posts->sum(fn($b) => $b->candidates->count())}\n";

foreach ($election->posts as $post) {
    echo "\n📋 {$post->title} (max {$post->max_votes} vote(s)):\n";
    foreach ($post->candidates as $candidate) {
        echo "  - {$candidate->name} ({$candidate->party})\n";
    }
}
```

**Expected Output:**
```
Election: City Council Election
Posts: 2
Total Candidates: 7

📋 Mayor (max 1 vote(s)):
  - Sarah Anderson (Progressive Party)
  - Michael Chen (Economic Alliance)
  - Diana Brown (Community First)

📋 City Council Member (max 3 vote(s)):
  - James Rodriguez (Progressive Party)
  - Lisa Martinez (Economic Alliance)
  - Robert Wilson (Community First)
  - Emily Taylor (Green Initiative)
```

---

## **Method 2: Create via Seeder** ✅ WORKING

### **Run the Existing Seeder (Recommended)**

A complete, production-ready DemoElectionSeeder already exists:

```bash
php artisan db:seed --class=DemoElectionSeeder
```

This creates:
- ✅ **1 Demo Election** (`Demo Election` with slug `demo-election`)
- ✅ **3 Posts** (President, Vice President, Secretary)
- ✅ **9 Demo Candidates** (3 per post, in `demo_candidacies` table)
- ✅ Safe to run **multiple times** (automatically cleans old data)
- ✅ **Ready to use** at `http://localhost:8000/election/demo/start`

### **Seeder Features**

The seeder is **idempotent** (safe to run multiple times):
- **Deletes all existing demo elections** before creating a fresh one
- Uses consistent slug: `demo-election` (matches `/election/demo/start` route)
- Ensures clean state and prevents conflicts
- Candidacy IDs include election ID for uniqueness across runs
- All candidate data safely isolated in `demo_candidacies` table

### **What Gets Created**

```
Demo Election 2024
├── President (राष्ट्रपति)
│   ├── Alice Johnson - Progressive Platform
│   ├── Bob Smith - Economic Growth
│   └── Carol Williams - Community First
├── Vice President (उप-राष्ट्रपति)
│   ├── Daniel Miller - Innovation Leader
│   ├── Eva Martinez - Social Justice
│   └── Frank Wilson - Infrastructure Expert
└── Secretary (सचिव)
    ├── Grace Lee - Administration Expert
    ├── Henry White - Organization Specialist
    └── Iris Walker - Communications Lead
```

### **Seeder Code Reference**

Location: `database/seeders/DemoElectionSeeder.php`

Key features:
- Uses `DemoCandidate` model (not `Candidacy`) for demo data isolation
- Stores demo data in `demo_candidacies` table
- Each candidate has: `user_id`, `candidacy_id`, `user_name`, `candidacy_name`, `proposer_name`, `supporter_name`
- Uses English proposer/supporter names (can be translated as needed)
- Full Nepali translations for posts

### **Modify the Seeder**

To customize candidates, edit `database/seeders/DemoElectionSeeder.php`:

```php
$presidents = [
    [
        'user_name' => 'Alice Johnson',
        'candidacy_name' => 'Alice Johnson - Progressive Platform',
        'proposer_name' => 'John Doe',
        'supporter_name' => 'Jane Smith',
    ],
    // Add more candidates here
];
```

Then run the seeder again to apply changes.

### **Verify Seeder Installation**

After running the seeder, verify the setup in Tinker:

```php
php artisan tinker

use App\Models\Election, App\Models\Post, App\Models\DemoCandidate;

// Check demo election exists
$election = Election::where('slug', 'demo-election')->first();
echo "✅ Election: " . $election->name . " (ID: " . $election->id . ")\n";

// Check posts and candidates
$posts = Post::where('post_id', 'like', '%-' . $election->id)->get();
foreach ($posts as $post) {
    $candidates = DemoCandidate::where('election_id', $election->id)
        ->where('post_id', $post->post_id)
        ->count();
    echo "   " . $post->name . ": " . $candidates . " candidates\n";
}

// Verify data isolation: demo vs real
use App\Models\Candidacy;
echo "\n✅ Data Isolation Check:\n";
echo "   Demo candidates: " . DemoCandidate::count() . "\n";
echo "   Real candidacies: " . Candidacy::count() . " (should be 0 for demo-only setup)\n";
```

Expected output:
```
✅ Election: Demo Election (ID: 8)
   President: 3 candidates
   Vice President: 3 candidates
   Secretary: 3 candidates

✅ Data Isolation Check:
   Demo candidates: 9
   Real candidacies: 0 (should be 0 for demo-only setup)
```

---

## **Complete Election Structure**

Here's the data model you're creating:

```
Election (1)
  ├── Post Position 1: President (max_votes: 1)
  │   ├── Candidate 1: Alice Johnson
  │   ├── Candidate 2: Bob Smith
  │   └── Candidate 3: Carol Williams
  │
  └── Post Position 2: Vice President (max_votes: 1)
      ├── Candidate 1: David Lee
      ├── Candidate 2: Eva Martinez
      └── Candidate 3: Frank Wilson
```

---

## **Testing Checklist**

After creating your demo election:

### **1. Access the Election**
```bash
# Open in browser
http://localhost:8000/election/demo/start
```

### **2. Test Voting Flow**
- ✅ See the post positions
- ✅ See all candidates for each position
- ✅ Vote for candidates
- ✅ Submit votes
- ✅ See results

### **3. Verify in Database**

```php
// Check election
Election::where('type', 'demo')->first();

// Check post positions
Post::where('election_id', 1)->get();

// Check candidates
Candidate::where('post_id', 1)->get();

// Check votes cast
Vote::where('election_id', 1)->get();
```

---

## **Common Issues & Fixes**

### **Issue: Demo election not found at /election/demo/start**

**Cause:** Seeder hasn't been run or demo election doesn't have slug 'demo-election'

**Fix:**
```bash
# Run the seeder
php artisan db:seed --class=DemoElectionSeeder

# Verify
php artisan tinker
Election::where('slug', 'demo-election')->where('type', 'demo')->first();
```

### **Issue: Demo candidates not showing in voting interface**

**Cause:** Looking at `candidacies` table instead of `demo_candidacies` table

**Fix:**
```php
// WRONG - For real elections only
Candidacy::where('post_id', 'like', 'president-%')->get();

// CORRECT - For demo elections
use App\Models\DemoCandidate;
DemoCandidate::where('election_id', 8)->get();
```

### **Issue: Seeder creates duplicate entries on re-run**

**Why it's fixed:** The seeder now:
1. Deletes ALL existing demo elections first
2. Uses unique `candidacy_id` that includes election_id
3. Is idempotent (safe to run multiple times)

**If you get uniqueness errors:**
```bash
# Just re-run the seeder - it cleans up automatically
php artisan db:seed --class=DemoElectionSeeder
```

### **Issue: Old demo elections (multiple active demo elections)**

**Fix:** The seeder now deletes all demo elections before creating a new one, ensuring only one active demo election exists at a time.

```bash
# Check how many demo elections exist
php artisan tinker
Election::where('type', 'demo')->count();

# If >1, just run seeder to clean up
php artisan db:seed --class=DemoElectionSeeder
```

---

## **Advanced: Multiple Elections**

Create 2-3 demo elections for testing:

```php
use App\Models\Election, App\Models\Post, App\Models\Candidate;

foreach (['election-1', 'election-2', 'election-3'] as $slug) {
    $election = Election::create([
        'name' => ucfirst(str_replace('-', ' ', $slug)),
        'slug' => $slug,
        'type' => 'demo',
        'is_active' => true,
        'description' => 'Demo election for testing',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(30)->format('Y-m-d'),
    ]);

    $post = Post::create([
        'election_id' => $election->id,
        'title' => 'Representative',
        'description' => 'Vote for your representative',
        'min_votes' => 1,
        'max_votes' => 1,
        'position_order' => 1,
    ]);

    // Add 3 candidates
    for ($i = 1; $i <= 3; $i++) {
        Candidate::create([
            'post_id' => $post->id,
            'name' => "Candidate $i",
            'description' => "Candidate description $i",
            'party' => "Party $i",
            'position_order' => $i,
        ]);
    }
}

echo "✅ Created 3 demo elections\n";
```

---

## **Quick Reference: Database Tables**

### **elections table**
```
id (int)
name (string)
slug (string, unique)
type (enum: demo, official)
is_active (boolean)
description (text)
start_date (date)
end_date (date)
created_at, updated_at
```

### **posts table**
```
id (int)
election_id (int) → elections.id
title (string)
description (text)
min_votes (int)
max_votes (int)
position_order (int)
created_at, updated_at
```

### **candidates table**
```
id (int)
post_id (int) → posts.id
name (string)
description (text)
party (string)
photo_url (string)
position_order (int)
created_at, updated_at
```

---

## **Need Help?**

Run these diagnostic commands:

```php
// List all elections
Election::all();

// List all posts for election 1
Post::where('election_id', 1)->get();

// List all candidates for post 1
Candidate::where('post_id', 1)->get();

// Count votes
Vote::where('election_id', 1)->count();

// Delete and start over
Election::destroy(1);
```

---

**Happy voting! 🗳️**
