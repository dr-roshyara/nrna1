# PROPOSED BOOK STRUCTURE + CHAPTER-BY-CHAPTER CROSSWALK

**Authority:** HPA instruction 2026-08-31 (eight constraints), recorded GN-76. **This is a
proposal and a crosswalk. No book file was edited. No chapter was moved, renamed, or rewritten.**
BA-1…BA-6 remain ratified and unmodified; any adoption of what follows requires a **BOOK
ARCHITECTURE v2 RATIFICATION** act (BA-6 §1; GN-34).

**Constraints applied verbatim:** Part III's 10 produced, gate-passed chapters kept intact ·
implementation material added as a separated layer, never by re-meaning Part III · both readers
served, explicitly layered, centre of gravity toward the implementing engineer · no verification
finding presented as ratified theory · no gap resolved by editorial rewriting · every
implementation requirement traceable to its source class · unratified dependencies marked
proposed/open · **STOP after this artifact.**

---

## 1 · A structural collision that needs your ruling before anything else

The five-part shape you sketched collides with the existing book at two points:

| Your proposal | The book as it stands | Conflict |
|---|---|---|
| Part I — Foundations (why · terminology · epistemic foundations) | **Part I · DISCOVERY** (6 ch) — the *history* of how KnowledgeOS was searched for; produced in Edition 1, **0/6 in Edition 2** | Different subject. "Foundations" is not the discovery history. |
| Part II — KnowledgeOS theory (formal model + mathematics) | **Part II · RECONSTRUCTION** (4 ch) — the method that turned corpus into architecture; **ACCEPTED and closed (GN-70)**, 4/4 produced | **Hard conflict.** Part II is accepted and frozen. It cannot be repurposed into a theory Part without reopening a closed acceptance. |
| Part III — Ratified Architecture | Part III · ARCHITECTURE (10 ch, produced, gate-passed) | ✅ agrees |
| Part IV — Implementation Specification | (does not exist) | ✅ new |
| Part V — Implications / Open Questions | Part IV · IMPLICATIONS (5 ch, planned, 0/5 produced) | ✅ agrees — renumber |

**The reconciliation I recommend** keeps every accepted artifact intact and adds rather than
repurposes. Your five logical layers all survive; two of them simply sit in Parts that already
have historical names:

```
Part I    · DISCOVERY                6 ch   [H]     unchanged   (Ed1 done · Ed2 0/6)
Part II   · RECONSTRUCTION           4 ch   [M]     ACCEPTED — frozen, untouched
Part III  · ARCHITECTURE            10 ch   [FA]    PRODUCED + GATED — protected, untouched
          ├─ the canon layer: what the governed architecture defines
Part IV   · THE FORMAL PROGRAMME   ~5 ch   [H/M]   NEW — the verification era (steps 183–283)
          ├─ what was attacked, what was produced, what could not be settled
          └─ every construct here is explicitly NON-CANONICAL
Part V    · IMPLEMENTATION SPECIFICATION ~9 ch [E]  NEW — what an engineer builds
          └─ objects → state → invariants → operations → transformations → evidence
             → governance → software → tests
Part VI   · IMPLICATIONS & OPEN QUESTIONS 5 ch [P]  the present Part IV, renumbered
```

**Why a separate Part IV (The Formal Programme) rather than folding the formal work into Part V:**
the verification-era constructs (Σ, `𝒪`, `Q_t`, the closure senses, the L0–L6 evidence model)
have **no governance act behind any of them**. They are real intellectual work and they belong in
the book — but as *what the programme did*, not as *what the system is*. Giving them a history
Part is the only placement that lets the book carry them without promotion.

**Alternative if you prefer your numbering exactly:** rename Part I to "Foundations" and move the
discovery history into it as its opening chapters — but that reopens Part I's ratified chapter
table, and Part II's conflict remains unresolvable. I do not recommend it.

---

## 2 · Chapter-by-chapter crosswalk — all 25 existing chapters

