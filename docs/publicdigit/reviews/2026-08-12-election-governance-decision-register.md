# Election governance decision register

**Type:** Decision register · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2, governance support)
**Contents:** **only unresolved business/architectural decisions.** Resolved items are listed once, in §1, and not re-opened.
**⛔ Nothing here is an implementation ticket.** No production code, test, fixture, migration, schema, ADR or Constitution change. **Session 1's Master Matrix and its potentially-affected population were not read, used, classified or modified.**

---

## ARB decision summary — usable independently of Session 1

| ID | Decision | Owner | Recommendation |
|---|---|---|---|
| **`D-APPLY`** | Apply the `ADR-002` amendment? | ARB / PO | — |
| **`D-APPLY-1`** | Apply now, or ratify termination first? | ARB / PO | **Ratify `BR-1.1` first** |
| **`D-APPLY-2`** | *revocation* vs *termination/removal* | ARB / PO | **Restate as "termination/removal"** |
| **`V-3`** | Record vs decision clarification | ARB | **Accept** |
| **`BR-1.1`/`1.2`** | What termination means; reversibility | PO | **Option B** *(retain record; permanent to officers; admin-reversible)* |
| **`Q3`** | How exercisability is represented | ARB | *(4 candidates; none selected)* |
| **`Q-E1`** | Credential issuance/revocation under suspension | PO + security | **Decide — today neither happens** |
| **`Q-E2`** | Suspended distinguishable from already-voted? | PO | **Yes** *(evidence-led)* |
| **`BR-1.13`** | Which suspension path is intended | PO | *(no recommendation — both are in use)* |
| **`BR-1.12`** | Is the `invited` approval gate real? | PO | *(no recommendation)* |
| **`BR-1.5`/`1.6`** | Mandatory reason; unconditional audit | PO | **Both yes** *(org side already requires them)* |
| **`Q-D1`** | Disposition of `scopeEligible()` | ARB → eng | **Do not activate** |
| **`G-REC`** | Officer Guide section-level recognition | ARB | **`D` — do not promote** |
| **`G-PUB`** | 🔴 May a published result be unpublished? | ARB | *(new; no recommendation)* |

**Three of these are load-bearing for the others:** **`D-APPLY-2`** *(a term the amendment forbids appears in the adopted rule)*, **`BR-1.1`** *(`Q3` and the amendment both reference it)*, and **`Q3`** *(no repair may proceed without it)*.

---

## 0 · ⚠️ SUPERSEDING REFINEMENT — `D-ENT-2` (2026-08-12)

**The Product Owner refined the hierarchy after this register was written. See [`ARB package — membership hierarchy refinement`](2026-08-12-arb-package-membership-hierarchy-refinement.md). Entries below are read subject to it.**

> **Full Membership: Organisation Membership is superior; its removal AUTOMATICALLY SUSPENDS the ElectionMembership — distinct from Chief suspension. Removing ElectionMembership does not remove Organisation Membership. Election-Only: no Organisation Membership required.**

**Reconciliation:** organisation membership is **not** continuously required for the entitlement to **EXIST** (`F1` and `A-3` survive) but **IS** continuously required, in Full Membership mode, for it to be **EXERCISABLE** (new). **`Q-A0`'s recorded answer was too coarse and is restated accordingly.**

**Mechanism — VERIFIED at both layers: there is no organisation→election trigger.** No event subscription, and **no foreign key** — measured: `election_memberships` has FKs only to `users` (`SET NULL`) and `elections` (`CASCADE`). The organisation side already emits `MembershipSuspended` / `MembershipTerminated` / `MembershipRestored`; **nothing subscribes.** So **`D-ENT-2` has never operated and currently has an empty domain of application** (`members` = 0 rows).

**Register changes:**

* **`BR-1.1` now spans three transitions** — organisation-driven automatic · Chief · removal.
* **`Q3`'s blast radius grows** — exercisability must compose a cause whose actor is **not an election officer**.
* **`Q-D1`'s recommendation stands, its REASON withdrawn** — it no longer "contradicts the adopted rule"; the objection is that it is the **wrong mechanism** and would return **0/20**.
* **New `W-1`…`W-7`** business-workflow questions — asked, not answered; **`W-7` first**.
* **New `FK-1`** — the declared organisation FK is **absent from the live schema**, so the linkage guarantee is **unenforced**. *(This corrects earlier statements of mine that "the FK enforces a role linkage"; `R7` in the Officer Guide analysis is reclassified `SUPPORTED` → `UNSUPPORTED`.)*
* **New `FK-2`** — deleting an election **hard-deletes** its membership records and their audit metadata, bypassing `SoftDeletes`. Recorded, not investigated.

