# Discovery: Election Post Source Analysis

**Date:** 2026-06-13  
**Status:** Complete  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)  
**Context:** Before creating `Domain/Election/NrnaElectionPosts.ts`, determine whether the backend already owns post data.

## 1. Are These IDs Stored in Database Tables?

**Yes.** The `posts` table exists with columns:

| Column | Type | Example |
|--------|------|---------|
| `id` | UUID (PK) | `550e8400-...` |
| `organisation_id` | UUID (FK) | |
| `election_id` | UUID (FK) | |
| `name` | string | `President` |
| `nepali_name` | string (nullable) | `अध्यक्ष` |
| `is_national_wide` | boolean | `true` |
| `state_name` | string (nullable) | `Bayern` (for regional) |
| `required_number` | integer | `1` or `2` |
| `position_order` | integer (nullable) | |

The `post_id` string field ("2021_01" etc.) is **not in the migration** but is in the `Post` model's `$fillable` array — suggesting it was added by a later migration as a legacy identifier.

**Finding:** The backend **fully owns** the post data structure including names, selection limits (`required_number`), geographic scope (`is_national_wide`, `state_name`), and ordering.

## 2. Are They Seeded by Migrations?

**Yes.** `database/seeders/DatabaseSeeder.php` creates posts for the demo election. The `Post` model's `required_number` field directly maps to CreateVote.vue's per-post selection limits.

## 3. Are They Configurable by Administrators?

**Yes.** Posts are linked to `organisation_id` and `election_id` — admins can define posts per election via the admin UI. The CreateVote.vue hardcoded post IDs ("2021_01" through "2021_19") appear to be **legacy identifiers for a specific NRNA election**, not universal constitutional constants.

## 4. Do Future Elections Reuse the Same IDs?

**Unclear from code alone.** The hardcoded IDs look specific to one election cycle. If the backend creates new posts per election (with UUIDs), the old "2021_01" IDs would not be reused.

## 5. Does the Backend API Already Expose Post Definitions?

**Partially.** The `CandidacyController::index()` loads posts with `id`, `post_id`, `name`, `is_national_wide`. The CreateVote.vue component receives a `candidacies` prop from the voting session. However, the page does **not** receive Post metadata directly — it receives raw candidacy data and filters it using hardcoded post IDs.

## 6. ADR-001 Assessment

| Question | Answer |
|----------|--------|
| Does the backend own post data? | ✅ **Yes** — `posts` table with all fields |
| Does the frontend consume it? | ❌ **No** — CreateVote.vue hardcodes 19 IDs instead |
| Is this a duplication? | ✅ **Yes** — the 19 post definitions exist in the database AND are hardcoded in the frontend |
| Could the backend send post definitions? | ✅ **Yes** — the Post model and controller already expose the data |

## 7. Architectural Conclusion

| Finding | Decision |
|---------|----------|
| Backend already owns post data | ❌ **Do NOT create `NrnaElectionPosts.ts` in Domain** |
| Frontend is duplicating backend data | The correct fix is to send post definitions from the backend API and consume them, not duplicate them in frontend Domain/ |

**The correct ADR-001-compliant solution is NOT to extract hardcoded IDs to Domain/Election, but to refactor CreateVote.vue to consume post definitions from the backend response.**

Creating `Domain/Election/NrnaElectionPosts.ts` would:
1. Violate ADR-001 (creating a new source when an existing one exists)
2. Duplicate database schema knowledge in the frontend
3. Create a maintenance burden when posts change

## 8. Updated Discovery Queue

| Priority | Candidate | Action | Status |
|----------|-----------|--------|--------|
| 1 | CreateVote.vue — hardcoded post IDs | **Do NOT extract to Domain** | ❌ Blocked by ADR-001 |
| 2 | CreateVote.vue — should consume backend API post data | Backend refactoring needed | 🔍 Requires backend changes |
| 3 | `Vote/DemoVote/Create.vue` | Discovery | ⏳ Next |
| 4 | `Elections/Voters/Index.vue` | Discovery | ⏳ |

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: CreateVote Assessment](20260613-create-vote-assessment.md)
