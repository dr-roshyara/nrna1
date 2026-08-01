# Discovery: CreateVote.vue Contract Divergence

**Date:** 2026-06-13  
**Status:** Complete

## Finding

`CreateVote.vue` (1408 lines, 19 hardcoded post IDs) is **dead code**.

- The route serving it (`get('/vote/create')`) is **commented out** (electionRoutes.php:140)
- All legacy voting routes redirect to the slug-based system (electionRoutes.php:151-165)
- The active voting flow uses `CreateVotingPage.vue` which correctly consumes `national_posts`/`regional_posts` from the backend

## Architectural Conclusion

```
Initial hypothesis:
CreateVote.vue contains domain knowledge → needs DDD extraction

Discovery result:
CreateVote.vue is a legacy consumer of an obsolete data contract

Resolution:
No domain extraction needed. The page is unreachable.
```

## Verification

| Check | Result |
|-------|--------|
| `CreateVote.vue` route active? | ❌ Commented out (line 140) |
| Legacy routes redirect? | ✅ All redirect to slug-based system |
| Backend has richer data? | ✅ `national_posts` + `regional_posts` with candidacies |
| Working page exists? | ✅ `CreateVotingPage.vue` consumes the correct API |
| Frontend DDD extraction needed? | ❌ No — this is a boundary/legacy issue, not a domain gap |

## Related

- [Discovery: CreateVote Assessment](20260613-create-vote-assessment.md)
- [Discovery: Election Post Source Analysis](20260613-election-post-source-analysis.md)