---

## 1 · Resolved — recorded once, not re-opened

`D-ENT-1` ✅ resolved · `Q-A0` ✅ = `F1` · Model B ✅ adopted · `A-1`…`A-6` ✅ adopted business rules · Officer Guide governance **status** ✅ assessed as `D` *(the recognition decision `G-REC` remains open)*.

---

## 2 · The register

### `BR-1.1` / `BR-1.2` — Termination semantics

| Field | Content |
|---|---|
| **Question** | What does officer "removal" of an `ElectionMembership` mean, and is it reversible? |
| **Why it matters** | The adopted rule says the entitlement persists *"unless a defined election-level termination rule terminates it"* — **so without this, the adopted rule is incomplete and drifts to an unconditional right.** |
| **Authoritative evidence** | `ADR-003` (consequences are governance decisions; past votes not auto-invalidated) · `ADR-T11` (no post-vote reach) · Membership context `TERMINATED` = terminal, mandatory actor + reason |
| **Observed implementation** | `remove()` retains the row + actor/reason/time; **no delete**; `SoftDeletes` unused; row lock guards a live vote |
| **Conflict / ambiguity** | Guide says *"permanent"*, but `approve()` has **no `removed` guard**; a second guide statement says an administrator may *"restore the membership record"* |
| **Decision owner** | **Product Owner** |
| **Blast radius** | The `ADR-002` amendment (`V-1`), `Q3`, removal/restore paths, audit obligations |
| **Options** | **A** entitlement ceases permanently · **B** record retained, exercisability withdrawn, admin-reversible |
| **Recommendation** | **B** — every layer already behaves this way, and **A would retroactively change the denominator of a possibly-published result** |
| **ARB decision required** | ✅ **Yes — and first** |

### `Q3` — Representation of exercisability

| Field | Content |
|---|---|
| **Question** | What domain concept represents *"may this entitlement be exercised now?"*, and where does a suspension decision live? |
| **Why it matters** | **Today `status='inactive'` is the ONLY thing preventing a suspended voter from voting.** Any clean-up that vacates it without a replacement silently removes the Chief's authority. |
| **Authoritative evidence** | `ElectionConstitution:126-128` — suspension *"freezes capabilities only… does NOT mutate business facts"* |
| **Observed implementation** | No capability column exists on `election_memberships`; `can_vote_now` lives on a **30-minute, voter-refreshable** credential. **Election-level suspension is a timestamped fact (`suspended_at`) with engine-derived state** |
| **Conflict** | The voter level violates the constitutional principle **because there is nothing to freeze** |
| **Decision owner** | **ARB** |
| **Blast radius** | Every voting-path predicate; the `status` semantics; audit; test coverage |
| **Options** | **A** capability field on the entitlement · **B** separate governance record · **C** decision/event log · **D** voter-level overlay mirroring the in-force election-level one |
| **Recommendation** | **None selected.** `D` is closest to the in-force pattern — **and was the original architecture proposal, rejected on availability grounds** *(`Voterlist.md` specified `suspended_at`/`suspended_by`; the recorded decision was "None exist → used existing `status`")* |
| **ARB decision required** | ✅ **Yes — no repair may precede it** |

### `Q-E1` — Credential issuance / revocation under suspension

| Field | Content |
|---|---|
| **Question** | Should suspension prevent a credential being issued, and invalidate one already issued? |
| **Why it matters** | **The system currently issues an instrument it will not honour.** |
| **Authoritative evidence** | **None** — credential control is unspecified as a business rule |
| **Observed implementation** | 🔴 A confirmed-suspended voter **was issued a fresh `VoterSlug`** (runtime); **no suspension/removal path touches `VoterSlug` or `Code`** |
| **Conflict** | Coherence, not contradiction: the ballot is refused while the credential is granted |
| **Decision owner** | **Product Owner + security** |
| **Blast radius** | Credential lifecycle; suspension paths; possibly `Q3` |
| **Options** | refuse issuance · revoke on suspension · both · neither *(status quo, documented as intentional)* |
| **Recommendation** | **Decide explicitly.** Silence here is what produced the incoherence |
| **ARB decision required** | ✅ Yes |

### `Q-E2` — Suspended vs already-voted

