# `D-ENT-1` two-mode domain decision report

**Type:** Domain decision investigation · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Report:** **`D-ENT-1` DOMAIN DECISION RESOLVED · `Q-A0` = `F1` · `ADR-002` AMENDMENT PROPOSED · IMPLEMENTATION NOT AUTHORISED · SESSION-1 MATRIX UNTOUCHED**
**Mode:** investigation only. **No production code, test, fixture, migration, schema, Constitution or ADR change. `ADR-002` untouched. Session 1's Master Matrix, `SD-4` and the 213 rows NOT read as authority, NOT classified, NOT modified.**

**Evidence classes:** `AUTHORITATIVE` · `PROPOSED BY PRODUCT OWNER` · `SUPPORTED BY EVIDENCE` · `OBSERVED IMPLEMENTATION BEHAVIOUR` · `UNSPECIFIED` · `CONTRADICTED` · `UNKNOWN`.
**No implementation behaviour is promoted to a business rule.** Model docblocks, `architecture_legacy/` documents, test names and field names are **not** treated as authority.

---

## ✅ `Q-A0` RESOLVED — `Q-A0 = F1` (Product Owner, 2026-08-12)

**The Product Owner made resolution conditional on verifying the recorded approval. Verification performed; it holds. `Q-A0` is closed as `F1`.**

> **ADOPTED BUSINESS RULE:** **In Full Membership mode, organisation membership is an admission prerequisite — NOT a continuously evaluated prerequisite for retaining the `ElectionMembership` entitlement.**
>
> **Not to be reinterpreted as continuous organisation-membership validation.**
> **Strictly separate from the Election Chief's suspension authority.**

### Verification — three independent recordings agree; the outlier has no earlier provenance

| # | Recording | Commit | What it says on continuity |
|---|---|---|---|
| 1 | `PBDIGIT-68` ruling block | **`fc53f78a`**, 2026-08-12 14:32 | **Explicit:** *"organisation membership is an **admission prerequisite** — **not** a continuously evaluated prerequisite for retaining the entitlement"* |
| 2 | Corrected wording, approval package §1 | **`3530a234`** | **Silent on organisation membership**, and constrains termination to *"a defined **election-level** revocation/removal rule"* — **so organisation-membership loss cannot terminate it.** Consistent with (1) **by implication, not restatement** |
| 3 | Approved rule list, governance package §1 | **`0fafdd50`** | **`A-3`: "Organisation membership changes do NOT automatically destroy the entitlement."** Recorded as an **APPROVED BUSINESS RULE** |

**And `F2`'s phrase *"continuously required remains to be established"* appears in NO repository artifact earlier than today's report** — it entered the record only when I transcribed this commission's framing. **It has no independent provenance.**

**Honest qualification about the nature of this evidence.** All three recordings are **my transcriptions of the Product Owner's conversational instructions**, committed under the repository owner's git identity — which is this session's configured committer, so **git authorship does not independently attest Product Owner authorship.** Authority rests on the repository's convention that the human is the Product Owner and decisions are recorded in tickets. **That convention makes it authoritative; it is not independent attestation, and I will not describe it as such.**

**Conclusion: `Q-A0 = F1`. The three recordings are consistent, none contradicts, and `F2` is the sole outlier with no earlier record.** The section below is **retained unchanged** as the record of the ambiguity as I found it.

---

## ~~Before anything else — two Product Owner formulations differ, and I have not chosen between them~~ *(SUPERSEDED by the resolution above; retained as the record)*

**This is the most consequential finding of the commission, and it is a governance matter, not a technical one.**

| | Formulation | On **Question A** |
|---|---|---|
| **F1** — approved 2026-08-12, recorded as binding in `PBDIGIT-68` | *"In Full Membership mode, organisation membership is an **admission prerequisite** — **not** a continuously evaluated prerequisite for retaining the election entitlement."* Corrected wording: *"remains associated with the election unless a defined election-level revocation/removal rule terminates it."* | **ANSWERED — not continuously required** |
| **F2** — this commission | *"organisation membership **remains relevant** to voting entitlement in Full Membership mode"*, and *"whether the organisation `Member` relationship is **continuously required remains to be established**."* | **DECLARED OPEN** |

