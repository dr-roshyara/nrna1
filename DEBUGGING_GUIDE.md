# 🔍 DEBUGGING GUIDE: Candidates Not Displaying

Debug logging has been added to trace where candidate data breaks in the data flow.

---

## **STEP 1: Run Backend Debug (Controller Logging)**

```bash
# Clear logs
echo "" > storage/logs/laravel.log

# Run the voting controller test
php artisan test tests/Feature/Demo/DemoVoteControllerCreateTest.php --no-coverage

# Check the debug output
cat storage/logs/laravel.log | grep "CANDIDATE DEBUG" -A 20
```

**Expected Output:**
```
===== CANDIDATE DEBUG =====
National posts count: 1
Regional posts count: 1
National Post 0: President has 2 candidates
  - Candidate 0: Candidate 1
  - Candidate 1: Candidate 2
Regional Post 0: Regional Representative has 1 candidates
```

**If you see this:** ✅ Backend is sending candidates correctly → Go to STEP 2
**If candidates count is 0:** ❌ Issue is in controller query → Check database directly

---

## **STEP 2: Run Frontend Debug (Browser Console)**

1. Start the development server:
```bash
npm run dev
```

2. Navigate to the voting page in your browser (or run test)

3. Open browser console (F12 / Cmd+Option+I)

4. Look for output starting with: `===== VUE PROPS DEBUG =====`

**Expected Console Output:**
```javascript
===== VUE PROPS DEBUG =====
Posts received: {national: [...], regional: [...]}
National posts: (1) [{…}]
Regional posts: (1) [{…}]
First national post candidates: (2) [{…}, {…}]
```

**If you see this:** ✅ Props are reaching Vue correctly → Check template rendering
**If posts is empty:** ❌ Issue is in controller response or Inertia configuration

---

## **STEP 3: Quick Database Check**

```bash
php artisan tinker
```

Then run:

```php
// Check if candidates exist
$count = \App\Models\DemoCandidacy::count();
echo "Total candidates: {$count}\n";

// Check a demo election
$election = \App\Models\Election::where('type', 'demo')->first();
if ($election) {
    echo "Demo election ID: {$election->id}\n";

    $posts = \App\Models\DemoPost::where('election_id', $election->id)->get();
    echo "Posts in election: " . $posts->count() . "\n";

    foreach ($posts as $post) {
        $candidates = \App\Models\DemoCandidacy::where('post_id', $post->id)->count();
        echo "  - {$post->name}: {$candidates} candidates\n";
    }
} else {
    echo "No demo election found!\n";
}

exit;
```

**Expected Output:**
```
Total candidates: 3
Demo election ID: [uuid]
Posts in election: 2
  - President: 2 candidates
  - Regional Representative: 1 candidates
```

---

## **STEP 4: Check Individual Post Candidates**

```bash
php artisan tinker
```

```php
$post = \App\Models\DemoPost::first();
if ($post) {
    echo "Post: {$post->name} (ID: {$post->id})\n";

    // Method 1: Direct relationship
    $candidates1 = $post->candidacies()->get();
    echo "Via candidacies relationship: " . $candidates1->count() . "\n";

    // Method 2: Direct query
    $candidates2 = \App\Models\DemoCandidacy::where('post_id', $post->id)->get();
    echo "Via direct query: " . $candidates2->count() . "\n";

    // Check candidate details
    foreach ($candidates2 as $cand) {
        echo "  - {$cand->user_name} (ID: {$cand->id})\n";
    }
} else {
    echo "No posts found!\n";
}

exit;
```

---

## **STEP 5: Verify Controller Query Manually**

```bash
php artisan tinker
```

```php
$election = \App\Models\Election::where('type', 'demo')->first();
$user = \App\Models\User::first();

// Replicate controller query
$nationalPosts = \App\Models\DemoPost::where('election_id', $election->id)
    ->where('is_national_wide', 1)
    ->with(['candidacies' => function($query) {
        $query->orderBy('position_order');
    }])
    ->orderBy('position_order')
    ->get();

echo "National posts fetched: " . $nationalPosts->count() . "\n";

foreach ($nationalPosts as $post) {
    echo "Post: {$post->name}\n";
    echo "  Candidacies relationship loaded: " . ($post->relationLoaded('candidacies') ? 'YES' : 'NO') . "\n";
    echo "  Candidacies count: " . $post->candidacies->count() . "\n";
}

exit;
```

