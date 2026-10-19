# KT-3 — `docs/knowledge_tranfer/`, batch 3 (2026-08-15): EKS/KnowledgeOS target architecture; working with sessions

| Field | Value |
|---|---|
| Read (full) | `20260815_0148_how_to_work_with_sessions.md` · `20260815_1615_target_architecture.md` · `20260815_1615_targer_architecure.md` · `20260815_1617_eks_2.0.md` |
| Source character | **AI conversational answers** (a "Principal Architect" persona; `eks_2.0` revises its predecessor in response to an *uploaded external review*, quoted). No governance status; recommendations only |
| Labels | [SRC-T] · [I] · [?] |

## 1. Naming convergence (2026-08-15) [SRC-T]

The same object is called **PKS**, **EKS (Engineering Knowledge System)** and **KnowledgeOS**:
- *"PKS is an Engineering Knowledge System where governed knowledge is the primary asset"*;
- *"KnowledgeOS is an Engineering Knowledge Operating System"* (still valid in v2.0).

**Vision (target architecture, `…_1615_targer_architecure.md`):** **"KnowledgeOS is an AI Engineering Knowledge Operating System that captures, governs, validates, relates, evolves, and projects engineering knowledge while preserving human authority, evidence provenance, and architectural integrity."**

## 2. EKS target architecture v2.0 (after external review) [SRC-T]

- **Position:** *"EKS is a provenance-aware engineering knowledge platform that manages governed engineering assertions, decisions, rules, evidence, and context delivery."* The shift is from *"Knowledge as a thing"* to *"Governed engineering assertions as domain objects"*.
- **Core model:** **Claim** (the fundamental assertion: statement, scope, evidence, confidence, lifecycle, owner) → Decision · Rule · Pattern (· Exception) → **Evidence** (source, version, location, extraction method, evaluator, verification state) → **Assessment** → **Verdict**. The generic `KnowledgeItem` was rejected as too broad.
- **Core invariant:** **"No engineering assertion becomes authoritative without lifecycle state, ownership, evidence or rationale, defined scope, and applicability rules."** It distinguishes *governed* from *authoritative*.
- **Bounded contexts reduced from 7 to 4:**
  - Knowledge Governance;
  - Evidence & Assessment;
  - Decision & Rule Management;
  - Knowledge Model.

  Delivery and AI are capabilities, not contexts: *"AI consumes knowledge, does not own knowledge."*
- **Three planes:**
  - **Knowledge plane** (the *"domain truth"*);
  - **Delivery plane** (search, projections, context packages);
  - **Execution plane** (sessions, agents, tools, approvals).
- **First-class models:**
  - **Scope:** product, system, repository, team, technology, environment, lifecycle phase, time. *"Without scope, retrieval becomes dangerous."*
  - **Authority:** who proposes, reviews, approves, grants exceptions, retires.
  - **Conflict relations:** supersedes, refines, contradicts, depends-on, derives-from, applies-to, invalidates.
  - **Freshness:** validFrom, validUntil, lastVerified, verificationInterval, stalePolicy.
  - **Lifecycle per type:** *"Lifecycle is a policy of the knowledge type, not a generic status field."*
- **Scripts** are mapped script → mechanism → capability → contract → ownership boundary, never script → service.
- **V1 product wedge:** *"A trusted engineering decision, rule, evidence, and context system."*

## 3. The earlier target-architecture drafts (same day) [SRC-T]

- **The five questions to get right:**
  1. Who owns knowledge?
  2. Who can change it?
  3. How does it become trusted?
  4. How does it evolve?
  5. How does AI consume knowledge without becoming authority?
- **Scripts are *"the first executable expression of KnowledgeOS"*.** They carry two identities: a domain rule (*"START requires human authority"*) and a technical realization. *"Existing scripts → evidence of capabilities → evaluate placement → decision"*, never *"therefore final architecture"*.

## 4. "How to work with sessions": the operating philosophy [SRC-T]

- **Five actors:** Governance (session 2) · Architecture (session 4) · Implementation (session 3) · Verification (session 1) · Human PO/ARB.
- **Core philosophy:** **"Controlled agency: every actor receives enough authority to perform its role, but not enough authority to silently redefine the system."**
- **Invariants stated:**
  - *"A report of an act is not the act"*;
  - *"Machine state before narrative"*;
  - **"Absence is never permission"**: no assignment, no grant, no START, an unreadable record and **UNKNOWN** are each *≠ permission*, so **STOP · REPORT · ESCALATE**;
  - *"One authoritative owner for an invariant"*;
  - *"Verification is an independent falsification role"*;
  - *"Findings must be classified before being fixed."*
- **The real test:** a session started without an assignment returned `UNASSIGNED · operable: false · STOP`, which is **success**, not failure.

## 5. Interpretation

- **[I] By mid-August the KnowledgeOS concept is a governed-assertion model:**
  - Claim → Evidence → Assessment → Verdict;
  - authority and scope;
  - per-type lifecycles;
  - a knowledge, delivery and execution separation;
  - "controlled agency" for AI.
  
  This is the **immediate predecessor** of the corpus period, where "EKS" appears in 251 corpus files from 2026-08-16 (B-01 §3).
- **[I] Candidate antecedents for corpus constructs:**

  | This file | Corpus construct |
  |---|---|
  | **Claim** as the fundamental assertion | *Claim ≠ Truth* (AX-1) |
  | *"UNKNOWN ≠ permission"* | UNKNOWN preservation (H-ZERO-001) |
  | *governed ≠ authoritative* | authority dimension |
  | conflict relations | contradiction handling |

  To be tested against the corpus.
