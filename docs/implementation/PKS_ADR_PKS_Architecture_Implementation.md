# ADR: PKS Architecture — Implementation-Agnostic Strategic Baseline & Handover

---

## Metadata

| Aspect | Detail |
|--------|--------|
| **ADR ID** | ADR-PKS-001 |
| **Title** | Implementation-Agnostic Strategic Baseline & Handover |
| **Status** | PROPOSED |
| **Date** | 2026-07-29 |
| **Domain** | Product Knowledge System (PKS) |
| **Bounded Context** | Architecture Definition / Implementation Handover |
| **Decision Drivers** | Implementation paradigm uncertainty, AI agent integration, governance preservation, strategic-tactical separation |
| **ARB Status** | APPROVED WITH MINOR REVISIONS |

---

## 1. Context

### 1.1 The Problem

The PKS strategic architecture has been fully defined, verified, and certified through Phases I and II (M0-M8, AD-1, C4-1/C4-2, AFV-1, AIA-1, CCP-1, ERV-1, MCA, CDR). The architecture is complete, the methodology is provisionally certified, and the baseline is frozen.

However, the implementation paradigm (Phase II.D) remains undecided. The system could be built as:

1. **Traditional Java/Spring microservices** — modular, statically typed, deterministic
2. **PHP/Laravel monolith/modulith** — pragmatic, proven, team-aligned
3. **AI multi-agent system** — autonomous, non-deterministic, tool-based
4. **Hybrid approach** — combining static analysis with AI agents

**The architectural question is:** Does the existing strategic baseline remain valid across all implementation paradigms? Or does it need to be rewritten, adapted, or extended for specific paradigms?

### 1.2 The Opportunity

The strategic artifacts (M0-M8, AD-1, C4-1/C4-2, governance records, IBC-1) represent the **problem space** — the domain itself. They describe what the system is, where boundaries lie, how information flows, and what constraints apply.

These are **implementation-agnostic** by construction. A bounded context boundary (CBC-1 → CBC-2) is a domain fact, not an implementation choice. A dependency rule (DR-1: nothing may depend on AC-2) is an architectural invariant, not a technical preference.

**The opportunity:** The strategic baseline is implementation-agnostic and should remain valid regardless of the implementation paradigm chosen for Phase II.D. Implementation-specific guidance (e.g., AI tool contracts, prompt governance, service decomposition) belongs in Phase II.D, not in the strategic baseline.

### 1.3 What This ADR Does

| In Scope | Out of Scope |
|----------|--------------|
| Establishes the implementation-agnostic nature of the strategic architecture | Prescribes implementation technologies |
| Defines the handover boundary between architecture and implementation | Specifies Tactical DDD patterns |
| Positions IBC-1 as the semantic firewall | Certifies every listed artifact as architecture (see Architectural Limits) |
| Clarifies the relationship between strategic architecture and governance process artifacts | Dictates realization patterns for any implementation paradigm |

---

## 2. Forces

### 2.1 Forces Favoring Implementation-Agnostic Baseline

| Force | Description |
|-------|-------------|
| **Strategic DDD principles** | Strategic DDD exists to define the problem space independent of solution technology |
| **Governance investment** | Significant governance effort (MCA, CDR, AFV, AIA, CCP, ERV) has been invested in the current baseline |
| **Certification** | The methodology is PROVISIONALLY CERTIFIED — recertification would be costly |
| **Reusability** | An implementation-agnostic baseline can be reused across different implementation paradigms |
| **IBC-1** | The Implementation Boundary Contract was explicitly designed as the semantic firewall |

### 2.2 Forces Favoring Implementation-Specific Adaptation

| Force | Description |
|-------|-------------|
| **AI agents are not modules** | Some implementation paradigms may require additional realization guidance |
| **Tactical DDD differs** | Implementation paradigm affects tactical patterns (aggregates, repositories, services) |
| **Technology constraints** | Different paradigms have different strengths, weaknesses, and constraints |
| **Team expertise** | The team may have stronger expertise in one paradigm than another |

### 2.3 Forces Favoring Caution

| Force | Description |
|-------|-------------|
| **Over-claiming risk** | "Not a single artifact needs to be rewritten" is too strong |
| **Mapping table risk** | "CBC-1 → Auditor AI Agent" is one realization, not the realization |
| **Implementation-specific guidance** | AI-specific governance belongs in Phase II.D, not in the strategic baseline |
| **Scope clarity** | Not every artifact is implementation-agnostic in the same way |

---

## 3. Decision

### 3.1 The Core Decision