| Field | Content |
|---|---|
| **Question** | Must a suspended voter be distinguishable from one who has voted, **at the enforcing gate**? |
| **Why it matters** | Two facts with **different authors** — officer vs voter — share one representation |
| **Authoritative evidence** | Adopted `A-2` implies they are different facts |
| **Observed implementation** | Both are `status='inactive'`; `isVoterInElection()` reads only `role` + `status`; **`markAsVoted()` also writes `inactive`** |
| **Conflict** | 2 of 5 required distinguishability cases fail |
| **Decision owner** | **Product Owner** |
| **Blast radius** | `status` semantics; error messaging; `Q3` |
| **Options** | distinguishable at the gate · distinguishable only in the record · not required |
| **Recommendation** | **Yes, at the gate** — a gate that cannot tell a governance act from a voter act cannot report or audit either correctly |
| **ARB decision required** | ✅ Yes |

### `BR-1.13` — Which suspension path is intended

| Field | Content |
|---|---|
| **Question** | Two mechanisms exist — a **two-actor** propose/confirm flow and a **single-actor** `suspend`. Which is the intended control? |
| **Why it matters** | **While both exist, "four-eyes protects the franchise" is not true** — the outcome is reachable single-handedly |
| **Authoritative evidence** | **None** |
| **Observed implementation** | Both routes live. **Tests endorse both.** The Officer Guide documents **only the single-actor button**; the four-eyes flow is **undocumented** |
| **Conflict** | Test-level and documentation-level endorsement of different controls |
| **Decision owner** | **Product Owner** |
| **Blast radius** | Suspension routes, tests, the guide, audit |
| **Options** | four-eyes only · single-actor only · both, with different meanings |
| **Recommendation** | **None** — this is a governance-strength choice, not a technical one |
| **ARB decision required** | ✅ Yes |

### `BR-1.5` / `BR-1.6` — Mandatory reason; unconditional audit

| Field | Content |
|---|---|
| **Question** | Must every act changing exercisability or terminating an entitlement record a reason, and be audited unconditionally? |
| **Why it matters** | A franchise change with no recorded reason or actor cannot be adjudicated |
| **Authoritative evidence** | Membership context **requires both** — an empty reason **throws** |
| **Observed implementation** | 🔴 **4 of 6 acts write no audit**; none reaches the **election's own** audit trail, though `ElectionAuditService` defaults to category `'voters'`; removal's log is **conditional on a legacy column**; no domain events. **The original architecture document specified suspension audit logging — it was dropped** |
| **Conflict** | Election side is weaker than the organisation side for the same class of act |
| **Decision owner** | **Product Owner** |
| **Blast radius** | All six governance operations |
| **Options** | mandatory both · audit only · status quo |
| **Recommendation** | **Both mandatory** — parity with the organisation side, and the facility already exists unused |
| **ARB decision required** | ✅ Yes |

### `BR-1.12` — Is the `invited` approval gate real?

| Field | Content |
|---|---|
| **Question** | Is `invited` an intended business state with an approval gate, or should it be removed? |
| **Why it matters** | An approval gate that four layers assume but production never creates |
| **Authoritative evidence** | **None** |
| **Observed implementation** | **Schema ✅ · UI ✅** *(own status pill, label, `invited → active` action)* **· Tests ✅** *(`makeMembership('invited')`)* **· Docs ✅** *(twice)* **· production writer ❌** |
| **Conflict** | 🔴 Documentation and UI describe a gate that does not exist |
| **Decision owner** | **Product Owner** |
| **Blast radius** | Assignment/import paths; the guide; UI; tests |
| **Options** | implement the gate · remove the state · keep as display-only |
| **Recommendation** | **None** |
| **ARB decision required** | ✅ Yes |

### `Q-D1` — Disposition of `scopeEligible()`

| Field | Content |
|---|---|
| **Question** | Retire, re-scope or fix the dormant query that implements the **opposite** of `F1`? |
| **Why it matters** | It requires an active `Member` row as a **voting-time** condition — the model `Q-A0` rejected |
| **Authoritative evidence** | `Q-A0` = `F1` |
| **Observed implementation** | **0 callers**; returns **0 of 20** rows because `members` is empty |
| **Conflict** | Direct contradiction with the adopted decision |
| **Decision owner** | **ARB → engineering** |
| **Blast radius** | Nil today *(dormant)*; **catastrophic if ever wired** — it would deny every voter |
| **Options** | delete · re-scope to admission-time · leave dormant with a warning |
| **Recommendation** | **Do not activate under any circumstances.** Disposition is a separate authorised slice |
| **ARB decision required** | ✅ Yes |

