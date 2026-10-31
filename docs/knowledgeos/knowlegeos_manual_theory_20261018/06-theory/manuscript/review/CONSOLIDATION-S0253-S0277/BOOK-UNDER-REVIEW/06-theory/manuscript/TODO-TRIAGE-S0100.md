# Research-level to-do triage, whole-book review S0100

*2026-10-19. By the Professor, Rulebook v1.3 §12c (entry 55) and the Principal's stage-2 list. Every chapter to-do (151 rows in nine TODO files) is **closed**, **merged** into a major item or another to-do, or **kept** with a priority. Priorities: **P1** on the critical path, **P2** needed for kernel results, **P3** reading-dependent or not theory-critical. Each major item has five lines: importance · why now · what it blocks · next research action · resolved when. Changes to the final goal and reserved decisions are the Principal's. The dispositions are also written into each chapter TODO file (status column).*

## 1. The critical path

```
M1 Req ──► M2 CE (total function) ──► M3 kernel type ──► M4 F22 / K4 placement ──► kernel results (deliverable 5)
   ▲            ▲                         ▲
   │            │                         └── M5 dimensions & bearer (F13) ◄── P2-1
   │            └── M8 A1 readings & authority senses
   └── M6 conflict definition (liveness, gate results) ◄── P2-2
```

**Reading of the path:** Req fixes which queries the kernel must answer (R_s); CE must answer them as a total function; the kernel type must express the laws CE and the record obey; only then can placement (F22) be decided. M5, M6 and M8 feed the first three nodes. M7, M9–M13 are needed for the theory's other deliverables but not for the kernel path.

## 2. Major items (five lines each)

### M1. Req: the kernel's required capabilities (T9-01)

- **Importance:** The purpose's kernel test is "minimal set satisfying P ∧ Req"; without Req every kernel claim is about invented requirements, and the laws stay prohibitions a null system satisfies.
- **Why now:** All ten part reviews and the integration review found no stated must-do requirements (P1-6, P2-10, P3-3, P3-5, P4-9, P5-5, I-02, I-12); §9.1 C2 tested with two invented ones.
- **Blocks:** Kernel existence and minimality (deliverable 5), F22 (placement), the boundary tests, Zero as a candidate Req item (I-08), CE's query set R_s.
- **Next research action:** Collect every must-do item the chapters already state (evaluator totality, AM-1, declassification, I-2 visibility, ContractDerivation, liveness of the conflict analyser) into one sourced list, each through the three boundary questions; read S0167 L1139 (the five verbs) in order.
- **Resolved when:** A sourced Req list in §9.1/T9-01, each item marked kernel / application with its evidence; T9-03 and T9-04 restated against it.

### M2. CE: the standing evaluator as a total function (CON-0138; §1.14.1)

- **Importance:** History and trust reduce to CE (§9.8 Lemma D, §9.9 T1); A1's checkability, F22 and F23 rest on it; it is the critical path's second node.
- **Why now:** §1.14.1's seven declared defects; §9.7 result 2 ("not yet a function for any θ"); P1-3; P4-5 (constant-T evaluator); P4-7; I-02.
- **Blocks:** Every "decomposes" or "reduces" result of Ch. 9 (conditional); delegation (§9.7.5); the 20+ policy parameters of Ch. 1 (T1-12…T1-29), which cannot be posed precisely without the function.
- **Next research action:** Repair defects 1–7 in order (monotone υ or explicit non-monotone semantics; grant issuance and delegation as acts; headline for "no adoption on record"; recording time; covers(B, x) and the actor–holder link; σ inside the fixed point); make §9.7.5 condition 4 two-sided; then re-test D and T1.
- **Resolved when:** A definition that is total on a stated domain, with a proof that is a proof, not a sketch; §9.7 result 2 withdrawn; D and T1 re-checked.

### M3. The kernel type and the state space (CON-0100; T9-02)

- **Importance:** The type the kernel is tested in must be able to express the laws tested in it (P4-1, critical).
- **Why now:** P4-A and P4-B both critical; I-01; §9.1 L91 "the model does not test A1".
- **Blocks:** F22, §9.6–§9.9 as kernel results; T9-03/T9-04.
- **Next research action:** Add transition invariants over (s, x, s′), trace invariants (append-only H1′), an input-origin condition (act-carrying x), guards, applications and uniqueness; re-prove C3 and re-run C4 with the deleting-record and forge models; fix 𝒮 from the attribute table (M5).
- **Resolved when:** Purge and forge models fail; A1, I-1(GKC), I-7(GKC) and H1′ are expressible; C3 re-proved.

