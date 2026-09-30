# Corpus Theory Recovery — F0001–F0025

| | |
|---|---|
| **Baseline** | Candidate Theory **v0.3**, frozen as a snapshot |
| **Purpose** | ⭐ **recover the theory the corpus already developed** — ⛔ *not* judge the corpus against the current theory |
| **Origin labels** | `[C]` corpus-derived · `[S]` corpus-synthesized · `[E]` expert-derived |
| **Result** | **3 recovered · 1 synthesized · 2 expert-derived · 1 correction to my own experiment · 2 refusals** |

---

## 1 ⭐⭐ The recovery that corrects `EXP-0002`

### `R-01` · There are **three** roles below the authority, not two — `[C]`

**Recovered from `ES-004.1`** *(ARB 2026-07-11)*, found by targeted search:

> *"The AI is **researcher/recorder/analyst**; the ARB is the authority. Retrospective language: **'Recommended for Promotion/Retirement' + evidence + 'ARB decision: PENDING.'** A decision becomes PROMOTED only when the ARB confirms."*
> **Authority chain: `implementation → evidence → analysis → ARB decision → standard`**

And separately, `ES-003.3` / `R-26`:

> *"The measuring instrument runs → captures → returns **PASS/FAIL** → stops; no interpretation inside the instrument."*

| Role | Output contract | Source |
|---|---|---|
| ⭐ **Measuring instrument** | `PASS/FAIL`, no interpretation | `ES-003.3` |
| ⭐ **Analyst** | ***recommends, never declares***; recommendation + evidence + `PENDING` | `ES-004.1` |
| **Authority** | accepts · defers · refuses | both |

> ## ⛔ **This corrects `EXP-0002`.**
>
> I classified `knowledge-lint`'s *"a recommendation to the author, never a rejection"* as a **violation** of `R-26`. But `ES-004.1` is titled ***"Retrospectives Recommend; They Never Declare"*** — recommending **is the analyst's correct contract**.
>
> ⭐ **The theory had two roles. The corpus has three.** I measured an analyst against an instrument's contract and recorded a violation that was conformance.

**Operation:** `SPLIT` + `ADD`. **Provenance:** `ES-004.1`, `ES-003.3`.
**Effect:** `EXP-0002` violation 2 → ⛔ **WITHDRAWN**. Violation 1 (`link-check --apply`) **stands** — neither an instrument nor an analyst may *apply* a correction; `ES-003.1` reserves that for the DA.

⚠️ **Open:** which role `knowledge-lint` actually occupies is now a *classification* question with a real answer space — previously the theory had no category for it.

---

## 2 · Recovery matrix

| Source | Recovered concept | Historical meaning | In v0.3 | Status | Action | Origin |
|---|---|---|---|---|---|---|
| `ES-004.1` | ⭐ **the analyst role** — *recommends, never declares* | a third role between instrument and authority | ⛔ missing | **NEW** | ⭐ `SPLIT`+`ADD` | `[C]` |
| `ES-004.1` | ⭐ **authority chain** `implementation → evidence → analysis → ARB decision → standard` | a **five-stage** chain | partial *(4 stages)* | **PARTIAL** | `EXPAND` | `[C]` |
| F0012 | *"Governance precedes automation…"* | the general principle | ⭐ added v0.3 | REPRESENTED | `PRESERVE` | `[C]` |
| F0012 | provider independence · four-layer runtime | invariance + anti-leak layer | ⭐ added v0.3 | REPRESENTED | `PRESERVE` | `[C]` |
| F0012 | ⭐ **designed vs observed relationship** — *"speculative relationships are marked SPECULATIVE inline"*; *"3 of 4 arrows speculative or empty"*; *"the accepted destination; not yet an observed cycle"* | the corpus **distinguishes** a designed arrow from an observed one | implicit only | **PARTIAL** | ⭐ `FORMALIZE` | `[C]`→`[S]` |
| F0018 + schema + `EXP-0001` | authority as a 2-D state space | 6 states needed, 5 representable | partial | **PARTIAL** | ⭐ `TYPE` | `[E]` — see §3 |
| `EXP-0002` + `ES-004.1` | instrument-kind distinction | — | ⛔ missing | **NEW** | ⭐ `ADD` | `[E]` — see §3 |
| F0022 · F0023 | decision model | ⚠️ excluded by document kind | ⛔ **unexamined** | **NOT YET EXAMINED** | ⛔ `INVESTIGATE` | — |
| — | *Knowledge Space* | used, never defined | missing | ⛔ **REFUSED** | ⛔ see §4 | — |

