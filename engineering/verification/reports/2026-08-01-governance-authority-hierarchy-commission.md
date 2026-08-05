# Governance Authority Hierarchy Commission

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** was each conclusion derived from the **highest applicable** governing authority, in the correct layer? Hierarchy verification only — no implementation review, no reinterpretation, no redesign of the hierarchy.
**Repository Integrity Gate:** ✅ PASSED.

> ## THE COMMISSION'S RESULT — TWO INVERSIONS, ONE UNDOCUMENTED PRECEDENCE
>
> **H-1** — `CLAUDE.md`, an **operational-guidance** document, asserts a **business-policy** claim (*"selections are anonymous"* · *"No vote coercion possible"*) that **Constitutional Policy 3 binds the architecture not to make.** A lower layer is asserting a higher layer's rule, and asserting it contrary to the higher layer.
> **H-2** — the AT-EVT-001 widening was justified from a **code docblock** citing EPIC-004K §11: an **implementation** artifact used as the source for an **architecture-layer** change.
> **H-3** — **ADR-T11 is labelled *"constitutional"* while sitting in the ADR layer**, and its precedence against Constitutional Policy 3 is **nowhere documented**. That is the escalation.

---

## 1. Governance authority hierarchy

The project **already documents one** (`.claude/IMPLEMENTATION_PROTOCOL.md`, amendment A-2). Recorded here, not redesigned:

```
Business Authority (constitutional decisions · ARB business rulings)
        ↓
Architecture Decisions (ADRs · ARB rulings)
        ↓
Governance (standards, ES-00x)
        ↓
Implementation Protocol (operational practice)
        ↓
Implementation
        ↓
Operational Evidence
```

> *"No protocol ever outranks architectural authority. Where this document and an issued ADR / approved design / governance ruling disagree, the authority wins."*

**Placing each authority actually used:**

| Authority | Layer | Evidence for the placement |
|---|---|---|
| **Constitutional Policies 1–4** (`EPIC-003 §THE FOUR DECISIONS`) | **Business Authority — constitutional** | ARB-ruled 2026-07-25; self-described *"binding"*; called *"binding inputs to Tactical DDD"* |
| **Q-2 parameter rulings** | **Business Authority** | *"the arithmetic is architecture; the numbers are the ARB's"* |
| **ADR-T11, T1–T22** | **Architecture Decisions** | the ADR log |
| **EPIC-004K** (§11/§57/§81/§142/§197) | **Architecture** — process architecture | an approved architecture document |
| **ES-00x · ADR-MP series** | **Governance / platform architecture** | standards index |
| **Implementation Protocol** | **Operational practice** | states so itself |
| **Cross-Context Integration Contract** | **Architecture (descriptive→partially normative)** | its own status line |
| **`CLAUDE.md` charter** | ⚠️ **Operational Guidance** | it is assistant-facing instruction ("PROJECT OVERVIEW", checklists) — **not** an ARB-issued authority |
| **`.claude/MEMORY.md`** | **Operational Guidance (pointer)** | names its own primaries |
| **Code docblocks** | **Implementation** | — |

## 2. Authority ownership matrix

| Authority | Owning governance body | Owning bounded context | Governs |
|---|---|---|---|
| Constitutional Policies 1–4 | **ARB (constitutional capacity)** | Constitutional Governance | **business policy** |
| Q-2 parameters | ARB | Constitutional Governance | **business policy** (durations) |
| ADR-T11 | ARB (architectural capacity) | Tactical Architecture | technical constraint on artifacts |
| EPIC-004K | ARB | Process Architecture (Adjudication) | process design |
| ADR-MP series | ARB | Messaging Platform | platform architecture |
| ES-00x | Engineering governance | Engineering Platform | engineering practice |
| Implementation Protocol | Operational (frozen) | — | how work proceeds |
| **`CLAUDE.md`** | ⚠️ **no governance body issues it** | — | **guidance — and it must not govern** |
| Docblocks · plans · reports | authors | — | explanation, evidence |