Columns as instructed. **Evidence status** uses the six classes you named
(RATIFIED / AUTHORIZED / FORMAL / TESTED / INTERPRETIVE / OPEN), never collapsed.

### Part I · DISCOVERY — retained whole, renumbering only if Part I is renamed

| Ch | Purpose | Disposition | Implementation dependency | Evidence status |
|---|---|---|---|---|
| I.1 The Problem Before the System | root era; day-one evidence-first stance | **RETAINED** | none | INTERPRETIVE + OPEN (OQ-11 EKS↔KnowledgeOS unsettled) |
| I.2 The Constitutional Turn | EP01 sequence, 11 articles, Z-KOS-001 | **RETAINED** | none — but Art. 2/3/4/6 are the *source* of Part V's governance constraints | AUTHORIZED (constitution ratified; banner-sync OQ-10 open) |
| I.3 The Lens Programme | kernel era, 8 formulations, Ω's arrival | **RETAINED · still gated** | none | OPEN — evidence-gated on the 38-doc kernel commission (BA-ED2-09); never filled from inference |
| I.4 The Measure-Theory Crisis | ALT-01 rise and fall | **RETAINED** | none | INTERPRETIVE (historical) |
| I.5 Questions, the Charioteer, and the Lord | Q-series; Sārathi/Knower; Lord collision | **RETAINED** | none | INTERPRETIVE + OPEN (OQ-7, OQ-8) |
| I.6 Formalization Attacks Itself | closures, EXP-01, Step 121, freezes | **RETAINED** | EXP-01's negative result is cited by Part V's evidence chapter | TESTED (EXP-01) + INTERPRETIVE |

### Part II · RECONSTRUCTION — frozen, no disposition available

| Ch | Purpose | Disposition | Implementation dependency | Evidence status |
|---|---|---|---|---|
| II.1 Archaeology Before Architecture | regimes R1–R5; era census | **UNTOUCHED — ACCEPTED** | none | RATIFIED as an accepted account (not as theory) |
| II.2 Grades and the Final Rule | the grading system; honesty rules | **UNTOUCHED — ACCEPTED** | Part V inherits its evidence-class discipline | RATIFIED (accepted) |
| II.3 Falsification Under Authority | 3B findings → GN-19 → v0.2; the breach | **UNTOUCHED — ACCEPTED** | none | RATIFIED (accepted) |
| II.4 Conformance and the Living Repository | 3C verdicts; Gate A dispositions | **UNTOUCHED — ACCEPTED** | CF-015's footprint facts feed Part V's software chapter | RATIFIED (accepted) |

### Part III · ARCHITECTURE — retained intact; **extended only by reference from Part V**

Nothing in this Part is rewritten, re-meant, or moved. Part V *cites* it; Part V never edits it.

| Ch | Purpose | Disposition | Implementation dependency (what Part V needs from it) | Evidence status |
|---|---|---|---|---|
| III.1 The Five Layers | L1–L5 authority strata | **RETAINED** | the layer frame that types every implementation claim | RATIFIED (FA-1) |
| III.2 The Knower and the Frame | Knower, G, IdealState, EC=η; I-1 | **RETAINED** | I-1 ownership constrains who may authorize | RATIFIED + OPEN (OQ-1 η-totality) |
| III.3 Knowledge and the World | X_t ≠ Observed(X_t); K_t; SourceObs≠SemanticObs | **RETAINED** | the ratified `K_t` (8 primitives) is the canon state object | RATIFIED (TESTED within scope) |
| III.4 Zero — the Gap That Drives Inquiry | Zero(K,EC); typology; three-name register | **RETAINED** | Zero's typology is the canon's nearest missingness carrier | RATIFIED + OPEN (OQ-1) |
| III.5 Evidence and Its Algebra | I-5/I-6; EXP-01 negative result | **RETAINED** | I-5/I-6 are the only TESTED invariants an implementer inherits | TESTED (I-5, I-6) + OPEN (OQ-3 operator unselected) |
| III.6 States and Admission | ladder + A6 + no-skip + layered states | **RETAINED** | the ratified ladder is the canon status model | RATIFIED (R-3/R-4, I-12) |
| III.7 Decision, Authorization, Action | DC 6-tuple; the 042 triple; interlock | **RETAINED** | DC is the canon decision object; the interlock bounds execution | RATIFIED + OPEN (OQ-4 far side) |
| III.8 The Policy That Governs Itself | stratification; I-11; four sightings | **RETAINED** | I-11 is the canon policy-change rule — **and the site of the unresolved GC-1 collision** | RATIFIED (I-11, REQUIRED-BY-COHERENCE) + **OPEN (GC-1)** |
| III.9 Three Kernels | D-FA-4 layering | **RETAINED** | none directly | RATIFIED + OPEN (OQ-2 membership) |
| III.10 The Invariants | I-1…I-12 with grades | **RETAINED** | the canon invariant set Part V must render executable | RATIFIED (2 TESTED · 8 READ · 2 RC) |

