# E0a-4: Principal Falsification and Architecture Stress Test

**Type:** Adversarial research for **Principal Reviewer** review · **decides no business rule; implements nothing** · **Date:** 2026-10-08
**Under test:** `2026-10-08-e0a3-membership-theory-and-architecture-synthesis.md` (**E3**): the act-history theory and **Architecture D**
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Classification of conclusions:**
> - **A:** proven by logic (from stated definitions);
> - **B:** proven under explicitly stated assumptions;
> - **C:** computationally verified within a finite bound (*not* a proof);
> - **D:** strong empirical evidence;
> - **E:** architectural derivation;
> - **F:** business hypothesis;
> - **G:** implementation choice.
>
> **Computation in this phase:** `stress.py`: **101,040** canonical histories (every history starts with an admission; ≤ 4 acts), with **explicit effective times** (4 valid-time points; backdated and future-effective acts), record order, ANNUL, CORRECT and type changes. A counterexample is **conclusive**; the absence of one is **C** only. Scripts are summarised in Appendix A.

---

## 1. Executive verdict

**Outcome B: the theory survives with amendments.** E3's core holds:
- historical membership truth is a function of recorded, dated, attributable facts;
- `IsMember` is identical whichever identity model is chosen;
- the (person, organisation) consistency boundary (D) is the strongest candidate.

**Five E3 claims were wrong or too strong. Each is falsified by a concrete counterexample:**
1. *"Record time is needed only if a successful appeal annuls an ending (A1)."* **Falsified:** every **backdated** act rewrites past truth (admit, end, renew, correct), and so do annulment and correction. The import feature already supplies backdated admissions (historical `joined_at`). **Record time is required now.**
2. *"A, B and D are the same history partitioned differently; D-ID.1 only changes naming."* **Weakened:** the facts are shared, **but identity-scoped meanings differ** between A and B: admission date, tenure since admission, number, and "the membership" (1,310 histories). D-ID.1 is a semantic choice, not only a naming one.
3. *"I5 is enforced internally under D."* **Counterexample:** End → re-Admit → Annul (or Correct) of the End makes two non-terminal memberships overlap (74 histories). I5 also needs a rule for **retroactive changes to endings after a re-entry**. D can enforce that rule locally; A cannot.
4. *"One term interval per period suffices."* **Falsified:** a late renewal after a lapse extends `[start, until)` across the lapse gap, so lapsed months retroactively count as membership. The term needs **segments**. The legacy code has exactly this defect (`joined_at` fixed, `membership_expires_at` extended).
5. *"Admission = one act with a basis."* **Incomplete:** membership needs **two declarations**, the organisation's decision **and** the person's declaration (application or invitation acceptance). Paths differ in their **order**. Direct add has **no** evidence of the person's declaration.

**Architecture D remains the best candidate**, strengthened by finding 3: the ending-reversal guard needs the same cross-period access as I5.

The corrected hypothesis is in §30.

---

## 2. Claims under test (verdicts in §§25–27)

| Claim | Statement |
|---|---|
| C1 | membership truth can be reconstructed from a dated act history |
| C2 | A, B and D are partitions of the same underlying history |
| C3 | Architecture D is the smallest robust consistency boundary |
| C4 | I5 can be enforced internally under D |
| C5 | lapse is derived, not a lifecycle state |
| C6 | suspension needs no stored lifecycle state |
| C7 | End + Annul + Correct are sufficient for ending and correction semantics |
| C8 | a separate Membership Number Registry is unnecessary |
| C9 | authority is outside Membership, accessed through a port |
| C10 | event-like immutable acts do not require Event Sourcing |
| C11 | `IsMember` is partition-invariant |
| C12 | the proposed aggregate is transactionally correct |

## 3. Evidence classification (key inputs)

| Input | Class |
|---|---|
| EM-VOC-001/002, EM-EO/ENT/FM, ADR_20261004_1549, ADR-T11 | adopted (constraints, not under test) |
| the import supplies historical `joined_at` / `expires_at` (`MemberImportController:72`, tutorial) | **D** (the backdating source exists) |
| renewal: `max(old, now) + duration`, `joined_at` unchanged (`MembershipRenewalController:55-90`) | **D** (IMPL) → the lapse-gap erasure (§9) |
| application (person declares) → approval (organisation decides); invitation (organisation offers) → acceptance (person declares); direct add (organisation only) | **D** (IMPL + guides) |
| concurrent memberships, sections, primary/secondary memberships in the PublicDigit corpus | **none found** |
| production membership data | **not available** (§20) |

## 4. Ontology stress test

| Criterion | **O1: act primitive** (E3) | **O2: membership primitive** (lifecycle stored) | **O3: fact primitive** (decision, temporal, restriction and attested facts, with provenance) |
|---|---|---|---|
| Explains the corpus | mostly. **Fails for imports:** a migrated membership is not an *act* done in PublicDigit, it is an **attested historical fact** | yes, but it reproduces the legacy status contradictions | **yes**: acts are one fact type; attestations (import, migration) another |
| Smallest vocabulary | small | smallest | small (+1 fact type) |
| Historical truth | yes (append-only) | **no**: a mutable state needs a separate audit log, and re-derivation is impossible | yes |
| Business language | **closest** (officers speak of decisions) | close ("member is active") | slightly more abstract |
| Accidental implementation assumptions | low | **high** (state enum) | low |
| Survives A, B, D | yes | yes | yes |
| Survives future rules | yes | poorly | **best** (a new attested source fits) |

