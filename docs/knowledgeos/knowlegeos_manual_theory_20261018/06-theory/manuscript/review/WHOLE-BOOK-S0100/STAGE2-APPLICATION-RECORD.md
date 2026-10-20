# Whole-book review S0100: stage 2, application record

*2026-10-20. By the Professor. Rulebook v1.3 with entries 56–58 (approved 2026-10-19). For each finding of `SETTLEMENT-STAGE1.md` this record says whether it was applied to the working book, what was changed, and whether its "resolved when" condition holds. Plant-only rows are excluded (`STAGE1-RESULT-RECORD.md` §3): P3-1, PS8-1, N4-01 and the L32 part of P5-1. The working book's text is the evidence; git holds every diff.*

**How R3 and R5 are applied here (entries 56, 58).**
- **R3:** for every accepted correction, `tmp/tools/inbound_citations.py` lists the inbound citations of the changed claim, its concept IDs and its section. Each citation is reassessed and the outcome is written below: *unaffected*, *changed* (with what), or *open*. Uncited paraphrase is not found by the tool; where the Professor knows of one, it is named.
- **R5:** a missing premise or ill-typed foundation is critical if F1–F4 hold. Severity was fixed at the settlement and is not re-rated. Resolution needs (i) the text, (ii) the proof or argument, (iii) the dependants and (iv) the register, together. Entry 58: the cited dependencies are confirmed one by one, and uncited ones are named.
- **"Resolved when" (entry 58):** each finding keeps the settlement's observable condition. It is closed only when the condition holds. An open item carries a role, a reason and a next action.

## 1. The four critical findings (item 4a)

### P2-1: the bearer of the dimensions

- **Applied: yes**, as a definitional fork with both meanings kept (DB-5: no Principal decision unless the kernel can carry only one).
- **Text:** §3.1, new section "What carries the dimensions (fork F37)": the ill-typing stated; reading (A), one bearer, an instance = (claim, carrier), with nature and evidence status equal across a claim's instances; reading (B), a bearer per dimension (nature and evidence status → claim; representation → carrier; operational state → realization, MM-4; governance status and canon scope → open). S0016's six-artifact test re-run under (B). §3.4 cross-reference. CON-0002's tag → provisional.
- **Argument:** the re-run classifies all six artifacts under (B) without compound values; the two compound cases (CAP-001 "prose + code"; `Round39-MC` "prose" and "enforced") are the two-bearer cases. Well-typedness only; no independence or sufficiency test.
- **Register:** CON-0002 VALIDATED → PROVISIONAL; CON-0184 (A) and CON-0185 (B) new, linked by same_subject_as. **Map:** §1 dimensions line; F37 new; F13 notes the bearer.
- **R3** (§3.1, CON-0002, CON-0010; 18 + 5 + 7 citations):

| Citation | Outcome |
|---|---|
| §0.3 L66 (pointer to Ch. 3) | unaffected |
| §3.2 L37 (owner is not a dimension) | unaffected |
| §3.3 L9 (kind extends the formula) | unaffected; if kind is admitted as a seventh axis (T3-03), it needs a bearer under F37 |
| §3.4 L32 | changed (cross-reference to (A)/(B)) |
| §3.6 L42, L94 | unaffected |
| T3-09 (MM-4) | changed: (B) gives operational state the realization as bearer; resolution criteria now include F37 |
| T3-10, SEQ-0012 (CON-0010) | unaffected; CON-0010 stays INVALID (partly valid) for its own reasons (§3.5) |
| CON-0001, -0003, -0004, -0005, -0006 rows | unaffected in status here; CON-0005 and CON-0006 are settled under P2-5 and P2-4 |
| CON-0066 (executability is a representation property) | unaffected; consistent with (B) (a carrier property) |
| map L16, F6, F13; outline L11 | map changed; F6 unaffected (it is the governance-status split); outline unaffected |

