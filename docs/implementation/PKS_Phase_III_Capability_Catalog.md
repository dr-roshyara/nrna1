# PKS Phase III — Engineering Capability Catalog

| | |
|---|---|
| **Kind** | **ENGINEERING CAPABILITY CATALOG** — the Phase III engineering roadmap. ***Creates no architecture, no bounded context, no governance. Lists capabilities and their evidence.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Status** | **DELIVERED — awaiting review.** No capability herein is authorized for implementation |
| **Commission** | PA, 2026-08-02 — *"Establish the Capability Lifecycle; build the Capability Catalog; elevate the abstraction one level… **Identifier Validation is an implementation of Identifier Integrity, not the capability itself**"* |
| **Inputs** | Stage 1A validation report · Stage 1A.5 engineering translation · EP-01 plan `20260802-0015` · AD-1 · M4 · M6 · PMR-10 |
| **Placement** | ⚠️ **INSTRUCTED, not derived.** Written to `docs/implementation/` per commission. **`php scripts/doc-placement.php --scope=product-specific --domain=pks --maturity=qualified` → `docs/pks` (exit 0).** *Deviation recorded rather than silently taken; this becomes the 92nd `PKS_*` file in the mixed folder the roots ADR exists to unmix* |
| **PMR-10** | ✅ **PERFORMED — and it FIRED THREE TIMES. See §0** |
| **Naming reconciled** | ⚠️ **CAP-001 was "Identity Integrity" in this catalog's first issue; the implementation commission (PA, 2026-08-02) names it *"Engineering Knowledge Validation – CAP-001: **Identifier Integrity**"*. **Renamed throughout to ONE name** — two names for one capability is the drift PMR-10 guards, and it would be least excusable in this capability |

**Epistemic classes: Observed · Derived · Hypothesis · Recommendation · Open Question.** *Not mixed.*

---

## 0. ⭐ PMR-10 fired while minting this catalog's own labels

**The rule this catalog's first capability enforces caught three collisions in the catalog's own vocabulary — before anything was minted.** *Recorded first, because it is the strongest available evidence for CAP-001 and it was produced by following the process rather than by arguing for it.*

| Proposed label | Collision found | Sense | Disposition |
|---|---|---|---|
| **Capability Catalog** | `Round38C-14C_Leadership_Decision.md` — *"**Governance** Capability Catalog — candidate capabilities to EXPLORE (not adopt, not software)"* | ⚠️ **HOMONYM** — same construct, **different domain** (PublicDigit governance vs PKS engineering) | ✅ **QUALIFIED** — this artifact is the ***Engineering* Capability Catalog** throughout. *(And the prior use is a precedent worth noting: it too listed candidates **to explore, not adopt**)* |
| **Evidence Integrity** | `docs/architecture/contexts/EvidenceContext_00.md` — an **"Evidence Integrity Service"** in PublicDigit's Evidence bounded context | ⛔ **HOMONYM — a different domain entirely** (election evidence, product-side) | ⛔ **REJECTED as a capability name.** Renamed **CAP-005 Assessment-Record Integrity** |
| **Link Integrity** | `2026-08-01-documentation-workstream-closure-record.md` — *"Documentation Placement & **Link Integrity** — Workstream Closure Record"* | ⚠️ **SAME SENSE, different kind** — it names a **closed workstream**, not a capability | ✅ **QUALIFIED — CAP-004 Reference Integrity**, with the workstream cited as its origin |
| CAP-001..006 · Identifier Integrity · Projection Integrity · Vocabulary Integrity | — | — | ✅ **FREE** |

> ### **Observed: three of eight proposed labels collided. Two were homonyms across domains; one crossed a kind boundary.** ***A catalog of capabilities that had not run PMR-10 would have shipped all three.***

---

## 0A. ⛔ THE THIRD PMR-10 ESCAPE HAS OCCURRED — recorded 2026-08-02

**PMR-10 was adopted on two escapes, with the note that *"the two existing collisions CANNOT be cured, since identifier stability forbids renaming — **adoption prevents the third**, it does not repair the first two."***