### `G-REC` — Officer Guide recognition

| Field | Content |
|---|---|
| **Question** | Grant section-level governance weight to the Officer Guide? |
| **Why it matters** | It is the only written statement of restoration, administrative reversal and the officer-pending state |
| **Authoritative evidence** | **It cites none** — zero authority references across all seven files |
| **Observed implementation** | No knowledge card · not a registered root · cited by nothing · one commit, no steward, no change control |
| **Conflict** | Three contradictions: `invited` · suspension-as-undo · publish/unpublish |
| **Decision owner** | **ARB** |
| **Blast radius** | Governance layering; documentation change control |
| **Options** | recognise `SUPPORTED` statements as operational expression · recognise nothing · defer until the underlying rules are ratified |
| **Recommendation** | **`D` — do not promote.** Recognition would settle **zero** rules by itself; every one still needs ratification |
| **ARB decision required** | ✅ Yes |

### `G-PUB` — 🔴 NEW: may a published result be unpublished?

| Field | Content |
|---|---|
| **Question** | Is publication of results reversible? |
| **Why it matters** | **Results integrity.** Officers are told they may publish and unpublish **at will** |
| **Authoritative evidence** | 🔴 **The Constitution has NO `unpublish` action.** The only action from `results_published` is `archive` |
| **Observed implementation** | An artisan console command `election:unpublish-results`; the guide documents an *"Unpublish Results"* **button** and states *"you can publish and unpublish as many times as needed"* |
| **Conflict** | 🔴 **Guide asserts a reversible publication cycle the Constitution does not authorise** |
| **Decision owner** | **ARB / Product Owner** |
| **Blast radius** | Results publication, archival, audit, voter-visible outcome |
| **Options** | authorise a constitutional `unpublish` · forbid it and remove the affordance · restrict to platform admin with audit |
| **Recommendation** | **None — outside `D-ENT-1` and outside this commission's scope.** **Raised, deliberately not investigated.** |
| **ARB decision required** | ✅ Yes |

---

## 3 · Implementation findings — **FINDINGS ONLY, never business rules**

**Consolidated so ARB is not surprised by them. None is authorised for repair, and none may be read as a rule.**

| # | Finding | Concern |
|---|---|---|
| 1 | Suspension writes `status='inactive'` — the entitlement's own field | `Q3` |
| 2 | Suspended and already-voted indistinguishable at the enforcing predicate | `Q-E2` |
| 3 | A suspended voter is issued a fresh credential; nothing revokes an existing one | `Q-E1` |
| 4 | Enforcement of suspension is **incidental** — nothing reads `suspension_status` | `Q3` |
| 5 | *"Permanent"* removal unenforced — `approve()` lacks a `removed` guard | `BR-1.1` |
| 6 | 4 of 6 governance acts unaudited; none reaches the election's audit trail; no domain events | `BR-1.6` |
| 7 | `invited` expected by schema/UI/tests/docs, written by no production path | `BR-1.12` |
| 8 | `scopeEligible()` contradicts `F1`; 0 callers; 0/20 | `Q-D1` |
| 9 | **No test asserts a suspended voter cannot vote** | coverage |
| 10 | **Full Membership never exercised** — `members` 0 rows | bounds all conclusions |

## 4 · Boundaries

* **Separation:** Session 1 is classifying its potentially-affected population by test intent. **Session 2 has not duplicated, influenced, read or modified that work**, and cites **no** row count as fact. *(Note: the figure quoted to me has changed between messages — 213, then 39. I have not investigated it; it is Session 1's datum, and nothing here depends on it.)*
* **No implementation:** no production code, test, fixture, migration, schema change, ADR amendment, Constitution amendment, or data cleanup.
* **Runtime residue untouched**, as directed: IERVP test data · working-organisation restoration · NULL `voter_source_strategy` backfill · another author's `ElectionUser.php` · `PBDIGIT-70` / `VoterSlugStep.php`.
* **`ADR-T11` preserved** — no proposed mechanism identifies or mutates a cast ballot.
* **`PBDIGIT-69`** cited nowhere.

**Related:** [`ARB package V-1/V-2/V-3`](2026-08-12-arb-package-adr-002-v1-v2-v3.md) · [`Officer Guide governance-source analysis`](2026-08-12-officer-guide-governance-source-analysis.md) · [`Session-1 handover`](2026-08-12-governance-handover-session2-to-session1.md) · `PBDIGIT-68`
