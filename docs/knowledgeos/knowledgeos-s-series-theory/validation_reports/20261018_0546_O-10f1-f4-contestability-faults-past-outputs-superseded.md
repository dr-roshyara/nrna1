# O-10f1–O-10f4 (and O-10i1): contestability, constrained faults, rules reading past outputs, superseded decisions

| Field | Value |
|---|---|
| Clock | 2026-10-18 05:46 |
| Process | A worker sub-agent drafted; a separate refute-first reviewer re-derived the key results **before** reading the draft, using its own code (`rc.py`, not importing the worker's). Integrated by Session B. |
| Review status | **PASS WITH CONDITIONS — INTERNALLY REVIEWED, SAME MODEL FAMILY.** All seven required corrections are applied below. **This section governs the worker draft where they differ.** |
| Artifacts | `../synthetic-checks/o10f1-f4/worker/`: draft `c64874d9…ebcb0`; PROTOCOL `34e6ce9de03220952a684a15dc73d5910b053bf0cb5b30613038f8b27ade8887` (frozen 05:39:59, before code); code `2ffee359…a5a0`; RESULTS `b8d6894a…a2ed`. `../synthetic-checks/o10f1-f4/reviewer/rc.py` |
| Evidence weight | The exhaustive checks K1 (69,120 triples) and K2 are substantive guards. K5c, K6 and K7 are single-instance confirmations of hand algebra and add **no** evidential weight. The reviewer's 20,000 random instances found 0 mismatches for F2-1, F2-1a and F2-2. |
| Authorization | Synthetic and analytical only. **Gate: BLOCKED.** |

## 0. Assumption made explicit: output-log fidelity (OF)

**OF:** the o-log is trusted as a faithful record of **what was decided**, not of whether it was right. V1 and V2 used this assumption without stating it.

## 1. O-10f2: constrained fault models (the core result)

**Setting.**
- A **fault model** Φ is the set of possible (x, o) pairs.
- The record (o, E(x)) is **verifiable** iff equal (o, E) always implies equal correctness bits [o = F(x)].
- x and x′ are **confusable** iff F(x) ≠ F(x′) and some output o ∈ {F(x), F(x′)} is possible for both.

| ID | Statement | Status |
|---|---|---|
| **F2-1** | **Verifiable ⇔ E never merges a confusable pair** | PROVED, both directions; exhaustive K1, plus 20,000 independent random instances by the reviewer: 0 mismatches |
| F2-1a | "E determines F ⇒ verifiable" holds for **every** Φ. Only V2's (⇒) depends on the fault model | PROVED |
| F2-2 | V2's equivalence holds for all E ⇔ every pair with different correct decisions is confusable. This is strictly weaker than V2's assumptions (a) + (c) | PROVED; exhaustive K2 |
| F2-3 | Under Φ = {F, F+2}: x and x′ are confusable iff \|F(x) − F(x′)\| = 2. Empty evidence is verifiable iff no two values of F are 2 apart; (⇒) fails if, in addition, F takes ≥ 2 values. *Correction:* for general E, (⇒) fails as soon as some F-distinct pair is not 2 apart. With F = 2x on {0,1}, empty evidence is **not** verifiable | PROVED + COUNTEREXAMPLE |
| **F2-4 (corrected)** | Under OF, **decision-recoverable ⇒ verifiable** (the view contains o, and d determines [o = d]). Only the converse fails (F2-3). *The draft's "neither implies the other" is refuted.* | PROVED (reviewer correction) |
| F2-6 / F2-7 | The same characterization holds in multi-step form per step. **Joint necessity across all steps is OPEN** | PROVED (per step); open (joint) |
| F2-8 | Under the joint fault model "all honest or all inverted", step 2 is verifiable **without step-2 evidence, given full step-1 evidence and the step-1 output**. Verifiability shifts to step 1. A per-step analysis over-demands evidence, so the joint fault model must be used | COUNTEREXAMPLE (dependence stated per the reviewer) |

**Consequence.**
- The evidence that **always** suffices is evidence that determines the rule's result (F2-1a). That is robust to any fault model, given OF and truthful attestation of the evidence.
- Less evidence suffices only under knowledge of the fault model, which is a trust assumption in its own right.

## 2. O-10f1: contestability

**Definition** (summary). A party p is given by four things:
- its trust set T_p;
- its record access A_p;
- its private knowledge K_p;
- its verification operations Op_p.

It also has its own fault model Φ_p, which is observer-relative.

**Two kinds of establishment.**
- **Epistemic establishment:** p can come to know a fact.
- **Demonstrative establishment:** p can convince an adjudicator J, under J's trust. Adversarial selection by the party is not modelled.

**Four levels.**
- (i) reconstruction;
- (ii) correctness under the cited rule version;
- (iii) procedural basis (version applicability, authority);
- (iv) remedy. **This level is outside mathematics.** Only necessary record conditions are given.

| ID | Statement | Status |
|---|---|---|
| F1-1 (corrected) | If T_p ⊆ T_a, A_p ⊆ A_a, K_p = ∅, Op_p ⊆ Op_a **and Φ_a ⊆ Φ_p**, then level-(ii) contestability for p ⇒ auditor verifiability **for decisions concerning p**. Full auditor verifiability needs this across every party class | PROVED (the dominance condition and scope added per the reviewer) |
| F1-2, F1-3 | Contestability is **strictly stronger** when (a) the party distrusts an attestation the auditor trusts, or (b) another party's field is hidden from p | COUNTEREXAMPLES |
| F1-4 | **Incomparable** in general: a party that privately knows its own input can contest where the auditor cannot verify. *This revises V5:* "T_p ⊆ T" alone does **not** order the two | COUNTEREXAMPLE (with F1-2) |
| F1-5 | Levels (i), (ii) and (iii) are logically independent. The (iii) ⇏ (ii) witness needs unconstrained outputs. Level (iii) needs an effective-date registry for rule versions and an authority record | COUNTEREXAMPLES; the (i) ⇏ (ii) witness relies on V1 |

## 3. O-10f3: rules reading past outputs

**Two notions of correctness.**
- **Local:** the rule was applied correctly to what actually happened.
- **Global:** the output equals what a fault-free run would produce.

**Which notion governs is a STAKEHOLDER POLICY CHOICE.**

| ID | Statement | Status |
|---|---|---|
| F3-1 | Local correctness reduces to F2 (under OF) | PROVED |
| F3-2, F3-3 | Local ⇏ global, and global ⇏ local | COUNTEREXAMPLES |
| F3-4 | With unconstrained outputs: global verifiability ⇔ the evidence determines the fault-free decision. This can require the **whole raw-input history** | PROVED |
| F3-5 (corrected) | If evidence may read outputs, (o-log, attested correctness-bit log) is the coarsest verifiable view. It is strictly coarser than (o-log, attested d-log) **only under condition W** (the same faulty o with different d possible); \|range\| ≥ 3 is necessary, **not sufficient**. *Counterexample to the draft table:* range {0,1,2} with Φ = {F, F+2}, where c = 0 forces d = o − 2. All trust moves to the attester, so "verifiable" here means "given a truthful attester" | PROVED (i–ii); the table corrected per the reviewer |

## 4. O-10f4 / O-10i1: verifying superseded decisions

**Four correction models**, formed by two axes: do later **evidence** corrections apply, and do later **rule** changes apply? **Which model applies is a STAKEHOLDER POLICY CHOICE.**

**Three distinct questions:**
- historical verification (was D(h_t, t) right with what was known and in force at t?);
- verification of the current authoritative decision;
- whether a supersession was justified.

"Hindsight correctness" is a further, separate question.

| ID | Statement | Status |
|---|---|---|
| F4-1 | Each question reduces to §1, with the relevant snapshot of evidence and rule as input | PROVED |
| F4-2, F4-4 | Stores holding only **current** evidence, or only the **current** version mapping, cannot verify historical decisions | COUNTEREXAMPLES |
| F4-3 | A **bitemporal evidence** log suffices. Bitemporal **decisions** alone do not | PROVED (via O-10i B3) |
| F4-5 | What non-retroactive models simplify, and what they do not | ANALYTIC |
| F4-6 (a)(b) | After lawful deletion, correctness stays **independently** verifiable only if the retained evidence still separates confusable pairs (assuming x-only evidence; with output-reading attestations, the criterion is plain determination of c). Otherwise verification becomes **attestation-only**, and only if a trusted attestation was recorded **before** deletion | PROVED (assumption added per the reviewer) |
| F4-6 (c) (corrected) | **From the retained record**, re-evaluation under a new rule is possible iff the retained data determines the new rule's result. It **can** still be done if a party re-supplies the evidence. **Checking the old output** against the new rule needs only separation of the new rule's confusable pairs, which is weaker | PROVED (two cases distinguished per the reviewer) |
| F4-6 (d)(e) | Hash commitments and attestation expiry | ANALYTIC; cryptographic assumptions **not proved** |

**Consequence.** **Retroactivity policy and deletion policy constrain each other.** A stakeholder choice in one limits the other.

## 5. Architecture mapping (ARCHITECTURAL PROPOSAL; not adopted)

| Result | Operational implication | Candidate requirement | Process | Candidate context | Stakeholder choice |
|---|---|---|---|---|---|
| F2-1, F2-1a | Evidence that determines the rule's result is always enough. Less evidence relies on fault-model knowledge | For critical decisions, retain rule-determining evidence, or document the fault-model assumption | audit | Evidence & Provenance | whether fault-model assumptions are acceptable |
| F1-2, F1-4 | Parties and auditors may need **different** evidence | Define per-party access and trust explicitly | appeals | Governance | who may see what; whose attestations are trusted |
| F1-5 | Procedural challenges need version-applicability and authority records | Keep an effective-date registry for rule versions and a log of authority | governance acts | Governance, Contracts | — |
| F3-2–F3-4 | Local vs global correctness differ | Declare which correctness notion applies | assessment | Contracts & Assessment | **local or global** |
| F4-2–F4-3 | Historical verification needs bitemporal **evidence** | Make evidence stores bitemporal **if** historical verification or retroactive models are required | corrections | Evidence & Provenance | which correction model |
| F4-6 | Deletion limits later verification and retroactivity | Before deletion, retain a minimal verifiable evidence set or an attestation | retention | Evidence & Provenance, Governance | retention vs deletion vs retroactivity |

## 6. Open

- **Joint multi-step necessity (F2-7).**
- Models where the party's operations are restricted; probabilistic records; parties acting adversarially.
- Combination with concurrent corrections (B5) and correction horizons (O-10i2).
- Legal adequacy of the adjudicator model, the authority records, tombstones and hash retention. **Outside mathematics.**