### Part IV (current) · IMPLICATIONS — **MOVED to Part VI**, content retained

| Ch | Purpose | Disposition | Implementation dependency | Evidence status |
|---|---|---|---|---|
| IV.1 Engineering with KnowledgeOS | implementation profile; L4 thinness | **MOVED → VI.1 · scope narrowed** — its implementation content is superseded by the new Part V; what remains is adoption outlook | its factual core (CF-015) migrates to Part V | INTERPRETIVE + OPEN |
| IV.2 AI Agents Under Epistemic Governance | bootstrap/operating-model as lived examples | **MOVED → VI.2** unchanged | examples only, never architecture | INTERPRETIVE |
| IV.3 Parallel Lanes and Future Intake | RA v1.1 lane; EKS relationship | **MOVED → VI.3** unchanged | none | OPEN (OQ-9, OQ-11) |
| IV.4 What Remains Open | OQ-1…12 with prerequisites | **MOVED → VI.4 · EXTENDED** — must now also carry GC-1, `𝒪_core` non-ratification, the Σ/`Q_t` non-canonical status, and the closure disagreement | none | OPEN (all) |
| IV.5 How to Read This Book's Evidence | provenance model; coverage honesty | **MOVED → VI.5 · EXTENDED** — must add the four status dimensions and the layer-separation rule | governs how Part V's requirements are read | RATIFIED (method) |

---

## 3 · Proposed new Parts — chapters, dependencies, and honest status

### Part IV · THE FORMAL PROGRAMME *(new · voice [H]/[M] · every construct NON-CANONICAL)*

| Ch | Purpose | Implementation dependency | Evidence status |
|---|---|---|---|
| IV.1 The Second Attack | why a verification programme was commissioned after v0.2; what it was told to do | none | INTERPRETIVE (history) |
| IV.2 The Formal Reconstruction | the competing state models (seven kernel tuples), what each tried to fix | none — **must not be read as the canon state** | FORMAL, NOT RATIFIED |
| IV.3 Status, Inquiry, Operations | Σ, `Q_t`, `𝒪` presented as *programme results with their exact status* | Part V depends on these but may not assume them | FORMAL + OPEN — **no governance act for any of the three** |
| IV.4 What Execution Showed | the executed results: what ran, what passed, what returned identity | Part V's test chapter inherits this | TESTED (harness-level, L4) — never real-environment |
| IV.5 What the Programme Could Not Settle | the closure disagreement, the contradictions, the withdrawn evidence | none | OPEN |

### Part V · IMPLEMENTATION SPECIFICATION *(new · voice [E] · the engineer's Part)*

Every requirement carries its source class inline. The chain you specified, one link per chapter:

| Ch | Chain link | Ratified source available? | Evidence status of the chapter as it could be written today |
|---|---|---|---|
| V.1 How to read a requirement | — | yes (II.2 discipline + BA-4) | RATIFIED (method) |
| V.2 Objects and identity | objects | **partial** — canon has `K_t`'s 8 primitives; identity is not a canon object | RATIFIED (partial) + FORMAL/OPEN for identity |
| V.3 State and its boundaries | state | **yes** — ratified `K_t` | RATIFIED |
| V.4 Invariants as executable constraints | invariants | **yes** — I-1…I-12 with grades | RATIFIED (2 TESTED, 8 READ, 2 RC) — **no closed invariant register exists** |
| V.5 Operations and their contracts | operations | **NO — the ratified model names zero operations** (grep-verified) | **BLOCKED** — FORMAL/OPEN only; `𝒪_core` not ratified, three non-agreeing membership lists |
| V.6 Legal transformations and rejection | transformations | **NO** — no pre/post-condition specification exists anywhere in the record | **BLOCKED** — and `δ`-commit executes as identity (`K₁ is K₀`) |
| V.7 Evidence, provenance, lineage | evidence | **partial** — I-5/I-6 TESTED; the 9-field Evidence object is unratified | RATIFIED (partial) + FORMAL/OPEN |
| V.8 Governance, authority, and what software may never decide | governance | **yes** — Art. 3/4, A6, DC.Auth, I-11 | RATIFIED — **with GC-1 open and visible** |
| V.9 Software realization and certification | software + tests | **partial** — real: lineage, provenance, content-addressed ids, the human-act discipline | TESTED (real, narrow) + OPEN — 16 of 25 constructs have no real-environment witness |

### Part VI · IMPLICATIONS & OPEN QUESTIONS — the present Part IV, moved and extended (table above).

---

## 4 · The finding that decides how much of Part V can be written now

Traced against the chain you named, the ratified corpus supports the **front half** and stops:

```
objects        RATIFIED (partial)   K_t's 8 primitives
state          RATIFIED             K_t
invariants     RATIFIED             I-1..I-12, grades intact
operations     ── BLOCKED ──        v0.2 names ZERO operations (verified: 0 matches)
transformations── BLOCKED ──        no pre/post-condition spec exists anywhere
evidence       RATIFIED (partial)   I-5/I-6 TESTED; 9-field object unratified
governance     RATIFIED             Art.3/4, A6, DC.Auth, I-11 — GC-1 open
software       TESTED (narrow)      lineage/provenance/ids/human-act discipline
tests          TESTED (L4 mostly)   16 of 25 constructs unobserved in the real environment
```

**Consequence, stated plainly:** an implementation specification cannot be completed from ratified
material alone, and the material that *would* complete it (`𝒪_core`, Σ, `Q_t`, the operation
algebra) is **not ratified and, in `𝒪_core`'s case, explicitly must not be frozen**. Part V is
therefore writable *now* at chapters V.1–V.4, V.7–V.9 with honest gaps, and **blocked at V.5–V.6**
until a synchronization decision exists. Writing V.5–V.6 from the verification lane would be
exactly the promotion your constraints 4, 5 and 7 forbid.

---

## 5 · Decisions required before any prose

1. **Structure** — adopt the six-Part reconciliation above, or rule differently on the Part I/II
   collision (§1). Part II cannot be repurposed without reopening GN-70.
2. **BA v2 ratification** — the amendment vehicle (`BA-ED3`, additive, in the BA-ED2 pattern).
3. **V.5/V.6 blockage** — either accept that Part V ships with two chapters marked
   `NOT ESTABLISHED — awaiting canonical operation set`, or rule on `𝒪_core` first.
4. **GC-1 / I-11** — Part V's governance chapter cites I-11; the collision must be visible there
   or ruled.
5. **Part IV (Formal Programme) admission** — whether the verification era enters the book at all,
   even as history.

## 6 · What was not done

No book file edited · no chapter moved on disk · Part III untouched (all 10 md5s unchanged) ·
Part II untouched · no construct promoted · no OQ moved · no evidence class collapsed ·
`𝒪_core` not frozen · Σ and `Q_t` not canonicalized · GC-1 not resolved.