---

## **STEP 6: Check Route Accessibility**

```bash
# Verify the route exists
php artisan route:list | grep demo-vote.create
```

Should show:
```
slug.demo-vote.create         GET  {vslug}/demo-vote/create  DemoVoteController@create
```

If not found, check `routes/election/electionRoutes.php`

---

## **STEP 7: Minimal Test Component**

Create a temporary test to isolate the issue:

**File:** `resources/js/Pages/Vote/DemoVote/DebugCreate.vue`

```vue
<template>
    <div class="p-8">
        <h1>Debug: Posts Data</h1>
        <pre class="bg-gray-100 p-4 rounded">{{ JSON.stringify(posts, null, 2) }}</pre>
    </div>
</template>

<script>
export default {
    props: ['posts']
}
</script>
```

Then modify the controller temporarily:
```php
return Inertia::render('Vote/DemoVote/DebugCreate', [
    'posts' => [
        'national' => $national_posts,
        'regional' => $regional_posts,
    ],
]);
```

Visit the page and check if you see the candidate data in the JSON display.

---

## **DIAGNOSTIC FLOWCHART**

```
Is the page loading?
  ├─ YES: Go to Step 1
  └─ NO: Check browser console for errors

Step 1: Backend debug logs show candidates?
  ├─ YES: Go to Step 2
  └─ NO: Go to Step 3 (database check)

Step 2: Vue console shows posts with candidates?
  ├─ YES: Issue is in template rendering → Check Create.vue template
  └─ NO: Issue in Inertia response → Check controller data structure

Step 3: Database has candidates?
  ├─ YES: Controller query is wrong → Review DemoPost::with(['candidacies'])
  └─ NO: Seed database with test data

Template rendering issue?
  ├─ YES: Check posts.national?.length and sortedCandidates()
  └─ NO: Component props are wrong
```

---

## **Common Issues & Fixes**

| Issue | Cause | Fix |
|-------|-------|-----|
| Backend logs show candidates but Vue doesn't | Inertia not serializing | Check if Array/Object structure matches |
| Database has candidates but controller logs don't | Query doesn't load relationship | Verify `with(['candidacies'])` is applied |
| Vue shows data but template doesn't render | v-if condition fails | Check `posts.national?.length` in template |
| Candidates show but are disabled/greyed | No-vote selected by default | Check noVoteSelections initial state |
| Console errors about missing method | sortedCandidates not passed | Check return statement in setup() |

---

## **Quick Fix Reference**

### **If Backend Logs Are Empty:**
```php
// Check if the query is working
$posts = DemoPost::where('election_id', $election->id)->get();
dd($posts); // Should show posts
```

### **If Vue Props Are Empty:**
```javascript
// Check Inertia response
console.log(window.$page); // Inertia's global page object
```

### **If Template Not Rendering:**
```vue
<!-- Add temporary debug display -->
<div>Posts: {{ posts }}</div>
<div>National: {{ posts.national }}</div>
<div v-if="posts.national?.length">Has national posts</div>
```

---

## **Run These Commands in Order**

```bash
# 1. Database check
php artisan tinker --execute="echo \App\Models\DemoCandidacy::count() . ' candidates exist';"

# 2. Test backend
php artisan test tests/Feature/Demo/DemoVoteControllerCreateTest.php

# 3. Check logs
cat storage/logs/laravel.log | grep "CANDIDATE DEBUG" -A 30

# 4. Check for errors
cat storage/logs/laravel.log | grep -i error | head -20

# 5. Check routes
php artisan route:list | grep demo-vote
```

---

**Report the output of these commands and I can identify exactly where the data flow is breaking!** 🔍
