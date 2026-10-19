# D6 review, D7 synthetic checks, O-7, and reviewer nominations

| Field | Value |
|---|---|
| Author | Session B (Professor), as worker and integrator |
| Written | 2026-10-17, clock time 18:49–18:55 |
| **Timestamp disclosure** | The two previous reports are named `…_1830_…` and `…_1850_…`, but their files were created at **18:17** and **18:21**. Those name stamps were assigned, not read from the clock. This report uses the real clock time, so it sorts *before* `…_1850_…` although it was written after it. The **true order is 1805 → 1830 → 1850 → this report.** The files are not renamed: they are never overwritten. |
| Authorization basis | The Principal's decision record of 2026-10-17: D1 conditional; D2 paused; D3 keep v0.4 as an unapproved draft; D4 proposal only; D5 conditional; D6 fresh-context sub-agent review; D7 synthetic only; D8 candidate requirements only |
| Inputs | No corpus file, family file, seal, hold-out or S5 material. Synthetic data only. |
| Labels | `INTERNALLY REVIEWED — FRESH-CONTEXT SUB-AGENT` for proofs · `COMPUTATIONALLY CHECKED (synthetic, 2–3 implementations)` for finite claims. **Nothing is INDEPENDENTLY VALIDATED.** |

## 1. D6 review (fresh-context sub-agent; falsification before reading)

**Procedure.**
- Stage 1: the reviewer saw only a claims-and-definitions packet with no proofs, and attempted each claim on its own.
- Stage 2: it then read the 1752, 1805, 1830 and 1850 arguments.
- The worker did not see or influence the review before it was complete.

**Outcome.**

| Result | Count |
|---|---|
| CONFIRMED | 23 |
| CONFIRMED WITH CORRECTIONS | 8 |
| REFUTED | 0 |

Stage 1 and stage 2 agreed on every claim. The reviewer also derived several results its own way:
- a second counterexample for C-0′b;
- an independent construction for C-1⁺;
- its own non-uniqueness witness for C-6′;
- a **two-decision** instance for C-3 (first letter + remainder) where 1850 had used infinitely many decisions;
- a hand proof that E2 has no 2-class admissible state.

**Corrections accepted (they go into the D4 proposal; nothing is applied):**
1. **C-6:** "unique up to isomorphism" holds only for **trim** realizations, i.e. the reachable part. The 1752 report and the packet omitted this.
2. **C-1:** state it as a **promise problem** (M is promised total), and require decidable equality on V_d.
3. **E2:** see §2. Retract the 1805 "three maxima" and "union of three principal ideals" claims. State the convention that ε carries no channel.
4. **E3′:** retract the 1805 sentence "always satisfiable by splitting into k reachable states". The ⇐ witness is **infinite-state**; under a finiteness requirement, ⇐ is unproved.
5. **1830 Consequences §1:** "unique and minimum state count" becomes "unique; minimum count unique **at finite index**".
6. **R1′:** admissibility must be iso-invariant **jointly** over (u, f_d).
7. **R2:** separate the sufficient condition "join-closed, (J2) + (JD)" from the exact condition "⋁𝒜 ∈ 𝒜".