---

## 3 · Expert-derived additions — `[E]`, with full justification

### `E-01` · `AuthorityState = Provenance × Standing` as a **typed product**

| | |
|---|---|
| **Missing** | a two-dimensional state space; the corpus asserts the distinction but never types it |
| **Why necessary** | `MATHEMATICAL_COMPLETENESS` + `SEMANTIC_COMPLETENESS`. The corpus supplies a worked example requiring **6** states; the schema admits **5**, single-valued. Without a product type the example is unstateable, and `EXP-0001` (39 docs, 0 counterexamples) has nothing to be a test *of* |
| **Basis** | `F0018` §4 · `authorities.yaml` · `knowledge-schema.yaml` · `EXP-0001` |
| **Reasoning** | \|P\|=2, \|S\|=3 ⇒ \|P×S\|=6. One field, `type: enum`, 5 values ⇒ at most one value per document ⇒ the conjunction is unsatisfiable |
| **Alternative** | ⚠️ **Yes, and it is live.** The five values may be **one ordinal trust scale** — the schema's own `rank: 1–5` supports this reading. If so there is no product, only a badly-ordered scale |
| **Assumptions** | that the value/description partition into P and S is semantically correct |
| **Falsifier** | one document carrying both dimensions, **or** evidence that `rank` is the intended semantics |
| **Status** | ⭐ **`[E]` EXPERT-DERIVED CANDIDATE** — ⛔ **not** a corpus fact |

### `E-02` · Instrument **kinds** must be typed

| | |
|---|---|
| **Missing** | the theory has *"instrument"*; the corpus has at least **two contracts** (`PASS/FAIL` vs *recommend-never-declare*) and my experiment measured one against the other's rule |
| **Why necessary** | `SEMANTIC_COMPLETENESS` + `DDD_COMPLETENESS`. Two concepts currently collapse, and the collapse **produced a false finding** |
| **Basis** | ⭐ `ES-003.3` · `ES-004.1` · `EXP-0002` raw result |
| **Reasoning** | Different output contracts, different permitted acts, different relation to the authority ⇒ different types. An analyst that returns `PASS/FAIL` would be over-claiming; an instrument that recommends would be interpreting |
| **Alternative** | ⚠️ they may be one role with two *modes*. The corpus does not say |
| **Falsifier** | governance text treating the two contracts as interchangeable |
| **Status** | ⭐ **`[E]` EXPERT-DERIVED CANDIDATE** |

---

## 4 ⛔ Deliberately NOT added — and why

| Refused | Reason |
|---|---|
| ⛔ **A definition of *Knowledge Space*** | Searched: no definition anywhere in `docs/knowledge`, `engineering`, `docs/implementation`. ⭐ **The term is used but never constrained enough to derive from.** Constructing one would be invention, not completion — there is nothing to complete. **Recorded as a genuine corpus gap.** |
| ⛔ **An operational definition of *promotion*** | Tempting — it would make the central mechanism testable. But `ES-004.1` shows promotion is an **ARB confirmation act**, and defining *"what counts as an observed promotion"* without the ARB's own criteria would fabricate the very thing under test |

> ⭐ **Both refusals follow `§5C.4`:** search first, and *"not found"* has eight meanings. For *Knowledge Space*, the meaning is **genuinely absent** — which is itself a recovery finding.

---

## 5 · Recovery coverage state

| | |
|---|---|
| **Recovered** | `R-01` the analyst role · the five-stage authority chain · designed-vs-observed as a corpus distinction |
| **Already represented** | governance-precedes-automation · provider independence · four-layer runtime · authority two-dimensionality |
| **Expanded** | the authority chain 4 → **5 stages** |
| ⭐ **Missing and now added** | the analyst role `[C]` · instrument kinds `[E]` · authority product type `[E]` |
| ⚠️ **Missing but unresolved** | *Knowledge Space* · an operational definition of promotion · F0013 ↔ `RQ-002` |
| **Competing** | the authority state space: **product** vs **single ordinal scale** (`rank`) — ⭐ **genuinely unresolved** |
| **Rejected as non-theoretical** | migration maps · stage roadmaps · measured counts · observation logs *(reason and location recorded)* |
| ⛔ **Not yet examined** | **F0022 · F0023** — excluded by document kind, `Q54` violated |

