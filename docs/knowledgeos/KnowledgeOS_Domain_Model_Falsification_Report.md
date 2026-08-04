# Engineering Knowledge Domain Model — Falsification Report

| | |
|---|---|
| **Kind** | ⭐ **FALSIFICATION TEST.** ⛔ ***The objective was to BREAK the model, not improve it. No redesign · no new domain · no folder · no invented abstraction.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Commission** | Strategic DDD Architect / Principal Knowledge Engineer / Enterprise Information Architect + ARB Chair's Addendum, 2026-08-02 |
| **Subject under test** | `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Domain_Model.md` — ⚠️ **a candidate, not accepted architecture** |
| **Instruments** | ⭐ **the repository's own** — `Round47-OP`'s nine admissible justifications · the **R16 Workbook's Strong/Medium/Weak** scale |
| **Method** | ⭐ **Every test was executed against artifacts, not reasoned from the model.** *Four falsifiers were sought; three landed* |

> # ⛔ **RESULT: THE MODEL DOES NOT SURVIVE INTACT.**
> ### **One claim FALSIFIED · two WEAKENED · two SURVIVED · two unchanged.**
> ⭐ **The strongest claim in the model — D-2 — was among those weakened, by evidence the model itself cited.**

---

## 1. Findings that WEAKEN the model

### ⛔⛔ W-1 · D-2's "own decision series" is a shared authority — **two of four grounds fall**

**`Round39-D6` header, verbatim:**

> **"Decision Authority (all ADRs unless noted): Round 39 Methodology Governance (**sponsor + ARB**)."**
> **"…amendment requires a Constitutional Amendment Record (**sponsor + ARB** + version bump)."**

| Ground I claimed | Verdict |
|---|---|
| *"Its own decision series — ADR-M, distinct from ADR-AIP"* | ⛔ **WEAKENED.** **The ARB participates in both.** *ADR-M is a **scoping device under a shared authority**, not a separate authority* |
| *"Its own lifecycle — independent evolution"* | ⛔⛔ **FALSIFIED as *evolution*.** **`Round39-D6`: *"Retrospectively documented. These ADRs were authored together on 2026-06-25."*** ⭐ *A series written in one sitting evidences an independent **subject**, never independent **evolution over time*** |

> ### ⭐ **This is precisely the falsifier the ARB Chair named: *"ADR separation is historical rather than semantic."* It is half true — the separation is real in subject, absent in time.**

### ⛔⛔ W-2 · D-3's ubiquitous-language divergence has **no artifact** — the strongest claim in D-3 is falsified

I argued: *"a boundary built to hold a language apart is a bounded context by construction."* **Three tests:**

| Test | Result |
|---|---|
| Does an artifact hold the abstract capability vocabulary? | ⛔⛔ **NO. There is no Capability Mapping file.** *The layer is described inside one record and instantiated nowhere* |
| Who cites it? | ⛔ **Exactly ONE source artifact** — `PKS_Phase_III_Governance_Runtime_Adapter_Record.md`, its own origin — plus `.claude/sessions/2026-07-31.md` and **eight documents I wrote today**. ⚠️ **No independent consumer** |
| Is the translation non-trivial? | ⛔⛔ **NO.** `.claude/settings.json` carries **`deny` · `ask` · `allow`** (19/22/8 entries). The abstract vocabulary is *deny · request approval · allow*. ⭐⭐ **The mapping is very nearly the IDENTITY function** |

> ### ⛔⛔ **AND THE DDD CONSEQUENCE: an Anti-Corruption Layer that performs an identity mapping is not an ACL. It is a restatement.**
> ⚠️ *The vocabulary separation is **asserted in prose**, not **enforced by any artifact or tool**. **D-3 drops from STRONG to WEAK.***

### ⛔⛔ W-3 · "PKS is a projection" — **FALSIFIED for every existing PKS artifact**

**Second Review executed. What a PKS artifact actually carries** (`PKS_Phase_II_M4_Identity_and_Lifecycle_Model.md`):

| Field present | ⛔ What a projection would carry instead |
|---|---|
| `Authority: **Generated** — never authoritative without human review` *(a table row, human-asserted)* | ⭐ `authority: derived` *(machine-checkable)* |
| `Status: **ACCEPTED WITH REFINEMENTS**` | ⛔ **no acceptance — a projection is regenerated, not accepted** |
| **`Disposition History`** — commissioned by the PA · work plan approved in Plan Mode · **ARB reviewer endorsement 9.9/10** · checkpoint amendments folded | ⛔ **none.** A projection has a **source** and a **regeneration command** |
| **`Commission`** — a named plan and work plan | ⛔ none |

