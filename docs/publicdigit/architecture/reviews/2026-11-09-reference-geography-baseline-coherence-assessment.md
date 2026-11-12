# Reference Geography: strategic-to-tactical coherence assessment (fourth revision)

| | |
|---|---|
| **Status** | **RATIFIED** 2026-11-10 by the Principal/ARB (D-93; act `docs/publicdigit/reviews/2026-11-10-reference-geography-baseline-ratification-act.md`). Accepts the domain design only; PO-1 to PO-8 and the Identity contract remain open. No code, schemas or tickets |
| **Assesses** | Strategic baseline §C (`2026-11-09-reference-geography-strategic-baseline.md`) against tactical baseline **v3.11** (`2026-11-09-reference-geography-tactical-design-baseline-v3.11.md`) |
| **Revises** | The third revision (against v3.10) |
| **Binding** | D-3, D-4, D-5, D-22, D-31, D-73 / CR-01, D-75 (with the XD-3 act), D-79, D-80, D-81 to D-92, D-84a |
| **Labels** | [AD] accepted · [BF] fact · [AC] architectural conclusion · [TD] tactical · [TU] technical uncertainty / proof obligation · [OQ] Principal decision |

## Disagreements (first)

**None with the review.** **I withdraw my own earlier claim** (third revision) that keeping the facts in force after a defective approval "follows from D-84". It does not: D-84 governs how facts change, not what a defective approval does to them. It is an **[OQ]** (§3).

## 1. Business explanation

Reference Geography guarantees:
- **one permanent identity per official reference**, whatever happens to its names, codes or parents;
- **every fact with its evidence and valid dates**; nothing overwritten;
- **historical answers that are complete or refused**, and identical every time;
- **every structural change, once two stewards have ever existed, approved by an independent person on exactly the content and evidence published;**
- **no decisions about belonging, membership, coverage, electorates or sovereignty**, and no lookup of anyone's affiliations.

## 2. Independent verdict

**v3.10 was coherent except for three points, all corrected in v3.11:**

| # | Finding | Kind | Correction |
|---|---|---|---|
| H-1 | The content digest covered **evidence references**, not evidence content, assessments or the derived classification [BF: v3.10 §5.3] | Defect [AC] | Digest covers claims with intervals, evidence versions with fingerprints, assessments and outcomes, predicates and p₀, classification, preparers and assessor; **evidence immutable** (a change is a new evidence version) |
| H-2 | The after-publication merge consequence was presented as derived | Overstatement | Five cases analysed; case B effectiveness put to you as **[OQ]** |
| H-3 | GROUPING justified by "no use in coverage (§10)"; §10 says no such thing, and D-80 lets coverage reference official references, including a level-1 region [BF] | Unfounded reasoning | Reasoning corrected; consequence stated (§5) |

**No further substantive defect found.**

## 3. Identity merge ruling

| Case | Outcome | Basis |
|---|---|---|
| A Before publication | Refused at the re-check under the lock | [AC] follows from D-88 |
| B After publication, second person required, approver now in `excluded(r)` | Approval **valid when given, substantively defective**; a governance finding is recorded; **effectiveness meanwhile: [OQ]** | D-88 + a governance choice |
| C After publication, no second person required | No D-88 finding; merge notice recorded only | [AC] D-84, D-89 |
| D Merge among people other than approver vs `excluded(r)` | No defect | [AC] D-88 |
| E Merge later reversed | Finding closed as unfounded; confirmations or corrections already published stand | [AC] |

**The [OQ] (case B):**
- **K: keep the facts in force, visibly flagged, until a valid second person confirms them or a correction replaces them.** Consumers see the flag and apply their own rules. **Recommended.**
- **S: suspend the facts by a new publication until approved again.**
- **Why K:**
  - the facts rest on the official instrument, not on PublicDigit's approval;
  - suspension disrupts organisations and elections for an internal defect;
  - **a suspension is itself a structural change needing an independent approver**, which in case B may be the very thing that is missing.
- Retroactive voiding is excluded by strategic invariant 2.

## 4. Content-version verification

| Must be protected | Protected by | Status |
|---|---|---|
| Claims, slots, values, valid intervals | Digest | ✅ |
| Evidence content | Immutable evidence versions + fingerprint in the digest | ✅ (v3.11) |
| Assessments and outcomes | Digest | ✅ (v3.11) |
| Dependency predicates, p₀ | Digest | ✅ |
| Derived classification | Digest; computed, not chosen | ✅ |
| Preparers and assessors, all versions | Recorded per version; `excluded(r)` = union | ✅ |
| Published authority records | Dependency predicates (staleness) | ✅ |
| Identity information | Re-check at publication at the current roster version | ✅ |
| Old approvals | Count only for the version they name | ✅ |
| Splitting or relabelling | Classification per claim kind, per revision | ✅ |

## 5. ADMISSION and GROUPING

- **ADMISSION = structural (recommended: confirm).**
  - It decides what exists in the register.
  - **Practical meaning:** once D-89 is active, routine ISO additions also wait for a second person.
  - For a non-ISO entry, your explicit decision (D-86 (4)) authorises it, and the second person checks that the revision implements it faithfully. **Not redundant.**
  - No counterexample found.