> **The PKS strategic architecture (M0-M8, AD-1, C4-1/C4-2, and the strategic portions of governance records) defines the semantic and governance constraints that implementations must honor. It is implementation-agnostic and shall remain the governing strategic baseline until amended through the established governance process. Implementation-specific guidance (e.g., prompt governance, tool contracts, service decomposition, technology selection) belongs in Phase II.D, not in the strategic baseline.**

### 3.2 What This ADR Establishes

This ADR establishes an **architectural principle**, not a certification of every listed artifact. Specifically:

| Principle | Description |
|-----------|-------------|
| **Strategic architecture is implementation-agnostic** | The problem space (Ubiquitous Language, Bounded Contexts, Context Maps, Domain Invariants) is independent of solution technology |
| **IBC-1 is the semantic firewall** | The Implementation Boundary Contract describes what implementation inherits, what it must respect, and what it must not assume |
| **Governance process artifacts are preserved for different reasons** | AFV, AIA, CCP, ERV are governance process artifacts — they are preserved because they validate the transformation and execution, not because they are architecture |
| **Phase II.D adds implementation-specific guidance** | Prompt governance, tool contracts, service decomposition, technology selection belong in Phase II.D |

### 3.3 Rationale

#### 3.3.1 Strategic DDD Defines the Problem Space

Strategic DDD operates at the problem space level. It defines:
- **Ubiquitous Language** — what terms mean in the domain
- **Bounded Contexts** — where boundaries lie
- **Context Maps** — how contexts relate
- **Domain Invariants** — what must always hold

These are independent of implementation technology. A `Verdict` has the same invariants whether computed by a Java class, a PHP service, or an AI agent.

**Conclusion:** The strategic baseline is inherently implementation-agnostic.

#### 3.3.2 Implementation-Specific Guidance is Phase II.D

The strategic baseline ends at IBC-1. Phase II.D (Implementation) is where implementation-specific guidance belongs:

| Implementation Paradigm | Phase II.D Guidance |
|-------------------------|---------------------|
| **Java/Spring** | Service decomposition, repository patterns, ArchUnit tests, Maven modules |
| **PHP/Laravel** | Modulith boundaries, Deptrac rules, Eloquent models, event listeners |
| **AI Agent System** | Prompt governance, tool contracts, model evaluation, non-determinism controls |
| **Hybrid** | Combining deterministic AST parsing with LLM semantic checks |

**Conclusion:** Implementation-specific guidance is not missing from the baseline — it belongs in Phase II.D.

#### 3.3.3 IBC-1 is the Semantic Firewall

IBC-1 was explicitly designed to be the bridge between architecture and implementation:

| IBC-1 Section | What It Does |
|---------------|--------------|
| **Certified Baseline** | What implementation inherits |
| **Context Boundaries** | What must be preserved |
| **Ownership Matrix** | Who owns what |
| **Open Questions** | What remains unresolved |
| **Forbidden Assumptions** | What implementation must not assume |
| **Implementation Constraints** | Engineering rules derived from governance |
| **Re-entry Triggers** | When to stop and escalate |

IBC-1 is the contract. It does not prescribe technology — it describes what must be respected.

**Conclusion:** IBC-1 is already the implementation-agnostic handover artifact.

#### 3.3.4 Implementation Paradigms May Evolve Independently

**Implementation paradigms may evolve independently without requiring strategic rediscovery, provided they continue to satisfy the certified strategic constraints and the Implementation Boundary Contract.**

This is one of the strongest benefits of the decision. The strategic baseline is frozen and certified. Implementation can evolve — from Java to AI agents to something else — without invalidating the architecture.

---

## 4. Architectural Limits

This ADR establishes implementation independence **only at the Strategic DDD level.**

It makes **no claim** that:

| Item | Status |
|------|--------|
| **Tactical DDD** (Aggregates, Entities, Repositories, Services) | ❌ Not implementation-agnostic |
| **Deployment topology** | ❌ Not implementation-agnostic |
| **Runtime architecture** | ❌ Not implementation-agnostic |
| **Operational controls** | ❌ Not implementation-agnostic |
| **Security architecture** | ❌ Not implementation-agnostic |
| **Implementation verification** | ❌ Not implementation-agnostic |
| **Technology selection** | ❌ Not implementation-agnostic |

These belong to Phase II.D (Implementation) or subsequent phases.

### 4.1 Distinction: Strategic Architecture vs Governance Process Artifacts

| Category | Artifacts | Implementation-Agnostic? |
|----------|-----------|--------------------------|
| **Strategic Architectural Artifacts** | M0-M8, AD-1, C4-1/C4-2, IBC-1 | ✅ Yes |
| **Governance Process Artifacts** | AFV-1, AIA-1, CCP-1, ERV-1, Execution Record | ⚠️ Preserved for different reasons — they document the governance process, not the architecture |
| **Certification Artifacts** | MCA, CDR | ⚠️ Preserved as certification records |

