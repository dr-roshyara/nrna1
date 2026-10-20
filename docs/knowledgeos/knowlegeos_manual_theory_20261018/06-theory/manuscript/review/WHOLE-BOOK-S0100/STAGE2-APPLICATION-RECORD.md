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

## 2. P1, Chapters 0 and 1 (item 4b)

*Applied to the working book 2026-10-20 (files under `chapter_00/`, `chapter_01/` only). R3 run with `tmp/tools/inbound_citations.py` on §1.8, §1.6, 1.14.1-7, 1.14.1-8, CON-0016, §1.1, §0.1, §0.7, 1.14.2-1, §1.2 (inbound citations from Ch. 0–1 themselves and from review folders not listed). Edits needed in shared files and other chapters: `part-P1-shared.md`.*

### P1-1: §1.8's identity
- **Applied: yes** (with N1-13, I-07, P1-10).
- **Text:** §1.8 retitled "the generative reading (a target), and an untested loop"; S0028 cited as Src, CANDIDATE [S0028 L37–41] in place of "cited-only"; T1 = "the reusable engineering product" [S0028 L39]; analysis restated as conditional, with the responsibility exercised by a person and the component absent, n = 0 [S0028 L144–148] (read for this record; allowed list); "resolves Chapter 0's layered reading" removed: §0.2 keeps both identities open; empirical test → the unwritten Ch. 7 (I-07). §0.2's revised box reconciled with L71 (target; both identities open). Ch. 1 index title updated.
- **R3** (§1.8, 32 section-only outside Ch. 0–1): §3.1 L24 (the "6 dimensions" lead) unaffected; §4.1 L60, §5.3 L42, §6.1 L34, L72, §6.4 L41 (LA-4 / CON-0076, the "every part travels" Ded) unaffected: they cite the component rule and the portability result, not the identity. Register row for the target: **shared** (new row).
- **Resolved when** "§1.8's head is conditional; §0.2 and §1.8 say the same; register row present": head and text conditional ✓; §0.2 and §1.8 agree ✓; register row **open** (shared file; role: Professor; next action: add the row proposed in `part-P1-shared.md`).

### P1-2: A1's status
- **Applied: yes** (with I-04, N1-07, I-18, N1-14, N1-09).
- **Text:** §1.6: A1 restated as a stipulated definition of recognized standing, with scope; the family are "consequences of A1"; **A1's readings named** (A1-de-jure; A1-recognized; A1-as-form = "every standing change in the record is labelled by a recorded act"; A1-as-fact (A1-fact) = "standing changes only by acts that occurred", matching §9.9.7 verbatim); the empirical claim separated (Hyp) with a falsifier and the practice rival; "predicts" sentence removed; "one axiom" replaced by the premise list (A1, companion grant premise, P-E, P-R, INV-A3, ι, C1); DB-1 named. §1.14.4-1: "NEW EVIDENCE for §1.6" replaced by "an instance of §1.6, not evidence for it". §1.14.5-13: reading (e), standing conferred by practice, added ("at least five").
- **R3** (§1.6, 56 citations outside Ch. 0–1): every citation uses A1 as a premise (Ch. 2 §2.0a L46, §2.4 L16, §2.5 L10–29, §2.7 L5, §2.8 L147, L278, §2.11 L33; §3.5 L40, §3.6 L36, L76, §3.7 L59; §4.0; §5.1 L27, §5.2 L28, §5.3 L64, §5.4 L102, §5.5 L23, L83, §5.6 L77; §6.5 L30, L92, L137, §6.6 L102; §8.6 L13, L82, L114; §8.9 L83, L118; §9.1, §9.4, §9.6, §9.7) → **unaffected** given A1-recognized. **Changed / to name a reading (shared):** §2.8 L31 (adds conditions to A1), §9.8 M1 (A1 as a fact), THEORY-MAP L29 ("Ax A1") and L278 ("Authority has three meanings"). §9.9 already uses A1-as-form / A1-as-fact (applied at 4a). Register CON-0020 "reversible only by refutation" (I-18): already INVALID; meaning unchanged.
- **Resolved when** "A1 carries a scope and a label; no instance is counted as evidence; the brief is filed": scope and label ✓; no instance counted as evidence in Ch. 0–1 ✓ (1.14.4-1 relabelled); brief DB-1 filed ✓ (`DECISION-BRIEFS-S0100.md`). **Holds. Closed.** Propagation of reading names outside Ch. 0–1: open (shared file; role: Professor).

### P1-3: §1.14.1's formal model
- **Applied: yes** (with N1-37, N1-20).
- **Text:** 1.14.1-8 head "Ded, conditional on the operator being monotone; property 1 is a sketch"; property 1 marked a sketch with its assumption; L249's "every value of the open parameters" conditioned (monotone operator; values checked); status box "not established for any setting" (§9.7); claims index regenerated from the heads (47 rows; the index now repeats each head's wording, incl. -7, -3-3, -5-14). 1.14.1-4 head scoped (exact for P = ∅).
- **R3** (1.14.1-7, 1.14.1-8; 13 + 10): §6.7 L64, L119; §8.6 L37; §8.10 L47, L126; T6-10 (these match "§1.14" generally) **unaffected**; THEORY-MAP L212, L250 (history lines) unaffected. §9.7 (which cites the model as not a function) consistent.
- **Resolved when** "index matches the heads; no unconditional 'proof' remains": index regenerated ✓; -8 conditional ✓; -7 already "sketch". **Holds. Closed.**

### P1-4: senses of "authority"
- **Applied: yes, in part.**
- **Text:** §1.2: CON-0016 tag → provisional ("two independent questions"; both factors INVALID); S0018 L104's guard recorded as a candidate counterexample to independence (quoted, read for this record); five senses of "authority" qualified (authority-field, authority-pair, current authority, grant authority, authority-body); claim identity named as an open prerequisite (§1.10).
- **R3** (CON-0016, 8 concept): register rows CON-0016 (status → shared), CON-0109, CON-0138 unaffected; THEORY-MAP L278 ("three meanings") → shared; BOOK-OUTLINE L13 unaffected.
- **Resolved when** "each use is qualified; the register is consistent": senses defined and named ✓; **not every use in Ch. 1 is rewritten with a qualifier** (open: role Professor; reason: a book-wide qualification pass belongs with the glossary, M10; next action: qualify uses when the glossary is written); register CON-0016 → PROVISIONAL is in the shared file (open until applied).

### P1-5: the knowledge/capability line
- **Applied: yes** (with N1-24, I-05 in part).
- **Text:** §1.1: new block "The line is drawn three ways" (config/app.php as knowledge vs §1.5's PHP files as capabilities; §0.3's Executable; S0008 L48's AST-012/013 counterexample, read for this record; §3.9 C2's convention as the third line; recorded under F11, test cases AST-012/013). §0.3: tension noted at "Representation". 
- **R3** (§1.1, 12): §3.6 L67, T3-19, SEQ-0012, CON-0039 **unaffected** (they already record the circularity); none cites §1.1's config placement as settled.
- **Resolved when** "§1.1 and §1.5 apply one criterion, or F11 records the case": **F11 must record the case** — the text records it under F11; the THEORY-MAP F11 row needs the entry (shared). Holds once the map row is added.

### P1-6: kernel/application marks for Ch. 0–1
- **Applied: yes, as a proposal.**
- **Text:** Ch. 1 index, new table "Kernel, application and observation marks for Ch. 0–1 (Int; a proposal, untested)": every law of §0.5 (I-1…I-11), §0.1, §0.7, every Ch. 1 section's law, and every register concept homed in Ch. 0–1 (CON-0012…0028, 0044, 0051, 0054, 0076, 0084, 0163), with form, proposed mark (K?/A/O/D/P) and the must-do partner as an input to Req (T9-01).
- **R3:** no inbound citation; feeds T9-01 (Ch. 9 TODO; shared: add a pointer).
- **Resolved when** "a table covers every Ch. 0/1 concept": **holds** for laws and register concepts. The marks are untested proposals (the boundary test is not run here).

### P1-7: "valid"
- **Applied: yes** (with I-09 for §1.16 L65).
- **Text:** §0.1: "valid" split into current and well-founded; composite predicate usable(k, t) = current ∧ well-founded ∧ visible, with a worked case; "unifies all" weakened (loss = absence, not invalidity). §1.16 L65 names the currency sense.
- **R3** (§0.1, 9): §2.1 L11 (I-09: must name the currency sense) → **shared**; §2.10 L11 unaffected; CON-0052 unaffected; map L7, outline L81 unaffected.
- **Resolved when** "one definition with a worked case": **holds. Closed** (Ch. 2 §2.1 L11 to follow, shared).

