# `PBDIGIT-74` — **FINAL RESEARCH REPORT**
### Election-audience recipient resolution: investigation complete, implementation decision-blocked

**Date:** 2026-09-30 · **Epic:** `PBDIGIT-EPIC-05` Member Communication
**By:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

> Statements are classified **FACT · VERIFIED · FALSIFIED · INFERENCE · HYPOTHESIS · GAP · DECISION REQUIRED · NOT AUTHORIZED**. The categories are not blurred.

---

## 1 · Executive summary

**VERIFIED** — The election-audience feature has **never worked**. From its original commit (`0bad829f3`, 2026-04-18) until 2026-09-30 its queries referenced `members.user_id`, a column that has never existed in any migration, so they could not execute. The 2026-09-30 change repaired that join correctly and thereby **exposed a second, deeper defect**: `users.organisation_id = sender` excludes legitimate election participants.

**VERIFIED** — The defect is **production-reachable**, not fixture-shaped.

**GAP** — The repository contains **no authoritative rule** stating which relation entitles a person to receive communication about an election, nor which relation establishes that the election belongs to the sending organisation.

**DECISION REQUIRED** — Two logically distinct PO/ARB decisions (§17). **NOT AUTHORIZED** — no implementation.

## 2 · Original question

> Why does election notification select zero recipients where eligible recipients exist?

## 3 · Initial hypotheses

| # | Hypothesis | Outcome |
|---|---|---|
| H1 | A regression from a previously verified state (`PBDIGIT-61` green) | **FALSIFIED** — §6 |
| H2 | Caused by the 2026-09-30 join change | **FALSIFIED** — §6 |
| H3 | A test/fixture mismatch, not production behaviour | **FALSIFIED** — §7 |
| H4 | A one-line fix exists once the cause is known | **FALSIFIED** — §11, §17 |

## 4 · Evidence reconstruction

**FACT** — `members` columns: `id, organisation_id, organisation_user_id, membership_number, status, joined_at, membership_expires_at, last_renewed_at, created_by, updated_by`. **No `user_id`**, in the creating migration or any later `Schema::table('members', …)`. `Member::user()` reaches `User` via `hasOneThrough(OrganisationUser)` precisely because no direct FK exists.

**FACT** — Today's eliminating predicate is `->where('users.organisation_id', $organisation->id)`, present in **all 7** election audiences and in `queryOrgAdmins`, `queryOrgParticipantsStaff`, `queryOrgParticipantsGuests` — **10 of 11 user-scoped queries**; the 4 member audiences scope via `members.*` instead.

**FACT** — `elections.organisation_id` and `election_memberships.organisation_id` both exist. `NewsletterService` references neither; it imports `Election` and never uses it.

## 5 · Falsification pass

An adversarial fresh-context reviewer was commissioned to refute the eight load-bearing claims. **Every material finding was then re-verified from source before being recorded here.** ⚠️ **The reviewer is technical falsification, NOT an independent governance lane.**

Claims 1, 2, 3, 5 **PROVED**; 6, 7, 8 **STRONGLY SUPPORTED**; Claim 4's *conclusion* supported but its *mechanism* refuted. **One reviewer claim I refuted myself** (§6).

## 6 · Corrected findings

| | Correction | Status |
|---|---|---|
| **A** | *"Regression from a verified state."* `PBDIGIT-61`'s five green suites are named at its own line 90; **`NewsletterAudienceSegmentationTest` is absent.** The file has exactly two commits, both 2026-04-18. | **FALSIFIED** |
| **B** | *"The 2026-09-30 change is not causal."* It is causal for the **failure signature** — it fixed a hard SQL error and revealed the exclusion beneath it. Not causal for the **exclusion**. | **FALSIFIED as originally stated** |
| **C** | *"`UserFactory` matches the platform org by slug."* The key is **`type + is_default`**; name/slug are creation values only. The *effect* holds; the mechanism did not. | **FALSIFIED (mechanism)** |
| **D** | Reviewer claim that `queryOrgParticipantsStaff/Guests` omit the predicate, so *"it is not a considered rule."* **All 10 user-scoped queries carry it** — a uniform pattern. | **REVIEWER CLAIM REFUTED** |
| **E** | *"Occurs in test conditions."* | **FALSIFIED** — §7 |

## 7 · Production reachability — **VERIFIED**

Two import paths assign `users.organisation_id` differently, and **both key `firstOrCreate` on email alone**:

| Path | Assigns |
|---|---|
| `VoterImportService:268-277` | `$organisation->id` — the **tenant** |
| `OrganisationUserImportService:318-327` | `$this->platformOrg?->id` — the **platform** org |

