# KnowledgeOS — Kernel Boundary Definition — Next-Agent Handoff & Commission

> **Role:** the handoff / commission for the **next governed actor** in the KnowledgeOS architectural decision chain. **Self-contained** — everything a fresh agent (with no prior session context) needs to continue from here. Write for an executor who has never seen this conversation.
> **What you are about to do:** the **final architectural consolidation + Kernel boundary definition** — the *last DDD step* before the Kernel implementation decision. This is a **governed architecture act**: discover-and-decide from existing law, produce a decision-support deliverable, **STOP for the HPA ruling**. It is **not** research, **not** implementation, and **not** Kernel implementation design. **The Kernel boundary itself is the subject of this act; its internal realization remains deferred** (DEF-1).
> **Status:** HANDOFF · the **F-1…F-5 decision support is DELIVERED** (2026-08-23) and engaged by the HPA · the **Kernel boundary remains UNDEFINED** · **the Kernel still waits**.

---

## 1 · Read this first

**Who you are.** You are the next actor in a governed decision chain under the **Human Principal Architect (HPA)**. Your commission is bounded: execute the act described in **§7**, nothing more. You are an executor and a **decision-support provider**, never an authority. **R-34:** you never accept your own work — you supply **evidence** and **recommendation**; the HPA **rules**. Keep evidence · recommendation · authority rigorously separate; the strongest statement you make never exceeds the available evidence.