The strategic architectural artifacts define the **what**. The governance process artifacts document the **how we got there**. Both survive implementation changes, but for different reasons.

---

## 5. Consequences

### 5.1 Positive Consequences

| Consequence | Description |
|-------------|-------------|
| **Baseline stability** | The strategic baseline remains frozen and certified |
| **Implementation flexibility** | The team can choose any implementation paradigm without invalidating the architecture |
| **IBC-1 as the handover** | IBC-1 provides the semantic firewall between architecture and implementation |
| **Governance preservation** | The governance investment (MCA, CDR, AFV, AIA, CCP, ERV) remains valid |
| **Reusability** | The strategic baseline can be reused across different implementation paradigms |
| **Independent evolution** | Implementation paradigms may evolve independently without requiring strategic rediscovery |

### 5.2 Negative Consequences

| Consequence | Description |
|-------------|-------------|
| **Implementation-specific guidance missing** | The baseline does not contain implementation-specific guidance (by design) |
| **Mapping table risk** | The mapping table could be misinterpreted as normative guidance |
| **AI-specific governance gap** | AI-specific governance is not in the baseline (by design) |

### 5.3 Mitigations

| Risk | Mitigation |
|------|------------|
| **Mapping table misinterpretation** | Label clearly as non-normative illustrative examples |
| **AI-specific governance gap** | AI-specific guidance belongs in Phase II.D, not the strategic baseline |
| **Over-claiming** | Use precise language: "strategic architecture remains valid without strategic rediscovery" |

---

## 6. Non-Decisions

| Non-Decision | Reason |
|--------------|--------|
| **Which implementation paradigm to use** | Phase II.D decision, not ADR decision |
| **Whether to use Java, PHP, or AI** | Implementation decision, not architecture decision |
| **How to implement specific contexts** | Phase II.D tactical decisions |
| **Technology selection** | Phase II.D decisions |
| **Realization patterns** | This ADR does not prescribe realization patterns for any implementation paradigm |

---

## 7. Relationship to Other Artifacts

### 7.1 Upstream Artifacts

| Artifact | Relationship |
|----------|--------------|
| **M8 Strategic Modeling Report** | Source of strategic model |
| **AD-1 Architecture Definition** | Source of logical architecture |
| **AFV-1 Architecture Fidelity Verification** | Verified the transformation is lawful |
| **AIA-1 Authority Impact Assessment** | Documents governance contamination risk |
| **CCP-1 Controlled Change Plan** | Plans deterministic execution of changes |
| **ERV-1 Execution Readiness Verification** | Verified the plan is executable |
| **IBC-1 Implementation Boundary Contract** | The handover artifact |

### 7.2 Downstream Artifacts

| Artifact | Relationship |
|----------|--------------|
| **Phase II.D Implementation** | Consumes IBC-1 and implements the architecture |
| **Technology-specific guidance** | Added in Phase II.D, not in the baseline |

---

## 8. Implementation Guidance

### 8.1 What the Implementation Team Should Do

| Step | Description |
|------|-------------|
| **1. Read IBC-1** | Understand the certified baseline, context boundaries, ownership, open questions, forbidden assumptions |
| **2. Respect IBC-1** | Do not resolve open questions, do not make forbidden assumptions, do not change boundaries |
| **3. Choose implementation paradigm** | Java, PHP, AI agents, or hybrid — the architecture does not prescribe |
| **4. Add implementation-specific guidance** | Prompt governance, tool contracts, service decomposition, technology selection — in Phase II.D |
| **5. Follow re-entry triggers** | If you discover a new bounded context, need to resolve an open question, or need to change a boundary — escalate to governance |

### 8.2 What the Architecture Team Should NOT Do

| Item | Reason |
|------|--------|
| **Add implementation-specific guidance to IBC-1** | IBC-1 is the semantic firewall — it should not prescribe technology |
| **Rewrite the strategic baseline** | The baseline is complete, frozen, and certified |
| **Reopen governance decisions** | AFV-F4 is pending; other decisions are closed |

---

## 9. Appendix A: Implementation-Agnostic vs Implementation-Specific

| Layer | Artifacts | Agnostic? | Notes |
|-------|-----------|-----------|-------|
| **Strategic DDD** | Ubiquitous Language, Bounded Contexts, Context Maps, Domain Invariants | ✅ Yes | Core problem space |
| **Architecture Definition** | AD-1, AP-1..AP-10, DR-1..DR-8 | ✅ Yes | Logical architecture |
| **Governance Process** | AFV, AIA, CCP, ERV, MCA, CDR | ⚠️ Partially | Document the governance process; preserved for different reasons |
| **Handover** | IBC-1 | ✅ Yes | Semantic firewall |
| **Tactical DDD** | Aggregates, Entities, Repositories, Services | ❌ No | Implementation-dependent |
| **Implementation** | Code, APIs, Databases, Infrastructure | ❌ No | Phase II.D |

