# Source passages (verbatim). Code ONLY from these.

## Q1 — session log 2026-08-01, lines 440-487

### 📋 ENGINEERING PROMOTION REVIEW — and the rule's first catch is our own

**Report:** `engineering/verification/reports/2026-08-01-engineering-promotion-review.md`

**Applying the promotion criteria retroactively was the highest-value use of them**, because this session **already wrote a module straight into `engineering/knowledge/methodology/`** on one work package's evidence — never assessed, never adopted.

> **Placement front-ran promotion.** The file sits where canon lives; its status line says *PROPOSED — NOT ADOPTED*. **Location implies canon; status denies it** — and **a file in the canonical tree accrues authority by location**, whatever its header claims.

**Assessment — Outcome B, not yet canon:** repeated evidence **⚠️ partial** (many instances, **one context**) · domain independence **✅** · **stability ❌ — four post-freeze amendments in a single day** · reusability **⚠️ untested**, and R-63's scope declaration is evidence *against* unmodified reuse · simplicity **⚠️ mixed**.

**Stability is the criterion least open to argument — it is counted, not judged.**

**This is not a criticism of the content.** §1–2 are the strongest candidate the programme has produced: domain-neutral, and **demonstrably discriminating** — they rejected AP-1 and AP-2 retrospectively and accepted G-1 prospectively. **What is missing is a second context, and no amount of refinement inside EPIC-004 can supply it.**

**I did not move the file.** Relocating something in `engineering/` **is itself an update to `engineering/`** — the thing this commission forbids doing unilaterally. Referred.

> #### The structural question underneath
>
> **Under a strict reading, no insight from a single-context programme can ever be promoted** — and EPIC-004 is the only context this programme has run. Candidates 2–5 all fail on exactly that, not on merit.
>
> **R-39 resolved this once**: early promotion permitted as an **explicit, recorded exception with a stated validation expectation** — *ruled*, not merely written into the tree. **So the real question is whether the programme wants a standing exception mechanism, or whether `engineering/` stays closed until a second context exists.** Larger than any single candidate, and the ARB's.

### ⛔ The lifecycle is not missing — ES-006.1 has defined it since 2026-07-11

**Report:** `engineering/verification/reports/2026-08-01-engineering-knowledge-lifecycle-assessment.md`

**I did not define a lifecycle, because one exists and is canonical:**

```
ES-006.1: Research -> Pilot -> Qualification -> Engineering Standard -> Stable Engineering Capability
```

with **ES-006.4** supplying the entry path. Writing a second one beside it would duplicate a canonical rule — and **ES-006.2 says so in its own words: *"no Level 5 exists; do not invent one."***

**So the diagnosis changes.** Not *the programme lacks a lifecycle*, but ***the programme has one and `Layer_Verification_Rule.md` bypassed it entirely.***

**Four of the five proposed states already exist** — Observation and Candidate are ES-006.4's own words; Project Knowledge is **deliberately out of ES-006's scope** as a separate bounded context; Engineering Canon is *Engineering Standard → Stable Capability*. **Only "Engineering Proposal" is partial**, and its closest match (*Qualification*) is a **step**, not a state an artifact rests in.

> #### The genuine gap is narrower than a missing state — it is placement
>
> `engineering/knowledge/methodology/` holds the **ADOPTED** DDD module (via the R-39 exception) and the **PROPOSED** Layer module **side by side, distinguishable only by reading a status line.**
>
> **The ladder is complete. The filesystem does not reflect it.** That is *canonical location ≠ canonical authority*, stated as narrowly as the evidence supports.

**Where the module actually sits: `Research`.** Pilot ❌ · Qualification ❌ · Standard ❌. **It did not climb the ladder and stall — it never entered it.** `DetermineArtifactType` was never run; **the artifact type was chosen by writing a file.** And **ES-006.3 already forbids that shape**: *research dossiers are input-only, never architecture until promoted through ES-006.1.*

**One open question, and it is cheap:** should `knowledge/methodology/` be reserved for **adopted** modules? **If yes**, the module moves — *not as a demotion, but because Research-stage material in the canon directory is what let placement front-run promotion.* **If no**, the status line must be **declared** authoritative over location, because today that is merely hoped.


## Q2 — session log 2026-08-01, lines 662-685

### ⚖️ Chair disposition — five approved, three held, and my strongest framing withdrawn

**Report:** `engineering/verification/reports/2026-08-01-stewardship-disposition-record.md`

**Approved as the Chair's position:** repository = **workspace/container, not a domain** · it **contains documentation for** multiple domains · organization unit = **domain, not bounded context** · **`engineering/` = the result of the cross-product Scope decision** · **ES-005 stays generic.**

**Recorded as disposition, not minted as rulings.** **R-34:** *authority is created only by explicit issuance.* *"What I would approve"* is a disposition; `R-nn` identifiers belong to the Decision Authority. **Nothing appended to the register.**

**Held:** a new staging root · relocating the file · **and my "undefined cell" conclusion.**

> #### On the disagreement — the Chair's route is better supported on all three points I could check
>
> **The word was wrong.** *A cell two named shapes can fill is **unruled**, not **undefined**.* **"Undefined" says the model is broken; it is merely incomplete.** I chose the stronger word and it did rhetorical work the evidence did not support. **Withdrawn as framed.**
>
> **n = 1 is exact.** `methodology/` holds two artifacts — DDD principles **ADOPTED**, Layer Verification Rule **PROPOSED — NOT ADOPTED**. **One unqualified cross-product artifact**, so *"don't generalize until evidence requires it"* applies precisely.
>
> **The precedent I missed, and it is squarely on point: R-39.** `DDD_Tactical_Governance_Principles.md` sits in `engineering/` as an **early promotion under a recorded governance exception**, evidence base *"one context."* **The programme had already solved engineering-side placement of not-fully-qualified material — with a ruling, not a directory. Stewardship has precedent; my staging shapes have none.** I proposed structure where the repository had already shown the governance answer.
>
> **And my own best objection fails on check.** *"`engineering/` = qualified is machine-testable, a status header is not"* — **nothing enforces it.** No Architecture test asserts the property; the only status-reading mechanism is the **non-blocking guard I wrote this session**. **Potential, not current** — it cannot carry weight against a reversed burden of proof. **It converts into a real question: should a gate assert maturity?**

**The precision that survives:** stewardship resolves *placement* **only if stewardship implies location**. If it does, the artifact sits engineering-side while unqualified — **a clarification of ES-005.3 clause 2 reached without a directory**, strictly better, **but the clause is interpreted, not bypassed**. If it does not, placement stays open. **Either way clause 2 must be faced; stewardship changes the cost.**

**And the narrowed question is a *prerequisite*, not a sibling.** **If stewardship = YES, the cross-product/unqualified case never reaches project-side** — so it never touches the roots question, which then rests purely on the **89 PKS + 3 KnowledgeOS product-specific documents**, where the measured evidence actually is. **My package treated them as one; they are two, in that order.**