**Verdict:**
- **O3, with acts as its principal subtype.** Every primitive is a **recorded fact with provenance**: an *act* (decided in PublicDigit by an actor), or an *attestation* (imported or migrated from another register).
- **This amends E3's "the primitives are acts".** Class **E**. The amendment is forced by the import counterexample, which is **D**.
- O2 is rejected (it reproduces the contradictions; FR §11).

## 5. Identity stress test

The **fact base is shared** across A, B and D. **Identity-scoped meanings are not** (computation: 1,310 / 101,040 re-entry histories):

| Concept | A | B | D | Nature of the difference |
|---|---|---|---|---|
| `IsMember(p,o,t)` | = | = | = | none (C11) |
| "the membership" (reference) | a new one after re-entry | the same | the period (D-ID.1 decides whether it is called the membership) | **business identity** |
| admission date of the current membership | the latest admission | the first admission | both available (period / root) | **business identity** |
| tenure / "member since" | since the latest admission | since the first admission (or cumulative) | all three computable | **business semantics** (new parameter: Q-TEN) |
| membership number | per membership | one | root or period | business identity (D-ID.2) |
| legal acts, audit references | membership id | membership id + date | period id | reference granularity |
| fee references | per membership | per membership + period label | per period | reference granularity |
| statistics (members, joiners, leavers per year) | re-entry counts as a new joiner | re-entry counts as a returning member | either, by definition | **reporting semantics** |

**Result:** D-ID.1 changes **business identity and reporting semantics**, not just naming. **E3 §1.2 and §24 understated this.** D still **represents** every variant, so the *structure* is decision-robust, but the *meaning of identity-scoped predicates* is a genuine business decision. Class **C** (the computation) + **E**.

## 6. Architecture D stress test (Aggregate = Person × Organisation)

| Adversarial case | Repository evidence | Real domain possibility? | Is it multiple memberships? | D survives? |
|---|---|---|---|---|
| concurrent memberships | none | rare | — | yes (overlapping periods are representable; I5 would change) |
| independent membership types | none (single-valued type) | — | **one membership + a type attribute** | yes |
| separate legal bases | none | e.g. a member by statute plus by contract | unclear | yes (two bases on one admission, or I5 exception) |
| multiple membership numbers | legacy FM-/AM- prefixes per type | possible if numbers encode type | one membership + a number history | yes (number per period / type segment) |
| independent authority decisions | — | yes (each act has its own decision) | no | yes (acts) |
| separate fee obligations | the Fee aggregate is already separate | yes | **multiple obligations** | yes (outside the aggregate) |
| independent suspension of a section | none | yes (a section ban) | a **restriction** with scope | yes (RestrictionSet, §13) |
| **multi-section clubs** (*Mehrspartenverein*: a club membership plus section enrolments with own fees and own join/leave dates) | **none in the corpus**; general domain knowledge | **yes, real in German associations** | **one membership + several section enrolments** (or sections as sub-organisations) | **yes, if** sections are modelled as Governance units / sub-organisations or as enrolments with their own lifecycle **outside** the membership root. Otherwise the root grows. **Flagged as a future falsification test** |
| honorary + ordinary | legacy: honorary = fee-exempt type | an honour status is usually **attached** to membership | **one membership + an attribute** (or a type) | yes |
| primary + secondary membership (federations) | none | yes, but across **different organisations** | different (p,o) | yes |
| sections legally creating separate memberships | none | possible in some federated structures | then sections are **organisations** | yes ((p, o_section)) |

**No case falsifies D.** One real-world structure, **multi-section clubs**, is unevidenced in the corpus. It would put pressure on the root only if section enrolments were folded into it. **Recommendation:** keep section enrolments outside the root by construction. Class **E/F**.

## 7. I5 falsification

The scenarios, classified:

| Scenario | Classification |
|---|---|
| ordinary + honorary; full + associate; temporary + permanent | **one membership + attributes** (type / status) |
| parent organisation + section | **multiple affiliations / organisations** |
| primary + secondary | different organisations |
| multiple statutory memberships | not evidenced (F) |
| multiple membership contracts | **multiple obligations** |
| multiple simultaneous applications | **applications, not memberships** (guarded) |
| re-admission while the previous membership is suspended | **forbidden by I5** (the membership is non-terminal), correctly |
| **End → re-Admit → retroactive Annul or Correct of the End** | **a genuine counterexample**: two legitimately accepted, now-overlapping non-terminal memberships (74 histories; necessary acts = End + Admit + {Annul or Correct}) |

**Verdict:**
- I5 **survives as a domain invariant candidate** (F).
- Its **enforcement needs two guards**: (g1) at admission (existing), and **(g2) on any retroactive change to an ending** (Annul, or a Correct that moves an ending later) when a later admission exists.
- **(g2) needs a business rule (Q-ANNUL-RE),** with three options:
  - (r) refuse the reversal while a later admission exists;
  - (m) merge: the later admission becomes void and redundant, so the original membership simply never ended (consistent with D-TERM-4 A1);
  - (o) allow the overlap (abandons I5).

