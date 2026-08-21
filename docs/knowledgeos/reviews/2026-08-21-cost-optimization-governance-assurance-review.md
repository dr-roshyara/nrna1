# Review — **`how_to_optimize_cost.md`**: governance-assurance automation, and **when** each part may be implemented

**Artifact reviewed:** `docs/knowledgeos/brainstorming/how_to_optimize_cost.md` *(1031 lines; lines 1–430 are the AMD6 authoring prompt, already executed at `8307beca`; **lines ~430–1031 are the proposal this review is about**)*
**Class:** Architecture review of a **brainstorming input**. ⛔ **This review decides nothing, commissions nothing, mints no identifier and adopts no model.** It classifies, tests the proposal against the repository, and recommends a sequence.
**Reviewing process, self-declared, NOT attestable** (`INV-ATTR-2`/`G-2`): `claude-code-session:bc1b47ef` — ⚠️ **disclosure: the same process authored AMD6, so the cost evidence cited below is partly evidence about its own work.** **`R-34`/`P-2`: this review accepts nothing.**
**Date:** 2026-08-21 · **Placement derived, not chosen:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**.

---

# 1 · Verdict

| | |
|---|---|
| **Is the diagnosis correct?** | ✅ **Yes, and it is measurable** — §3 quantifies it from the AMD3→AMD6 record rather than asserting it |
| **Is the proposed direction right?** | ✅ **Yes for the deterministic half. ⚠️ Not yet for the routing half** — and the two halves have **different authorities and different timing**, which the document treats as one programme |
| **Can any of it start now?** | ⭐ **YES — one phase, and it needs no new authority at all**, on an accepted governance precedent (§4) |
| **Can the rest start now?** | ⛔ **No.** It is **review-model evolution**, which the **2026-08-01 methodology freeze** covers explicitly, and it has **no second adopter** (`ES-006.1`) |
| **Biggest correction** | 🔴 **The proposal puts the four-layer trace fully in the automated column. Three of the chain's most valuable findings (`RD-1`, `RD-7`, `RD-3·b`) are NOT mechanically detectable** — §6, `X-5`. Automating *verification of a declared trace* is deterministic; **deriving the act list is judgement**, and conflating them would over-promise |
| **Biggest omission** | ⭐ **The cost is not reviewer minutes — it is AMENDMENT CYCLES.** §3.2 |

⛔ **Nothing in this review may be read as adopting assurance classes, a routing model, a gate, or `EKS-06`.** Those need a PO/ARB act, and §7 says exactly which.

---

# 2 · What the proposal gets right, and why it matters

| | |
|---|---|
| ⭐ **The core principle** | *"Automation handles evidence production; humans retain authority."* — **this is `R-34`/`P-2` restated for machines, and it is the correct invariant.** It composes with everything Track 2 established |
| ⭐ **The refusal to automate independence** | *"AI may produce `CONFLICT DETECTED`, never `INDEPENDENCE = TRUE`"* — ⭐ **exactly right, and it is the `C-10`/`C-12` distinction generalised.** An eligibility checker that returns `TRUE` would be manufacturing authority, which is the one thing `G-2`/`R5b` forbids |
| ⭐ **"Batch the work; don't merge the semantics"** | **The sharpest sentence in the document.** It is the model-integrity rule applied to throughput: batching is a scheduling decision, merging is an ontological one |
| ⭐ **The no-findings gate** | The right idea — ⚠️ **with the wrong noun.** §6 `X-4` |
| ✅ **Not adopting *"AI can do routine reviews"*** | ⭐ **The document polices itself here, and correctly.** That restraint is why the rest of it is worth taking seriously |

---

# 3 · The empirical case — **measured from the record, not asserted**

## 3.1 Mechanical findings as a share of each independent review

**Classification rule, stated so it can be disputed:** a finding is **`M` (mechanical)** if a deterministic checker over the artifact could have produced it with no architectural judgement; **`J` (judgement)** otherwise.

