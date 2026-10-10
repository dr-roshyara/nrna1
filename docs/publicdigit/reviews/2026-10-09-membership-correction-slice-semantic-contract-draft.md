# Membership Correction Slice: Semantic Contract

*The filename keeps `-draft` for link stability only. The status is the **Status** line below and §16; the authoritative register is the gate decision record (label corrected 2026-10-10, C-AMEND-1, G2-b).*

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Commission:** the PO's Membership mission "Draft the Correction-Slice Contract" (2026-10-09). Membership domain only.
**Status:** ✅ **APPROVED by the PO, 2026-10-10** (formal approval; D-COR-2 and D-COR-5 (A) recorded; CQ-9 outside the approval). **Not implementation authorisation:** C-1 needs a PO-approved story + TDD plan and a gate re-evaluation (`2026-10-10-membership-c1-gate-decision-record.md`). Current gate status = §16 and the gate record. Status statements inside the dated sections §13 – §15 are historical as of their dates.
**Governing gate:** ARB-MEM-01: verified correction / annulment is required before Membership live writes and cutover (`2026-10-09-membership-human-arb-decision-record.md`).

**Labels:**
- **[RAT]** ratified domain rule;
- **[IMPL]** verified implementation behaviour;
- **[MODEL]** bounded-model result;
- **[PROP]** proposal;
- **[OPEN]** unresolved decision;
- **[ASSUME]** unverified assumption.

## 1. Purpose, scope, non-goals

**Purpose:** define the semantics, refusals, temporal rules, lineage, persistence, concurrency and acceptance tests of **Annul, Void and Correct** for the existing `Member` aggregate, so that the correction capability can be implemented in separately authorised TDD slices.

**In scope:**
- Annul, Void and Correct of **Admit** and **End** facts;
- Correct of Correct (chains), Annul and Void of Annul / Void where ratified;
- consequences (the trim policy) as ratified;
- resolution, `knownAt`, refusals, events, persistence shape and the concurrency obligations.

**Non-goals:**
- Renew, ChangeType, Suspend, Restore (not in the kernel; their correction follows the same algebra later);
- ex nunc annulment (LQ-2: an `End(kind)`, not a correction);
- the appeal *process* (CQ-9);
- correction *authority* allocation (CQ-3);
- HTTP / UI; migration; cutover; Governance, Election or Finance implementation;
- any change to ratified rules.

## 2. Ratified rules and traceability

| ID | Rule | Source |
|---|---|---|
| R-1 | Fact families include Annul, Correct and Void | act §3; final proof §16.2 [RAT] |
| R-2 | Annul applies only to facts that represent a **decision** | act §4; FP §16.2 [RAT] |
| R-3 | Annul(Annul) is an appeal that restores the original, subject to G\* | act §4; FP §16.4 F [RAT] |
| R-4 | Void is a distinct legal operation ("the record never reflected reality"), not Annul | act §4; FP §13.8 [RAT] |
| R-5 | Correct is allowed per the correction policy. A **Correct of an annulled fact is inert** unless the annulment is overturned. **Correct of a voided fact is refused** | act §4; FP §16.3 / §16.4 H, I [RAT] |
| R-6 | **Refused:** Annul(Void), Correct(Annul), Correct(Void) | act N-3; FP §16.4 G [RAT] |
| R-7 | Annul and Void create **no** consequences; they change liveness and may **revive** superseded consequences | act §3; FP §16.2 [RAT] |
| R-8 | Correct replaces a value; it creates consequences over Δ and **inherits** the superseded fact's consequences, clipped (g2) | FP §14.2, §16.2 [RAT] |
| R-9 | A consequence (trim) is recorded **on its cause**, frozen at commit, live iff its cause is active and not superseded (CFC+) | decision register RA-4 (amended); FP §12.4 option C, §15.1 [RAT] |
| R-10 | Resolution = live → active (whole chain live) → value → BaseClaim → LiveCut → lifetime → predicates. It is pure; guards are not re-evaluated | act §6; FP §14.2, §14.4, §16.3 [RAT] |
| R-11 | G\* = accept iff the resolved post-state satisfies INV-9 ∧ INV-EP ∧ INV-COV. On a write conflict, **recompute f⁺ (consequences)** then G\* | FP §14.10 / §16.10; Slice 1a R-1a-2 [RAT] |
| R-12 | KnownAt(τ) for τ before a correction's `recordedAt` is unchanged; TrueAt changes | FP §5, §13.8 [RAT] |
| R-13 | Correct cannot change the **holder**. Wrong holder ⇒ Void(Admit) + a new Admit on the right Member | FP §5 [RAT] |
| R-14 | Separate domain commands and fact kinds; a **shared storage shape is allowed** | FP §5, §13.8 [RAT] |
| R-15 | Events: `FactAnnulled`, `FactVoided`, `FactCorrected`, `PeriodTrimmed`, `TrimRevived` | decision register RA-12 [RAT] |
| R-16 | Annul requires `decidedAt` (it is a decision). For Void and Correct it is optional | FP §13.8 [RAT] |

**Discrepancy D-COR-1 (reported, not resolved here).**
- `2026-10-08-membership-final-decision-register.md:91` ("Correct requires a live target") and `:197` ("a Correct may target only an active fact") **contradict** act §4 / FP §16.3 (a Correct of an *annulled* target is allowed and inert).
- This contract follows **the ratification act**, the later instrument that reflects FP §16.3.
- **The PR should confirm, and annotate the register** (historical text is not rewritten).

## 3. Operation semantics and preconditions

### 3.1 Annul (a decision set aside, ex tunc)

| Aspect | Contract |
|---|---|
| Business meaning | a valid decision is set aside and treated as never effective (ex tunc). Statistics count it as a historical decision that was annulled [RAT] |
| Permitted targets | an earlier fact of the **same Member** with **decision provenance** (`ProvenanceKind::Decision`): Admit, End, Correct, Annul (= appeal) [RAT R-2, R-3]. **Not** Void (R-6). Not facts with `rule` or `attested` provenance [RAT R-2]: use Void if the record was wrong |
| Preconditions (pre / post separated in **§14.2**) | target exists and has a smaller seq; `decidedAt` present (R-16); authority evidence (CQ-3) [OPEN]. **Annul of a target that is already not live:** the corpus is silent → [OPEN D-COR-2] (proposal: refuse as `TargetNotLive`, because it has no effect and is ambiguous for audit) |
| Resulting fact | `Annul{factId, target, provenance(decision, authority, reason), decidedAt}`. **No value; no consequences** (R-7) |
| Effect | `live(target)` becomes false; liveness propagates per R-10. Superseded consequences may revive (R-7). An Annul of an Annul revives the original (R-3) |
| Invariants | G\* on the post-state (R-11). A revival that would violate an invariant is **refused** (FP §16.4 F) |
| Rejection | `TargetNotFound`, `TargetHasNoDecisionProvenance`, `AnnulOfVoidRefused` (R-6), `DecidedAtRequired`, [OPEN] `TargetNotLive`, plus the invariant refusals (`OverlappingMembershipPeriods`, `PeriodLifetimeNotContiguous`, `MembershipCoverageLost`) |
| Temporal | no valid-time value of its own. `decidedAt` is required; `recordedAt` and `seq` are assigned at append. TrueAt changes; KnownAt(τ < recordedAt) is unchanged (R-12) |
| Events | `FactAnnulled`; `TrimRevived` for each consequence revived (R-15). **`TrimRevived` arises only once Correct exists (C-2)** |

### 3.2 Void (the record never reflected reality)

| Aspect | Contract |
|---|---|
| Business meaning | a data determination: the record was never real. It is **excluded from statistics** [RAT R-4] |
| Permitted targets | any earlier fact of the same Member: Admit, End, Correct, Annul, Void (Void(Void) corrects a wrongful voiding) [RAT FP §16.4]. The target must be **live** if D-COR-2 = refuse (§14.2) |
| Preconditions (pre / post separated in **§14.2**) | target exists, with a smaller seq; authority evidence (registrar / data authority, CQ-3) [OPEN]. Target already not live: [OPEN D-COR-2] (as for Annul) |
| Resulting fact | `Void{factId, target, provenance(reason, authority), decidedAt?}`. **No consequences** (R-7) |
| Effect | `live(target)` becomes false. Void(Annul) restores the original (R-3 analogue, FP §16.4 "Void(Annul)") |
| Invariants / rejection | G\*. `TargetNotFound`, [OPEN] `TargetNotLive`, plus the invariant refusals |
| Temporal / events | as Annul, except `decidedAt` is optional. `FactVoided`; `TrimRevived` (from C-2 only) |
| Holder errors | wrong holder ⇒ Void(Admit) here + a new Admit on the correct Member (two commands, two aggregates; **not** atomic across Members) [RAT R-13; ASSUME: no cross-Member transaction needed] |

### 3.3 Correct (a real fact, recorded with a wrong value)