**Recorded disagreement (unresolved; integrator's view given, not imposed).**
- **The D6 reviewer** says the 1850 reviewer was wrong to call the 1805 binary-join remark "backwards".
- **The 1805 text** actually reads: "closure under binary joins suffices **only if** 𝒜 has no infinite strictly ascending chains."
- **Integrator's reading:** the 1850 reviewer's objection is to the "only if", and that objection is valid. A family that contains its own top element can have infinite ascending chains and still satisfy binary closure trivially. The D6 reviewer's point holds for the "if" direction (under ACC, binary closure suffices).
- **Agreed statement:** "Under ACC, (J2) suffices. Without ACC, (J2) may fail to suffice. (J2) + (JD) is sufficient; ⋁𝒜 ∈ 𝒜 is exact."
- **Status:** OPEN until an independent reviewer adjudicates.

**Literature claims still unverified:** Pfleeger 1973, Paull–Unger, Skowron–Rauszer 1992, Sénizergues, Larsen–Skou 1991, Eilenberg.

## 2. D7 synthetic checks (synthetic only; checks of examples, not proofs)

Three implementations, written without seeing each other's code. The scripts are kept in `../synthetic-checks/`.

| Implementation | Method | Author |
|---|---|---|
| A | BFS-canonical trim DFAs plus Moore minimization | worker |
| B | labelled word-partition enumeration up to length k, with a bounded right-congruence re-check | separate sub-agent; read no other code |
| D6 | canonical DFAs | the D6 reviewer, its own script |

| Claim | A | B | D6 | Status |
|---|---|---|---|---|
| E2: admissible classes with 1 / 2 / 3 states | 0 / 0 / 6 | 0 / 0 / 6 | 0 / 0 / 6 | agree |
| E2: 4-state admissible / maximal | 1009 / 141 | 1009 / 141 | 1009 / 141 | agree |
| E2: totals of trim DFAs (2, 3, 4 states) | — | 56 / 7965 / 2,128,064 | same | agree (A's 4-state total is 2,128,064) |
| E4: encodings / orbits / admissible orbits | 24 / 3 / 2 | 24 / 3 / 2 | (hand) | agree |
| E5: admissible encodings, 2 bits / 3 bits | 0 / 48 | 0 / 48 | (hand) | agree |
| O7a: Gray admissible with one-bit flips; binary not | — | yes | (hand) | agree |
| O7c: 4 classes; three 2-sets sufficient, updatable, inducing ∼ | — | yes | (hand) | agree |
| R4(b): Moore minimization of the product (d, d_ρ) = brute-force coarsest congruence | 400 random machines, 0 mismatches | — | — | one implementation only |

**E2, corrected statement (computationally checked):**
- There are **exactly 6** admissible 3-class right congruences, all maximal: 3 "first symbol" and 3 "first symbol after stripping leading x".
- There are **exactly 141** maximal admissible 4-class ones. I hand-verified one: [(1,2,3), (0,0,0), (2,2,2), (3,3,3)].
- Maxima with ≥ 5 classes are **not determined**.
- So there are ≥ 147 pairwise-incomparable maxima, and the minimum (3 classes) is not unique. Uniqueness of the kernel fails badly under cap-type policies.

**Limitations.** These are bounded finite enumerations. They check particular examples and cannot establish a general theorem. All three implementations are by the same model family, so shared conceptual errors are possible: for instance, all three read "141" as "141 of the 1009 admissible ones".

## 3. O-7: static examples under dynamics (analytical; internally reviewed; computationally checked)

- **E1 survives (O7a).** Instance: Σ = {a}, d(aⁿ) = [n mod 4 ∈ {1, 2}]. The Gray encoding is admissible both for one-bit decoders and for single-bit-flip updates; the binary encoding fails both. *New point:* an encoding choice can make the decoder **and** the update simpler at once.
- **XOR survives under flip dynamics (O7b).** {x⊕y} realizes ∼ (2 classes), while {x, y} realizes a strictly finer partition (4 classes). Irredundant component sets have different sizes **and** different partitions.
- **XOR changes under overwrite dynamics (O7c).**
  - ∼ has 4 classes and {x⊕y} is no longer sufficient.
  - Three irredundant pairs, {x, y}, {x, x⊕y} and {y, x⊕y}, each realize **∼ itself**.
  - *New point:* **the dynamics decide which component schemas are viable. The partition stays unique while the schema is not.**

**Theory impact.** Kernel *information* is intrinsic, and it is fixed by the decisions and the dynamics. A kernel *schema* (which components are stored) is a design choice, and that choice is constrained by the update alphabet.

## 4. Reviewer nominations (proposals; the Principal confirms)

### D1: safety reviewer for the filter scripts

| Option | Independence | Can do | Cannot do | Recommendation |
|---|---|---|---|---|
| **ChatGPT reviewer, on the supplied script source + specification + fixture design** | different model family; independent of Session B | logic review; re-derive the expected behaviour; design fake-seal fixtures | run code in this repository; see real seals (and must not) | **Recommended as the primary reviewer** |
| A human developer (the Principal or a delegate) | strongest | run the fake-seal fixtures; sign the record | — | recommended to **execute and sign** the fixture run |
| Session A (Researcher) | independent of Session B's scripts, but authored the resolver `p3b_discovery_io` they rely on; corpus-exposed | resolver semantics | an independent check of its own resolver | co-reviewer for the resolver only |
| A fresh-context Claude sub-agent | **same model family** | a pre-review only | count as independent | not sufficient alone |

The script source contains paths and logic, not sealed data, so supplying it to an external reviewer exposes no restricted content. The fixtures must use **fake** seals and labels.

### D6: proof reviewer

- **Done:** an internal fresh-context sub-agent review (§1).
- **Recommended next:** an external review for model-family independence by the ChatGPT reviewer, on the supplied claims packet first and the arguments second (same two-stage protocol). Optionally, a human mathematician with automata and order-theory background should check the six literature claims.
- **Until then the label stays INTERNALLY REVIEWED.**

## 5. Safety gate: status and a conflict

- **The proposed filter patch** is written: `../corpus-access/PROPOSED-FILTER-PATCH-20261017.md`. It covers F-1…F-8, **F-9 (new: an UNSEALED state allows everything)**, **N-1 (`norm()`: `lstrip` strips every leading `.` and `/` character)** and **F-10 (new: edited-and-moved copies)**. Nothing is applied or run.
- **Conflict with D2.** Both filters, and the count-only check, *use the sealed lists inside the script*. D2 says not to do that while the rule is paused. So **the safety gate cannot pass, and the D5 reads cannot start, until the rule owner clarifies D2.** The D1 review of the code *logic* can still proceed, using fake fixtures only.
- **Disclosure.** Session B's earlier filter runs (today, under G-LOG-0141 item 2 and its extension) used the sealed lists inside the script, with nothing displayed. Whether that use was permitted is the question D2 has paused.
- **No evidence so far that already-read material is affected.** That cannot be established until the gate passes.

## 6. D8: candidate requirements (not approved; not implementation instructions)

| CR | Candidate requirement | Basis | Conditional on |
|---|---|---|---|
| CR-1 | Privacy constraints name the protected population (histories, actors or outputs) and never count internal states | E3′ (internally reviewed) | stakeholder privacy policy; Gate D |
| CR-2 | Audit and separation requirements are expressed as required decisions in 𝔇 | R4(b) (internally reviewed) | the stakeholder audit list; Gate D |
| CR-3 | Every proposed restriction on kernel updates (reversibility, linearity, incremental-only) is checked against A2/A3 before adoption | A2–A5 | architecture track |
| CR-4 | Domain policies are stated at partition level; schemas and encodings are infrastructure | B2, O7 | architecture track |
| CR-5 | Cap-type aggregation policies are flagged as destroying kernel uniqueness | E2 (computationally checked) | stakeholder policy |

## 7. Status and next actions

| Item | Status |
|---|---|
| Core proofs | INTERNALLY REVIEWED (3 sub-agent reviews + D6); 0 refuted at D6; 7 corrections pending (D4 proposal) |
| Finite examples | COMPUTATIONALLY CHECKED (synthetic; 2–3 implementations) |
| O-7 | resolved for the stated instances |
| Open | E2 ≥ 5 classes; E3′ under finiteness; O-1′; encoding-level update restrictions; the R2 wording disagreement; O-3, O-5, O-6 |
| Safety gate | **blocked by D2**; patch proposed; D1 reviewer to be confirmed |
| Corpus evidence | unchanged: no Gate-D source verified |

**Next authorized analytical work:** O-5 (declare the loss for approximate sufficiency before any estimation), O-6 (choice of equivalence under nondeterminism), and the D4 patch proposal as a diff for review.