| Review | Reviewer | New findings | `M` | Share | The `M` findings |
|---|---|---|---|---|---|
| AMD3 review | `9c908e70` | 14 *(`RC-1`…`RC-11` + `5b`/`7a`/`7b`)* | **4** | **29 %** | `RC-1` *(precondition points forward)* · `RC-7a` *(enumerated 2 of 3)* · `RC-7b` *(rows not in execution order)* · `RC-7` *(dependency table incomplete)* |
| AMD4 review | `870305e0` | 13 *(`RD-1`…`RD-10`, `DI-1`…`DI-3`)* | **6** | **46 %** | `RD-1` · `RD-7` · `RD-9` · **`DI-1`** *(§4.1/§4.2 collisions + 21 references)* · **`DI-2`** *(stale "Phase 2b")* · **`DI-3`** *("SIX" over 8 rows)* |
| AMD5 review | `ccf6c9c7` | 12 distinct | **7** | **58 %** | `RD-7·a` *(criterion 14 cites an undefined step)* · `RD-3·b` *(act with no destination)* · **`DI-4`** *(unlabelled superseded)* · **`DI-5`** *(two enumerations)* · **`DI-6`** *(gate claim vs its own column)* · **`DI-7`** *(`CASE B` vs `CASE β`)* · `DI-1·r` |

> ## 🔴 **The trend is the finding: 29 % → 46 % → 58 %.**
> **As the design stabilised, an increasing share of scarce independent-architecture capacity went to defects a machine could have caught.** ⭐ **The document's diagnosis is therefore not a hunch — it is a measurable trend across three independent reviewers, none of whom was looking for it.**

## 3.2 ⭐ The cost the document under-states: **amendment-cycle amplification**

**A mechanical defect found by an independent reviewer does not cost a review. It costs a whole amendment plus a whole re-review.**

```
DI-1  (heading collisions)     → AMD5's §4 renumbering + 21 reference repoints + a re-audit
DI-4 · DI-5 · DI-6 · DI-7      → AMD6 (4 of its 7 commissioned residuals = 57 %)
                                  + 12 registered commission corrections C-1..C-12
                                  + a mandated 14-point manual pre-delivery check
                                  + a fresh independent review still outstanding
```

**Chain volume, measured today: 4,315 lines of governed artifact · 5 amendment commits · 4 independent reviews · 1 governance review · 12 registered commission corrections in 4 grants — and ⛔ zero lines of implementation. The migration has not executed.**

⇒ ⭐ **The ROI is not *"20 minutes of reviewer time"*. It is *"roughly one amendment cycle per defect class"* — and `DI-4`…`DI-7` show the cycles are not independent: mechanical defects survive amendments and then trigger the next one.**
⚠️ **Honest bound: this is ONE track. `ES-006.1` forbids promoting anything from a single occurrence, and §7 respects that** — it is why only the phase that needs no promotion may start now.

## 3.3 What AMD6 itself demonstrated, in both directions

| | |
|---|---|
| ✅ **For the proposal** | **The AMD6 author ran the mechanical checks BY HAND** — a table-column validator, heading uniqueness, `§4.x` reference resolution, a corpus grant grep, a forbidden-term grep — because the commission mandated 14 manual pre-delivery checks. ⭐ **The check list already exists as an authorized obligation; the proposal only changes who performs it** |
| ✅ **For the proposal** | **A hand-run check caught a real self-inflicted defect:** the draft reproduced the malformed term `C-6` forbids while claiming it appeared nowhere. **A terminology check catches that class deterministically; a human nearly did not** |
| ⚠️ **Against over-promising** | **AMD6 also CREATED new mechanical surface** — `§4.7`'s `P5·1`…`P7·3` operator IDs, criteria 16–20, and `§0.6.5`'s declared trace table. ⭐ **Per the Deferred Register's own criterion — *"gated on their OBJECTS existing"* — those objects now exist, so the checks that read them are no longer speculative** |

---

# 4 · ⭐ The governance answer to **"when"** — it is already ruled, in both directions

**The proposal is not one programme. It is two, and the estate has already ruled on both classes.**

| Half | Governing rule | Consequence for timing |
|---|---|---|
| ⭐ **Deterministic assurance** *(a read-only checker that produces a report)* | ⭐ **`KnowledgeOS_Deferred_Architecture_Register`, correction ACCEPTED 2026-08-04: *"automation of deterministic work is not speculative architecture"* — `laravel new` never needed a second adopter, and the prior second-adopter gating of `init` was recorded as OVER-APPLIED and revised** | ✅ **May start NOW.** It changes **no rule, no role, no gate, no artifact**; it only produces evidence. **The freeze does not reach it** |
| 🔴 **Assurance classes · risk routing · gates · role-model change** | ⛔ **`.claude/CLAUDE.md`: *"THE METHODOLOGY IS FROZEN (2026-08-01). No protocol refinement · no review-model evolution · no documentation-architecture evolution · no KnowledgeOS proposals — unless PublicDigit implementation exposes a genuine deficiency."*** | ⛔ **Must NOT start.** The deficiency exposed here is in **KnowledgeOS governance execution, not PublicDigit implementation**, so the freeze's exception does **not** fire. ⇒ **backlog item + a PO/ARB act** |