| Aspect | Contract |
|---|---|
| Business meaning | the event was real, but an attribute was recorded wrongly. The value is replaced; the original stays in the audit history [RAT] |
| Permitted targets | Admit, End, Correct (chains) of the same Member [RAT]. **Refused:** Annul, Void (R-6) |
| Correctable attributes | **Admit `effectiveAt`** and **End `effectiveAt`** [RAT, FP §14.3]. **Admit initial term, End kind, provenance or authority evidence:** the corpus does not specify → [OPEN D-COR-3]. The holder can **never** be changed (R-13) |
| Preconditions | target exists, with a smaller seq; **target not voided** → `CorrectOfVoidedFact` (R-5); target annulled → **accepted, inert** until revived (R-5, policy H); authority evidence (the recording authority, plus the organ for a decision attribute; CQ-3) [OPEN] |
| Resulting fact | `Correct{factId, target, value, provenance, decidedAt?, consequences}`. Consequences = g2 on the strict prefix: the Δ cuts of the enlarged claim on other periods, plus the inherited consequences of the superseded fact, clipped to the new BaseClaim (R-8, R-9) |
| Effect | `value(target)` becomes the latest live Correct's value (R-10). BaseClaim of the target period changes; consequences per CFC+ |
| Invariants | G\* (R-11) and, **if BD-REV-ER = refuse**, no live Admit may end with an empty resolved lifetime because of this correction [OPEN §9] |
| Rejection | `TargetNotFound`, `TargetNotCorrectable` (Annul / Void / unsupported kind), `CorrectOfVoidedFact`, `HolderChangeNotAllowed`, `ValueUnchanged` [PROP: refuse a no-op correction], the invariant refusals, and [OPEN] `PeriodAbsorbed` (BD-REV-ER) |
| Temporal | the value is a **valid-time date** (EP-Q6 = T1). For Correct(End), the value must be **strictly after the period's start** (as C-S0-1) [PROP: carry C-S0-1 over to the corrected value; otherwise BaseClaim degenerates to ∅]. KnownAt(τ < recordedAt) is unchanged |
| Events | `FactCorrected`; `PeriodTrimmed` per consequence created; `TrimRevived` per consequence revived |

### 3.4 Repair matrix (what fixes which error)

| Error | Remedy | Evidence |
|---|---|---|
| wrong admission date | Correct(Admit, date) | [MODEL] O-5: 124 / 124 |
| wrong end date | Correct(End, date) | [MODEL] 74 / 74 |
| admission that should not exist (a recording error) | Void(Admit) | [MODEL] 85 / 85 |
| admission decision set aside | Annul(Admit) | [MODEL] 85 / 85 (the same resolution as Void; it differs in meaning, statistics and authority) |
| spurious End | Void(End) or Annul(End) | [MODEL] 25 / 25 each |
| wrong holder | Void(Admit) + Admit on the correct Member | [RAT R-13]; not modelled |
| appeal succeeds against an annulment | Annul(Annul) | [RAT R-3]; [MODEL] 90 / 90 restored |

## 4. Invariants and rejection rules

- **Unchanged:** INV-M, INV-FID, INV-9, INV-EP, INV-COV, C-S0-1, C-S0-2, R-1a-1 … 5, IDEM-1 … 4.
- **INV-EP and INV-COV become reachable only with Correct (C-2)**: consequences arise only from Correct. Annul / Void create none, so the C-1 fragment can never violate them (audit §13, M2). The known fragmentation cases need ≥ 5 – 6 facts (FP CX-B / CX-C), so C-2 bounds must be ≥ 5 facts.
- **New structural invariants:**
  - **INV-TGT:** `target(f) < f` and within the same Member;
  - **INV-N3:** no Annul(Void), Correct(Annul), Correct(Void);
  - **INV-CHAIN:** a Correct chain's root is an Admit or End.

**Proposed new `RefusalReason` cases (names [PROP]):**
- `TargetNotFound`
- `TargetHasNoDecisionProvenance`
- `AnnulOfVoidRefused`
- `CorrectionOfAnnulOrVoidRefused`
- `CorrectOfVoidedFact`
- `TargetNotCorrectable`
- `HolderChangeNotAllowed`
- `DecidedAtRequired`
- `ValueUnchanged`
- `TargetNotLive` [OPEN D-COR-2]
- `PeriodAbsorbed` [OPEN BD-REV-ER]

## 5. Temporal and historical reconstruction

- **Bitemporal answers [RAT R-12]:**
  - TrueAt(t) = resolution of all facts;
  - KnownAt(τ)(t) = resolution of facts with `recordedAt ≤ τ` (existing `Member::knownAt`).
  - A correction recorded at ρ changes TrueAt for every affected t and **never** changes KnownAt(τ) for τ < ρ.
- **Metamorphic properties to test:**
  - (a) **prefix stability:** KnownAt(τ) on log F equals the resolution of F's prefix up to τ;
  - (b) **reversal returns to the prior resolution (domain-restricted, §14.4):** for a fact x appended last to F and **live**: F·x·Void(x) resolves like F (any kind of x); F·x·Annul(x) resolves like F only if x has **decision provenance and is not a Void** (Annul(Void) is refused, N-3). **Not** applicable to x = Annul(·): Annul(Annul) is an **appeal** (R-3), covered by (c), not ordinary reversal;
  - (c) **appeal restoration:** F·a·Annul(a)·Annul(Annul(a)) resolves like F·a **or** the last step is refused by G\* (FP §16.4 F); it presupposes D-COR-2 = refuse (§14.2, counterexample);
  - (d) **correction idempotence of value:** F·Correct(t,v)·Correct(t,v) refused as `ValueUnchanged` [PROP];
  - (e) **counterfactual (CFC+):** Annul(c) resolves to the log without c and its dependants **with the recorded consequences, not a replay** (decision register line 205; F∖D(c) can be an invalid history, FP §16.5). Trivially true in C-1 (no consequences); meaningful from C-2.

## 6. Correction lineage and audit

- **Lineage is the `target` chain:** original ← Correct ← Correct …; reversal ← Annul / Void. Nothing is updated or deleted (R-1a-4).
- **Every correction fact carries:**
  - its own provenance (`Decision` for Annul; the reason and actor for all);
  - authority evidence (G-AUTH-1 fields once decided);
  - `decidedAt` / `recordedAt` / `seq`.
- **Reporting [RAT FP §13.8]:**
  - Annul → "decision X set aside on …";
  - Void → "record X never valid" (excluded from statistics);
  - Correct → "X recorded with wrong attribute; corrected on …".

## 7. Oracle evidence, limits and test mapping

**Oracle:** `docs/publicdigit/membership/architecture/models/g6_correction_oracle.py` (+ `g6_checks.py`), written from FP §14.2 and act N-3.

| Established [MODEL] | Not established |
|---|---|
| agreement with the **Slice 0 kernel** on the Admit / End fragment: 400 / 400 decision pairs, comparing **admissibility and per-instant membership only** (not per-period lifetimes). **Not independent evidence for the correction semantics:** the Slice 0 contract was itself derived from FP §14.2, both artefacts are AI-authored, and the Final Freeze scripts behind FP's figures are not in the repository (audit §13, m6) | correctness beyond H = 5, L ≤ 4 (remedies: ≤ 3 facts) |
| INV-COV never needs to refuse (a bounded replication of a ratified claim) | probabilities: counts are enumerated cases |
| Annul(Annul) restores (90 / 90); every Admit / End single-fact error is remediable by one operation (418 / 418 checks) | provenance (the oracle lets Annul target any fact, a **superset** of R-2); `TargetNotLive` (it allows it); `decidedAt`; authority; holder; concurrency; persistence; Renew / Suspend / ChangeType |
| BD-REV-ER absorption occurs in 35 / 14,365 histories | — |

**Oracle vs ratified rule: ambiguities to resolve before tests are derived:**
- The oracle allows **Annul of any fact** and **Annul / Void of a non-live target**, which is a superset of R-2 and D-COR-2. Restricting it only shrinks the reachable set, so verified properties carry over (the same argument as FP §16.2 harness note). The **test oracle must still implement the restriction**, or the differential tests will disagree on refusals.
- The oracle models the trim representation as **absolute** intervals; FP §12.4 recommends **relative**. Both pass under G\* (FP §12.4); the implementation choice must be fixed in the slice contract (§10, C-2).

**Test mapping (to become RED-first tests in the authorised slices):**
1. **Port the oracle into PHP test support** (as Slice 0 did with `PointwiseOracle`), independent of the production `Resolution`.
2. **Golden cross-check:** the **PHP port of the g6 oracle** reproduces the Python oracle's verdicts and resolutions on the enumerated space, recorded as a fixture.
3. **Exhaustive differential test** (bounded H, L): the kernel's `decide*` and `resolve` equal the PHP port of the g6 oracle for every history. A golden cross-check proves **transcription fidelity**, not correctness (audit §13, m6).
4. Property and metamorphic tests (§5); persistence round-trip tests (§8); race tests (§8); mutation measurement of the new code.

## 8. Persistence, concurrency, idempotency (contract obligations)

- **Fact shape [RAT R-14, R-9; storage details are implementation decisions]:**
  - extend `org_membership_facts.kind` to `annul | void | correct`;
  - a **reference column** for the target. The approved Slice 1b column `period_fact_id` already has R5's single-reference meaning for End. Whether to **generalise it** or **rename it to `target_fact_id`** (EP-01 principal review R5) is [OPEN D-COR-4, PR];
  - the Correct **value** in `effective_at` (a date) [PROP];
  - **`consequences jsonb` + `g2_version`** on the fact row (EP-01 R4) [RAT direction; schema PROP];
  - a composite FK from the target to a fact of the same Member;
  - kind-specific CHECKs;
  - **an additive migration** (the 1b migration is not edited).