> ### ⛔ **It did not prevent the third. The third has happened.**

| Fact | Evidence |
|---|---|
| **R-65..R-71 were in circulation as *approved-in-substance, unminted* governance conclusions**, cited across ≥8 documents | R-65 *repository-is-a-workspace* · R-66 *documentation-by-DOMAIN* · R-67 *`engineering/` = cross-product scope* · R-68 *ES-005 resolves by rule* · R-69 *R-39 is the precedent* · **R-70 the Artifact Classification Model** · **R-71 Placement Is Derived** |
| ⛔ **R-65 has now been minted as *SLICE 7C AUTHORIZED*** | rulings register, 2026-08-01 |
| ⛔ **R-66 has now been minted as *SLICE 7C ACCEPTED*** | rulings register, 2026-08-02 |
| **Neither can be cured** | *identifier stability forbids renaming* — PMR-10's own adoption note |

**Observed: R-70 and R-71 are cited BY NUMBER in the documentation-roots ADR and the classification records. The series has now advanced past them, so every such citation is ambiguous on its face.**

> ### ⭐ **Derived — and this is now CAP-001's decisive evidence: a GOVERNED rule, adopted expressly to prevent this, failed to prevent it, because it had NO EXECUTABLE CHECK.**
>
> ***The gap CAP-001 addresses is no longer hypothetical or historical. It is live, dated, and in the register.***

⚠️ **Recorded, NOT repaired.** *Renaming is forbidden; the disposition of the two damaged citations is the **Authority's**, and it is added to §8's open questions.*

---

## 1. Executive Summary

**This catalog elevates the engineering abstraction one level, exactly as commissioned.**

| Before | After |
|---|---|
| *"Build an Identifier Validator"* | **CAP-001 Identifier Integrity** — a stable capability. **`identifier-check.php` is its FIRST REALIZATION, not the capability** |

**Six capabilities are catalogued. One is designed. Five are candidates. None is authorized.**

> ### **Derived: the capability must survive its implementations.** *Today Identifier Integrity validates identifiers. Tomorrow it may validate aliases, reservations or namespaces. **The capability does not change when the script does.***

⚠️ **Recommendation, stated against the grain of the commission:** the commission's Issue 5 proposes a reusable **"Validation Capability"** specialized into six children. **This catalog does NOT assert that abstraction.** *Only one capability has escaped-defect evidence; generalizing a parent from one evidenced child is the abstraction-before-second-consumer error the programme has refused four times.* **The six are listed as siblings; the parent abstraction is recorded as `H-CAT-1` (§8), refutable and unadopted.**

---

## 2. DDD principles this catalog observes

| Principle | Consequence here |
|---|---|
| **Ubiquitous Language comes first** | §6 defines the vocabulary **before** any implementation |
| **Policies are reusable; implementations change** | §7 inserts the Domain Policy layer between governed rule and capability |
| **Capabilities are stable; implementations are not** | a capability is named for its **responsibility**, never for its script |
| **Implementation realizes; it never redefines** | ⛔ no capability here creates a bounded context, ownership, authority, lifecycle or new UL term |
| ⭐ **A capability gap is not a boundary** *(M6 §7.5)* | a catalogued capability asserts **no** boundary on the context map |
| **Reuse before create** *(R-36)* | CAP-004 and CAP-006 are recorded as **already realized** |

---

## 3. The Capability Lifecycle

```
Strategic Discovery  (FROZEN)
        ↓
Tactical Capability Discovery      → executed in EP-01 plan 20260802-0015 §Phase 0
        ↓
ENGINEERING CAPABILITY CATALOG     → this artifact
        ↓
Capability Design                  → §5 for CAP-001
        ↓
Execution Plan (EP-01)             → exists for CAP-001; awaiting approval
        ↓
Implementation                     → ⛔ unauthorized
        ↓
Operational Evidence
        ↓
Next capability                    → chosen from this catalog, never invented
```

**Status vocabulary — defined here because an undefined status is how a roadmap starts asserting things:**

