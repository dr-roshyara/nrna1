*Whole-book review S0100, part P2, reviewer A (opus). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:05:20Z.*

# Review S0100, part P2 (Ch. 2 and Ch. 3): step-2 report

**Process independence.** I am an AI session that is independent of the author *as a process*, not as a person. I worked from the frozen copy only.

## 1. Assessment

- **Ch. 2** (rules, conflict, applicability) is mostly sound in its formal parts and self-critical. It has two demonstrable formal defects: the §2.3 conflict definition and the §2.8.4 containment rule.
- **Ch. 3** (meta-model) reliably reports its sources and challenges them well. However, three VALIDATED verdicts (CON-0005, CON-0038, CON-0064) do not survive checking.
- **Largest risk (critical).** The book never says what *carries* the six dimensions: the knowledge item, the claim, or the artifact. The granted RQ-002 places them on artifacts. The book places them on knowledge items, and it also says one piece of knowledge has several representations at once. Read together, the dimensional model and knowledge ≠ artifact are ill-typed.

## 2. Findings (ranked)

Key to the last column: **V** = checked against an allowed source or granted file · **U** = rests only on files I may not open · **M** = mixed. "Verified" means source-verified; "internal" means checked for internal consistency only.

| # | Sev. | Type | Location | Source line | What is wrong | Consequence | Correction | Checked |
|---|---|---|---|---|---|---|---|---|
| 1 | **critical** | theory omission | §3.1 (L5 "every piece of knowledge"), §3.4, Ch. 0 §0.3, §3.8 realization; CON-0001, -0002, -0010 | GKC L12–19 (granted): "Knowledge Instance — **an artifact** occupying a coordinate"; S0016 L121–132 classifies six **artifacts**; Ch. 1 §1.5: one knowledge item in YAML, Markdown and JSONL at once | Representation is single-valued per point. If the dimensions belong to knowledge, multi-carrier knowledge has no coordinate; if they belong to artifacts, knowledge ≠ artifact leaves knowledge without most dimensions. §3.8 also models representation a second time, as the knowledge→artifact edge. MM-4 is only the operational-state case of this. | CON-0001/0002 (VALIDATED) and CON-0010 cannot be jointly consistent as written; the attribute set (F13) has no bearer | Register a fork: give each dimension a bearer (nature and evidence status → claim; representation and operational state → carrier or realization edge; governance status → open, MM-4). Re-run S0016's six-artifact test under that assignment | verified (GKC, S0016); §1.5 rests on GA, so **M** |
| 2 | high | theory defect | §3.1 "Independence shown by use", Ded | S0016 L127–128 | The cited pair (frozen/enforced against realized/advisory) differs on **both** dimensions, so it is consistent with a function. It shows no non-dependence. The table does contain a valid pair: approved/unexercised (L129) against approved/ENFORCED (L131). "Realized" is not among D-III's 8 values. | The Ded is invalid as written, though its conclusion can be rescued | Cite L129 against L131, and note that "realized" lies outside D-III | verified, **V** |
| 3 | high | theory defect / source defect | §3.1 L34 ("the meta-model's form of §2.9"); CON-0006; §3.5 L-A "ENFORCED" | S0016 L128 `Round39-MC` "enforced (void-overriding)" means normative force; L131 deny "ENFORCED" means a runtime block; S0020 L46 "ENFORCED" means 18 lint rules; S0043 L50: every `scripts/*.php` check is ADVISORY | "Enforced" carries three meanings. S0016's orthogonality pair compares §2.9's *binding strength* with its *enforcement consequence*, so the meta-model merges what §2.9 separates. | The L34 claim is contradicted; CON-0006 is not a primitive; §2.10's "lint-enforced" is unsettled | Split CON-0006 onto §2.9's four properties (this closes T2-04); qualify every use of "enforced" | verified, **V** |
| 4 | high | theory defect | CON-0005 VALIDATED (§3.1) | S0013 L77 (H-9: **HYPOTHESIS**, cited in the register row itself); S0009 L69, L84 (candidate); S0016 L45 ("not a field anywhere"; "ten rediscoveries" with no list) | Validated on a necessity argument that rests on uncounted history, which is the ground on which CON-0009 was made INVALID. Ch. 2 TODO T2-04 lists it as "awaiting validation". The map (F13) gives 3 values in §3.1 and 4 in §1.3. | Grades within the book conflict | Downgrade to PROVISIONAL | verified, **V** |
| 5 | high | theory defect | §3.8 C3, CON-0064 VALIDATED ("impact questions … the union answers them correctly") | S0026 L103: the back-edge is "visible **only when** production and authority orders are kept apart" | The cited source says the opposite in part. Edge directions also do not follow change propagation: changing a contained artifact changes its container, which runs against the containment edge; changing an artifact does not change the knowledge. | One of CON-0064's four bullets is unsupported | Keep VALIDATED for "typed union loses nothing / closure loses type / total extension adds order". Mark the impact clause Hyp and require each relation's direction of propagation to be stated | verified, **V** |
| 6 | high | theory defect | §2.8.4 containment rules | — | The book says the rules "follow from" supervaluation, with ⊥ ranging over 𝒫(Vᵢ), so ∅ is a possible completion. Counterexample: A = (⊥, {a}), B = (V₁, {b}). The per-dimension rules give true ∧ false = **false**; supervaluation gives **unknown**, because X₁ = ∅ empties A. I confirmed this by brute force. It is the same class of error as audit L-12. | A definite "not contained" can be reported for a case that is actually undetermined (for example in exception checks) | Either define ⊥ as a non-empty unknown, or make "A possibly empty" yield unknown | internal (computed) |
| 7 | high | theory defect | §2.3 final conflict definition | — | It asks whether effects can be "jointly satisfied" at a world state w. If w is a complete state, φ(w) is already fixed, so there is nothing left to satisfy. The worked example silently splits w into a context and an "assignment". | Every downstream use of "conflict" (§2.4–2.6, §2.8, §2.9) inherits the gap | Split w into a context c and a controlled outcome x: conflict iff ∃(c, t) ∈ A′₁ ∩ A′₂ with ¬∃x: φ₁(c, x) ∧ φ₂(c, x) | internal |
| 8 | high | theory defect | §2.5, and the map ("priority numbers are rejected") | S0021 L114; S0034 L70 | Ground 1 excludes only ranks *set by a rule's author*. A precedence order enacted by the authority itself and applied by mechanism (lex superior) is not excluded. I-10's void-overriding is such an order, and it picks a winner between obligations that both bind (against ground 2). | The rejection holds only for author-assigned ranks | Narrow the title and claim to that scope; state where authority-enacted precedence stands | internal; S0021 verified, **M** |
| 9 | high | theory defect | CON-0038 VALIDATED | S0021 L99 "changed **freely**", L124 (graph scope is a choice); S0020 L35 (changing that scope is a governance act); S0021 L115 (I-11 restated by an unidentified "review") | The two criteria ("not changeable by a project"; "changeable without breaking the model") are not complements. The book rescues the concept by redefining "freely". The empty invariant set satisfies the distinction trivially. | Validation overstated | PROVISIONAL; define the invariant/choice line by a single criterion | verified, **V** |
| 10 | high | theory omission / homonym | CON-0098; CON-0011; §2.8.1 | GKC L12–14: "KNOWLEDGE SPACE = NATURE × REPRESENTATION × …" (the coordinate space); S0016 L30 (space boundary = `scope.include`); S0020 L34 (`scope.include` = graph coverage) | (a) The book's own §0.3 source uses a fourth sense of "knowledge space", which is missing from the list of senses. (b) `scope.include` serves both as a space's membership boundary (S-1) and as a checker's coverage (S-3). That is the S-1/S-3 conflation §2.8.1 forbids. | F8 is framed without one sense in use; CON-0011's "declared boundary" is ambiguous | Add the coordinate-space sense; split boundary from coverage | verified, **V** |
| 11 | normal | theory omission | Ch. 2 and Ch. 3 throughout; register | RESEARCH-PURPOSE L43–54 | No concept is marked kernel/application, minimal or stable, and the register has no field for it | The part does not yet answer the purpose's test | Add a register field and apply the test | internal |
| 12 | normal | evidence | §3.1 L40 (CON-0003 reason) | S0012 L125: "an observation, not a defect claim"; the disagreement is between a diagram and the enum | Used as evidence that the dimension itself is inconsistent | Weak reason (the register row has since been restated) | Drop it or qualify it | verified, **V** |
| 13 | normal | overclaim retained | §3.4 plain language; §3.0 index ("errors it makes unstatable") | S0016 L98 | The model makes the error *distinguishable*, not unsayable. The book's own Int (the absence test) is the correct, weaker form. | Rhetoric carried into the book's own voice | Reword | verified, **V** |
| 14 | normal | distortion (selective quotation) | §3.5 Open (S0023) | S0023 L79: adoption risk "pre-empts a declared gate" | Only the risk of rejecting is quoted | One-sided recommendation | Quote both risks | verified, **V** |