> **`F1` decides Question A; `F2` reopens it.** *"Remains relevant"* is ambiguous between *relevant at admission* and *relevant throughout*.

**Why this must be settled before the ADR amendment:** the proposed amendment's whole substance is a new **Entitled** axis whose defining property is that it is **not** re-derived from organisation membership. **Under `F1` that axis is correct. Under `F2` it is premature.**

**I have not chosen.** Below, Question A is treated as **OPEN** per this commission's instruction, and every dependent statement is marked with which formulation it relies on. **`PRODUCT OWNER DECISION REQUIRED` (`Q-A0`).**

---

## The Product Owner's correction, adopted: two questions, never merged

| | Question | Scope | Owner |
|---|---|---|---|
| **A** | **Must a person remain an organisation `Member` throughout the election?** | Full Membership mode only | Organisation ↔ Election boundary |
| **B** | **May the Election Chief suspend the person's election-specific entitlement?** | **Both modes** | Election governance |

**They are independent.** A "yes" to B says nothing about A: an election-specific suspension power can exist whether or not organisation membership is continuously required. **Conflating them would make the Chief's authority look like a consequence of organisation status, which it is not.** `SUPPORTED BY EVIDENCE` — no suspension or removal path in the system branches on organisation membership or on `voter_source_strategy`.

---

## A · Current two-mode business model

```
FULL MEMBERSHIP MODE                     ELECTION-ONLY MODE
Organisation Member                      (no organisation Member required)
      │ admission prerequisite                     │
      ▼                                            ▼
   ElectionMembership  ◄──── same concept ────►  ElectionMembership
      │                                            │
      └──────────────► voter entitlement ◄─────────┘
                              │
                     Election governance
                    (Chief may suspend — Q.B)
                              │
                              ▼
                    may the voter exercise?
```

| Claim | Class |
|---|---|
| Two modes exist, snapshotted per election and immutable after creation | `AUTHORITATIVE` — `VoterSourceStrategy` |
| Election-Only draws from organisation enrolment; Full Membership from the `Member` table | `AUTHORITATIVE` (same source) |
| `Member` = organisation relationship; `ElectionMembership` = election relationship; **not collapsed** | `PROPOSED BY PRODUCT OWNER` + `SUPPORTED BY EVIDENCE` |
| One entitlement concept across both modes | `PROPOSED BY PRODUCT OWNER` |
| Full Membership has ever been exercised | `CONTRADICTED` — `members` has **0 rows**; no admission has occurred |

---

## B · Precise Model B formulation — each component proved or disproved

**The commission's candidate formulae, component by component. Nothing assumed.**

| Component | Full Membership | Election-Only | Verdict |
|---|---|---|---|
| **Organisation `Member` required** | ✅ **RESOLVED `Q-A0`=`F1`: required at ADMISSION ONLY**, not re-derived afterwards | **Not required** | FM: **`ADOPTED BUSINESS RULE`** · EO: `AUTHORITATIVE` |
| **`ElectionMembership` required** | yes | yes | `AUTHORITATIVE` — it is the entitlement record |
| **Election-specific active/suspended status** | yes | yes | `PROPOSED BY PRODUCT OWNER`; representation is `Q3` |
| **Lifecycle permits voting** | yes | yes | `AUTHORITATIVE` — `ElectionLifecycle::canVote()`, constitutionally owned |
| **Credential requirements** | yes | yes | `OBSERVED IMPLEMENTATION BEHAVIOUR` — `VoterSlug`/`Code`; **as a business rule `UNSPECIFIED`** |
| **Not already voted** | yes | yes | `OBSERVED IMPLEMENTATION BEHAVIOUR` (`has_voted`); business meaning `UNSPECIFIED` (`BR-1.11`) |
| **= may exercise vote** | — | — | **The conjunction itself is `PROPOSED BY PRODUCT OWNER`, not `AUTHORITATIVE`** |