### M4. Placement of authority recording and CE (F22; K4)

- **Importance:** The first kernel placement decision of the book; reserved for the Principal once posed.
- **Why now:** P4-6 (identity premise; prevention vs detection), P4-7, P4-10 (§9.6 L127 unstruck).
- **Blocks:** The kernel boundary for authority; Ch. 9's results.
- **Next research action:** After M2 and M3: make the act-identity mechanism a premise; add the prevention/detection sub-fork; validate the relocation test on a known case; strike or condition §9.6 L127. Decision brief DB-3.
- **Resolved when:** F22 posed with its premises, as a decision brief; the Principal decides.

### M5. Dimensions, their bearer, and the attribute signature (F13; P2-1)

- **Importance:** The meta-model's six dimensions are the state attributes the kernel works with; their bearer is undefined (P2-1, critical).
- **Why now:** P2-1, P2-4, P2-5, P2-6, I-02 (F13 unmapped), CON-0002 PROVISIONAL.
- **Blocks:** M3's state space; Ch. 1 labels ↔ Ch. 3 dimensions; every concept that uses the dimensions.
- **Next research action:** Open the bearer fork with both meanings kept under separate IDs (decision brief DB-5); assign a bearer per dimension; split "enforced"; map CON-0006 onto §2.9; build the F13 table.
- **Resolved when:** Each dimension has a bearer (or the fork is posed for the Principal); S0016's six-artifact test re-run; the F13 table exists.

### M6. The conflict definition (P2-2)

- **Importance:** Ch. 2's central definition; gate results rest on it; it is a candidate Req item (liveness).
- **Why now:** P2-2 (critical): pairwise only; no context/choice split; I-19 refutation adequacy.
- **Blocks:** §2.4–§2.9 results; the gate rule "proven consistent opens a HARD gate".
- **Next research action:** Restate set-wise with minimal unsatisfiable subsets and a context/outcome split; repair §2.8.4 containment (⊥ possibly ∅); re-derive §2.9; state liveness.
- **Resolved when:** The three-rule set is flagged; A=(⊥,{a}), B=(V₁,{b}) gives "unknown"; the gate rule re-derived.

### M7. Independence and eligibility (F32; §4.1, §8.6, §1.14.3-3)

- **Importance:** What counts as independent support, and who may verify, decide the status of every count and every review, including the book's own.
- **Why now:** P3-4, P5-2, P5-3, I-03: three incompatible senses; the in-estate notion can never be met.
- **Blocks:** Promotion of any pattern (n ≥ 2), F32, CON-0151, the meaning of VALIDATED (P3-9).
- **Next research action:** One graded, factor-relative notion; one E(o) definition; the factor → blind-set bridge as a hypothesis; decision brief DB-2.
- **Resolved when:** One notion used in §1.14.3-3, §4.1 and §8.6; T4-03 has a criterion that can be met; the register's confirmations re-graded.

### M8. A1's readings and the senses of "authority" (P1-2, P1-4, I-04)

- **Importance:** A1 is the book's one axiom; its status (norm or claim) and its readings decide what CE must compute.
- **Why now:** P1-2, P1-4, I-04; §9.8 M1 vs §9.9 M-T2 use different readings.
- **Blocks:** M2 (what CE evaluates), F23 (P4-4), external authority (T1-15).
- **Next research action:** Name the readings (de jure, recognized, fact, form) in §1.6 and propagate them; split "authority" into qualified IDs; decision brief DB-1.
- **Resolved when:** Every use of A1 and "authority" names its reading; the Principal's choice (if any) recorded.

### M9. What KnowledgeOS is, and the loop (Ch. 7) (P1-1, I-07, I-17)

- **Importance:** The identity is the book's subject; the loop is the evidence for it; Ch. 7 is planned and unwritten.
- **Why now:** P1-1; I-17 (Ch. 7 references); T1-39, T1-45 (the loop has never closed).
- **Blocks:** The identity claim of §1.8 and §0.2; the evidence chapter; TARGET-TOC.
- **Next research action:** Retitle §1.8 as a target; decide Ch. 7: write it or retire it (decision brief DB-6, with TARGET-TOC).
- **Resolved when:** §1.8 and §0.2 agree; Ch. 7's fate decided by the Principal.