Lower-priority findings are in the appendix.

## 3. Changed theory

The brief lists **no item**: no text of Ch. 2 or Ch. 3 was changed at the last two settlements. There is nothing to judge. I reviewed the whole part as instructed.

## 4. Refutation adequacy and the foundational derivations

### Falsification attempts the book records

| Attempt | Adequate? | Reason, and the strongest counterexample I found |
|---|---|---|
| I-11, the lint-rule case (§3.6) | **inadequate** | It restates the definition. A stronger case: the `deny` configuration is a *constraint* whose representation is executable and which the runtime enforces (S0016 L131). I-11 survives this only through I-1, so it is derivable from I-1, not independent. |
| CON-0038 (I-4/I-7/I-11 "not changed by a project") | **inadequate** | It redefines "freely" (finding 9). |
| §3.1 "would need more cases" | **inadequate** | The cited pair does not establish even the weaker result (finding 2). |
| §3.8 C3 (a pair of nodes in two relations) | adequate for the typed-union bullet | Nothing was attempted for the impact bullet (finding 5). |
| CON-0034 (CAP-002 deferred yet bound) | adequate | — |
| CON-0047 (domains built by the author) | adequate | The limit is recorded. |
| §3.9 C2 (person, engine, policy engine) | adequate as tests of fit | The convention stays a stipulation, as the book says. |
| §3.10 C4 (computed check) | adequate | I reproduced the counts: 1,940 families, 0 laminar non-forests, 946 forests that are not laminar. |
| §2.8.4 audit L-12 (empty dimension) | adequate for that case | It misses the analogous case of a ⊥ that may be empty (finding 6). |
| §2.3 final definition; §2.5 grounds; §2.11 "effective" | **none recorded** | Counterexamples: findings 7, 8, and row D below. |