> ### ⛔⛔ **You do not endorse a projection at 9.9/10. You regenerate it.**
>
> ⭐ **And a mechanical confirmation:** grepping `authority: derived|generated` across `docs/pks`, `docs/knowledgeos`, `docs/implementation` returns **10 files — every one a PROSE MENTION of the vocabulary, not one an application of it.** *Those paths sit outside `knowledge-schema.yaml`'s declared `scope.include`, so no frontmatter there is validated at all.*
>
> ### ⚠️ **This SHARPENS my Ontology Classification §4, which said "blocked in practice." It is stronger than blocked: existing PKS artifacts are POSITIVELY AUTHORITATIVE — authored, commissioned, reviewed, disposed and accepted. That is the lifecycle of an authoritative artifact, and it is incompatible with `derived`.**

### ⚠️ W-4 · D-7 "Context Type" was inferred from a single instance

| | |
|---|---|
| Instances observed | **ONE** — PublicDigit's |
| Instances generated from the type | ⛔ **ZERO** |
| ⭐ Therefore | *"one instance per product"* is **an observation of one case**, restated as a rule. ⚠️ **A type that has never been instantiated is a description, not a discovered construct** |

## 2. Findings that STRENGTHEN the model

### ⭐⭐ S-1 · D-2 survives on a ground I had not weighted — an **explicit mutual scope exclusion**

**`Round39-D6`, verbatim rule:**

> ### **"Governance discoveries (constitutional/capability) MUST NOT appear here. No DDD concepts here."**

| Why this is stronger than what it replaces | |
|---|---|
| ⭐ It is a **deliberately maintained** boundary, not an accident of filing | *someone wrote a prohibition to keep two subjects apart* |
| ⭐ It is **bidirectional in effect** | ADR-M excludes governance; ADR-AIP carries no methodology rules |
| ⭐ **It survives the shared-authority finding** | *a shared authority that **forbids itself** from mixing two subjects is evidence FOR two models, not against* |

> ### ⭐ **D-2 remains a boundary — but on subject exclusion, not on decision-series separation or independent evolution.**

### ⭐⭐ S-2 · D-4's rejection is CONFIRMED — I tried to revive it and failed

**The commission asked me to find independent ownership for Workflow. The EEP's own header:**

> **`Owner: Decision Authority`** — ⛔ **the same owner as D-1.** *Status "Adopted · STABLE (Decision Authority, 2026-07-10)."*

> ### ⭐ **REJECTION SURVIVES. Workflow has no owner of its own. It is D-1's, exactly as the model said.**

### ⭐ S-3 · D-1 survives every falsifier

| Falsifier sought | Result |
|---|---|
| multiple owners of the same concept | ⛔ not found — DA/ARB throughout |
| changes that do not co-occur | ⛔ not found — ES set moves under `STANDARDS_INDEX` |
| forced co-evolution with a product | ⛔ not found — ratification and freeze are product-independent |
| absent invariants | ⛔ not found — append-only rulings; **R-46** |

### ⭐ S-4 · The "Round 39 Methodology Governance" body is **named separately**

*Composition overlaps the ARB but is not identical — it is **"sponsor + ARB"**, scoped to methodology.* ⚠️ **Partial** authority independence: enough to keep D-2 alive, not enough to call it autonomous.

## 3. Remaining unresolved architectural questions

| # | Question | Why it is unresolved |
|---|---|---|
| ⭐⭐ **U-1** | **Was `Round39-MC` adopted by sponsor authority BECAUSE D-2 is independent, or DESPITE it being part of D-1?** | ⛔ **The artifacts record the act, never the reason.** *Only the sponsor and ARB know, and the model's validity turns on it* |
| ⭐ **U-2** | **Would a second runtime require translation, or is `deny/ask/allow` universal?** | ⛔ untestable at n=1. *If universal, D-3's ACL is permanently an identity map* |
| **U-3** | Does the D-6a/D-6b split hold under one real traversal? | ⛔ **0 traversals.** The split is motivated and entirely untested |
| **U-4** | Is `Round39-D6`'s scope exclusion still enforced, or was it stated once? | ⚠️ *stated 2026-06-25; no subsequent enforcement event found* |
| **U-5** | Could H-CAT-1 be overturned on the FROZEN `Platform_Capability_Pattern`? | ⚠️ *the pattern postdates the refusal; ⛔ **only the ARB may decide*** |
| **U-6** | Does D-1 authorize D-2, or are they peers? | ⚠️ **carried forward, now sharper: shared ARB suggests peers-under-one-authority, not hierarchy** |

## 4. Confidence — R16 Workbook scale, scored independently