## 8. Partition invariance

- **`IsMember`: C** (0 differences in 137,256 abstract-time and 101,040 explicit-time histories) **+ B** (proof sketch §19.4 under a stated targeting assumption).
- **Not invariant:** admission date, tenure, the number and the membership reference (§5).
- **The richer histories requested** (End → Admit → Suspend → Restore → Renew → End → Annul → Admit; a future End → withdrawal → re-entry; correction → re-evaluation; type change → End → re-entry) are covered by the act alphabet and depth ≤ 4 **as sub-sequences only**. Full 8-act chains exceed the bound. The inductive argument (§19) covers them, under its assumption.

## 9. Temporal logic

**There are three clocks, not four.** "Valid time" and "effective time" coincide for domain facts: a fact is valid from when it takes effect. The distinct clocks are **effectiveAt** (valid), **decidedAt** and **recordedAt**.

| Case | What was true | What the organisation believed | What PublicDigit knew | Decided | Effective | Clocks needed |
|---|---|---|---|---|---|---|
| 1 decided today, effective tomorrow | member until tomorrow | the same, from today | from recording | today | tomorrow | effective ≠ decided |
| 2 decided today, effective yesterday | **already ended yesterday** | from today | from recording | today | yesterday | **all three:** truth at "yesterday" changes after the fact |
| 3 import today, effective six months ago | member for six months | for six months (old register) | **from today only** | ? (historical) | −6 months | effective + recorded (+ an attested decision, if known) |
| 4 termination today, effective next month | member until next month | — | — | today | +1 month | effective ≠ decided; **withdrawal window** |
| 5 termination effective last month, appealed today | (A1) never ended / (A2) ended, then restored | changes today | changes today | earlier + today | last month | **record time** to show what was believed before the appeal |
| 6 historical record corrected today | the corrected value | — | changes today | — | historical | record time |
| 7 migrated from another system | per source | per source | from migration | source-dependent | historical | effective + recorded + provenance |

**Computation (C):** past valid-time truth is rewritten by the last act in histories ending with **END (4,116), CORRECT (1,359), RENEW (1,957), ADMIT (837), ANNUL (263)**.
- Restricted to histories **without backdating**, only **ANNUL (92), CORRECT (94)** and **RENEW (206)** rewrite the past.
- The RENEW cases are the **lapse-gap erasure** artefact of the single-interval term (finding 4). They vanish with term segments.

**Verdict:**
- **Record time is required as soon as any backdated or retroactive fact exists.** The import guarantees that it does (class **D**). **E3's "record time iff A1" is falsified.**
- **Decision time is required** whenever effective ≠ decided (cases 1, 2, 4).
- **Cost:** with append-only facts carrying `recordedAt`, "what was known at τ" is **free**: filter the facts with `recordedAt ≤ τ`. **No bitemporal table machinery is needed.** That is the decisive argument for O3 (§4).

## 10. Historical truth (H1–H12; A / B / D)

**Notation:** P1/P2 = periods; ✓/✗ = in force at the relevant t; `rec` = needs record time.

| History | Identity A / B / D | Number (retained / reissued) | Period | Notable truth | Record truth |
|---|---|---|---|---|---|
| H1 Admit → Lapse → Renew | M1 / M1 / P1 | — | P1 (2 **term segments**) | in force ✓ / lapsed ✗ / in force ✓. **The lapse gap stays ✗ only with segments** | late renewal: `rec` if backdated |
| H2 Admit → Suspend → Restore | M1 / M1 / P1 | — | P1 | in force; suspended (restriction) in between | — |
| H3 Admit → End | M1 / M1 / P1 | — | P1 | ended ⇒ ✗ | — |
| H4 Admit → End → Admit | **M1, M2** / **M1** / P1, P2 | same / new | P1, P2 | ✓ ✗ ✓ under all three. **Admission date: A = 2nd, B = 1st** | — |
| H5 Admit → future End → Annul | M1 / M1 / P1 | — | P1 | never ended (the annulment came before effectiveness) | **no rewrite** |
| H6 Admit → effective End → Annul | M1 / M1 / P1 | — | P1 | (A1) never ended | **rec** (the belief changed) |
| H7 Admit → TypeChange → End → Admit | M1, M2 / M1 / P1, P2 | per D-ID.2; type prefix ❓ | P1 (type X→Y), P2 (type ❓ at admission) | the type at t comes from the segment | — |
| H8 import historical | M1 / M1 / P1 (attested) | the imported number | P1 | ✓ from the historical date | **rec** (unknown before the import) |
| H9 backdated Admit | M1 / M1 / P1 | — | P1 | ✓ from the backdated date | **rec** |
| H10 correction of a historical date | M1 / M1 / P1 | — | P1 | the corrected truth | **rec** |
| H11 number retained | M1, M2 / M1 / P1, P2 | **same** (root) | — | — | — |
| H12 number reissued | M1, M2 / M1 (B′) / P1, P2 | **new** (period) | — | — | — |