**The one-paragraph state of the program.** KnowledgeOS is a governed architecture for a knowledge platform whose purpose is to **preserve the identity and justified life of epistemic states**. The architecture is FROZEN at **Reference Architecture v1.1** (the "Constitution" and the eleven invariants). All research is **CLOSED**. A series of governed decisions — **OQ-2 · OQ-3 · OQ-5 · F-1…F-5** — has closed the **domain-discovery phase**: the minimum domain capabilities and responsibilities that must exist *before the Kernel boundary can legitimately be defined* are now established, and every one of them is **already existing law** (F-1…F-5 established no new capability — they *pinned* the KnowledgeCore's inbound admission pipeline). The next governed act is the **Kernel boundary definition**, commissioned by the HPA. **The Kernel itself remains undefined and unimplemented — the Kernel still waits.**

**What is frozen / what you may not touch** (absolute list — a violation of any line is a governance failure, not a style choice):

- **No v1.2.** No modification of Reference Architecture v1.1, the Constitution, the aggregate, the Kernel, the SNF mechanisms, or the research corpus.
- **No new law.** No new article, invariant, aggregate, member, event, bounded context, or register row. Register **25+4 unchanged** · invariants **11→11** · members **12→12** · contexts **6→6**.
- **No research.** SNF research **CLOSED**. **OQ-4** (KOS-SCB v0.2) **UNAUTHORIZED**. Research posture: **inform, never initiate** — if the boundary question cannot be resolved from existing law, record it in **Unresolved questions** and STOP; never start a research track.
- **No code.** No schema, API, class, interface, port, adapter, framework, or technology choice.
- **No DSL, no parser design, no FST implementation, no architecture redesign.**
- **No amendment applications.** The **F-1…F-5 Port Contract amendment candidates** stay **recorded, not applied** (HPA-gated). The Port Contract stays **PROPOSED · NON-AUTHORITATIVE**.
- **Do not reopen** OQ-2 · OQ-3 · OQ-5 · OQ-4 · AH-1…AH-5 · the SNF research track.
- **No Sanskrit / Pāṇinian grammar into the Kernel.** Natural-language handling lives at Expression / mechanism altitude, never core (**Test F**).

---

## 2 · The domain, in DDD terms

### 2.1 What KnowledgeOS is

A platform that manages **epistemic states** — justified beliefs — under three constitutional guarantees:

1. **Identity** — every knowledge item has an **identity-of-meaning**, **assigned at creation, never derived** (from similarity, observable match, coextension, or canonical-form equality).
2. **Lifecycle** — every transition is a named, **forward-only** state transition; nothing is overwritten; history is preserved.
3. **Admissibility** — **nothing enters knowledge except through a single, domain-owned admission gate** (the Verification Port), and only with a **preserved justification path**.

**In DDD terms:** KnowledgeCore is the **core domain**. The KnowledgeAggregate is the **consistency boundary** at which constitutional admissibility of a state transition is determined. Everything else is a mechanism or a supporting context at a port.

### 2.2 The six bounded contexts (v1.1 §5.3)

| Context | Role | Owns | Notes |
|---|---|---|---|
| **KnowledgeCore** | CORE | KnowledgeAggregate · ConflictRecord — **all eleven invariants** | upstream of everything; consumes candidates via ports |
| **Authority** | SUPPORTING | AuthorityGrant | the core **references**, never holds authority |
| **Projection** | SUPPORTING | DerivedView | regenerable, non-authoritative, **no write-back** |
| **Decision Boundary** | SUPPORTING | DecisionRecord | informs, **never executes** |
| **Reasoning & Validation** | GENERIC/EXTERNAL | its own transient artifacts — **no domain state** | adapter at the **Verification Port**; bound by the port contract |
| **Expression** | GENERIC/EXTERNAL | its own transient artifacts — **no domain state** | adapter at the **Expression↔Meaning Port (ACL)**; bound by the port contract |

**The port discipline:** Expression and Reasoning & Validation are external; they produce **candidates** that cross ports as **port-contract vocabulary (proposals)** — never as domain state, never as members.

### 2.3 The core: the KnowledgeAggregate (v1.1 §6)

**Root:** `Knowledge`, identified by **KnowledgeId**. **The KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined.** No member is reachable from outside except through the root; no transition is admitted that violates an invariant.

**The twelve members:**

| Member | Kind | Responsibility (condensed) |
|---|---|---|
| **KnowledgeId** | Value object | identity-of-meaning; assigned once; **never derived** |
| **Meaning** | Value object | the **admitted** intensional content — ⟨C-2⟩; a mechanism's output is a *meaning candidate outside the boundary* and becomes Meaning **only by admission** |
| **ContextTuple** | Value object | the delimiting conditions beyond which a claim is incomplete |
| **EvidenceLinks** | Collection | justification evidence **references** + acquisition method + reliability conditions; **⟨C-3⟩** the core owns the *links*, never the evidence *content* |
| **JustificationPath** | Value object | the reasoning path: premises · rules · assumptions · inference rule · conclusion; the *record* of reasoning, the *process* is external |
| **Authority** | Reference | recorded reference to a human act; held by Authority context, referenced by the aggregate |
| **Agency** | Value object | epistemic lineage; the one member whose **absence rejects creation** |
| **EpistemicState** | Value object | VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE; **no mechanism may ever produce this member** |
| **TemporalValidity** | Value object | valid-from · valid-until · superseded-by; freshness never truth |
| **Confidence** | Value object (governed) | a **structured** epistemic attribute, carried as Temporal Epistemic Metadata (UM-46), never a scalar knowledge-quality score; **⟨R-1⟩ assigned INSIDE the boundary** — a mechanism-supplied score must never cross the port and become Confidence (OQ-5: ruled **ACCEPT**, member stays) |
| **History** | Value object (immutable) | forward-only revision log; supersession never overwrites |
| **Relations** | Collection | relationship links to other aggregates — ⟨C-4⟩ by KnowledgeId reference only, never containment |

**ConflictRecord** (second aggregate of the core): conflicting knowledge coexists as **CONFLICTED** until governed resolution; the record survives resolution forward-only. Supporting aggregates: **AuthorityGrant · DerivedView · DecisionRecord** (§6.2).

### 2.4 The eleven invariants (v1.1 §7)

| Invariant | Statement (condensed) |
|---|---|
| **INV-KOS-IDENTITY-001** | Identity is **assigned, never derived**; similarity never becomes identity; ⟨C-1⟩ canonical-form equality is a similarity claim, not an identity determination |
| **INV-KOS-DIMENSION-001** | Dimensions evolve independently; **no scalar surrogate for structure** — ⟨R-1⟩ no mechanism-supplied score becomes Confidence |
| **INV-KOS-AUTHORITY-001** | Authority is **assigned, never emergent**; evidence/assessment/source never self-authorize |
| **INV-KOS-DECISION-001** | Knowledge **informs, never executes** |
| **INV-KOS-PROJECTION-001** | A projection is **never its source**; regenerable, no write-back |
| **INV-KOS-VERIFICATION-001** | **No entry into knowledge without a preserved justification path**; generation never becomes justification; observation never becomes inference — enforced at the **Verification Port — the only admission path** |
| **INV-KOS-FAILURE-001** | Failed reasoning is preserved as an explicit state (REJECTED); never knowledge, never silently discarded |
| **INV-KOS-CONTRADICTION-001** | Conflicting knowledge coexists as CONFLICTED until governed resolution; challenge never destroys identity |
| **INV-KOS-UNKNOWN-001** | UNKNOWN is first-class; unknown ≠ absent ≠ false; ⟨C-5⟩ a mechanism's inability to determine meaning maps to UNKNOWN, never to ABSENT/FALSE/low-confidence accept |
| **INV-KOS-AGENCY-001** | Every state preserves epistemic agency; knowledge is never anonymous |
| **INV-KOS-HISTORY-001** | Revision never deletes; supersession is forward-only |

### 2.5 The events (v1.1 §8)

`KnowledgeCreated` · `EvidenceAdded` · `BeliefRevised` · `MeaningTranslated` · `ContradictionDetected` · `ContradictionResolved` · `KnowledgeSuperseded` · `KnowledgeRejected` · `AuthorityAssigned` · `DecisionInformed`.

**⟨A-3⟩ Event admission guard:** an event records a **domain state transition**, never a technical operation. *Not events:* a parse · a normalization · a canonical-form computation · a database write · an LLM response · a benchmark run. If nothing in the aggregate changes, there is **no domain event**.

### 2.6 Altitudes (v1.1 §16) — the three-plate model

| Altitude | What it is | May it change? |
|---|---|---|
| **KERNEL — must exist** | the constitutional invariants and the boundary that enforces them | **No** — amendment by the HPA only |
| **MECHANISM — may change** | how responsibilities are carried out (validation · reasoning · LLM · Semantic Compiler (candidate) · normalizers) | **Yes** — free evolution, subject to *no evolution violates an article* |
| **REPRESENTATION — projection** | how information is encoded or projected (evidence records · context tuples · epistemic state model · verdict vocabulary · derived views · **canonical forms (SNF)**) | **Yes** — regenerable, non-authoritative |

**The kernel does not reason.** The kernel altitude contains no reasoner. The aggregate enforces invariants; engines at its ports do the reasoning. A kernel member can refuse a transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**. In DDD terms: *the aggregate has no dependency on any engine*.

### 2.7 Dependency rules (v1.1 §18)

- **D-1** The core depends on **nothing**.
- **D-2** Mechanisms depend on the core's published language, **never the reverse**.
- **D-3** Supporting contexts serve the core and hold what the core must not.
- **D-4** Every inbound arrow is a **port with an anti-corruption layer**; nothing enters by shared model or shared storage.
- **D-5** Every outbound arrow is **advisory or projection**; nothing leaves as authority, and **nothing written outside returns as knowledge**.
- **D-6** Replaceability is a property, not a hope.

### 2.8 The Expression↔Meaning Port Contract (r4)

`docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md`. Structure: §1 pipeline (Expression → Port/ACL → Verification Port → knowledge state) · §2 six obligations (each tracing to an invariant) · §3 Q1–Q8 · §4 candidate payload vocabulary (**r4-4**: port vocabulary is **never an aggregate member**) · §5 trust boundary (mechanism-owned vs domain-owned) · §6 what it does NOT decide · §7 quality gates. **Status: PROPOSED · NON-AUTHORITATIVE.** The five F-1…F-5 amendment candidates (LA Review-01 §4) are **recorded, not applied** — their application and the contract's ratification are **separate HPA acts**.

---

## 3 · What has been established — the governed decision chain

| # | Act | Status | Record |
|---|---|---|---|
| 1 | **P5** — Semantic Competition acceptance | ✅ HPA ACCEPTED | `20260822-2327` |
| 2 | **AH-1…AH-5** — decision support delivered | 📋 DELIVERED · decision fields ⬜ OPEN (HPA) | `20260822-2334` |
| 3 | **EP-01 · T-2/T-3** — Port Contract seven-site edit + G-1…G-8 gate verification | ✅ EP-02 APPROVED → ACCEPTED (HPA) | `20260823-0029` |
| 4 | **T-5** — v1.1 reassessment | ✅ ACCEPTED (HPA) — v1.1 REMAINS, no change required | `20260823-0829` |
| 5 | **OQ-2** — may SNF-equivalence be an EvidenceLink? | ✅ HPA ruled ACCEPT · RESOLVED · CLOSED | `20260823-0931` |
| 6 | **HPA research directive** | ✅ recorded — research: inform, never initiate | MEMORY/CONTEXT/session log |
| 7 | **OQ-3** — cross-language sameness: meaning or translation? | ✅ HPA ruled ACCEPT · RESOLVED · CLOSED | `20260823-0953` |
| 8 | **OQ-5** — should Confidence remain an aggregate member? | ✅ HPA ruled ACCEPT · RESOLVED · CLOSED | `20260823-1014` |
| 9 | **F-1…F-5** — domain capabilities before the Kernel boundary | ⭐ DELIVERED · all five RESOLVED FROM EXISTING LAW · amendments ⬜ OPEN (HPA) | `20260823-1051` |
| 10 | **← YOU ARE HERE** | **Kernel boundary definition** — commissioned by the HPA | this handoff |

**Remaining open items (recorded, not resolved — each requires a separate HPA act):**
- **F-1…F-5 amendment candidates** — apply or not (Port Contract wording; ⬜ OPEN).
- **AH-4** — *metric version as part of any measured-competence claim* (research governance; ⬜ OPEN).
- **AH-5** — *asserted vs possible: category boundary or degree?* (modelling, OQ-3 territory; ⬜ OPEN).
- **DEF-1** (boundary realization) · **DEF-4** (SNF as port encoding) · **DEF-5** (storage/schemas/APIs/classes) — Logical/Implementation Architecture.
- **OQ-4** (KOS-SCB v0.2) — **UNAUTHORIZED**.
- **Port Contract ratification** — PROPOSED · NON-AUTHORITATIVE.

---

## 4 · The F-1…F-5 result — the Domain Capability Map

**What F-1…F-5 are:** the five Port Contract **amendment candidates** recorded in LA Review-01 §4 (`20260822-1611`), each a wording/precision finding, each gated on HPA approval, none applied — analyzed as **domain questions** to establish what must exist *before* the Kernel boundary can legitimately be defined.

**Verdict — one line: F-1…F-5 establish NO new domain capability.** Each encodes a domain fact that is **already existing law**; they collectively **pin** (make explicit, make unambiguous) the **KnowledgeCore's inbound admission pipeline**.

### 4.1 MUST EXIST — authoritative domain responsibilities the Kernel boundary will protect

| Capability | Established by | Existing law |
|---|---|---|
| **Single-gate admission** — the only admission path into epistemic state, owned by the aggregate boundary | F-1 | INV-KOS-VERIFICATION-001 · P-2 · D-5 |
| **Contract-conformance enforcement** — published language: mechanisms conform to the six obligations; conformance ≠ admission | F-1 | §5.2 ACL · Port Contract §2 · §3-Q6 |
| **Identity assignment** — assigned at the aggregate root, never derived or proposed | F-2 (grounding) | INV-KOS-IDENTITY-001 · P-4 |
| **Evidence admission** — offered observations become admitted EvidenceLinks only by domain decision | F-2 | ⟨C-3⟩ · INV-KOS-IDENTITY-001 |
| **Justification preservation + sufficiency evaluation** — the admitted record is domain-owned | F-5 | INV-KOS-VERIFICATION-001 · §16 · JustificationPath member |
| **History recording** — forward-only, admitted transitions only | F-4 | §8 · D-5 · ⟨A-3⟩ |
| **Representation-agnostic candidate intake** — no surface form is core | F-3 | Test F (§2 · §11.2) |
| **Epistemic-state determination** — the domain alone determines the seven states | F-1 grounding | §9 · §10 · obligations 1 · 6 |
| **Confidence assignment inside the boundary** | OQ-5 — already resolved law, cited, **not reopened** | ⟨R-1⟩ · OQ-5 record |

### 4.2 MAY EXIST — mechanism-side, behind the port, **never** in the kernel

Candidate production / reasoning (Reasoning & Validation) · representation translation / normalization / canonicalization incl. SNF (Expression · §16) · declared-insufficiency self-declaration (port vocabulary §4) · candidate-side evidence offering / collision observations (port vocabulary Q8) · mechanism-side / infrastructure retention of non-admitted artifacts (F-4).

### 4.3 MUST NOT EXIST IN KERNEL

A second admission path (F-1 · INV-KOS-VERIFICATION-001) · a hidden candidate store / hidden semantic aggregate (F-4 · D-5 · ⟨A-3⟩) · natural-language interpretation as a core capability (F-3 · Test F) · mechanism-authored identity assignment (F-2 · INV-KOS-IDENTITY-001) · mechanism Confidence score crossing as Confidence (OQ-5 · ⟨R-1⟩).

### 4.4 OUTSIDE KNOWLEDGEOS DOMAIN (Expression / external contexts)

Surface-form handling (any language, any medium — F-3) · reasoning engines (validation · debate · fallacy detection — F-5) · normalization / canonical-form computation (F-4).

**The one-sentence synthesis:** the KnowledgeCore's **inbound admission pipeline** — single gate → conformance → identity assignment → evidence admission → justification preservation/evaluation → history recording, representation-agnostic, with the domain alone determining epistemic state and Confidence — is the cluster of minimum domain capabilities that must exist before the Kernel boundary can legitimately be defined. **Every capability is already law.**

---

## 5 · Kernel Boundary Preconditions (inputs, NOT the boundary)

From the F-1…F-5 record §9 — the conditions that must hold **before the Kernel boundary can legitimately be defined**:

| # | Precondition | Status |
|---|---|---|
| 1 | The nine MUST EXIST capabilities are identified as the domain-owned responsibilities the boundary will protect | ✅ RESOLVED FROM EXISTING LAW |
| 2 | Mechanism-side production (reasoning · representation · insufficiency declaration · evidence offering) stays outside the kernel | ✅ RESOLVED FROM EXISTING LAW |
| 3 | The invariants to preserve are enumerated (eleven-invariant set, unchanged) | ✅ UNCHANGED (11→11) |
| 4 | The crossing discipline is settled: anything crossing is **port vocabulary** (r4-4), a **proposal**, never a determination | ✅ RESOLVED FROM EXISTING LAW |
| 5 | The preservation discipline is settled: History-at-admission or mechanism-side — **never domain state** | ✅ RESOLVED FROM EXISTING LAW |
| 6 | The two-gate structure is recognized as law: conformance ≠ admission | ✅ RESOLVED FROM EXISTING LAW |
| 7 | Representation-agnosticism is recognized as law: no surface form is core (Test F) | ✅ RESOLVED FROM EXISTING LAW |
| 8 | Two-sided ownership is recognized as law: mechanisms produce; the domain disposes | ✅ RESOLVED FROM EXISTING LAW |
| 9 | The boundary is defined against the delivered Port Contract with its status recorded (**PROPOSED · NON-AUTHORITATIVE**; the F-1…F-5 amendments and ratification are separate HPA acts the boundary must not assume) | ⬜ PENDING (HPA) |
| 10 | Open items the boundary must respect but not resolve (DEF-1 · DEF-4 · DEF-5 · OQ-4) | ⬜ OUT OF SCOPE — recorded, not resolved |

**The preconditions do not design the Kernel.** They state what must be true about the domain before the boundary can legitimately be defined. §4 shows all the domain facts are already law.

---

## 6 · The HPA's working hypothesis (2026-08-23) — for the boundary act to verify, NOT settled law

The HPA engaged the F-1…F-5 delivery and refined the Kernel terminology. This is the **leading hypothesis** the boundary act must test adversarially — it is a recommendation for the act, **not** an already-decided boundary:

1. **The Kernel is NOT a generic rule evaluator.** A `(command, state, rules, evidence) → admissibility verdict` formulation is **too small** — it has no home for the authoritative responsibilities the law already assigns to the domain (identity assignment · evidence admission · justification preservation/sufficiency evaluation · epistemic-state determination · confidence · history · single-gate admission). The Kernel must **protect an actual domain boundary**.
2. **"Kernel = constitutional validator" is a metaphor, not the domain definition.** Constitutional admissibility is **one responsibility inside** the boundary — not the whole. The central domain act is richer: `candidate → verification → admissibility → identity assignment → epistemic state → evidence/justification/confidence → history → KnowledgeCreated` — the **inbound admission pipeline** F-1…F-5 pinned.
3. **Working formulation to verify:** *the Kernel is the authoritative admission boundary of KnowledgeCore.* Whether "Kernel" names the entire KnowledgeCore boundary or a stricter subset is exactly what the boundary act must decide.
4. **F-3 implication:** natural language is **one representable surface**; the **candidate shape is invariant**. The Sanskrit / FST / Semantic-Compiler research therefore has a clean home at **Expression / Interpretation**, **never in the Kernel**.
5. **F-4 implication:** **no hidden semantic aggregate.** Non-admitted candidates are **not KnowledgeOS domain state** — recorded in History as part of an admitted transition, or retained mechanism-side/infrastructure-side. **KnowledgeAggregate remains the authoritative core aggregate** (with ConflictRecord as the second core aggregate and the three small supporting aggregates — AuthorityGrant · DerivedView · DecisionRecord); **no additional hidden candidate/semantic aggregate may be introduced**; a "candidate store" is forbidden.

The boundary act may **confirm, refine, or refute** this formulation — but must do so **from existing law**, and must record each claim as hypothesis → evidence → verdict, with the strongest statement never exceeding the evidence.

---

## 7 · THE NEXT ACT — the commission (verbatim-in-substance from the HPA)

### 7.1 The one question to answer

> **Given the existing KnowledgeOS domain law, the F-1…F-5 capability map, the OQ-2/OQ-3/OQ-5 rulings, and the current aggregate/invariants — what is the smallest authoritative KnowledgeCore boundary that can satisfy all existing invariants without importing responsibilities from Expression, Reasoning & Validation, Governance, Workflow, Evidence mechanisms, or Infrastructure?**

### 7.2 The deliverable must produce ONLY these thirteen items

1. **Kernel boundary**
2. **Inside**
3. **Outside**
4. **Aggregate(s)**
5. **Domain services**
6. **Value objects**
7. **Commands**
8. **Domain events**
9. **Invariants**
10. **Required ports**
11. **Explicit anti-capabilities**
12. **Unresolved questions**
13. **Implementation preconditions**

### 7.3 Exclusions (absolute — verbatim)

**No code. No technology choice. No new research. No DSL. No parser design. No FST implementation. No architecture redesign.**

### 7.4 Two-phase shape

- **Phase 1 · Architectural consolidation.** Assemble the **consolidated input set**: v1.1 law · the F-1…F-5 Domain Capability Map (§4 of this handoff) · the OQ-2/OQ-3/OQ-5 rulings · the current aggregate/invariants · the Kernel Boundary Preconditions (§5). Record the disposition status of every remaining open item (F-1…F-5 amendments · AH-4 · AH-5 · DEF-1/4/5 · OQ-4 · Port Contract ratification) — **recorded, not decided**; each keeps its owner. Produce **no new law**.
- **Phase 2 · Kernel boundary definition.** Answer the §7.1 question, producing exactly the thirteen items of §7.2. For each of the nine MUST EXIST capabilities (§4.1), state whether it is **inside** or **outside** the proposed boundary and under which invariant it is protected. For each MUST NOT EXIST IN KERNEL prohibition (§4.3), confirm the boundary excludes it. Honour precondition 9: record the Port Contract's PROPOSED · NON-AUTHORITATIVE status and do **not** assume the F-1…F-5 amendments or ratification.

### 7.5 Deliverable format and status

The deliverable is a **decision-support record** (a governed document, house style `docs(knowledgeos): …`, placed per ES-004.2 / doc-placement). Status: **PROPOSED · NON-AUTHORITATIVE · decision ⬜ OPEN (HPA)**. It is the **last DDD step**: the HPA rules on the proposed boundary; then come **Kernel capability mapping** and the **implementation decision** (both future separate acts — **not** yours).

### 7.6 Completion discipline (R-34)

- The act **produces** the boundary; a **separate, adversarial DDD critique** of the consolidated boundary **verifies** it (the producer and the verifier must not be the same role). If you perform both, keep them in separate passes and label the passes explicitly.
- The boundary is a **proposal to the HPA**. You do not accept it; you do not implement it; you do not begin capability mapping.
- Apply the **ZERO lens** (§7.7) in both passes — for every boundary responsibility, in the producer pass and in the adversarial critique.

### 7.7 The ZERO lens — test the boundary from the absence case

For **every boundary responsibility**, determine the zero/absence case:

> **What happens when the required information, evidence, meaning, justification, authority, confidence, or interpretation is missing, unknown, contradictory, or insufficient?**

Test the boundary from **both directions**:

```text
           NORMAL CASE                          ZERO CASE
               ↓                                    ↓
Candidate → Admission → Knowledge        Candidate → Insufficient / Unknown / Conflict
                                                    ↓
                                           What does the domain do?
```

Ground the test in the P5 distinction, preserved as the deepest discovery of the prior acts:

- **absence ≠ unknown · unknown ≠ false · uncertainty ≠ rejection · lack of evidence ≠ evidence of absence** (INV-KOS-UNKNOWN-001 · ⟨C-5⟩ · INV-KOS-FAILURE-001 · INV-KOS-CONTRADICTION-001).

Every boundary responsibility's zero case must resolve to a **lawful epistemic state** (UNKNOWN · ABSENT · REJECTED · CONFLICTED) or a **mechanism-side retention** — never a silent gap, never a second admission path, never a hidden aggregate. If the boundary has no answer for a zero case, that is a boundary defect to record — not an excuse to leave the case undefined.

---

## 8 · Governance rules you MUST follow

1. **The Operating Loop** — you are executing governed-architecture phases: *1 Business capability → 2 Strategic DDD → 3 Canonical Discovery → 4 Tactical DDD → 5 Stewardship → 6 Impact classification → 7 Readiness → 8-10 RED/GREEN/VERIFY → 11 Acceptance*. For this act, the relevant obligations: **canonical discovery** (does the capability/aggregate/port already exist? If one exists, consume or extend it — never create a second; **ES-005.4**), **strategic DDD** (which bounded context OWNS the boundary? — KnowledgeCore; does ownership change? — No; never proceed with ownership unresolved), **impact classification** before recommending, and **acceptance** (supply evidence; never accept your own work — R-34).
2. **EP-01 Plan First.** The HPA's commission scope (§7 of this handoff) is the approved plan for this act. Do not deviate. **If the deliverable would invalidate the approved scope: STOP, explain why, present the revised plan, wait for approval — never silently change direction.**
3. **Research posture: inform, never initiate.** Unresolvable-from-existing-law → record in **Unresolved questions** and STOP for the HPA. Never start a research track. OQ-4 stays UNAUTHORIZED.
4. **Documentation discipline.** Establish classification before placement; resolve placement with `php scripts/doc-placement.php`; **exit code 2 = unruled → record PENDING and escalate**; invent no category and no folder. Plans: ES-004.2 (`./docs/plans/YYYYMMDD-HHMM-<what>-plan.md`). Lifecycle sync at closure: ES-004.3 (Runtime: plan status + CONTEXT · Historical: session log, append-only · Decision: acceptance record — decision text and history are **never rewritten**).
5. **Edit safety.** No automated search-and-replace on source code. Read-then-Edit, smallest scope, show the diff, verify. **Do not commit pre-existing untracked files from other sessions** — stage only your own files. Never use bare `git stash`/`git stash pop`. No push to main/master, no force-push, no merge — push only to `origin/kos-v11-ddd-refinement`.
6. **STOP after delivery.** Deliver the record → **STOP for the HPA ruling**. Never proceed to the next act (capability mapping, implementation) without a separate commission. **The Kernel still waits.**

---

## 9 · Canonical source files (read these)

- **Reference Architecture v1.1 (the law):** `docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md` — §4 core · §5.2/5.3 context map/table · §6 aggregate · §7 invariants · §8 events/⟨A-3⟩ · §9 epistemic lifecycle · §16 altitudes · §17 rejected concepts · §18 dependencies · §20 deferrals.
- **Expression↔Meaning Port Contract (r4):** `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` — PROPOSED · NON-AUTHORITATIVE.
- **LA Review-01 (F-1…F-5 canonical source):** `docs/knowledgeos/reviews/20260822-1611-KOS-LA-Review-01-Expression-Meaning-Boundary.md` — §4 amendment candidates.
- **F-1…F-5 decision support (the capability map + preconditions):** `docs/knowledgeos/reviews/20260823-1051-KOS-EP01-F1-5-domain-capabilities-before-kernel-boundary.md`.
- **OQ-2 / OQ-3 / OQ-5 decision records:** `docs/knowledgeos/reviews/20260823-0931-…OQ2…` · `20260823-0953-…OQ3…` · `20260823-1014-…OQ5…`.
- **AH-1…AH-5 decision support:** `docs/knowledgeos/reviews/20260822-2334-KOS-EP01-AH1-5-decision-support.md`.
- **Governance:** `.claude/CLAUDE.md` (EP-01, the Operating Loop, R-34, ES-004.2/3) · `engineering/governance/ES-004-Documentation.md` · `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`.

---

## 10 · Completion report format for your deliverable

End your record with this YAML (house format):

```yaml
act: KERNEL-BOUNDARY-DEFINITION
status: COMPLETED | BLOCKED
research_required: NO
research_track_opened: NO
implementation_performed: NO
kernel_designed: NO
kernel_implemented: NO
domain_model_changed: NO
aggregate_changed: NO
invariants_changed: NO
vocabulary_changed: NO
constitution_changed: NO
v1_1_changed: NO
boundary:           # your 13-item deliverable in summary form
  kernel_boundary:
  inside:
  outside:
  aggregates:
  domain_services:
  value_objects:
  commands:
  domain_events:
  invariants:
  required_ports:
  anti_capabilities:
  unresolved_questions:
  implementation_preconditions:
consolidation:
  input_set_assembled: YES
  open_items_recorded:  # F-1…F-5 amendments · AH-4 · AH-5 · DEF-1/4/5 · OQ-4 · Port Contract ratification — recorded, not decided
zero_lens_applied:      # §7.7 — every boundary responsibility tested from the NORMAL case and the ZERO case
  producer_pass: YES
  adversarial_pass: YES
next_actor: HUMAN PRINCIPAL ARCHITECT
next_act: SEPARATE GOVERNED ACT
```

---

## Traceability

- **Commission:** HPA, 2026-08-23 — *"don't research more. Do the final architectural consolidation and Kernel-boundary definition"* · the one-question spec and the thirteen-item output as recorded in §7; exclusions verbatim. The HPA confirmed the sequence: *"final architectural decision closure → Kernel boundary definition → Kernel capability mapping → implementation decision"* and *"That would be the last DDD step before we can legitimately decide what the Kernel actually is."*
- **Inputs:** v1.1 reference architecture (`20260822-1402`) · Port Contract r4 (`20260822-1559`) · LA Review-01 (`20260822-1611`) · F-1…F-5 decision support (`20260823-1051`) · OQ-2/3/5 decision records · AH-1…AH-5 decision support (`20260822-2334`).
- **Chain position:** P5 ✅ → AH-1…AH-5 📋 → T-2/T-3 ✅ → T-5 ✅ → OQ-2 ✅ → OQ-3 ✅ → OQ-5 ✅ → **F-1…F-5 ✅** → **← this handoff** → Kernel boundary definition → capability mapping → implementation decision.
- **Status:** HANDOFF · the F-1…F-5 decision support is DELIVERED and engaged by the HPA · the five amendment candidates remain ⬜ OPEN (HPA) · Port Contract **PROPOSED · NON-AUTHORITATIVE** · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · **no Kernel boundary defined · no Kernel authorization · the Kernel still waits**.