- **Concurrency [RAT R-11]:**
  - the existing `DecideAndAppend` loop (re-authorise → reload → **re-decide**, which recomputes g2 → append at the expected version) **already satisfies** "recompute f⁺ on conflict", **provided** g2 runs inside `decide*`, not in the adapter [PROP: g2 lives in the kernel];
  - race tests: Annul vs Correct on the same target; two Corrects; a Correct vs an End on the adjacent period (FP §16.10 smallest lost-update case).
- **Idempotency:** unchanged IDEM-1 … 4. Fingerprints include the target and the value.
- **Prerequisite:** `PBDIGIT-264` (F-1 far-date guard, F-4b commands normalisation, F-5 N1 isolation) **before** the persistence slice C-4 [PROP order].

## 9. Open decisions (not decided by this contract)

| ID | Question | Evidence | Options | Recommendation [PROP] | Decider | Blocks |
|---|---|---|---|---|---|---|
| **BD-REV-ER** | may a correction absorb a whole live period (live Admit, empty resolved lifetime)? | [MODEL] 35 / 14,365 histories. Minimal: `Admit(p0@0), End(p0@1), Admit(p1@1), Correct(Admit p1 → 0)` erases p0 while its Admit and End stay live | (a) allow; (b) refuse until that period's Admit is annulled or voided (decision register :146) | **(b) refuse**: otherwise an implicit set-aside without Annul / Void, authority or reason; refusing changed nothing else in the bounded space | PO (business policy, register :155) | C-2 (Correct) |
| **CQ-3** | who may Annul (the competent organ), Void (the registrar / data authority) and Correct (the recording authority + the organ for decision attributes)? Is a technical role sufficient evidence? | FP §5 / §13.8 name *kinds* of authority, not organs; live code has only owner / admin roles | per organisation statute vs platform default; interim adapter (as D-4) | keep **the authority evidence on the fact mandatory**; the allocation is decided per CQ-3. A technical role is **not** proof of competence (EP-01 review :276) | PO + Legal + Governance owner | C-3 (application commands), not the kernel |
| **CQ-9** | which body hears appeals; ex tunc vs ex nunc (LQ-2); is a successfully appealed exclusion "never happened" (BD-TERM4, recommended yes)? | R-3 gives the *semantics* of a successful appeal (Annul(Annul)); the *process* is unspecified | — | keep the semantics; do not model a process | PO + Legal | C-3 authority for Annul(Annul); ex nunc path |
| D-COR-1 | the register's "Correct requires a live / active target" vs act §4 | §2 | — | the act governs; annotate the register | PR | — |
| D-COR-2 | Annul / Void of a target that is already **not live** (defined on `live`, not `active`: an inert Correct of an annulled target is live and must remain voidable) | the corpus is silent. **Counterexample if accepted:** Admit a, Annul(a), Void(a), Annul(Annul(a)) leaves a unrestored, contradicting R-3's "restores" (oracle-verified) | refuse (`TargetNotLive`) / accept as a no-op | refuse. **APPROVED by the PO 2026-10-10** (refuse with `TargetNotLive`; Annul(Annul) / Void(Annul) of a live annulment are not prohibited). **PR concurred 2026-10-10** | PR / PO | C-1 |
| **D-COR-5** | **appeal against an exclusion after re-entry:** Annul(End) after a later Admit is **refused by G\* (INV-9)**, because Annul creates no consequences (act §3, FP §16.2). This contradicts BD-REV = "trim the later period" (register :41), BD-TERM4 "yes (Annul)" (:42) and FP §7 scenario H ("Annul(End) → g2") | oracle: refused in every probed variant; works only after Annul / Void of the re-entry Admit | (a) confirm the act: trim applies only to Correct-generated consequences, and an Annul-based reversal after re-entry is refused until the later period is addressed; (b) let Annul(End) run g2 (changes the act and the C-1 kernel); (c) BD-REV = refuse | **(a)**. Whether the re-entry Admit is then Annulled or Voided is a CQ-9 / LQ-2 question | PR (architecture), PO + Legal (BD-TERM4 meaning) | **C-1** (option (b) would change it) |
| ↳ D-COR-5 decision | (A) architecture: **CONFIRMED by the Human ARB 2026-10-10** (option (a)); (B) business / legal: **OPEN** (PO + Legal, CQ-9) | — | — | — | — | — |
| D-COR-3 | are the Admit initial term, End kind, provenance and authority evidence correctable by Correct? | the corpus is silent (FP §14.3 covers dates) | — | dates only in the first slice; the others OPEN | PO | C-2 scope |
| D-COR-4 | `period_fact_id` generalised vs renamed `target_fact_id` | 1b contract vs EP-01 R5 | — | rename in an additive migration while the table is unreleased (dev: migration pending) | PR | C-4 |

## 10. Dependency-aware implementation plan (each slice separately authorised)

| Slice | Content | Depends on | Blocked by |
|---|---|---|---|
| **C-1 kernel: Annul / Void** | stages 1 – 2 (`live`, `active`); target rules R-2, R-6, D-COR-2; G\* (only INV-9 can fire in this fragment; INV-EP / INV-COV unreachable, audit M2); events `FactAnnulled` / `FactVoided` (no `TrimRevived` in C-1); PHP oracle port + golden cross-check + exhaustive differential tests | contract approval | D-COR-2 **and the D-COR-5 architecture question** (§14.6; corrected in §15). **Not blocked by BD-REV-ER or CQ-3** (no consequences arise without Correct; authority is application-level) |
| **C-2 kernel: Correct + consequences** | `value`, chains, g2, `LiveCut` (stage 5), CFC+, the BD-REV-ER guard, trim representation (relative / absolute) | C-1 | **BD-REV-ER**, D-COR-3 |
| **C-3 application** | `AnnulFact` / `VoidFact` / `CorrectFact` commands and handlers via `DecideAndAppend`; `MembershipAct` extended; authority evidence | C-1 (C-2 for Correct); **G-AUTH-1 in the Slice 2 contract** | **CQ-3** (or an interim adapter accepted like D-4), CQ-9 for Annul(Annul) authority |
| **C-4 persistence** | additive migration (§8); adapter encode / decode; round-trip and race tests | C-1 / C-2 shapes; **PBDIGIT-264** | D-COR-4 |
| Parallel | Slice 2 (Admit / End wiring) may proceed after its own contract. **Neither Slice 2 nor C-x is approved by this document** | — | — |
| Gate | cutover, migration, live writes stay blocked until C-1 … C-4 are accepted (ARB-MEM-01) | — | — |

**Acceptance criteria (each slice):**
- RED-first tests;
- exhaustive differential equality with the PHP port of the g6 oracle in the stated bounds, plus the specification-derived properties of audit §13.5;
- metamorphic properties (§5) pass;
- mutation measurement reported with its scope (covered MSI target agreed per slice);
- no regression in the existing 93 unit, 12 architecture and 27 PostgreSQL Membership tests;
- the developer guide;
- an implementation review;
- **a PR decision.**

## 10a. Acceptance criteria and tests per operation (RED first in the authorised slice)

| Operation | Acceptance criteria → tests |
|---|---|
| **Annul** | see the slice-scoped, pre / post-separated criteria in **§14.3** (supersedes this row) |
| **Void** | see **§14.3** (supersedes this row) |
| **Correct** | (1) Correct(Admit / End date) changes TrueAt exactly as the oracle predicts. (2) Correct(Correct) means the latest live correction wins. (3) Correct(Annul / Void) → `CorrectionOfAnnulOrVoidRefused`. (4) Correct of a voided target → `CorrectOfVoidedFact`. (5) Correct of an annulled target is accepted **and inert**; it becomes effective **once the target is live again**, i.e. every live Annul / Void of the target is itself annulled or voided (Annul(Annul) **or** Void(Annul)). If the target is voided before revival, the correction stays inert (oracle-verified). Correct of a target voided **through its chain** (root voided): [OPEN m5, C-2]. (6) A holder change → `HolderChangeNotAllowed`. (7) Correct(End) at or before the period start → refused [PROP C-S0-1 carry-over]. (8) Consequences: the Δ cuts and inherited clipped cuts equal g2. Annul(Correct) revives the target's own cuts (CFC+). (9) [OPEN BD-REV-ER] the absorption case behaves per the decision. (10) `FactCorrected`, `PeriodTrimmed`, `TrimRevived` |
| **All three** | kernel criteria: §14.3 (C-1) / §14.5 (C-2). **Application, concurrency, persistence and tenant-scoping criteria belong to C-3 / C-4** (§14.5), not to C-1 |

## 11. Risks, assumptions, TODOs

**Risks:**
- **Oracle–kernel common-mode error:** mitigated by the independent sources and the PHP port + golden cross-check.
- **State space growth with consequences:** the bounds must be chosen so exhaustive tests stay CI-feasible.
- **INV-EP / INV-COV become live code paths in C-2** (not C-1). Their earlier "unreachable" evidence no longer applies from C-2.
- **Cross-Member holder repair is two commands** (not atomic). A failure between them leaves a voided Admit without its replacement, which is visible and repairable.

**Assumptions:**
- [ASSUME] the trim policy (BD-REV = trim) stays frozen **for Correct-generated consequences**. Its application to Annul-based reversals is contradictory in the corpus (D-COR-5);
- [ASSUME] no cross-Member transaction is needed;
- [ASSUME] Correct values are calendar dates.

