# 📚 Election Engine Documentation

**Comprehensive guide for the voting system architecture**

---

## 📖 Documentation Index

### Core Concepts
1. **[NATIONAL_REGIONAL_CANDIDATES.md](./NATIONAL_REGIONAL_CANDIDATES.md)** ⭐ START HERE
   - Complete architecture overview
   - Data model with diagrams
   - Backend implementation details
   - Frontend component structure
   - Regional filtering logic
   - Complete data flow examples
   - Testing strategy
   - Troubleshooting guide

2. **[QUICK_REFERENCE.md](./QUICK_REFERENCE.md)** 🚀 QUICK LOOKUP
   - At-a-glance data model
   - Common queries
   - Frontend code snippets
   - Common tasks
   - Critical rules
   - Troubleshooting checklist

---

## 🎯 Quick Start

### For New Developers

1. Read **[NATIONAL_REGIONAL_CANDIDATES.md](./NATIONAL_REGIONAL_CANDIDATES.md)**
   - Understand the architecture
   - Learn the principles
   - See code examples

2. Use **[QUICK_REFERENCE.md](./QUICK_REFERENCE.md)** while coding
   - Quick lookups
   - Copy-paste snippets
   - Troubleshooting

### For Specific Tasks

- **Adding a regional post** → See "Add Regional Post" in QUICK_REFERENCE.md
- **Debugging missing candidates** → See "Debug Missing Candidates" in QUICK_REFERENCE.md
- **Understanding data flow** → See "Complete Data Flow" in NATIONAL_REGIONAL_CANDIDATES.md
- **Performance issues** → See "Performance Considerations" in NATIONAL_REGIONAL_CANDIDATES.md

---

## 📊 Architecture at a Glance

### Data Model
```
Posts (Real Authority)
├─ is_national_wide: 1=national, 0=regional
├─ state_name: NULL (national) or "Bayern", "Hessen", etc.
└─ Candidates inherit region from post

Candidates (Workers)
├─ post_id: Links to post
├─ user_id: The person
└─ NO region field (single source of truth)
```

### Backend Flow
```
Request → Get User Region → Query Posts (Filter by is_national_wide + state_name)
→ Group Candidates by post_id → Return Inertia response
→ Frontend renders separate National/Regional sections
```

### Frontend Flow
```
CreateVotingPage receives: national_posts[] + regional_posts[] + user_region
→ Renders separate sections
→ CreateVotingform handles selections (keyed by post_id)
→ Submit separates national/regional votes
```

---

## 🔑 Key Files

| File | Controller/Component | Purpose |
|------|---------------------|---------|
| `app/Http/Controllers/VoteController.php` | Backend | Real election filtering |
| `app/Http/Controllers/Demo/DemoVoteController.php` | Backend | Demo election filtering |
| `resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue` | Frontend | Main voting page |
| `resources/js/Pages/Vote/DemoVote/CreateVotingform.vue` | Frontend | Per-post component |

---

## ✅ Golden Rules

1. **Region stored on Posts** - Single source of truth
2. **Candidates have NO region** - Inherited from post
3. **Filter at database level** - Queries, not application code
4. **Separate sections** - National and regional rendered separately
5. **Keyed state** - Selections stored by post_id, never flat array
6. **Both election types** - Demo and Real use identical logic

---

## 🚀 Implementation Checklist

- ✅ Backend: Both `VoteController` and `DemoVoteController` use same filtering
- ✅ Database: Posts have `is_national_wide` and `state_name` columns
- ✅ Database: Candidates table has NO region column
- ✅ Frontend: CreateVotingPage displays national and regional sections
- ✅ Frontend: CreateVotingform stores selections by post_id
- ✅ Testing: Regional isolation verified
- ✅ Performance: Indexes created for queries

---

## 📋 Common Patterns

### Get National Posts
```php
$posts = Post::where('election_id', $id)
    ->where('is_national_wide', 1)
    ->get();
```

### Get Regional Posts for User
```php
$posts = Post::where('election_id', $id)
    ->where('is_national_wide', 0)
    ->where('state_name', $user->region)
    ->get();
```

### Validate Regional Integrity
```php
if (!$post->is_national_wide && $post->state_name !== $user->region) {
    abort(403, 'Invalid region');
}
```

---

## 🧪 Testing

See **Testing Strategy** section in [NATIONAL_REGIONAL_CANDIDATES.md](./NATIONAL_REGIONAL_CANDIDATES.md)

Key test cases:
- All voters see national posts
- Voters see only their region's posts
- Candidates assigned to correct posts
- Regional isolation verified

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Candidates from wrong region | Check regional filter in query |
| Regional posts not showing | Verify user.region matches post.state_name |
| Cross-post interference | Check selectedByPost is keyed by post_id |
| Missing candidates | Verify candidates exist for post_id |
| Performance issues | Create database indexes |

**Detailed troubleshooting:** See [NATIONAL_REGIONAL_CANDIDATES.md - Troubleshooting](./NATIONAL_REGIONAL_CANDIDATES.md#troubleshooting)

---

## 📞 Need Help?

1. **Quick question?** → Check [QUICK_REFERENCE.md](./QUICK_REFERENCE.md)
2. **Understanding architecture?** → Read [NATIONAL_REGIONAL_CANDIDATES.md](./NATIONAL_REGIONAL_CANDIDATES.md)
3. **Debugging issue?** → Go to Troubleshooting section
4. **Code examples?** → See Common Patterns

---

## 📈 Version History

| Version | Date | Changes |
|---------|------|---------|
| 2.0 | Feb 2026 | Production ready, complete architecture |
| 1.0 | Feb 2026 | Initial implementation |

---

## ✨ Status

**✅ PRODUCTION READY**

- Architecture: ✅ Reviewed
- Code: ✅ Tested
- Documentation: ✅ Complete
- Performance: ✅ Optimized

---

## 🔗 Related Documentation

- [Main Developer Guide](../README.md)
- [ARCHITECTURE.md](../ARCHITECTURE.md)
- [DATABASE_SCHEMA.md](../database-schema.md)
- [Query Examples](../query-examples.md)

---

**Maintained By:** Development Team
**Last Updated:** February 2026
**Contact:** [Development Team]
