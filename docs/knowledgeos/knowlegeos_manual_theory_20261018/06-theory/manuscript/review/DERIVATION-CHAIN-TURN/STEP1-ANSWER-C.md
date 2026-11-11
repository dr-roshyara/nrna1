DERIVATION STEP 1 ANSWER C

Independence here is process independence: I wrote this from the listed book files, without opening anything the Professor wrote for this turn. [I] marks my own judgements. "Ded" means the step-by-step argument is shown below; anything I only sketch is labelled a sketch.

## 1. The unit of derivation

**Criteria**

| # | Criterion | Why it matters |
|---|---|---|
| K1 | The object is **fixed by decided premises**, not open | Laws cannot be derived from an object the book has not fixed |
| K2 | Laws can be **stated over it** in C6's classes (S/T/H/O/R) | Otherwise "derive" has no formal meaning |
| K3 | It is **not defined from the laws** to be derived | Otherwise the derivation is circular |
| K4 | It **discriminates**: some laws follow from it and some do not, each with a countermodel | A unit from which everything, or nothing, follows is trivial (the analogue of DC-7) |
| K5 | It is **stable** under the choices the book leaves unfixed (G, batching, U3 (b), Question G) | Otherwise each law depends on an undecided choice |

**The four candidates against the criteria**

| Candidate | Verdict | Reason (lines) |
|---|---|---|
| Primitive concepts (RESEARCH-PURPOSE L28–35) | **rejected** as the unit | Fails K1/K2. Concepts are names, not premises: 2 of 12 ✓ (BOOK-OUTLINE §1); §9.19.5 L185 says "not evidence of necessity … not finalised". They are also homonymous: lifecycle of grants vs lifecycle of items (§9.19.5 L190–191), and three senses of authority (BOOK-OUTLINE L13). |
| Mechanisms (the pool 𝓜, §9.19.4) | **rejected** | Fails K3 and K5. C6 gives I_S, I_T, I_R and the reference definitions "independently of (M, G)" (§9.1 L99). Every law is a condition "not on which mechanism made a step" (§9.21 L40), and G is fixed only up to regrouping (§9.21 L45). Laws constrain mechanisms; they cannot be derived from them. Mechanisms enter only through the **form of the step relation**, which is not a pool. |
| Req = (Q, O), alone | **partial** | Meets K1–K5 only for must-do laws. **Lemma L-mono (Ded, under U5 (a) EF):** Req forces no S, T or H prohibition. Proof: (1) Req_O is upward-closed in M, by H-mono (§9.19.6 L236). (2) Observer correctness (a) does not mention M or G (§9.1 L116). (3) Take any system that meets Req, and a prohibition violated by some step σ. Add a mechanism that produces σ. Req still holds by (1) and (2), and the prohibition fails. The proof uses Req alone, not P. ∎ |
| Entry kinds and fields (𝒮 = records in D; §9.20 Th1) | **the core of the unit** | Meets K1 (C1, D, W's kinds, §9.20 as the state, Question O (c)), K3 and K5 (§9.20 Th1 needs G-kind only for step 7). But a state space alone carries no transition law. **Countermodel:** [root, grant, adoption] → [root, grant] goes from a record in D to a record in D and breaks append-only (law 7). Every state is valid, and Q2 = "the record itself, cut at s" stays correct on the new state. |

**Choice [I]: a combination, and a fifth object it exposes.** The unit is **RS = (D with C1 and W's kinds, as 𝒮) + Req's references and inputs (Req v3, with Q2 under Question O (c))**. Must-do laws and laws about derived values follow from RS. **The must-never laws (T/H) do not follow from any of the four.** They are axioms of P (I_T), which the book holds as given premises (§9.1 L96, L99). Their content currently lives in the description of G-kind ("adds an entry", §9.19.4 L160) and in C6's examples. I call this set **A_T**: append-or-tombstone only, origin, P-loss, and the guard set. **Result:** the laws derive from no single candidate unit. RS derives the forced laws (§3 below). A_T must be stated as axioms, not derived. The origin rule, which is part of A_T, is not decided (§9.21 L51).

**"Primitive" split into named senses, each with a test**

| Sense | Object | Test (home) |
|---|---|---|
| **P-con** | a concept (RESEARCH-PURPOSE L28–35) | an independence model: it alone fails while the others hold (§9.16 R7 (i); RESEARCH-PURPOSE L47) |
| **P-field** | an entry kind or field of 𝒮 | read by a clause of an existing reference definition (R7 (ii), L373), plus a removal model: two records differing only in it get different answers (§9.19.5 L204) |
| **P-mech** | a mechanism of a pool | underivable, meaning not a sequence of members in one step (§9.19.4 L173), and not a case split (§9.21 L42); necessary iff m ∈ ⋂𝒦 (T9) |
| **P-ax** [I, new name] | a law in A_T or I_S | a countermodel in which it alone fails while the rest of A_T and RS hold (the precedent is §9.8 M1–M3) |
| **P-req** | a Req item | the corrected F-min: removing it makes some non-default answer unreachable (§9.19.8 L364) |

## 2. One law, end to end: law 1 (I1, no authority without a recorded act; §9.11 L33)

**Statement derived (Law 1\*).** For every record R in D, cut s, item c, scope x and time t: if the system's Q1 answer is T, then R≤s contains (i) an adoption y of c with t_y ≤ t and authorized(y) = T, and (ii) a citation chain from y through grant issuances to a root entry, every member of which is an entry of R≤s with value T.

**Premises (all decided):**
- P1: C1 = constitute (§9.13 L81).
- P2: 𝒮 = records in D (§9.20 Th1, L153).
- P3: Q1's reference is CE*(k, x, t; R≤s) (§9.10 L39).
- P4: current-authority is F if c has no adoption on record; otherwise a strong-Kleene disjunction over adoptions y with t_y ≤ t of authorized(y) ∧ … (§9.13 L129).
- P5: Φ. A root is T. An act citing no basis is F. Otherwise authorization is a disjunction over the cited bases g, each requiring a(g) = T. Any entry other than a root or a grant gives F (§9.13 L117–123). A grant is itself an act (L125).
- P6: KK, the least fixed point of Φ, is reached from ⊥, where roots are T and everything else is U; it is reached in at most n + 1 steps (§9.13 R1, L170).
- P7: observer condition (a): obs_Q1 = ref_Q1 on every valid state (§9.1 L116).

| Step | Claim | Justification |
|---|---|---|
| 1 | the answer equals CE*(c, x, t; R≤s) | P7, P3 |
| 2 | CE* = T ⇒ current-authority = T at KK | definition of CE*, §9.13 L135 |
| 3 | ⇒ some adoption y of c with t_y ≤ t has authorized(y) = T | P4: the first clause gives F; a strong-Kleene ∨ is T only if some disjunct is T, and an ∧ only if every conjunct is |
| 4 | define stage(a) as the least n with Φⁿ(⊥)(a) = T; stage(a) is finite | P6 |
| 5 | if x is not a root and stage(x) = n ≥ 1, x cites a basis g that is a root or a grant, with a(g) = T at stage ≤ n − 1 | P5, applied to Φ(Φⁿ⁻¹(⊥)) |
| 6 | stages strictly decrease along the chain, so the chain ends at stage 0, where only roots are T | 4, 5; ⊥ |
| 7 | every member is an entry of R≤s, because Φ reads only cited entries of the cut | P5; §9.13 L139 (sound support) |
| 8 | Law 1\* | 3, 6, 7 ∎ |

- **What it uses:** decided premises only. It does **not** use the origin rule, G-kind or D's earlier-entries-only rule; step 6 needs only the iteration from the bottom, so it also holds under D_acyclic [I].
- **What it does not give:** that the entries came from outside inputs. A mechanism that forges an adoption entry still satisfies Law 1\*; excluding it is law 2's origin part, which belongs to A_T.
- **Consequence:** law 1 moves from class S (§9.11 L33) to an O-consequence that Req forces, which confirms the implication in §9.20.8 L156.
- **Falsifier:** a record R in D with CE* = T and either no adoption of c at or before t, or a T-chain that does not end at a root of R≤s. Another falsifier: a Θ_f in which roots can be annulled or need a basis. I did not run `ce_model.py`, so there is no computational check behind this.

## 3. The laws of §9.11, marked

**Definitions.**
- **Forced:** every system meeting RS (Req plus D, C1, Θ_f, 𝒮 = R) satisfies the law.
- **Compatible:** the law is a condition on the system's own states, steps, runs, replies or structure; it is not forced (by L-mono); and it can hold together with Req. These are A_T or P candidates.
- **Independent:** the law's truth is not fixed by the system's runs. Each one is classed **W** (world assumption) or **App** (application rule).
- **Impl** [I]: §9.15's kernel-assumption side, about implementation. The commission's two-way split has no slot for it; the K/L precedent does.
- **Unc:** unclassified; the law's object is outside Req (visibility, Q6).

| Law | Mark | Ground |
|---|---|---|
| 1 | **forced** | §2 above |
| 2 | record part **forced** [Ded sketch]; origin part **compatible** (uses the origin rule) | With covers fixed and Θ_f, CE* changes only by an appended act entry; tombstones change no value (§9.16 R5); W entries are not read by Φ. A forging mechanism still meets Req. |
| 3 | independent, W | L1–L4 (§9.15) |
| 4a | compatible (origin rule) | the same as the origin part of law 2 |
| 4b | compatible | under EF, a guard that refuses on its own judgement still meets Req |
| 5 | explicit: **forced** (as law 2); reasoned: compatible (the reason is a D field by decision, read by no clause, §9.20.3 row 3); visible: App | |
| 6 | compatible; supersession is not in D (§9.13 L52) | |
| 7, 8 | compatible | the countermodel in §1; observers read state by state, so a history rewrite is invisible to them [I] |
| 9a | compatible | **[I] gap:** O1's text states idempotence (§9.10 L109), but its formal reading (EF plus observers) cannot carry a T condition |
| 9b | standing (consumptions): **forced**; entry prevention: compatible | Φ makes a second consumption F (§9.13 L34). This does not cover work-act uses (§9.16 L183). Prevention is a guard (K4). |
| 10a | compatible | |
| 10b | independent, W | L6 |
| 11a | compatible; vacuous under Θ_f, since θ-setting acts are not in D | |
| 11b | independent, Impl | K7 |
| 12 | independent, Impl | K6; vacuous in the type (§9.11 L48) |
| 13 | independent, Unc | Q6 is a candidate; OQ-25 |
| 14 | O3's R part: **forced**; H5's W part: W | L4 |
| 15 | **forced** (it is Req) | Q3's reference is a construction, Int and not modelled (§9.13 L255) |
| 16 | value at a cut, and Q2: **forced**; "same call, same answer later": compatible | the latter needs laws 7, 10a and P-loss |
| 17 | **forced** (definitional) | |
| 18 | (i): compatible; (ii): E, so W; return to the author: App | held acts are not a kind of D |
| 19 | STOP: compatible; REPORT: **forced by O1's text, not by its EF form**; ESCALATE: App | |
| 20a | compatible | the HALT model meets Req and fails AG EF; placed in applications by R6 (b) |
| 20b | independent, W/E | |
| 21 | within one system at a cut: **forced** (CE* is a function of the call); across applications: compatible | needs a shared cut (K2) and F26 (b) |
| 22 | O part: Unc (Q6); HP part: compatible | F36 (a) |
| 23 | **forced** | Q5's observable consequence |
| 24 | compatible | coordination, K4 |
| 25 | independent, W | L2 |
| 26 | compatible; establishment is not in D; detection placed by R6 (b) | |
| 27 | T reading: compatible, conditional on E1 (U4 (d)); otherwise W; delivery: App | |
| 28 | independent, W | |
| 29 | compatible, **undecided** | rests on B-07, not usable as decided |
| 30 | compatible | a property of the contract's merge, outside B's single record; the check under the extended identity is not done (T9-18) |
| 31 | recording at the weaker reading: compatible; the reply: **forced by O1's text**; delivery: App | |

**Counts [I]:**
- **Forced, 13 parts:** 1, 2-rec, 5-explicit, 9b-standing, 14-R, 15, 16-value, 17, 19-REPORT (by text), 21-one system, 23, 31-reply (by text). Every forced part is an O or R consequence.
- **Compatible:** every S, T and H prohibition.
- **The finding:** the must-never side of the law set is a set of axioms of P, not consequences of Req. This is the dual of §9.1 C1.

## 4. Consequences, stated and not settled

- **Deliverable 1.** Like T9-02's deliverable, the primitive table could be retyped. Concepts would become an index over P-field groups and P-ax laws. Independence models would then be run on the A_T axioms (origin, append-only, P-loss), not on concepts. The 2/12 ✓ marks do not carry over to that form.
- **T9-03.** Primitive minimality splits in two:
  - **(i) state or field minimality.** For Req's core this is given by Question O (c) (§9.21 L111).
  - **(ii) independence of the A_T axioms.** Open; only §9.8's M1–M3 bear on it.
  - Mechanism minimality has no bearing on the laws (§9.21 L40), and is kept apart.

## 5. Disproof

| Answer | What would refute it |
|---|---|
| Unit = RS + A_T; mechanisms rejected | two pools with equal step relations that differ on some §9.11 law |
| L-mono | a decided Req form that is not monotone. **Under enabledness (U5 (b)) L-mono fails**, so it rests on U5 (a). |
| Law 1\* | the falsifier in §2 |
| A "forced" mark | a system meeting RS that violates the law |
| A "compatible" mark | a proof that the law follows from RS |
| A W mark | a W law shown to be a function of the record |

## Limits

- §9.13 was read only to L262 (of 374); §9.16 only at L93, L160–399 and the grep hits.
- Not read: §9.02–9.09, 9.12, 9.14, 9.17, 9.18, and the chapter 0/1/3/5 files beyond the grep and sed lines listed below.
- No tool was run. The "forced" mark for law 2-rec is a sketch, not a proof.

## Files opened (all read-only)

- `.../06-theory/manuscript/review/DERIVATION-CHAIN-TURN/BRIEF-STEP1.md`
- `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`
- `06-theory/RESEARCH-PURPOSE.md`; `06-theory/manuscript/BOOK-OUTLINE.md`; `06-theory/manuscript/TARGET-TOC.md`
- `chapter_09/`: 09.01, 09.10, 09.11, 09.13 (L1–262), 09.15, 09.16 (L160–399, plus the grep below), 09.19, 09.20, 09.21

**Files copied:** none.

## Commands and searches run

1. `wc -l`, `grep -n "R7"` and `sed -n 93p` on `chapter_09/09.16-reassessment.md`.
2. `wc -l` on `chapter_01/01.06-nothing-but-an-act-confers.md`, `chapter_09/09.08-is-history-a-primitive.md` and `chapter_00/00.05-the-invariants.md`.
3. `grep -n "^\*\*A1\|A1\.\|stipulat"` on 01.06.
4. `grep -n "H1′\b\|H1′ (\|H2\b"` on 09.08.

No globs, listings, git commands or corpus sources were used.