> ⛔ **Coverage is NOT completeness.** 25 of ~3,000 files. ⭐ **Two of the three most consequential recoveries this pass came from `engineering/governance/`, not from the corpus window at all.**

---

# 6 ⭐⭐⭐ F0022 · F0023 re-examined — the exclusion was a serious error

> ⛔ **Both files were excluded as "governance process" by document kind.** Both are **heavily theory-bearing**. `Q54` was violated, and the cost includes **one wrong headline finding**.

## 6.1 `R-02` · The corpus already answers *"what is KnowledgeOS for"* — better than my theory does — `[C]`

**`engineering/architecture/reference/Engineering_Decision_Model.md`** — DRAFT, **ARB-accepted 2026-07-11**, `Owner: Decision Authority`, pointed to by the frozen runtime binding.

| Recovered | Verbatim |
|---|---|
| ⭐ **The purpose statement** | *"The Standards say what the rules are; **this model says what decisions an Engineer resolves with them**"* |
| ⭐ **A universal pattern** | `Question → Decision → Authority → Procedure → Evidence` |
| **Eight indexed decisions** | `DetermineConcern` · `DeterminePlacement` *(mechanized)* · `DetermineArtifactLifecycle` · `DetermineArtifactType` · `DetermineReusePotential` · `DeterminePromotionPath` · `DetermineApplicableStandards` · `DetermineQualificationMethod` |
| **A stopping rule** | *"No new engineering decisions unless operational evidence demonstrates insufficiency… the first question is: **which existing decision does this extend?**"* |
| ⭐ **Its anti-purpose** | *"a decision **INDEX, never a second rulebook**"* |

> ## ⭐⭐ **And the answer to the commission's single question:**
>
> ### *"The eight indexed Engineer decisions — resolved daily through governed procedures — **and the Governance decisions it PREPARES BUT NEVER MAKES**."*
>
> ### ⭐⭐⭐ ***"For authority decisions, the platform's product is DECISION-READINESS, not decisions."***

⛔ **This is a better answer than my §1.** Mine — *"a governance platform for engineering knowledge"* — is a **positioning claim the corpus declined to adopt**. This is **ARB-accepted canon**, and it is sharper: the platform resolves eight engineer decisions and **shapes, but never makes,** governance decisions. *77 rulings were made by humans; the platform's contribution was that each arrived shaped.*

**Operation:** `ADD` + `REPLACE` §1's core answer. **Origin `[C]`.**

## 6.2 `R-03` · The catalog/log asymmetry — and F0022 correcting F0023 — `[C]`

**F0023 Finding 1:** *"the platform has a decision **CATALOG** for engineers and only a decision **LOG** for governance"* — 77 rulings, cataloged nowhere.

⭐ **F0022 corrects it the next day:** *"The absence of governance decisions from the catalog is **DESIGN, not gap**"* — *"Promotion is a human governance act and is never automated."*

> **Two files, one day apart: a finding, then its own correction.** The asymmetry is at least partly intentional.

## 6.3 ⛔⛔ `R-04` · **My realization layers duplicate a corpus construct — which the corpus already tested and partly rejected**

**F0022 §8** tests a four-viewpoint hypothesis: **Semantic · Structural · Behavioral · Operational**. Its verdict, with ARB provenance 2026-07-11:

> *"the Reference Architecture and this Decision Model are **complementary siblings, not a hierarchy**… Neither depends on the other; both depend on the Standards."*
> ⛔ **Correction 1 — drop the arrows entirely: "viewpoints are PROJECTIONS of one platform, and projections do not flow into each other."**
> ⚠️ **Correction 2 — the mapping is not 1:1; the *structural* slot is CONTESTED.**

> ## ⛔ **I built §13A's five realization layers using FOUR OF THESE EXACT NAMES, with arrows, without searching.**
>
> ⭐ **That is proposing-before-searching — committed by me, in the protocol section meant to enforce searching (`Q53`).**

⚠️ **And it creates a sixth UL collision.** Same names, different referents:

| Name | Corpus meaning | My meaning |
|---|---|---|
| **structural** | the meta-model — element types | does the schema encode it |
| **behavioral** | decision resolution (Decision Model + EEP) | does the implementation enforce it |

**Required action:** ⭐ **rename my layers** — the corpus owns these four names with ARB provenance — **and reconsider the arrows.** My layers do carry a real dependency (nothing is enforced that is not represented), so they may legitimately be ordered where viewpoints are not. ⚠️ **But that must be argued, not assumed.**