**The ownership finding:** `CLAUDE.md` and MEMORY have **no issuing authority**. They are the two artifacts I most often cited for constraints. **An unissued document cannot be a source of constraint** — it can only point to one.

## 3. Authority precedence matrix — cited vs governing

| Conclusion | Authority cited | Authority that actually governs | Identical? |
|---|---|---|---|
| Late ≠ redelivered | EPIC-004K §197 | §197 (architecture; no constitutional rule contradicts it) | ✅ **Yes** |
| Q-2 owns durations; APM enforces | §81 | **Q-2 (business)** for the *value*; §81 (architecture) for the *split* | ✅ Yes — both cited in their own scope |
| EPW = CW + MAD + LSM | §142 | **Policy 2 (constitutional)** — §142 *operationalizes* it | ⚠️ **Partly.** §142 is downstream. Conclusion unchanged; the **governing** authority is Policy 2, and its text adds a requirement §142 omits (*the Contestation Window must be explicitly defined*) |
| A timer must not conclude | Policy 4 | **Policy 4 (constitutional)** | ✅ Yes — verified against primary last commission |
| "The trail violates anonymity" | ADR-T11 (via charter) | **Constitutional Policy 3 (CL-1/CL-2)** | ❌ **No — wrong layer AND wrong authority.** Withdrawn |
| "The charter's *(anonymous)* claim is inaccurate" | my own reading | **Policy 3's binding vocabulary correction** | ❌ **No — I under-cited.** Now authority-supported |
| AT-EVT-001 may be widened | EPIC-004K §11 **via a docblock** | §11 (architecture) — read at source | ❌ **No — H-2 inversion** |
| Registration ≠ Delivery | PB-006 via docblock/MEMORY | PB-006 (ARB ruling) | ⚠️ sourcing inverted; conclusion consistent |

**Pattern:** where I cited **architecture** for questions that **constitutional policy** governs, the conclusion was wrong or under-supported. Where architecture genuinely governed (§197, §81), it was right.

## 4. Cross-authority consistency review

| Expected relationship | Holds? |
|---|---|
| ADRs **operationalize** constitutional policies | ⚠️ **Mostly** — §142 operationalizes Policy 2 correctly. **But ADR-T11 vs Policy 3 is unresolved (H-3)** |
| Policies **constrain** ADRs | ⚠️ **Unverifiable for T11** — no precedence statement exists |
| Architecture **follows** policy | ✅ EPIC-004K cites Policies 2 and 4 explicitly |
| Implementation **follows** architecture | ✅ WP-6's ADPR/AGIR verified this |
| **No higher rule derived from a lower artifact** | ❌ **VIOLATED twice — H-1 and H-2** |

### H-3 in full — the one genuine precedence gap

| | **ADR-T11** | **Constitutional Policy 3** |
|---|---|---|
| Layer | **Architecture Decision** | **Business Authority — constitutional** |
| Self-label | *"constitutional, build-breaking"* | *"binding"* |
| On linkage | *"no voter↔vote linkage in any aggregate, event payload, or projection"* | the code→vote linkage *"is an intentional design trade-off … **NOT an architectural defect at this stage**"*, bounded by CL-1/CL-2/CL-3 |

**They are reconcilable** — T11 governs greenfield *artifact shapes*; Policy 3 governs the *custodial mechanism* — but **nothing states that reconciliation**, and **ADR-T11 self-describes as "constitutional" while sitting one layer below constitutional governance.** Under the documented hierarchy Policy 3 outranks it; under T11's own label they appear to be peers. **A reader can reach opposite conclusions depending on which cue they follow — which is exactly what happened to me.**

## 5. Escalation package — unresolved precedence

**Only questions where multiple authorities legitimately apply.**

### H-3 · ADR-T11 vs Constitutional Policy 3

