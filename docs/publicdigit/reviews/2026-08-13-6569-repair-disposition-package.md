# PBDIGIT-65/69 Repair Disposition — decision-ready, nothing chosen

**Type:** Governance disposition package (Session 2) · **Date:** 2026-08-13 · **Commission:** PO — reconcile the Decision-B audit; evaluate repair options **without choosing**; distinguish *audit complete* from *repair authorized*
**⛔ No option chosen · no repair location selected · no grant created or signed · no code/test/Constitution change · EM-VOT-002 not reopened · EM-OPEN-021 untouched · Session 1/3/4 remain stopped.**

> ## **AUDIT COMPLETE ≠ REPAIR AUTHORIZED.** The evidence threshold is crossed; the authorization threshold is not.

---

## 1 · What the audit established (reconciled, with strength labels)

**Source:** [`2026-08-13-belongstotenant-family-audit.md`](2026-08-13-belongstotenant-family-audit.md) (`9cf441fc`) — executed exactly under the PO-authored charter; nothing repaired; stop conditions honored.

| Finding | Strength |
|---|---|
| **41 true trait consumers** (enumeration self-corrected from 24 after failing its own sanity check — disclosed) | MEASURED |
| **9 Decision-A-relevant (class A) models**; **19 models (classes D+E) explicitly OUTSIDE Decision A** — a shared technical mechanism does not imply shared domain semantics; Membership/Contestation/Adjudication tenancy semantics belong to their own contexts, unadjudicated | MEASURED / boundary held |
| **TWO confirmed Decision-A violations, ONE mechanism**, both feeding the same live voting stack: **(1)** `User::isVoterInElection()` (`User.php:315`) — scoped relation, no bypass, **plus** tenant-free cache key `user.{id}.voter.{election_id}` — **runtime-proven (P6)**; **(2)** the `isEligible` projection at **`app/Http/Controllers/ElectionVotingController.php:37-44`** *(path precision: not under `Http/Controllers/Election/`)* — same scoped relation, no bypass — **the original PBDIGIT-65 site, verbatim unchanged; proven by the ticket's 2026-08-09 runtime A/B + static re-verification now.** Session 2 re-read both sites: the relation is a plain `hasMany` on the trait-scoped model; verified | PROVEN (2 sites, 1 mechanism) |
| **EM-VOT-002 is NOT tenant-contaminated** — both enforcement paths reach candidacies through `withoutGlobalScopes()` at the relation | verified statically + P0 tests · **do not reopen** |
| `Election::getVoterCountAttribute` — same tenant-free-key/tenant-dependent-answer pattern | ⚠️ **STATIC only, not runtime-reproduced** |
| **`has_voters` hybrid** (one branch bypassed, one scoped) — `complete_administration` can flip with ambient context | ⚠️ STATIC · **lifecycle-transition concern, NOT voting-time entitlement — Decision A does not govern it** (§4) |
| **375 `withoutGlobalScopes()` calls repo-wide** | **MEASURED COUNT ONLY — 375 bypasses ≠ 375 proven-correct sites; per-site correctness NOT audited.** Meaning: *domain correctness on this path currently depends on developers remembering a per-site bypass* — an architectural smell on the record; whether it warrants redesign is a separate decision |