**INFERENCE (strong)** — Whichever importer first encounters an email address permanently fixes `users.organisation_id`. A person onboarded through the organisation-user importer and later enrolled in an election retains `organisation_id = platform` and is **excluded from every election newsletter in production**.

**FACT** — `organisation_users` permits a user in N organisations (`unique(organisation_id,user_id)`); `users.organisation_id` names exactly one. A genuinely multi-organisation participant is therefore **structurally unreachable** by at least one organisation's election newsletters.

## 8 · Organisation-relationship model — **FACT**, five distinct relations

```
R1  (u,g) := users.organisation_id = g                 account / identity-record ownership (NOT NULL, single-valued)
R1' (u,g) := active organisation_users row             organisation participation (role, status, joined_at; multi-valued)
R1''(u,g) := members row via organisation_user_id      organisation membership        ← PBDIGIT-68's subject
R2  (u,e) := active election_memberships row           election participation (carries its OWN organisation_id)
E   (e,g) := elections.organisation_id = g             the election's owning organisation
R4  (u,e,a) := eligible for audience a of election e   ← UNDEFINED, UNOWNED
```

**Implementation encodes** `R4 ≡ R2 ∧ role ∧ active ∧ R1(u,sender) ∧ ¬unsub ∧ ¬bounced` — and notably **not** `E(e,sender)`.
**Tests encode** `R4 ≡ R2 ∧ role ∧ active`.
**`VoterEligibilityService` uses `R1'`** (`:62-71`, Election-Only) — `users.organisation_id` appears **nowhere** in that file. **VERIFIED: two application services in one codebase establish organisation participation by different relations.**
**`PBDIGIT-68` ruled on `R1''`** — and **zero** matches for communication/recipient/notification in that ticket.

**GAP** — `ADR-MP-01` declares messaging a Generic Technical Subdomain holding *"no business decisions"*. **The nearest authority explicitly disclaims the recipient question, and nothing assigns it elsewhere.**

## 9 · `PBDIGIT-75` security finding — **VERIFIED**

`authorizeNewsletterAccess()` (`:432-452`) never verifies `elections.organisation_id === $org->id`: the admin/owner branch returns without inspecting `$electionId`; the officer branch checks only `ElectionOfficer.election_id`. Validation is bare `exists:elections,id` throughout.

**VERIFIED** — `users.organisation_id` is currently the **only** barrier to resolving another organisation's election participants (no policy, no Gate, no `authorize()`, `BelongsToTenant` bypassed by `withoutGlobalScopes()`).
⇒ **This is a constraint on any fix, not a live recipient leak today.** **The two tickets are coupled and must not be implemented independently.**

## 10 · Unsubscribe finding — **VERIFIED, recorded as a follow-up only**

Member audiences filter `members.newsletter_unsubscribed_at`; election audiences filter `users.newsletter_unsubscribed_at`; the only writer in the codebase is `NewsletterUnsubscribeController:17`, which updates **`members.*`**. **Nothing writes `users.newsletter_unsubscribed_at`.**

⇒ A public opt-out **does not suppress election newsletters**, and a non-member election participant has no unsubscribe path (the controller resolves a `Member` by token). **NOT AUTHORIZED** — recorded in `PBDIGIT-74` §10.4 as a clearly marked follow-up; **not investigated further, not implemented, not merged into `PBDIGIT-74`.**

## 11 · Formal contradiction — **VERIFIED** (an observation, *not* a theorem)

Three **current in-tree artifacts** are jointly unsatisfiable:

1. `queryElectionVoters` requires `R1(u, sender)`
2. `test_non_member_election_participant_receives_newsletter_without_member_id` requires `¬R1'' ∧ R2 ⊨ R4`
3. `UserFactory` guarantees `R1(u, platform)`, and `platform ≠ sender`

⇒ **The inconsistency needs no contradictory documentation.** It is internal to code + tests + fixtures. ⚠️ **Not a universal theorem — a finding about this artifact set.**

## 12 · `E/O/M/A` result

**HYPOTHESIS, partly falsified.** The four dimensions **survived** as useful classifiers of this case (E ✅ O ✅ **M ❌** A ✅ for investigation only). **Their sufficiency is FALSIFIED** — §13.

## 13 · `H` = authority-of-source — **HYPOTHESIS, justified by one counterexample**

Counterexample pair, both from this ticket:

- `x` = `users.organisation_id` — E ✅ O ✅ **M ✅** (a clear meaning: account ownership) A ✅
- `y` = `election_memberships.organisation_id` — E ✅ O ✅ M ✅ A ✅

**They agree on all four dimensions yet differ materially in semantic correctness**, because only `y`'s source is authoritative for *"does this person participate in this organisation's election?"*

⇒ **The defect is a category error, not a missing meaning.** `H` meets the stated minimality criterion: `∃x,y: state_EOMA(x)=state_EOMA(y) ∧ classification(x)≠classification(y)`.

⚠️ **NOT universally proven — supported by this single counterexample.** `K` (observability) and `V` (validity/currency) were tested against the same criterion and **did not meet it**; that negative finding is preserved. **No further dimension is introduced.**

## 14 · Scope-Generalization Error — **research observation only**

> **Extending a proposition beyond the population, branch, artifact set, or authority scope its evidence actually established.**

Observed five times in this sequence: `PBDIGIT-61`'s green-suite scope · UC-4 authorization/hold interpretation · `RecordedOperationalStatus` availability · `M-7` branch-dependency inference · `PBDIGIT-74` production reachability. Each began with a **true local observation** and ended with a conclusion broader than its evidence.

⚠️ **NOT a methodology rule, NOT adopted, NOT promoted.** The methodology is frozen; promotion would need independent adoption (`ES-006.1`).

## 15 · What has been falsified

H1 regression · H2 the 2026-09-30 change as cause of the exclusion · H3 test-only · H4 one-line fix · the `UserFactory`-by-slug mechanism · one reviewer claim (uniform predicate) · **`E/O/M/A` sufficiency** · and the earlier corollary *"Goal A blockers are semantic/obtainability/authorization while Goal B blockers are existence/engineering"* — **this does not survive**; the correct conclusion is that **the dimensions may classify blockers, but blocker distribution cannot be inferred from Goal A versus Goal B.** An important **negative result**, not a new rule.

## 16 · What remains unknown

**GAP** — Who owns `R4`. **GAP** — Which relation establishes `E(e,g)` for communication purposes. **GAP** — Whether opt-out semantics for non-member participants exist at all (§10). **UNVERIFIED (reviewer-reported, not re-checked):** `previewCount`'s `name`/`email` sample exposure · `BelongsToTenant` platform-slug mismatch · `exists:elections,id` and soft deletes · one test whose name does not match what it exercises.

## 17 · Exact PO/ARB decisions required — **DECISION REQUIRED**

> ### Decision A — Communication eligibility
> **Which relation establishes that a person is entitled to receive communication concerning a specific election?**

> ### Decision B — Election tenancy
> **Which relation establishes that an election belongs to the organisation sending the communication?**

**Related but logically distinct.** Both are currently unanswered.

**Evidence, not a recommendation:** an implementation using `election_memberships.organisation_id` plus an explicit `elections.organisation_id = sending organisation` check **is already expressible with the current schema**. ⛔ **This is evidence that the schema may already support a solution. It is NOT a recommendation, NOT a selection, and NOT authorization to choose it.**

## 18 · Implementation prerequisites

1. Decision A recorded. 2. Decision B recorded. 3. Explicit implementation authorization for `PBDIGIT-74`. 4. `PBDIGIT-75` addressed **jointly** — relaxing recipient scoping without securing election ownership could create cross-organisation exposure. 5. Opt-out semantics (§10) treated separately.

## 19 · Explicit STOP state

### Research state
**COMPLETE**

### Implementation state
**BLOCKED — HUMAN DECISION REQUIRED**

### Next actor
**PO/ARB**

### Decisions required
1. communication eligibility authority
2. election-tenancy authority

### Coupled ticket
**PBDIGIT-75**

### No implementation performed
**CONFIRMED** — no production code, no test, no fixture, no migration, no ADR, no Goal-A governance state changed.

**Traceability:** `PBDIGIT-74` §§1-10 · `PBDIGIT-75` · `PBDIGIT-61:90` · `PBDIGIT-68` · `ADR-MP-01` · `NewsletterService.php` (7 election audiences, `:144/:156/:169/:182`, `:203`, `:444`) · `OrganisationNewsletterController.php:124-129, :212-218, :260, :432-452` · `VoterEligibilityService.php:62-71` · `VoterImportService.php:268-277` · `OrganisationUserImportService.php:318-327` · `UserFactory.php:37-42` · `NewsletterUnsubscribeController.php:17` · `Member.php:33, :57-66` · `ElectionMembership.php:32` · commits `0bad829f3`, `c2f899b1d`, `a72528b29`
