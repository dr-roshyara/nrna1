# PBDIGIT-65 — A voter's eligibility depends on which page they last visited

**Type:** Defect (root cause proven at runtime) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-09
**Found by:** IERVP Experiment B runtime verification · **Evidence:** `.claude/sessions/2026-08-08.md`
**Status:** `OPEN — not authorised`. **No repair attempted; the repair owner is genuinely undecided (see below).**

| | |
|---|---|
| **Customer impact** | 🔴 **An enrolled, active voter is told they cannot vote.** The election is open, they hold a valid voter membership, and the ballot refuses them — with no error a voter could act on |
| **Severity** | **Blocking for any voter who belongs to more than one organisation**, which includes every voter enrolled in elections at two organisations |
| **Confidence** | **High** — root cause proven by controlled A/B, not inferred |

---

## Proven at runtime — one session, one voter, one election, one row

**Only the session's `current_organisation_id` was changed between A and B. Nothing else.**

```
A.  GET /elections/election-2026-65b26848                    ->  isEligible: FALSE
    (session tenant = the OTHER organisation, set by the login redirect)

B.  GET /organisations/iervp-experiment-b   (sets tenant context)
    GET /elections/election-2026-65b26848                    ->  isEligible: TRUE
```

## The chain — every link measured

| # | Link | Evidence |
|---|---|---|
| 1 | **The membership row is valid** | `election_memberships`: `role=voter`, `status=active`, `deleted_at=NULL`, correct `election_id` **and** `organisation_id` — matches on every column · `OBSERVED IN DATABASE` |
| 2 | **The relationship adds no filter** | `User::electionMemberships()` is a plain `hasMany(ElectionMembership::class)` (`app/Models/User.php:289-292`) · `OBSERVED IN CODE` |
| 3 | **The model is tenant-scoped** | `ElectionMembership` uses `BelongsToTenant` (`:32`); the global scope filters `organisation_id` by **`TenantContext::get() ?? session('current_organisation_id')`** (`app/Traits/BelongsToTenant.php:44-60`) · `OBSERVED IN CODE` |
| 4 | **The election route never sets that context** | `/elections/{slug}` is **not** organisation-scoped, so the session keeps whatever organisation was last visited · `OBSERVED AT RUNTIME` |
| 5 | **Login sent the voter to the wrong organisation** | The voter belongs to two organisations; login redirected to the other one, which set the session tenant · `OBSERVED AT RUNTIME` |
| 6 | **Therefore the membership is invisible** | `first()` returns `null`, so `ElectionVotingController:41-44` computes `isEligible = false` · **PROVEN by the A/B above** |

## Why this is not a business-rule defect

**The rule is correct and the data is correct.**

* **Authoritative representation:** `ElectionMembership` — it carries `role` and `status`.
* **The rule applied:** `role === 'voter' && status !== 'removed'` — **not in dispute.**
* **What overrode it:** `TenantContext` / `session('current_organisation_id')` — **which organisation the browser most recently looked at.**

**Tenant context is infrastructure and interface concern. It is not a business input to voter eligibility.** Here an infrastructure concern silently overrides a correct rule applied to correct data.

> **A voter's right to vote must not depend on which page they visited last.**

**Classification:** **tenant/context defect**, with a **persistence-scoping** mechanism, **triggered by an interface/routing** decision. **Not** a business-rule defect, **not** a data defect.

## ⚠️ Repair owner is undecided — deliberately

**At least three authorities could legitimately own this, and they imply different products:**

1. **Route-level tenant establishment** — should `/elections/{slug}` derive tenant context from the election's own organisation?
2. **Scope applicability** — should `BelongsToTenant` apply at all to a model already keyed by `election_id`, which is itself organisation-unique?
3. **The eligibility query** — should the eligibility lookup be explicitly unscoped, since it asks a question about a *named* election?

**Engineering will not choose between these.** Each is defensible, and the choice determines how tenant isolation behaves for every election-scoped model, not just this one.

## Acceptance criteria — stated as business behaviour

* [ ] **An enrolled, active voter can reach and cast their ballot regardless of which page they visited beforehand**, and regardless of how many organisations they belong to.
* [ ] **Eligibility gives the same answer for the same voter and election every time it is asked**, independent of navigation history or session state.
* [ ] **Tenant isolation is not weakened** — a voter still cannot see or act on another organisation's elections. *(The repair must not trade a correctness bug for an isolation bug.)*
* [ ] A regression test encodes **a voter belonging to two organisations**, since a single-organisation voter never reproduces this.

## Explicit non-goals

* **Not** changing the eligibility rule itself — it is correct.
* **Not** modifying membership data.
* **Not** deciding the repair location (above).
* **Not** addressing the separate login-routing observation below.

## Related

* **`PBDIGIT-64`** — an election can enter voting with no candidates. **Independent cause**, same runtime programme. The two interact only in that `PBDIGIT-64` decides whether *the election* accepts votes while this ticket decides whether *the voter* may cast one — **two authorities, two different failure modes.**
* **Separate observation, not yet investigated:** with the voter enrolled in more than one election, **login routing did not select the expected election** — it went to an organisation page. That routing choice is what set the wrong tenant, so it may be the same story from the interface end. **`MECHANISM NOT ESTABLISHED`.**
* `PBDIGIT-49` — voter eligibility has two homes; this is further evidence for that theme.

---

**Traceability:** `app/Http/Controllers/ElectionVotingController.php:37-44` · `app/Models/User.php:289-292` · `app/Models/ElectionMembership.php:32` · `app/Traits/BelongsToTenant.php:44-60` · election `election-2026-65b26848` (organisation `iervp-experiment-b`), voter `iervp.voter1@example.test`, A/B performed 2026-08-09.