**TODOs:**
- PR / PO review of this draft;
- decide BD-REV-ER, D-COR-1 … 5 (D-COR-5: architecture part by PR / ARB, business part by PO + Legal);
- CQ-3 / CQ-9 with Legal and Governance;
- authorise C-1.

## 12. Recommendation for the next separately authorised slice

**C-1 (kernel: Annul / Void).** It is independent of BD-REV-ER and CQ-3. It needs this contract's approval, a decision on D-COR-2, **the D-COR-5 architecture confirmation** and PO authorisation (corrected in §15; see §14.9). It establishes the PHP port of the oracle that C-2 needs (INV-EP / INV-COV only become reachable in C-2), and establishes the PHP oracle that C-2 needs.

## Decision register

| Item | Status |
|---|---|
| R-1 … R-16 (ratified rules used) | **APPROVED** (ratified sources) |
| this contract | **APPROVED by the PO 2026-10-10** |
| the proposed refusal names, `ValueUnchanged`, the C-S0-1 carry-over for Correct(End), g2 in the kernel, the value in `effective_at` | **PROPOSED** |
| D-COR-2 | **APPROVED by the PO 2026-10-10** (refuse, `TargetNotLive`) + **PR concurrence recorded 2026-10-10** |
| D-COR-5 (A) | **CONFIRMED by the Human ARB 2026-10-10** (Option A: no implicit consequences); PR concurs |
| BD-REV-ER, CQ-3, CQ-9, D-COR-1, D-COR-3, D-COR-4, D-COR-5 (B) | **OPEN** |
| C-1 | **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16). → *Superseded 2026-10-10:* the C-1 plan was **approved in principle by the PO, with conditions** (plan `20261010-1009`); the subsequent start-gate re-evaluation **determined that all applicable conditions were closed and C-1 was eligible to start** (gate decision record: start-gate checklist; "Gate re-evaluation"); C-1 was then **implemented under `PBDIGIT-268`**; **formal acceptance PENDING (G3)**. Current status: §16. Reconciliation: gate decision record row 6, C-STATUS-1. |
| C-2 (on BD-REV-ER, D-COR-3); C-3 (on CQ-3, G-AUTH-1); C-4 (on PBDIGIT-264, D-COR-4) | **BLOCKED** |
| cutover / live writes | **BLOCKED** (ARB-MEM-01) |

**Files read:**
- ratification act §3 – §6, N-3, N-4;
- final proof §5, §12.4, §13.8, §14.2 – §14.5, §16.2 – §16.4, §16.10;
- final decision register (RA-4, RA-5, RA-12, BD-REV, BD-REV-ER, BD-TERM4, lines 91 / 146 / 197 / 205 / 215 – 218);
- EP-01 principal review (storage table);
- Slice 1b contract / migration;
- `Resolution.php` (stages);
- the g6 oracle and checks;
- the Human ARB decision record.

**Files changed:** this document only (new, uncommitted).

---

## 13. Semantic consistency audit (2026-10-09; the PO's Membership mission "Independent Semantic Consistency Audit")

> **Historical section (dated).** Its gate and status statements reflect its date. The current status is in §16 and in `2026-10-10-membership-c1-gate-decision-record.md`.

**Method:**
- an independent read-only reviewer (fresh context, adversarial) audited this draft against act §3 / §4 / N-3 / N-4, FP §5 / §7 / §13.8 / §14 – §16 and the decision register;
- the reviewer and I probed specific histories with `g6_correction_oracle.py` (pure research model, no database);
- I re-verified every major finding.

**The corrections are applied in place above** (§4, §5 (e), §7, §9, §10, §10a, §11, §12). Nothing ratified was changed; no decision was closed.

### 13.1 Verdict (recommendation)

**READY WITH EXPLICIT OPEN DECISIONS for C-1.** C-1 may start once:
- (1) **D-COR-2** is decided (defined on `live`);
- (2) the PR confirms that the **ratified no-consequence semantics of Annul / Void** (act §3) remain in force for the kernel. The **business meaning** of an appeal after re-entry stays OPEN with PO + Legal (§14.6);
- (3) this contract, as corrected, is approved;
- (4) the PO **authorises** C-1.

### 13.2 Findings

| # | Severity | Finding | Evidence | Applied correction |
|---|---|---|---|---|
| M1 | major | appeal against an exclusion **after re-entry** is not executable as the corpus words it | oracle: `Admit p0@0, End p0@2, Admit p1@3, Annul(End p0)` → **refused INV-9** (also Void; also variants). It works only after Annul / Void of p1's Admit. Act §3 / FP §16.2 ("no consequences") vs register :41 (BD-REV trim), :42 (BD-TERM4), FP §7 H. A "trim" reading would anyway absorb p1 entirely (BD-REV-ER) | new **D-COR-5** (§9) |
| M2 | major | C-1 (Admit / End / Annul / Void) can never create a consequence, so INV-EP / INV-COV and `TrimRevived` are unreachable in C-1 | reviewer enumeration of the C-1 fragment at H = 5, L = 5: 11,981 accepted, **0** with a consequence. **Derived:** no Correct ⇒ no cut ⇒ lifetime = BaseClaim | §4, §10, §10a, §11, §12 |
| M3 | major | D-COR-2 under-specified (`live` vs `active`) | oracle (verified by me): `Admit a, Annul(a), Void(a), Annul(Annul(a))` → a **not restored** (lifetime ∅); the control without Void → restored. An inert Correct of an annulled target is live and must stay voidable | §9 D-COR-2 |
| m4 | minor | a correction of an annulled target revives also via Void(Annul), and only once *every* live Annul / Void of the target is dead | probes: the value applies after Annul(Annul) and after Void(Annul); stays inert if the target was voided before revival | §10a Correct (5) |
| m5 | minor | "voided" target through its chain (root voided) is undefined | the oracle accepts it as inert | OPEN for C-2 |
| m6 | major (evidence) | §7 overstated oracle independence; "PHP oracle" does not exist | the Slice 0 contract was derived from FP §14.2; the FP scripts are not retained; 400 / 400 compares per-instant membership only | §7, §10, §10a |
| m7 | minor | §5 (e) must say "recorded consequences, not a replay" | FP §16.5 | §5 |

### 13.3 §10a classification (after corrections)

| Criterion | Class |
|---|---|
| Annul 1 – 4, 6 | CONSISTENT (R-2, R-6, R-16) |
| Annul 5 (restore or G\* refusal) | CONSISTENT, subject to D-COR-2 |
| Annul 7 (events) | CONSISTENT after correction (no `TrimRevived` in C-1) |
| Annul 8 | OPEN (D-COR-2) |
| Void 1 – 3, 5, 6 | CONSISTENT |
| Void 4 (excluded from statistics) | AMBIGUOUS for a kernel slice (no read model). Verifiable only at the projection / application level (C-3) |
| Correct 1 – 3, 6, 8, 10 | CONSISTENT |
| Correct 4 | AMBIGUOUS (m5) |
| Correct 5 | CONSISTENT after correction (m4) |
| Correct 7 | OPEN [PROP] (C-S0-1 carry-over) |
| Correct 9 | OPEN (BD-REV-ER) |
| "All three": differential equality | CONSISTENT after renaming (PHP **port** of g6) + spec-derived properties (§13.5) |
| appeal after re-entry | was MISSING, now OPEN (D-COR-5) |

### 13.4 Operation interactions (oracle-probed; bounds H = 5)

| Initial facts | Operation | Outcome | Determined by |
|---|---|---|---|
| a, Annul(a) | Annul(Annul) | a restored | act §4; FP §16.4 F |
| a, Annul(a) | Void(Annul) | a restored | FP §16.4 |
| a, Void(a) | Void(Void) | a restored | FP §16.4 |
| a, Void(a) | Annul(Void) | refused | N-3 |
| a, Annul(a) | Annul / Void(a) again | the oracle accepts it; a later appeal then fails to restore | **OPEN D-COR-2** (recommended: refuse) |
| a, Correct c | Annul(c) / Void(c) | the value reverts; the target's own cuts revive | FP §16.4 B / E |
| a, c1, c2 (both Correct(a)) | Annul(c2) | c1's value applies again | §14.2: value = max live Correct |
| a, c1, Correct(c1) | — | the latest live correction wins | FP §16.4 J |
| Annul or Void x | Correct(Annul / Void) | refused | N-3 |
| a, Annul(a) | Correct(a, v) | accepted, inert; effective once a is live again (Annul(Annul) or Void(Annul)) | act §4; m4 |
| a, Annul(a), inert c | Void(a), then Annul(Annul) | a stays dead, c stays inert | §14.2 (`live`) |
| revival would overlap | Annul(Annul) | refused (INV-9) | FP §16.4 F |
| p0 ended (exclusion), p1 re-admitted | Annul(End p0) | **refused (INV-9)** | **CONTRADICTORY in the corpus → D-COR-5** |
| Correct(End p0) trimming p1 | Annul(Admit p1), then Annul(Annul) | p1 revived, still trimmed | CFC+ |
| Correct(Correct) on a voided root | — | accepted, inert | OPEN m5 (C-2) |

### 13.4a Formal invariants and reconstruction properties

**State:**
- a Member log F = ⟨f₁ … fₙ⟩ (seq order);
- each fᵢ has kind, target, value, provenance, decidedAt and recordedAt.