| Status | Means |
|---|---|
| **Candidate** | catalogued; evidence not yet sufficient to design |
| **Designed** | tactical boundary defined; **implementation NOT authorized** |
| **Realized** | an implementation exists and operates |
| **Deferred** | evidence exists; deliberately not next, with a stated reason |
| **Rejected** | evaluated and refused, with the rule that refused it |

⛔ **"Active" is deliberately NOT in this vocabulary.** *The commission's example marked Identifier Integrity "Active"; nothing operates and nothing is authorized, so recording "Active" would assert an unoccurred state (**AIP-10**).*

---

## 4. The Engineering Capability Catalog

| ID | Capability | Definition (responsibility) | Policy | Status | Evidence | Realizations |
|---|---|---|---|---|---|---|
| **CAP-001** | ⭐ **Identifier Integrity** | every identifier is unique within its **register(ns)** | **DP-1** | **DESIGNED** ⛔ *unauthorized* | **PMR-10 GOVERNED** · ⛔ **§0A — THE THIRD ESCAPE, REALIZED 2026-08-02** · C-1..C-4 · M4's *OQ- overload* + *R-nn cross-kind* · **§0's three label collisions** | *(planned)* `identifier-check.php` |
| **CAP-002** | **Projection Integrity** | every projection regenerates from its sources and is cited as authority by nothing | **DP-2** | **DEFERRED** | **AD-1**: *"its correctness is verifiable structurally"* · guide steps **6/31** conform | — |
| **CAP-003** | **Vocabulary Integrity** | a governed term carries one meaning per context; homonyms are qualified | **DP-3** | ✅ **REALIZED** *(2026-08-21 — Track-2 Phase-0, back-tested)* | **F-BCP-4** *register* 3-sense · **OQ-PKS-11** *Qualification* · **§0's homonyms** · `constitutional` ×6 · ⭐ **back-test: AMD4 `DI-2` ×4 LIVE `Phase 2b` rediscovered · AMD5 `DI-7` ×2 undeclared confusable pairs rediscovered · AMD6 quiet** | `knowledge-lint.php --profile=structural` *(S3)* |
| **CAP-004** | **Reference Integrity** *(renamed — §0)* | a reference resolves to an existing target, or is classified as evidence | **DP-4** | ✅ **REALIZED** | 121-vs-9 scan · 53 classified · ⭐ **intra-document extension back-tested 2026-08-21: AMD5 `DI-5` dangling step ref rediscovered · AMD6 quiet** | `link-check.php` · `doc-placement.php` · `link-check.php --anchors` *(S2)* |
| **CAP-005** | **Assessment-Record Integrity** *(renamed — §0)* | an assessment records its method, scope and instrument | **DP-5** | **Candidate** | negative-claim discipline **broken 3×** while in force | — |
| **CAP-006** | **Knowledge-Card Integrity** | a governed document carries a valid, schema-conformant card | **DP-6** | ✅ **REALIZED** | baseline 9 errors/0 warnings | `knowledge-lint.php` |

⛔ **REJECTED — recorded with the rule that refused it, so it is not re-proposed:**

| Rejected | Rule |
|---|---|
| **Cross-artifact contradiction detection** | *"no mechanism, no owner (OQ-PKS-3), no cadence"* — building it **assigns an ownership the governance left vacant**. ⛔ *Redefines rather than realizes* |
| **A bounded context / component named "Identifier …"** | **AD-1 §6.2** (no component over AR-1/AR-2) · **Q-2** open · **M6 §7.5** trigger unmet |

---

## 5. CAP-001 — Identifier Integrity *(the only designed capability)*

| | |
|---|---|
| **Responsibility** | given a proposed identifier and its register(ns), **report whether minting it would collide** |
| **Governing rule** | **PMR-10** (GOVERNED) |
| **Domain policy** | **DP-1** (§7) |
| **Inputs** | proposed identifier · declared register(ns) · **the governed registers themselves** ⛔ *never a cached index (DR-1)* |
| **Outputs** | **exactly one verdict** from AP-8's closed set + its evidence |
| **Owns** | **execution only.** ⛔ *owns no identifier, register, criterion or numbering scheme* |
| **Failure modes** | series not a governed register → **INCONCLUSIVE** · register unreadable → **INCONCLUSIVE** · ⛔ **fail-closed: absence of evidence is never PASS** |
| **Evidence to collect** | did it fire? · did it change engineering behaviour? · which verdict on which input |
| **Realization** | `scripts/identifier-check.php` — **one CLI script, behaviour-only.** ⛔ not in `app/Contexts/` *(PKS is not software; AD-1 is deployment-neutral)* |