- **GROUPING = non-structural (recommended: confirm, with the consequence stated).**
  - It is statistical, not territorial (D-85), and it follows UNSD's own publication.
  - **Counterexample examined:** an organisation that references a level-1 region in a unit's coverage would see that coverage change, with only one steward having checked it.
  - **Why the recommendation stands:** the change copies an uncontested official instrument, and D-85 directs organisations to their own international regions for organisational purposes.
  - **If you consider that consequence unacceptable, choose "structural".**

## 6. Strategic-to-tactical coherence

**Re-checked, no defect:**
- identity and reconciliation (one level, atomic, multi-country through the single sequencer);
- identifier uniqueness;
- withdrawals carry the target's kind;
- valid-time shortening is a claim with a before-image;
- profiles;
- dependencies for gains and losses;
- stale revisions and content versions;
- both boundaries;
- D-84, D-84a, D-88, D-89;
- context ownership: Geography knowledge; Organisation belonging, areas, coverage; Membership facts; Election electorate; Identity persons; Governance organs;
- address ownership still a recommendation.

## 7. Technical proof boundary

| Logically established (given the obligations) | Requires implementation evidence |
|---|---|
| Completeness at τ and at p; stable answers; no timestamp moved; ties ordered by position; atomic publication; approvals bound to content; governance ordering | **PO-1 to PO-7** (lock to commit, single write path, dependency-only reads, gap-free positions, one snapshot, refusal, one clock) on PostgreSQL · **PO-8** platform: Membership's §38.2 barrier, a separate platform dependency not changed here · **Identity contract** (four items) |

**No cross-context causal consistency is claimed.**

## 8. Principal decision register

| # | Decision | Recommended | Consequence |
|---|---|---|---|
| 1 | **Case B effectiveness** | **Ruled: K ([AD] D-90)** | Facts stay usable and flagged; consumers decide reliance |
| 2 | ADMISSION structural | **Ruled: structural ([AD] D-91)** | Admissions wait for a second person in era B |
| 3 | GROUPING non-structural | **Ruled: non-structural ([AD] D-92)** | One steward may publish an M49 regrouping; organisations decide coverage consequences; platform-policy assignments stay with L-4c |

**Deferred (not blockers):** Q-2, L-4c, Japanese classification, naming policies, address ownership.

## 9. Readiness

**No Principal decision remains open in the baseline** (D-90, D-91, D-92 ruled). **Ready for your Principal/ARB ratification.** **Not implementation-ready** until PO-1 to PO-8 and the Identity contract are discharged.

## 10. Final coherence check after D-92 (2026-11-10)

**One finding, corrected:** PO-2 (single write path) did not list the D-90 governance notices, which also take positions in the publication stream. **Added.** No other contradiction found.

**Complete classification model** (tactical §14):

| Kind | What changes | Existence / containment / structure / authority? | Source | Second person in era B (D-89) | D-84a |
|---|---|---|---|---|---|
| EXISTENCE, SUCCESSION | An entry begins, ends or succeeds another | Existence | Instrument | **Yes** | If contested |
| CONTAINMENT, BOUNDARY | Parent; boundary description | Containment | Instrument | **Yes** | If contested |
| CLASSIFICATION, PROFILE_SCHEME / TIER / CLASS | A unit's class; a country profile (D-81) | Structure | Instrument | **Yes** | If contested |
| ISSUER, MANDATE, COMPETENCE, PRECEDENCE | Who may assert what; conflict rules | Authority | Legal basis | **Yes** | If contested |
| RECONCILIATION | Two identities are one entity | Identity | Assessment with evidence | **Yes** | If contested (D-84a lists competing positions, not every identity judgement) |
| ADMISSION | A country-or-area enters the register | Existence | D-86 policy or Principal decision | **Yes ([AD] D-91)** | If contested |
| GROUPING | M49 region of an entry | Statistical grouping only | M49 row or verified code | **No ([AD] D-92)**; platform-policy route: L-4c | If contested |
| IDENTIFIER, SOURCE_NAME | Codes, attributed names | Attribute | Issuing body | No | If contested |
| CLDR_NAME, CLDR_INHERITANCE | Display names | Presentation (D-87) | CLDR release | No | If contested |
| DISPUTE | Marker or clearance | None (§4) | Assessment | No | **Yes** (judgement) |
| PLATFORM_DEFAULT_LOCALE | Default locale | Presentation | Platform | No | — |

**Rules preserved:** a withdrawal carries the target's kind; any structural claim makes the revision structural; classification is computed, never chosen; downstream use never decides classification.

**Deferred, not blockers:** L-4c, Q-2, Japanese classification, names below level 2, level-1 region names, address ownership.

**Proof obligations:** PO-1, PO-4, PO-5, PO-7 need PostgreSQL evidence; PO-2, PO-3, PO-6 need architectural enforcement; PO-8 is cross-context (Membership §38.2); the Identity contract (four items) depends on Identity's tactical design.
