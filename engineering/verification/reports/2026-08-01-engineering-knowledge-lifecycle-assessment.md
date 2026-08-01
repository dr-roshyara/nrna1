# Engineering Knowledge Lifecycle — Assessment

**Date:** 2026-08-01 · **Prepared by:** Recording Architect
**Commission:** define the lifecycle of engineering knowledge before canonical adoption.
**Repository Integrity Gate:** ✅ PASSED. **No file relocated · nothing adopted · no methodology redesigned · no exception mechanism created.**

---

> # THE LIFECYCLE IS NOT MISSING. **It has existed, canonically, since 2026-07-11.**
>
> **`ES-006.1 — The Promotion Ladder`:**
>
> ```
> Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability
> ```
>
> with **ES-006.4** supplying the entry path *(Project Outcome → Observation → DetermineReusePotential → DetermineArtifactType → ES-006.1)*.
>
> **So I will not define a lifecycle.** Writing a second one beside ES-006 would duplicate a canonical rule — and **ES-006.2 says so in its own words: *"no Level 5 exists; do not invent one."***
>
> ### The diagnosis changes accordingly
>
> **Not:** *the programme lacks a lifecycle.*
> **But:** ***the programme has a canonical lifecycle, and `Layer_Verification_Rule.md` bypassed it entirely.***

---

## 1. The proposed states, mapped onto the existing ladder

| Proposed state | Already exists as | Verdict |
|---|---|---|
| **Observation** | **ES-006.4** — *Observation*, the same word | ✅ exists |
| **Candidate** | **ES-006.4** — *candidate standard*, an outcome of `DetermineArtifactType` | ✅ exists |
| **Project Knowledge** | **explicitly OUT of ES-006's scope** — *"a separate bounded context … this scoping prevents ES-006 from slowly absorbing project concepts"* | ✅ exists, deliberately elsewhere |
| **Engineering Proposal** | ⚠️ **closest is `Qualification`** — but that is a **step** (an audit), not a **state an artifact rests in** | ⚠️ **partial** |
| **Engineering Canon** | **ES-006.1** — *Engineering Standard* → *Stable Engineering Capability* | ✅ exists |

**Four of five map cleanly.** The proposed lifecycle is very largely a restatement of ES-006.1 in different words — **and adopting the different words would leave the programme with two vocabularies for one ladder.**

## 2. The genuine gap — narrower than "a missing state"

**ES-006 governs *maturity* thoroughly. It does not make *location* carry a maturity signal.**

`engineering/knowledge/methodology/` currently holds **both**:

| Module | Maturity |
|---|---|
| `DDD_Tactical_Governance_Principles.md` | **ADOPTED** — via the explicit early-promotion exception **R-39** |
| `Layer_Verification_Rule.md` | **PROPOSED — NOT ADOPTED**, never piloted, never qualified |

> **Two artifacts at opposite ends of the ladder, in one directory, distinguishable only by reading a status line.**
>
> **That is the real inconsistency** — and it is exactly the ARB's point that **canonical location ≠ canonical authority**, stated as narrowly as the evidence supports.

**It is a placement question, not a lifecycle question.** The ladder is complete; **the filesystem does not reflect it.**

## 3. What ES-006 already answers — cited, not restated

| Question | Canonical answer |
|---|---|
| **Entry criteria** | **ES-006.4** — only *Project Outcome* (implementation · verification · qualification · retrospective). **"Never arbitrary mid-coding speculation."** |
| **Promotion criteria** | **ES-006.1** — *"nothing is promoted because it is a good idea; everything is promoted because operational evidence demonstrated necessity"* (burden of proof, **R-37**) |
| **Qualification step** | **ES-003** |
| **Placement of research-tier material** | **ES-005** |
| **Governing authority** | ARB / Decision Authority; the ladder is audited by **promotion-ladder audits** (ES-006's own qualification method) |
| **Retirement** | ES-006 — *"harvested, promoted, and retired"* |

**Nothing in the commission's promotion criteria is absent from ES-006.** The five criteria I applied yesterday are a restatement of its burden-of-proof rule.

## 4. Where `Layer_Verification_Rule.md` actually sits on the existing ladder

| Ladder stage | Reached? |
|---|---|
| **Research** | ✅ yes — derived from operational work (G-1) |
| **Pilot** | ❌ **no** — never applied in a second context |
| **Qualification** | ❌ **no** — never qualified under ES-003 |
| **Engineering Standard** | ❌ **no** — never adopted |
| **Stable Engineering Capability** | ❌ no |

> **It is at `Research`, sitting in the directory reserved for the last two stages.**
>
> **It did not climb the ladder and stall — it never entered it.** ES-006.4's `DetermineArtifactType` was never run; the artifact type was chosen by writing a file.

**And ES-006.3 already forbids the shape of that move:** *"Research dossiers are **input-only**: never architecture until promoted through ES-006.1."*

**Not moved, per the constraint.** Its lifecycle state is now identified, which is what was asked.

## 5. Recommendation — one narrow thing, and the ARB's to take

**Do not define a new lifecycle. Do not rename the existing stages. Do not create an exception mechanism.**

**The one open question is placement**, and it has a cheap form:

> **Should `engineering/knowledge/methodology/` be reserved for ADOPTED modules, with pre-canonical material held where its ladder stage belongs (ES-005's research-tier placement)?**

**If yes**, `Layer_Verification_Rule.md` moves — **not as a demotion, but because Research-stage material sitting in the canon directory is what let placement front-run promotion in the first place.**

**If no**, then the status line must be treated as authoritative over location, and that should be **stated**, because today it is merely hoped.

**📝 Recording Note.** **R-39 remains the honest precedent for the other route**: early promotion is available, but only when **ruled, named as an exception, and given a validation expectation.** The DDD module has all three. **This module has none of them** — which is the whole difference between the two files in that directory.

---

## What I did not do

⛔ No lifecycle defined *(one exists)* · ⛔ no state added to ES-006 · ⛔ no file relocated · ⛔ nothing adopted · ⛔ no exception mechanism created · ⛔ no methodology redesigned.

---

**Traceability:** **ES-006** (purpose, scope, and the ladder) · **ES-006.1** (the canonical lifecycle) · **ES-006.2** (*"do not invent one"*) · **ES-006.3** (research is input-only) · **ES-006.4** (the harvest question and entry path) · **ES-003** (qualification) · **ES-005** (research-tier placement) · **R-37** (burden of proof) · **R-39** (the recorded early-promotion exception) · the engineering promotion review that raised the inconsistency.