**Recognised:** ✓ at Admit (if REC-0 = no). Otherwise from a Recognise fact. **No history is unanswerable under D with O3 + three clocks + term segments.** A and B each lose one dimension (A: cross-membership continuity is computed outside the aggregate; B: undated references).

## 11. Aggregate boundary stress test

| Question | Finding | Class |
|---|---|---|
| History size | about 1 renewal per year + rare acts → **~100 facts in 50 years** per (p,o) | E (estimate; no data, §20) |
| Write frequency per (p,o) | yearly (renewal), rare (suspend, end) | E |
| Fee operations independent? | **yes:** the Fee aggregate is outside (already separate in the code) | D |
| Type changes independent? | **no:** a type change starts a term or type segment within a period | E |
| Restrictions independent? | membership suspension: inside. **Governance bans: outside** | E |
| Does every command need all periods? | **no.** Only **Admit** (I5 g1) and **Annul/Correct of an End** (I5 g2) need cross-period access. All other commands touch the current period | **E**: this is exactly what justifies the (p,o) boundary |
| Unbounded growth / contention | history grows slowly. Contention needs concurrent writes on one person's membership: negligible | E |
| **Not internal to D** | **number uniqueness within the organisation** (N-U) spans persons, so it needs a uniqueness mechanism or an allocator **outside** any (p,o) aggregate | **C8/C12 amendment** |

**Verdict:** the (p,o) boundary is **derived from the invariants** (g1, g2), not from conceptual ownership. **C12 is supported under assumptions** (fees outside, number uniqueness outside, sections outside).