## 6.4 ⛔ `R-05` · **My propagation test was WRONG — and the exclusion caused it**

`C-0007` recorded: *"no file dated after the falsification repeats the claim or takes up the correction — the correction propagated to **zero** files."*

⛔ **F0022 and F0023 are dated 2026-08-03 — AFTER F0015 — and I excluded them from the test.**

⭐ **F0023 §3 states the back-edge as established fact:** *"the thin back-edge **(n≈3)** is visible only through them."* **No hedge. Treated as known.**

> ## ⭐ **The correction DID propagate — into the two files I did not read.**

⚠️ **Caveat, stated rather than glossed:** F0023 is one day later, same programme, same author-context. This is **corroboration within a track**, ⛔ **not independent confirmation**. It does **not** settle whether n≈3 is true. But it **falsifies my claim that the correction went nowhere.**

**Effect on `CMP-0001`:** formulation B gains a second in-corpus appearance. The competition remains **OPEN** — the three records are still outside reach.

## 6.5 `R-06` · **Four partial orders** — a structure not yet recovered — `[C]`

F0023 §3 lists among load-bearing ontology elements:

> *"**the four partial orders** | in which direction may change propagate | ⭐ the thin back-edge (n≈3) **is visible only through them**"*

⛔ **The theory has no account of these.** Four partial orders governing change propagation is a **mathematical structure**, and the corpus says the back-edge is visible *only* through it.

**Status:** ⚠️ **RECOVERED AS A POINTER, NOT AS CONTENT.** Requires a targeted search for their definition. ⛔ **Do not construct them** — `§5C.4`.

## 6.6 `R-07` · Ontology elements bound to the decisions they serve — `[C]`

⭐ **Evans' test applied as an admission criterion:** *"what decisions become easier because of this model?"*

| Element | Decision it serves | Proof it is load-bearing |
|---|---|---|
| ⭐⭐ **KNOWLEDGE ≠ ARTIFACT** | `DetermineArtifactLifecycle` | *"the deletion litmus — **can this be deleted without loss of governed knowledge?** — is **UNASKABLE** unless knowledge and its file are different things"* |
| `AUTHORITY-SCOPE` | `DetermineConcern`/`DeterminePlacement` | the mechanized decision reads exactly this |
| `GOVERNANCE-STATUS` | `DeterminePromotionPath` | the ladder's rungs **are** its values |
| `EVIDENCE-STATUS` | the earns/grants gate | *"evidence EARNS, governance GRANTS"* |

⭐ **A genuine theoretical dependency: the ontology's central split is the precondition of the catalog's own procedure.** Ontology and decision model are **load-bearing for each other**.