---

## 10. Appendix B: Non-Normative Illustrative Realizations

> **IMPORTANT:** The following table is **non-normative**.
>
> It provides **illustrative examples only**.
>
> It does **not** constrain implementation architecture.
>
> It does **not** imply preferred realization.
>
> It does **not** prescribe technology choices.
>
> Each strategic artifact can be realized differently within the same paradigm and across paradigms.

| Domain Concept | Java 21 | PHP 8.3 | AI Agent |
|----------------|---------|---------|----------|
| **CBC-1** | Core Domain Service + Write DB | Core Domain Module + Eloquent Models | Auditor Agent (RAG + AST Tools) — *one possible realization* |
| **CBC-2** | Read Model Processor + Redis/MinIO | Read-Side Event Listeners + Webhooks | Projection Agent (Mermaid & PR Bot) — *one possible realization* |
| **AR-1** | DTO Transformers & Interfaces | Interface Adapters & Value Objects | Governance Guard Agent (Schema Validator) — *one possible realization* |
| **IC-5** | Maven dependency prohibition | Deptrac architectural rule gate | Isolated System Prompts & Tool sets — *one possible realization* |

---

## 11. Appendix C: Terminology Refinement

| Overclaimed Statement | Refined Statement |
|----------------------|-------------------|
| "Not a single artifact needs to be rewritten" | "The strategic architecture remains valid without strategic rediscovery; implementation-specific guidance may be added in Phase II.D" |
| "Universal mapping" | "Non-normative illustrative realizations" |
| "Modules ↔ Agents" | "Modules and agents provide analogous but not equivalent separation mechanisms" |
| "Regardless of implementation stack" | "The certified strategic architecture remains invariant across implementation paradigms; only the realization strategy changes" |
| "Governance records are implementation-agnostic" | "Governance process artifacts are preserved as records of the governance process, not as implementation-agnostic architecture" |

---

## 12. Appendix D: Open Questions

| # | Question | Status |
|---|----------|--------|
| 1 | Does the ADR need to be updated after AFV-F4 disposition? | OPEN |
| 2 | Should the non-normative realization examples be included in IBC-1? | OPEN |
| 3 | Does AI agent implementation require additional governance beyond IBC-1? | OPEN — likely yes, belongs in Phase II.D |

---

## 13. Traceability

| Artifact | Section |
|----------|---------|
| M8 Strategic Modeling Report | Strategic model |
| AD-1 Architecture Definition | Architecture |
| IBC-1 | Handover contract |
| AFV-1 | Fidelity verification |
| AIA-1 | Governance impact |
| CCP-1 | Change plan |
| ERV-1 | Execution readiness |
| RET-1 | Retrospective |

---

## 14. ARB Review Status

| Area | Assessment |
|------|------------|
| Strategic DDD | ✅ Excellent |
| Strategic/Tactical separation | ✅ Excellent |
| Governance consistency | ✅ Strong |
| Technology neutrality | ✅ Strong |
| AI treatment | ✅ Correct |
| Risk of overclaim | ⚠ Minor — addressed in Appendix C |
| Scope clarity | ⚠ Minor — addressed in Section 4 |

### ARB Verdict

**Status:** **APPROVED WITH MINOR REVISIONS**

**Required revisions (applied):**

1. ✅ Clarified that "implementation-agnostic" primarily applies to the **strategic architecture**, while governance process artifacts are preserved for different reasons (Section 4)
2. ✅ Strengthened Appendix B's disclaimer — examples are **non-normative** and do not prescribe implementation architecture
3. ✅ Added **Architectural Limits** section (Section 4)
4. ✅ Strengthened the decision statement — strategic baseline tied to **governance process** ("until amended through established governance")

**Commendation:**

The ADR demonstrates a mature understanding of Strategic DDD. Its central insight — that **the strategic baseline defines the semantic and governance constraints that implementations must honor, while realization belongs to Phase II.D** — is well argued and consistent throughout. This ADR is suitable for approval as a governing architectural principle.

---

**Decision: The PKS strategic architecture is implementation-agnostic and shall remain the governing strategic baseline until amended through the established governance process. Implementation-specific guidance belongs in Phase II.D, not in the strategic baseline.**

**Status: APPROVED WITH MINOR REVISIONS — revisions applied.**