- **R5, dependencies confirmed:** GKC L19–20 (claim as unit) ✓; S0016 L58–63, L121–135, L154 ✓ (read for this record, file hash matching the allowed list); §1.5 ✓. **Uncited dependency named:** F6 (governance status split) decides whether governance status has one bearer.
- **Resolved when** "each dimension has a bearer; the test is re-run": **holds for (A)**; **does not hold for (B)**, where governance status and canon scope have no bearer yet. The re-run is done. **Open item:** role Professor; reason: the bearer of governance status depends on F6 (standing vs lifecycle, PM-1), and canon scope on the scope senses (N1-31); next action: assign both bearers after F6 and N1-31 are applied (item 4b), or record why they stay open.

### P2-2: set-wise conflict, context and choice

- **Applied: yes.**
- **Text:** §2.3, new (d): context c and outcome x split; a set of rules in conflict iff some (c, t) in every rule's effective applicability has no x satisfying all mandatory effects; pairs as the two-rule case; minimal conflicting sets with their regions reported; the three-rule example flagged; monotone on the common region; cost. The "final definition" carries a forward pointer to (d). §2.6, "Re-run on sets": over-approximation stays sound if each partition is analysed as a set; comparing pairs with intersecting keys is not enough. §2.9: the gate rule re-derived for the whole applicable set. §2.5 L22: the region restated for minimal conflicting sets. Also applied here, being in the same paragraph: **N2-28** (decidability wording, §2.3 (a)).
- **Map:** §3 conflict line restated (this also applies **N2-08**).
- **Register:** no concept row holds §2.3's definition (Ch. 2 is pre-register in this respect); none changed.
- **R3** (§2.3; 17 section-only):

| Citation | Outcome |
|---|---|
| §0.2 L37; §0.5 L10 (I-4); §1.14 L141 (decisions, T2-01) | unaffected |
| §2.2 L43 (applicability) | unaffected |
| §2.4 L26 (region-valued result), L53 ("a genuine conflict in the sense of §2.3") | unaffected: (d) generalizes both |
| §2.5 L22 (the region of a conflict) | changed (minimal conflicting set) |
| §2.5 L24 (where the region is computable) | unaffected |
| §2.6 | changed (re-run on sets) |
| §2.8 L85 (UNKNOWN over unknown overlap) | unaffected; holds for sets |
| §2.8 L227, L255 (E6) | unaffected |
| §2.9 L38–39 (proven conflicts only between mandatory effects) | unaffected; "joint unsatisfiability" already set-wise in wording |
| §2.9 gate rule | changed (re-derived) |
| T2-01 | unaffected |

- **R5, dependencies confirmed:** A′ᵣ (§2.3 (b), audit L-07) ✓; §2.9's PC L77–87 gate rule ✓ (as quoted in §2.9). **Uncited dependency named:** §2.8.8's licensing rule ("no conflict" licensed by disjointness, E1) holds per set as well, since disjoint applicability empties the common region.
- **Resolved when** "the three-rule example is flagged; the gate rule is re-derived": **holds. Closed.** Dependent work (conflict-based gate results) may resume.

### P4-1: the kernel type

