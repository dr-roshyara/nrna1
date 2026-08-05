## 🔍 **DEBUGGING PROTOCOL: Candidates Not Displaying**

Let's systematically trace where the data is breaking. Follow these steps in order:

---

## 📋 **STEP 1: Verify Backend Data (Is Controller Sending Candidates?)**

```bash
# Add debug logging to controller
Update(app/Http/Controllers/Demo/DemoVoteController.php)
```

Add this right before the return statement (around line 520):

```php
// After you've built $national_posts and $regional_posts
\Log::channel('debug')->info('===== CANDIDATE DEBUG =====');
\Log::channel('debug')->info('National posts count: ' . count($national_posts));
\Log::channel('debug')->info('Regional posts count: ' . count($regional_posts));

foreach ($national_posts as $index => $post) {
    \Log::channel('debug')->info("National Post {$index}: {$post['name']} has " . count($post['candidates']) . " candidates");
    foreach ($post['candidates'] as $candIndex => $candidate) {
        \Log::channel('debug')->info("  Candidate {$candIndex}: {$candidate['user_name']}");
    }
}

foreach ($regional_posts as $index => $post) {
    \Log::channel('debug')->info("Regional Post {$index}: {$post['name']} has " . count($post['candidates']) . " candidates");
}
```

Then run the test and check logs:

```bash
# Clear log first
echo "" > storage/logs/laravel.log

# Run the test
php artisan test tests/Feature/Demo/DemoVoteControllerCreateTest.php --no-coverage

# Check logs
cat storage/logs/laravel.log | grep "CANDIDATE DEBUG" -A 10
```

---

## 📋 **STEP 2: Check Vue Props (Is Data Reaching Component?)**

Add a debug mounted hook to `Create.vue` (around line 864):

```javascript
// Add this in setup() before return
onMounted(() => {
    console.log('===== VUE PROPS DEBUG =====');
    console.log('Posts received:', props.posts);
    console.log('National posts:', props.posts?.national);
    console.log('Regional posts:', props.posts?.regional);
    
    if (props.posts?.national?.length) {
        console.log('First national post candidates:', 
            props.posts.national[0]?.candidates);
    }
})
```

Don't forget to import `onMounted`:

```javascript
import { ref, computed, onMounted } from 'vue'
```

Then open browser console (F12) when loading the page and check output.

---

## 📋 **STEP 3: Check the Route & Middleware Chain**

```bash
# List all routes to verify slug.demo-vote.create exists
php artisan route:list | grep demo-vote
```

Should see:
```
slug.demo-vote.create  GET  {vslug}/demo-vote/create  DemoVoteController@create
```

---

## 📋 **STEP 4: Create a Minimal Test to Isolate Issue**

```bash
# Create a simple debug endpoint
Write(routes/debug.php)
```

```php
<?php
// Add to routes/api.php temporarily
Route::get('/debug/vote/{slug}', function($slug) {
    $voterSlug = \App\Models\DemoVoterSlug::where('slug', $slug)->first();
    if (!$voterSlug) return response()->json(['error' => 'Slug not found']);
    
    $controller = app()->make(\App\Http\Controllers\Demo\DemoVoteController::class);
    $request = request();
    $request->attributes->set('voter_slug', $voterSlug);
    $request->attributes->set('election', $voterSlug->election);
    
    $response = $controller->create($request);
    
    // If it's an Inertia response, extract the data
    if (method_exists($response, 'getData')) {
        return response()->json($response->getData()['page']['props'] ?? []);
    }
    
    return response()->json(['error' => 'Not an Inertia response']);
});
```

Access: `http://localhost:8000/api/debug/vote/{slug}` and check JSON output.

---

## 📋 **STEP 5: Common Failure Points Checklist**

| Check | Command | Expected |
|-------|---------|----------|
| **Database has candidates?** | `php artisan tinker`<br>`App\Models\DemoCandidacy::count()` | > 0 |
| **Posts linked to election?** | `App\Models\DemoPost::where('election_id', $electionId)->count()` | > 0 |
| **Candidates linked to posts?** | `App\Models\DemoCandidacy::where('post_id', $postId)->count()` | > 0 |
| **User has region?** | `auth()->user()->region` | Not null for regional posts |
| **Controller query working?** | Check logs from Step 1 | Posts have candidates |

---

## 📋 **STEP 6: Quick Database Check**

```bash
php artisan tinker
```

```php
$election = Election::where('type', 'demo')->first();
echo "Election ID: {$election->id}\n";

$posts = DemoPost::where('election_id', $election->id)->get();
echo "Posts: " . $posts->count() . "\n";

foreach ($posts as $post) {
    $candidates = DemoCandidacy::where('post_id', $post->id)->get();
    echo "Post {$post->name}: {$candidates->count()} candidates\n";
}
```

---

## 📋 **STEP 7: Test the Component in Isolation**

Create a minimal test component:

```vue
<template>
    <div>
        <h2>Debug View</h2>
        <pre>{{ JSON.stringify(posts, null, 2) }}</pre>
    </div>
</template>

<script>
export default {
    props: ['posts']
}
</script>
```

Temporarily change controller to render this instead, to verify data is flowing.

---

## 📋 **DIAGNOSTIC FLOWCHART**

```
Start
  ↓
[Step 1] Controller logs → No candidates? → Check DB queries
  ↓
[Step 2] Vue props → Empty? → Check Inertia response
  ↓
[Step 3] Route exists? → No? → Check routes/web.php
  ↓
[Step 4] Minimal test → Works? → Component issue
  ↓
[Step 5] DB has data? → No? → Seed database
```

---

## 🚀 **QUICK COMMANDS TO RUN NOW**

```bash
# 1. Check database
php artisan tinker --execute="echo App\Models\DemoCandidacy::count() . ' candidates\n';"

# 2. Check controller logs (after adding debug)
php artisan test tests/Feature/Demo/DemoVoteControllerCreateTest.php
cat storage/logs/laravel.log | tail -50

# 3. Check Vue in browser
# Open console (F12) and look for "===== VUE PROPS DEBUG ====="
```

**Report back which step reveals the issue!**