| Domain | Model's claim | Before | ⭐ **After falsification** | Basis |
|---|---|---|---|---|
| **D-1 Engineering Governance** | bounded context | Strong | ⭐ **STRONG** | survived all four falsifiers (S-3) |
| **D-2 Engineering Method** | bounded context, **strongest** | Strong | ⚠️ **MEDIUM** | ⛔ two grounds fell (W-1); ⭐ survives on scope exclusion (S-1) |
| **D-3 Engineering Runtime** | bounded context | Strong | ⛔ **WEAK** | ⛔ no artifact · one citation · identity mapping (W-2) |
| **D-6a Evidence Protocol** | bounded context | — | ⚠️ **MEDIUM** | well-motivated, **0 traversals** |
| **D-6b Product Evidence** | product-side records | — | ⭐ **STRONG** | ownership stated in canon; abundant records |
| **D-5 Engineering Capability** | ⛔ **not** a context — a knowledge kind | Medium | ⚠️ **MEDIUM** | unchanged; H-CAT-1 stands |
| **D-4 Engineering Workflow** | ⛔ **REJECTED** | — | ⭐ **STRONG (as a rejection)** | ⭐ revival attempted and failed (S-2) |
| **D-7 PKS** | a context **TYPE** | — | ⛔ **WEAK** | inferred from n=1; projection claim falsified (W-3, W-4) |

⭐ **Note the asymmetry the ARB Chair asked for: *falsified* ≠ *unevidenced*.** **D-3 and D-7 carry contradicting evidence (falsified). D-6a carries no evidence (unevidenced).** *The first is worse.*

## 5. Recommendation — per domain

| Domain | ⭐ Recommendation | Action implied |
|---|---|---|
| **D-1** | ✅ **ACCEPT** | may become canonical |
| **D-2** | ⚠️ **REVISE** | ⭐ **restate the boundary on the ONE surviving ground — `Round39-D6`'s explicit scope exclusion.** ⛔ **Delete "own decision series" and "independent evolution" as grounds** |
| **D-3** | ⏳ **DEFER** | ⛔ **untestable at n=1.** *Revisit when a second runtime adapter exists — the trigger already reserved in `engineering/README.md`* |
| **D-6a / D-6b** | ⏳ **DEFER (D-6a)** · ✅ **ACCEPT (D-6b)** | *the split needs one traversal; the records half is not in doubt* |
| **D-5** | ✅ **ACCEPT the demotion** | ⛔ do not promote until the ARB overturns H-CAT-1 **on evidence** |
| **D-4** | ✅ **ACCEPT the rejection** | ⭐ confirmed by the EEP's declared owner |
| **D-7** | ⚠️ **REVISE** | ⭐ **restate as *"an observation of one instance"*, not a discovered type.** ⛔ **And record PKS-as-projection as FALSIFIED for existing artifacts** |
| ⭐ **The model as a whole** | ⚠️ **REVISE, then re-test** | ⛔ **NOT canonical.** *2 of 8 accept · 3 revise · 2 defer · 1 accept-as-rejection* |

---

## ⭐ Closing

**The commission asked me to break the model. It broke in three places, and two of them were its headline claims.**

| | |
|---|---|
| ⛔ **The most damaging finding** | **D-3's ACL performs an identity mapping and has no artifact.** *A boundary asserted in prose and instantiated nowhere is not a bounded context* |
| ⛔ **The most consequential** | **D-2's "own ADR series" is a shared authority, and the series was written in one day.** ⭐ *It survives — but on a different ground than the one I gave* |
| ⭐ **The most useful** | ⭐⭐ **`Round39-D6`'s scope exclusion — *"Governance discoveries MUST NOT appear here"*.** *A shared authority that forbids itself from mixing two subjects is stronger evidence than two ADR series* |
| ⭐ **What survived a revival attempt** | **D-4's rejection.** *I looked for independent ownership and found `Owner: Decision Authority`* |
| ⚠️ **What remains unanswerable from artifacts** | **U-1** — *whether the sponsor split methodology governance because it is independent, or despite it not being* |

> ### **The model is not canonical. It is a candidate that lost two grounds, gained a better one, and now carries per-domain confidence instead of a uniform claim.**
> ### ⭐ **That is the falsification working. A model that survived intact would have told us nothing.**

---

*Traceability: Validation/falsification commission 2026-08-02 · ⭐ **executed against artifacts, not reasoned from the model** · instruments: `Round47-OP` nine criteria + R16 Strong/Medium/Weak · **three falsifiers landed (W-1 shared authority + retrospective authoring · W-2 no Capability Mapping artifact + identity mapping · W-3 PKS artifacts are authored/accepted, not derived)** · **two survived deliberate attack (D-1, and D-4's rejection, which I tried to revive)** · **one better ground discovered (`Round39-D6` scope exclusion)** · falsified vs unevidenced distinguished · **confidence scored per domain: D-2 Strong→MEDIUM, D-3 Strong→WEAK, D-7 WEAK** · verdict **REVISE then re-test; NOT canonical** · ⛔ **no redesign · no new domain · no folder · no abstraction invented.***

> **⛔ Submitted to the Decision Authority. Nothing in this document executes.**
