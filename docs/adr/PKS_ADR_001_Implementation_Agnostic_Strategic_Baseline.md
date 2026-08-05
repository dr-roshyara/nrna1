# PKS ADR 001 — Implementation-Agnostic Strategic Baseline & Handover

---

## Metadata

| Aspect | Detail |
|--------|--------|
| **ADR ID** | PKS-ADR-001 |
| **Title** | Implementation-Agnostic Strategic Baseline & Handover |
| **Status** | **PROPOSED** *(lifecycle status — unchanged until the Authority's constitutional act; **explicitly unchanged by the review commission's closure on 2026-07-30 — closing a review is not adopting the artifact it reviewed**)* |
| **ARB Review Outcome** | APPROVED WITH MINOR REVISIONS (revisions applied) |
| **Knowledge Contract Review Commission** | **CLOSED — formal close-out ISSUED by the Authority 2026-07-30** (five clauses; record: `docs/implementation/PKS_ADR_001_Knowledge_Contract_Review.md` §14.8). All findings disposed, zero rejections. **Closure does NOT change this ADR's lifecycle status, and implies no adoption.** |
| **Authority Disposition** | **PENDING** — no adoption record exists; review outcome ≠ adoption (per the program's acceptance-step discipline, CCP-1 §12.6) |
| **Date** | 2026-07-29 |
| **Domain** | Product Knowledge System (PKS) |
| **Architecture Area** | Architecture Definition / Implementation Handover *(field renamed from "Bounded Context" — these are program areas, not bounded contexts of the domain; the domain's BCs are CBC-1/CBC-2, and "Bounded Context" is a governed term under the terminology freeze)* |
| **Decision Drivers** | Implementation paradigm uncertainty, AI agent integration, governance preservation, strategic-tactical separation |
| **Revision History** | 2026-07-29: Section 1 corrections applied per ARB review (status separation; field rename; filename `.md.md` → `.md`). · 2026-07-29: **Section 2 corrections applied per ARB disposition** — F-2.1 ACCEPT (baseline state stated precisely: frozen ≠ promoted; AD-1 Governance Review Required; "certified" scoped to the methodology) · F-2.2 ACCEPT (IBC-1's design intent separated from its actual REVISE-draft status) · F-2.3 ACCEPT-editorial (boundary/relationship conflation corrected) · Q-2.1 ANSWERED ("Phase II.D" defined as the downstream implementation program, not a phase of the methodology). · 2026-07-29: **Section 3 corrections applied per ARB disposition** — F-3.1 ACCEPT (constraint-defining set corrected; C4-1/C4-2 removed — a view may not define a constraint) · F-3.3 ACCEPT WITH REFINEMENT ("implementation-agnostic" defined in §3.1.2 as three assertions plus one explicit non-assertion; **clarified, not narrowed**, per ARB direction) · F-3.4 ACCEPT (the six constraint-defining records named explicitly, replacing "strategic portions of governance records") · F-3.5 ACCEPT-optional (principle restated). **F-3.2 DEFERRED TO THE AUTHORITY — recorded in §3.1 and Appendix D item 4, NOT corrected.** · 2026-07-29: **Knowledge Contract Review corrections applied per Authority disposition (KC-1..KC-12 all ACCEPT; Q-KC-1 answered *describe only*; Appendix B extraction sequenced first).** Appendix B → companion `..._Companion_Illustrative_Realizations.md` with the AR-1 realizations and the invented domain event **removed, not relocated** · Appendix C + §14 → companion `..._Companion_Review_Record.md` · §4 non-claims restated as *out of scope — not assessed* · **§4.0.1 confidence-ceiling limit added** · §4.1 and Appendix A now reference §3.1.1 as the **single** taxonomy · **§8 reframed as descriptive-only, authorizing nothing** · governance-state summary replaced by a pointer. **R-2 NOT applied — it was a recommendation and was not disposed.** · 2026-07-30: **DISPOSITION COMMISSION — per-section findings KC-13…KC-22 all ACCEPT (KC-18/KC-22 accepted with the minimal remedy recorded), OBS-§6 ACCEPT-editorial, Q-KC-2 DEFERRED (PMR-routable).** **F-3.2 ACCEPT and APPLIED** — §3.1 now reads *"shall become, upon promotion, and thereafter remain…"*, removing the side-effect promotion; the correction **narrows the claim and decides nothing about promotion**. **R-2 ACCEPT and APPLIED** at §4.0 (*this ADR resolves no open question*) and registered as Appendix D item 5. **ELEVEN cascade sites of accepted findings corrected under their authority, not as new findings** — **KC-13 ×9** (§3.3 ×2, §8.1, §10 ×2, plus companions ×4) and **KC-17 ×2** (§3.2, §8.2); *the interim figures "six" and "nine" were stated before the §10 and companion sites were found — corrected here, see the review record §14.5.* **§13 reduced to a reference to §7 (KC-22); IBC-1 moved from §7.1 upstream to §7.2 downstream (KC-19); the AFV verdict restated in full (KC-20); §5.2 gained the four real negative consequences (KC-15); §3.1.2 declared the canonical non-assertion statement (KC-18).** **This ADR's lifecycle status is UNCHANGED by the review's closure: it remains PROPOSED — closing a review is not adopting the artifact it reviewed.** · 2026-07-30: **KNOWLEDGE CONTRACT REVIEW COMMISSION CLOSED** — formal close-out issued by the Authority in five clauses (accept the record · charter fulfilled and closed · ADR remains PROPOSED pending a separate adoption decision · execution work transferred to project governance · framework questions unaffected). **No further review scope is open against this ADR; a future review is a new commission with a new record.** |

---

## 1. Context

### 1.1 The Problem

The PKS strategic architecture has been defined and verified through Phases I and II (M0–M8, AD-1, C4-1/C4-2, AFV-1, AIA-1, CCP-1, ERV-1, MCA, CDR). **State as of 2026-07-29 — a POINT-IN-TIME SNAPSHOT, superseded by the governance records** *(correction per F-2.1; snapshot framing per KC-9b — every row below becomes false once the prepared execution queue runs, so the governance records, not this table, are authoritative on current state)*:

| Element | Actual state |
|---|---|
| **Strategic model (M0–M8)** | **Complete and consolidated;** the strategic baseline is **frozen under change control** (Consolidation §2.2) — *frozen is not the same as promoted* |
| **Baseline promotion** | **NOT YET PROMOTED.** KBI-1 classified the baseline *not yet promotion-ready*; promotion follows Package D |
| **AD-1 (Architecture Definition)** | **GOVERNANCE REVIEW REQUIRED** — AFV-F4 awaits Authority disposition; six corrections prepared and unapplied |
| **C4-1 (Views)** | Four corrections prepared and unapplied (incl. one governed-term restoration) |
| **Methodology** | **PROVISIONALLY CERTIFIED** on two dimensions — Method Design *provisionally certified*; Operational Evidence *supported by one execution lineage*. **"Certified" applies to the methodology, not to the boundaries, which are ACCEPTED at Medium-High** |

**The three DURABLE propositions the table illustrates** *(these do not go stale; the rows above do)*: **frozen is not promoted** · **"certified" applies to the methodology, provisionally, not to the boundaries** · **the boundaries are ACCEPTED at Medium-High**.

**Why this precision matters to the ADR's own argument:** the decision below rests on the baseline being *settled enough to hand over*. It is frozen and disposed, which is what the argument needs — but it is not yet promoted, and three artifacts carry unapplied corrections. **The ADR's principle holds; its premise must be stated at the record's actual strength.**

However, the implementation paradigm remains undecided. **Terminology used throughout this ADR** *(clarification applied per ARB answer to Q-2.1)*: **"Phase II.D" denotes the downstream implementation program that CONSUMES the strategic baseline — it is NOT a further phase of the PKS Strategic DDD methodology.** That program is separately governed by the already-frozen **Implementation Process v1.0** (15-step workflow, 14-box DoD). The governed shape is therefore **Strategic DDD → IBC-1 handover → Implementation Program**, never *Strategic DDD → Phase II.D as an extension of itself* — consistent with the Authority's **Implementation Handover Governance** ruling, under which the methodology governs the handover and never the implementation.

The system could be built as:

1. **Traditional Java/Spring microservices** — modular, statically typed, deterministic
2. **PHP/Laravel monolith/modulith** — pragmatic, proven, team-aligned
3. **AI multi-agent system** — autonomous, non-deterministic, tool-based
4. **Hybrid approach** — combining static analysis with AI agents

**The architectural question is:** Does the existing strategic baseline remain valid across all implementation paradigms? Or does it need to be rewritten, adapted, or extended for specific paradigms?

### 1.2 The Opportunity

The strategic artifacts (M0–M8, AD-1) represent the **problem space** — the domain itself. They describe what the system is, where boundaries lie, how information flows, and what constraints apply.

**IBC-1's status, stated separately from the strategic artifacts** *(correction applied per ARB finding F-2.2)*: **IBC-1 is the artifact DESIGNED to be the handover contract, and that design intent is sound — but it does not yet exist as an issued artifact.** It stands as a reviewed draft with verdict **REVISE** (3 Critical · 10 Major), and per finding IBC-C1 **it cannot be issued before the baseline is promoted**, because it presently declares as certified a baseline that is not. **This strengthens rather than weakens the present decision:** a handover contract still under revision is precisely why the implementation-agnosticism principle needs stating independently of it.

These are **implementation-agnostic** by construction. **A bounded context's membership rule is a domain fact, not an implementation choice** — CBC-2's members are identified by *absence of independent semantic identity*, a property of the domain that no technology choice alters. *(Corrected per ARB finding F-2.3: the earlier example wrote "a bounded context boundary (CBC-1 → CBC-2)", which named a **relationship** — R-3, Conformist plus the unidirectionality constraint as disposed at DAR-1 — rather than a boundary. Boundary ≠ relationship is a preserved distinction of this program.)* A dependency rule (DR-1: nothing may depend on AC-2) is an architectural invariant, not a technical preference.

**The opportunity:** The strategic baseline is implementation-agnostic and should remain valid regardless of the implementation paradigm chosen for Phase II.D. Implementation-specific guidance (e.g., AI tool contracts, prompt governance, service decomposition) belongs in Phase II.D, not in the strategic baseline.

### 1.3 What This ADR Does

| In Scope | Out of Scope |
|----------|--------------|
| Establishes the implementation-agnostic nature of the strategic architecture | Prescribes implementation technologies |
| Defines the handover boundary between architecture and implementation | Specifies Tactical DDD patterns |
| Positions IBC-1 as the **designed** semantic firewall (design intent; IBC-1 is not yet issued — see §1.2) | Certifies every listed artifact as architecture (see Architectural Limits) |
| — | Promotes the baseline, issues IBC-1, or performs any Authority act |
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

> **The PKS strategic architecture — the strategic model (M0–M8), the Architecture Definition (AD-1), and the four governance records named in §3.1.1 — defines the semantic and governance constraints that implementations must honor. It is implementation-agnostic in the sense defined in §3.1.2, and **shall become, upon promotion, and thereafter remain, the governing strategic baseline until amended** through the established governance process. Implementation-specific guidance (e.g., prompt governance, tool contracts, service decomposition, technology selection) belongs in the implementation program (Phase II.D), not in the strategic baseline.**

> ✅ **F-3.2 — DISPOSED: ACCEPT and APPLIED (Disposition Commission, 2026-07-30).** The clause previously read *"shall remain the governing strategic baseline"*, which **presupposed the baseline was already governing** — so adopting this ADR could have **effected promotion as a side effect**, bypassing promotion-readiness verification (promotion is a distinct pending Authority act: Package D → promotion; KBI-1 classified the baseline *not yet promotion-ready*).
>
> **Why ACCEPT was the boundary-preserving decision rather than the intrusive one:** the correction **narrows** the ADR's claim to match the governed state. It **does not decide promotion, schedule it, or assert readiness** — it removes an unintended side effect. **Withdrawing an unsupported claim does not require establishing an alternative claim** (DAR-1 §4(a)). REJECT would have left a clause capable of bypassing a verification gate; DEFER was the prior state and the commission was convened to end it.

### 3.1.1 The constraint-defining set, stated exactly *(correction applied per ARB finding F-3.1 and F-3.4)*

**Constraint-defining (normative for implementation):**

| Artifact | Why it defines constraints |
|---|---|
| **M0–M8** | The strategic model: Ubiquitous Language, concept canon, identity and lifecycle model, classification, bounded contexts, relationships |
| **AD-1** | The logical architecture: principles **AP-1..AP-10**, dependency rules **DR-1..DR-8**, boundaries, the integration model |
| **Authority Disposition (incl. §7 re-disposition)** | The four dispositions themselves — CBC-1/CBC-2 accepted, CBC-4 adjacent, CBC-3 a candidate seam — **plus §1's binding acceptance semantics**: grades carry their R-M6-6-capped meaning, and recorded reopening triggers are **binding reopening conditions** |
| **DAR-1** | The disposed relationship model: two pattern names withdrawn, one pattern label replaced by a constraint. **An implementation reading M7 without DAR-1 would inherit withdrawn patterns** |
| **Consolidation §2.2** | The change-control rules and the binding contingencies that travel with the baseline |
| **CDR** | Froze SDM v1 / EOP v1 and **declared Process Under Configuration Control** — the reason implementation may not amend the baseline |

**NOT constraint-defining (preserved as records — see §4.1):** C4-1 *renders* these constraints and **may never be cited as authority** (AP-2; C4-1's own DP-7) · C4-2 *verifies the rendering* · AFV-1, AIA-1, CCP-1, ERV-1, KBI-1, MCA, RET-1 document the transformation, the governance, and the process.

*(Two corrections are embodied here. **F-3.1:** the earlier statement grouped **C4-1/C4-2** with the constraint-defining set — but a **view cannot define a constraint**, since the architecture's own AP-2 forbids citing a view as authority, and C4-2 is a verification record, not architecture. **F-3.4:** the earlier phrase **"the strategic portions of governance records"** was undefined, unbounded, and in tension with §4.1; the four records above are named explicitly, each with the reason it qualifies, which resolves that tension.)*

### 3.1.2 What "implementation-agnostic" means here *(definition added per ARB finding F-3.3, accepted with refinement)*

The term is the ADR's central claim and is therefore defined rather than assumed. **It asserts three things and deliberately does not assert a fourth:**

| # | Asserted | Basis |
|---|---|---|
| **1** | **Paradigm-neutral in expression** — no constraint-defining artifact names a technology, framework, runtime, or deployment form | Verifiable now by inspection; AD-1 is deployment-neutral by construction |
| **2** | **Invariant in claim** — the model's claims about the domain do not change when the paradigm changes. A `Verdict` carries the same invariants whether computed by a Java class, a PHP service, or an AI agent | The claims are about the domain, not about a solution |
| **3** | **Realizable by any implementation approach that satisfies the stated strategic constraints** — paradigm choice is unconstrained **except** by constraint-satisfaction; the model privileges no paradigm | The constraints are stated in paradigm-neutral terms (assertion 1), so nothing in them selects a paradigm |

**This is the CANONICAL non-assertion statement for this ADR** *(KC-18)*. §4.0 (*out of scope — not assessed*), §6 (*Non-Decisions*) and §8.2 (*what this ADR does not do*) **elaborate it and do not extend it**; where any of them appears to add a non-assertion, this section governs.

**NOT asserted:** that **every** paradigm does in fact satisfy the constraints. **Whether a given paradigm satisfies DR-1, AP-3, or any other constraint is determined in the implementation program by demonstration — it is not asserted here.** *(This is the refinement the ARB directed: the claim is clarified rather than narrowed. Assertion 3 is conditioned on constraint-satisfaction, which avoids the overclaim that any imaginable implementation can realize the architecture, while preserving the ADR's real point — that the model does not privilege a paradigm and the test is constraint-satisfaction, not paradigm identity.)*

### 3.2 What This ADR Establishes

This ADR establishes an **architectural principle**, not a certification of every listed artifact. Specifically:

| Principle | Description |
|-----------|-------------|
| **Strategic architecture is implementation-agnostic** | The problem space (Ubiquitous Language, Bounded Contexts, Context Maps, Domain Invariants) is independent of solution technology |
| **IBC-1 is the *designed* semantic firewall** | The Implementation Boundary Contract describes what implementation inherits, what it must respect, and what it must not assume. **Design intent; IBC-1 stands at REVISE and is not issued** (§1.2) *(KC-17 cascade)* |
| **Governance records document the transformation, not the architecture, and are preserved on that basis** | AFV-1, AIA-1, CCP-1, ERV-1, KBI-1, MCA, RET-1 validate how the architecture came to be and how change proceeds; they are therefore preserved as records rather than inherited as constraints *(restated as a principle per ARB finding F-3.5 — the earlier row stated a classification in a table of principles. Distinguish from the six constraint-defining records named in §3.1.1, four of which are governance records that DO establish enduring constraints.)* |
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

**Implementation paradigms may evolve independently without requiring strategic rediscovery, provided they continue to satisfy the strategic constraints as disposed and the Implementation Boundary Contract.** *(KC-13 cascade: "certified strategic constraints" — certification attaches to the methodology, not to the constraints.)*

This is one of the strongest benefits of the decision — **as design intent; §5.1 records that it is not assessed (KC-14)**. The strategic baseline is **frozen under change control; certification attaches to the methodology, provisionally** *(KC-13 cascade)*. Implementation can evolve — from Java to AI agents to something else — without invalidating the architecture.

---

## 4. Architectural Limits

This ADR establishes implementation independence **only at the Strategic DDD level**, in the three senses defined at §3.1.2.

### 4.0 What this ADR does NOT assess *(corrected per KC-6)*

**It makes no claim, in either direction, about the following.** Each is **out of scope — not assessed by this ADR**:

| Item | Status |
|------|--------|
| **Tactical DDD** (aggregates, entities, repositories, services) | Out of scope — not assessed |
| **Deployment topology** | Out of scope — not assessed |
| **Runtime architecture** | Out of scope — not assessed |
| **Operational controls** | Out of scope — not assessed |
| **Security architecture** | Out of scope — not assessed |
| **Implementation verification** | Out of scope — not assessed |
| **Technology selection** | Out of scope — not assessed |

*(**KC-6 correction.** The earlier table headed these rows *"❌ Not implementation-agnostic"* under the heading *"It makes no claim that:"*. **A non-claim and a negative claim are different acts** — the first is silence, the second asserts the opposite property — and this ADR has assessed none of the seven items. At least one negative was likely false in part: **AP-1** — *knowledge feeds authority, never is authority* — is a security-relevant constraint that **is** paradigm-neutral. **The section that bounds this ADR's claims must not itself over-claim.**)*

**Explicit limit — this ADR RESOLVES NO OPEN QUESTION** *(ARB recommendation R-2, ACCEPTED and applied by the Disposition Commission, 2026-07-30)*. It records a decision about the baseline's implementation-agnosticism; it **resolves none of the Surfacing Register's open questions**, none of AD-1's six, and **not OQ-PKS-7**, on which AR-1's placement depends (AFV-F4, undisposed). **Adopting this ADR closes nothing that was open.**

These belong to the **implementation program** (see §1.1's terminology note), not to this ADR or to the strategic baseline. *(KC-11: the earlier phrase "or subsequent phases" is removed — "subsequent phases" of what was ambiguous, and reintroduced the very ambiguity Q-2.1 resolved.)*

### 4.0.1 The governing limit: the confidence ceiling *(added per KC-5)*

**The most important limit is not about scope but about strength.** Every element of the strategic baseline is graded **at most Medium-High, on a single-corpus, single-lineage basis** (MCR-5, which requires every grade to state its independence basis), and the methodology is only **provisionally certified** — Method Design *provisionally certified*, Operational Evidence *supported by one execution lineage*.

**Consolidation §2.2 binds the consequence:** **no downstream artifact may cite the baseline as independently confirmed.** Anything inheriting these constraints inherits that ceiling with them. **An implementation that treats the constraints as independently established would over-trust them** — the constraints are binding, and their evidential basis is one corpus read by one lineage.

*(This limit was absent from the earlier §4 and is the second artifact in which the omission was found — the first was IBC-1 — which raises it from an oversight to a systematic omission across handover-facing artifacts.)*

### 4.1 Strategic architecture vs governance records — by reference *(corrected per KC-4, KC-12)*

**The classification is stated ONCE, at §3.1.1, and is not restated here.** §3.1.1 names the six constraint-defining artifacts individually, each with the reason it qualifies, and names what is not constraint-defining.

**The durable insight this section contributes, which survives the correction:**

> **The constraint-defining artifacts define the *what*. The governance records that are not constraint-defining document the *how we got there*. Both survive implementation changes — but for different reasons, and only the first binds implementation.**

**One refinement the correction requires:** the earlier form of this section recognized **two** categories. There are **three** — *(i)* strategic artifacts that define constraints (M0–M8, AD-1) · *(ii)* **governance records that also define constraints** (Authority Disposition, DAR-1, Consolidation §2.2, CDR — see §3.1.1) · *(iii)* governance and verification records that document the process and bind nothing (AFV-1, AIA-1, CCP-1, ERV-1, KBI-1, MCA, RET-1, C4-2), plus **C4-1, which renders the constraints and may never be cited as authority** (AP-2; C4-1's own DP-7).

*(**KC-4.** The earlier restatement here, together with Appendix A's, produced **three incompatible classifications inside one ADR** — most consequentially the **CDR**, named constraint-defining at §3.1.1 while both restatements filed it as a mere certification record; and **Authority Disposition, DAR-1, and Consolidation were absent from both taxonomies entirely.** The cascade was created by an accepted correction to §3.1, which is why **a restated taxonomy is a taxonomy that will diverge again.** **KC-12:** the single ✅/⚠️ column also conflated two orthogonal axes — *constraint-defining?* and *implementation-agnostic?* — which is how C4-2 acquired a ✅ despite not being architecture. The two axes are separated at Appendix A.)*

## 5. Consequences

### 5.1 Positive Consequences

| Consequence | Description |
|-------------|-------------|
| **Baseline stability** | The strategic baseline remains **frozen under change control** (Consolidation §2.2). **Certification applies to the methodology — provisionally, on two dimensions — and NOT to this baseline** *(KC-13)* |
| **Implementation flexibility** | The team can choose any implementation paradigm without invalidating the architecture |
| **IBC-1 as the handover** | IBC-1 is **intended to** provide the semantic firewall between architecture and implementation. **Status: REVISE (3 Critical · 10 Major); revision is queued against the promoted baseline** — see §1.2 *(KC-17)* |
| **Governance preservation** | The governance investment (MCA, CDR, AFV, AIA, CCP, ERV) remains valid |

**Intended properties — NOT ASSESSED by this ADR** *(separated per KC-14: §4.0 places these out of scope and §4.0.1 caps their confidence; stating them as realized consequences contradicted both)*:

| Intended property | Status |
|---|---|
| **Reusability across implementation paradigms** | Design intent. **Not assessed** — no second paradigm has been realized |
| **Independent evolution of paradigms without strategic rediscovery** | Design intent. **Not assessed** — asserting it would exceed §4.0.1's ceiling |

### 5.2 Negative Consequences

| Consequence | Description |
|-------------|-------------|
| **Implementation-specific guidance missing** | The baseline does not contain implementation-specific guidance (by design) |
| **Mapping table risk** | The mapping table could be misinterpreted as normative guidance |
| **AI-specific governance gap** | AI-specific governance is not in the baseline (by design) |
| **The confidence ceiling travels with the constraints** | Every element is graded **at most Medium-High on a single-corpus, single-lineage basis**; **no downstream artifact may cite the baseline as independently confirmed** (§4.0.1; Consolidation §2.2). An implementation treating the constraints as independently established would over-trust them |
| **Two architecturally undefined regions are inherited** | **AR-1 and AR-2** are regions over which **no component may be defined** (AD-1 §6.2). Implementation inherits an architecture with deliberate holes, and **AR-1's placement presupposes OQ-PKS-7** (AFV-F4, undisposed) |
| **One translation obligation is unowned** | **U-2** — at the PKS ∥ Work-Management edge the knowledge obligations cross a domain boundary and **no context owns the translation** (M7; open since M7) |
| **One accepted boundary is a candidate seam at Low-Medium** | **CBC-3 Normative Governance** is an evidence-decided candidate seam, not an accepted context (M6 §14). Implementation must not treat it as settled |

*(**KC-15.** The three original rows each end or read *"(by design)"* — **a by-design consequence is a scope statement, not a downside.** The four rows above were already established in the governed record and were absent from the only section a reader consults for costs.)*

### 5.3 Mitigations

| Risk | Mitigation |
|------|------------|
| **Mapping table misinterpretation** | Label clearly as non-normative illustrative examples |
| **AI-specific governance gap** | AI-specific guidance belongs in Phase II.D, not the strategic baseline |
| **Over-claiming** | **Every claim in §5 names the artifact that establishes it** — a checkable control, replacing the earlier mitigation *"use precise language"* *(KC-16: an exhortation has no owner, no trigger, and no check; KC-13 and KC-14 were over-claims standing two subsections above it)* |

---

## 6. Non-Decisions

| Non-Decision | Reason |
|--------------|--------|
| **Which implementation paradigm to use** | Phase II.D decision, not ADR decision |
| **Whether to use Java, PHP, or a given implementation paradigm** | Implementation decision, not architecture decision *(OBS-§6: "AI" is a paradigm, not a language — the row mixed categories)* |
| **How to implement specific contexts** | Phase II.D tactical decisions |
| **Technology selection** | Phase II.D decisions |
| **Realization patterns** | This ADR does not prescribe realization patterns for any implementation paradigm |

---

## 7. Relationship to Other Artifacts

### 7.1 Upstream Artifacts

| Artifact | Relationship |
|----------|--------------|
| **PKS_Phase_II_M8_Strategic_Modeling_Report.md** | Source of strategic model |
| **PKS_Phase_IIB_Architecture_Definition.md** | Source of logical architecture |
| **PKS_Phase_IIB_Architecture_Fidelity_Verification.md** | **VERIFIED WITH FINDINGS** — the transformation is **lawful in structure and defective in four steps**; four findings require action **before promotion**. **AFV-F4 remains undisposed** *(KC-20: the earlier summary — "verified the transformation is lawful" — kept half of a two-part verdict and dropped the half that blocks promotion)* |
| **PKS_Phase_II_Authority_Impact_Assessment.md** | Documents governance contamination risk |
| **PKS_Phase_II_Controlled_Change_Plan.md** | Plans deterministic execution of changes |
| **PKS_Phase_II_Execution_Readiness_Verification.md** | Verified the plan is executable |

### 7.2 Downstream Artifacts

| Artifact | Relationship |
|----------|--------------|
| **PKS_IBC-1_Architecture_Review.md** | **The handover contract — DOWNSTREAM of this baseline**, to be revised against the promoted baseline *(KC-19: it was listed as upstream. An artifact that must be revised to conform to the baseline cannot be a source of it)* |
| **Phase II.D Implementation** | Consumes IBC-1 and implements the architecture |
| **Technology-specific guidance** | Added in Phase II.D, not in the baseline |

---

## 8. What Implementation Will Inherit — descriptive only, not authorizing

> **This section AUTHORIZES NOTHING.** *(Reframed per Authority answer to Q-KC-1 — **describe only** — and per finding KC-3.)*
>
> **Implementation is not authorized by this ADR.** Authorization follows **promotion of the baseline** and **issuance of the handover contract**, neither of which has occurred: the baseline is not promoted, and IBC-1 is an unissued draft (verdict REVISE). §1.3 disclaims performing any Authority act, and this section is written to stay inside that disclaimer.
>
> **What follows therefore describes what the implementation program will inherit and be expected to respect *once it is authorized*. It instructs no one at present.**

### 8.1 What the implementation program will inherit

| # | Inherited obligation | Source |
|---|---|---|
| 1 | The **frozen baseline, once promoted** — context boundaries, ownership, relationships as disposed *(KC-13 cascade: "certified baseline" — the baseline is frozen, not certified)* | §3.1.1 |
| 2 | The **open questions**, which it may not resolve | The Surfacing Register (seven ARB-owned) |
| 3 | The **forbidden assumptions** and **re-entry triggers** the handover contract will carry | IBC-1, once issued |
| 4 | The **confidence ceiling** — constraints binding, evidence single-lineage | §4.0.1 |
| 5 | **Freedom of paradigm**, bounded by constraint-satisfaction, which it must **demonstrate** rather than assume | §3.1.2 assertion 3 and its non-assertion |

### 8.2 What this ADR does not do, restated

| Item | Reason |
|------|--------|
| Add implementation-specific guidance to the handover contract | The handover contract is the **designed** semantic firewall; it does not prescribe technology *(KC-17 cascade)* |
| Rewrite the strategic baseline | The baseline is frozen under change control; changes follow the recorded reopening conditions |
| Summarize the governance disposition state | **The governance records are the sole authority on what is open, deferred, pending, or closed. This ADR does not restate it.** *(KC-7: the earlier form asserted "other decisions are closed", which interpreted the governance state without an authorizing record and was imprecise — several items are deferred or pending acceptance rather than closed.)* |

## 9. Appendix A: Artifact Classification — by reference, not restated

*(Corrections applied per Authority disposition of KC-4 and KC-12.)*

**The classification of artifacts is stated ONCE, at §3.1.1, and is not restated here.** §3.1.1 names the six **constraint-defining** artifacts individually with the reason each qualifies, and names what is **not** constraint-defining (C4-1 renders; C4-2 verifies; AFV-1, AIA-1, CCP-1, ERV-1, KBI-1, MCA, RET-1 document the transformation).

**Why by reference:** this appendix previously restated the taxonomy and, together with §4.1, produced **three incompatible classifications inside one ADR** — most consequentially the CDR, named constraint-defining at §3.1.1 and filed as a mere certification record in both restatements. **A restated taxonomy is a taxonomy that will diverge again** (KC-4). §3.1.1 governs.

**Two axes, kept separate** *(KC-12)* — the earlier single ✅/⚠️ column conflated them, which is how C4-2 acquired a ✅:

| Axis | Question | Where answered |
|---|---|---|
| **Constraint-defining?** | Does this artifact define constraints implementations must honor? | **§3.1.1** |
| **Implementation-agnostic?** | Is this artifact's content independent of the implementation paradigm? | **§3.1.2** (which defines the term) |

**They are orthogonal.** C4-2 is implementation-agnostic **and** not constraint-defining. Tactical DDD, deployment, runtime, and technology selection are **neither** — and §4 records that as *not assessed*, not as a negative claim.

---

## 10. Appendix B — EXTRACTED

**The non-normative illustrative realizations have been extracted to a companion document:** [`PKS_ADR_001_Companion_Illustrative_Realizations.md`](./PKS_ADR_001_Companion_Illustrative_Realizations.md).

**Why** *(KC-9, KC-1, KC-2, KC-10; recommendation R-1)*: the material illustrates this ADR's argument but **carried the implementation layer — technology stacks — which §4 declares out of scope**, creating an internal inconsistency. Two rows additionally asserted strategic content the disposed strategic model does not contain: **component realizations over AR-1, an architecturally undefined region** (AD-1 §6.2 permits no component there), and **an invented domain event**. **The extraction preserves the argument by reference and restores the strategic/tactical boundary; the two invented rows were removed rather than relocated.**

**Standing rule recorded at the extraction:** *a non-normative statement is still a statement.* No companion may assert the existence of a domain concept, component, event, or boundary that the disposed strategic model does not contain.

---

## 11. Appendix C — EXTRACTED

**The terminology-refinement record has been extracted to** [`PKS_ADR_001_Companion_Review_Record.md`](./PKS_ADR_001_Companion_Review_Record.md), together with the former §14.

**Why** *(KC-8; recommendation R-1)*: it is **review history**, and this program's practice is that findings apply to the object while **the record that raised them stands separately**. It is retained in full in the companion for its anti-drift value — it names the exact overclaims removed, so a future revision can recognize them if they reappear.

---

## 12. Appendix D: Open Questions

| # | Question | Status |
|---|----------|--------|
| 1 | Does the ADR need to be updated after AFV-F4 disposition? | OPEN |
| 2 | Should the non-normative realization examples be included in IBC-1? | OPEN |
| 3 | Does AI agent implementation require additional governance beyond IBC-1? **(Recorded lean: likely yes, and it would belong in the implementation program — a lean, not an answer.)** | **OPEN** *(KC-21: the Status column previously pre-answered the question, mixing Question with Interpretation)* |
| **5** | **ARB recommendation R-2 — should this ADR state as an explicit limit that it *resolves no open question*?** | **ACCEPTED and APPLIED (Disposition Commission, 2026-07-30)** — stated at §4.0. *(KC-21: R-2 was tracked only in the review record, not in the ADR's own register of open items, which is where a reader looks.)* |
| **4** | **ARB finding F-3.2 — does §3.1's clause *"shall remain the governing strategic baseline"* effect a promotion of the baseline as a side effect of adopting this ADR?** Promotion is a distinct pending Authority act (Package D → promotion; KBI-1: *not yet promotion-ready*). A candidate correction exists and is deliberately not applied: *"shall become, upon promotion, and thereafter remain, the governing strategic baseline until amended…"* | **DEFERRED TO THE AUTHORITY (2026-07-29)** — recorded, not corrected, because disposing it is itself an Authority act. **Consequence if left as written: adopting this ADR may bypass promotion-readiness verification** |

---

## 13. Traceability — **by reference to §7**

**The artifact relationships are stated ONCE, at §7** (§7.1 upstream · §7.2 downstream), and are not restated here.

*(**KC-22.** This section previously held a second, overlapping register of the same relationships, differing from §7 without explanation — §13 named the Retrospective, §7 named the downstream pair, and shared rows carried different relationship descriptions in each table. **Neither was wrong yet, which is exactly how KC-4 began** — the third instance of that pattern in this artifact. **§7 was retained as the canonical statement because it already carries the richer relationship semantics.** Additional artifacts the earlier §13 named and §7 does not: `PKS_Phase_II_Retrospective.md` — a record of the process, not a constraint (§3.1.1's not-constraint-defining set), reachable through the companion review record.)*

---

## 14. Review Record — EXTRACTED

**This ADR's review history has been extracted to** [`PKS_ADR_001_Companion_Review_Record.md`](./PKS_ADR_001_Companion_Review_Record.md), together with the former Appendix C.

**Why** *(KC-8; recommendation R-1)*: an embedded review record mixed **Historical Record** with **Decision** content, and created a **second, competing account of this ADR's status** alongside the metadata block. **The metadata block is now this ADR's sole status statement:** lifecycle *PROPOSED* · ARB Review Outcome *approved with minor revisions* · Authority Disposition *PENDING*.

---

**Decision: The PKS strategic architecture is implementation-agnostic and shall remain the governing strategic baseline until amended through the established governance process. Implementation-specific guidance belongs in Phase II.D, not in the strategic baseline.**

**Lifecycle Status: PROPOSED · ARB Review Outcome: APPROVED WITH MINOR REVISIONS (revisions applied) · Authority Disposition: PENDING.**

---

*Traceability: Based on Phase I Strategic Discovery (2026-07-27), ARB Rulings (DR-1..DR-6, 2026-07-28), Phase II Plan Approval (2026-07-28), M0-M8 execution, AD-1 Architecture Definition, C4-1/C4-2, AFV-1, AIA-1, CCP-1, ERV-1, MCA, CDR, RET-1, KBI-1, and ARB Chief review feedback (2026-07-29).*