**Documentation arithmetic discrepancies — preserved, NOT silently fixed (PO instruction):** ① audit §3 class C says **"8"** but lists **9** names (the audit's own parenthetical partially acknowledges the Demo pair); ② `OrganisationUser` is deliberately dual-listed (B+E), so class totals must not be summed; ③ audit §1's headline says **"ONE demonstrated violation (runtime-proven)"** while §4/§8 record **"2 sites, 1 mechanism"** — consistent once proof provenance is stated (site 1 proven by P6 now; site 2 by the ticket's own earlier runtime A/B + verbatim-unchanged static check). None of these invalidates the 41-consumer count, which is independently stated.

## 2 · The repair subject, precisely

One mechanism, two expressions, one live stack:

```
tenant-scoped relation (BelongsToTenant on ElectionMembership)
        read WITHOUT bypass at two voting-time sites
                +
tenant-DEPENDENT answer cached under a tenant-FREE key (site 1)
                =
valid Election entitlement invisibly revoked by ambient context,
and the wrong answer transported across contexts by the cache
```

This violates the accepted Decision-A rule and the adopted entitlement model (Model B; `EM-ENT-004`; `EM-GOV-001`): *the entitlement's answer must be a function of the election and the voter's admitted status in it — never of ambient context.*

## 3 · The three repair options — evaluated, NONE chosen

| | **Option A — repair the two confirmed sites** | **Option B — Election-owned voting-time access pattern** | **Option C — broader infrastructure remediation** |
|---|---|---|---|
| Content | fix `isVoterInElection()` + the `ElectionVotingController` projection; `BelongsToTenant` mechanism untouched | the Election context explicitly resolves its own organisational scope for voting-time entitlement — no reliance on *scope + remembered bypass* | rule on whether unconditional `BelongsToTenant` on election-keyed models is itself structurally inappropriate |
| Expresses Decision A… | at the two defect sites only | **architecturally** — *Election → entitlement → Election-owned scope → no ambient dependency* replaces *ambient tenant → global scope → hope the caller remembered the bypass* | at the mechanism level, family-wide |
| Size / risk | smallest; bounded; testable against the P6 A/B | larger; touches the voting-time read path; **WHERE it lives is an architecture decision, not stack-trace-derived** | largest; crosses bounded contexts (9 class-A models; 19 models outside Decision A); **must NOT be smuggled into 65/69** |
| What it leaves open | the mechanism smell persists (recorded risk); `voter_count` + `has_voters` + 375-bypass fragility untouched unless separately included | same residuals minus the voting-path fragility | class-D/E semantics need their own contexts' rulings first |
| Prerequisite for a grant | none further — evidence complete | an architecture design decision (home + shape) recorded first | its own programme decision; audit evidence *considers* it, does not authorize it |
| Composability | **A now + B later is coherent** (A cites B as recorded follow-up intent) | can follow A without waste (A's tests survive as regression) | independent track |

**Principal-Architect recommendation on record (not a ruling):** do not choose B or C yet; disposition around the two confirmed violations first.

## 4 · Adjacent findings needing their own rulings *(not silently swept into any option)*

1. **`voter_count` cache** — same pattern, static evidence only. Sub-question: include in the A-grant explicitly, or leave for the pattern track?
2. **`has_voters` hybrid** — lifecycle concern, outside Decision A. **Relevance flag (evidence, not a decision):** it gates `complete_administration` on the Election-Only critical path, and the **adopted `EM-VOT-003` voter-half will make voter presence constitutionally significant at the voting boundary too** — when EM-VOT-003's implementation is designed, the *same ambient-context question* arises there. Sub-question: rule on it with the voting-time repair (shared mechanism), or as its own item (different boundary)?
3. **Cache rule candidate** — *"tenant-dependent answer ⇒ tenant-qualified key"*: two instances now exist; **meets ES-006.1's threshold for a PKS OBSERVATION, not promotion.** Recordable on request.
4. **375-bypass fragility** — recorded architectural smell; a per-site audit or fitness rule would be its own commissioned work.
5. **Class D/E consumers** — explicitly outside this programme's authority; their contexts' governance, if ever.

## 5 · Grant template — Option A, PREPARED AND EXPLICITLY UNSIGNED *(EM-VOT-002 package precedent)*

> **☐ NOT GRANTED.** If the PO selects Option A, this becomes the grant by one signature; until then it authorizes nothing.
>
> **Scope:** restore Decision-A-conformant behaviour at exactly two sites — `User::isVoterInElection()` (`User.php:315-328`, including its cache key/behaviour) and the `isEligible`/`canVote` projection (`ElectionVotingController.php:37-44`). **The mechanism of conformance is Session 3's design question under strict TDD** — the grant prescribes the invariant (*same voter + same election ⇒ same answer, independent of ambient organisation/tenant context and of cache state across contexts*), not the technique.
> **Test obligation:** RED first; tests cite **Decision A** and `PBDIGIT-65`/`69`; the P6 three-step A/B (wrong tenant/cold · correct tenant/warm · correct tenant/cleared) becomes the acceptance scenario and must pass with a single consistent answer.
> **Out of scope:** `BelongsToTenant` itself · cache keys beyond site 1 · `voter_count` (unless the PO's signature says otherwise per §4.1) · `has_voters` · `EM-VOT-003` implementation · EM-OPEN-021 · class B–F consumers · any refactor of the 375 bypass sites.
> **Verification:** Session 1 verifies independently; Session 3 does not self-certify. Baseline `SD-1` = 1,376 respected.

Options B and C have **no draftable grant yet** — B needs a recorded architecture design decision (home + shape), C needs its own programme ruling.

## 6 · Questions for the PO *(the disposition, unbundled)*

1. **Which option** — A / B / C / a composition (e.g., "A now; B as recorded follow-up intent")?
2. **`voter_count`** — inside the A-grant or left to the pattern track?
3. **`has_voters`** — ruled with this repair, or its own item?
4. **The PKS cache observation** — record it (observation only)?
5. **On A: sign the §5 template** (or amend, then sign).

## 7 · Authorization state *(unchanged by this package)*

```
Active implementation grants:  NONE          Decision A: CLOSED        Decision B: COMPLETED (audit delivered)
PBDIGIT-65/69:                 CONFIRMED DEFECTS · NOT AUTHORIZED      EM-VOT-002: CLOSED — not reopened
EM-VOT-003:                    ADOPTED — implementation pending its own grant
EM-OPEN-021:                   OPEN — independent, evidence complete for deciding
Sessions 1 / 3 / 4:            STOPPED
```

**Traceability:** family audit (`9cf441fc`) · audit charter (PO-authored, verbatim) · P6 (`fc86049f`) · Decision A acceptance + ADR-002 annotation (`dc105c5d`) · Decision B ruling (§5e, `23b6becd`) · `EM-VOT-003` (Manifesto §4a) · decision package §§5a–5e · `PBDIGIT-65`/`69` tickets.