> **Result: the two formulae are correct in shape and differ in exactly one row.** Every component except *"organisation `Member` required"* is identical across modes. **So the modes differ only at admission — which is the strongest available argument that `ElectionMembership` means the same thing in both.** `SUPPORTED BY EVIDENCE`.

**One component fails a business test and should not be accepted uncritically:** *"credential requirements"* is presented as a component of entitlement, but the credential is **voter-held and voter-refreshed** — `start()` issues or refreshes a `VoterSlug` on demand. **A condition the subject can satisfy at will is not an entitlement condition; it is an authentication step.** `INTERPRETATION`.

---

## C · What `ElectionMembership` owns — and does not

| Owns | Class |
|---|---|
| **That admission happened**, for this election | `AUTHORITATIVE` (approved) |
| **The identity of the entitlement** — person × election | `AUTHORITATIVE` |
| **Its own role** (`voter`, `candidate`, `observer`, …) | `OBSERVED` + `SUPPORTED BY EVIDENCE` |
| **Its own governance state** (suspended or not) | `PROPOSED BY PRODUCT OWNER` |

| Does **NOT** own | Why |
|---|---|
| **Whether the election permits voting now** | Election lifecycle — constitutionally owned |
| **Whether the person is an organisation `Member`** | Organisation context |
| **Credential possession** | `VoterSlug`/`Code`; `CodeController` even declares `can_vote_now` *"the single source of truth"* for its own concern |
| **The vote** | `ADR-T11` — no linkage exists, by construction |
| **Whether identity is verified** | Trust Attestation — `ADR-001`/`ADR-002` |
| **The consequences of an integrity failure** | Governance — `ADR-003` |

> 🔴 **The implementation violates this boundary today:** `markAsVoted()` writes `status='inactive'` onto the entitlement, so **the entitlement's own field records a fact about the vote** — a concern it does not own. `OBSERVED IMPLEMENTATION BEHAVIOUR`, **not** a business rule.

## D · What organisation `Member` owns

| Owns | Class |
|---|---|
| Whether the person belongs to the organisation | `AUTHORITATIVE` |
| Its own lifecycle — active / suspended / terminated, with mandatory actor and reason | `AUTHORITATIVE` in the Membership context |
| **Whether the person *may be admitted*** in Full Membership mode | `AUTHORITATIVE` — `VoterSourceStrategy` |
| **Whether an admitted voter *remains* entitled** | 🔴 **Question A — OPEN** (`Q-A0`) |

**Not owned:** election-specific suspension. `SUPPORTED BY EVIDENCE` — no suspension path touches organisation state, and none branches on mode.

---

## E · Suspension semantics — the nine questions answered explicitly

| # | Question | Answer | Class |
|---|---|---|---|
| 1 | Does it **revoke** `ElectionMembership`? | **No.** The row persists | `OBSERVED` + consistent with approved rule |
| 2 | Preserve the membership but **disable entitlement**? | **No** — the entitlement is not what should change | `PROPOSED BY PRODUCT OWNER` |
| 3 | **Disable exercisability only**? | **Yes — this is the approved meaning.** The implementation instead mutates `status`, the entitlement's own field | rule `PROPOSED`; behaviour `CONTRADICTED` |
| 4 | **Prevent credential issuance**? | 🔴 **NO.** Measured at runtime: a confirmed-suspended voter was **issued a fresh `VoterSlug`** | `OBSERVED IMPLEMENTATION BEHAVIOUR` (runtime) |
| 5 | **Invalidate an already-issued credential**? | 🔴 **NO.** **No suspension or removal path touches `VoterSlug` or `Code` at all** | `OBSERVED` |
| 6 | **Prevent ballot opening**? | **Yes** — HTTP 403 at `EnsureElectionVoter` on every real slug route | `OBSERVED` (runtime) |
| 7 | **Prevent ballot submission**? | **Yes, by shared route middleware** — not separately exercised, because step 1 already refuses | `SUPPORTED BY EVIDENCE` |
| 8 | **Affect audit history**? | **No** — history is never rewritten. But the acts themselves are barely recorded: 4 of 6 governance operations write **no** audit at all | `OBSERVED` |
| 9 | **Affect only this election**? | **Yes** — the entitlement is per-election by construction (unique person × election) | `AUTHORITATIVE` (schema) + approved |