### M10. Labels and glossary (I-n, A1, Ded, ⊥, baseline, qualification)

- **Importance:** Homonym labels let a law be read under the wrong rule (I-13).
- **Why now:** N1-03, N1-15, N1-28…N1-37, N2-12, N3-23, I-13.
- **Blocks:** Every cross-reference by label.
- **Next research action:** One glossary; qualified labels book-wide.
- **Resolved when:** Every label carries its source scheme; the glossary exists.

### M11. Knowledge spaces, PKS, product vs deployment (F8)

- **Importance:** The PKS is the application the platform serves; its definition is split across senses.
- **Why now:** P2-9 (GKC coordinate-space sense missing), T1-09/T1-43 duplicates.
- **Blocks:** F8; Ch. 6 placement claims.
- **Next research action:** Add the coordinate-space sense S0; restate F8 per sense; merge T1-09 and T1-43.
- **Resolved when:** F8 restated with its senses.

### M12. The knowledge/capability line (F11, SEQ-0012)

- **Importance:** I-11 and §1.5's classification rest on it.
- **Why now:** P1-5, I-05, N2-19 (skill).
- **Blocks:** §1.1, §1.5, §3.9, CON-0039.
- **Next research action:** Settle F11 or label §1.1 superseded by §3.9's convention; add the skill case.
- **Resolved when:** One criterion in §1.1, §1.5 and §3.9.

### M13. The laws of change: one home (P3-10, I-14)

- **Importance:** Ch. 4 promises them; nothing holds them.
- **Why now:** P3-10; I-14; CON-0021, CON-0022 without a home.
- **Blocks:** Ch. 4's title; TARGET-TOC.
- **Next research action:** Place the kinds of change in Ch. 4 (or rename Ch. 4) and break the §1.6 ↔ Ch. 4 loop.
- **Resolved when:** One home; CON-0021/0022 have a book_location.

## 3. Every chapter to-do

**Counts:** 151 rows · closed 34 · merged 91 · kept 26. Open rows by priority (merged and kept): P1 23 · P2 53 · P3 35 · update rows 6.