**Definitions (FP §16.3, unchanged):** `live`, `Active`, `Superseded`, `Value`, `BaseClaim`, `LiveCut`, `ResolvedLifetime`; G\* = INV-9 ∧ INV-EP ∧ INV-COV on Res(F·f⁺).

| ID | Property | Kind | First slice |
|---|---|---|---|
| P-DET | Res is a function of F alone (pure fold; FP §14.4 well-founded recursion) | P-proof (FP) | C-1 |
| P-KNOWN | ∀ τ < recordedAt(f): KnownAt_{F·f}(τ) = KnownAt_F(τ) | P-arg (prefix) + test | C-1 |
| P-INV | every accepted F satisfies INV-9 ∧ INV-EP ∧ INV-COV | induction on commits (FP §14.5) + exhaustive | C-1 (INV-9) / C-2 (EP, COV) |
| P-REST | **superseded by §14.4** (explicit domain) | — | C-1 |
| P-EQ | **superseded by §14.4** (restricted to targets where both Annul and Void are admissible) | — | C-1 |
| P-COMM | **superseded by §14.4**: independence is defined by dependencies and affected periods, **not** disjoint target IDs (counterexample) | — | C-1 |
| P-LIN | every change in Res is attributable to a live fact along a target chain (no orphaned effect; CFC+) | exhaustive | C-2 |

### 13.5 Oracle independence and specification-derived tests

**Roles:**
- **Reference:** the PHP **port** of `g6_correction_oracle.py`, in test support.
- **Under test:** the production kernel (`Member::decide*`, `Resolution`).
- **Shared ancestry:** both derive from FP §14.2. **Correlated-error risk is real.**

**Properties that reduce correlated error** (computed by different means than the oracle; [PROP] test obligations):
- (a) **deletion counterfactual:** Res(F·Annul(c)) equals Res(F ∖ D(c)) using the *recorded* consequences. Computed by deleting facts, not by evaluating `live` (FP §15.2).
- (b) **P-EQ:** Annul and Void of the same target resolve identically.
- (c) **P-KNOWN:** prefix stability.
- (d) **C-1 closed form:** lifetime(q) = [value of q's live Admit, value of q's latest live End, or ∞), accepted iff pairwise disjoint. Written directly from §14.2's BaseClaim with no cuts.
- (e) **P-COMM:** commutation of independent reversals.
- (f) **replay equality** wherever a replay is possible (act N-1).

### 13.6 Decision impact on C-1