⛔ **And two elements decide nothing:** `DOMAIN` *(the frozen BC map "was NEVER CONSULTED" — occurrence #11)* and `PURPOSE` *(unratified)*.

## 6.7 `R-08` · `I-5` is lint-enforced — `[C]`

*"**I-5, lint-ENFORCED:** one authoritative artifact per topic + context."*

⭐ **This is `authorities.yaml`'s `single_per_topic: true`** — the same invariant, now traced to a named ontology element **and** to machine enforcement. Links `SI-0007` to a governed rule.

## 6.8 Two implicit decision types — `[C]`

⛔ **`FREEZE`** — exercised **n≥3**, *"each time by declaration, under no named authority, procedure, or reversal condition."*
⛔ **`RETIRE`** — extends nothing; `PM-6` stands open.

> ⭐ **These are the only two recurring decisions that extend nothing in the catalog**, and both carry the operational evidence the model's own stopping rule demands.

⚠️ **Note the reflexivity:** *FREEZE is itself an ungoverned decision type* — and this programme has used it three times.

## 6.9 ⚠️ A discrepancy to carry

| Source | Claim |
|---|---|
| F0012 `G-C2` | CAP-001: **0 executions, 0 decisions changed** |
| F0023 | CAP-001 counts **`decisions-changed-by-tool: 1`** as its success metric |

⚠️ **Unresolved** — is `1` a target or an achievement? Bears directly on the operational-realization question.

---

## 7 · What the exclusion cost

| # | Cost |
|---|---|
| **1** | ⛔ **A better answer to *"what is KnowledgeOS for"*** sat unread for three passes — **ARB-accepted canon**, sharper than my own §1 |
| **2** | ⛔ **A wrong headline finding** — *"the correction propagated to zero files"* was an artifact of the exclusion |
| **3** | ⛔ **A duplicated construct** — my realization layers reuse four corpus names, with arrows the corpus explicitly dropped |
| **4** | A mathematical structure (four partial orders) still unrecovered |
| **5** | The strongest statement of why the ontology matters — the deletion litmus is **unaskable** without `KNOWLEDGE ≠ ARTIFACT` |

> ⭐ **All five came from one error: classifying by document kind.** The corpus names it *"filename thinking wearing a taxonomy"* — and I did it **to the two files that explain what the platform is for**.

---

# 8 ⭐⭐⭐ F0026–F0030 — the richest batch, and it corrects me three times

## 8.1 ⛔ `R-09` · *Knowledge Space* IS defined — my "genuinely absent" was wrong

**F0027 `C-9`, verbatim:**

> ### **"A Knowledge Space is not a folder. It is a DECLARED SCOPE with an owner and a governing constitution."**

**Evidence it cites:** `knowledge-schema.yaml`'s `scope.include: docs/knowledge/**/*.md` — *"a knowledge space is a set of artifacts with a DECLARED BOUNDARY"* · three registered roots · the EKP having its own constitution and owner.

> ⛔ **I searched `docs/knowledge`, `engineering`, `docs/implementation` and recorded: *"no definition exists anywhere… a genuine corpus gap."***
>
> ⭐ **The definition was in `docs/knowledgeos/` itself, two files past my window.**

**This is `ACL-4` exactly** — a claim over a *search scope* presented as a claim over the *corpus*. ⭐ **My refusal to construct a definition was right. My claim that none existed was wrong.**

`T-0022` is no longer semantically ungrounded. ⚠️ **But see 8.5 — its other leg just broke.**

## 8.2 ⛔ `R-10` · The operational-realization question was already answered

**F0030 `PG-1`, verbatim:** *"**0 of 25 capabilities are ENFORCING**"* · §6: *"⛔ **ALL FIVE ARE ADVISORY.** Every one runs only when invoked."*

> ⛔ **I queued this as my next experiment** *(`OT-0002a`, realization layer `INVOKED`)*. **The corpus answers it five files past my window.**

⭐ **Another proposing-before-searching — mine, the second this session.** `Q53` exists to prevent exactly this and did not fire, because I was planning an *experiment* rather than proposing a *formulation*.

⚠️ **Protocol observation, not yet a change:** `Q53`/`Q38` cover **construction**. They do not cover **experiment design**. An experiment should also search first.

## 8.3 ⭐ `R-11` · `B-6` resolved — the CAP-001 discrepancy

| Source | Claim |
|---|---|
| F0012 | 0 executions, 0 decisions changed |
| F0023 | `decisions-changed-by-tool: 1` |
| ⭐ **F0030 `C-13`** | **"1 execution, 1 decision changed"** |

⭐ **Not a contradiction — a time series.** It went 0 → 1 between F0012 and F0030. **`B-6` closes.**

## 8.4 ⭐ `R-12` · The uniqueness is now precisely located

**F0026 §3:** *"the platform's EDGES are almost all standard engineering — what is uniquely unproven is **ONE edge** (`creates→PKS`) and **ONE layer name** (`DP-n`)."* ⭐ *"The distinctive bet of KnowledgeOS is now precisely locatable: **it is R-6**."*

And the loop with its **actor** named — *"guides **PRODUCT ENGINEERING** (the actor — work, not magic)"* — with the structural verdict:

> ⭐ **"The loop's TAIL is demonstrated and its HEAD is not. The loop is broken at exactly ONE place — the generation edge."**

⚠️ **F0026 also records the honesty cost:** broadening R-6 to the composite *"makes it HARDER to prove, not easier."*

## 8.5 ⛔ `R-13` · A THREE-WAY contradiction on Knowledge-Space nesting

| Source | Position |
|---|---|
| **F0014** | ⭐ *"H-3 **(resolved)** — Knowledge Spaces NEST — closed by G-4"* |
| **F0027** | ⚠️ *"`H-3` **KNOWLEDGE SPACE nesting** … containment vs adjacency **unevidenced**"* — listed as still open |
| **F0026** | ⚠️ `R-9` **MEDIUM** — *"nesting is less standardized than bounding"* |

⛔ **All three are dated 2026-08-02/03. The date cannot order them.**

> ⭐ **`T-0022` — *"a deployment IS a nested Knowledge Space"* — rests on H-3 being closed. Two of three sources say it is not.** **`T-0022` is now CONTESTED.**

**New competition `CMP-0006`. Discriminator:** does any artifact evidence one space *containing* another, as opposed to sitting beside it? ✅ **Corpus-findable.**

## 8.6 ⭐ `R-14` · `DP-n` — capabilities are owned by design policies, not domains

**F0027:** *"**DESIGN POLICY (`DP-n`) — the actual anchor of capabilities.** The reviewer was right that capabilities must not float; the repository already anchors them, **and not to a domain**."* Observable in the Catalog's 1:1 `CAP→DP` binding. ⛔ **`ENGINEERING DOMAIN` rejected as owner — no artifact binds a `CAP-n` to a `PD-n`.**

**The chain:** `MISSION → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY → KNOWLEDGE → ARTIFACT`

## 8.7 ⭐ `R-15` · Eleven invariants vs eight implementation choices

⭐ **F0027 §4 is the most directly theory-bearing table in 30 files.** `I-1` knowledge≠artifact · `I-2` one capability protects exactly one invariant · `I-3` declared boundary · `I-5` one authoritative artifact per topic *(lint-enforced)* · `I-6` runtime never owns knowledge · `I-8` **governance precedes automation** · `I-9` status ⟂ authority · `I-10` constitutional principles are void-overriding · `I-11` **restated**: *"Knowledge does not EXECUTE. Capabilities execute."*

> ⭐ *"A future adopter may change every choice and none of the invariants — that is what makes this a platform rather than a repository layout."*

⚠️ **`I-7` — *"a PKS never contains reusable Method"* — is `stated AND BREACHED`.**

## 8.8 ⭐⭐ `R-16` · A RUNNING SYSTEM — and the first real instance of the mechanism

**F0028 is unlike anything in the first 25 files.** Not a document about a system — **records from one**: 10 recommendations issued · 1 decided · time-to-decision **6.5 hours** · `recommendations.jsonl` and `decisions.jsonl` with intra-day timestamps.

⭐ **This is `instruments produce, the authority accepts` actually running** — issuance separate from decision separate from rationale, two-record separation *"held in practice."*

**Two findings bear directly on the theory:**

| | |
|---|---|
| ⭐⭐ **`RD-6` ACTOR PROVENANCE GAP** | *"the decision record's `actor` stamps the git user, but **the decider was the AI at the user's direction** — when AI participates in decisions, the actor model needs to distinguish **who-executed from who-authorized**"* |
| ⭐ **`RD-7` first ANOMALY** | *"A recommendation **CAN currently be decided twice**"* — two identical ACCEPTED records. ⛔ *"do not patch the guard before the semantics are decided"* |

⭐ **`RD-6` is `I-14` (machine confidence ≠ epistemic authority) meeting reality**, and the corpus reaches the same distinction independently: **who-executed vs who-authorized.**

⛔ **And the learning event is `3/5` complete** — *recommendation ✓ decision ✓ rationale ✓ · commit ✗ outcome ✗.*

## 8.9 ⭐ `R-17` · *Survival ≠ sufficiency* — an executed test

**F0029 §2:** the success criterion — *"if PublicDigit disappeared, what survives?"* — was **run, not imagined**, as the MVK bootstrap.

> ⭐⭐ **"Every element survived; the SET failed to bootstrap — verdict FAIL."**

⭐ **A composition result:** element-wise portability does not entail set-wise sufficiency. **The kernel is portable and not yet viable.**

## 8.10 · A third answer to *"what is KnowledgeOS?"*

**F0030 §1:** *"KnowledgeOS is a reusable engineering platform whose **product is METHOD** — the rules, instruments and contracts by which software is engineered — and which **owns no product's knowledge**."*

⚠️ **Three answers now exist, and they are NOT rivals** — they answer different questions:

| Question | Answer | Source |
|---|---|---|
| What is its **product**? | ⭐ **METHOD** | F0030 |
| What does it deliver for **authority decisions**? | ⭐ **DECISION-READINESS** | F0023 (ARB-accepted) |
| What **kind of platform** is it? | governance of engineering knowledge | F0017 *(corpus-declined)* |

⛔ **Recorded as a layered answer, not a competition.** ⚠️ Whether the layering is the corpus's or mine is `[S]`, not `[C]`.
