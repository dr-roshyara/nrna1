# 🚀 Quick Reference: National vs Regional Candidates

**For quick lookups during development**

---

## Data Model At a Glance

```
Posts Table:
├─ is_national_wide: 1 = all voters | 0 = by region
├─ state_name: NULL (national) | "Bayern", "Hessen", etc. (regional)
└─ ⚠️ Candidates have NO region column (region inherited from post)

Candidates Table:
├─ post_id: Links to the post
├─ user_id: Who is running
└─ ⚠️ NO region column!
```

---

## Backend Queries

### Get National Posts
```php
$posts = Post::where('election_id', $election->id)
    ->where('is_national_wide', 1)
    ->get();
```

### Get Regional Posts for User
```php
$posts = Post::where('election_id', $election->id)
    ->where('is_national_wide', 0)
    ->where('state_name', $user->region)  // ← User's region
    ->get();
```

### Get Candidates for a Post
```php
$candidates = Candidacy::where('post_id', $post->id)->get();
// No region filter needed - region comes from post!
```

### For Demo Elections
```php
$posts = DemoPost::where('is_national_wide', 1)->get();  // National

$posts = DemoPost::where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();  // Regional
```

---

## Frontend Components

### Pass Data to Vue
```php
return Inertia::render('Vote/CreateVotingPage', [
    'national_posts' => $nationalPostsArray,
    'regional_posts' => $regionalPostsArray,
    'user_region' => $auth_user->region,
]);
```

### Render in Parent Component
```vue
<section v-if="national_posts.length > 0">
  <h2>National Candidates</h2>
  <create-votingform
    v-for="post in national_posts"
    :post="post"
    :candidates="post.candidates"
    @add_selected_candidates="handleCandidateSelection('national', i, $event)"
  />
</section>

<section v-if="regional_posts.length > 0">
  <h2>Candidates for {{ user_region }} Region</h2>
  <create-votingform
    v-for="post in regional_posts"
    :post="post"
    :candidates="post.candidates"
    @add_selected_candidates="handleCandidateSelection('regional', i, $event)"
  />
</section>
```

### Store Selections (Critical!)
```javascript
// ✅ CORRECT: Keyed by post_id
selectedByPost: {
  1: [candidacy_1, candidacy_2],
  2: [candidacy_3]
}

// ❌ WRONG: Not keyed
selected: [candidacy_1, candidacy_2, candidacy_3]
```

---

## Common Tasks

### Task: Add a Regional Post for Bayern

```php
$post = Post::create([
    'election_id' => 1,
    'name' => 'State Representative',
    'is_national_wide' => 0,
    'state_name' => 'Bayern',  // ← Region specified
    'required_number' => 2
]);

// Candidates added normally - they inherit the region from the post
$candidate = Candidacy::create([
    'post_id' => $post->id,
    'user_id' => 123
]);
```

### Task: Verify User Can See Post

```php
$user = User::find(1);
$post = Post::find(1);

if ($post->is_national_wide) {
    // Everyone sees it
    return true;
} else if ($post->state_name === $user->region) {
    // User's region matches
    return true;
}

return false;
```

### Task: Debug Missing Candidates

```php
// Check the post
$post = Post::find(1);
dd([
    'post_id' => $post->id,
    'is_national_wide' => $post->is_national_wide,
    'state_name' => $post->state_name,
    'candidates_count' => $post->candidates->count()
]);

// Check candidates
$candidates = Candidacy::where('post_id', $post->id)->get();
dd($candidates);
```

---

## Critical Rules

| Rule | ✅ Do | ❌ Don't |
|------|-------|---------|
| **Region Info** | Store on Post | Add to Candidacy |
| **Filtering** | Use WHERE clause | Filter in app |
| **Candidate Region** | Inherit from post | Store explicitly |
| **Selection State** | Key by post_id | Use flat array |
| **National Posts** | is_national_wide=1 | state_name is null |
| **Regional Posts** | is_national_wide=0 + state_name | Hardcode regions |

---

## SQL Index Optimization

```sql
-- For faster queries
CREATE INDEX posts_election_national
ON posts(election_id, is_national_wide);

CREATE INDEX posts_election_region
ON posts(election_id, is_national_wide, state_name);
```

---

## Testing Checklist

- [ ] National posts visible to all users
- [ ] Regional posts visible only to matching region
- [ ] Bayern voter doesn't see Hessen posts
- [ ] Candidates grouped correctly by post
- [ ] No cross-post selection interference
- [ ] Submitting votes works for both national and regional
- [ ] Demo elections work like real elections

---

## File Locations

| File | Purpose |
|------|---------|
| `app/Http/Controllers/VoteController.php` | Real election filtering |
| `app/Http/Controllers/Demo/DemoVoteController.php` | Demo election filtering |
| `resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue` | Main voting component |
| `resources/js/Pages/Vote/DemoVote/CreateVotingform.vue` | Per-post component |

---

## Quick Troubleshooting

| Problem | Check |
|---------|-------|
| Candidates from wrong region | Regional filter in query |
| Regional posts not showing | User region matches post state_name |
| Cross-post interference | selectedByPost keyed by post_id |
| No candidates for post | Verify post_id in candidates |
| Performance issues | Check database indexes |

---

**For detailed guide:** See [NATIONAL_REGIONAL_CANDIDATES.md](./NATIONAL_REGIONAL_CANDIDATES.md)
