# 📋 Demo Election Setup Guide

A complete guide to create a demo election with candidates and ballot items for testing the voting system.

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

### **2. Create Ballot/Posts (Positions)**

```php
use App\Models\Ballot;

$ballot = Ballot::create([
    'election_id' => $election->id,
    'title' => 'President',
    'description' => 'Vote for the next President',
    'min_votes' => 1,
    'max_votes' => 1,
    'position_order' => 1,
]);

echo "✅ Ballot created: ID {$ballot->id}\n";
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
        'ballot_id' => $ballot->id,
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

#### **Step 3: Create Ballots (Multiple Positions)**

```php
use App\Models\Ballot;

// Position 1: Mayor
$mayor = Ballot::create([
    'election_id' => 1,
    'title' => 'Mayor',
    'description' => 'Elect your city mayor',
    'min_votes' => 1,
    'max_votes' => 1,
    'position_order' => 1,
]);

// Position 2: City Council Member
$council = Ballot::create([
    'election_id' => 1,
    'title' => 'City Council Member',
    'description' => 'Elect up to 3 council members',
    'min_votes' => 0,
    'max_votes' => 3,
    'position_order' => 2,
]);

echo "✅ Created 2 ballot positions\n";
```

#### **Step 4: Create Candidates for Each Position**

##### **For Mayor (Ballot ID 1):**

```php
use App\Models\Candidate;

// Candidate 1
Candidate::create([
    'ballot_id' => 1,
    'name' => 'Sarah Anderson',
    'description' => 'Former teacher with 15 years in education',
    'party' => 'Progressive Party',
    'photo_url' => 'https://via.placeholder.com/200?text=Sarah+Anderson',
    'position_order' => 1,
]);

// Candidate 2
Candidate::create([
    'ballot_id' => 1,
    'name' => 'Michael Chen',
    'description' => 'Business owner with economic expertise',
    'party' => 'Economic Alliance',
    'photo_url' => 'https://via.placeholder.com/200?text=Michael+Chen',
    'position_order' => 2,
]);

// Candidate 3
Candidate::create([
    'ballot_id' => 1,
    'name' => 'Diana Brown',
    'description' => 'Community activist and organizer',
    'party' => 'Community First',
    'photo_url' => 'https://via.placeholder.com/200?text=Diana+Brown',
    'position_order' => 3,
]);

echo "✅ Created 3 candidates for Mayor\n";
```

##### **For City Council (Ballot ID 2):**

```php
// Council Candidate 1
Candidate::create([
    'ballot_id' => 2,
    'name' => 'James Rodriguez',
    'description' => 'Parks & Recreation Director',
    'party' => 'Progressive Party',
    'photo_url' => 'https://via.placeholder.com/200?text=James+Rodriguez',
    'position_order' => 1,
]);

// Council Candidate 2
Candidate::create([
    'ballot_id' => 2,
    'name' => 'Lisa Martinez',
    'description' => 'Public Health Advocate',
    'party' => 'Economic Alliance',
    'photo_url' => 'https://via.placeholder.com/200?text=Lisa+Martinez',
    'position_order' => 2,
]);

// Council Candidate 3
Candidate::create([
    'ballot_id' => 2,
    'name' => 'Robert Wilson',
    'description' => 'Infrastructure & Transportation Expert',
    'party' => 'Community First',
    'photo_url' => 'https://via.placeholder.com/200?text=Robert+Wilson',
    'position_order' => 3,
]);

// Council Candidate 4
Candidate::create([
    'ballot_id' => 2,
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

$election = Election::with('ballots.candidates')->find(1);

echo "Election: {$election->name}\n";
echo "Ballots: {$election->ballots->count()}\n";
echo "Total Candidates: {$election->ballots->sum(fn($b) => $b->candidates->count())}\n";

foreach ($election->ballots as $ballot) {
    echo "\n📋 {$ballot->title} (max {$ballot->max_votes} vote(s)):\n";
    foreach ($ballot->candidates as $candidate) {
        echo "  - {$candidate->name} ({$candidate->party})\n";
    }
}
```

**Expected Output:**
```
Election: City Council Election
Ballots: 2
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

## **Method 2: Create via Seeder**

### **Create a Seeder**

```bash
php artisan make:seeder DemoElectionSeeder
```

### **Add Code to Seeder**

Edit `database/seeders/DemoElectionSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Ballot;
use App\Models\Candidate;
use App\Models\Election;
use Illuminate\Database\Seeder;

class DemoElectionSeeder extends Seeder
{
    public function run()
    {
        // Create Election
        $election = Election::create([
            'name' => 'Demo Election 2024',
            'slug' => 'demo-2024',
            'type' => 'demo',
            'is_active' => true,
            'description' => 'Public demo election for testing',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(365)->format('Y-m-d'),
        ]);

        // Create Ballot Position
        $ballot = Ballot::create([
            'election_id' => $election->id,
            'title' => 'President',
            'description' => 'Vote for the next President',
            'min_votes' => 1,
            'max_votes' => 1,
            'position_order' => 1,
        ]);

        // Create Candidates
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
                'ballot_id' => $ballot->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'party' => $data['party'],
                'photo_url' => $data['photo_url'],
                'position_order' => $index + 1,
            ]);
        }

        $this->command->info('✅ Demo election created with candidates!');
    }
}
```

### **Run Seeder**

```bash
php artisan db:seed --class=DemoElectionSeeder
```

---

## **Complete Election Structure**

Here's the data model you're creating:

```
Election (1)
  ├── Ballot Position 1: President (max_votes: 1)
  │   ├── Candidate 1: Alice Johnson
  │   ├── Candidate 2: Bob Smith
  │   └── Candidate 3: Carol Williams
  │
  └── Ballot Position 2: Vice President (max_votes: 1)
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
- ✅ See the ballot positions
- ✅ See all candidates for each position
- ✅ Vote for candidates
- ✅ Submit votes
- ✅ See results

### **3. Verify in Database**

```php
// Check election
Election::where('type', 'demo')->first();

// Check ballot positions
Ballot::where('election_id', 1)->get();

// Check candidates
Candidate::where('ballot_id', 1)->get();

// Check votes cast
Vote::where('election_id', 1)->get();
```

---

## **Common Issues & Fixes**

### **Issue: Election not showing**
```php
// Check if active
Election::find(1)->update(['is_active' => true]);

// Check if has ballots
Ballot::where('election_id', 1)->count();
```

### **Issue: Candidates not displaying**
```php
// Check if candidates exist
Candidate::where('ballot_id', 1)->count();

// Check if ballot_id is correct
Candidate::where('ballot_id', 1)->get();
```

### **Issue: Can't vote**
```php
// Check election dates
Election::find(1)->where('start_date', '<=', now())
                  ->where('end_date', '>=', now())
                  ->exists();
```

---

## **Advanced: Multiple Elections**

Create 2-3 demo elections for testing:

```php
use App\Models\Election, App\Models\Ballot, App\Models\Candidate;

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

    $ballot = Ballot::create([
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
            'ballot_id' => $ballot->id,
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

### **ballots table**
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
ballot_id (int) → ballots.id
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

// List all ballots for election 1
Ballot::where('election_id', 1)->get();

// List all candidates for ballot 1
Candidate::where('ballot_id', 1)->get();

// Count votes
Vote::where('election_id', 1)->count();

// Delete and start over
Election::destroy(1);
```

---

**Happy voting! 🗳️**
