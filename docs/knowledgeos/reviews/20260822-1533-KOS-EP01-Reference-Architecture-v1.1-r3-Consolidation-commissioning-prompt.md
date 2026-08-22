# Reference Architecture v1.1 — r3 CONSOLIDATION · commissioning instrument (r3 UNFROZEN · D-1 RATIFIED)

> **Source:** Human Principal Architect (HPA), 2026-08-22 — recorded **verbatim-in-substance**. The HPA's message is the authoritative act; this instrument is the chain's record of it.
> **Act:** the **ruling that unfreezes the r3 change set** and commissions its application — *"the next engineering action should therefore be to **apply the already-frozen r3 refinement to v1.1**, while using a DDD mindset to make sure the resulting architecture actually captures what we learned. Only after that should Logical Architecture open."*
> **Correction of the earlier commission:** *"the earlier prompt was **too narrow**. It treated the task as another review of v1.1, whereas the governance state has now changed."* → **this task is NOT a third review. It is consolidation.**
> **Outcome required:** **KnowledgeOS Reference Architecture v1.1 — CONSOLIDATED** — *"Not v1.2. Not Logical Architecture. Not Semantic Compiler Architecture."*

---

## 1 · ✅ D-1 RATIFIED — the canonical wording

The HPA settled the discrepancy flagged in the closure record:

> *"The important correction is also that the canonical wording is the one actually carried by r2:*
> **"The KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined."**
> *The session was right not to silently substitute the earlier wording."*

**D-1 is CLOSED.** *"constitutional administration"* was a transcription slip; **no amendment to the artifact's wording is instructed**. The canonical phrase is to be used exactly, and explicitly **not** replaced by *"constitutional administration" · "constitutional state" · "truth determination" · "knowledge validation"*. The operative architectural concept is `KnowledgeAggregate → state transition → constitutional admissibility` — **the aggregate does not become a truth oracle.**

## 2 · The change authority — exact and closed

**The r3 change set is the change authority and its whole extent:** **C-1 · C-2 · C-3 · C-4 · C-5 · R-1 · A-1 · A-2 · A-3.**

> *"Apply them. Do not reopen the HPA decisions. Do not reinterpret them. Do not invent additional changes."*
> *"Every change from r2 → r3 must be traceable… Do not create unexplained architectural drift. If you discover something that is not covered by the frozen change set: **STOP and record it as an observation.** Do not silently incorporate it."*

## 3 · Governance state at commissioning

```
Research CLOSED · Constitution v1.0 FROZEN
Reference Architecture v1.1 r2      PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE
First HPA review                    CLOSED
Second architectural review         ACCEPTED
r3 change set                       ⟹ UNFROZEN BY THIS ACT — apply
Logical Architecture                NOT OPEN
Expression↔Meaning Port Contract    NOT OPEN
KOS-SCB v0.2 experiment             NOT AUTHORIZED
OQ-2 · OQ-4                         OPEN — deliberately, not to be resolved here
```

**The distinction to preserve throughout:** *"Acceptance of a review is not the same as acceptance of every future mechanism."* The HPA accepted the **architectural refinement**; the HPA has **not** authorized Logical Architecture · the Port Contract · Semantic Compiler or SNF implementation · the v0.2 experiment · database, API, class or technology design. **The task ends at Reference Architecture v1.1.**

## 4 · What the consolidation must be

Smaller · clearer · internally consistent · DDD-aligned · constitutionally bounded · mechanism-independent · implementable later · **resistant to semantic collapse**. *"Do not make it larger simply because more research exists."*

**DDD as architectural discipline, not as an excuse to create classes.** In scope: domain · bounded context · core/supporting/generic · aggregate boundary · domain responsibility · invariant ownership · domain event · port boundary · external capability. **Out of scope: PHP classes · database tables · REST endpoints · repositories · ORM models · framework services · message schemas · technology choices.**

**Forbidden (§23):** new bounded contexts without evidence · new constitutional articles · new dimensions · new philosophical sources · database/API/class design · framework or model selection · implementation · Semantic Compiler or SNF implementation · benchmark execution.

## 5 · The architectural principles restated by the HPA

> **"KnowledgeOS owns the identity and justified evolution of knowledge. It does not own the particular mechanism by which meaning is extracted from expression."**
> **"A semantic mechanism may construct a meaning candidate; only the KnowledgeOS domain can determine the constitutional admissibility of a state transition."**
> **"Canonical representation may support identity reasoning, but representation equality must never silently become identity."**

## 6 · Required artifact and content

**Artifact:** the **existing canonical v1.1 document**, revised in place to **r3** — *"Do not create duplicate architecture documents. Use the existing document identity and repository conventions."* → `docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md` (the artifact's established identity; r2 was likewise produced by in-place revision).

**Required sections (20):** status · executive summary · architectural principles · core domain · bounded context map · KnowledgeAggregate · aggregate invariants · domain events · epistemic lifecycle · Expression→Meaning boundary · Semantic Compiler placement · SNF placement · LLM boundary · Zero placement · constitutional invariant mapping · kernel/mechanism/representation separation · rejected/external concepts · domain dependencies · architecture quality gates · explicit deferred decisions.

**Quality gates (§25):** Identity · Replacement · LLM · Representation · Aggregate · **Non-collapse** (*"Can any representation, similarity score, parser confidence, authority signal or LLM output directly become Knowledge Identity? The answer must be: No."*) · Reduction.

**Completion condition (§26):** r3 applied · DDD boundaries coherent · Semantic Compiler, SNF and LLM correctly bounded · the aggregate boundary explicit · constitutional invariants preserved · **no new architecture introduced** → status **CONSOLIDATED**.

---

## Traceability

- **Act:** HPA commission, 2026-08-22 — corrects the earlier (too narrow) framing, **unfreezes r3**, **ratifies D-1**, and commissions the consolidation; 27 numbered instructions recorded in substance above.
- **Objects:** v1.1 **r2** (to be revised in place to r3) · first HPA review (**CLOSED**, `…1425…`) · second architectural review (**ACCEPTED**, `…1459…`, commit `1ac7f639`) · acceptance record (`…1523…`, commit `feb19dd7`) · closure record (`…1527…`, commit `358f16cb`, **D-1 now closed by this act**).
- **Delivered under this commission:** v1.1 **r3 CONSOLIDATED** (same artifact identity) + the r2→r3 change ledger + observations recorded, not incorporated (§24).
- **Status:** ✅ **COMMISSION RECORDED · r3 UNFROZEN · D-1 RATIFIED.** Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · P4 gate **unchanged** · Logical Architecture **still NOT open** · OQ-2 / OQ-4 **still open**.