> 🔑 **Answers 4 and 5 are the sharpest new result.** Suspension stops the ballot but **not the credential**. A suspended voter can still obtain and refresh a voting credential; they are refused only when they try to use it. **The system therefore issues an instrument it will not honour** — which is the same incoherence as the entry surface reporting `isEligible: true`. **Whether credential issuance *should* be refused is `UNSPECIFIED` (`Q-E1`).**

### The five distinguishability requirements

**A suspended voter must remain distinguishable from:**

| Must differ from | Distinguishable? | By what |
|---|---|---|
| person who is **not an `ElectionMembership`** | ✅ yes | absence of the row |
| person who is **not an organisation `Member`** | ⚠️ **not in practice** | the `Member` aggregate is **empty**; the FK proves only a role linkage |
| person whose **election has not reached voting** | ✅ yes | lifecycle state, separately computed |
| person who **has already voted** | 🔴 **NO at the enforcing predicate** | both are `status='inactive'`; `isVoterInElection()` reads only `role` + `status`. Distinguishable only by *also* reading `has_voted`/`suspension_status`, which it does not |
| person who **lacks credential possession** | ✅ yes | absence of a valid `VoterSlug` |

**Two of five fail. The consequential failure is *suspended vs already-voted*** — two facts with different authors (officer vs voter) sharing one representation. `OBSERVED IMPLEMENTATION BEHAVIOUR`.

---

## F · Entitlement vs eligibility vs exercisability

| Concept | Question | Owner | Durability |
|---|---|---|---|
| **Organisation membership** | belongs to the organisation? | Organisation | durable, own lifecycle |
| **Election membership** | admitted to this election? | Election | durable — the entitlement record |
| **Voter entitlement** | holds an election-specific right? | Election | durable until terminated |
| **Voter eligibility** | satisfies the conditions attached to that right? | **contested** — `ADR-002` says Eligibility context; runtime uses `isVoterInElection()` | evaluated |
| **Voter suspension** | has governance withdrawn exercise? | Election governance (Chief) | reversible |
| **Voting exercisability** | may act **now**? | derived | momentary |
| **Lifecycle permission** | does the election permit voting? | Election | phase-scoped |
| **Credential possession** | proved possession? | credential | session-scoped, **voter-refreshable** |
| **Has-voted state** | consumed? | Voting | irreversible (`ADR-T11`) |

> **Nine distinct concepts. `status` currently carries three of them** (entitlement existence, exercisability, consumption) **plus a fourth value (`invited`) that nothing produces.** `OBSERVED`.

---

## G · Full Membership lifecycle

**Nothing here is inferred from Election-Only behaviour.**

| Scenario | What authoritative artefacts say | Class |
|---|---|---|
| `Member` exists + `ElectionMembership` exists | may be admitted and may exercise | `AUTHORITATIVE` (admission) + `PROPOSED` (exercise) |
| `Member` does **not** exist + `ElectionMembership` exists | 🔴 **Question A.** `F1`: entitlement stands. `F2`: open. **A dormant query (`scopeEligible()`) implements the opposite** — it requires an active `Member` — with **0 callers**, returning **0 of 20** rows | `UNSPECIFIED` / `CONTRADICTED` by dormant code |
| `Member` existed at import, later ceases | **The core of Question A** | `UNSPECIFIED` (`Q-A0`) |
| `ElectionMembership` suspended | not exercisable; entitlement intact | `PROPOSED` |
| `ElectionMembership` removed | exercisability withdrawn; record retained with actor/reason/time | `OBSERVED`; as a rule `UNSPECIFIED` (`BR-1.1`) |