- **Applied: yes.**
- **Text:** §9.1: Pres and Min replace the direction's A1 and A2 (also applies **I-13**'s rename); C2's tag → invalid as the kernel type, with its scope (state invariants); C3's scope extended; new **C6**, the kernel type (𝒮, X, M, G, I_S, I_T, I_R, Req): input origin, guards, transition and trace invariants, applications, Kernel′ = P_S ∧ P_T ∧ P_R ∧ Req, six results, and what it does not show; dependency table and tool line. §9.6 "the form of the test" uses Kernel′. §9.6–§9.9 each carry a "Kernel type" line: their results are informal with respect to C6 (the settlement's "or label §9.6–§9.9 informal", applied together with the type).
- **Computation:** `tmp/tools/kernel_type_model.py`, run `tmp/tools/kernel_type_model-run-20261020.txt` (all assertions passed).
- **Register:** CON-0100 PROVISIONAL → INVALID as the kernel type (its state-invariant results kept; I-19 (3)); CON-0183 new (restated form). **Map:** §6 kernel line; F17 scoped; **F36** new. **Outline:** new row. **TODO:** T9-05 new.
- **New result, beyond the settlement:** C3's one-check existence fails for trace invariants (C6 result 4, a counterexample in the model), and is restored by a history variable that every act-consuming mechanism reads and writes (result 5).
- **R3** (§9.1 C2, CON-0100, §9.1 C3, §9.1; 5 claim, 8 concept, 30–39 section-only, overlapping):

| Citation | Outcome |
|---|---|
| §6.6 L77 (stop conditions, the trivial case) | unaffected (C6 result 2) |
| §9.2 L18 ("preservation is decided element by element") | **changed:** true for state and transition invariants, false for trace invariants (C6 results 3–4) |
| §9.2 L19 (litmus like P) | unaffected |
| §9.3 L3 (depends on CON-0100 PROVISIONAL) | changed: status updated; the links use only must-never/must-do and the guard reading, which C6 keeps |
| §9.3 C2 L18–25 (guard reading; C3 does not apply) | unaffected; confirmed by C6 result 6 |
| §9.3 L35–36 (necessity, minimality) | unaffected |
| §9.4 L67, L86 | unaffected |
| §9.5 L19, L44 (must-do half) | unaffected |
| §9.6 L15, L71, L263 | L71 changed (Kernel′); others unaffected |
| §9.6–§9.9 results | changed: marked informal w.r.t. C6; T9-05 |
| §5.4 L39 (requirement vs required capability) | unaffected |
| T9-01–T9-04 | unaffected; T9-04: the C6 model also has two minimal kernels ({record, grant}, {record, grant_nh}), consistent with T9-04 being open |
| CON-0105, CON-0110, CON-0111 | unaffected |
| map L172, F16, F17; outline L21, L65, L71 | map and outline changed; F16 unaffected |

- **R5, dependencies confirmed:** EXT-010 L19–89 and EXT-011 L224–275 as quoted in §9.1.1 ✓; H1′ (§9.8), I-1(GKC) (§0.5), U (§1.16.7) ✓ as stated in their homes. **Uncited dependency named:** ARCH-INV-1 (§1.14.2) for the origin condition; it is cited in C6's "Why" line through A1 only.
- **Resolved when** "§9.1 restated; the deleting-record and forge models excluded; C3 re-proved": **holds. Closed.** C3 is re-proved for state and transition invariants and shown to fail for trace invariants without a history variable. Dependent work (kernel placement) may resume, informally with respect to C6. **Follow-ups, not part of the condition:** T9-05 (derive §9.6–§9.9 against Kernel′; role Professor); F36 (must the kernel carry transition and trace invariants; architecture fork).

### P4-4: F23 result 5

- **Applied: yes**, together with **N4-14** (settled "with P4-4").
- **Text:** §9.9.7 M-T2 restated by A1's reading (A1-as-form: an independence model, so occurrence is a primitive candidate, stated as an axiom; A1-as-fact: A1's bridge premise, the residual assumption of §1.12); the residual counted once (§1.12-2). §9.9.9 result 5 restated: T-a a re-expression, T-c untested (Hyp), T-b's occurrence by A1's reading; DB-1 named. Result 4 scoped to a single-operator estate given §8.6 C1, "candidate axiom" labelled Hyp. §9.9.5 "What follows" labelled Hyp, with the rival (faithfulness as an applicability assumption). §9.9.1 plain language aligned. §9.9.11 records result 5's first form.
- **Register:** CON-0182's meaning and history updated. **Map:** F23 row and the theory-turn-4 line. **Outline:** Trust row.
- **R3** (§9.9, CON-0182, CON-0161; 6 + 4 + 4, overlapping):

| Citation | Outcome |
|---|---|
| §9.1 L26, L116; T9-05 (cite §9.6–§9.9 as a range) | unaffected |
| map F23, map L207 | changed |
| outline L19 | changed |
| CON-0161 row | unaffected (INVALID as stated stands) |
| CON-0182 row | changed |

- **R5, dependencies confirmed:** §9.9.2's definition of a primitive (an independence model) ✓; §8.6 C1 ✓; §1.12-2 ✓. **Dependency on a later correction:** the names A1-as-form and A1-as-fact are defined in §9.9.7; their home is Ch. 1 §1.6, where I-04 names A1's readings (item 4b).
- **Resolved when** "result 5, the map and the outline agree": **holds. Closed**, with one consistency check carried to I-04: §1.6's named readings must match §9.9.7's two definitions.