| Ch. | To-do | Concept | Disposition | Into | Priority | Reason |
|---|---|---|---|---|---|---|
| 00 | T0-01 | Invariant numbers "I-1 … I-n" | MERGE | M10 | P2 | invariant numbering is a label homonym (N1-03, I-13) |
| 00 | T0-02 | Mission of KnowledgeOS | MERGE | M9 | P2 | mission: identity and loop (P1-1, I-07) |
| 00 | T0-03 | What KnowledgeOS is | MERGE | M9 | P2 | what KnowledgeOS is (P1-1) |
| 00 | T0-04 | The development sequence of the theory | KEEP | — | P3 | Principal decision when it becomes blocking; not blocking now |
| 00 | T0-05 | "Knowledge ≠ Artifact" | MERGE | M5 | P1 | knowledge ≠ artifact: the bearer of the dimensions (P2-1, critical) |
| 00 | T0-06 | "Baseline" (and "Constitution") | MERGE | M10 | P3 | qualified "baseline": glossary |
| 00 | T0-07 | The eight knowledge states (Candidate … Archived) | MERGE | M5 | P2 | the eight states: governance status as a dimension (P2-4) |
| 00 | T0-08 | Numbering of "governance precedes automation" | MERGE | M10 | P3 | label numbering |
| 00 | T0-09 | The reserved knowledge-decision catalogue | KEEP | — | P3 | reading-dependent (reserved decision catalogue) |
| 00 | T0-10 | KIND: its own axis, or a value of NATURE? (U-MM-1) | MERGE | M5 | P2 | KIND axis or value (U-MM-1; P2-12) |
| 00 | T0-11 | Independence of the five classification passes | MERGE | M5 | P2 | independence of the classification passes (P2-12: lineage) |
| 00 | T0-12 | E-1: the falsified consumption model | KEEP | — | P3 | reading-dependent (E-1 register entry) |
| 00 | T0-13 | Two invariant sets (GKC I-1…I-11 vs the engineering set A-10) | MERGE | M3 | P2 | two invariant sets: which invariants the kernel type must express |
| 00 | T0-14 | CON-0025 enacted mission: INVALID (partly valid) | CLOSE | register CON-0025 | — | INVALID concept tracked in the register; reopen on a ratification record |
| 00 | T0-15 | CON-0028 KnowledgeOS as an AI-native product: INVALID (not verifiable) | MERGE | M9 | P2 | KnowledgeOS as a product (identity) |
| 00 | T0-16 | CON-0024 mission vs constraint: INVALID (partly valid) | CLOSE | register CON-0024 | — | INVALID; tracked in the register |
| 00 | T0-17 | CON-0027 KnowledgeOS as governance architecture: INVALID (partly valid | MERGE | M9 | P2 | governance-architecture identity |
| 00 | T0-18 | CON-0051 AIP-14 platform-cost metric: INVALID (undefined) | KEEP | — | P3 | AIP-14 metric: awaits D-9a (ARB), not on the critical path |
| 01 | T1-01 | "Authority" | MERGE | M8 | P1 | "authority" senses; A1 readings (P1-4, I-04) |
| 01 | T1-02 | "Currently valid / currently authoritative / current authority / effec | MERGE | M8 | P1 | current/effective/recognized terms (P1-4, N1-28) |
| 01 | T1-03 | TD-001 "Authority-Determined Decision Validity" (temporary definition) | MERGE | M2 | P1 | TD-001 validity: the evaluator's definition |
| 01 | T1-04 | The subject of validity | MERGE | M2 | P1 | subject of validity: CE's argument c |
| 01 | T1-05 | "The relevant time" | MERGE | M2 | P1 | the relevant time: CE's time arguments |
| 01 | T1-06 | FREEZE decisions | KEEP | M8 | P2 | freeze authority: a governance question via the Principal |
| 01 | T1-07 | "Standing" (book vs corpus) | MERGE | M8 | P1 | standing, book vs corpus (P1-4) |
| 01 | T1-08 | "Qualification" | MERGE | M10 | P3 | "Qualification" homonym; Awaiting Human Decision kept |
| 01 | T1-09 | Knowledge Space nesting | MERGE | M11 | P2 | space nesting (F8) |
| 01 | T1-10 | Lifecycle states "idea" / "research" | KEEP | — | P3 | lifecycle states idea/research: Awaiting Human Decision; not blocking |
| 01 | T1-11 | PKS | MERGE | M11 | P2 | PKS (F8) |
| 01 | T1-12 | Consumption of a grant (formal model) | MERGE | M2 | P1 | grant consumption in the formal model (CE inputs) |
| 01 | T1-13 | "Use" of a grant (formal model) | MERGE | M2 | P1 | grant "use" in the formal model |
| 01 | T1-15 | External authority ("who must be obeyed") | KEEP | M8 | P2 | external authority: the boundary of A1 (F2); kept as its own item |
| 01 | T1-16 | Product vs deployment (tenant) | MERGE | M11 | P2 | product vs deployment (F8) |
| 01 | T1-17 | Genesis: the first decision | MERGE | M2 | P1 | genesis and root acts: CE's root |
| 01 | T1-18 | Retroactive authorization | MERGE | M2 | P1 | retroactive authorization: CE's ρ |
| 01 | T1-19 | Performer eligibility (independence) as a condition of an act's validi | MERGE | M7 | P2 | performer eligibility: independence and F32 |
| 01 | T1-20 | Closure horizon λ: when may a missing record mean "not authorized"? | MERGE | M2 | P1 | closure horizon λ: a CE policy parameter (Awaiting Human Decision kept) |
| 01 | T1-21 | Simultaneous conflicting acts (tie-break) | MERGE | M2 | P1 | tie-break: a CE policy parameter (F26) |
| 01 | T1-22 | "Late evidence" | MERGE | M2 | P2 | late evidence: CE policy |
| 01 | T1-23 | Trigger: "could change the outcome" | MERGE | M2 | P2 | review trigger: CE policy |
| 01 | T1-24 | "Dependent actions" / "consequences" | MERGE | M2 | P2 | dependent actions: needs a dependency definition over recorded bases |
| 01 | T1-25 | Interim status pending review | MERGE | M2 | P2 | interim status: CE policy |
| 01 | T1-26 | Post-ruling outcomes and the remedy for dependent actions | MERGE | M2 | P2 | remedy for dependent actions |
| 01 | T1-27 | Escalation routing, timing and scope | MERGE | M2 | P3 | escalation routing: policy, not theory-critical |
| 01 | T1-28 | "Pause" of an affected action | MERGE | M2 | P3 | pause of an affected action: policy |
| 01 | T1-29 | Emergency exception to a pause, and its authority | MERGE | M2 | P3 | emergency exception: policy, needs legal input (T1-15) |
| 01 | T1-30 | Which authority decides what | MERGE | M8 | P2 | which authority decides what |
| 01 | T1-14 | Delegation conditions | MERGE | M2 | P1 | delegation conditions (CE defect 2: issuance and delegation unchecked) |
| 01 | T1-31 | "×" in "authority = provenance × standing" | CLOSE | resolved (Int) | — | resolved by interpretation (see its resolution row); reopen if a defining passage is found; P1-4 covers the composite |
| 01 | T1-32 | "No external instance" of generating a governed knowledge space | MERGE | M9 | P2 | "no external instance" of generation (identity/loop) |
| 01 | T1-33 | When a missing owner is a defect | KEEP | — | P3 | reading-dependent (ownership sources) |
| 01 | T1-34 | The "language test" | KEEP | — | P3 | undefined term ("language test"): R2 reading list |
| 01 | T1-35 | Identifier namespaces (I-n, C-n, R-n) | MERGE | M10 | P2 | identifier namespaces (N1-03) |
| 01 | T1-36 | "Prompt" in the capability model | CLOSE | resolved | — | resolved 2026-10-18 |
| 01 | T1-38 | The I-7 breach (a product space holding reusable method) | KEEP | — | P3 | I-7 breach: reading-dependent; settled findings P2-8 record it |
| 01 | T1-37 | MVK: the expansion of the term | KEEP | — | P3 | undefined expansion (MVK): R2 reading list |
| 01 | T1-39 | Did the retrospective (PB-004) ever run? | MERGE | M9 | P2 | did the retrospective run: the loop |
| 01 | T1-40 | Two knowledge bodies (governed corpus vs operational registers) | KEEP | — | P3 | two knowledge bodies: reading-dependent |
| 01 | T1-41 | SPECIALIZES (engineering knowledge → product knowledge) | MERGE | M11 | P3 | SPECIALIZES: follows LG-5 |
| 01 | T1-15 (evidence, 2026-10-18) | external authority | MERGE | T1-15 | — | evidence/update row of T1-15 |
| 01 | T1-44 | Is metadata ≠ knowledge a genuine axis? | KEEP | — | P3 | metadata ≠ knowledge axis: test against later sources |
| 01 | T1-45 | Has evidence ever flowed back to the platform? | MERGE | M9 | P2 | evidence back-flow: the loop |
| 01 | T1-42 | The four partial orders (authority, production, realization, containme | MERGE | M5 | P2 | four partial orders (CON-0064; P2-6) |
| 01 | T1-43 | Do knowledge spaces nest? | MERGE | M11 | P2 | space nesting (duplicate of T1-09) |
| 01 | T1-46 | Is the register the complete record of authorizations? (D-2) | MERGE | M2 | P1 | is the register the complete record (D-2): decides CE's "no adoption on record" |
| 01 | T1-45 (update, 2026-10-19) | evidence back-flow | MERGE | T1-45 | — | evidence/update row of T1-45 |
| 01 | T1-47 | Rules in force with the standing "proposed" | MERGE | M8 | P2 | rules in force with standing "proposed" (1.14.5-13) |
| 01 | T1-46 (evidence, 2026-10-19) | the register as the complete record | MERGE | T1-46 | — | evidence/update row of T1-46 |
| 01 | T1-48 | CON-0015 freeze (as a decision type): INVALID (not verifiable) | MERGE | T1-06 | P2 | freeze as a decision type: same question as T1-06 |
| 01 | T1-46 (correction, review C2 F-03) | the register as the complete record | MERGE | T1-46 | — | evidence/update row of T1-46 |
| 01 | T1-43 (note, review C2 F-09) | duplicate tracking | MERGE | T1-43 | — | evidence/update row of T1-43 |
| 01 | T1-31 (resolution, 2026-10-18) | "×" | MERGE | T1-31 | — | evidence/update row of T1-31 |
| 01 | T1-50 | No authority spans the three authority systems (MQ-2) | MERGE | M8 | P2 | no authority spans the three systems (MQ-2) |
| 01 | T1-51 | CON-0012 decision-readiness: INVALID (partly valid) | CLOSE | register CON-0012 | — | INVALID; tracked in the register |
| 01 | T1-52 | CON-0018 standing (authority-field value): INVALID (partly valid) | MERGE | M8 | P1 | CON-0018 standing: split into qualified IDs (P1-4) |
| 01 | T1-53 | External authority is outside the act model | CLOSE | merged into T1-15 | — | already merged at review C4 |
| 01 | T1-54 | "Party" in the execution protocol's separation of duties | MERGE | M7 | P2 | "party" in separation of duties: independence |
| 01 | SEQ-0007 | Sequence gap (SQ-1): missing step / inconsistent order | CLOSE | resolved | — | resolved 2026-10-19 |
| 01 | SEQ-0008 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved 2026-10-19 |
| 01 | SEQ-0009 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved 2026-10-19 |
| 01 | SEQ-0011 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved 2026-10-19 |
| 02 | T2-01 | Conflict between decisions | MERGE | M6 | P1 | conflict between decisions: the conflict definition (P2-2) |
| 02 | T2-02 | NOT_APPLICABLE (analysis result) | MERGE | M6 | P2 | NOT_APPLICABLE: an analysis result of the conflict analyser |
| 02 | T2-03 | Ruled absence vs a ninth kind of nothing | KEEP | — | P3 | ruled absence: revisit at the next consolidation |
| 02 | T2-04 | "Evidence-status" and "operational-state" | MERGE | M5 | P1 | evidence/operational status vs §2.9 (P2-4) |
| 02 | T2-05 | "Advisory" vs "enforced" | MERGE | M5 | P1 | "advisory" vs "enforced" (P2-4 homonym) |
| 02 | T2-06 | DP-n as a layer | CLOSE | resolved | — | resolved 2026-10-18 |
| 02 | T2-07 | CON-0034 design policy as capability anchor: INVALID (partly valid) | CLOSE | register CON-0034 | — | INVALID; tracked in the register; N2-21 (DP-n rival) carries the open question |
| 02 | T2-08 | CON-0040 "void-overriding" constitutional principle: INVALID (TODO — M | MERGE | M6 | P2 | void-overriding precedence (P2-7) |
| 02 | T2-09 | CON-0061 trigger-bound deferral: INVALID (partly valid) | KEEP | — | P3 | deferral decider: reading-dependent |
| 02 | T2-10 | CON-0063 "a deferred rule cannot be cited as authority": INVALID (part | CLOSE | resolved | — | resolved at review C4 |
| 02 | T2-11 | CON-0055 control kinds: INVALID (partly valid / not verifiable) | MERGE | M5 | P2 | control kinds: the "enforced" homonym (P2-4) |
| 02 | T2-12 | CON-0062 closed-vocabulary collision: INVALID (partly valid) | KEEP | — | P3 | closed-vocabulary collision: reading-dependent |
| 02 | SEQ-0001 | Sequence gap (SQ-1): undefined, plus an unmarked forward use | CLOSE | resolved | — | resolved 2026-10-19 |
| 02 | SEQ-0002 | Sequence gap (SQ-1): undefined | KEEP | — | P3 | undefined maturity levels L0–L4: define or cite (R2 reading list) |
| 02 | SEQ-0003 | Sequence gap (SQ-1): inconsistent order | CLOSE | resolved | — | resolved |
| 02 | SEQ-0004 | Sequence gap (SQ-1): inconsistent order | CLOSE | resolved | — | resolved |
| 02 | SEQ-0005 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved |
| 02 | SEQ-0006 | Sequence gap (SQ-1): missing step | CLOSE | resolved | — | resolved |
| 03 | T3-01 | CON-0003 governance status: INVALID (partly valid) | MERGE | M5 | P2 | CON-0003 governance status: dimension and bearer |
| 03 | T3-02 | CON-0007 intent = f(governance status, owner): INVALID (partly valid) | MERGE | M5 | P2 | CON-0007 intent: derived attribute |
| 03 | T3-03 | CON-0008 kind (archetype): INVALID (partly valid) | MERGE | M5 | P2 | CON-0008 kind (P2-12) |
| 03 | T3-04 | CON-0011 knowledge space: INVALID (partly valid) | MERGE | M11 | P2 | CON-0011 knowledge space (F8) |
| 03 | T3-05 | CON-0030 platform ontology (L-B): INVALID (not verifiable) | CLOSE | register CON-0030 | — | INVALID; tracked in the register |
| 03 | T3-06 | CON-0031 product ontology (L-C): INVALID (partly valid) | CLOSE | register CON-0031 | — | INVALID; tracked in the register |
| 03 | T3-07 | CON-0029 documentation ontology (L-A): INVALID (partly valid) | CLOSE | register CON-0029 | — | INVALID; tracked in the register |
| 03 | T3-08 | CON-0032 declared scope: INVALID (partly valid) | CLOSE | register CON-0032 | — | INVALID; tracked in the register |
| 03 | T3-09 | CON-0006 operational state: INVALID (partly valid) | MERGE | M5 | P1 | CON-0006 operational state: carrier (MM-4) and the "enforced" split (P2-4) |
| 03 | T3-10 | CON-0010 knowledge ≠ artifact: INVALID (partly valid) | MERGE | M5 | P1 | CON-0010 knowledge ≠ artifact: bearer (P2-1, critical) |
| 03 | T3-11 | CON-0035 strategy, CON-0036 principle: INVALID (partly valid) | CLOSE | register CON-0035, CON-0036 | — | INVALID; tracked in the register |
| 03 | T3-12 | CON-0037 relation types: INVALID (partly valid) | MERGE | M5 | P2 | CON-0037 relation types: four orders (P2-6) |
| 03 | T3-13 | CON-0041 read across a boundary, never own: INVALID (not verifiable) | CLOSE | register CON-0041 | — | INVALID (n=1); tracked in the register |
| 03 | T3-14 | CON-0042 PKS as container: INVALID (partly valid) | MERGE | M11 | P2 | CON-0042 PKS as container (F8) |
| 03 | T3-15 | CON-0043 deployment (vs product): INVALID (partly valid) | MERGE | M11 | P2 | CON-0043 deployment (F8) |
| 03 | T3-16 | CON-0045 inert vs attested projection: INVALID (partly valid) | CLOSE | register CON-0045 | — | INVALID; tracked in the register |
| 03 | T3-17 | CON-0046 structural vs transformational: INVALID (partly valid) | CLOSE | register CON-0046 | — | INVALID; tracked in the register |
| 03 | T3-18 | CON-0057 kernel admission (Tier-1): INVALID (partly valid) | MERGE | M1 | P2 | CON-0057 kernel admission (Tier-1): a candidate admission test for Req |
| 03 | T3-19 | CON-0039 knowledge does not execute: INVALID (partly valid) | MERGE | M12 | P2 | CON-0039 knowledge does not execute (F11) |
| 03 | SEQ-0010 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved |
| 03 | SEQ-0012 | Sequence gap (SQ-1): circular | MERGE | M12 | P2 | circular knowledge/capability criterion (F11) |
| 03 | SEQ-0015 | Sequence gap (SQ-1): missing link | CLOSE | resolved | — | resolved |
| 03 | T3-20 | Fork F11: what "execute" means | MERGE | M12 | P2 | fork F11: what "execute" means |
| 04 | T4-01 | CON-0022 composite trajectory: INVALID (partly valid) | MERGE | M13 | P2 | CON-0022 composite trajectory: the laws of change (P3-10) |
| 04 | T4-02 | CON-0023 qualification: INVALID (partly valid) | MERGE | M13 | P3 | CON-0023 qualification (also M10 homonym) |
| 04 | T4-03 | R-6 count (guides product engineering) unverified | MERGE | M7 | P2 | the R-6 count: independence of instances (P3-4) |
| 05 | T5-01 | CON-0052 model amnesia: INVALID (partly valid) | MERGE | M9 | P3 | CON-0052 model amnesia: homeless count; Ch. 7 (I-17) |
| 05 | T5-02 | "PA" (a reviewing role) | KEEP | — | P3 | undefined "PA": R2 reading list |
| 05 | T5-03 | "Adopted stance N-10" | KEEP | — | P3 | N-10 adoption record: reading-dependent |
| 05 | T5-04 | CON-0050 presupposing evidence: INVALID (partly valid) | CLOSE | register CON-0050 | — | INVALID; tracked in the register; N3-04 restates the rule |
| 05 | T5-05 | CON-0053 gap kinds: INVALID (partly valid) | MERGE | M1 | P2 | gap kinds (CON-0053): "gap" homonym with §5.4, Zero (P3-2) |
| 05 | SEQ-0014 | Sequence gap (SQ-1): undefined | CLOSE | resolved | — | resolved |
| 05 | SEQ-0016 | Sequence gap (SQ-1): missing link (imprecise back-pointer) | CLOSE | resolved | — | resolved |
| 05 | T5-06 | A sole evidence path ("the log's growth is the ONLY path to an AI-Orch | KEEP | — | P3 | a sole evidence path: reading-dependent |
| 05 | T5-07 | Incidents and commissions as activations | MERGE | M2 | P2 | incidents and commissions as activations: D-2 (T1-46) |
| 06 | T6-01 | The method/binding/evidence decomposition has no class for elements th | KEEP | — | P3 | method/binding/evidence: a class for neither; reading-dependent |
| 06 | T6-02 | If case law moves to product spaces, the platform's methods keep their | MERGE | M9 | P3 | evidence of methods outside the platform: the loop |
| 06 | T6-03 | Three or more homes for evidence (platform register, product spaces, ` | KEEP | — | P3 | three homes for evidence: reading-dependent |
| 06 | T6-04 | Trigger vs gate. S0031 merges two kinds of entry condition for its sta | KEEP | — | P3 | trigger vs gate: a distinction to record |
| 06 | T6-05 | S0031's own arithmetic and logic. 9/7 vs 7 + 1 + 8 in its table; M-10  | CLOSE | source defect | — | source arithmetic; no theory change |
| 06 | T6-06 | The PKS → product arrow: speculative in S0031, "partially evidenced" i | MERGE | M9 | P3 | PKS → product arrow: the loop |
| 06 | T6-07 | Counts of the recurring self-error disagree: S0032 three absence error | MERGE | M7 | P2 | counts without a fixed class (CON-0070) |
| 06 | T6-08 | R-42's membership criterion for the platform side (an AI runtime momen | KEEP | — | P3 | R-42 membership: assess at its source |
| 06 | T6-09 | S0033's three-level rule (repository → product → subsystem; do not pro | KEEP | — | P3 | three-level rule: reading-dependent |
| 06 | T6-10 | Two ownership schemes: S0032's five layers with owners vs S0019's thre | MERGE | M8 | P2 | two ownership schemes vs three authority systems |
| 06 | T6-11 | Minor source defects: S0033's unmeasured "~90/95%" case law; S0035's q | CLOSE | source defect | — | minor source defects; no theory change |
| 08 | T8-01 | The experiment's raw record | KEEP | — | P3 | the experiment's raw record (N5-20) |
| 08 | SEQ-0013 | Sequence gap (SQ-1): missing step | CLOSE | resolved | — | resolved |
| 09 | T9-01 | The kernel's required capabilities | MERGE | M1 | P1 | Req itself |
| 09 | T9-02 | The state space 𝒮 | MERGE | M3 | P1 | the state space 𝒮 and the attribute signature |
| 09 | T9-03 | Two notions of minimality; the pool | MERGE | M3 | P2 | two notions of minimality; the pool |
| 09 | T9-04 | Uniqueness of the minimal kernel | MERGE | M3 | P2 | uniqueness of the minimal kernel |

## 4. Not in the chapter TODO files, added to the stage-2 work

- The three register defects found by R1 on the working book: CON-0138, CON-0140, CON-0143 missing from their home sections (P2; register fix).
- The 7 true R2 candidates: KT-8, AR-11, C-1, CV-1, CAP-001, C-14, "port" (P3; define at first use).
- The settlement's real corrections (47 material issues, 140 normal rows, 19 integration rows), minus the plant-only rows (`STAGE1-RESULT-RECORD.md` §3): applied in stage 2 as a separate work item, finding by finding.
