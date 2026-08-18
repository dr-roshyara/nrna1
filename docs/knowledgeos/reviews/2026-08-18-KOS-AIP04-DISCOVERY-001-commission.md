# KOS-AIP04-DISCOVERY-001 — Commission

## ADR-AIP-04 — AI Engineering Platform Capability and Role Model Evolution

**Track:** 2 — EKS / Knowledge Engineering Platform Evolution · **Workflow:** `architecture-discovery`
**Declared role set:** `governance`, `architecture`, `verification` — **implementation excluded, mechanically**
**Status:** grant AUTHORIZED · assignment REGISTERED · HANDOFF recorded · **START NOT performed**
**Placement:** derived from the subject (`doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, exit 0), **not inherited from sibling paths.**

## 1 · Purpose

> **Determine the capabilities required for a sustainable session-based AI Engineering Operating System *before* formalizing or extending the role model.**

**A DISCOVERY / ARCHITECTURE work item. It does not authorize implementation.**

## 2 · ⭐ The capability-first principle — the ordering is the method

```
capability → ownership → bounded context / stewardship → human role → agent → platform service → technology
```

⛔ **Do NOT begin with *"how do we create six agents?"***

**The six currently proposed responsibilities are HYPOTHESES TO EVALUATE, NOT DECISIONS:** Governance Engineer · Communication Engineer · Knowledge Engineer · Architecture Engineer · Implementation Engineer · Independent Verification Engineer.

*This guard was registered before the commission existed, precisely so the discovery could not drift into org design. It now binds.*

## 3 · Scope — eight investigations

1 what capabilities the session-based engineering operating system requires · 2 **which are genuinely distinct** · 3 which are governance / communication / knowledge / architecture / implementation / verification / shared infrastructure / stewardship / cross-cutting · 4 what **ownership boundaries** exist · 5 the **ubiquitous language and invariants** of each · 6 **the reasons to change** for each · 7 which require **human authority · specialized role · agent · shared platform service** · 8 the **relationships** between Governance, Communication, Knowledge, Architecture, Implementation, Independent Verification and Session Orchestration.

## 4 · Evidence base

The existing KnowledgeOS evidence: session lifecycle · grants · REGISTER · HANDOFF · START · COMPLETE · acceptance · independent verification · **next-actor communication** · **knowledge lineage** · evidence management · existing platform components · `ADR-AIP-03`/BC-7 role-model deferral · the existing ADR-AIP-04 guard · `EKS-01`…`EKS-04`.

⛔ **Do not treat the proposed six-role model as established fact.**

## 5 · Classification and the proposal/decision line

Every important statement classified **OBSERVED · INFERRED · PROPOSED · DECIDED · OPEN**. ⛔ **Do not turn a proposal into a decision.**

## 6 · ⭐ Boundary rule — the two-track coupling, applied

**Track 2 MUST NOT silently modify the shared L3 contract or any accepted semantic decision used by Track 1.**

**If discovery reveals a change to a shared architectural boundary: STOP · record the implication · return the required change to Architecture / PO-ARB as a separate act.**

*This is the parallel-track rule made operational inside the lane that is most likely to trip it: a capability discovery is exactly the activity that notices a shared boundary is wrong.*

## 7 · Relation to BC-7

The BC-7 strategic and tactical architecture is **accepted**, and its role-model ownership question was **expressly deferred to ADR-AIP-04**. Therefore: ⛔ **do not reopen BC-7** · **use its deferred role question as an input** · **determine what capability/ownership decision ADR-AIP-04 now needs.**

## 8 · Deliverable

**`ADR-AIP-04 — Capability Discovery Proposal`**, seventeen sections: Context · Evidence Base · Capability Inventory · Capability Boundaries · Ownership Analysis · Ubiquitous Language · Invariants · Reasons to Change · Capability Relationships · Role/Stewardship Analysis · Human/Agent/Service Options · Session-Orchestration Relationship · Knowledge/Communication Relationship · Candidate Contexts or Stewardships · Open Questions · Recommendations · **PO/ARB Decisions Required**.

⛔ No implementation code · no technology selection · **no agent creation** · no acceptance · **no silent role adoption.**

## 9 · Stop condition

**After delivering the proposal: STOP.** Do not create implementation work · do not modify Track 1 · do not change shared L3 or accepted semantics.

**Next actor:** independent review / verification as governed, **then** PO/ARB decision. R-34/P-2: the producer must not verify its own proposal.