**Verdicts (AP-8's closed set — mapped, not invented):** free → **PASS** · already minted → **FAIL** · cited but unminted → **WARN** · not a governed register → **INCONCLUSIVE** · cross-kind reuse → **WARN**. ⛔ *`PASS AFTER CORRECTION` · `EMERGENT` · `CERTIFIED` are never emitted.*

⚠️ **Known criteria gap, disclosed:** **no canonical list of governed registers exists.** The capability will emit INCONCLUSIVE for every unregistered series — **correct behaviour, and evidence that M6 §7.5's reopening trigger (a UL ruling on *register*) is needed.**

---

## 6. Ubiquitous Language

**CAP-001 Identifier Integrity** *(⛔ no term is coined; each traces to M4, PMR-10 or M6)*

| Term | Definition | Class |
|---|---|---|
| **Identifier** | the assigned or intrinsic name by which a governed artifact is cited | Derived (M4 §1.1) |
| **Register(ns)** | M4's **identity namespace unit**. ⚠️ **Always written `register(ns)`** — `register` alone is a **three-sense collision** (M6 R-M6-8: *doc · ns · art*) | Observed (M4; M6) |
| **Identity mode** | 1 durable-global (register-namespaced) · 2 scoped-assigned · 3 intrinsic/name-as-identity | Observed (M4 §1.1) |
| **Mint** | the act of bringing a new identifier into use. **The act PMR-10 governs** | Observed (PMR-10) |
| **Collision** | two artifacts bearing the same identifier **within one register(ns)** *(in-kind)*, or across kinds where citations omit register context *(cross-kind)* | Observed (M4 §1.2) |
| **Unminted citation** | an identifier cited in the corpus but never entered in its register — **a collision hazard, not yet a collision** | Derived *(the R-65..R-71 case)* |
| **Semantic identity** | what makes an item *the same item* across citations and supersession | Observed (M4 §1.3) |
| **Representational identity** | filename, path, ordinal. ⛔ **never semantic identity** | Observed (M4 §1.3) |
| **Reservation** | a pre-claimed, not-yet-minted identifier | ⚠️ **Hypothesis — no corpus instance found** |

**Cross-capability terms:** *Projection · Source · Regenerate* (CAP-002) · *Term · Homonym · Qualification* (CAP-003) · *Reference · Target · Resolved · Ambiguous · Missing* (CAP-004) · *Method · Scope · Instrument* (CAP-005) · *Card · Schema · Authority · Status* (CAP-006).

---

## 7. Domain Policies — the layer between governed rule and capability

```
GOVERNED RULE  (authority-owned, immutable here)
      ↓
DOMAIN POLICY  (reusable statement of the obligation)      ← inserted per the commission
      ↓
CAPABILITY     (stable responsibility)
      ↓
IMPLEMENTATION (changeable)
```

| Policy | Statement | From | Capability |
|---|---|---|---|
| **DP-1** | **Every identifier shall be unique within its register(ns), and checked before minting** | **PMR-10 · AP-4** | CAP-001 |
| **DP-2** | **Every projection shall be regenerable from its sources, and citable as authority by nothing** | AP-2 · AP-9 · DR-1 | CAP-002 |
| **DP-3** | **A governed term shall carry one meaning per context; where overloaded, it shall be qualified** | **SD-3** · F-BCP-4 | CAP-003 |
| **DP-4** | **A reference shall resolve to an existing target, or be classified as evidence — never repaired on a guess** | the ≥99 confidence bar | CAP-004 |
| **DP-5** | **An assessment shall state what was checked, over what scope, with what instrument** | negative-claim discipline | CAP-005 |
| **DP-6** | **A governed document shall carry a valid knowledge card** | Knowledge-Constitution | CAP-006 |

> **Derived: DP-1..DP-6 are RESTATEMENTS, not new governance.** *Each is an existing governed rule expressed as an engineering obligation. **No policy here creates authority.***

---

## 8. Next steps · open questions · hypotheses

**Next, in order:** **(1)** review this catalog · **(2)** **EP-01 approval — Decision Authority** for plan `20260802-0015` · **(3)** **execution authorization — ARB** *(separate act)* · **(4)** RED → GREEN → evidence · **(5)** choose capability 2 **from this catalog** — CAP-002 is the recorded successor.

**Hypotheses — refutable, unadopted:**

| # | Hypothesis | Refuted by |
|---|---|---|
| **H-CAT-1** | A parent **"Validation Capability"** abstraction is justified | ⚠️ **TWO siblings now realized (CAP-001, CAP-003) — evidence recorded, decision stays ARB's.** Observed common contract: both emit `Shared\Domain\Assessment`/`Verdict` · fail-closed `INCONCLUSIVE` on "nothing to evaluate" · warn-only exit 0 · identical Domain/Application/Infrastructure/Tests shape. **Whether a common contract "justifies" the parent is `OQ-4` — recorded, not decided.** Refuted if the six siblings share no common contract once two are realized |
| **H-CAT-2** | This catalog becomes the engineering roadmap | refuted if capabilities keep being proposed outside it |
| **H-CAT-3** | Capabilities outlive their implementations | refuted if CAP-001's definition must change when its script does |
| ⭐ **H-CAT-4** | **PKS is evolving toward an engineering KNOWLEDGE GRAPH rather than a knowledge MODEL** — *a model is mostly hierarchical; a graph is relational, and PKS increasingly carries concepts · vocabulary · relationships · evidence · policies · decisions · traceability as **edges*** *(PA, 2026-08-02)* | *refuted if capability work never needs to traverse relationships — i.e. if hierarchical lookup suffices across the first several capabilities.* ⛔ **EXPLICITLY NOT introduced as an architectural layer today** (PA: *"treat it as a research hypothesis to validate after several capabilities have been implemented"*). **Evidence would come from CAP-001..CAP-003 operation, not from analysis** |

**⛔ NEW open question (§0A): what disposition applies to the R-70/R-71 citations now that R-65/R-66 are minted otherwise?** *Renaming is forbidden; an annotation (the R-53 pattern) is the likely instrument. **Authority.**

**⛔ RAISED — `OQ-1` (Track-2 Phase-0 plan §9, owner **Governance / ARB**):** `DI-3`/`DI-6`'s class — *a stated enumeration contradicting the content it enumerates* — has **NO catalogued capability**. Is it a new capability, an extension of an existing `DP`, or not a capability at all? ⛔ **Capability existence is not Architecture's to decide; nothing invented; `S8` blocked until answered** *(plan `D-6`)*. Pointer only — the plan's §9 row is the record.

**Open questions carried, none resolved:** the **governed-register list** (Authority — also M6 §7.5's trigger) · **Q-7** trigger location · **Q-2** components over undefined regions · **OQ-PKS-7** one corpus or three · **IBC-1's absence** · **R-65..R-71 → decision C-4**.

---

*Traceability: PA commission 2026-08-02 (Capability Lifecycle) · elevates the abstraction one level — **Identifier Validation becomes the first realization of CAP-001 Identifier Integrity** · six capabilities catalogued (1 designed · 2 realized · 2 candidate · 1 deferred), two rejections recorded with their refusing rules · vocabulary and domain-policy layers added per commission · **PMR-10 performed and FIRED THREE TIMES (§0), producing two renames and one qualification** · **"Active" omitted from the status vocabulary per AIP-10** · **the parent-abstraction proposal recorded as H-CAT-1 rather than adopted** · placement **instructed, not derived** — deviation recorded · **no code · no governance · no architecture · no bounded context · no KnowledgeOS promotion.***

> **⛔ STOP. Catalog delivered. No capability is authorized for implementation.**