**Is organisation membership a continuing condition, or only a precondition?** **`UNSPECIFIED` — and now doubly so**, because `F1` answers it and `F2` reopens it. **No runtime evidence can settle it: the mode has never been exercised.**

## H · Election-Only lifecycle

| Claim | Verdict |
|---|---|
| `ElectionMembership` alone is sufficient as the election-specific entitlement | ✅ `SUPPORTED BY EVIDENCE` — measured: 5/5 voters, no `Member` row, admitted and (pre-suspension) able to reach the ballot |
| Organisation `Member` is **not** required | ✅ `AUTHORITATIVE` (`VoterSourceStrategy`) + measured |
| Suspension is an **election-specific** authority and does **not** change organisation membership | ✅ `SUPPORTED BY EVIDENCE` — no suspension path writes organisation state |
| Suspension is mode-independent | ✅ `SUPPORTED BY EVIDENCE` — no path branches on `voter_source_strategy` |

> ⚠️ **But Election-Only cannot validate Model B's core claim.** With no organisation `Member`, there is no competing authority to lose — **the entitlement is durable here by absence, not by decision.**

---

## I · Authority / evidence table

| Rule | Class | Where authority actually sits |
|---|---|---|
| Two modes; snapshot immutable | `AUTHORITATIVE` | `VoterSourceStrategy` |
| Election-Only needs no `Member` | `AUTHORITATIVE` | `VoterSourceStrategy` |
| `ElectionMembership` = election entitlement | `PROPOSED BY PRODUCT OWNER` | nowhere yet — **0 constitutional mentions** |
| Existence ≠ exercisability | `PROPOSED BY PRODUCT OWNER` | nowhere yet |
| Chief may suspend (Question B) | `PROPOSED BY PRODUCT OWNER`; enforced in application | `ElectionPolicy`, not the Constitution |
| Only `active` may exercise | `OBSERVED` + documented intent | `isVoterInElection()` |
| Organisation membership continuously required | 🔴 **`UNSPECIFIED`/contested** (`F1` vs `F2`) | — |
| Lifecycle governs voting windows | `AUTHORITATIVE` | Constitution |
| No voter↔vote linkage | `AUTHORITATIVE`, build-breaking | `ADR-T11` |
| Revocation = identity-trust withdrawal, does not block voting | `AUTHORITATIVE` | `ADR-001`/`ADR-003` + vocabulary |
| Suspension freezes capability without mutating facts | `AUTHORITATIVE` for elections | `ElectionConstitution:126-128` |

## J · Contradictions and ambiguities

| # | Item | Status |
|---|---|---|
| **C-1** | 🔴 **`F1` vs `F2`** on Question A | **PO decision required (`Q-A0`)** |
| **C-2** | `ADR-002`'s *"eligibility computed from membership at check time"* vs Model B | recorded; unresolved by design |
| **C-3** | `scopeEligible()` implements the **opposite** of `F1` — dormant, 0 callers, would deny every voter | contradiction in code |
| **C-4** | Suspension **does not** prevent credential issuance, yet refuses the ballot | incoherence |
| **C-5** | Suspended vs already-voted **indistinguishable** at the enforcing predicate | representation defect |
| **C-6** | `revoke` already means identity-trust withdrawal, and explicitly does **not** block voting | vocabulary collision |
| **C-7** | Voter-level `suspend` vs election-level `suspend` | vocabulary collision |
| **C-8** | Four eyes on the governed suspension path; **one** actor on `suspend`, `approve`, `remove` | governance gap |
| **C-9** | `invited` documented as real; **no producer** | doc vs implementation |
| **C-10** | Two eligibility definitions on the entitlement, **both dead**; the live gate is a third | modelling |

