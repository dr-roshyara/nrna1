# KnowledgeOS — F-1…F-5 — Domain Capabilities before the Kernel Boundary — Decision Support

> **Role:** a governed **domain-discovery / decision-support act** (commissioned by the Human Principal Architect, 2026-08-23) — determines, from the existing KnowledgeOS architecture, domain model, invariants, terminology, evidence, and governance, **what F-1…F-5 establish about the minimum domain capabilities and responsibilities that must exist before the Kernel boundary can legitimately be defined**. **Not** implementation · **not** Kernel design · **not** research · **not** v1.2.
> **Canonical source of F-1…F-5:** `docs/knowledgeos/reviews/20260822-1611-KOS-LA-Review-01-Expression-Meaning-Boundary.md` §4 — the **five Port Contract amendment candidates**, each a **wording / precision** finding (clarity · naming · wording · precision · precision), each **gated on HPA approval**, none applied. The contract is a delivered artifact; amendments are a separate authorized act.
> **Verdict — one line:** **F-1…F-5 establish NO new domain capability.** Each encodes a domain fact that is **already existing law** — they collectively **pin** (make explicit, make unambiguous) the KnowledgeCore's **inbound admission pipeline**: single-gate admission (F-1) · evidence admission with member-safe naming (F-2) · representation-agnostic intake (F-3) · preservation altitude = History-at-admission or mechanism-side, never domain state (F-4) · two-sided justification ownership (F-5). Each is a **rendering of existing law, not new law** — the amendment candidates sharpen the Port Contract's wording; they do not change the domain.
> **Status:** ⭐ **DELIVERED — F-1…F-5 DECISION SUPPORT · PROPOSED · NON-AUTHORITATIVE.** Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · Port Contract remains **PROPOSED · NON-AUTHORITATIVE** · F-1…F-5 amendments **recorded, not applied** (⬜ OPEN · HPA) · no v1.1 edit · no contract edit · no v1.2 · no member added or removed (12→12→12) · no invariant changed (11→11) · no context changed (6→6) · **no Kernel boundary defined** — the preconditions established here are inputs, not the boundary · **Kernel still waits**.

---

## 1 · The commission

The HPA commissioned F-1…F-5 as the next governed act after the OQ-5 ruling (2026-08-23):

> *"We are not yet at 'start coding the Kernel.' We are at: final architectural decision closure → Kernel boundary definition → Kernel capability mapping → implementation decision. That is the right sequence."*

The act's objective (HPA commission, verbatim-in-substance):

> **Determine, from the existing KnowledgeOS architecture, domain model, invariants, terminology, evidence, and governance, what F-1…F-5 establish about the minimum domain capabilities and responsibilities that must exist before the Kernel boundary can legitimately be defined.**

Governing principles (HPA commission):

1. **Discover the domain first. Define the boundary second. Map capabilities third. Implement last.**
2. **The Kernel must be derived from authoritative domain responsibilities and invariants; the domain must never be distorted to fit a preselected Kernel design.**
3. **Do not treat F-1…F-5 as a generic checklist.** Treat each F-item as a **domain question** whose purpose is to determine what KnowledgeOS actually needs before the Kernel boundary is defined.
4. **Do not mix documentation-integrity cleanup into this act.** `Documentation Integrity ≠ F-1…F-5 Domain Discovery ≠ Kernel Boundary Design ≠ Kernel Implementation`. The F-1…F-5 act applies the artifact discipline (§15) and edit safety (§16) to **its own** edits only.
5. **Research posture: inform, never initiate.** If a decision cannot be resolved from existing evidence, prepare a bounded research requirement and STOP for HPA authorization — never run research.

**Boundaries honored by this act (absolute list, verbatim-in-substance):** no code · no aggregate modification · no member added or removed · no Kernel classes/interfaces/ports/adapters · no schema/API/event change · no Constitution edit · no v1.1 edit · no v1.2 · no Port Contract modification · no reopening of OQ-2 / OQ-3 / OQ-5 / OQ-4 · no reopening of SNF research · no autonomous research start · no new vocabulary invented conveniently · no KnowledgeOS redesign · no inference of implementation from technology preferences · no projection promoted to domain authority · no domain concept collapsed into an infrastructure concept · **no Kernel defined before the F-items and consolidation complete**.

---

## 2 · F-1…F-5 — the canonical definitions

F-1…F-5 are the **five amendment candidates** recorded by **LA Review-01** (`20260822-1611-KOS-LA-Review-01-Expression-Meaning-Boundary.md`), each a finding on the **Expression↔Meaning Port Contract** (`20260822-1559-KOS-Expression-Meaning-Port-Contract.md`, r4), each **gated on HPA approval**:

| # | The amendment candidate (LA Review-01 §4) | Class | Defect? | Targets |
|---|---|---|---|---|
| **F-1** | State the **two-gate reading** explicitly — Expression↔Meaning Port = outer **published language** (contract conformance); Verification Port = the only **admission path** (constitutional admissibility) | clarity | no | contract §1 / Q6 |
| **F-2** | Reserve **"EvidenceLink"** for the domain member (EvidenceLinks); name the crossing artifact an **"evidence observation" / "candidate-equality observation"** that *may result in* a link only by admission | precision / naming | no | contract Q8 / §4 |
| **F-3** | Make the port explicitly **not a natural-language port**: accepts **any representable submission** (structured data · formal notation · machine payloads), **Test F** as warrant | wording | no | contract §1 / Q1 |
| **F-4** | Pin the **preservation altitude** of candidates / collision reports — aggregate **History** at admission, or **mechanism-side / infrastructure**, never domain state | precision | no | contract Q8 |
| **F-5** | State the **two-sided ownership** of the justification path — **production** mechanism-owned, **record + admissibility evaluation** domain-owned | precision | no | contract §5 / Q5 |

**The domain question each encodes** (the commission's re-reading):

- **F-1** → *Does KnowledgeOS require two structurally distinct gates at its inbound edge — a conformance gate and an admission gate — and is admission a single, domain-owned path?*
- **F-2** → *Is the artifact that crosses the port an aggregate member, or a proposal that may result in a member — and who owns the determination?*
- **F-3** → *Does the core's inbound published language depend on natural language, or is any representable submission admissible?*
- **F-4** → *Where do non-admitted artifacts (candidates, collision reports) live — can a candidate store exist as domain state?*
- **F-5** → *Who owns the justification path — the mechanism or the domain — and is it a member?*

---

## 3 · F-1 — the two-gate reading

### The domain question
Does the KnowledgeCore require a **conformance gate** (published language — what may be submitted) structurally distinct from an **admission gate** (constitutional admissibility — what becomes epistemic state), with admission a single, domain-owned path?

### Canonical finding
LA Review-01 Q1: keep the **two-gate reading explicit** — the Expression↔Meaning Port is the outer **published language** (mechanism-facing contract conformance); the Verification Port is the inner **constitutional admission gate** (the only admission path, INV-KOS-VERIFICATION-001). The contract's Q6 already routes conformance trust through "the kernel (the aggregate boundary)"; stating the two gates as distinct prevents the port from being misread as the admission gate itself. **Clarity, not defect.**

### A-L classification
- **Admission responsibility** = **E** (aggregate invariant: INV-KOS-VERIFICATION-001) — realized at the KnowledgeAggregate boundary (**D**).
- **Expression↔Meaning Port / ACL** = the boundary articulation — **H** (application/workflow: contract-conformance enforcement at the inbound edge); it is published language of the KnowledgeCore, **not** a mechanism (**J** excluded by §16 — the port is not an engine).
- **meaning candidate** = **B** (value object, port-contract vocabulary — never an aggregate member, r4-4).

### Bounded-context discipline (9 questions)
| Question | Answer |
|---|---|
| **Owner** | KnowledgeCore — the ACL belongs to the context it protects (LA Review-01 Q1) |
| **Name-in-context** | Expression↔Meaning Port (published language) · Verification Port (admission) |
| **Canonical meaning** | conformance ≠ admission; the port is the published language; the Verification Port is the only way inbound proposals become epistemic state |
| **Who may mutate** | only the KnowledgeAggregate (at admission) — mechanisms never |
| **Who observes** | Expression mechanisms (conform) · Reasoning & Validation (submit via Verification) · Projection (reads, advisory) |
| **What crosses** | expression → meaning candidate (port) · candidate + preserved justification path → admitted meaning (Verification) |
| **In what representation** | any representable submission (F-3) · candidate payload vocabulary (§4) |
| **What must NOT cross** | epistemic states · verdicts · KnowledgeId proposals (obligations 1 · 4) · mechanism Confidence (⟨R-1⟩) |
| **Which invariant protects the boundary** | INV-KOS-VERIFICATION-001 (only admission path) · P-2 (only the domain determines constitutional admissibility) · D-5 (nothing written outside returns as knowledge) |

### Invariant-first analysis
**Capability** = admit a candidate into a justified epistemic state → **purpose** = only the domain may promote a proposal to knowledge → **concepts** = port · verification · candidate · admitted meaning → **invariants** = INV-KOS-VERIFICATION-001 · P-2 · D-5 → **owning aggregate/context** = KnowledgeAggregate (KnowledgeCore) → **evidence** = v1.1 §5.2 context map · §7 · Port Contract §1 (pipeline) · §3-Q6 · LA Review-01 Q1 → **authorized transition** = `KnowledgeCreated` (admission at the Verification Port) → **boundary crossing** = the candidate passes both gates; only the second admits → **observable consequence** = mechanisms conform; the domain alone disposes.

### What F-1 establishes about minimum domain capabilities
**Single-gate admission** — the KnowledgeCore must possess **exactly one admission path** into epistemic state, owned by the aggregate boundary, structurally distinct from the contract-conformance gate. A **MUST EXIST** capability — already law.

### Decision / evidence separation
- **Fact (evidence):** the two-gate structure is existing law — INV-KOS-VERIFICATION-001 · P-2 · D-5 · §5.2 map (Expression → Expression↔Meaning ACL → KnowledgeCore; Verification Port = Article 6).
- **Recommendation (advisory):** the Kernel boundary must protect single-gate admission as an authoritative responsibility.
- **Authority (HPA):** whether to apply the F-1 wording amendment to the Port Contract — **⬜ OPEN · HPA-gated** · not applied here.

### Decision status
**RESOLVED FROM EXISTING LAW.** (The amendment application itself is a separate HPA act.)

---

## 4 · F-2 — EvidenceLink naming precision

### The domain question
Is the artifact that crosses the port an aggregate member, or a **proposal that may result in a member only by admission** — and who owns the determination?

### Canonical finding
LA Review-01 Q6: precision on the name. The contract uses **"EvidenceLink"** for the crossing artifact, but v1.1's **EvidenceLinks** is the aggregate **member**. Recommend naming the crossing artifact an **"evidence observation"** (or **"candidate-equality observation"**) — port vocabulary that *may result in* an EvidenceLink member only by admission — reserving "EvidenceLink" for the domain record. This keeps the no-hidden-aggregate discipline airtight (a port artifact must not *appear* to be a member).

### A-L classification
- **EvidenceLinks** = **D** (aggregate member — ⟨C-3⟩: references + acquisition method + reliability conditions; the core owns the **links**, never the evidence **content**).
- **evidence observation / candidate-equality observation** = **B** (value object, port-contract vocabulary, a **proposal** — never a member, r4-4).
- **the identity-assignment act** that may admit a link = **E/D** (aggregate responsibility; identity is assigned, never derived — INV-KOS-IDENTITY-001).

### Bounded-context discipline (9 questions)
| Question | Answer |
|---|---|
| **Owner** | EvidenceLinks = KnowledgeCore (the member) · the observation = the Expression mechanism that produced it |
| **Name-in-context** | EvidenceLink (domain record) vs evidence observation (crossing artifact) |
| **Canonical meaning** | the crossing artifact is a **proposal** offered to an identity-assignment act; only **admission** creates an EvidenceLink member |
| **Who may mutate** | only the aggregate admits a link; mechanisms only offer |
| **Who observes** | the identity-assignment act (the aggregate) · downstream readers |
| **What crosses** | candidate-equality observations (collisions) |
| **In what representation** | candidate-side structured observation (port vocabulary) |
| **What must NOT cross** | an admitted link · an identity determination · evidence *content* (⟨C-3⟩) |
| **Which invariant protects the boundary** | INV-KOS-IDENTITY-001 (assigned never derived) · ⟨C-3⟩ (core owns links, never content) · INV-KOS-VERIFICATION-001 (only admission path) |

### Invariant-first analysis
**Capability** = record justified evidence relationships → **purpose** = justification preserved, content owned externally → **concepts** = evidence link · evidence observation · identity assignment → **invariants** = INV-KOS-IDENTITY-001 · ⟨C-3⟩ → **owning aggregate/context** = KnowledgeAggregate (KnowledgeCore) → **evidence** = v1.1 §6 ⟨C-3⟩ · Port Contract Q8 · LA Review-01 Q6 → **authorized transition** = `KnowledgeCreated` (admission; evidence reference recorded) → **boundary crossing** = observation offered; link admitted → **observable consequence** = no port artifact appears to be a member.

### What F-2 establishes about minimum domain capabilities
**Evidence admission** — the domain must own the determination of whether an offered observation becomes an **admitted** EvidenceLink; mechanisms offer, never dispose. A **MUST EXIST** capability — already law.

### Decision / evidence separation
- **Fact (evidence):** the member/artifact distinction is existing law — EvidenceLinks is a member (⟨C-3⟩) · identity assigned never derived (INV-KOS-IDENTITY-001) · port vocabulary never aggregate members (r4-4) · collisions are evidence, never admission (contract Q8 — the contract's **position on OQ-2**).
- **Recommendation (advisory):** the Kernel boundary must protect evidence admission as authoritative; the crossing artifact is a proposal, never a member.
- **Authority (HPA):** whether to apply the F-2 naming amendment — **⬜ OPEN · HPA-gated** · not applied here.

**OQ-2 guard:** F-2 is the **naming/precision** question — it does **not** decide whether SNF-equivalence *may* be recorded as an EvidenceLink (**OQ-2** — content question, **the HPA's**, untouched; the contract's position at contract altitude remains subject to HPA rule). F-2's precision holds **regardless** of OQ-2's outcome: whatever the crossing artifact is called, "EvidenceLink" is reserved for the member.

### Decision status
**RESOLVED FROM EXISTING LAW.** (The amendment application itself is a separate HPA act.)

---

## 5 · F-3 — not a natural-language port

### The domain question
Does the core's inbound published language depend on natural language — or is **any representable submission** admissible?

### Canonical finding
LA Review-01 Q9: strengthen §1/Q1 to state the port accepts **any representable submission** — natural-language expression · structured data · formal notation · machine-generated payloads — with **Test F** as the warrant, so the port cannot be misread as secretly a natural-language port. A structured submission produces the same candidate shape (meaning candidate + declared insufficiency + justification path + metadata); only the surface varies.

### A-L classification
- **candidate shape** = **B** (value object, port-contract vocabulary).
- **natural-language handling** = **J** (mechanism) — **never core** (Test F).
- **representation-agnostic intake** = **E/H** (boundary property — an invariant-like property of the port: no surface form is core).

### Bounded-context discipline (9 questions)
| Question | Answer |
|---|---|
| **Owner** | the port = KnowledgeCore (published language) · the surface form = Expression context |
| **Name-in-context** | the port accepts "any representable submission" |
| **Canonical meaning** | expression is any surface form, carries no epistemic weight (v1.1 §10); the candidate shape is surface-independent |
| **Who may mutate** | n/a at intake — mechanisms submit |
| **Who observes** | KnowledgeCore (conformance → admission) |
| **What crosses** | any representable submission → the same candidate shape |
| **In what representation** | any — representation is never core |
| **What must NOT cross** | anything natural-language-dependent (no surface form is core) |
| **Which invariant protects the boundary** | **Test F** (v1.1 §2 · §11.2) — no mechanism whose reason for existing is natural language can be core |

### Invariant-first analysis
**Capability** = accept any representable submission → **purpose** = no surface form is core → **concepts** = expression · candidate shape · surface → **invariants** = Test F (the decisive placement argument) → **owning aggregate/context** = KnowledgeCore boundary → **evidence** = v1.1 §2/§11.2 Test F · Port Contract Q1 · LA Review-01 Q9 → **authorized transition** = none at intake (submission alone changes nothing, ⟨A-3⟩); admission follows → **boundary crossing** = any surface form → candidate → **observable consequence** = remove natural language entirely and the core is unharmed — only *reach* is lost.

### What F-3 establishes about minimum domain capabilities
**Representation-agnostic candidate intake** — the KnowledgeCore's inbound published language must not depend on any surface form; only the **candidate shape** is invariant across representations. A **MUST EXIST** capability — already law (Test F).

### Decision / evidence separation
- **Fact (evidence):** Test F is existing law (v1.1 §2 · §11.2) — the decisive falsification result A-1: remove natural language entirely, the core is unharmed; therefore no mechanism whose reason for existing is natural language can be core, and the core's inbound published language cannot depend on natural language either.
- **Recommendation (advisory):** the Kernel boundary must be representation-agnostic — no surface form enters the core's altitude.
- **Authority (HPA):** whether to apply the F-3 wording amendment — **⬜ OPEN · HPA-gated** · not applied here.

### Decision status
**RESOLVED FROM EXISTING LAW.** (The amendment application itself is a separate HPA act.)

---

## 6 · F-4 — preservation altitude

### The domain question
Where do non-admitted artifacts (candidates, collision reports) live — can a candidate store exist as domain state?

### Canonical finding
LA Review-01 Q7: make the **preservation altitude explicit** — the contract's "collision reports… are preserved" should say: preservation is either (a) part of the aggregate's **History** when a transition is admitted, or (b) **mechanism-side / infrastructure**, never domain state. This preempts a silent **"candidate store"** aggregate.

### A-L classification
- **History** = **D** (aggregate member — forward-only, nothing overwritten; v1.1 §8 · Port Contract §5).
- **mechanism-side / infrastructure retention** = **J/I** (mechanism / infrastructure — **never domain**).
- **the prohibition of a hidden candidate store** = **E** (invariant-guard: no hidden aggregate · D-5 · ⟨A-3⟩).

### Bounded-context discipline (9 questions)
| Question | Answer |
|---|---|
| **Owner** | History = KnowledgeCore · candidate storage = Expression/mechanism side |
| **Name-in-context** | "preservation altitude" |
| **Canonical meaning** | non-admitted artifacts never become domain state; only **admitted transitions** are recorded in History |
| **Who may mutate** | only the aggregate appends History (forward-only) |
| **Who observes** | readers |
| **What crosses** | nothing — non-admitted artifacts do not cross into domain state |
| **In what representation** | History entries · mechanism-side stores |
| **What must NOT cross** | a candidate store into domain state · a second admission path |
| **Which invariant protects the boundary** | D-5 (nothing written outside returns as knowledge) · INV-KOS-VERIFICATION-001 (only admission path) · ⟨A-3⟩ (no domain event without aggregate change) |

### Invariant-first analysis
**Capability** = record only admitted transitions → **purpose** = forward-only history; no non-domain state → **concepts** = History · candidate · collision report · preservation → **invariants** = D-5 · ⟨A-3⟩ · INV-KOS-VERIFICATION-001 → **owning aggregate/context** = KnowledgeAggregate → **evidence** = v1.1 §8 (History forward-only) · §11.3 ⟨A-3⟩ (no event for parse/normalization/canonical-form computation) · Port Contract Q8 (collisions never by themselves change any knowledge state) · LA Review-01 Q7 → **authorized transition** = `KnowledgeCreated` (admission → History) → **boundary crossing** = none for non-admitted artifacts → **observable consequence** = no hidden candidate store; collision reports change no knowledge state.

### What F-4 establishes about minimum domain capabilities
**History recording of admitted transitions only** — preservation is History-at-admission **or** mechanism-side / infrastructure, **never domain state**. A **MUST EXIST** capability (History) + a **MUST NOT EXIST IN KERNEL** prohibition (a candidate store / hidden semantic aggregate). Already law.

### Decision / evidence separation
- **Fact (evidence):** the preservation discipline is existing law — History forward-only (v1.1 §8) · ⟨A-3⟩ (no event without aggregate change) · D-5 (nothing written outside returns as knowledge) · INV-KOS-VERIFICATION-001 (no second admission path) · the candidate has no identity, no protected state, no lifecycle, no events (LA Review-01 Q7).
- **Recommendation (advisory):** the Kernel boundary must record only admitted transitions; non-admitted artifacts stay mechanism-side.
- **Authority (HPA):** whether to apply the F-4 wording amendment — **⬜ OPEN · HPA-gated** · not applied here.

### Decision status
**RESOLVED FROM EXISTING LAW.** (The amendment application itself is a separate HPA act.)

---

## 7 · F-5 — two-sided ownership of the justification path

### The domain question
Who owns the justification path — the mechanism or the domain — and is it a member?

### Canonical finding
LA Review-01 Q4: state the **two-sided ownership** explicitly — the candidate carries a **mechanism-authored reasoning proposal**; the aggregate's **JustificationPath** is the admitted record whose sufficiency the domain evaluates. This prevents "mechanism-owned" from being misread as "the domain does not own the justification path."

### A-L classification
- **JustificationPath** = **D** (aggregate member — v1.1 §6).
- **candidate-side reasoning proposal** = **B** (port-contract vocabulary — carried by the submission, never a member, r4-4).
- **justification preservation + sufficiency evaluation** = **E/D** (INV-KOS-VERIFICATION-001: no entry into knowledge without a preserved justification path; the domain evaluates sufficiency).

### Bounded-context discipline (9 questions)
| Question | Answer |
|---|---|
| **Owner** | record + evaluation = KnowledgeCore (JustificationPath member) · production = Reasoning & Validation (external, mechanism) |
| **Name-in-context** | JustificationPath (member) vs candidate-side justification (proposal) |
| **Canonical meaning** | the path is **preserved** (the domain keeps the record); the **process is external** (the kernel does not reason — v1.1 §16) |
| **Who may mutate** | only the aggregate admits the record; mechanisms author the proposal |
| **Who observes** | the aggregate (admissibility) · Projection (reads, advisory) |
| **What crosses** | the justification path with the candidate |
| **In what representation** | premises · rules · assumptions · inference rule · conclusion (structured) |
| **What must NOT cross** | an evaluated verdict · a black box (obligation 2) |
| **Which invariant protects the boundary** | INV-KOS-VERIFICATION-001 (no entry without a preserved justification path) · §16 (the kernel does not reason) · §4.2 Reasoning row (the path is kept, the process is external) |

### Invariant-first analysis
**Capability** = preserve and evaluate justification → **purpose** = knowledge enters only with a preserved justification path → **concepts** = justification path · reasoning proposal · sufficiency → **invariants** = INV-KOS-VERIFICATION-001 · §16 → **owning aggregate/context** = KnowledgeAggregate (record + evaluation); Reasoning & Validation (production) → **evidence** = v1.1 §16 · §4.2 · §6 · Port Contract Q5/Q6 · LA Review-01 Q4 → **authorized transition** = `KnowledgeCreated` (path admitted with the transition) → **boundary crossing** = candidate + path; only the domain evaluates sufficiency → **observable consequence** = no black box; mechanisms never decide admissibility.

### What F-5 establishes about minimum domain capabilities
**Justification preservation and evaluation** — the domain must own the admitted justification record and its sufficiency evaluation; the mechanism owns only the **production**. A **MUST EXIST** capability — already law.

### Decision / evidence separation
- **Fact (evidence):** the two-sided ownership is existing law — the kernel does not reason (v1.1 §16) · Reasoning & Validation is external; "the path is kept, the process is external" (§4.2) · JustificationPath is an aggregate member (§6) · no entry without a preserved justification path (INV-KOS-VERIFICATION-001).
- **Recommendation (advisory):** the Kernel boundary must protect justification preservation and sufficiency evaluation as authoritative; reasoning production stays outside.
- **Authority (HPA):** whether to apply the F-5 wording amendment — **⬜ OPEN · HPA-gated** · not applied here.

### Decision status
**RESOLVED FROM EXISTING LAW.** (The amendment application itself is a separate HPA act.)

---

## 8 · Cross-F-item synthesis — the Domain Capability Map

**What F-1…F-5 establish, taken together:** the KnowledgeCore's **inbound admission pipeline** is the cluster of minimum domain capabilities that must exist before the Kernel boundary can legitimately be defined. Every capability in the MUST EXIST column is **already law**; F-1…F-5 **pin** it — they make it explicit and unambiguous, they do not create it.

### MUST EXIST (authoritative domain responsibilities — the Kernel boundary will protect these)

| Capability | Established by | Existing law |
|---|---|---|
| **Single-gate admission** — the only admission path into epistemic state, owned by the aggregate boundary | F-1 | INV-KOS-VERIFICATION-001 · P-2 · D-5 |
| **Contract-conformance enforcement** — published language: mechanisms conform to the six obligations; conformance ≠ admission | F-1 | §5.2 ACL · Port Contract §2 · §3-Q6 |
| **Identity assignment** — assigned at the aggregate root, never derived or proposed | F-2 (grounding) | INV-KOS-IDENTITY-001 · P-4 |
| **Evidence admission** — offered observations become admitted EvidenceLinks only by domain decision | F-2 | ⟨C-3⟩ · INV-KOS-IDENTITY-001 |
| **Justification preservation + sufficiency evaluation** — the admitted record is domain-owned | F-5 | INV-KOS-VERIFICATION-001 · §16 · JustificationPath member |
| **History recording** — forward-only, admitted transitions only | F-4 | §8 · D-5 · ⟨A-3⟩ |
| **Representation-agnostic candidate intake** — no surface form is core | F-3 | Test F (§2 · §11.2) |
| **Epistemic-state determination** — the domain alone determines the seven states | (F-1 grounding — not a new finding) | §9 · §10 · obligations 1 · 6 |
| **Confidence assignment inside the boundary** | (OQ-5 — already resolved law; cited, **not reopened**) | ⟨R-1⟩ · OQ-5 record (20260823-1014) |

### MAY EXIST (mechanism-side, behind the port, **never** in the kernel)

| Capability | Established by | Where it lives |
|---|---|---|
| Candidate production / reasoning | F-5 | Reasoning & Validation (external) |
| Representation translation / normalization / canonicalization (incl. SNF) | F-3 | Expression context · SNF = representation/mechanism (§16) |
| Declared-insufficiency self-declaration | F-5 (Q3 grounding) | port vocabulary §4 |
| Candidate-side evidence offering (collision / equality observations) | F-2 | port vocabulary Q8 |
| Mechanism-side / infrastructure retention of non-admitted artifacts | F-4 | mechanism-side / infrastructure |

### MUST NOT EXIST IN KERNEL

| Prohibition | Established by | Existing law |
|---|---|---|
| A second admission path | F-1 | INV-KOS-VERIFICATION-001 |
| A hidden candidate store / hidden semantic aggregate | F-4 | D-5 · ⟨A-3⟩ · LA Review-01 Q7 |
| Natural-language interpretation as a core capability | F-3 | Test F |
| Mechanism-authored identity assignment | F-2 | INV-KOS-IDENTITY-001 |
| Mechanism Confidence score crossing as Confidence | (OQ-5 — already law; cited, not reopened) | ⟨R-1⟩ |

### OUTSIDE KNOWLEDGEOS DOMAIN (Expression / external contexts)

| Capability | Established by | Owner |
|---|---|---|
| Surface-form handling (any language, any medium) | F-3 | Expression context (§5.3) |
| Reasoning engines (validation · debate · fallacy detection) | F-5 | Reasoning & Validation (§5.3) |
| Normalization / canonical-form computation | F-4 | Expression / mechanism (§16 REPRESENTATION) |

### UNRESOLVED (from this act)
**None.** All five F-items resolve from existing law. Items that remain unresolved **outside** this act's scope: **DEF-1** (boundary realization) · **DEF-4** (SNF as port encoding) · **DEF-5** (storage/schemas/APIs/classes) · **OQ-4** (KOS-SCB v0.2 — UNAUTHORIZED) · the **Port Contract's ratification status** · the **F-1…F-5 amendment applications** (HPA-gated).

---

## 9 · Kernel Boundary Preconditions (NOT Kernel design)

These are the conditions that must hold **before the Kernel boundary can legitimately be defined** — they are inputs to that future act, not the boundary itself.

| # | Precondition | Established by | Status |
|---|---|---|---|
| 1 | **The authoritative responsibilities are identified.** The nine MUST EXIST capabilities (§8) are the domain-owned responsibilities the Kernel boundary will protect | F-1…F-5 synthesis | ✅ RESOLVED FROM EXISTING LAW |
| 2 | **The non-authoritative responsibilities are identified.** Mechanism-side production (reasoning · representation · insufficiency declaration · evidence offering) stays outside the kernel | F-5 · F-3 · F-2 | ✅ RESOLVED FROM EXISTING LAW |
| 3 | **The invariants to preserve are enumerated.** INV-KOS-VERIFICATION-001 · IDENTITY-001 · DIMENSION-001 · UNKNOWN-001 · AUTHORITY-001 — the eleven-invariant set, unchanged | v1.1 §7 | ✅ UNCHANGED (11→11) |
| 4 | **The crossing discipline is settled.** Anything crossing is **port vocabulary** (r4-4), a **proposal**, never a determination; admission via the single gate | F-2 · F-1 | ✅ RESOLVED FROM EXISTING LAW |
| 5 | **The preservation discipline is settled.** History-at-admission or mechanism-side — **never domain state** | F-4 | ✅ RESOLVED FROM EXISTING LAW |
| 6 | **The two-gate structure is recognized as law.** Conformance ≠ admission | F-1 | ✅ RESOLVED FROM EXISTING LAW |
| 7 | **Representation-agnosticism is recognized as law.** No surface form is core (Test F) | F-3 | ✅ RESOLVED FROM EXISTING LAW |
| 8 | **Two-sided ownership is recognized as law.** Mechanisms produce; the domain disposes | F-5 | ✅ RESOLVED FROM EXISTING LAW |
| 9 | **The boundary is defined against the delivered Port Contract, with its status recorded.** The contract is **PROPOSED · NON-AUTHORITATIVE**; the F-1…F-5 amendment candidates and the contract's ratification are **separate HPA acts** the boundary definition must not assume | Port Contract §0 · LA Review-01 §5 | ⬜ PENDING (HPA) — a precondition, not resolved here |
| 10 | **Open items the boundary must respect but not resolve.** DEF-1 · DEF-4 · DEF-5 · OQ-4 (unauthorized) | v1.1 §20 | ⬜ OUT OF SCOPE — recorded, not resolved |

**The preconditions do not design the Kernel.** They state what must be true about the domain before the Kernel boundary can legitimately be defined — and §8 shows all the domain facts are already law. The Kernel boundary definition itself remains a **future separate governed act**.

---

## 10 · Anti-Kernel-drift test

| # | Would any answer be "yes"? | Answer |
|---|---|---|
| 1 | An invented Kernel component? | **NO** |
| 2 | An invented interface? | **NO** |
| 3 | An invented implementation concept? | **NO** |
| 4 | A domain concept collapsed into a technical service? | **NO** |
| 5 | A projection promoted to a domain authority? | **NO** |
| 6 | A mechanism promoted to a domain concept? | **NO** |
| 7 | Evidence promoted to authority? | **NO** |
| 8 | Confidence promoted to epistemic authority? | **NO** (OQ-5 already-ruled; cited, not reopened) |
| 9 | A new aggregate member? | **NO** (12→12→12) |
| 10 | A changed invariant? | **NO** (11→11) |
| 11 | A new bounded context? | **NO** (6→6) |
| 12 | A silent rename? | **NO** (F-2 naming precision recorded as a candidate, **not applied**) |
| 13 | Research started? | **NO** |
| 14 | OQ-4 reopened? | **NO** (stays UNAUTHORIZED) |
| 15 | v1.1 modified? | **NO** |
| 16 | Constitution modified? | **NO** (FROZEN) |
| 17 | An F-item amendment applied? | **NO** (recorded, not applied — HPA-gated) |

**All NO ✅.**

---

## 11 · What this act does NOT decide

| # | Not decided here | Owner |
|---|---|---|
| 1 | **Whether to apply the F-1…F-5 amendments** to the Port Contract | **HPA** (each amendment a separate authorized act) |
| 2 | **The Port Contract's ratification status** (remains PROPOSED · NON-AUTHORITATIVE) | **HPA** / Logical Architecture |
| 3 | **OQ-2** (may SNF-equivalence be an EvidenceLink?) — F-2's naming precision is orthogonal to OQ-2's content | **HPA** |
| 4 | **OQ-4** (KOS-SCB v0.2) — unchanged, **UNAUTHORIZED** | **HPA act required** |
| 5 | **DEF-1** (aggregate boundary realization) · **DEF-4** (SNF encoding) · **DEF-5** (storage/schemas/APIs/classes) | Logical / Implementation Architecture |
| 6 | **The Kernel boundary definition itself** — the preconditions here are inputs, not the boundary | **future separate governed act** |
| 7 | **AH-5 deferral · AH-4** — later acts in the chain | **future separate governed act** |
| 8 | **v1.2 · v1.1 edits · Constitution edits · any new law** | **not opened — nothing modified** |

---

## 12 · Governance STOP position

**STOP — decision support delivered.** All five F-items resolve from existing law; **no bounded research requirement** is proposed (§1 research posture: the commission identified no question that existing architecture/governance/evidence cannot answer — the empirical mechanism-reliability questions routed to OQ-4 remain **UNAUTHORIZED**, explicitly not reopened). No implementation. No Kernel boundary defined.

**The HPA rules on:**
1. Each **F-1…F-5 amendment candidate** (separately or together) — whether the Port Contract's wording is amended to make the two-gate reading, the member-safe naming, the representation-agnostic acceptance, the preservation altitude, and the two-sided ownership explicit.
2. The **next act** in the chain — **AH-5 deferral · AH-4** → architectural consolidation → **Kernel boundary definition** (the preconditions of §9 are its inputs).

**Boundaries honored by this act (re-asserted):** **nothing modified** — no v1.1 edit · no contract edit · no code · **no v1.2** · no register · no Constitution · no Kernel · no SNF · no corpus · **no research track opened** · **no member added or removed** (12→12→12) · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · contract remains **PROPOSED · NON-AUTHORITATIVE** · the F-1…F-5 amendments are **recorded, not applied**.

---

## Completion report (HPA-commissioned format)

```yaml
act: F-1…F-5
status: COMPLETED
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
f_items:
  F-1: RESOLVED FROM EXISTING LAW — single-gate admission (INV-KOS-VERIFICATION-001 · P-2 · D-5); amendment candidate recorded, not applied
  F-2: RESOLVED FROM EXISTING LAW — evidence admission; EvidenceLinks member vs evidence observation (⟨C-3⟩ · INV-KOS-IDENTITY-001 · r4-4); amendment candidate recorded, not applied
  F-3: RESOLVED FROM EXISTING LAW — representation-agnostic intake (Test F, v1.1 §2/§11.2); amendment candidate recorded, not applied
  F-4: RESOLVED FROM EXISTING LAW — preservation = History-at-admission or mechanism-side, never domain state (§8 · ⟨A-3⟩ · D-5); amendment candidate recorded, not applied
  F-5: RESOLVED FROM EXISTING LAW — two-sided justification ownership (INV-KOS-VERIFICATION-001 · §16 · JustificationPath member); amendment candidate recorded, not applied
kernel_boundary_preconditions:
  - "10 preconditions identified (§9) — all domain facts already law; the Kernel boundary definition remains a future separate governed act"
  - "Precondition 9 PENDING: the Port Contract is PROPOSED · NON-AUTHORITATIVE; the F-1…F-5 amendments and ratification are separate HPA acts"
next_actor: HUMAN PRINCIPAL ARCHITECT
next_act: SEPARATE GOVERNED ACT
```

---

## Traceability

- **Commission:** HPA, 2026-08-23 — the F-1…F-5 governed domain-discovery/decision-support act; objective and governing principles as recorded in §1; §11 per-F-item classification · §12 Domain Capability Map · §13 Kernel Boundary Preconditions · §14 anti-Kernel-drift test · §15 artifact discipline · §16 edit safety · §17 governance STOP condition · §18 completion-report YAML.
- **Canonical source of F-1…F-5:** `docs/knowledgeos/reviews/20260822-1611-KOS-LA-Review-01-Expression-Meaning-Boundary.md` §4 (the five amendment candidates, classes, recommended actions, each gated on HPA) · Q1 (F-1) · Q6 (F-2) · Q9 (F-3) · Q7 (F-4) · Q4 (F-5) · §5 (amendment candidates recorded, not applied).
- **Object the amendments target:** `docs/knowledgeos/architecture/20260822-1559-KOS-Expression-Meaning-Port-Contract.md` (r4) — §1 pipeline (the two gates) · §2 six obligations · §3 Q1/Q6 (F-1 · F-3) · Q5 (F-5) · Q8 (F-2 · F-4) · §4 vocabulary (r4-4) · §5 trust boundary (F-5) · §6 what the contract does NOT decide.
- **Existing law drawn on (v1.1):** `docs/knowledgeos/architecture/20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md` — §2 · §11.2 (Test F) · §5.2 context map · §5.3 context table · §6 aggregate members (Meaning ⟨C-2⟩ · EvidenceLinks ⟨C-3⟩ · JustificationPath · Confidence ⟨R-1⟩) · §7 invariants (VERIFICATION-001 · IDENTITY-001 · DIMENSION-001 · UNKNOWN-001 · AUTHORITY-001) · §8 (History forward-only · ⟨A-3⟩) · §10 (Expression→Meaning boundary) · §11 (Semantic Compiler placement · Test F) · §16 (KERNEL/MECHANISM/REPRESENTATION; the kernel does not reason) · §18 (D-1…D-6; D-5) · §20 (deferrals: DEF-1 · DEF-3 · DEF-4 · DEF-5 · OQ-1…OQ-5).
- **Chain position:** P5 accepted (20260822-2327) · OQ-2 resolved (`56e1bd8a`) · OQ-3 resolved (`55c73b1b`) · OQ-5 resolved (`952b7b85`) → **F-1…F-5 (this act)** → AH-5 deferral · AH-4 → architectural consolidation → Kernel decision. Recorded as "already waiting" in the AH-1…AH-5 decision-support (`20260822-2334`, §"Already waiting"), T-5 acceptance (`20260823-0829`), and the OQ-5 decision record (§8).
- **Discipline honored:** the act is **recorded, not invented** (F-1…F-5 are LA Review-01's recorded findings; the commission is the act) · every clause of every F-item's resolution traces to existing law — no new law, no article, no invariant, no aggregate, no member, no event, no register row (**25+4 unchanged**) · Constitution **satisfied, never extended** · the Port Contract is **not amended** (F-1…F-5 remain candidates gated on HPA approval) · no experiment authorized (v0.4 · OQ-4) · OQ-2 · OQ-5 · OQ-4 not reopened · no research track opened (all five resolve from existing law) · no Kernel boundary defined · the strongest statement never exceeds the evidence.
- **Status:** ⭐ **F-1…F-5 — DOMAIN CAPABILITIES BEFORE THE KERNEL BOUNDARY — DECISION SUPPORT — DELIVERED · PROPOSED · NON-AUTHORITATIVE.** All five F-items **RESOLVED FROM EXISTING LAW** as domain facts; the amendment candidates remain **⬜ OPEN (HPA)** · not applied. Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **unauthorized** · Port Contract **PROPOSED · NON-AUTHORITATIVE** · **Kernel still waits**.