### P1-8: the working context
- **Applied: yes.**
- **Text:** §0.7: one rule — content discarded, pin kept (§1.16.6), step 4 restated; §1.17.4: recording means a pin, consistent with §0.7.
- **R3** (§0.7, 28): §2.8.8 L243, L255; §5.4 L54; §6.6 L20, L37, L40, L88, L102; §9.5 L7, L18, L55, L68; CON-0117, CON-0139; map L191; outline L76 → **unaffected** (CON-0139's restated form already counts the working context and pinned quotations as non-authoritative copies; §9.5 uses the pinned reference).
- **Resolved when** "one rule stated in both places": **holds. Closed.**

### P1-9: "ARB" headings
- **Applied: yes.**
- **Text:** eight 1.14 heads and §1.15's provenance attributes: "an ARB ruling (comment, keep, adoption) relayed (V3); one line of support, V3"; index regenerated.
- **R3:** heads only; THEORY-MAP L212/L250 unaffected.
- **Resolved when** "no heading claims an ARB ruling without 'relayed'": **holds. Closed.**

### P1-10: §1.8 "natural experiment"
- **Applied: yes.** "consistent with" the scent principle; rival S0032 L197 (the index exists and is not read; read for this record). **Resolved:** done as stated. Closed.

### P1, normal rows, I-rows and PS8-rows

| ID | Applied? | What changed (file:§) | Note |
|---|---|---|---|
| N1-01 | yes | 00.05: GKC L66 quoted ("Every other rule derives from it: …") with readings (i) and (ii) | quote taken from the settlement (GKC not re-opened) |
| N1-02 | yes | 01.14: 1.14.4-11, S0012's withdrawal of LG-1 (L152; S0018 §4) | — |
| N1-03 | yes | 01.00 index: convention (unqualified = GKC; I-n(RO), I-n(S0008)); 00.03 L59 → I-1(RO); 01.11, 01.09 → I-7(S0008); 01.14 → I-4(RO) | book-wide qualification beyond Ch. 0–1: shared |
| N1-04 | yes | 01.13: 1.13.1-3 property 3 "unenforced" (S0097 L41; 144/144 written by the tool) | — |
| N1-05 | yes | 01.14: 1.14.2-1 further evidence (a)–(d), quoting S0097 L41 and S0100 L22 (read) | merged with PS8-7 |
| N1-06 | yes | 01.03: "three labels"; grade vs provenance untested | removal test stays open |
| N1-07 | yes | 01.06: "predicts" sentence removed | with P1-2 |
| N1-08 | yes | 01.14: 1.14.5-14, `frozen` re-anchored to S0016 L43, S0020 L24; S0018 L27 kept for order, roles, guards | anchors are the reviewer's (R-sv) |
| N1-09 | yes | 01.06: S0018 L121 quoted exactly ("requiring either that …"; read) | — |
| N1-10 | yes | 00.05: the step to "every claim serves some need" stated as part of (T), assumed | — |
| N1-11 | yes | 00.09 Q1: answered by RESEARCH-PURPOSE, the kernel; F14 | — |
| N1-12 | yes | 00.06 box: "GKC's status fuses standing and lifecycle; only its standing part is meant" | — |
| N1-13 | yes | 01.08: T1 "product" | with P1-1 |
| N1-14 | yes | 01.06: S0018 L105 primary (read), S0002 L37 a restatement, one line | — |
| N1-15 | yes, in Ch. 0 | 00.03: GKC L39 wording; ⇔ → ⇒; Ch. 0's ⊥ → *na* (inapplicable) | book-wide ⊥ reservation: shared (glossary) |
| N1-16 | yes | 00.02: MQ-7 marked as posed in the withdrawn framing; used as the book's own criterion (Int) | — |
| N1-17 | yes | 00.01: "[RQ-002 Project Knowledge Domain L52–62]"; 00.00: Meta-Model, Raw, Taxonomy, Project Knowledge Domain added | — |
| N1-18 | yes | 01.04: counts as reported (U); "consistent with criteria causing closure" | — |
| N1-19 | yes, in part | 01.09: non-blocking checks named in the 8; stage mapping stated as not given | the per-row split of the 8 is open (code-level U) |
| N1-20 | yes | 01.14: 1.14.1-4 head scoped | — |
| N1-21 | yes | 01.02: N-2 always act-labelled (A1 restated); root document adopted at creation = an act | — |
| N1-22 | yes (evidence only) | 01.05: rival CON-0120, falsifier, "valid, conditionally" | B2, not scored |
| N1-23 | no change here | — | settled under P3-10 (Ch. 4 / §1.6 deferral loop; P3 part) |
| N1-24 | yes | via P1-5 | B2 |
| N1-25 | no change | — | pointer only |
| N1-26, N1-27, N1-29, N1-33, N1-35 | via P1-7, P1-4, P1-5, N1-03, N1-15 | — | — |
| N1-28 | yes, in part | 01.14 and 01.00: title "State (V3's lifecycle state) is not authority" | glossary entries: **open** (M10, no glossary file yet) |
| N1-30, N1-31, N1-32, N1-34 | **open** | — | glossary qualifications (binding, scope, projection, record); M10; role Professor; next action: write the glossary |
| N1-36 | yes | 01.07 "a label of its own"; 01.11 "a property of its own" | — |
| N1-37 | yes | 01.14: 1.14.1-8 conditional, property 1 sketch (-7 already "sketch") | — |
| N1-38 | yes | 01.07: bound undefined for k = 0; rule needed (open) | — |
| N1-39 | **no matching text** | — | the working §1.14.1 has no sentence "today no rule … cites the grant"; already changed in an earlier round |
| N1-40 | yes | 01.09: ladder hypothesis labelled the book's Hyp | — |
| N1-41, N1-42 | no change | — | confirmations |
| N1-44 | no change in P1 | — | see P5-3 |
| I-04 | yes, in Ch. 1 | 01.06: readings named (definitions match §9.9.7) | propagation to §2.8 L31, §9.8 M1, map L29: shared |
| I-07 | yes | 01.08: empirical test → unwritten Ch. 7 | — |
| I-09 | yes, §1.16 part | 01.16 L65: currency sense; "current" | §2.1 L11: shared |
| I-18 (new correction) | yes | 01.06 table: "refutation only" not kept (rests on CON-0020) | the core "cannot be decreed" stays |
| PS8-6 | yes, Ch. 0–1 part | 00.00 and 01.00: references form stated (short names; §1.1–§1.11, §1.15–§1.17 without tables) | book-wide missing pairs: open (role Professor; `PROFESSOR/STEP8-REFCHECK.txt`) |
| PS8-7 | yes | via N1-05 | — |
| PS8-8 | yes | 01.14: 1.14.5-1 further evidence: what is the same (order) and what differs (no normative effect) | resolved: cannot be read as CON-0159 as stated |
| PS8-1–5, 9–15 | not in Ch. 0–1 | — | — |

**Counts (P1 part):** applied 54 (incl. in part 5), no change 8, open 6 (N1-30, -31, -32, -34; N1-28 glossary half; PS8-6 book-wide half), no matching text 1 (N1-39).

## 3. P2, Chapters 2 and 3 (item 4b)

*Fork record, 2026-10-20. P2-1 and P2-2 are in §1 of the application record (commit 81cfcd1eb). Working-book files only; nothing staged or committed. Shared-file edits are listed in `part-P2-shared.md`.*

### P2-3: §2.8.4 containment with ⊥
- **Applied: yes.** §2.8.4 containment step 3 added: a "false" becomes "unknown" when some Xᵢ of A is ⊥, since ⊥ may complete to ∅. Counterexample stated: A = (⊥, {a}), B = (V₁, {b}).
- **Check:** `tmp/tools/containment_check.py`, run `tmp/tools/containment_check-run-20261020.txt`. For k = 2 and V = {a, b}, all 625 pairs: steps 1–2 alone are unsound in 70 cases; steps 1–3 agree with supervaluation in all 625; the overlap rule agrees in all 625.
- **R3** (§2.8.4, none at claim level): the containment-chain sites (§1.6 L23, §1.13 L92–93, §2.11 L19, §1.14 L429) use containment as a concept, not this rule's verdicts. **Unaffected.** The fix only turns some "false" results into "unknown", and an authority check over unknown does not pass, so the change is conservative.
- **Resolved when** "the brute-force check agrees with the rule": **holds** for the case checked. **Closed.**

### P2-4: "six independent properties"; "enforced"
- **Applied: yes.**
- **§3.1:**
  - the plain-language text now reads "independent only up to stated constraints";
  - the cited pair L127/L128 is shown to prove nothing; the valid pairs are L129 vs L131 (operational state is not a function of governance status) and L129 vs L130 (the converse, added by the Professor and checked in S0016);
  - "independent except constraint set S" (§0.3);
  - three senses of "enforced" (binding force, runtime block, lint), and "unexercised" as exercise history;
  - a mapping table from CON-0006's values onto §2.9's properties. This closes **T2-04**, pending review; the TODO is updated.
- **Also applied:** N2-15 and N2-17 (S0016 L54 against L46, recorded).
- **R3** (CON-0006): map L16 (shared: add the mapping note); CON-0006 row (shared). §2.9 is unaffected: its four properties stand, and §3.1 now cites them.
- **Resolved when** "mapping table in place; §3.1 restated": **holds** in the text. The register row for CON-0006 is shared. **Closed** once the shared edit is made.

### P2-5: CON-0005 is PROVISIONAL
- **Applied in text:** the §3.1 tag now reads provisional, with S0013 L77. The register status change is shared.
- **R3** (CON-0005):
  - §8.5 L63 is unaffected (a grade-rise example);
  - map L279 is unaffected;
  - CON-0122 is unaffected.
- **Resolved when** "register row updated": **open, waiting on the shared edit** (role: Professor; next action: apply part-P2-shared item P2-5).

### P2-6: CON-0064, the impact clause and acyclicity
- **Applied: yes.** §3.8:
  - C3's tag separates the clauses; the impact clause is marked **H**, with S0026 L103, which supports the opposite of "the union answers them";
  - each relation's propagation direction is stated as the book's Int;
  - C2 is re-tested on S0021's relations: "depends on" has the PKS ↔ product cycle (L92–93), "references" is cyclic, and "contains" is acyclic only under H-3. Acyclicity holds on S0026's edge set only;
  - §3.6's four relations are mapped onto §3.8's four (N2-18).
- **R3** (CON-0064, §3.8):
  - §5.4 L106 ("an order only if acyclic"): unaffected;
  - §6.4 L46: unaffected;
  - §6.6 L54, L84: unaffected (open questions);
  - §6.1 L78: unaffected;
  - T6-06: unaffected;
  - map L22 and outline L15: changed (shared);
  - CON-0104: unaffected.
- **Resolved when** "clauses graded separately": **holds** in the text. The register meaning is shared.

### P2-7: §2.5 priority numbers
- **Applied: yes.** The title is narrowed to author-assigned ranks (in the index too). A new paragraph says neither ground excludes precedence enacted by the competent authority: it is a resolution by act, under A1, and I-10 is an instance. Standing (class-wide) enacted precedence is left open.
- **R3** (§2.5, 13 section-only):
  - §5.1 L44: unaffected;
  - §6.6 L54 (a "linear authority hierarchy" as a source proposal) asks the §2.5 question. **Changed in meaning:** if that hierarchy is enacted by an authority, §2.5 no longer rejects it. Propagation flag (shared): §6.6 C3 should cite §2.5's new scope;
  - CON-0040: unaffected.
- **Resolved when** "title and claim scoped": **holds** in Ch. 2. The map row needs no change; it already says "void bounded by the conflict region".

### P2-8: CON-0038, invariant vs choice
- **Applied: yes.** §3.6:
  - the tag is now provisional;
  - the two halves are shown not to be complements (who may change vs whether a change breaks the model);
  - one criterion is stated as Int;
  - "freely" is marked as a redefinition;
  - I-7 is marked as breached by the product, so the split fails for I-7 as a statement of what holds.
- **R3** (CON-0038): the register row only. **Shared:** status PROVISIONAL, meaning updated.
- **Resolved when** "row and section updated": the section holds; the row is shared.

### P2-9: senses of "knowledge space"; boundary vs coverage
- **Applied: yes.**
- **§3.10:**
  - an S0 coordinate-space row added, with the PKS-as-region reading (Int). GKC and PKSM are outside the allowed list, so attribution is **U**, on the reviewer's check;
  - `scope.include`'s two jobs split into boundary(A) and coverage(A) (S0016 L30; S0020 L34–35; Ch. 2 §2.8.1);
  - F8 restated per sense (S0–S3);
  - the membership criterion now reads boundary(A) ⊆ boundary(B).
- **§3.3:** the CON-0011 table row notes the split.
- **R3** (CON-0098, CON-0011): map F8 L127 and L312 changed (shared: F8 per sense; four senses); CON-0042 and CON-0096 unaffected; CON-0097 term mismatch (N2-13, shared).
- **Resolved when** "F8 restated": **holds in §3.10.** The map's F8 row is shared.

### P2-10: kernel/application mark
- **Not applied here.** It needs a register column, which is a shared file; listed in the shared file. The test cannot be applied in Ch. 2–3 text without the column.
- **Open:** role Professor; reason: register column; next action: add the column (with I-12), then mark the Ch. 2–3 concepts.

### P2-11: "rule" vs §2.0's set
- **Applied: yes.**
- **§2.0:** "norm" (the formal object, adopted or not) is split from "rule" (an adopted norm), and the analyses are stated for norms.
- **§2.10:** a terms note says the thesis concerns norm-bearing statements (norms, decisions, checks), reading "rule" in RS's measured sense.
- **Not done:** "name §2.0's three rules". RS is not in the allowed list, so it is recorded as open (**U**) in §2.0.
- **R3** (§2.0, 9 section-only): outline L16 ("Policy (rules)") unaffected. The Ch. 2 sites use "rule" for candidates, read as "norm" by the new convention. No text change elsewhere.
- **Resolved when** "terms split": **holds.** Naming the three: **open** (role Professor; reason: RS outside the allowed list; next action: an explicit grant for RS, or leave U).

### P2-12: §3.3 kinds
- **Applied: yes.**
- **§3.3:**
  - the plain language now says "persist, with shared vocabulary" (N2-07);
  - what each of the five passes classifies (S0009 L38–44; S0021 L48);
  - the category test applied to each kept kind: runtime asset (a representation value), projection (a provenance value) and artifact (a carrier) fail; the rest pass in this reading;
  - a "kind" sense note (N2-12);
  - T3-03 updated.
- **R3** (CON-0008, §3.3, 16 section-only):
  - §0.3 L66, §6.0, §6.3 L23: unaffected;
  - §9.9 L106 ("Type … §3.3 kinds"): unaffected;
  - CON-0008 row: shared (meaning: three kept kinds fail the test);
  - outline L11: unaffected.
- **Resolved when** "test applied": **holds.**

### Normal findings and PS8 rows

| ID | Applied? | What changed (file:§) | Note |
|---|---|---|---|
| N2-01 | settled under P2-10 | — | open with P2-10 |
| N2-02 | yes | 03.01 governance-status bullet: S0012 L125 qualified with its own words | quote checked in S0012 |
| N2-03 | yes | 03.04 plain language, heading, table row; 03.00 index: "distinguishable" | source quote kept as the source's |
| N2-04 | yes | 03.05 Open: both risks quoted (S0023 L79) | checked |
| N2-05 | yes | 03.02: "only the orthogonality part" | — |
| N2-06 | yes | 03.01 table: value lists anchored to GKC L35–38 | GKC outside the allowed list: U (reviewer's anchor) |
| N2-07 | yes | 03.03 plain language | with P2-12 |
| N2-08 | applied with P2-2 | map §3 (commit 81cfcd1eb) | — |
| N2-09 | yes | 02.02: ten parts in the mapping, seven formalized, three not | — |
| N2-10 | yes | 02.04: "A1, the authority axiom (Ch. 1 §1.6)" | no "authority theorem" left in Ch. 2 |
| N2-11 | yes | 02.07: "resolves … to proven, refuted or unknown" | — |
| N2-12 | yes | 02.08 §2.8.6 and 03.02: scope-dimension test vs meta-model dimension test; 03.07: S0022's H-5; 03.05: "projection" sense; 03.03: "kind" sense | no S0013 H-5 use found in Ch. 2–3 |
| N2-13 | shared | CON-0097 term "two criteria" vs five in the text | the text has five; the term should change |
| N2-14 | yes (with I-15) | 02.11: "adopted" is a lifecycle status (RS L144); Effective = adopted ∧ t ∈ validity; 02.08 §2.8.6: three lifecycle notions aligned | — |
| N2-15 | settled under P2-4 | 03.01 | — |
| N2-16 | yes | 02 TODO T2-10 label → VALIDATED | matches the register (VALIDATED) |
| N2-17 | yes | 03.01 mapping table (exercise vs consequence); S0016 L54 vs L46 noted | — |
| N2-18 | yes | 03.08 C2: S0021 re-test; §3.6 → §3.8 mapping | with P2-6 |
| N2-19 | yes | 03.09 C2: skill counterexample, two treatments, interim scope to explicit knowledge | — |
| N2-20 | yes | 02.10: counted once; CON-0055 vs CON-0092 reconciled (compatible); "10 advisory hooks" holds by mechanism type only; 03.09 C4 scope note | — |
| N2-21 | yes | 03.06: "anchored to its DP"; rival reading (one concept, two names; I-2, S0021 L106) | test case open |
| N2-22 | yes | 02.04: DISJOINT is a reason for CONSISTENT; "cannot establish … may report a recorded one"; reconciled vocabulary | — |
| N2-23 | yes | 02.03 (e) liveness; 02.06 "sound is not useful"; 02.08 §2.8.8 safety note | — |
| N2-24 | yes | 02.08 §2.8.2: at equality, a lifecycle act by the rule's authority | who may authorize exceptions: open |
| N2-25 | yes | 03.05: proofs 1–2 are naming issues, 3 bears on L-A ≠ L-B; "fourth layer declined" rests on document type = knowledge kind | — |
| N2-26 | yes | 02.08 E4: "no carrier in the examined representation class" | — |
| N2-27 | yes | 03.09 C1 table: L567 for "Capability ≠ Authority", L286 for API-006; references updated | both checked in S1524 |
| N2-28 | applied with P2-2 | 02.03 (a) | — |
| PS8-6 (Ch. 2–3 part) | yes | Ch. 3: 9 references tables completed (missing S-IDs and lines added; Professor's check now reports none in Ch. 3); Ch. 2: a references-form statement in 02.00 (short-name list = reference table) | the book-wide remainder is outside this part |
| other PS8 | — | none lands in Ch. 2–3 | PS8-9 restated IDs already done (e5a9435bf) |

**F6 and the P2-1 open item:** no P2 row decides F6. Governance status still mixes standing and lifecycle, so under §3.1's reading (B) it has **no bearer yet**. *Conditional:* if F6 splits it (PM-1), standing → the claim (Ch. 1 §1.2) and lifecycle → the carrier (a document lifecycle) would give each part a bearer. The P2-1 open item stays open.

**Out of scope, noticed:** §6.6 C3's "linear authority hierarchy" should be re-read against §2.5's new scope (in the shared file).

## 4. P3, Chapters 4 and 5 (item 4b)

*Applied 2026-10-20 to the working book (`chapter_04/*`, `chapter_05/*` only). Settlement line numbers referred to the frozen copy; every site was located in the working text. Shared-file edits: `part-P3-shared.md`. Placeholder ID `CON-0187` in §5.3 is to be replaced by the parent with the next free CON-ID.*

### Material rows

**P3-1 (plant-only; excluded).** Working §5.7 C3 reads: "It is recognized only when it is recorded, and inferring one is not evidence", with S0080 L47 ("do not infer"). The working text already agrees with S0080 L47; index 05.00's §5.7 entry says "a supersession recognized only when recorded". **No change.**

**P3-2 (Zero homonym inside Z3).**
- Applied: yes. §5.4.2: Z3 split into the operator (a set, S0896 L1237–1262), Zero-all (Δ = ∅, S2377 L356–384; RESEARCH-PURPOSE's sense) and Zero-blocking (S0896 L800–808); Zero-all ⇒ Zero-blocking, not conversely. Z4 re-tested (Ded): a state of one proposition's evidence, at most a requirement's Conflicted status, so a separate sense (settles I-19 (5) for the text). §5.4 C8: "keeps" corrected to "changed, not kept" (S2377 moves the predicate from blocking to all requirements). Also applies **I-08** (§5.4 part) and N3-02.
- R3 (§5.4, §5.4 C2; ~21 citations): §0.9 Q3, §2.8 E9/E10, §6.6 L65/L101, §9.1 L29, §9.4 L58, §9.7 L26/L177, map F15, L266, L288, L296, L306, L309, outline L21, L37, L65 — **unaffected**. Map §5a "Zero names three concepts" and map L275 ("a fourth sense") — **changed** (shared). Outline L77 "Zero(EC) reachable" — **changed** (I-08; shared). CON-0080 meaning/basis — **changed** (shared).
- Resolved when "each sense has its own term": **holds in Ch. 5**; the map and outline need the shared edits.

**P3-3 (the operator's must-do half).**
- Applied: yes. New §5.4 C9: three trivial models (empty contract; no blocking requirement; always-∅ operator); the source's no-invented-gaps (S0896 L1021–1050) and reproducibility/replay (L981–1000); must-do half: ContractDerivation, an owner (an act) for R and criticality, empty contract reported as "no contract", replay with model versions. New TODO T5-08. Owner table in 05.00 (N3-20).
- R3: as P3-2; CON-0080 basis needs "must-do half: §5.4 C9" (shared).
- Resolved when "the trivial models fail stated requirements": **holds as requirements stated**; the owner of R and criticality stays open. **Open item:** role Professor; reason: no source read in order names ContractDerivation's owner; next action: T5-08 when S0896/S1296 are read in order.

**P3-4 (independence as a stipulation).**
- Applied: yes. §4.1.2: relabelled "a definition: a stipulation of this book"; recurrence vs independent support separated; in-estate consequence (Ded: no in-estate pair is independent; C5 holds trivially; the promotion condition cannot be met in the estate); graded/property-relative notion named, question referred to **DB-2** (with P5-3). C2's rule relabelled a stipulation (N3-24). T4-03 updated.
- R3 (§4.1, §4.1 C5): §8.6 L10, L26, L70, L114 — unaffected (P5-3 territory); §6.4 L20, L63; §8.2 L23; §8.3 L35, L45; §0.2 L55; T1-19; CON-0128 — unaffected. Map §4 "recurrence must be independent …" — **changed** (shared: add the in-estate consequence). CON-0069/CON-0070 rows — unaffected in status.
- Resolved when "T4-03 has a criterion that can be met": **does not hold.** **Open item:** role Principal (DB-2); reason: under the stipulated definition no in-estate instance can be independent; a satisfiable criterion needs a graded notion, a Principal decision; next action: DB-2.

**P3-5 (no kernel/application mark in Ch. 4–5).** Applied: **no**. **Open item:** role Professor; reason: depends on the register column "kernel / application / observation" (I-12, shared) and P1-6's classification method; next action: fill the column for Ch. 4–5 rows once it exists. Partial: the owner table in 05.00 pairs Ch. 5 invariants with owners (N3-20).

**P3-6 ("decision" vs eligibility).**
- Applied: yes. §5.4 C2 retitled "Execution eligibility needs criticality as well as status"; "exactly when" → "only if"; "subject to authorization" (S0896 L800–808); "the decision itself … is an act (Ch. 1 A1)"; C6, C7, C8 "proceed"/"decision" → eligibility. Index 05.00 §5.4 entry restated. Also applies N3-21, I-15 (§5.4 C2 part).
- R3 (§5.4 C2): map §5a "Res: the decision needs (status, criticality) pairs …" — **changed** (shared). Others unaffected (see P3-2).
- Resolved when "C2 and map 5a no longer say 'decision'": **holds for C2; map 5a needs the shared edit.**

**P3-7 (§5.5 C1 overclaim).**
- Applied: yes. §5.5 C1: "The field named the authorizer, so A1 can be shown" dropped; now "the record shows neither the executor nor the authorization"; `actor` is a git stamp; attribution only in free text (S0044 L42); consistent with C2. Also applies N3-12 (§5.5 part: authorizer-was-a-person relabelled Int).
- R3 (§5.5 C1, CON-0109, CON-0165): map L183 ("executor ≠ authorizer …") — unaffected; F13a — unaffected; outline L13, L29 — unaffected; CON-0165 meaning — unaffected (it does not claim A1 can be shown); §6.4 L35, §6.5 L15/L67/L69, §8.5 L30/L63, §8.9 L81 — unaffected.
- Resolved when "C1 consistent with C2": **holds. Closed.**

**P3-8 (CON-0079 verdict).**
- Applied: yes. §5.3.2: orthogonality of the answers (Ded: every yes/no combination possible); order of asking ≠ dependence; activation a conjunction (S0029 L16, quoted). Tag → `[CON-0079 · validated]`.
- R3 (§5.3): §1.8 L27, §3.8 L93, §6.2 L45, T6-04, CON-0076 — unaffected. Map §5 "three **sequential** questions, not orthogonal `[CON-0079 ✗]`" — **changed** (shared). CON-0079 row — **changed** (shared).
- Resolved when "verdict rests on the register meaning": **holds once the register row is updated** (shared).

**P3-9 (VALIDATED = same-process review).** Applied: **no** (register legend and basis cells are shared). Shared edit given. Resolved when "legend added": pending the parent.

**P3-10 (laws of change).**
- Applied: in part, as directed. Index 04.00 now states where the kinds of change live (Ch. 1 §1.6 table: CON-0019, CON-0020/CON-0163; §1.14: derivation vs attestation, retirement; CON-0021/0022 registered only), that the home is undecided (TARGET-TOC, M13, the Principal's), and that Ch. 1 §1.6 is the home meanwhile and Ch. 4 no longer defers back. Index 04.00 §4.3 entry corrected (N3-10).
- The other half of the loop (§1.6 → Ch. 4 pointers, if any) is outside my files: shared.
- Resolved when "one home for the taxonomy": **holds provisionally** (§1.6 named as the home); **open item:** role Principal; reason: Ch. 4's title and the final home are TARGET-TOC decisions (M13); next action: TARGET-TOC decision. CON-0021/0022 book_location: shared (I-14).

### Normal, integration and step-8 rows

| ID | Applied? | What changed (file:§) | Note |
|---|---|---|---|
| N3-01 | yes | 05.03 §5.3.2: CON-0078 split; closed list + cap INVALID; principle `[CON-0187 · provisional]`; volume gate may fit "sufficient dataset" | new register row (shared) |
| N3-02 | yes | 05.04 C8 table and lead line: both sources pair a yes/no predicate with a status/type (S0896 L760–778) | — |
| N3-03 | yes | 05.04 C8 item 3: "S2377 leaves open whether this fits its own order … its own case is a change of R" | CON-0101 history (shared) |
| N3-04 | yes | 05.01: rule restated "… or to any open question the decision rests on" (D-9a upstream of D-9) | CON-0050 meaning (shared) |
| N3-05 | yes | 04.02 C3: minimum time window, not sample size | — |
| N3-06 | yes | 04.01 C3: restricted to confirmatory counts; census counts excepted (15/19, §5.7 C1) | map L66 (shared) |
| N3-07 | yes | 05.03 C3 table, C4: Track A undetermined (an authority's acceptance would be a permitted re-ruling; acceptor not named, L43) | — |
| N3-08 | yes | 04.01 C5: volume watch measures recording volume, arms the machinery (S0027 L199; S0029 L27); "conflicts with L140" deleted | S0027 L196–201 and S0029 L27 checked in source |
| N3-09 | yes | 04.01 C5: "one agent, one instruction set" labelled Int (S0027 names no actor) | — |
| N3-10 | yes in part | 04.00 §4.3 entry; 05.00 §5.4 entry; 05.00 §5.7 already P3-1's rule | map CON-0076, Zero senses; CON-0080 criticality + "dependencies not built"; CON-0081 ten → eleven (shared) |
| N3-11 | yes | 04.03 C6: "stated in advance", qualified: no threshold (as §4.2 C5) | — |
| N3-12 | yes | 04.03 C5b; 05.05 C1: "authorizer was a person" → Int (S0044 L42) | — |
| N3-13 | yes | 05.04 C1: transfer check required; use provisional until done | — |
| N3-14 | yes | 05.04 C7: preservation condition redundant under fixed R; needed only when R changes (THM-G4) | — |
| N3-15 | yes | 05.06 C6 heading and bullet: "compatible … a checkability burden remains" | no register row carries this tension (searched); no register change |
| N3-16 | yes | 05.02: "a weak tension … 'the gap' at L24 is the overall verdict" | — |
| N3-17 | yes | 05.00: index rows in numeric order (§5.3 before §5.4) | — |
| N3-18 | yes | 05.04 C8 THM-G3 row: exception for a freshness-only requirement | — |
| N3-19 | yes | settled with N3-23, P3-2, P3-6 | — |
| N3-20 | yes | 05.00: "Invariants in this chapter and their owners" table (8 rows) | — |
| N3-21 | yes | settled under P3-6 | — |
| N3-22 | yes | 04.01 C3: "membership test applied after the review's caution (S0027 L173–175)" | source checked |
| N3-23 | yes | 05.00: homonym table (gap, evidence gap, Zero, decision, closure, independent, instance, authority, requirement); 05.02: §5.2 gap mapped onto §5.4's | — |
| N3-24 | yes | 04.01 definition and C2 rule → stipulation; 04.02 C1 → Src; 05.03 C1 → Int (analogy); 05.07 C1 duty half → Int (the book's norm) | — |
| N3-25 | yes | new TODO rows T4-04 (OE-KOS-4, MO-2), T5-08 (operator must-do, P3-3), T5-09 (S2377 items) | laws of change already M13 |
| I-08 | yes (§5.4) | see P3-2 | outline L76/L77 "Zero(EC) reachable" → qualify (shared) |
| I-14 | no (shared) | — | CON-0021/0022 book_location (shared); no text home exists in Ch. 1 for them yet |
| I-15 | yes (§5.4 C2, §5.2) | see P3-6, N3-23 | the §2.8/§2.11 "adopted" part is P2's |
| I-17 | yes | 05.01 table row and Package 10: "no home yet; Ch. 7 is planned and not written"; T5-01 home §5.1 | CHAPTER-PLAN stale marks (shared, not mine) |
| PS8-6 (Ch. 4–5) | yes | references tables completed: 04.01 (S0027 L173–175; S0029 L27), 04.03 (S0044 lines), 05.01 (S0025), 05.02 (S0025, S0029), 05.04 (S0896 L671–808, L981–1050; S2377 lines; S0913 row), 05.06 (S0061 L51; S0164 row), 05.07 (S0058, S0061 rows) | remaining checker hits (05.01 S0023/S0019, 05.02 S0024, 05.05 S0055) are parser artifacts: two S-IDs on one row; the lines are present |
| PS8-9 (§5.5 C1, §5.6 C3) | no change | already retagged (CON-0165, CON-0168) | — |
| PS8-15 (§5.7 C4) | no change | changed theory holds (both reviewers) | — |
| Changed theory P3 | no change | "the order itself is not stable" kept as normal | — |

### Observed, out of scope (not changed)
Review-history notes remain in v1.3 sections of Ch. 4–5 that DB-8 did not catch: §4.2.4 "(review C8, finding 11)"; §4.3 C5 "*This answers §4.2's open question; §4.2 itself is left unchanged until review.*"; §5.5 C2 "(review C8, finding 5)"; §5.5 C6 heading "added in window C11; reviewed in C11/T3"; §5.6 C6 heading "added in Trial T3; reviewed in C11/T3"; §5.5 references row "(row added in window C11 …)"; §5.7 references "(C8–C10 added after review C14)". A DB-8 follow-up sweep with change-log entries is needed.

### Counts
Material (10): applied 6 (P3-2, P3-3, P3-4, P3-6, P3-7, P3-8), in part 1 (P3-10), not applied/shared 2 (P3-5, P3-9), excluded 1 (P3-1, no change). N-rows (25): applied 24, settled-under 1 counted within. I-rows: applied 3 (I-08 §5.4, I-15 part, I-17), shared 1 (I-14). PS8: 1 applied (PS8-6 Ch. 4–5), 2 no change. Open items: P3-3 (owner), P3-4 (DB-2), P3-5, P3-9, P3-10 (TARGET-TOC).

## 5. P4, Chapters 6 and 9 (item 4b)

*Applied 2026-10-20 to the working book (chapter_06/*, chapter_09/*). Not committed. P4-1, P4-4, N4-14 and I-13 were applied in 81cfcd1eb. N4-01 is plant-only and excluded. Settlement line numbers refer to the frozen copy; sites were located in the working book.*

### P4-2: CON-0134 "never stored"
- **Applied: yes.** §6.5 C4: heading restated ("one producer, the fold; a stored derived value has no standing"), tag `[CON-0134 · invalid as stated]` with restated form CON-0141; new bullet "What does not hold" (no key whitelist [S0073 L77]; 20 stored keys inert [S0073 L84]; would be persisted, not refused [S0078 L142]; non-consultation [S0078 L144]; T-1b [S0073 L119]); the census labelled dated (2026-08-17); T-1 split into T-1a/b/c [S0073 L118–120]. Sources read for this record (S0073, S0078 on the allowed list).
- **R3** (§6.5 C4, CON-0134; 4 claim, 13 concept, 52 section-only):

| Citation | Outcome |
|---|---|
| §8.8 L14 ("§6.5 C4, a derived projection is never stored") | **changed needed (Ch. 8, shared file)** |
| §8.8 L31, L40, L43 | unaffected (CON-0141 is the restated form; "the fold is fixed" premise kept) |
| §9.7 L26 (existing homes), L106 (table row) | changed (restated form; "never stored" not required) |
| §9.8 L18 (existing homes), L65 ("Necessity rests on CON-0134") | changed: rests on T-1b, a design rule (I-06) |
| §6.7 L17, L119 | L17 changed; L119 unaffected (a list) |
| CON-0141, CON-0177 rows; map L205 | shared file |
| section-only §6.5 citations (Ch. 1, 5, 6, 8, 9; outline) | unaffected (they cite §6.5 for C0–C2, C8, C9 or as a home) |

- **Resolved when** "no derivation cites 'never stored' as structural": **holds in Ch. 6/9**; §8.8 L14 and the register/map remain (shared file). Open item: role Professor; reason: outside this part's files; next action: apply the shared edits.

### P4-3: §6.5 C5's falsified premise
- **Applied: yes.** §6.5 C5: premise marked superseded (F-1, S0072 L32; S0078 L86–98); START still has zero grant references [S0078 L98]; the read-dependency argument (C-1, S0072 L33) strengthens one root [S0078 L100; S0073 L170].
- **R3** (§6.5 C5; grep "never consults the session"): §5.5 L56 unaffected (verdict still subject-blind). §9.6 L54 already states the cross-part read: consistent.
- **Resolved when** "C5 consistent with §9.6.2": **holds. Closed.**

### P4-5: §9.7.5 condition 4 one-directional
- **Applied: yes.** §9.7.5 condition 4: two-sided conformance on T, F and U (soundness and completeness); constant T named; §9.7.7 result 5 aligned. Check: `tmp/tools/ce_conformance_check.py`, run `tmp/tools/ce_conformance_check-run-20261020.txt` (old condition admits constant T; new condition excludes constant T, U and F; all assertions passed).
- **R3** (§9.7.5, condition 4): TODO-TRIAGE L31–32, §1.14 L18, L103 unaffected; map L238 and CON-0173 meaning need "on each of T, F and U" (shared file).
- **Resolved when** "constant T and constant U both fail": **holds. Closed.**

### P4-6: K4 partly circular
- **Applied: yes.** §9.6.4: new premise (K2) identity mechanism (content-derived vs assigned; an assigned global identity is K4's own example); K4 sub-fork K4-p / K4-d (DB-3); step 1 needs K2 = content-derived; step 2 carries U's detection clause; step 3 split into prevention and detection cases. §9.6.6a item 3 and §9.6.8 aligned.
- **R3** (§9.6.4, §9.6.6a, K4, CON-0137, CON-0171): §9.7 L84, L132, L151 unaffected (F26(a) is a prevention case); §6.6 L26, L47, §8.9 L16, L68 unaffected (K3); map F22 row and CON-0171's "global act identity" need K2 (shared file).
- **Resolved when** "K4 restated with identity and the sub-fork": **holds. Closed.** The Principal's question is DB-3 (filed).

### P4-7: necessity by definition; reductions conditional on CE (partly plant)
- **Plant part excluded:** the §9.8 Lemma D condition (base text already carries "(premise)" and "conditional on CE's repair": verified in §9.8.6 result 1).
- **Applied (real residue):** §9.6.3 "What kind of necessity" (definitional) and pointer to T9-06; §9.7.3 necessity labelled definitional; §9.8.6 result 2: the overlap with authority holds by definition; §9.9.3: T1 is conditional on CE (premise (vi)). New TODO **T9-06** (validate the relocation test on a known case). Attribute signature (T9-02) unchanged: it is M5/F13 work.
- **Resolved when** "conditions carried in the register": **not yet** — CON-0160 needs "conditional on CE's repair" (shared file). Open: role Professor; reason: register outside this part; next action: shared edit. T9-06 open (role Professor; next action: pick a capability with an independently settled placement).

### P4-8: RL applied selectively
- **Applied: yes**, option 1 (count the HANDOFFs as RL breaches), with the scoped RL stated as Hyp. §6.5 C2 bullet restated (not an I-5 breach; an RL breach as RL is stated; RL vs DP-3 conflict); §9.6.5 RL row and result; §9.6.7 "Applied consistently". DP-3 verified [S0078 L201–204; S0071 L253–260].
- **R3:** §1.14 L310 (Ch. 1: "lanes recorded their own STARTs") unaffected but could add HANDOFFs (shared/propagation note); §5.7 L74, §8.9 L72 unaffected.
- **Resolved when** "§6.5, §9.6.5, §9.6.7 consistent with §1.14.3": **holds. Closed.**

### P4-9: no kernel/application mark in Ch. 6
- **Applied: yes**, as a table in `06.00-index.md` (every Ch. 6 concept: kernel / application / platform engineering / parked, with reason; no boundary test claimed).
- **Resolved when** "marks in place": **holds for the chapter**; the register column (I-12) is the parent's/shared decision.

### P4-10 / I-11: §9.6 step 6 withdrawn conclusion
- **Applied: yes.** §9.6.4 step 6 restated (common, versioned rule over a shared or mergeable record; kernel home does not follow, §9.7.9; not a stronger candidate); first form recorded in §9.6.9.
- **R3:** BOOK-OUTLINE L58 and map F22 row still say "the stronger kernel candidate" / "kernel-defined function" (shared file); §9.6.6a item 2 already consistent.
- **Resolved when** "§9.6 L127, the map and §9.7 agree": **holds in §9.6/§9.7**; map and outline pending (shared file).

### Normal, integration and step-8 rows

| ID | Applied? | What changed (file:§) | Note |
|---|---|---|---|
| N4-01 | excluded | — | plant only |
| N4-02 | yes | 09.06 §9.6.6 plane-ward evidence | T-1c (writer obligation, S0073 L77, L120) and 23/30 lost writes (S0234 L282) added; settlement named §9.5 C3, but the sentence is only in §9.6.6 |
| N4-03 | yes | 06.03 C5 | (B) ⇒ (A) scoped to adopting projects of another domain |
| N4-04 | yes | 06.01 C1 remedy 1 | declares the split; well defined, not validated |
| N4-05 | yes | 06.06 C1 | standing vs currency harm; copied scripts (S0066 L496–509) |
| N4-06 | yes | 06.06 C4 | "not identical"; normative order S0066 L338–358, L417–433 |
| N4-07 | yes | 09.03 C4 | S2834 L2116–2122 echoes the programme; CON-0111 a definitional non-identity only (register basis: shared) |
| N4-08 | yes | 09.09 §9.9.3 | scoped to step 025y (S1500 L273–286); S0973 L542–598 noted |
| N4-09 | yes | 06.05 C2 | 5 types listed by S0067, 8 declared [S0071 L253–260] |
| N4-10 | yes | 06.05 C2; 09.06 §9.6.5 RL row | no "37 of 37" string in the working text; the generalization was the unscoped referent claim; scoped to 6 cases (S0073 L88, L155) |
| N4-11 | yes, under P4-10 | — | — |
| N4-12 | yes | 06.02 C1 | rival: within-rater inconsistency; S0034 L20–33 |
| N4-13 | yes | 06.05 §6.5.2 | operational reading (owner = holder of the amendment act); S0091 L181 checked: I-4 "registry ≠ authority" owner "BC-7/BC-5"; whether it is S0067's I-4 left open. Also removed a duplicated CON-0132 tag |
| N4-14 | applied with P4-4 | — | — |
| N4-15 | no (shared) | — | map note linking F31, F34, §9.4 C6, §1.14.1 root: shared file |
| N4-16 | yes, in part | 09.09 §9.9.8 | comparison table vs S0973 L1747–1773; S0167, S0357, S0630 when read in order (open) |
| N4-17 | yes, in part | 09.09 §9.9.2 | factive/non-factive (S2834 L2039–2081); "knowledge item" qualified; glossary entry: shared/open (no glossary file exists) |
| I-06 | yes | 09.08 §9.8.4 L65; 09.07 table | re-anchored to T-1b; rests on a design rule |
| I-11 | yes (text) | 09.06 step 6 | map position line and counts: shared |
| I-13 | applied with P4-1 | — | — |
| I-19 (3) | parent (81cfcd1eb) | — | — |
| I-19 (8) | yes (text) | 06.05 C4 tag | register CON-0134 → INVALID: shared |
| PS8-6 | yes for Ch. 6/9 | references tables of 06.02–06.07, 09.03–09.09 | 37 (section, source) gaps closed (lines appended or rows added); re-check reports none for Ch. 6/9 |
| PS8-15 | yes (P4 part) | — | §9.8 M5 and result 6 (TT4 changes) re-checked against S0129 L4, L97, L102 and S0095 L162, L166: hold. This also corrects the settlement's TT4 check, which looked at the copy's renumbered result 6 |
| DB-8 leftovers | yes | 06.05 (L14, L101, refs ×3), 06.03 C5 tag, 09.04 refs ×2, 09.06 (×3), 09.08 refs | 12 notes removed, each logged verbatim in 06.99 / 09.99 |

**Not done (out of scope, noted):** §9.1 C4's "*Correction recorded*" note (§9.1 belongs to P4-1's edit; it has no WDNH section). Map F26 row "scoped after review C15" (shared, DB-8 leftover).

**Quote check** (`tmp/tools/corpus_quote_check.py`, after the edits): 06.05 45/0, 06.06 22/0, 06.02 8/0, 09.06 14/0, 09.09 13/0, 09.03 8/0 (found / not found).

## 6. P5, Chapter 8 (item 4b)

*2026-10-20. Files edited: `chapter_08/08.00-index.md`, `08.01`, `08.02`, `08.05`, `08.06`, `08.07`, `08.08`, `08.09`, `08.10`, `08.11`, `08.99-change-log.md`, `TODO.md`. Shared-file and other-chapter edits: `part-P5-shared.md`. Corpus lines re-read for this part (hash matching the allowed list): S0025 L35, L97, L162; S0055 L7, L55–73; S0060 L11–17; S0090 L87–90; S0092 L35; S0094 L19, L29–41; S0095 L99, L219–223; S0097 L60–68; S0100 L43–49, L97, L134–138. S0098's first commit date (2026-08-22) from git.*

### P5-1: §8.11 C1, "self-declaration was false" residue

- **Applied: yes, the real part.** The L32 sentence of the copy ("So the correcting session's self-declaration was false …") is plant-only: **the working §8.11 C1 does not contain it**; it read "So both self-declarations were true." That sentence and the plain language ("Every self-declared identifier in the episode was true") still over-claimed, since the erratum's declaration is unchecked (the real half of P5-1 = PS8-2).
- **Text:** plain language, C1 ("both checked self-declarations were true; the erratum's is unchecked"; no identifier shown false; what was false was the correction's verdict on the refusal's claim), §8.11.4 (new "Corrected at S0100" block). "Resume" reading marked a candidate (B's point).
- **Register / map:** CON-0180, CON-0157 history; map L211 (shared file). These are the "four places" with the plain language.
- **R3** (§8.11 C1, CON-0157, CON-0180):

| Citation | Outcome |
|---|---|
| §1.14 L310 (1.14.3-2 further evidence: "a continuation … attributed an act … whether that breached the bar is open") | unaffected |
| §1.14 L544 (references) | unaffected |
| §9.9 L15, L18 (home lists citing §8.11) | unaffected (uses only "self-declared, not attestable") |
| CON-0157, CON-0180 rows; map L211 | changed (shared file) |

- **Resolved when** "all four places agree": **holds in Ch. 8** (plain language, C1, §8.11.4); holds book-wide once the shared-file edits to CON-0180 and map L209/L211 are applied. **Open item:** role Professor (parent); reason: register and map are shared files; next action: apply `part-P5-shared.md` rows P5-1.

### P5-2: E(o), three relations under one term (= PS8-3)

- **Applied: yes.**
- **Text:** §8.9 C2: one definition, E_ρ(o) relative to an eligibility rule ρ; grounds (g1) authorship = production of o or the protected work (not assurance) and (g2) material participation in producing the evidence verified, incl. findings o takes up (CON-0158, Hyp); fixed and closed population defined (N5-16); Ded restated for a fixed population and fixed ρ (non-increasing; drops under g1 and g2; S0100 L49 now inside the result); "verifying did not make them so" scoped to SB-1; F35 named. §8.11 C7: "an instance of §8.9 C2's ground (g2), not of authorship", carrying CON-0158's status. §8.9.4 and §8.11.4 record the withdrawn "drops only when" and "fits". TODO T8-03.
- **Register / map:** CON-0151 meaning (the same definition), F35 row, map L220 (shared file).
- **R3** (§8.9 C2, CON-0151, §8.11 C7):

| Citation | Outcome |
|---|---|
| §8.10 head table ("§8.9 C2, eligibility and fork F32") | unaffected |
| §8.11 head table, C2 Int, C7 | changed (C7) / unaffected (C2) |
| map F32 (L148) | unaffected; F35 added beside it |
| map L220 | changed (shared file) |
| CON-0151 row | changed (shared file) |

- **Resolved when** "one wording in three places": **holds in Ch. 8** (§8.9 C2 defines; §8.11 C7 uses ground g2 of it); holds in the register once CON-0151's meaning is replaced with the same definition (shared file).

### P5-3: information lineage degenerate; "independent" senses; single operator

- **Applied: yes** (with N5-01, N5-13, N5-17, I-03's §8.6 part).
- **Text:** §8.6.2: information lineage = information read other than the object under check; two factors added (human operator as a channel; inherited standing instructions); four senses of "independent" named (process; no shared generator; factor by factor; organisational); the single-operator consequence stated with DB-2; the factor → blind-set bridge stated as Hyp (TODO T8-02). C1 "seven" → "nine" (also §8.6 C5, §8.8 C4 L64, §8.9 head table, §8.10 C7). §8.6.5 records the withdrawn forms. §8.11 C2 Int (PS8-12) now names the definition its argument assumes.
- **Not in my files:** §1.14.3-3 reconciliation ("fresh AI sessions independent as process") → shared file §4.
- **R3** (§8.6, CON-0125, CON-0126, CON-0127): 

| Citation | Outcome |
|---|---|
| §4.1 L18 (graded / property-relative notion) | unaffected; consistent with sense (iii) |
| §5.5 L56, L64, L83; §5.7 L11, L36–39, L112–113 | unaffected (C3 unchanged) |
| §6.6 L66, L70 ("another agent may share model, method or context", §8.6 C1) | unaffected |
| §9.4 L15, L21 | unaffected |
| §9.6 L162; §9.7 L107, L177 | unaffected |
| §9.9 L75–76 ("refuted, not established (§8.6 C1)") | **changed: stale citation, should be §8.6 C3** (shared file; also the parent's new result-4 wording) |
| §9.9 L57, L60, L80, L105, L133–134, L15, L18 | unaffected |
| §1.14.3-3 L314 | **changed** (shared file) |
| CON-0125, -0126, -0127 rows; map L202; outline L40 | changed (shared file) |

- **Resolved when** "definitions fixed; bridge stated": **holds in Ch. 8.** Book-wide, §1.14.3-3 must be reconciled (shared file §4). **Open item:** role Ch. 1 owner (parent/P1 fork); reason: Ch. 1 file; next action: apply shared row P5-3. The Principal's question is filed as **DB-2**.

### P5-4: Correction #3 as a "breach" (with I-10)

- **Applied: yes, in Ch. 8.**
- **Text:** §8.11 C6: the case restated under F25: S0097's "MAY NOT … create the replacement architecture" binds the verification assignment (S0097 L63–67, read); whether the session acted under it is not shown; S0100 L135 cited for what it asserts, the producer mismatch, separately (I-10). §8.11.4 records the withdrawn "against the boundary just quoted".
- **R3** (§8.11 C6, CON-0159, CON-0181):

| Citation | Outcome |
|---|---|
| §1.14 L310 (1.14.3-2: "the estate breaches them in practice … produced the correction answering them (§8.11 C6)") | **changed** (shared file §4) |
| §1.14 L401 (1.14.5-1, analyses) | unaffected by P5-4; PS8-8 is the Ch. 1 fork's |
| map L211 | changed (shared file) |

- **Resolved when** "no 'breach' wording remains": **holds in Ch. 8**; open in §1.14.3-2 (shared file). **Open item:** role Ch. 1 owner; next action: apply shared row P5-4.

### P5-5: Ch. 8's scope; the unstated law; no kernel marks (with I-16's producer ≠ verifier family)

- **Applied: yes.**
- **Text:** §8.10 C7: the candidate law stated once (Hyp): "a party constrained by a control may not be the sole authority on its own compliance with it", as the producer ≠ verifier family (§1.14.3), with its five instances listed (§8.6 C3; §8.11 C2, C3 ×2; S0096 L51), neither derived from nor deriving them; DB-4 named. §8.11 C3 adds N5-22's instance and points to §8.10 C7. Index: "What this chapter is, and what it is not" (DB-4); a kernel / application / observation mark table (Int, provisional); an invariant owner/must-do table (N5-10).
- **R3:** the law is new; §1.14.3 (producer ≠ verifier) is its family home and is cited, not changed. Map L215 gains a pointer (shared file).
- **Resolved when** "law stated; chapter scope decided": **law stated: holds. Chapter scope: open**, the Principal's (DB-4). **Open item:** role Principal; reason: reserved decision; next action: DB-4.

### Normal, integration and step-8 rows

| ID | Applied? | What changed (file:§) | Note |
|---|---|---|---|
| N5-01 | settled under P5-3 | 08.06 §8.6.2 | — |
| N5-02 | yes | 08.06 C2 proof ("contains"; per-defect note) | register CON-0126 and outline L40: shared |
| N5-03 | yes | 08.10 C10 last bullet: "a source slip" withdrawn; seven = roles (S0094 L19, L29–41 re-read) | — |
| N5-04 | yes | 08.10 lineage line: labelled Int, with what S0094 says it of | — |
| N5-05 | yes, merged with PS8-14 | 08.11 C1 "How it was settled": one lineage, mechanical; §8.10 C5's assumptions carried | — |
| N5-06 | yes | 08.10 C2: retitled "a true finding stays true of its time"; premises (fixed s; no retroactive acts); tag → provisional | register CON-0156 and map L215: shared |
| N5-07 | yes | 08.09 C1 Scope: "per claim; completeness excluded" | CON-0150: shared |
| N5-08 | yes | 08.01: CON-0058–0060 tags → provisional (self-review only) | register, map §6: shared |
| N5-09 | partly | 08.00-index: §8.7 row moved before §8.8; §8.11 subject updated | register CON-0125/0128/0127 basis, map §6 and position line, outline §8 rows: shared |
| N5-10 | yes | 08.00-index: invariant owner / must-do table | owners as the sources show them; "none named" for §8.11 C4 |
| N5-11 | yes | 08.07 C4: precondition check largely quotes the report (S0060 L13–16 re-read: three of four rows are quotations), Int; open question on the standing of accepted verification evidence | — |
| N5-12 | yes | 08.11 C6: CON-0181 scoped to analyses read only by people; §1.14.5-1's automatic case noted | CON-0181: shared |
| N5-13 | yes | 08.06 §8.6.2 and C2: two factors; "likely reason" → Hyp with the rival | — |
| N5-14 | yes | 08.09 plain language, C3 head ("claim's scope" vs "search's scope"), C5 ("application" qualified); 08.00-index row | — |
| N5-15 | yes | 08.00-index order | — |
| N5-16 | yes | 08.09 C2: fixed and closed population defined in E_ρ(o)'s definition | — |
| N5-17 | yes | 08.06 C2: per-defect idealisation stated | — |
| N5-18 | yes | 08.08 C7: W-5 (presentation vs evidence defect, S0090 L89 re-read); S0090 added to references | — |
| N5-19 | yes | 08.10 C10: S0095 L99's reason quoted (settled before ownership) | — |
| N5-20 | yes | 08.01 lesson 3: "Ded, conditional": if the report is accurate, on F-2 (F-3 refuted, F-1 team-size dependent, T8-01); builder/designer/judge one party (L35, L97 re-read); empty register reconciled with CON-0058; model's prior knowledge named as an unrecorded channel | — |
| N5-21 | yes | 08.10 C10: S0095 L221 quoted; the disagreement with S0092 L35 stated | — |
| N5-22 | yes | 08.11 C3: "disclosure is not a ruling on its own significance" (S0100 L134, L137–138 re-read), instance of §8.10 C7 | — |
| N5-23 | yes | 08.02 C1: base-rate rival (S0038 L211–212, L229) | — |
| N5-24 | settled under N5-09 | — | — |
| N5-25 | yes | 08.11 C8: marked an inference from a filename timestamp; first commit 2026-08-22 (git, checked) | — |
| N5-26 | yes, checked | 08.05 C3: S0055's table re-read: 13 rows, 11 ✅, 2 ❌ (L61–73); "13 rows, two failures" holds; anchor added; references updated | "11 of 12" is the source's headline (L7) and section title (L57) |
| I-03 (§8.6 part) | yes | 08.06 §8.6.2 senses of "independent" | register re-grading: P3-9 (P3 fork) |
| I-10 (§8.11 C6 part) | yes | 08.11 C6 | §1.14.3-2: shared |
| I-16 (producer ≠ verifier family) | yes | 08.10 C7 (stated once) | other I-16 duplicates are not Ch. 8's |
| PS8-1 | excluded (plant-only) | — | working C1 never had the sentence |
| PS8-2 | yes | see P5-1 | — |
| PS8-3 | yes | see P5-2 | — |
| PS8-4 | yes | 08.11 C7: §1.14.1-12 citation removed; marked Int with no home | no book home states truth ≠ standing as such |
| PS8-5 | yes | 08.11 C4: M4 for the trivial solution, M5 for self-declaration | — |
| PS8-6 (Ch. 8 part) | yes | 08.11 references completed (S0097, S0098, S0100 lines); missing sources added to 08.08 (S0056), 08.09 (S0092, S0097, S0100), 08.10 (S0080, S0091, S0099, S0100; S0095 lines) | a source-level check over 08.01–08.11 now reports no cited source missing from its table; line-level completeness checked for §8.11 only. Book-wide part: not Ch. 8's |
| PS8-9 (Ch. 8 part) | no change | §8.2 C3 already tagged CON-0164; §8.7 L14 already `[CON-0123, CON-0168]` | — |
| PS8-10 (Ch. 8 part) | shared | register basis/reviewed fields | — |
| PS8-11 | yes | 08.11 C6: "settles" defined; falsifier stated | CON-0181: shared |
| PS8-12 | yes | 08.11 C2 Int: the assumed definition named | — |
| PS8-13 | no change (record only) | — | corrected through PS8-3 |
| PS8-14 | yes | see N5-05 | — |
| PS8-15 | no change (process) | — | done in the review |
| DB-8 leftovers | yes | three notes removed (08.05 references; 08.06 references; 08.10 C10 head), each in 08.99 | WDNH attributions ("refuted at review C16", "(review C11/T3, finding 2)") kept: they give the withdrawal's reason (item 1 of the instruction). §8.4 L37 "(review C9, finding 4)" kept: it is the evidence for the claim. §8.5 L3 and L29 "added after the C10 test" kept: they mark provisional status, not only history. Map F25 "(restated after review C13)": shared file |

**Counts:** material rows 5 (all applied in Ch. 8; P5-1, P5-3, P5-4 need shared-file or Ch. 1 edits to close book-wide; P5-5's chapter-scope half is the Principal's, DB-4). Normal rows 26: 22 applied, 3 settled under others (N5-01, N5-24, N5-09's Ch. 8 part applied, rest shared), 1 checked and confirmed with an anchor (N5-26). Integration: 3 applied in Ch. 8. Step-8: 9 applied, 1 excluded (PS8-1, plant-only), 4 no change (PS8-9 Ch. 8 part, PS8-13, PS8-15) or shared (PS8-10).

## 7. Shared files and cross-chapter propagation (item 4b)

| Part | Row ID | Target | Applied? | Note |
|---|---|---|---|---|
| P1 | P1-4 | register CON-0016 | yes | VALIDATED→PROVISIONAL |
| P1 | P1-1 | register new CON-0186; §1.8 tag | yes | book_location chapter_01/01.08-what-knowledgeos-is-a-generator.md; tag added to §1.8's identity line |
| P1 | I-18 | register CON-0020 history | yes | |
| P1 | P1-2/I-04 | map L29 (A1) | yes | named readings, DB-1 |
| P1 | P1-4 | map "Authority" senses | yes | six senses |
| P1 | P1-5 | map F11 | yes | four ways the line is drawn |
| P1 | P1-2 | map F12 | yes | rival: standing by practice |
| P1 | P1-1 | map/outline "generator" lines | no change | no such line in map or outline |
| P1 | P1-2 | outline Authority row | yes | |
| P1 | I-04 | §2.8 L31 | yes | A1-recognized named |
| P1 | I-04 | §9.8 M1 | yes | A1-as-fact (Ch. 1 §1.6) |
| P1 | I-09 | §2.1 L11 | yes | currency sense |
| P1 | N1-03 | §3.6 I-n(RO) | yes | label note at the invariants list |
| P1 | N1-15 | ⊥ book-wide | open | needs the glossary (M10) |
| P1 | P1-6 | T9-01 pointer | yes | |
| P2 | P2-5 | CON-0005 | yes | →PROVISIONAL |
| P2 | P2-4/N2-17 | CON-0006; map L16 | yes | |
| P2 | P2-12 | CON-0008 | yes | |
| P2 | P2-9 | CON-0011, CON-0098; map F8, L312 | yes | |
| P2 | P2-8 | CON-0038 | yes | →PROVISIONAL |
| P2 | P2-6 | CON-0064; map; outline | yes | |
| P2 | N2-13 | CON-0097 term | yes | |
| P2 | P2-7 | §6.6 C3 | yes | enacted precedence vs author rank; "not shown in S0066" |
| P2 | P2-10/N2-01 | register column | open | see below |
| P3 | N3-01 | new CON-0187; CON-0078; placeholder in 05.03; map | yes | CON-NEW-P3a → CON-0187 |
| P3 | P3-8 | CON-0079; map | yes | →VALIDATED |
| P3 | P3-2/P3-3/N3-10 | CON-0080, CON-0081; map (Zero line, L275, must-do line) | yes | |
| P3 | N3-04 | CON-0050; map | yes | |
| P3 | N3-03 | CON-0101 history | yes | meaning/basis had no "contradicts" wording |
| P3 | N3-06 | CON-0070; map | yes | |
| P3 | N3-10 | map CON-0076 ~ | yes | register status PROVISIONAL confirmed |
| P3 | P3-4 | map recurrence line | yes | DB-2 |
| P3 | P3-6 | map Res line | yes | |
| P3 | I-14 | CON-0020/0021/0022 book_location | yes | CON-0020 → 01.06 (it is tagged there); 0021/0022 "none in the text yet; home undecided" (no path, so R1 does not flag) |
| P3 | P3-9 | map legend | yes | stated once in the legend; rows not touched |
| P3 | I-08 | outline L77 | yes | |
| P3 | I-17 | CHAPTER-PLAN | yes | stale note above the table |
| P3 | DB-8 | Ch. 4–5 notes (7) | yes | logged in 04.99 / 05.99; "reviewed in C11/T3" kept as status |
| P3 | P3-5 | register column | open | see below |
| P4 | P4-2/I-19(8) | CON-0134, CON-0141; map L205; §8.8 L14 | yes | |
| P4 | I-06 | CON-0177 | yes | |
| P4 | P4-5 | CON-0173; map L238 | yes | |
| P4 | P4-6 | CON-0171 | yes | |
| P4 | P4-7 | CON-0160 | yes | |
| P4 | N4-07, N4-13 | CON-0111, CON-0132 | yes | |
| P4 | P4-10/I-11 | map F22; outline L58 (two strings) | yes | |
| P4 | N4-15 | map note after F37 | yes | |
| P4 | I-11 | map position line, counts, chapters line | yes | close_source.py --position: S0100, 99/4/30; register 187 (35 V, 67 P, 85 I) |
| P4 | DB-8 | map F26, F25 | yes | |
| P4 | P4-8 | §1.14 L310 | yes | HANDOFFs added |
| P4 | N4-17 | glossary | open | no glossary (M10) |
| P5 | N5-08 | CON-0058/0059/0060; map | yes | →PROVISIONAL |
| P5 | P5-3/N5-13 | CON-0125; map L202; §1.14 L314 | yes | |
| P5 | N5-02 | CON-0126; outline L40 | yes | |
| P5 | N5-09 | CON-0127, CON-0128; outline L81 | yes | |
| P5 | N5-07, N5-06, N5-12 | CON-0150, CON-0156, CON-0181; map | yes | |
| P5 | P5-2/PS8-3 | CON-0151; map L220; F35 row | yes | F35 inserted before F36 |
| P5 | P5-1/PS8-2 | CON-0180, CON-0157; map L211 | yes | |
| P5 | P5-4/I-10 | map L211; §1.14 L310 | yes | Correction #3 not counted among the breaches |
| P5 | P5-5 | map §8.10 line | yes | |
| P5 | PS8-10 | Ch. 8 basis "not yet reviewed" | yes | CON-0121–0124, 0128 synchronised with the reviewed field |
| P5 | R3 P5-3 | §9.9 "§8.6 C1" ×5; CON-0182 | yes | verified: §8.6 C3 = "independence can be refuted but not established" |

### Allocated IDs
- CON-0186: the generative reading (a target) (P1-1)
- CON-0187: gate justified by the failure mode it prevents (restated) (N3-01)

### R1 (register_home_check.py)
163 rows with a section home; flags 4: H2 CON-0138 (09.06), CON-0140 (06.06), CON-0143 (09.07): the known item-4c defects; H4 CON-0133 (information). Register: 1 header + 187 rows, 13 fields each, IDs unique. No placeholders left (CON-NEW, <new ID>, <next free).

### Open items
- **I-12 / P2-10 / P3-5 / P1-6 / P4-9 register column "kernel / application / observation":** role Professor, decision by the Principal if the register's structure changes; reason: the 13 columns are fixed by CV-1 §6 and read by tools; next action: propose the field (or a separate marks table keyed by CON-ID) and fill it from the chapter-index tables (Ch. 1, Ch. 6, Ch. 8 indexes).
- **Glossary (N1-15, N1-28 ff., N2-12, N4-17, M10):** role Professor; reason: no glossary file exists; next action: create it under M10.
- **Item 4c:** CON-0138, CON-0140, CON-0143 tags (parent).