---

## K · Consequences for `PBDIGIT-49` — evidence offered, **no classification performed**

**I did not read, use or modify Session 1's Master Matrix, `SD-4`, or the 213 rows. The 213/1,376 figure is taken as given from the Product Owner's message. Nothing below classifies any row.**

**Concept-level mapping, offered as Session 2 evidence for Session 1 to use or discard:**

| Business decision | Implementation concepts it would touch | Likely Master-Matrix relevance |
|---|---|---|
| **Question A** (`Q-A0`) — continuity of organisation membership | `scopeEligible()` · `FullMembershipPolicy` · `VoterEligibilityService` · admission-gate paths | **Full Membership admission/eligibility tests.** Under `F1`, tests asserting org-status→election-ineligibility at *voting* time would be wrong; at *admission* time they remain correct. **The admission/voting-time distinction is the discriminator, and it can only be applied by reading each test's intent.** |
| **Question B** — Chief's suspension authority | `ElectionVoterController` (6 ops) · `manageVoters` · `ElectionMembership` suspension fields | Voter-management and suspension-workflow tests |
| **Exercisability model (`Q3`)** | `isVoterInElection()` · `EnsureElectionVoter` · `VoteEligibility` · `status` semantics | Any test depending on `status='active'` or on `inactive` |
| **Consumption meaning (`BR-1.11`)** | `markAsVoted()` · has-voted gates | Casting and post-vote tests |
| **Termination (`BR-1.1`/`BR-1.2`)** | `remove()` · `approve()`'s missing `removed` guard | Removal tests |

> **The single most useful discriminator I can offer:** **a test is affected only if it asserts a *voting-time* consequence of *organisation* status.** A test asserting an *admission-time* consequence is unaffected under either formulation. **Mechanism reference is not affectedness** — a class may reference `ElectionMembership` purely as a fixture. **Applying this discriminator is Session 1's work, on Session 1's authority, after `Q-A0` is decided.**

---

## L · Proposed `ADR-002` amendment outline — **PROPOSAL ONLY**

**⚠️ Conditional on `Q-A0`.** The amendment's substance is a new axis whose defining property is that it is *not* re-derived from organisation membership — **which is exactly what `F2` reopens.** Under `F2`-as-continuous, most of this outline collapses.