### The foundational derivations

| Derivation | (a) Strongest counterexample in scope | (b) Where it should stop holding | (c) Rival explanation | (d) Falsifier | Is the book's treatment adequate? |
|---|---|---|---|---|---|
| **A. Six independent dimensions** (§3.1) | Operational state "enforced" implies an executable representation (§2.9: enforcement ≤ realization), unless "enforced" means binding strength (finding 3). Ch. 0's constraint set S already makes status depend on representation. | For tacit knowledge, and wherever bearers differ | The six are properties of *two* objects, knowledge and carrier, merged into one product space | One artifact pair breaking S0016's independence claim once values are split by bearer | **No.** Independence is asserted in plain language and supported by an invalid Ded. |
| **B. Conflict = joint unsatisfiability on effective overlap** (§2.3), with three-valued applicability (§2.8.4) | Finding 7 (ill-typed w); finding 6 (containment) | Non-propositional effects; rules whose effects concern different subjects | Conflict as *non-compliance in every admissible action* (an action-theoretic reading) instead of satisfiability | Two rules both obeyable in every context but flagged by the definition, or the reverse | **Mostly.** Strong audit history; two gaps remain. |
| **C. Knowledge ≠ artifact** (§3.4) | Knowledge with a single carrier (the book's own C6); and finding 1 | Where the bearer of the dimensions is the artifact (RQ-002) | "Knowledge" = an equivalence class of artifacts under a sameness-of-content relation, so it is derivable rather than primitive | One case where two carriers of "the same" knowledge diverge, with no criterion to say which carries it | **Partly.** INVALID-partly-valid because of a conflict with S0020 L49. But that conflict is §3.5's adoption of S0020, not a defect of the split. |
| **D. Effective = standing adopted ∧ t ∈ validity** (§2.11; supports CON-0063) | A rule that is adopted, in force and superseded-in-part. "Adopted" is a lifecycle value in RS L144, yet the book calls it standing. | Partial supersession; exception regions | Effectiveness as a per-(w, t) property rather than a per-rule one | A deferred rule that binds by a recorded act | **Adequate in scope.** The lifecycle-vs-standing language needs repair (appendix A10). |

## 5. Concept verdicts and the architecture view

### Concept verdicts (only concepts I tested)

| Concept | Verdict | One-line reason and scope |
|---|---|---|
| CON-0001 nature | VALIDATED | Distinct from representation (GKC L35–36); the knowledge/claim is its bearer |
| CON-0002 representation | **PROVISIONAL** | Bearer undefined; contradicted by multi-carrier knowledge (finding 1) |
| CON-0005 evidence status | **PROVISIONAL** | Unsupported: source grades conflict; necessity is uncounted (finding 4) |
| CON-0006 operational state | INVALID (confirmed, with a further reason) | Homonym that merges binding strength with enforcement (finding 3); disproved as stated |
| CON-0009 admission test | INVALID as stated; the restated form is PROVISIONAL | Agree with the book |
| CON-0010 knowledge ≠ artifact | PROVISIONAL | Sound as a distinction; bearer unresolved |
| CON-0038 invariant vs choice | **PROVISIONAL** | Two criteria are mixed (finding 9) |
| CON-0047 projection method | VALIDATED | Validated as a well-defined procedure; its evidence strength is limited |
| CON-0063 a deferred rule is silence | VALIDATED | Derivation checked |
| CON-0064 four orders | VALIDATED for three bullets; the impact clause is unsupported | Finding 5 |
| CON-0092, CON-0093 | VALIDATED | Trivially sound in their scope |
| CON-0097 | PROVISIONAL | Agree with the book |
| CON-0098 | PROVISIONAL, incomplete | Missing sense (finding 10) |
| §2.3 final conflict definition | PROVISIONAL | Not yet built: the formal gap of finding 7 |
| §2.6 partition soundness | VALIDATED | Implication chain checked |
| §2.8.4 containment rules | INVALID as stated | Disproved by one case (finding 6) |
| §2.5 priority rejection | PROVISIONAL | Holds for author-assigned ranks only |
| §3.8 C2, §3.10 C4 | VALIDATED | Order criterion and laminar result checked |

**Not tested:** CON-0003, -0004, -0007, -0008, -0011, -0029 to -0037, -0039 to -0046, -0055 to -0057, -0061, -0062, -0065 to -0067, -0082, -0083, -0090, -0091, -0094, -0095; §2.0–2.2 and §2.7–2.11 against RM/RS/SC/SD/EV/IM/PC/DE (all U).

### Architecture view

- **Ch. 2 works as a chain.** Its primitives are rule, applicability, scope (three kinds), effect, exception, standing and validity. Its laws are SC-2, containment, the licensing of E1–E10 and "effective". Its results are partition soundness, the gate rules and the priority rejection. The chain from primitives to results is coherent.
- **Ch. 3 is mostly an inventory of source vocabulary.** 26 of its 41 hosted concepts are INVALID. It derives few laws of its own, and its one bridge to Ch. 2 (§3.1 L34) is contradicted (finding 3).
- **Isolated parts:** §2.6 (no links in or out); and Ch. 3's dimensions against Ch. 1's labels (F13, unmapped).
- **Circular:** I-11, still unreduced (SEQ-0012). I agree with the book.

**Independence and minimality of primitives:**

| Primitive or law | Result |
|---|---|
| Governance status vs operational state | independent (L129 against L131) |
| Operational state vs representation | not independent (§2.9) |
| Evidence status vs governance status | undecided (asserted in S0016 L45, never shown) |
| Intent | derivable |
| I-11 | derivable from I-1 plus "only runtimes run" (§3.9 C2) |
| The "no standing" norm on projections | derivable from A1 |
| CON-0006 | probably derivable from §2.9's properties, so not minimal |

## 6. Coverage

**The blind comparison covers only my five sampled sources** (S0016, S0020, S0030, S0013, S0009). The rest of the part was reviewed non-blind.

| Point from my inventory | Status | Rank |
|---|---|---|
| Who carries the dimensions (S0016 §4 classifies artifacts) | MISSING | 1 |
| The "enforced" homonym (S0016 L128 vs L131; S0020 L46) | MISSING | 2 |
| Grade drift of evidence and operational status: ADMIT (S0016) / candidate (S0009) / HYPOTHESIS (S0013 H-9) | CONTRADICTED by CON-0005's verdict | 3 |
| "Ten rediscoveries" given with no list | MISSING | 4 |
| The orthogonality "proof" at n=2 | COVERED, but the book's own Ded is wrong | 5 |
| Trust/provenance as a fifth concern without a dimension (S0016 L119) | OPEN (touched in §3.2 L37) | 6 |
| Kernel "falsified for sufficiency at n=1" (S0009 L14) | MISSING in Ch. 3 (§3.6 cites S0025 L153 instead) | 7 |
| Sufficiency untested; values outside D-III (except "realized"); the file's time layers; MM-4 | COVERED | — |
| Key collision overreach; declared scope; L-B does not exist; PKS-as-projection falsified; S0020 L49 vs the split; persistence rather than independence of passes; U-MM-1 | COVERED | — |
| S0013's definitional guard (canon ≠ validated) | MISSING; it bears on the book's own VALIDATED grades | 8 |
| S0030 (empty log); "mission enacted"; S0013 C-12 | JUSTIFIABLY EXCLUDED from Ch. 2/3 | — |

**New topics and forks:**
- the bearer of each dimension (new fork);
- precedence enacted by an authority, against precedence set by a rule's author;
- the boundary sense against the coverage sense of `scope.include`.

**Source errors (listed separately):**
- S0016: no sufficiency column; values outside D-III; "enforced" homonym; D-III attributed to "RQ-002 + statuses.yaml", although GKC L38 has 5 values and the enum has 8.
- S0020: closing traceability line not updated after W-3.
- S0009: closing contradicts REV 2; tally slip at L143; "could not see each other" (L46).
- S0013: "nothing in two states" holds per row, not per concept.
- S0022: tallies disagree (covered by the book).

## 7. Next steps (in dependency order)

| # | Step | Done when |
|---|---|---|
| 1 | Assign a bearer to every dimension (fork) | each of the six has a bearer, and S0016's six-artifact test is re-run under it |
| 2 | Split "enforced"; map CON-0006 onto §2.9's four properties (closes T2-04) | a mapping table is in place, and the §3.1 independence claim is restated with values split by bearer |
| 3 | Re-grade CON-0005, CON-0038, CON-0064 | register rows updated with these reasons |
| 4 | Repair §2.3 (context/outcome split) and §2.8.4 (⊥ semantics) | brute-force check over small V agrees with supervaluation |
| 5 | Add the coordinate-space sense to CON-0098; split `scope.include` | F8 restated with four senses |
| 6 | Scope §2.5 to author-assigned ranks; place I-10 explicitly | a stated rule for precedence enacted by an authority |
| 7 | Apply the kernel test to every concept of the part | a register field filled for every Ch. 2/3 concept |

**No question for the Principal is needed:** each step can proceed as a recorded fork.

## 8. Limits

- I did not open the `docs/knowledge_tranfer/` files (RM, RS, SC, SD, EV, GA, V3, CF, AB, IM, IA, PC, DE, SI, AMS). Everything in §2.0–2.11 that rests on them is checked for internal consistency only.
- I did not check: S0166, S0889, S1052, S0577, S0357, S0651, S2221, S2838; S0024 L124–149; the configuration and code files; the rest of Ch. 1, and Ch. 5, 6 and 9 beyond the lines named below.
- I did not run `kernel_model.py`.
- I decided no fork.

## 9. Files opened and searches run

### Step 1
- `R/P2/BRIEF.md` (headings, L50–90); S0016, S0020, S0030, S0013, S0009 (in full).
- `wc -l` on S0026 and S0015 (contents not opened).
- grep of `CORPUS-ALLOWED.tsv` for S0009, S0013, S0015, S0016, S0020, S0026, S0030.
- `git log --format` on the five sources.

### Step 2: files opened
- `R/P2/STEP2-COMMISSION.md`; `R/P2/BRIEF.md` (in full).
- In `BOOK-UNDER-REVIEW/06-theory/`:
  - `RESEARCH-PURPOSE.md`;
  - `THEORY-MAP.md` (L1–60 and L337–349, plus grep);
  - `CONCEPT-REGISTER.tsv` (header and the Ch. 2/3 rows, via awk/grep);
  - `manuscript/chapter_02/` (every file in the brief, including `02.99` and `TODO.md`);
  - `manuscript/chapter_03/` (every file in the brief, including `TODO.md`);
  - `manuscript/chapter_00/00.03-the-knowledge-model.md` (L1–60);
  - `manuscript/chapter_01/01.05-knowledge-is-not-its-carrier.md` (L1–40);
  - `manuscript/chapter_01/01.06*.md` (grep for "A1" only).
- Allowed sources, at specific lines via sed: S0017 L50, 68 · S0008 L23–25, 37, 38, 67 · S0012 L93, 118–125 · S0021 L7, 19, 32, 44, 48, 79, 89, 91, 99, 101, 103, 108, 111, 115, 117, 124, 130, 134, 150, 151, 159 (and `wc`) · S0022 L5, 10, 30, 42, 52, 62, 66, 67, 69, 77, 83, 91, 104, 125, 142, 149, 161, 166, 187, 191 · S0026 L10, 29, 36, 39, 42, 49, 51, 59, 62, 83, 84, 103, 128 · S0043 L44, 48, 50, 52, 53, 59, 63, 64 · S0024 L72, 80, 84, 88, 137 · S0025 L72, 74, 143, 144, 153 · S1524 L194, 891, 900 · S0029 L7, 16, 28 · S0005 L26 · S0007 L35 · S0023 L75, 79, 82.
- Granted file: `docs/implementation/RQ-002_General_Knowledge_Architecture_Constitutional_Model.md` L10–45.
- Directory listing and `wc -l` of the copy. That output listed the directories `chapter_02/review` and `chapter_03/review`; I did not open them.
- My own check script: `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/chk.py` (the laminar count and the containment counterexample).

### Step 2: searches
- The copy's `book_search.py` with: `--help`, "bearer", "Knowledge Instance", "coordinate", "knowledge item", "authority theorem", "A1", "canonical means", "kernel or application".
- grep in `chapter_02/*.md` and `chapter_03/*.md` for: S0013, S0030, H-9, H-5, "definitional guard", "S0009 L14", L119, realized.
- grep of `THEORY-MAP.md` for §2., §3., Ch. 2, Ch. 3, F8, F11, F12.
- grep of `CONCEPT-REGISTER.tsv` for the Ch. 3 concept IDs.

**Item-3 files of the reviewer note:** none reached. I did not open the working chapters, the working map, register or outline, `BASELINE-REVISIONS.md`, any `*-OLD-*` file, or git history of the manuscript.

---

## Appendix: lower-priority findings (normal)

- **A1** §3.2 L13 says the test is applied "consistently", against its own restatement that only orthogonality is applied. Internal.
- **A2** §3.1 L16 and L18 cite S0016 L42/L44 for value lists; S0016 has none there, and the values are at GKC L35–38. **V**
- **A3** §3.3 plain language: "five earlier schemes agree", against the body's persistence reading. Internal.
- **A4** THEORY-MAP §3 states the superseded conflict definition (no exceptions removed, no "mandatory only"). Internal.
- **A5** §2.2 L49 "ten parts" against the seven parts formalized. Internal.
- **A6** §2.4 L16 "authority theorem" against the map's "Ax A1". Internal.
- **A7** §2.7: formal analysis "upgrades … to proven or unknown"; unknown is not an upgrade. **U**
- **A8** Homonyms: "dimension" (scope dimension in SC-1 vs meta-model dimension) with two different admission tests (§2.8.6 vs §3.2); "kind" (artifact kind vs archetype vs nature); "H-5" (S0013 generates→PKS vs S0022 method layer); "projection" (§2.6 π, §2.4 derived view, §3.5 DP-2, §3.10 idempotent). **M**
- **A9** The register term for CON-0097 says "two criteria"; the text has five. Internal.
- **A10** §2.11 calls "adopted" a standing, while its own quote of RS L144 calls it a lifecycle status. This collides with §2.8.6's "three lifecycle notions". **U/internal**
- **A11** §3.1 L5 "six independent properties", against Ch. 0 §0.3's constraint set S and §2.9's enforcement ≤ realization. Internal.
- **A12** T2-10's concept label still reads "INVALID" although the item is resolved as VALIDATED. Internal.