> ## ⭐ **That split IS the answer to "how and when", and neither half required a new rule to reach it.**
> ⚠️ **And a second timing constraint, which is the more important one in practice: ⛔ do not change the review model while a review is pending on it.** **AMD6 is unreviewed. `OPEN-M6` (four amendments) and `OPEN-M7` are outstanding. Altering assurance routing now would mean the artifact under review was produced under one model and judged under another** — the same class of error as an author reviewing their own work.
> ⇒ ⭐ **Phase 0 is the ONLY phase that can run before AMD6 clears its independent review, precisely because it cannot touch the chain in flight.**

---

# 5 · `ES-005.4` canonical discovery — ⛔ **do not build nine engines; extend three scripts**

**The proposal names nine new components** *(Mechanical Gate Engine · Provenance Checker · Reference Integrity Engine · Role Eligibility Resolver · Review Routing Engine · Exception Queue · Evidence Pack Generator · Assurance Classification · Assurance Pipeline)*. **Searched first, per `ES-005.4` — consume or extend what exists, never create a second:**

| Proposed component | ⭐ Existing home | Verified |
|---|---|---|
| **Mechanical Gate Engine** | ⭐ **`scripts/knowledge-lint.php`** — 343 lines, *"PHPStan for knowledge"*, schema-driven, `--strict`/`--json` already present | ⛔ **hard-scoped to `docs/knowledge/` (`$knowledgeDir` at line 33) and NEVER sees `docs/knowledgeos/`.** ⭐ **THAT SINGLE SCOPE GAP IS WHY THE ENTIRE TRACK 2 CORPUS HAD ZERO MECHANICAL COVERAGE** |
| **Reference Integrity Engine** | ⭐ **`scripts/link-check.php`** — 221 lines, with an evidence-and-confidence model *(only ≥99 may be applied)* | covers **file** links; ⛔ **does not resolve intra-document `§`-anchors**, which is what `DI-1` was |
| **Assurance Pipeline** | ⭐ **`scripts/verify.sh`** — a gate orchestrator with per-gate `fail`/`warn` severity and timing | ✅ **the pipeline pattern already exists and is proven on the UI/security gates** |
| **Provenance / identity checker** | ⭐ **`scripts/identifier-check.php`** + `scripts/lib/EngineeringKnowledge/Capabilities/IdentifierIntegrity/{Domain,Application,Infrastructure,Tests}` | ⭐ **a governed capability with a real DDD structure and a contract README already exists — this is the pattern to extend, not to re-invent** |
| **Assurance Classification** | ⭐ **the operating loop's Phase-6 impact classification** *(Strategic Architecture · Tactical Architecture · Governance · Engineering · Repository · Documentation · Operational · PKS Observation · KnowledgeOS Candidate)* | ⛔ **a second taxonomy would be the *"new dimension"* the model-integrity rule refuses unless orthogonal · necessary · sufficient — A–E is none of the three** |
| **Where the problem is recorded** | ⭐ **`docs/knowledgeos/backlog/` (`EKS-01`…`EKS-05`)**, whose index states *"a backlog item records a problem and a candidate requirement; it commissions nothing"* | ⭐ **`EKS-02` is already *"Subject-Derived Placement, MACHINE-ENFORCEABLE"* and `EKS-05` is already classed *"FUTURE ARCHITECTURE EXPLORATION"* — both the home and the precedent exist** |
| **Exception Queue · Evidence Pack Generator** | ⛔ **no object exists yet** | ⚠️ **gated on their objects existing** *(the Deferred Register's own criterion)* |

⇒ ⭐ **Seven of the nine proposed components already have a governed home. The correct first act is a `--root`/profile extension of `knowledge-lint`, not a new engine.**
⚠️ **One measured obstacle, recorded not solved:** `knowledge-lint` requires **frontmatter + a knowledge card**; **no Track 2 artifact has either.** ⇒ the extension must be a **profile that checks structure WITHOUT requiring frontmatter** — because *requiring* frontmatter on governed architecture artifacts **is** a documentation-architecture change, and that is frozen.

---

# 6 · Corrections I would make to the proposal

| | Correction |
|---|---|
| ⭐ **`X-1`** | **Split the document into two artifacts, because they have different authorities.** *(a)* **Deterministic assurance** — engineering work, startable now, no promotion needed. *(b)* **Assurance classes / routing / gates** — review-model evolution, frozen, PO/ARB. ⛔ **Presenting them as one programme is what makes the whole thing look unauthorizable** |
| ⭐ **`X-2`** | ⛔ **Do not build the nine engines.** Extend `knowledge-lint` (profile + root), `link-check` (intra-document anchors) and the `IdentifierIntegrity` capability pattern; orchestrate with `verify.sh`. **`ES-005.4`** |
| ⭐ **`X-3`** | **Assurance classes A–E are a ROUTING model, and routing is AUTHORITY.** Class A/B route work *away from* human reviewers — the exact decision `C-10`/`C-12` needed three grants to settle. ⇒ ⭐ **let the classifier REPORT a class long before it ROUTES on one.** *"Report first, route later, on evidence"* costs nothing and needs no rule change |
| ⭐ **`X-4`** | **The *"no-findings gate"* is right, and *"gate"* is the wrong noun.** As a **gate** it can block a handoff ⇒ it is a governance object ⇒ frozen. As an **entry condition the AUTHOR runs voluntarily** it needs no authority and delivers most of the value immediately. ⭐ **AMD6's own mandated 14-point pre-delivery check is already exactly this obligation, already authorized — automating it changes only who performs it** |
| 🔴 ⭐ **`X-5`** | **The four-layer trace is over-claimed, and this is the correction that matters most.** **Deterministic: VERIFYING that every ID in an author-declared trace table resolves** *(would have caught `DI-5` and criterion 14's dangling *"step 5"*)*. ⛔ **NOT deterministic: DERIVING the act list.** ⇒ ⭐ **a checker reduces *dangling-reference* defects to zero and reduces *missing-act* defects (`RD-1`, `RD-7`, `RD-3·b` — the *"act named in a specification and carried by no phase"* class, THREE separate amendments) by NOTHING.** **Only a human notices an act the author never declared.** ⛔ **Say so, or the pipeline will be trusted for the class it cannot see** |
| ⚠️ **`X-6`** | **Do not call it a *"Risk Classifier"*.** It classifies **change kind**, and a vocabulary for that already exists (`X-2`'s Phase-6 list) |
| ⚠️ **`X-7`** | **Sharpen the cost model to cycles, not minutes (§3.2).** The current framing under-sells the case by roughly the cost of two amendments |
| ⚠️ **`X-8`** | ⛔ **`EKS-06` must not be minted casually.** Measured: `php scripts/identifier-check.php EKS-06` → **`INCONCLUSIVE` — *"series `EKS` is not a governed register; absence of evidence is not PASS"***, and `PMR-10` requires a collision check **before** minting. ⇒ **the ID needs a human act, and the `EKS` index arguably needs adding to the governed register map — itself a separate, small governance question, not a side effect of this review** |

---

# 7 · ⭐ Recommended implementation sequence — **how, and when**

> ⛔ **Each phase names the authority it requires. A phase must not start because the previous one succeeded — it starts when its ENTRY condition holds AND its authority exists.**

## **Phase 0 — Mechanical back-test. ⭐ NOW. Authority required: NONE.**

**Why no authority is needed:** it is a **read-only checker** that changes no rule, role, gate or artifact, and it is covered by the **accepted 2026-08-04 correction** (*"automation of deterministic work is not speculative architecture"*). ⛔ **It produces a report. It blocks nothing.**

**How — extend, don't create:**

| Check | Host | ⭐ Back-tests against |
|---|---|---|
| heading/identifier uniqueness within a document | `knowledge-lint` *(new no-frontmatter profile)* | **`DI-1`** |
| monotonic section ordering | same | `RC-7b` · `DI-1` |
| every intra-document `§x.y` reference resolves to exactly one heading | `link-check` *(extend to anchors)* | **`DI-1`** *(21 references)* |
| stale-token scan against a per-document current vocabulary | `knowledge-lint` profile | **`DI-2`** *("Phase 2b")* |
| enumeration-vs-content agreement *(a stated count vs the rows it covers)* | `knowledge-lint` | **`DI-3` · `DI-6`** *("SIX" over 8 rows; "3 gate execution" vs its own Gates column)* |
| table column-count consistency | `knowledge-lint` | *(hit repeatedly by hand during AMD6)* |
| every ID in a declared trace table resolves *(section · criterion · operator ID)* | `knowledge-lint` | **`DI-5`** *(criterion 14 cited an undefined step)* |
| confusable-identifier detection | `knowledge-lint` | **`DI-7`** *(`CASE B` vs `CASE β`)* |
| unlabelled-superseded heuristic → **WARN only** | `knowledge-lint` | **`DI-4`** |
| cited grant / aggregate IDs exist in the corpus *(read-only)* | small reader, `IdentifierIntegrity` pattern | **`C-9`** · `OPEN-M6` verification |

> ## ⭐ **THE EXIT CRITERION IS A FALSIFICATION TEST, AND IT IS AVAILABLE TODAY:**
> **run the checker against the artifact AS IT STOOD at `bb1708b7` (AMD3), `0a2fa71d` (AMD4) and `7d3abc59` (AMD5), and require it to REDISCOVER the mechanical findings the three independent reviews raised — 17 of them, by commit.**
> ⛔ **If it does not rediscover them, the proposal is UNPROVEN and Phase 1 must not start.** ⭐ **This is the cheapest decisive experiment in the whole programme, and no other phase can be evaluated without it.**

**Effort:** one authorized engineering slice — ~2 script extensions plus the back-test harness; each individual check is small. **Deliverable:** the checker, its RED/GREEN evidence, and a back-test report. **⛔ Not in scope:** any gate, any routing, any frontmatter requirement, any change to a governed artifact.

## **Phase 1 — Author-side adoption. After Phase 0 back-tests GREEN. Authority: the existing pre-delivery obligation.**

**The author runs the checker before handoff and attaches the report.** ⭐ **This automates an obligation the AMD6 commission already imposed (its 14-point pre-delivery verification) — so it creates no new gate and needs no new act.** ⛔ **Warn-only. It must not block a handoff.**
**Exit criterion:** ⭐ **the next independent review raises FEWER mechanical findings than `ccf6c9c7`'s 7-of-12.** **That is the operational evidence `ES-006.1` requires, and it is the first evidence of a SECOND occurrence.**

## **Phase 2 — Report becomes required EVIDENCE. ⛔ Authority: a PO/ARB act. ⛔ Not before AMD6 is ACCEPTED.**

**The assurance report becomes a required attachment to a handoff — evidence, not a gate.** The reviewer reads exceptions rather than rediscovering facts.
⛔ **Entry: AMD6 accepted, `OPEN-M6` registered.** *(§4: do not change the model while a review is pending on it.)* ⛔ **Still no routing. No review is skipped or shortened.**

## **Phase 3 — Assurance classification, ADVISORY. ⛔ Authority: PO/ARB + a SECOND ADOPTER (`ES-006.1`).**

**The classifier REPORTS a class; ⛔ nothing routes on it.** Classes come from the existing Phase-6 vocabulary (`X-6`), not a new A–E taxonomy.
**Entry:** a second track has used Phase 0/1 and produced its own evidence. ⛔ **One track is research, not a pattern.**

## **Phase 4 — Routing. ⛔ Authority: an ARB decision / ADR. ⛔ NOT on current evidence.**

**Only here may a class change WHO reviews WHAT.** ⛔ **And never for independence, acceptance, authority, capability establishment or ownership** — those stay human under every phase.
**Entry:** operational evidence from **≥ 2 tracks** that the classifier's class matched the reviewer's judgement, **with the disagreements recorded**.

## Recorded, not started

⭐ **I recommend `EKS-06 — Governance Assurance & Role Execution Scaling` be created as a BACKLOG item** *(problem + candidate requirement; commissions nothing)*, carrying: the §3 measurement, the `X-1`…`X-8` corrections, the Phase 0–4 sequence, and the candidate invariant below.
⛔ **I have NOT created it, and the reason is the rule this review is about:** the `EKS` series is **not a governed register** (`identifier-check` → `INCONCLUSIVE`), `PMR-10` requires a pre-mint collision check, and the freeze bars *"KnowledgeOS proposals"*. ⭐ **Minting an ID into an ungoverned series during a freeze, on my own initiative, is exactly the quiet accretion the estate's own rule forbids — *"never let a ticket or an observation silently become architecture"*.** ⇒ **it needs one human act, and the content is ready.**

---

# 8 · The authority boundary — what must never be automated

```
AI / automation MAY                          HUMAN RETAINS, under every phase
──────────────────────────────               ─────────────────────────────────
produce mechanical evidence                  architecture judgement
extract and resolve references               independence determination
detect contradictions and anomalies           acceptance
detect POSSIBLE role conflicts               authority
first-pass semantic analysis                 capability establishment
report a candidate classification            ownership
                                             exception acceptance
```

⭐ **The candidate invariant, and it is the one line worth promoting eventually:**

> ## **Automation may reduce the COST of assurance and must never manufacture AUTHORITY.**

✅ **It composes with every distinction Track 2 established** — *recording ≠ asserting · evidence ≠ proof · reference ≠ ownership · author ≠ independent reviewer · self-check ≠ independent assurance · execution ≠ governance* — ⭐ **and it is the machine-facing case of `G-2`/`R5b`'s *"a grant registers a human act BY REFERENCE — the record never manufactures authority"*.**
⛔ **It is a CANDIDATE. One track's evidence. `ES-006.1` bars promotion from a single occurrence, and this review does not promote it.**

---

# 9 · Risks

| | Risk | Mitigation |
|---|---|---|
| 🔴 | **False assurance** — a GREEN mechanical report reads as *"the design is sound"* | ⭐ **the report must state what it did NOT check, positively.** `X-5`'s missing-act blindness is the specific case, and it is the class that produced `RD-1`, `RD-7` and `RD-3·b` |
| 🔴 | **Scope creep from checker to gate** — a warn-only tool quietly becomes a blocker | **Phases 2–4 each name their authority.** A gate without a PO/ARB act is an unauthorized governance object |
| ⚠️ | **The checker becomes the spec** — authors write to satisfy it | keep it **descriptive and warn-only** through Phase 1; ⛔ never let a check invent a required artifact shape *(the frontmatter trap in §5)* |
| ⚠️ | **Displacement** — automation work competes with the stated priority *(PublicDigit: WP-7C → WP-8 → EPIC-005)* | ⭐ **Phase 0 is one slice and is the only phase recommended now.** ⛔ **If it cannot be afforded, defer the whole thing to `EKS-06` and lose nothing** |
| ⚠️ | **Reviewer disclosure** | ⚠️ **this review's cost evidence includes the reviewer's own AMD6 work.** §3.1's AMD3/AMD4/AMD5 measurements are independent of it; §3.3 is not |

---

# 10 · Non-decisions

⛔ **This review does NOT:** adopt any model · create `EKS-06` · mint any identifier · define an assurance class · create or modify a gate · change any review or routing rule · change `knowledge-lint`, `link-check`, `verify.sh` or any script · decide `OPEN-M1`/`M2`/`M4`/`M5`/`M6`/`M7` · reopen `B′`, `R-CONFLICT`, `OPEN-M3` or `INV-ORDER` · touch the AMD6 chain · review or accept AMD6 · lift, narrow or reinterpret the **2026-08-01 methodology freeze** — ⭐ **which only PO/ARB may do.**

**What I recommend, in one line:** ⭐ **authorize Phase 0's back-test as a single engineering slice, record everything else as `EKS-06`, and let the AMD6 review chain finish undisturbed.**

**Traceability:** `docs/knowledgeos/brainstorming/how_to_optimize_cost.md` *(the reviewed input)* · the independent AMD3/AMD4/AMD5 architecture reviews *(`9c908e70`, `870305e0`, `ccf6c9c7`)* — finding sets enumerated mechanically for §3.1 · AMD6 at `8307beca` · **`KnowledgeOS_Deferred_Architecture_Register`, the ACCEPTED 2026-08-04 correction *"automation of deterministic work is not speculative architecture"*** · `.claude/CLAUDE.md` — the **2026-08-01 methodology freeze** and the operating loop's Phase-6 classification · `ES-005.4` · `ES-006.1` · `ES-002.1`/`ES-002.2` · `PMR-10` · `G-2`/`R5a`/`R5b` · `R-34`/`P-2` · `INV-ATTR-2` · **evidence read directly:** `scripts/knowledge-lint.php:8,32–33,58` · `scripts/link-check.php:1–21` · `scripts/identifier-check.php:1–26` + `scripts/lib/EngineeringKnowledge/Capabilities/` · `scripts/verify.sh:1–16` · `scripts/doc-placement.php` *(exit 0)* · `docs/knowledgeos/backlog/00_index.md` + `EKS-02` + `EKS-05` · `php scripts/identifier-check.php EKS-06` → **`INCONCLUSIVE`** · **chain volume measured: 4,315 lines · 5 amendment commits · 4 independent reviews.**

**REVIEW DELIVERED · STOPPING.** ⛔ **NOTHING IS ADOPTED, COMMISSIONED OR IMPLEMENTED BY THIS ACT.**

---

# 11 · Addendum *(added 2026-08-21, additive — ⛔ no verdict in §1–10 is changed)*

**Trigger:** the Decision Authority's response to §1–10, which **accepted the two-authority-class split**, restated `X-5` as a formulation, and named the implementation target. **Two things are recorded here: the DA's refinement, and a canonical-discovery finding that arrived AFTER §5 and makes Phase 0 cheaper than §7 described.**

## 11.1 ⭐ The DA's refinement of `X-5` — adopted into this review's language

> ```
> Mechanical Assurance    proves DECLARED STRUCTURE
> Architecture Review     discovers UNDECLARED ARCHITECTURAL CONTENT
> ```

⭐ **This is a better formulation than `X-5`'s and it should be the platform's standing sentence**, because it states the boundary as a *division of labour* rather than as a *limitation*: the checker is not a weak reviewer, it is a different instrument. ⛔ **It also forbids the sentence the platform must never emit — *"mechanical assurance proves architectural completeness"*.**
⚠️ **Attribution, kept straight: the formulation is the DA's, not this review's.** `X-5` established the boundary; the DA named it.

## 11.2 🔴 ⭐ Canonical discovery, completed — **§5 searched the SCRIPTS and missed the CATALOGUE. The capability layer already governs this space.**

**`docs/implementation/PKS_Phase_III_Capability_Catalog.md` (225 lines) catalogues six engineering capabilities with domain policies `DP-1`…`DP-6` and a lifecycle** *(Candidate → Designed → Realized)*:

| CAP | Rule *(`DP-n`)* | Status | Realization |
|---|---|---|---|
| **CAP-001** Identifier Integrity | **`DP-1`** every identifier is unique within its **register(ns)**, checked before minting | DESIGNED | `identifier-check.php` |
| CAP-002 Projection Integrity | `DP-2` | DEFERRED | — |
| ⭐ **CAP-003 Vocabulary Integrity** | ⭐ **`DP-3`** a governed term carries **one meaning per context**; homonyms are **qualified** | ⭐ **CANDIDATE** | ⛔ **none** |
| ⭐ **CAP-004 Reference Integrity** | ⭐ **`DP-4`** a reference **resolves to an existing target**, or is classified as evidence | ✅ **REALIZED** | `link-check.php` · `doc-placement.php` |
| CAP-005 Assessment-Record Integrity | `DP-5` | Candidate | — |
| CAP-006 Knowledge-Card Integrity | `DP-6` | ✅ REALIZED | `knowledge-lint.php` |

⭐ **Mapping §7's six back-test targets onto capabilities that ALREADY EXIST:**

| Target | ⭐ Existing capability | Fit |
|---|---|---|
| **`DI-1`** duplicate `## 4.1` / `## 4.2` headings | ⭐ **CAP-001 (`DP-1`)** | ⭐ **exact, once the *register* is understood to be THE DOCUMENT** — a heading identifier colliding inside its own document is a `DP-1` violation |
| **`DI-1`** 21 unresolved `§4.x` references · **`DI-5`** criterion 14 citing an undefined step | ⭐ **CAP-004 (`DP-4`)** | ⭐ **exact** — *"a reference resolves to an existing target"*. ⚠️ **Its realization covers FILE links only; intra-document anchors are the gap** |
| **`DI-2`** stale *"Phase 2b"* · **`DI-7`** `CASE B` vs `CASE β` | ⭐ **CAP-003 (`DP-3`)** | ⭐ **exact** — a term carrying two meanings, and an unqualified near-homonym |
| **`DI-4`** two competing CURRENT definitions of one disposition | ⚠️ **CAP-003 (`DP-3`), candidate mapping** | *"one meaning per context"* plausibly covers it — **to be confirmed, not assumed** |
| 🔴 **`DI-3` · `DI-6`** a stated COUNT contradicting the rows it counts | ⛔ **NO CATALOGUED CAPABILITY** | ⭐ **Named and returned to governance; ⛔ nothing invented** — per the operating loop's Phase 3: *if the classification genuinely has no home, name what is missing and invent nothing*. **Working label only, not minted: *"self-consistency of an enumeration and the content it enumerates"*** |

> ## ⭐ **Consequence — Phase 0 is cheaper AND better-governed than §7 said:**
> **Phase 0 is not *"build a checker"*. It is: ⭐ REALIZE `CAP-003` — which the catalogue itself lists as the pending choice of *"capability 2"* — and ⭐ EXTEND `CAP-004`'s realization to intra-document references.** ⛔ **No new capability · no new `CAP` identifier · no new register · no new domain policy.** ⭐ **Which also removes §7's `X-8` obstacle entirely: nothing needs minting, so `identifier-check`'s `INCONCLUSIVE` on an ungoverned series never arises.**
> ⭐ **And the evidence shape matches the one the catalogue ALREADY ACCEPTED for CAP-001** — *"a GOVERNED rule, adopted expressly to prevent this, failed to prevent it, because it had NO EXECUTABLE CHECK."* **§0.4.4's canonical-document rule is exactly such a rule, and `DI-4` violated it two amendments after it was adopted.**
> ⭐ **Bonus, recorded because the catalogue is waiting on it: realizing a second capability TESTS the catalogue's open hypothesis `H-CAT-1`** *("a parent Validation Capability abstraction is justified — refuted if the six siblings share no common contract once two are realized")*.

## 11.3 The infrastructure is already wired — measured

```
composer.json      PSR-4  EngineeringKnowledge\  →  scripts/lib/EngineeringKnowledge/
phpunit.xml:44-45  <testsuite name="EngineeringKnowledge">  →  ./scripts/lib/EngineeringKnowledge
CAP-001 layout     Domain/ · Application/ · Infrastructure/ · Tests/{Domain,Application,Infrastructure} · README.md
Shared\Domain      Assessment · Verdict  (already used by CAP-001's domain tests)
```

⇒ ⭐ **the TDD path needs no new harness: `--testsuite=EngineeringKnowledge` already runs capability tests.** ⛔ **This does not make the work authorized; it makes it small.**

## 11.4 ⚠️ One observation on the DA's sequence diagram, resolved rather than left implicit

**The DA's diagram places *"separately authorize Phase 0"* as a branch of the PO/ARB step in the AMD6 chain, while §4 found Phase 0 needs no new authority.** ⭐ **Both are satisfiable, and an existing precedent settles it: `R-46` — EP-01 plan approval is the DECISION AUTHORITY's act, not the ARB's** *(cited in `docs/plans/20260802-0015-…-plan.md`, the CAP-001 precedent)*. ⇒ **Phase 0 proceeds on a DA plan approval and does NOT queue behind AMD6's PO/ARB acceptance; what stays queued behind that acceptance is Phase 2, exactly as §7 said.**
⛔ **This review does not decide the sequence — it records that the estate already has a rule for it, and that the rule makes the DA's constraint and §4's finding compatible rather than conflicting.**

**Addendum traceability:** the DA's response of 2026-08-21 *(the `X-5` formulation and the named implementation target)* · `docs/implementation/PKS_Phase_III_Capability_Catalog.md` §4 *(the `CAP-001`…`CAP-006` table)*, §7 *(`DP-1`…`DP-6`)*, §0 *(the homonym cases)*, §8 *(`H-CAT-1`; *"choose capability 2 from the catalogue"*)* · `docs/implementation/PKS_Phase_III_Engineering_Knowledge_Model.md:107,113` · `composer.json` PSR-4 · `phpunit.xml:44–45` · `scripts/lib/EngineeringKnowledge/Capabilities/IdentifierIntegrity/**` · `R-46` via `docs/plans/20260802-0015-pks-identifier-validation-capability-plan.md`.