1. **Preserve** the Verified ≠ Eligible ≠ Authorized orthogonality, the authorization formula, and both worked examples. *(Note: `ADR-002`'s fees-unpaid example concerns admission to a **future** election, which Model B also gates — compatible, not conflicting.)*
2. **Add axis 0 — Entitled:** admitted to a specific election; election-owned; established by an admission decision; **persists until a defined termination rule ends it; not re-derived from organisation membership.** ← **requires `F1`**
3. **Amend the three incompatible statements** — `:161` *"computed from membership at check time"*, `:78` the mixed check list, `:71`.
4. **Separate admission-time from check-time:** organisation membership, fees and type apply **at admission only**; check-time evaluates entitlement, suspension, window and scope.
5. **Amended formula, decisions only — no storage, class or mechanism named:**
   `CanCastVote = Entitled && Exercisable && Verified && Permission`
6. **Disambiguate the vocabulary:** *Revocation* in `ADR-002`/`ADR-001`/`ADR-003` means **identity-trust withdrawal**; entitlement termination must not be called revocation.
7. **Record that** `ADR-002`'s *"Current Implementation Evidence"* section is **stale** — it cites `Member voting_rights`, whereas the live gate is `isVoterInElection()`. *(Recording staleness ≠ amending the decision.)*
8. **Out of scope:** how exercisability is represented (`Q3`), and any tactical class, entity or repository.

**`ADR-002` is NOT amended by this document.**

## M · Questions requiring Product Owner decision

| # | Question |
|---|---|
| **`Q-A0`** | 🔴 **Which formulation binds — `F1` (admission prerequisite only) or `F2` (continuity open)?** **Everything downstream, including the ADR amendment, depends on this.** |
| **`Q-A1`** | If continuity **is** required: at what moments is it evaluated, and what happens to an in-flight voting session? |
| **`Q-E1`** | Should suspension **prevent credential issuance**, and **invalidate an existing credential**? *(Today: neither.)* |
| **`Q-E2`** | Must a suspended voter be distinguishable from one who has voted, **at the enforcing gate**? |
| **`Q-B1`** | Actor counts for suspend / restore / remove, given authority does not currently track consequence |
| **`Q-B2`** | Is a reason mandatory, and must every act be audited unconditionally? |
| **`BR-1.1`/`1.2`** | Termination semantics — Option B recommended previously |
| **`BR-1.7`** | Is suspension temporary or indefinite? |
| **`BR-1.11`** | Consumption's meaning |
| **`BR-1.12`** | Is `invited` a real business state? |
| **`BR-1.13`** | Which suspension path is intended? |
| **`Q-D1`** | Disposition of `scopeEligible()` — it implements the opposite of `F1` |

## N · What remains unspecified

**Stated plainly, without softening:**

* **Question A is unspecified**, and now contested between two Product Owner formulations. **No runtime evidence can settle it — Full Membership has never been exercised** (`members` = 0 rows).
* **Voter entitlement has no authoritative definition anywhere** — `ElectionMembership` appears **0 times** in the Constitution; `admit`, `restore`, `revoke` **0 times**.
* **Exercisability has no domain concept** (`Q3`), and no capability field exists on the entitlement.
* **Credential requirements are unspecified as a business rule** — and the credential is voter-refreshable, so its status as an entitlement condition is doubtful.
* **Consumption's meaning is unspecified.**
* **Termination semantics are unspecified** (recommendation on record, unratified).
* **Suspension duration, review, reason and audit obligations are unspecified.**
* **Whether the Officer Guide carries governance weight is unspecified** — assessed as `D`, mixed authority; nothing promoted.
* **No test anywhere asserts that a suspended voter cannot vote** — so no part of this model is currently protected by the estate.

---

## Boundaries

* **Nothing changed:** no production code, test, fixture, migration, schema, Constitution or ADR; `ADR-002` untouched; nothing implemented; no dormant code activated.
* **DDD order observed:** business decision → domain meaning → authority → application capability → authorization → interface → persistence → tests. **The investigation began from the business question, not from the code**; code appears only as evidence about the current system.
* **Session 1 untouched and not consulted as authority.** Master Matrix, `SD-1`/`SD-2`/`SD-4`, the 1,376 baseline and the 213 rows were **not read, used, classified or modified.** §K is offered as evidence, explicitly not a classification. **The Session-1 instruction text supplied with this commission was not executed by me** — it is addressed to Session 1.
* **`PBDIGIT-69`** cited nowhere.
* **Not treated as authority**, per instruction: model docblocks (including one `@see` that now resolves only under `architecture_legacy/`), `architecture_legacy/` documents (one of which is an assistant-conversation transcript), test names, field names.
* **Not executed:** `approve()`-on-a-`removed`-row remains read-only evidence.

**Traceability:** `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Models/ElectionMembership.php:126-146,157-171,202-224` · `app/Models/User.php:315-328` · `app/Http/Middleware/EnsureElectionVoter.php:37-54` · `app/Http/Controllers/ElectionVotingController.php:41-44,111,156-191` · `app/Http/Controllers/ElectionVoterController.php:171-190,196-216,222-241,290-356` · `app/Policies/ElectionPolicy.php:91-98` · `app/Http/Controllers/CodeController.php:24-25` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php` · `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204,208-227` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` · measured read-only 2026-08-12: no suspension/removal path touches `VoterSlug` or `Code` · a confirmed-suspended voter was issued a fresh credential · `members` 0 rows · `scopeEligible()` 0/20.
