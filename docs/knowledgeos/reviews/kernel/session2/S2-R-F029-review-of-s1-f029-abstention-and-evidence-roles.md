# Session-2 Review — S1-F029

## 1 · Source · 2 · Session-1 claim
`session1/S1-F029-abstention-as-valid-output-and-construction-evidence-must-not-validate-itself.md`

Two operational constraints: **abstention is a valid result** when evidence does not support a sufficiently safe decision; and **the same evidence must not serve as both construction evidence and independent validation evidence**. Plus RAG placed as access-not-model, an authority-verb boundary, and the corpus's only implementation-altitude document. Marked **STRONG IMPLEMENTATION EVIDENCE** for both constraints.

## 3 · Independent assessment
**Evidence class:** CONSTRAINT ×2 · CLAIM ×2 · IMPLEMENTATION EVIDENCE. **Question types:** EVIDENCE, GOVERNANCE. **Level 1:** UNVERIFIED, 0 URLs across five book extractions.

## 4 · Confirmed

**`.1` The CQRS-projection collision has a single identifiable origin.** Session 1 traces the technical sense of *projection* to `024119` (*Architecture Patterns with Python*), concluding *"the five senses of projection are not five confusions but **one imported technical term plus four research senses**."* ✅ That is a genuine tractability result: a collision with a known import point can be repaired by naming the import, which an organically-grown collision cannot.

**`.2` Authority verb boundary.** *"AI may recommend, analyze, classify and propose; **authority remains explicitly assigned**"* — the cleanest verb-level statement in the corpus, and ⟦L⟧ consistent with `INV-KOS-AGENCY-001` and *"no mechanism may ever produce this member"* (l. 213).

## 5 · Challenged

**`.3` The decision-safety framing conflicts with the corpus's own exclusions — NEW FINDING**
Session 1 correctly notes the claim is **stronger** than law's: abstention licensed not by insufficient grounds but by *"insufficient grounds for a **sufficiently safe decision**"* — a **consequence-relative** threshold.

⚠ **But a consequence-relative threshold requires the boundary to know what decision the knowledge will inform** — and *workflow* and *decision* are on `S1-F011`'s 16-item exclusion list and `S1-F009`'s and `S1-F019`'s not-own lists. A Kernel that abstains based on downstream safety must hold something three formulations put outside it.

So the stronger reading is **not merely stronger — it is in tension with the corpus's most consistent exclusion.** Neither Session 1 nor the source notices. The weaker, grounds-relative reading (law's) carries no such cost. **Recorded, not resolved.**

**`.4` *"Third independent arrival"* at abstention — partly.** Audi's *evidence ≠ adoption* and Freedman are **independent sources**; both reach the corpus through the same extraction with the same prompt shape. **POSSIBLE INDEPENDENT ARRIVAL at source level, DEPENDENT at extraction level.** And v1.1's Port Contract obligation 3 is *law being compared to*, not an arrival — so the count is two, not three.

## 6 · DDD classification
Abstention → candidate **first-class output** of an assessment procedure. Evidence **role** → candidate **attribute of an evidence reference**. RAG/retrieval → **mechanisms**, outside. Repository/UoW/CQRS → **infrastructure**, no domain standing.

## 7 · Implementation relevance — **what would we build?**

### Candidate A · Abstention as a first-class output
⟦L⟧ Port Contract obligation 3 already makes declared insufficiency a valid answer. **DO NOT IMPLEMENT (already protected)** for the grounds-relative form; the consequence-relative form is **NEEDS FURTHER EVIDENCE** and carries `.3`'s cost.

### Candidate B · **Construction evidence must not validate itself** — the strongest new candidate in this batch

**Build what?** An evidence reference that carries its **role** in the claim it supports — *construction* versus *independent validation* — so one item cannot silently occupy both.

**Where?** Three placements, and choosing between them is not mine:
- **B (contents)** — a role attribute on `EvidenceLinks` (l. 209);
- **C (surrounding law)** — a Port Contract / ACL obligation checked at admission, never entering the aggregate;
- **A (extent)** — **no**, nothing here moves a gate or an aggregate.

**What problem does it solve?** Circular self-validation: an item that both constructs a claim and certifies it provides no independent warrant. This is `S1-F025`'s circularity at **evidence** level rather than **procedure** level — the same defect one layer down.

**Which law does it affect?** `EvidenceLinks`, which today carries *"evidence references + acquisition method + reliability conditions"*. ⟦L⟧ **No non-collapse row covers evidence roles** — §15's *Evidence ≠ Authority* separates evidence from warrant, not one evidence item's two roles. Session 1's **NOT ADDRESSED** classification is correct.

**What evidence authorizes it?** One book extraction, uncited. **Thin.**

**What is missing?** Whether *"reliability conditions"* already encodes role. That is a **semantic** question about what the phrase denotes — a `grep` cannot settle it, and I will not manufacture an answer from the string.

**Smallest implementation?** One declared role per evidence reference, with the admission check refusing an item that appears in both roles for the same claim. An attribute plus a guard — not a subsystem.

**Verdict → IMPLEMENTATION QUESTION.** ⚠ Fourth in the register, and the first whose missing fact is semantic rather than locational.

### Candidate C · RAG as access layer, technical patterns
**PRESERVE AS KNOWLEDGE ONLY** — *a mathematical or technical model is not automatically a Kernel model.*

## 8 · Conclusion vs reasoning
Abstention: **conclusion valid, arrival count fails** (`.4`), **stronger form untested and in tension** (`.3`). Evidence roles: **both valid**, evidence thin. *Strong implementation evidence*: **half survives** — Candidate B is the first constraint in three artifacts that is not already law.

## 9 · Effect on previous findings
`S2-R-F018` gains a cost on the Entity horn: a shareable evidence Entity could occupy both roles, which this forbids. `S2-R-F025` extended one layer down.

## 10 · Open questions · Adjudication · Status
Is the abstention threshold grounds- or consequence-relative · does *reliability conditions* encode role.
**ADJUDICATION: NONE. STATUS: CONTINUE.**