| Field | Content |
|---|---|
| **Authorities** | ADR-T11 (Architecture, self-labelled *constitutional*) · Policy 3 (Business Authority, *binding*) |
| **The question** | When both address voter↔vote linkage, **which governs** — and does T11's *"constitutional"* label grant constitutional precedence, or describe *severity* within the ADR layer? |
| **Why I cannot resolve it** | No precedence statement exists between them. The documented hierarchy places Policy 3 higher; T11's own label suggests parity. **Resolving it by reasoning would be me assigning precedence** |
| **If Policy 3 governs** | linkage is accepted at Phase 1 under CL-1/CL-2/CL-3; T11 constrains *artifact shapes* within that envelope; **C-1 is decided on CL-1/CL-2, not on T11** |
| **If T11 governs** | linkage is prohibited in the enumerated kinds regardless; Policy 3's custodial acceptance is narrower than its text suggests |
| **Decision required** | Precedence, and whether *"constitutional"* on an ADR is a **layer claim** or a **severity label** |

### H-1 · Charter asserting business policy

| Field | Content |
|---|---|
| **Authorities** | `CLAUDE.md` (Operational Guidance, unissued) · Policy 3 (binding) |
| **The question** | The charter states *"selections are anonymous"* and *"No vote coercion possible"*; Policy 3 binds the architecture **not** to state the system is anonymous. **Correct the charter, or is the charter outside "the architecture"?** |
| **Ambiguity** | only whether an assistant-facing charter counts as *"the architecture"* |
| **Decision required** | Confirm the correction (**C-6**), and whether guidance documents may carry policy claims at all |

## 6. DDD governance assessment

| Check | Result |
|---|---|
| Business rules owned by business governance | ⚠️ **Partly** — Policies are ARB-owned, **but the charter restates one incorrectly (H-1)** |
| Technical decisions owned by technical governance | ✅ ADRs, EPIC-004K, ES-00x all ARB/engineering-owned |
| **No implementation artifact as business authority** | ❌ **Violated (H-2)** — a docblock sourced an architecture change |
| Authority inversion | ❌ **Two found** (H-1, H-2) |
| Each authority in the correct bounded context | ✅ Yes — Constitutional Governance · Tactical Architecture · Process Architecture · Messaging Platform · Engineering Platform are all distinct and correctly placed |

**No authority sits in the wrong context.** The failures are **inversions of use**, not misplacements of ownership — which is a better problem to have, because it is corrected by discipline rather than by restructuring.

## 7. Permanent governance recommendation

**Two rules, both stated as checks:**

> **1. AUTHORITY LEVEL CHECK — before asserting a constraint, name its layer and confirm no higher layer speaks to the same question.**
> *"Which authority did I read?"* is necessary but insufficient. The question is **"which authority governs?"** A correctly-read architecture document is the wrong source for a question constitutional policy answers.

> **2. A governing authority is interpreted INSIDE ITS GOVERNING CONTEXT** — the ARB's generalization, adopted over my narrower *"read the neighbours."*
> *Neighbours* was documentation advice. The real rule is DDD: an authority's meaning is fixed by its **related invariants, surrounding decisions, governing assumptions and bounded context** — which is why Policy 3's CL-1/CL-2/CL-3 could not be recovered from a one-line summary of Policy 4, however accurate that line was.

**Practical form:** *can I quote it · do I know what governs it · do I know what it governs?*

**Recorded as method, deliberately NOT promoted to a standard** (R-38/R-39). Filed with the un-promoted observations.

---

**Traceability:** documented hierarchy — `.claude/IMPLEMENTATION_PROTOCOL.md` (A-2 canonical hierarchy + precedence table) · **primary authorities:** `EPIC-003_Tactical_DDD_Entry_Assessment.md` §THE FOUR DECISIONS (Policies 1–4, CL-1/CL-2/CL-3, binding vocabulary correction) · `ADR-T-LOG-Tactical-Implementation.md:18` (ADR-T11, incl. its *"constitutional"* label) · `EPIC-004K` §11/§57/§81/§142/§197 · `CLAUDE.md:61–66` (the charter's anonymity claims). **No conclusion changed; no precedence assigned; no hierarchy redesigned.**