**15. Migration argument** (the commission's §15): **secondary evidence only.** E3 §21/§22's "best migration fit" is **demoted**. It is true but irrelevant to correctness. D is justified by g1 + g2 alone.

## 12. Act model stress test (Correct; append-only; Event Sourcing)

| | **X: original + correction fact** | **Y: mutable record + audit log** | **Z: immutable fact + correction relation** |
|---|---|---|---|
| Legal history | ✓ | partial (the log must be complete) | ✓ |
| Auditability | ✓ | ✓ | ✓ |
| Reproducibility ("as known at τ") | ✓ | **✗** (needs log replay) | ✓ |
| Simplicity | medium | **simple** | medium |
| Migration | ✓ (an attestation + corrections) | ✓ | ✓ |
| Performance | fine (small histories) | fine | fine |

**Verdict:**
- **Z (≡ X)**: Correct is a **new fact** with a `corrects` relation to the corrected fact. **Correct must be able to target any fact kind** (dates, type, basis), not only End. That amends C7.

**Event Sourcing vs append-only facts + a materialised current state vs current state + a decision history:**
- **The domain needs** immutable, attributable, dated facts with record time (legal history, "as known at τ"). It does **not** need state rebuilt *only* from events, event versioning as a persistence protocol, or projections as the primary model.
- **Append-only facts + a materialised current state** satisfy every requirement found.
- **Current state + a decision history** fails "as known at τ" for attested and corrected facts.

**C10: STRONGLY SUPPORTED** (E, domain argument).

## 13. Suspension stress test

| Dimension | In force + suspended (restriction) | Not in force |
|---|---|---|
| member count | counted, unless S1 is defined | not counted |
| dues | continue (unless a rule pauses them; E0b) | none |
| term | runs (unless a term-extension fact applies) | — |
| quorum / services / election / committee | restricted per right | none |
| standing | evaluated separately | n/a |

**Is "Suspended" one concept?** **No.** The corpus contains:
- **(i)** a decided **full suspension** of membership rights (`Member::suspend`; guides);
- **(ii)** decided **scoped bans** by other organs (PI-5: one election) → Governance;
- **(iii)** **derived** restrictions from standing (the legacy "voice-only when fees are partial"; a typical statute rule).

**RestrictionSet model: SUPPORTED.**
- A membership carries **decided restrictions** (full suspension; any membership-scoped restriction).
- Consumers also receive **Governance bans** and **standing-derived restrictions** as **separate facts**.
- "Suspended" is the name of the **full, decided, membership-scoped restriction**.
- **C6 holds** (no stored lifecycle state). Class **E** + **D**.

## 14. Recognition stress test

| Path | Initiates | Decides | Accepts (the person's declaration) | Exists from | Can be rejected / withdrawn | Evidence to keep |
|---|---|---|---|---|---|---|
| application | person | organ | the application itself | the decision | rejected (decision); withdrawn (before the decision) | the application + the decision |
| invitation | organisation | organ (at the invitation) | **the acceptance** | the acceptance | declined; offer withdrawn/expired | the invitation + the acceptance |
| import / migration | organisation | historical (unknown) | historical (unknown) | the historical effective date | — | source + attestation |
| direct add | organisation | organ | **none recorded** | the decision | — | the decision only |

**Finding:** a membership is legally a **meeting of two declarations** (the person's will to belong; the organisation's admission). `Admit(basis)` **loses** the person's declaration and the **order**.

**Amendment:** the `Admit` fact records **both** components:
- **the organisation's decision:** actor, authority evidence, decidedAt;
- **the person's declaration:** a reference to the application or acceptance, or `attested` / `absent`;
- plus the basis.

Application and Invitation remain **process aggregates outside**.

**New question Q-CONSENT** (later, with REC): *may an organisation admit a person without a recorded declaration from that person* (direct add)? Class **D** (paths) + **F** (the rule).

## 15. Authority boundary

| Concept | Owner | Stored in Membership? |
|---|---|---|
| actor | — | yes |
| **claimed capacity** (the role or organ the actor acted for) | — | **yes** |
| actual authority (competence rules, statutes) | Governance | **no** |
| authority **check result** at command time (allowed, by which rule/policy version) | Governance answers; Membership records | **yes, as an evidence snapshot** |
| decision (the act) | Membership | yes |
| effectiveness | Membership | yes |

**Amendment to C9:** store an **authority evidence snapshot**, not a bare `authorityRef`. A reference into an evolving Governance model can dangle or change meaning, while a snapshot keeps the historical act explainable. **An act later found to be unauthorised** is handled by an **Annul/Correct fact with that reason**, never by rewriting.

**C9: SUPPORTED with this amendment** (E).

## 16. Membership Number

| Placement | Retained | Reissued | Organisation namespace | Migration / import | Duplicates | Correction | Reporting | Re-entry |
|---|---|---|---|---|---|---|---|---|
| on the root | ✓ | ✗ | uniqueness across roots needs an outside mechanism | ✓ (imported number) | outside mechanism | a Correct fact | ✓ | ✓ (kept) |
| on the period | ✗ (copied manually) | ✓ | same | ✓ | same | same | ✓ | ✓ (new) |
| on the "relationship" | = the root in D | — | — | — | — | — | — | — |
| on the admission fact | = period-level | — | — | — | — | — | — | — |
| external registry | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |

**Minimum model:**
- the number is **an attribute on the root or the period** (set by D-ID.2);
- **uniqueness and allocation** within the organisation is an **organisation-level uniqueness constraint / allocator**. That is a **G** implementation mechanism, not a domain registry.

**C8: SUPPORTED** (no registry *concept*). **Amendment:** N-U is the one membership invariant that is **never internal** to a (p,o) aggregate.

## 17. Type history

- The **type definition** (fee, duration, approval) is configuration **outside** the aggregate.
- The **type assignment** is **a segmented attribute of a period** (`typeFrom(t)`), changed by a ChangeType fact. **It is not a separate concept with its own invariants.** The only invariant it joins is *term segments derived from the type's duration at grant or renewal* (if adopted), and that lives inside the period.
- **Fees according to type:** the Fee aggregate snapshots the amount (existing `fee_amount_at_time`).
- **Rights according to type:** a type may carry **classifications** that **Election criteria** read (a type at t). **It never grants an entitlement** (EM-ENT-001/007 ADOPTED). `grants_voting_rights` stays an open disposition (FR §13); **the theory forbids only its *granting* semantics.**
- **Number prefix by type:** if numbers encode the type (legacy FM-/AM-), a type change would imply a number change. That is a **business question** (deferred; DP §5 Q3).

**Verdict:** ChangeType is **a Membership fact** (it changes a period attribute). "MembershipTypeAssignment" is its **result**, not a separate aggregate. Class **E**.

## 18. Computational verification

| Property | Result (`stress.py`, 101,040 histories, explicit times) | Class |
|---|---|---|
| C11: IsMember equal under A, B, D | **0 differences** | C |
| admission date / tenure equal under A and B | **differ in 1,310** (re-entry) | C (a counterexample: conclusive) |
| I5 under later knowledge | **violated in 74**; necessary acts: End + Admit + {Annul (26), Correct (48)} | C (a counterexample: conclusive) |
| past truth rewritten (all) | END 4,116 · RENEW 1,957 · CORRECT 1,359 · ADMIT 837 · ANNUL 263 | C (counterexamples to E3's claim) |
| past truth rewritten (no backdating) | ANNUL 92 · CORRECT 94 · RENEW 206 (lapse-gap artefact) | C |
| INV-2 (ended ⇒ ¬in force) | **not tested computationally:** it is **definitional** (§19.1). A first check in the script was vacuous (a coding error, found and discarded) | A |

**Symmetry reduction:** histories are canonicalised to start with an admission (acts before the first admission are no-ops), with a 4-point valid time and record order = sequence order.

**Limits:**
- one (p,o);
- ≤ 4 acts;
- 4 time points;
- one ANNUL/CORRECT target rule (the latest End);
- no Recognise;
- type changes are toggles.

## 19. Inductive proof attempts (over appended facts)

**Setting:** H is a finite set of facts. Each step appends one fact α (append-only). Predicates are evaluated on H with annulled facts removed, corrections applied, and the targeting rule *a non-admission fact applies to the latest admitted period whose start ≤ its effective time*.

1. **`Ended ⇒ ¬InForce`:** InForce is *defined* as `Recognised ∧ Effective ∧ ¬Ended`. **Proven by logic (A).** No induction is needed.
2. **Lapse ≠ End:** `Ended(m,t) ⇔ ∃` a non-annulled End fact on m with effectiveAt ≤ t.
   - **Base:** ∅ has no End facts.
   - **Step:** only an End fact adds one. Annul removes one; Correct moves one; Admit, Renew, Suspend, Restore and ChangeType add none. The term passing adds **no fact**.
   - ⇒ `Lapsed` never implies `Ended`. **Proven (A)** under these definitions.
3. **Holder/organisation immutability:** no fact kind carries a holder change. A wrong holder is handled by Void + a new Admit (D-TERM-5 c1). **Proven under that assumption (B).**
4. **IsMember partition invariance:** under A, B and D the set of admitted **segments** and the facts applied to them are identical, given the shared targeting rule. IsMember = ∃ an in-force segment, which does not depend on how segments are grouped into identities. **Proven under the assumption (B)** that the targeting rule and the acceptance decisions are identical across models. *Without* the shared targeting rule (for example B applying a renewal across a gap), it fails. That is the lapse-gap issue, fixed by term segments.
5. **Historical preservation:** facts are never deleted; Annul and Correct add facts. Therefore the as-known state at τ = the evaluation of `{f : recordedAt(f) ≤ τ}` is stable for every τ already passed. **Proven by construction (A)** under append-only.
6. **I5:**
   - **Base:** ∅ holds.
   - **Step:** Admit preserves it (guard g1). End decreases the count. Renew, Suspend, Restore and ChangeType do not change it.
   - **Annul or Correct of an End can increase the count at times after a later admission.**
   - **The induction fails exactly there** (the §7 counterexample). **I5 is provable only with guard g2.**

## 20. Statistical analysis

**Statistical validation is unavailable.**
- No production membership dataset is accessible to this research.
- The development database contains demo and test data. It is not representative, and its analysis is outside this read-only mandate.
- No frequencies are asserted. The aggregate-size estimate (§11) is labelled **E** (an estimate), **not** statistics.

## 21. ML assessment

**ML not justified.**
- There is no real dataset (§20).
- Terminology and lifecycle vocabularies were enumerated **exactly** by code reading: three status vocabularies, six re-entry behaviours.
- Counterexamples were found by exhaustive search, which is strictly stronger than learned pattern discovery.
- **If** production data becomes available, two uses would then be justified: anomaly detection of transition sequences, and duplicate-identity detection (persons with several accounts). They are pattern discovery only, never semantics.

## 22. Requirements derived (not implemented)

| ID | Requirement | Business rationale | Domain rule / invariant | Acceptance criteria / test scenario | Aggregate | Context |
|---|---|---|---|---|---|---|
| **REQ-MEM-ID** | every membership period and every (p,o) root is identifiable; the identity-scoped meanings (admission date, tenure, number) follow the D-ID decisions | references, reports | INV-1; D-ID.1/.2 | H4/H11/H12 give the decided identity and number | root | Membership |
| **REQ-MEM-TIME** | every fact carries effectiveAt, decidedAt (where it is a decision) and recordedAt | backdating, imports, appeals | three clocks (§9) | as-known-at-τ and true-at-t both answerable (cases 1–7) | root | Membership |
| **REQ-MEM-HISTORY** | facts are append-only; annulment and correction are new facts with relations; term **segments** per renewal | legal history; lapse visibility | §12 Z; §10 H1 | after a late renewal, the lapse gap is not in force; as-known-at-τ is stable | root | Membership; Audit |
| **REQ-MEM-END** | End(kind, decidedAt, effectiveAt, reason); Annul before or after effectiveness; reversal after re-entry follows Q-ANNUL-RE | statutes | INV-2; g2 | H5 no rewrite; H6 as decided; End→Admit→Annul obeys Q-ANNUL-RE | root | Membership; Election (FM-5 later) |
| **REQ-MEM-SUSPENSION** | full suspension = a decided membership-scoped restriction interval; other restrictions are separate facts | rights | RestrictionSet | IsMember / InForce / Suspended published separately | root (decided); Governance (bans) | Membership; Governance |
| **REQ-MEM-RECOGNITION** | Admit records the organisation's decision + the person's declaration (or attested/absent) + basis | validity | REC-0; Q-CONSENT | each path stores both components or marks them absent | root | Membership |
| **REQ-MEM-MULTIPLICITY** | ≤ 1 non-terminal period per (p,o), with guards g1 (Admit) and g2 (ending reversals) | prevent double membership | I5 | the §7 counterexample is refused, merged or allowed per Q-ANNUL-RE | root | Membership |
| **REQ-MEM-NUMBER** | the number on the root or period (D-ID.2); unique within the organisation (N-U) via an organisation-level mechanism | credential | N-U | two people in one organisation never share a number; two organisations may | outside (uniqueness) + root/period (value) | Membership |
| **REQ-MEM-TYPE** | the type is a segmented period attribute; never an entitlement | fees, criteria | INV-11 | the type at t is answerable; no voting fact derives from it | root | Membership; Election reads it |
| **REQ-MEM-AUTHORITY** | each decision fact stores an authority evidence snapshot; competence rules live in Governance | explainable history | C9 amended | the act shows who acted, in which capacity, and the check result | root (snapshot) | Membership ← Governance |

## 23. A / B / D decision matrix (qualitative; **no weights**: a weighting is a governance judgement)

| Criterion | A | B | D |
|---|---|---|---|
| domain correctness (IsMember) | ✓ | ✓ | ✓ |
| invariant locality (g1, g2) | **✗ both cross-aggregate** | ✓ | ✓ |
| historical truth (O3, three clocks, segments) | ✓ | ✓ | ✓ |
| temporal correctness | ✓ | ✓ (needs segments **and** periods) | ✓ |
| identity clarity | ✓ (per membership) | ✗ undated references | ✓ (both levels) |
| auditability | ✓ | ✓ | ✓ |
| business expressiveness (all D-ID cells, tenure variants) | A-cells only | B-cells only | **all** |
| aggregate consistency | needs cross-aggregate coordination | ✓ | ✓ |
| bounded-context integrity | ✓ | ✓ | ✓ |
| migration feasibility (**secondary**) | medium | good | good |
| implementation complexity | low + coordination | medium | medium |
| future evolution | rework if D-ID changes | rework | **parameter change** |
| Election integration | ✓ | ✓ (date-bound references) | ✓ |

**D remains strongest.** **A is now weaker than in E3**: guard g2 adds a second cross-aggregate invariant.

## 24. Decision-robustness matrix

**Key:** ● changes · ○ unchanged.

| Open question | Ontology | Identity | Aggregate boundary | Invariant | Command | Event | Persistence | Only naming / config |
|---|---|---|---|---|---|---|---|---|
| D-ID.1 | ○ | ● (meaning of identity-scoped predicates) | ○ (D) | ○ | ○ | ○ (names) | ○ | **semantic parameter + naming** |
| D-ID.2 | ○ | ● (number scope) | ○ | ○ | ○ | ○ | ○ | parameter |
| D-TERM-1 | ○ | ○ | ○ | ○ | ○ | ○ | ○ | enumeration (End kinds) |
| D-TERM-3 | ○ | ○ | ○ | ○ | ● (Annul before effect allowed?) | ○ | ○ | rule |
| D-TERM-4 | ○ | ○ | ○ | ● (g2 semantics) | ● | ○ | ○ | **true decision** |
| **Q-ANNUL-RE** (new) | ○ | ● (merge voids a later admission) | ○ | ● (I5 g2) | ● | ○ | ○ | **true decision** (with D-TERM-4) |
| D-TERM-5 | ○ | ○ | ○ | ○ | ● (Void vs Correct) | ○ | ○ | rule |
| D-SUSP | ○ | ○ | ○ | ○ | ○ | ○ | ○ | **predicate definition** |
| REC-0 | ○ | ○ | ○ | ○ | ● (Recognise fact) | ● | ○ | small |
| **Q-CONSENT** (new) | ○ | ○ | ○ | ● (Admit validity) | ● | ○ | ○ | rule |
| I5 | ○ | ○ | ○ | ● | ○ | ○ | ○ | rule (structure unchanged) |
| N-U | ○ | ○ | ○ | ● | ○ | ○ | ● | constraint scope |
| **Q-TEN** (new: tenure / "member since") | ○ | ○ | ○ | ○ | ○ | ○ | ○ | **derived-predicate definition** |
| three clocks / segments / O3 | **architecture (no business decision needed)** | | | | | | | |

**True architectural decisions** (Principal Reviewer): O3; three clocks; term segments; D; g1 + g2 placement; the authority snapshot; RestrictionSet; the number-uniqueness mechanism outside the aggregate.
**Business parameters:** D-ID.1/.2, D-TERM-1, D-SUSP, REC-0, I5, N-U, Q-TEN.
**True business decisions with structural effect:** D-TERM-4 + Q-ANNUL-RE, Q-CONSENT.

## 25. Claims supported

| Claim | Verdict | Basis |
|---|---|---|
| C5 lapse derived | **STRONGLY SUPPORTED** | proof §19.2 (A); needs term segments (amendment) |
| C6 no stored suspension state | **SUPPORTED** | RestrictionSet (§13); D + E |
| C8 no number registry | **SUPPORTED** | §16 (uniqueness is a mechanism, not a concept) |
| C10 no Event Sourcing needed | **STRONGLY SUPPORTED** | §12 domain argument |
| C11 IsMember partition-invariant | **SUPPORTED UNDER ASSUMPTIONS** | C (238k histories) + B (§19.4: shared targeting and acceptance) |
| C3 D smallest robust boundary | **SUPPORTED** | derived from g1 + g2 (§11). The migration argument is demoted |
| C9 authority outside via a port | **SUPPORTED** (amended: an evidence snapshot) | §15 |
| C12 aggregate transactionally correct | **SUPPORTED UNDER ASSUMPTIONS** | fees, number uniqueness and sections outside (§11) |

## 26. Claims weakened

| Claim | Verdict | Why |
|---|---|---|
| C1 truth from a dated act history | **SUPPORTED UNDER ASSUMPTIONS (amended)** | needs **facts** (acts + attestations), **three clocks**, **term segments** |
| C2 A/B/D are partitions of one history | **WEAKENED** | the facts are shared; **identity-scoped meanings differ** (business, not naming) |
| C4 I5 internal under D | **SUPPORTED UNDER ASSUMPTIONS** | needs guard g2 + Q-ANNUL-RE. Internal under D, cross-aggregate under A |
| C7 End + Annul + Correct sufficient | **SUPPORTED UNDER ASSUMPTIONS** | Correct must target any fact; needs Q-ANNUL-RE; Void for never-valid |

## 27. Claims falsified

| E3 statement | Counterexample |
|---|---|
| "record time is needed iff D-TERM-4 = A1 (or a retroactive correction)" | backdated Admit, End and Renew rewrite the past (§9); imports supply backdating |
| "D-ID.1 changes only naming" | admission date, tenure, number and reporting differ (§5) |
| "one current term per period suffices" (DR proportionality, implicit in E3) | lapse-gap erasure by a late renewal (§9, H1) |
| "Admit(basis) captures recognition paths" | the person's declaration and its order are lost (§14) |
| "migration fit supports D" (as an argument) | demoted to secondary (it is not falsified as a fact) |

## 28. Remaining business decisions

**MUST (unchanged six):** D-ID.1, D-ID.2, D-TERM-1, D-SUSP, REC-0, I5.

**Later; three new questions, all found by counterexample:**
- **Q-ANNUL-RE:** *if an ending is overturned after the person was re-admitted, what happens?* Refuse / merge / allow overlap. Decide **with D-TERM-4**.
- **Q-CONSENT:** *may an organisation make someone a member without that person's own application or acceptance?* Decide with REC.
- **Q-TEN:** *what does "member since" / membership duration mean after a gap?* Since the first joining / since the latest joining / total time as a member. Needed by `CommitteeLevel.minMembershipYears` and by election criteria.

**Unchanged:** D-TERM-3, D-TERM-5, REC-IMP, REC-INV, N-U.

## 29. Remaining architecture decisions (Principal Reviewer)

1. **O3**: facts with provenance (acts + attestations) as the primitive.
2. **Three clocks** on facts (effective, decided, recorded); append-only.
3. **Term segments** per renewal (fixes the lapse-gap erasure).
4. **Architecture D**, justified by g1 + g2 (migration secondary).
5. **The authority evidence snapshot** on decision facts.
6. **RestrictionSet**: full suspension inside; bans and standing-derived restrictions as separate facts.
7. **Number uniqueness** as an organisation-level mechanism outside the aggregate.
8. **Section enrolments** (if ever needed) outside the root.
9. **The EM-VOC-001 path** if D-ID.1 = A (unchanged from E3).

## 30. Final recommendation

**Outcome B: E3 survives with amendments.**

**Corrected architecture hypothesis (E0a-4):**

```text
Facts (append-only, each with effectiveAt · decidedAt? · recordedAt · provenance)
  ├── acts:         Admit{orgDecision + authority snapshot, personDeclaration|attested|absent, basis, type}
  │                 Renew(→ new term segment) · ChangeType · Suspend/Restore (full, membership-scoped)
  │                 End{kind} · Annul(target) · Correct(target, any fact) · Void(Admit) · [Recognise iff REC-0]
  └── attestations: imported / migrated historical facts (source, provenance)

Consistency boundary: one root per (person, organisation)                                  [D, justified by g1 + g2]
  root:   identity · holder · organisation · [number if retained]
  period: identity · term segments · type segments · restriction intervals · ending · [number if reissued]
  guards: g1 Admit (no non-terminal period) · g2 Annul/Correct of an End (Q-ANNUL-RE)

Derived (never stored as truth): Effective, Lapsed, Ended, Suspended, Recognised, InForce, IsMember,
  admissionDate / tenure (per Q-TEN, D-ID.1), as-known-at-τ (filter recordedAt ≤ τ)

Outside: Application · Invitation (process) · Fee aggregate · Governance (authority rules, bans, sections)
         · number uniqueness / allocation (organisation-level) · Election (consumes published facts)
```

**Is E0b justified?** **Yes, as research, in parallel.** MembershipType → obligations → dues → standing depends only on **period + type segments + recorded facts**, which are now stable under every open decision. **No implementation** is justified: G1/G3/G4/G5 and the MUST decisions are still open.

**Next phase:**
1. The **Principal Reviewer adopts or rejects** §29 items 1–8 (the architecture core) and the amended claim verdicts.
2. The **PO answers** the six MUST questions (G §16 Q1–Q6). The three new later questions are added to the register.
3. **E0b standing research**, and in parallel the **canonical Membership domain model (facts catalogue, guards, derived predicates)** on the amended D.

---

### Appendix A: scripts (session scratchpad; research aids)

- **`stress.py`:**
  - one (p,o); valid time 0..3; TERM = 2;
  - acts ADMIT / RENEW / SUSPEND / RESTORE / END / CHTYPE with an effective time, plus ANNUL / CORRECT (targeting the latest End);
  - the record order is the sequence order;
  - an admission is accepted **at record time** under the then-current knowledge (g1) and is not recomputed;
  - models A / B / D;
  - checks: IsMember equality, admission-date and tenure equality, I5 under later knowledge, and past-truth rewriting by the last act.
- **`i5break.py`:** the necessary-act analysis for I5 violations, and rewriting restricted to histories without backdating.
- **E3's `theory.py`:** retained for the 137,256 abstract-time results.

**Every count is a bounded-search result (C). Counterexamples are conclusive; their absence is not.**

**STOP.** Nothing implemented, migrated, repaired or committed.