| Decision | Owner | Blocks C-1? | Why |
|---|---|---|---|
| D-COR-2 (live; refuse) | PR / PO | **yes** | refusal semantics of Annul / Void and P-REST |
| D-COR-5 | architecture: PR (that the act's no-consequence semantics stand). Business meaning of an appeal after re-entry: **PO + Legal** | **architecture part: yes; business part: no (C-3)** | §14.6 |
| BD-REV-ER | PO | no | absorption needs Correct |
| D-COR-1 (register vs act on Correct of annulled) | PR | no | Correct is C-2 |
| D-COR-3 (correctable attributes) | PO | no | C-2 |
| D-COR-4 (column name) | PR | no | C-4 |
| CQ-3 (authority) | PO + Legal + Governance | no (kernel). Blocks C-3 | the kernel records provenance; it does not judge competence |
| CQ-9 (appeal body; ex tunc / ex nunc) | PO + Legal | no (kernel). Affects D-COR-5 follow-up (Annul vs Void of the re-entry) and C-3 | — |

### 13.7 C-1: Definition of Ready / Definition of Done (TDD-first, mandatory)

> **Superseded by §15.6 – §15.7** (kept as audit trail). Differences: §13.7 conflated D-COR-5's architecture and business parts, and listed event *emission* in C-1 scope, whereas §14.3 K-E1 assigns emission to C-3.

**Scope:**
- the kernel only (`app/Contexts/Membership/Domain/Organisational/`);
- the fact kinds `Annul`, `Void`;
- target rules R-2 (decision provenance for Annul), R-6 (Annul(Void) refused), D-COR-2;
- stages 1 – 2 of `Resolution` (`live`, `active`);
- G\* (INV-9 is the only invariant that can fire);
- events `FactAnnulled` / `FactVoided`;
- the PHP port of the g6 oracle in **test support**.

**Excluded:** Correct, consequences, `TrimRevived`, persistence, the application layer, authority evaluation, HTTP, migration.

**Definition of Ready:**
- [ ] this contract (as corrected) approved;
- [ ] D-COR-2 decided;
- [ ] D-COR-5 (a) confirmed;
- [ ] PO authorisation of C-1 recorded;
- [ ] a story ID and a plan (`docs/plans/`) exist.

**Definition of Done (TDD-first):**
1. **RED first, every behaviour:** a failing test committed (or recorded) **before** its production code. One RED → GREEN cycle per rule: R-2, R-6, D-COR-2, liveness, Annul(Annul) / Void(Annul) / Void(Void) restoration, G\* refusal on revival, events.
2. **The PHP port of the g6 oracle**, with a golden cross-check against the Python outputs (transcription fidelity).
3. **Exhaustive differential test** over the C-1 fragment (bounds stated, e.g. H = 5, L ≤ 5), comparing verdicts, refusal reasons **and per-period lifetimes** (not only per-instant membership).
4. Specification-derived properties §13.5 (b), (c), (d), (e), and P-REST.
5. **Mutation measurement** of the new code (scope stated); every surviving mutant classified.
6. **No regression:** 93 unit + 12 architecture Membership tests green. Slice 0 / 1a behaviour is unchanged for Admit / End-only logs.
7. Developer guide `developer_guide/membership/04_…`, an implementation review, and a PR decision. Then **STOP**.

### 13.8 TODOs (priority)

- **P0:** PR / PO decide D-COR-2 and D-COR-5; approve this corrected contract; authorise C-1 (TDD-first).
- **P0:** PO decides BD-REV-ER and D-COR-3 (for C-2); PR decides D-COR-1 and D-COR-4.
- **P1:** CQ-3 / CQ-9 with Legal and Governance (C-3; D-COR-5 follow-up).
- **P1:** PBDIGIT-264 before C-4; formal Slice 1b acceptance + DV-1 countersignature.
- **P2:** retain the Final Freeze scripts if recoverable, or extend g6 to the full combination space as the reproducible reference.

**Files changed in this audit:** this contract draft only (corrections + §13). Uncommitted. No code, test, schema, configuration or database change; no tests run.

---

## 14. Reconciliation (2026-10-10; the PO's Membership mission "Correction Contract Reconciliation and C-1 Readiness")

> **Historical section (dated).** Its gate and status statements reflect its date. The current status is in §16 and in `2026-10-10-membership-c1-gate-decision-record.md`.

**This section supersedes conflicting wording above** (the rows marked "superseded by §14"). Nothing ratified is changed and no open decision is closed. **Evidence:** pure in-memory probes of `g6_correction_oracle.py` (no tests, no database).

### 14.1 Inconsistencies corrected

| # | Inconsistency | Correction |
|---|---|---|
| R-a | Annul (1) "the target is not live" mixed a precondition with a postcondition; Void (1) "any earlier fact" contradicted D-COR-2 | pre / post separation (§14.2) |
| R-b | C-1 completion required persistence, tenant-scoping and application race tests, although those layers are excluded from C-1 | slice-scoped criteria (§14.3, §14.5) |
| R-c | §5 (b) reversibility and P-EQ were stated without domains. Annul(Annul) is an appeal, not a reversal | explicit domains (§14.4) |
| R-d | P-COMM used "disjoint targets" as independence | **counterexample** (§14.4); dependency-based definition |
| R-e | D-COR-5 let a PR confirmation stand in for a business / legal interpretation | split architecture vs policy (§14.6) |
| R-f | C-1 target rules named Annul(Correct) although Correct is not in C-1 | boundary resolved (§14.7) |

### 14.2 Liveness: pre- and postconditions (D-COR-2: **APPROVED by the PO 2026-10-10 = refuse**; PR concurred 2026-10-10)

| | Annul(x) | Void(x) |
|---|---|---|
| **Pre: target** | x exists in the same Member, seq(x) < seq(f); **x has decision provenance**; **x is not a Void** (N-3) | x exists in the same Member, seq(x) < seq(f); any kind |
| **Pre: liveness** | **if D-COR-2 = refuse:** `live(x)` required, otherwise `TargetNotLive`. **If D-COR-2 = accept:** a no-op is allowed (not recommended: the §13.2 M3 counterexample breaks appeal restoration) | the same |
| **Pre: other** | `decidedAt` present (R-16) | — |
| **Admissible iff** | the pre-conditions hold **and** G\* holds on Res(F·f) | the same |
| **Post** | `live(x)` = false; facts whose liveness depends on x follow §14.2 of FP (`live` recursion) | the same |
| **Restoration** | if x = Annul(a) or x = Void(a) is **itself live**, the pre-condition holds, and a (or its chain) becomes live again, subject to G\* | the same |

**Notes:**
- **`live` ≠ `active` ≠ not `superseded`.** An inert Correct of an annulled target is **live** (it is not annulled or voided) but **not active**. It is therefore a valid target of Annul / Void under D-COR-2 = refuse.
- **Restoration works only through a live reversal fact.** Annul(Annul(a)) works when Annul(a) is live. Under D-COR-2 = refuse, the M3 history (a second Void of an already-annulled a) cannot arise.

### 14.3 C-1 kernel acceptance criteria (pre / post separated; kernel only)

**Admissible histories in C-1:** Admit, End, Annul, Void (§14.7).

| ID | Criterion | Rule | Test (RED first) |
|---|---|---|---|
| K-A1 | Annul(x) with x live, decision provenance, x ∈ {Admit, End, Annul} → accepted; afterwards ¬live(x) | R-2, §14.2 | unit + exhaustive differential |
| K-A2 | Annul(x) with `rule` / `attested` provenance → `TargetHasNoDecisionProvenance`; nothing appended | R-2 | unit |
| K-A3 | Annul(Void) → `AnnulOfVoidRefused` | R-6 / N-3 | unit |
| K-A4 | Annul without `decidedAt` → `DecidedAtRequired` | R-16 | unit |
| K-A5 | Annul(x) with ¬live(x) → `TargetNotLive` | D-COR-2 (PO-approved 2026-10-10; PR concurred 2026-10-10) | unit |
| K-V1 | Void(x), any live earlier fact (incl. Annul, Void) → accepted; afterwards ¬live(x) | R-4, §14.2 | unit + differential |
| K-V2 | Void(x) with ¬live(x) → `TargetNotLive` | D-COR-2 (PO-approved 2026-10-10; PR concurred 2026-10-10) | unit |
| K-R1 | appeal: a, Annul(a), Annul(Annul(a)) → a live again; resolution as F·a | R-3 | unit + property P-APPEAL (§14.4) |
| K-R2 | Void(Annul(a)) restores a; Void(Void(a)) restores a | FP §16.4 | unit |
| K-R3 | a restoration whose post-state overlaps → refused (INV-9), nothing appended | FP §16.4 F; R-11 | unit |
| K-G1 | any Annul / Void whose post-state violates INV-9 → refused (e.g. Annul(End p0) after re-entry: D-COR-5) | R-11, act §3 | unit + differential |
| K-H1 | KnownAt(τ) for τ < recordedAt(f) unchanged | R-12 | property P-KNOWN |
| K-E1 | an accepted Annul / Void records `FactAnnulled` / `FactVoided` evidence (the kernel decision carries the event data; emission is the application's job, C-3) | R-15 | unit |
| K-X1 | Admit / End-only logs resolve exactly as before C-1 (Slice 0 / 1a regression) | — | the existing 93 unit tests + differential |
| K-X2 | exhaustive differential: verdicts, refusal reasons **and per-period lifetimes** equal the PHP port of g6 (restricted to R-2, D-COR-2) within the stated bounds | — | differential |

### 14.4 Properties with explicit domains

| Property | Domain / preconditions | Statement | Concerns | Not applicable when |
|---|---|---|---|---|
| **P-REV-V** | F accepted; x appended last to F and live | F·x·Void(x) ≡ F (resolution) | per-period lifetimes | x is not the last fact (dependants exist) |
| **P-REV-A** | as P-REV-V, and x has decision provenance and x is not a Void | F·x·Annul(x) ≡ F | lifetimes | x is a Void (refused, N-3); x non-decision (refused); x = Annul(·) (an appeal, see P-APPEAL) |
| **P-APPEAL** | D-COR-2 = refuse; F·a accepted; Annul(a) accepted | F·a·Annul(a)·Annul(Annul(a)) ≡ F·a, **or** the last step is refused by G\* | lifetimes | D-COR-2 = accept (counterexample §13.2 M3) |
| **P-EQ** | x live; x has decision provenance; x not a Void | Res(F·Annul(x)) = Res(F·Void(x)) | **resolution only.** Statistics differ by design (Void excluded) | x = Void (Annul refused); non-decision x (Annul refused) |
| **P-KNOWN** | any accepted F·f | KnownAt_{F·f}(τ) = KnownAt_F(τ) for τ < recordedAt(f) | historical resolution | — |
| **P-COMM** | f, g reversals; **both orders accepted**; **independent**: the dependency closures of their targets are disjoint, **and** the sets of periods whose BaseClaim each changes are disjoint and do not overlap in valid time | F·f·g ≡ F·g·f | lifetimes | either order is refused. **Counterexample to the "disjoint targets" version:** `Admit p0@0, End p0@2 (=E0), Admit p1@3 (=A1)`. Annul(A1) then Annul(E0) is **accepted** (p0 = [0,∞)); Annul(E0) then Annul(A1) is **refused** (INV-9). Targets are disjoint but the claims interact |
| **P-CFC** (C-2) | Correct present | Res(F·Annul(c)) uses the **recorded** consequences; **not a replay** (F∖D(c) may be invalid, FP §16.5) | lifetimes | C-1 (trivial: no consequences) |

### 14.5 Deferred acceptance criteria (by slice)

| Slice | Criteria (moved here from §10a "All three") |
|---|---|
| **C-2 kernel** | Correct semantics and refusals (§10a Correct 1 – 10); chains and value precedence (max live Correct); consequences (g2 Δ + inherited clipping); `TrimRevived` / `PeriodTrimmed`; INV-EP / INV-COV now reachable, bounds ≥ 5 facts; P-CFC; Annul / Void **of Correct** facts (§14.7); BD-REV-ER, D-COR-1, D-COR-3, m5 |
| **C-3 application** | `AnnulFact` / `VoidFact` / `CorrectFact` handlers through `DecideAndAppend`; `MembershipAct` extension; authority evidence (CQ-3, G-AUTH-1); appeal authority (CQ-9); event emission; statistics / read model ("Void excluded"); IDEM-1 … 4 per command; application-level race tests (re-decide on conflict); the D-COR-5 business workflow |
| **C-4 persistence** | the schema / reference column (D-COR-4); same-Member composite FK on the target; encode / decode round-trips (target, value, consequences + `g2_version`); tenant scoping of new columns; PostgreSQL race tests; database isolation; **after PBDIGIT-264** |

**Annotation C-AMEND-1 (2026-10-10, additive; no existing §14.5 criterion is changed).** **Status: ADOPTED by the Human PO / ARB, 2026-10-10** (gate decision record, row 5, C-AMEND-1). An amendment of this PO-approved slice contract; **no ratified rule is changed**; **CQ-3 remains OPEN**; it authorises neither C-3 nor C-4. Source: C-1 independent review (`2026-10-10-membership-c1-correction-independent-review.md`, finding C4; §G 2 – 3); decisions H-3, D-A3, D-A4.

**C-3 application, additional entry criteria:**
1. An accepted Annul, Void or Correct decision carries the caller's provenance, including populated authority evidence, unchanged.
2. This is shown by a unit test with populated `AuthorityEvidence` and by a sensitivity mutant that drops or alters it.
3. §3.1 specifies `provenance(decision, authority, reason)`; the kernel `Provenance` has no attribute named for "reason".
4. **The meaning and representation of "reason", and of the associated authority evidence, are undecided. They stay OPEN under CQ-3** and must be decided explicitly before C-3 implementation.
5. No existing attribute is taken to represent "reason" without that decision.

**C-4 persistence, additional entry criteria:**
1. **F-1 (H-3):** the adapter must not call `effectiveAt()` on a fact that has no valid time (options: C-1 acceptance review §5).
2. Persistence is called *verified* only after Annul / Void encode / decode round-trips pass on an **isolated** PostgreSQL test database (ARB-SEC-01 gate); never on a shared or unapproved database.
3. "After PBDIGIT-264" (above) is unchanged.

### 14.6 D-COR-5: architecture vs business policy (OPEN)

| Layer | Content | Status | Owner |
|---|---|---|---|
| **Mechanical (derived)** | with the ratified rule "Annul / Void create no consequences" (act §3; FP §16.2), Annul(End p0) after a later Admit p1 is refused by G\* (INV-9). It is admissible only after p1's Admit is no longer live | **derived** (oracle-probed; also K-G1) | — |
| **Corpus conflict** | register :41 (BD-REV "trim the later period"), :42 (BD-TERM4 "yes (Annul)"), FP §7 scenario H ("Annul(End) → g2") assume the reversal trims | **contradiction** to be acknowledged | PR (architecture record) |
| **Architecture question** | do the act's no-consequence semantics stand (no act amendment)? | OPEN. **Blocks C-1** (an amendment would change the kernel) | **PR / ARB** |
| **Decision (2026-10-10)** | **CONFIRMED by the Human ARB (Dr. Nab Raj Roshyara)**: the no-implicit-consequences rule stands; the older trim wording is superseded for Annul / Void (decision request, source comparison C1 / C3). PR concurs | **APPROVED** | Human ARB |
| **Business question** | what does a successful appeal against an exclusion mean after re-entry? Must the re-entry be annulled or voided first? Is it a new membership or a continuation? Does BD-TERM4 apply unchanged? | OPEN. **Does not block C-1** (it governs the C-3 workflow and the records chosen) | **PO + Legal** (CQ-9, LQ-2) |

A PR confirmation of the architecture question **does not decide** the business question.

### 14.7 C-1 / Correct boundary (decided by evidence, not by choice)

- **Evidence:**
  - the kernel has no `Correct` fact class (`app/Contexts/Membership/Domain/Organisational/`: `Admit`, `End` only);
  - no persistence path writes a Correct (C-4 not built);
  - no migration has run (dev: the 1b migration is pending).
- **Therefore every log C-1 can see contains only Admit / End / Annul / Void.**
- **Consequences:**
  - the ratified target category **Annul / Void of a Correct is kept** in the contract;
  - its **tests are deferred to C-2** (§14.5). C-1 neither removes nor implements it;
  - C-1 must refuse a Correct as a target only once Correct exists, so no C-1 code path for it is required now.

### 14.8 Key state-transition table (C-1)

| # | Log before | Operation | Result | Rule |
|---|---|---|---|---|
| T1 | Admit a | Annul(a) | accepted; a dead; no periods | K-A1 |
| T2 | Admit a | Void(a) | accepted; a dead | K-V1 |
| T3 | Admit a, Annul(a) | Annul(Annul(a)) | accepted; a live again | K-R1 |
| T4 | Admit a, Annul(a) | Void(Annul(a)) | accepted; a live again | K-R2 |
| T5 | Admit a, Void(a) | Void(Void(a)) | accepted; a live again | K-R2 |
| T6 | Admit a, Void(a) | Annul(Void(a)) | refused `AnnulOfVoidRefused` | K-A3 |
| T7 | Admit a, Annul(a) | Void(a) | [D-COR-2] refused `TargetNotLive` | K-V2 |
| T8 | Admit p0, End p0, Admit p1 (later) | Annul(End p0) | refused INV-9 | K-G1 / D-COR-5 |
| T9 | Admit p0, End p0, Admit p1 | Annul(Admit p1), then Annul(End p0) | both accepted; p0 = [s0, ∞) | K-A1, K-G1 |
| T10 | Admit p0, End p0, Admit p1, Annul(Admit p1), Annul(End p0) | Annul(Annul(Admit p1)) | refused INV-9 (the revival overlaps) | K-R3 |
| T11 | Admit a (provenance `attested`) | Annul(a) | refused `TargetHasNoDecisionProvenance` | K-A2 |

### 14.9 Blockers, Ready / Done, verdict

**Blockers for C-1:**

| Blocker | Owner |
|---|---|
| D-COR-2 decision (`live`; refuse recommended) | PR / PO |
| D-COR-5 **architecture** question (the act's no-consequence semantics stand) | PR / ARB |
| approval of this reconciled contract | PR + PO |
| implementation authorisation | PO |

**Definition of Ready (C-1):** all four blockers resolved and recorded; a story ID and plan exist.

**Definition of Done (C-1, TDD-first):**
- K-A1 … K-X2 each driven RED → GREEN, one behaviour at a time, the failing run recorded before production code;
- the PHP port of g6 with a golden cross-check (transcription fidelity);
- exhaustive differential tests over the C-1 fragment, comparing verdicts, refusal reasons and **per-period lifetimes** (bounds stated);
- the properties P-REV-V, P-REV-A, P-APPEAL, P-EQ, P-KNOWN, P-COMM within their §14.4 domains;
- a mutation measurement of the new code (scope stated; survivors classified);
- no regression in the 93 unit and 12 architecture Membership tests;
- a developer guide, an implementation review and a PR decision; then STOP.

**Verdict (recommendation): READY WITH EXPLICIT OPEN DECISIONS.** The contract is internally consistent for C-1 after this reconciliation. Two decisions (D-COR-2; the D-COR-5 architecture question) and approval + authorisation remain. **This is not implementation authorisation.**

---

## 15. Final readiness gate and decision package (2026-10-10; the PO's mission "Final Correction Contract Readiness Gate")

> **Historical section (dated).** Its gate and status statements reflect its date. The current status is in §16 and in `2026-10-10-membership-c1-gate-decision-record.md`.

**Status:** decision package for human decision. **Not approval and not implementation authorisation.**

### 15.1 Repository and artefact verification

| Check | Result | Evidence class |
|---|---|---|
| file state | **untracked** (`?? …-correction-slice-semantic-contract-draft.md`), never committed, so **no Git diff exists**. Verification is by full read + mechanical checks | repository-verified |
| Markdown integrity | 0 tables with inconsistent column counts (scripted check, code spans and escaped pipes masked). Headings §1 – §15 in order, no gaps | repository-verified |
| truncated sentences | none found. The truncation seen in the PO's transcript is **terminal rendering** (`cut -c`) of edit commands, not file damage | repository-verified |
| broken references | none. "§14.10" in R-11 refers to **FP §14.10** ("G\* (layering)", `…-final-proof.md:721`), which exists | repository-verified |
| unsuperseded contradictions | **4 found and repaired in place** (§15.2 F-1 … F-4). Each superseded passage is marked; nothing deleted | repository-verified |
| other files changed by this contract work | **none.** `git status`: the only Membership-contract file touched is this one. The other modified files belong to other sessions or earlier commits | repository-verified |
| oracle probes cited in §13 / §14 (T1 – T11, M3, P-COMM counterexample) | executed in this session against `g6_correction_oracle.py` (in-memory). Re-runnable; **not re-run in this gate** | session-verified (inherited) |
| test counts 93 / 12 / 27 | from the audit run of 2026-10-09 (`…-theory-synthesis-…-audit.md` §R). **Not re-run** (no tests in this mission) | inherited |
| ratified sources | act §3 / §4 / N-3 / N-4; FP §5, §7, §13.8, §14 – §16; register lines quoted. Read in this session | repository-verified |

### 15.2 Findings (ranked)

| # | Severity | Section | Defect | Impact | Repair (applied) |
|---|---|---|---|---|---|
| F-1 | **major** | §10 (C-1 row) | "Blocked by: D-COR-2 only" omitted the D-COR-5 architecture question | could start C-1 with a contested kernel rule | now "D-COR-2 **and** the D-COR-5 architecture question" |
| F-2 | **major** | §12 | "needs only … approval and … D-COR-2" (same omission) | as F-1 | now lists both decisions + PO authorisation |
| F-3 | **major** | §13.7 | stale DoR / DoD: conflated D-COR-5 architecture / business; put event *emission* in C-1 (contradicts §14.3 K-E1) | wrong gate and scope | marked **superseded by §15.6 – §15.7** |
| F-4 | minor | Decision register; §11 | "D-COR-1 … 4"; no C-1 status row | incomplete register | now "… 5"; C-1 row = BLOCKED with reasons |
| F-5 | minor | §3.2 Void targets | "any earlier fact" without the liveness pre-condition | ambiguity vs D-COR-2 | appended "must be **live** if D-COR-2 = refuse (§14.2)" |

### 15.3 C-1 semantic boundary: verification (classification)

| Item | Verdict | Class |
|---|---|---|
| Annul ≠ Void (meaning, statistics, authority, targets) | holds (§3.1 / §3.2; FP §13.8) | ratified requirement |
| Annul needs decision provenance + `decidedAt` | holds (R-2, R-16; K-A2, K-A4) | ratified requirement |
| D-COR-2 refuses Annul / Void of a non-live target | stated as **OPEN**, recommendation refuse (§14.2, K-A5, K-V2) | unresolved architecture decision |
| pre (target live) vs post (target not live) | separated (§14.2) | derived invariant |
| Annul(Annul) is an appeal, not an ordinary reversal | holds (R-3; P-APPEAL separate from P-REV-A) | ratified requirement |
| Annul / Void create no consequences | holds (R-7) — under confirmation (D-COR-5 architecture) | ratified requirement, pending confirmation |
| G\* on the post-state; refusal on violation | holds (R-11; K-G1, K-R3) | ratified requirement |
| KnownAt / TrueAt | holds (R-12; P-KNOWN; K-H1) | ratified requirement |
| C-1 only Admit / End / Annul / Void | holds by evidence (§14.7) | implementation constraint |
| table ↔ refusals ↔ events ↔ criteria | consistent: T1 – T11 map to K-A1 … K-X2. Refusal names are **PROPOSED** (§4). Events: the kernel carries the data, the application emits (K-E1) | proposal requiring approval (names) |
| properties have domains | holds (§14.4) | derived invariant |
| P-COMM needs semantic independence | holds (§14.4, counterexample) | derived invariant |
| oracle = bounded evidence, not proof / probability | holds (§7, §13.5) | — |
| no test presented as ratified policy | holds. Every OPEN item is marked; the proposed refusal names are PROPOSED | — |

### 15.4 Decision record proposal: D-COR-2

- **Question:** may Annul or Void target a fact that is already **not live** (already annulled or voided by a live reversal)?
- **Recommended option:** **refuse**, with `TargetNotLive` (name PROPOSED).
- **Rationale:**
  - (1) **audit clarity:** every reversal fact has an effect; no dormant reversal facts to interpret later;
  - (2) **appeal integrity:** the ratified appeal rule R-3 ("Annul(Annul) restores") holds unconditionally only if no second reversal can stack on the same target.
- **Evidence (oracle, in-memory):**
  - `Admit a, Annul(a), Void(a), Annul(Annul(a))` → a **not restored** (lifetime ∅);
  - control `Admit a, Annul(a), Annul(Annul(a))` → a restored (§13.2 M3).
- **Consequences if adopted:**
  - Annul(Annul(a)) and Void(Annul(a)) remain valid, because their target, the Annul, is live;
  - Void(Void(a)) is valid;
  - a reversal of an inert (live, not active) Correct remains valid (§14.2);
  - a second reversal of an already-dead target is refused, so the correct path is to reverse the existing reversal.
- **Consequences if rejected (accept as a no-op):**
  - P-APPEAL fails (counterexample);
  - restoration becomes history-dependent;
  - audits must interpret stacked reversals;
  - K-A5 / K-V2 change to "accepted, no effect".
- **Owner:** Principal Reviewer (architecture) with PO (it affects user-visible refusals). **Approval required:** a recorded decision. **Not ratified by this document.**

### 15.5 Decision record proposals: D-COR-5

**(A) Architecture question.** Owner: **Principal Reviewer / ARB.** It blocks C-1.

- **Question:** does the ratified rule "Annul and Void create no consequences; they only change liveness and may revive superseded consequences" remain unchanged in the kernel?
- **Ratified sources:**
  - act §3 ("Annulment and Void do not create new membership consequences");
  - FP §16.2 (Annul / Void: "no; revives superseded consequences only");
  - register freeze-audit amendment (line 215: "Annul / Void create consequences? no; revive only").
- **Observed consequence (derived, oracle):** Annul(End p0) after a later Admit p1 is refused by G\* (INV-9). It is admissible only after p1's Admit is no longer live (T8 – T10).
- **Invariant consequence:** INV-9 is preserved. Making Annul generate consequences would (i) change the act, and (ii) make a "trim" always absorb the later period entirely (the BD-REV-ER case).
- **Confirmation requested:** "The act's no-consequence semantics stand for the kernel. Older wording (register :41 BD-REV 'trim the later period', FP §7 scenario H 'Annul(End) → g2') is recorded as superseded by act §3 / FP §16.2 for Annul / Void."
- **Not decided here.**

**(B) Business / legal question.** Owner: **PO + Legal** (CQ-9, LQ-2, BD-TERM4). It does **not** block C-1: it governs the C-3 workflow and the records chosen, not the kernel rule.

- **Question:** when an exclusion End is successfully appealed after the person has re-entered, what is the intended outcome?
- **Possible outcomes:**
  - the re-entry is annulled or voided and the original period continues;
  - the re-entry stands and the gap is covered by other means;
  - the appeal is decided ex nunc (an End(kind) per LQ-2).
- Whether BD-TERM4 ("never happened") applies unchanged in this case.
- **Not inferred from (A).** Its answer selects *which* facts the competent organ records; the kernel then validates them under (A).

### 15.6 C-1 Definition of Ready (final)

- [ ] D-COR-2 decided and recorded (§15.4);
- [ ] D-COR-5 (A) confirmed and recorded (§15.5);
- [ ] this contract approved (PR + PO), including the PROPOSED refusal names;
- [ ] PO authorisation of C-1 recorded;
- [ ] a story ID and a plan (`docs/plans/`) exist;
- [ ] D-COR-5 (B), BD-REV-ER, D-COR-1 / 3 / 4, CQ-3, CQ-9 explicitly recorded as **out of C-1 scope** (they remain OPEN).

### 15.7 C-1 Definition of Done (final; TDD-first)

1. **RED first** for each of K-A1 … K-X2 (§14.3): a failing test run recorded **before** its production code; one behaviour per cycle; GREEN with the minimal change in the existing kernel (`Member::decide*`, `Resolution` stages 1 – 2, `RefusalReason`, new `Annul` / `Void` fact classes).
2. **PHP port of the g6 oracle** in test support + a golden cross-check against the Python outputs (proves transcription fidelity only).
3. **Exhaustive differential tests** over the C-1 fragment (bounds stated, e.g. H = 5, L ≤ 5): verdicts, refusal reasons and **per-period lifetimes**.
4. **Domain-restricted properties** (§14.4): P-REV-V, P-REV-A, P-APPEAL, P-EQ, P-KNOWN, P-COMM, plus the C-1 closed form (§13.5 d).
5. **Mutation measurement** of the new code (scope stated; every survivor classified).
6. **Regression:** the existing Membership domain / application unit tests and the 12 architecture tests green. Admit / End-only behaviour unchanged (K-X1).
7. **Developer guide** `developer_guide/membership/04_…`, an **implementation review**, a **PR decision**. Then STOP.
8. **Out of scope:** no persistence, application, authority, HTTP, migration or Correct changes.

### 15.8 Remaining blockers (all slices)

| Blocker | Owner | Decision required | Evidence | Slice |
|---|---|---|---|---|
| D-COR-2 | PR + PO | refuse / accept a reversal of a non-live target | §15.4 | **C-1** |
| D-COR-5 (A) | PR / ARB | confirm no-consequence semantics | §15.5 A | **C-1** |
| contract approval | PR + PO | approve §1 – §15 incl. PROPOSED names | this document | **C-1** |
| C-1 authorisation | PO | explicit go | — | **C-1** |
| BD-REV-ER | PO | absorption: refuse / allow | §9; 35 / 14,365 histories | C-2 |
| D-COR-1 | PR | annotate the register vs act §4 | §2 | C-2 |
| D-COR-3 | PO | correctable attributes | §9 | C-2 |
| CQ-3 | PO + Legal + Governance | authority per operation | §9 | C-3 |
| CQ-9 / D-COR-5 (B) | PO + Legal | appeal body; appeal after re-entry | §15.5 B | C-3 |
| G-AUTH-1 | PR (Slice 2 contract) | authority port shape | audit A.4 | C-3 |
| D-COR-4 | PR | reference column name | §9 | C-4 |
| PBDIGIT-264 | PO authorisation | persistence hardening | plan §9 – §10 | C-4 |
| Slice 1b formal acceptance + DV-1 countersignature | PR; PO | acts | ARB-MEM-02 | C-4 / cutover |
| cutover gates C-0 … C-10 | various | — | plan §4 / §9.5 | cutover (after C-1 … C-4, ARB-MEM-01) |

### 15.9 Contract approval checklist

- [ ] §2 rules R-1 … R-16 match their sources (spot-checked in this session).
- [ ] D-COR-1 … 5, BD-REV-ER, CQ-3, CQ-9 shown as OPEN; nothing closed by inference.
- [ ] Every superseded passage is marked (§10a, §13.4a, §13.6, §13.7, §5 (b) / (c)).
- [ ] The proposed refusal names are accepted, or renamed.
- [ ] The C-1 scope (§14.3, §15.7) is acceptable; C-2 / C-3 / C-4 deferrals (§14.5) accepted.
- [ ] Oracle evidence accepted as bounded and correlated-error-prone (§7, §13.5), not as proof.

### 15.10 Verdict

**READY FOR HUMAN DECISION.** The artefact is structurally sound and internally consistent after the F-1 … F-5 repairs. C-1 is **not** ready for authorisation until D-COR-2 and D-COR-5 (A) are decided and the contract is approved. **No implementation is authorised by this document.**

---

## 16. Recorded decisions (2026-10-10)

The authoritative register is `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md`. Summary:

| Item | Status |
|---|---|
| D-COR-2 | **APPROVED by the PO** (refuse with `TargetNotLive`) **+ CONCURRED by the Principal Reviewer** (ChatGPT; supplied 2026-10-10) |
| D-COR-5 (A) | **CONFIRMED by the Human ARB (Dr. Nab Raj Roshyara) 2026-10-10**: Annul / Void change liveness only; no implicit period consequences; established consequences may revive; invariants reject invalid states; Correct-generated consequences separate; appeal after re-entry stays with CQ-9. PR concurs |
| D-COR-5 (B) | OPEN (PO + Legal) |
| this contract | **APPROVED by the PO 2026-10-10** (formal) |
| C-AMEND-1 | **ADOPTED by the Human PO / ARB 2026-10-10**: additive §14.5 C-3 / C-4 entry criteria; CQ-3 OPEN; plus the status-label corrections (H1, register line, the C-1 row below; G2-b). Register row 5 |
| C-STATUS-1 | **APPROVED by the Human PO / ARB 2026-10-10** (subject to the Principal Reviewer's wording review): the stale C-1 start-gate statuses (the "Decision register" C-1 row; register row 4) are marked superseded by additive notes: plan approved in principle with conditions → re-evaluation: all applicable conditions closed, eligible to start → implemented (`PBDIGIT-268`) → formal acceptance PENDING (G3). Historical text kept. Register row 6. Condition satisfied and P1 – P6 executed in `76b1192de` (provenance: register row 6); **G3 PENDING** |
| C-1 | **Implemented, formal acceptance PENDING.** Plan approved and gate re-evaluated 2026-10-10 (gate decision record: start-gate checklist, "C-1: ELIGIBLE TO START", and the "Gate re-evaluation" section, "C-1 is eligible to start"); implemented as `PBDIGIT-268` (`f7c251354` … `5765b9fa3`), acceptance correction `9ae69b7bc` … `d90bf8af9`; independent review: ACCEPT WITH EXPLICIT CONDITIONS (no blocking findings). **Formal acceptance pending G3 (PR, then Human PO / ARB).** Limitations: PostgreSQL persistence unverified; the wider Membership unit suite has errors outside C-1; evidence bounded. *(Corrected 2026-10-10 under C-AMEND-1 / G2-b; the earlier text "BLOCKED until recorded" was stale.)* |
| C-1 scope (PO) | test-first Annul and Void in the existing Membership kernel only. **Not authorised:** Correct and consequences, application authority changes, persistence changes outside the slice, legacy replacement, cutover, live writes |
