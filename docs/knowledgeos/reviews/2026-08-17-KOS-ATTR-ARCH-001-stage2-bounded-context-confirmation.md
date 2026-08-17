# `KOS-ATTR-ARCH-001` — Stage 2: Bounded Context Confirmation Report

**Type:** DDD strategic analysis (Architecture terminal) · **Date:** 2026-08-17
**Prerequisite verified:** `KOS-ARCH-BASELINE-001` Phase A **v1.1 ACCEPTED** (registration `2026-08-17-KOS-ARCH-BASELINE-001-phase-a-acceptance-registration.md`, commit `378f6eaa`; accepted content `40026b12`; Verification #3 accepted). Stage-2 **analysis** is the authorized scope of that acceptance.
**Evidence source:** the accepted Phase A v1.1 baseline **only** (`docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md`). No new measurement was performed; every baseline figure carries the baseline's own classification, and every judgment added here is marked **`Inferred`** with its reasoning. The baseline's snapshot (2026-08-15) is this analysis's snapshot.

> ## What this report is, and is not
> **Analysis, not decision.** It confirms or challenges the declared bounded contexts, names candidates, and produces ADR candidates. It approves **no** final bounded context, designs **no** implementation architecture, creates **no** classes, chooses **no** technology, designs **no** database, writes **no** code. Approval of any boundary is a PO/ARB act that this report cannot perform.

---

## 1 · Method

For each declared context (baseline §3.1) three strategic questions were asked, answerable from the baseline alone:

1. **Language test** — does a distinct, self-consistent ubiquitous language exist for it? *(A bounded context is a language boundary, not a code boundary — so zero executable realization does not by itself refute a context.)*
2. **Ownership test** — does it own identifiable state, decisions, or invariants (baseline §5, §8)?
3. **Change-reason test** — would it change for reasons different from its neighbours'?

A context can pass all three with no code (rules-only contexts are real), but a context that fails the language test cannot be rescued by having code.

---

## 2 · Verdict per declared context

| BC | Declared context | Verdict | One-line reason | Confidence |
|---|---|---|---|---|
| BC-1 | Knowledge Governance | **CONFIRM, boundary UNRESOLVED inside** | coherent language exists (constitution), but possibly **two** knowledge systems under one label (U-4) | `Inferred` · medium |
| BC-2 | Implementation Guidance | **CONFIRM, but currently overloaded** | its component hosts a second, foreign language (orchestration) | `Inferred` · high |
| BC-3 | Verification & Evidence | **CONFIRM** | distinct language (checks, verdicts, traceability), partial realization is not a boundary defect | `Inferred` · medium |
| BC-4 | Adversarial Review Support | **CONFIRM the capability; CHALLENGE the context** | the function is real but realized entirely by the role model and human acts, not by anything with its own state | `Inferred` · medium |
| BC-5 | Design & Decision Support | **CHALLENGE as declared Core** | declared Core, measured thinnest; and it holds one responsibility (the registry) that does not speak its language | `Inferred` · medium |
| BC-6 | Session Continuity | **CONFIRM — the cleanest boundary in the system** | own language, own state stores, live realization, no leakage found in the baseline | `Inferred` · high |
| — | *(undeclared)* Governed Session Orchestration | **PROMOTE TO CANDIDATE BC-7** | see §4 — the strongest candidate on the record | `Inferred` · medium |

### 2a · Reasoning that the table compresses

**BC-1 Knowledge Governance — confirm, unresolved inside.** The language test passes: a Knowledge-Constitution exists with its own vocabulary (`Observed` as a document, baseline §5.2). The ownership test half-passes: knowledge is governed "by constitution and convention, with no executing component" — ownership by rules is still ownership. **But the baseline records two knowledge tiers with different constitutions** (`docs/knowledge/` EKP vs `docs/pks/` under `ES-006`'s promotion ladder) and cannot say whether they are one system (U-4, `Unknown`). A bounded context whose *internal* language may actually be two languages is confirmed only provisionally: **the boundary line is right or one line short, and the evidence cannot tell.** → OQ-1, ADR-C4.

**BC-2 Implementation Guidance — confirm, overloaded.** The discipline-tripwire language (plan/WBS/DoD, step order, reminders) is distinct and realized (`Observed`, 3 live hooks). **The overload:** CMP-004 also carries `AST-015`/`AST-016`, whose language — `SessionAssignment`, `mutationOwner`, `HANDOFF`, `START`, `grant`, `humanActRef` — **"appears in none of the six declared contexts' ubiquitous-language sections"** (baseline §3.2, `Observed` about the documents). Two languages in one component is the classic sign of a boundary running *through* a box rather than around it. The context is confirmed; **the component's contents are not all this context's.** → §5 M-1.

**BC-3 Verification & Evidence — confirm.** Distinct language, its own declared purpose, one live Tier-1 blocking asset (`Observed`). Partial realization (CMP-005 `under-construction v0.1`) affects the *roadmap*, not the *boundary*. No evidence in the baseline shows its language leaking into or out of a neighbour.

**BC-4 Adversarial Review Support — the interesting case.** The capability is unambiguously real: the baseline's own production process demonstrates it (producer ≠ reviewer, `R-34`, `I-8`), and §9's role model realizes it. But as a *context*, it fails the ownership test as measured: **no state, no component (CMP-006 `deferred v0.0`), no artifact store of its own** — its entire realization is the AI Responsibility Model plus human acts. Two defensible readings, neither decidable from the baseline: **(a)** it is a rules-only context awaiting realization (like BC-1); **(b)** it is a *policy of the role model*, i.e. a capability **inside** whatever context owns roles and assignments — which today is the undeclared orchestration area. **Reading (b) has one strategic advantage the declared map cannot express: the producer≠verifier invariant is enforced (to the extent it is) by the same record mechanism that holds assignments** — same state, same change-reason. Not decided here. → OQ-2, ADR-C7.

**BC-5 Design & Decision Support — challenge as declared Core.** Two independent challenges, both from baseline facts:
1. **The Core claim does not match the measured centre of gravity.** Baseline §1: the platform's substance is the rule corpus and the one executable governance surface (the workflow mechanism). The declared-Core context is realized only by-reference (frozen templates) plus one data file. A "Core" subdomain that creates nothing and holds the least is a misdeclaration *or* a statement of intent that the current state has not reached — the baseline cannot distinguish ambition from architecture (`Inferred`).
2. **The registry does not speak this context's language.** CAP-13 "Platform Self-Governance" (registry, `Observed` as data) sits in BC-5, but its consumers are governance processes (registry-first compliance, §6.2), and its subject is the platform's own census — not ADR/IDD drafting or capability modelling. → §5 M-2.

**BC-6 Session Continuity — confirm, cleanest.** Own language (bootstrap, rehydration, append-only session records, staleness), own state (`.claude/runtime/YYYY-MM-DD-*`, session logs — §5.1), live realization, and — decisive for a boundary judgment — **the baseline shows its stores disjoint from the orchestration stores** (day-files vs `runtime/workflow/*.json`). No confirmed leakage in either direction.

---

## 3 · True business capabilities (as measured, not as declared)

Ranked by what the baseline shows actually operating. Classification is the baseline's; the *ranking* is `Inferred`.

| # | True capability | Declared home | Measured realization | Note |
|---|---|---|---|---|
| T-1 | **Authority registration & audit** — human acts → registered grants → queryable, foldable state | **none** — no `CAP-` id exists (baseline §4, `Observed`) | AST-015/016 live + governance convention | the platform's most consequential executable capability is undeclared |
| T-2 | **Session continuity & rehydration** | CAP-01/02 (BC-6) | live | matches its declaration — the only capability that fully does |
| T-3 | **Independent assurance by role separation** (producer ≠ verifier ≠ acceptor) | CAP-11 (BC-4) | performed by convention + role model; no component | real, demonstrated, mechanism-free |
| T-4 | **Engineering-discipline nudging** | CAP-06 (BC-2) | live, all Tier-2 non-blocking | advisory by design |
| T-5 | **Destructive-action gating** | CAP-07 family (BC-3) | one Tier-1 guard live | partial |
| T-6 | **Platform self-census** | CAP-13 (BC-5) | data only; not loaded at runtime; bypassed by 3 unregistered hooks | declared binding, measured advisory |
| T-7 | **Knowledge curation** | CAP-03 (BC-1) | not built; convention only | rules-only |
| T-8 | **Decision-artifact drafting support** | CAP-10/12 (BC-5) | by-reference templates | thinnest |

**Strategic reading (`Inferred`, medium):** the capability the system is *actually organized around* — T-1 + T-3, authority made auditable and work made role-separable — has **no declared context and no declared capability id**. The declared map describes the platform the ARB froze before 2026-08-14; the measured platform has since grown its centre somewhere the map does not cover. This is the single most important Stage-2 finding.

---

## 4 · Candidate bounded contexts

### CBC-1 · **Governed Session Orchestration** — promote to candidate BC-7 *(strongest candidate)*

The baseline classified "is this a distinct BC?" as `Inferred`/low and deliberately did not choose (§3.2, U-3). Stage-2 adds the strategic arguments the baseline's Phase-A scope could not make:

* **Language:** complete and self-consistent — work item, session assignment, role, transition, grant, handoff, human act, mutation owner — and **disjoint from all six declared languages** (baseline `Observed` about the documents).
* **Ownership:** its own aggregate (Work Item/Workflow, baseline §7 `Inferred`/medium), its own invariants (I-1, I-2, I-3, I-10), its own persistence (one JSON per work item), its own interpretation authority (AST-015).
* **Change-reason:** it changes when the *governance process* changes (new transition vocabulary, new grant discipline) — never when implementation-guidance tripwires change. The two have shared a component for three days and already needed the baseline's own §3.2 to explain why.
* **Against (recorded honestly):** the Increment-1 boundary decision *deliberately* placed it inside CMP-004 under `ES-001.1` parsimony and the accepted "no new independent subsystem" direction (`Declared`). Recognizing BC-7 would reverse a recorded decision and therefore **requires an ADR, not an analysis** — which is exactly what ADR-C1 is for.

**Confidence: `Inferred` · medium** (raised from the baseline's *low* because Stage-2 is allowed to weigh the strategic evidence Phase A had to leave unweighed; still not high, because the parsimony ruling is a real counter-authority).

### CBC-2 · **Platform Self-Governance** (registry + census + registry-first process) — weak candidate

Evidence for: its subject (the platform's own inventory and compliance) is nobody's declared language; the composition root (CMP-001) is registry-only with no declared home (§6.4); registry-first compliance is measured, not owned. Evidence against: almost no behaviour — one data file and a process convention; may be a **capability of BC-7 or of Governance-the-role** rather than a context. **Confidence: `Inferred` · low.** Recorded so it is weighed once, deliberately — not silently absorbed.

### Explicitly NOT proposed

* No merge of BC-3 + BC-4 is proposed. The overlap in purpose (independent checking) is real, but the Phase-02 keep-separate analysis was a genuine derivation (`Observed` as a document) and nothing measured contradicts it yet. → OQ-2 keeps it visible.
* No split of BC-1 is proposed — U-4 must be answered by governance first (ADR-C4 frames it).

---

## 5 · Misplaced responsibilities

| # | Responsibility | Where it sits | Where its language points | Evidence | Class |
|---|---|---|---|---|---|
| M-1 | Workflow record mechanism + resolver (AST-015/016) | CMP-004 / BC-2 | CBC-1 (orchestration) | baseline §3.2: language absent from all six declared contexts | `Observed` (fact) + `Inferred` (misplacement) · high |
| M-2 | Platform registry (CMP-008, CAP-13) | BC-5 Design & Decision Support | CBC-2 / platform self-governance | §4, §6.2: consumers are governance processes, not design activities | `Inferred` · medium |
| M-3 | Composition root (CMP-001) | registry only — **no declared context at all** | wiring is an architectural responsibility with no owner in the declared map | §6.4 census discrepancy | `Observed` (gap) · high |
| M-4 | 3 unregistered executing hooks (`claude-code-trigger`, `engineering-placement-guard`, `project-knowledge-guard`) | wired in settings, owned by nothing | names *suggest* BC-2/BC-1 territory — **suggestion is not evidence** (the baseline's own central rule: never infer a boundary from a name) | §6.2; runtime behaviour is U-9 `Unknown` | `Observed` (unowned) · high; ownership `Unknown` |
| M-5 | Platform governance artifacts stored in `docs/publicdigit/reviews/` (a Product root) | product artifact store | platform artifact store per `ES-005.1` | §2 boundary rule vs practice; U-2 | `Observed` (divergence) · high |

## 6 · Coupling problems

| # | Coupling | Why it matters strategically | Evidence | Class |
|---|---|---|---|---|
| C-1 | **Two languages in one component** (BC-2 guidance + orchestration mechanism in CMP-004) | every future change to either forces shared ownership decisions; the seam already needed prose (§3.2) to explain | §3.1/§3.2 | `Observed` · high |
| C-2 | **Interpretation authority is environment-swappable** — `KOS_MECHANISM_PATH` selects which AST-015 interprets the records | the boundary of the system's only executable authority surface is soft; a context whose *authority* can be redirected by an env var has an unhardened perimeter | U-6, §6 | `Declared` (documented as not hardened) · medium |
| C-3 | **Authority state has no provenance** — all workflow records gitignored, zero history | the orchestration candidate's own store contradicts the context's purpose (auditable authority); V-E showed the cost is already real: the snapshot's machine figures are retroactively unverifiable | §5.1 + V-E note | `Observed` · high |
| C-4 | **Convention-only writer boundary** — `recordedBy` unvalidated on REGISTER/HANDOFF/START | the Governance-only-writes invariant (I-5) is mechanism-backed on 2 of 5 transitions; the context boundary between Governance and every other actor is prose exactly where entries are created | §5.3, §8 limitation | `Declared` (handover probes) · medium |
| C-5 | **All role lanes share one worktree and one git identity** | every context's actors are physically coupled; I-1 (single mutation owner) is the only guard, and provenance is not separable (U-7) | §9, U-7 | `Observed`/`Unknown` mix · high impact, low resolvability now |
| C-6 | **Registry contract declared binding, measured bypassable** | "registry-first" is a boundary promise other contexts rely on; 3 hooks bypassing it silently converts a contract into a suggestion | §6.2 | `Observed` · high |

**Note on C-3/C-4:** naming them here does **not** propose mechanisms. `R-37` (governance precedes automation; mechanisms only after evidence of insufficiency) governs any remedy — and the baseline itself now *supplies* documented evidence of insufficiency for C-3 (V-E) and C-4 (the probes). Whether that evidence is sufficient is the ARB's call (ADR-C6).

## 7 · Unclear ownership boundaries

* **U-2 (inherited):** platform vs product artifact store — unresolved; M-5 is its concrete instance.
* **U-4 (inherited):** one knowledge system or two — BC-1's internal line.
* **New OB-1 (`Inferred`):** who owns *wiring* (the composition root)? The declared map has no answer; the registry says CMP-001; the frozen table says nothing.
* **New OB-2 (`Inferred`):** who owns *role definitions*? §9's AI Responsibility Model is realized in the rulebook (`A-1`-family) and consumed by orchestration records — if BC-7 is recognized, the role model is either its language or a published language it consumes from governance. Undecidable from the baseline.
* **New OB-3 (`Inferred`):** who owns CAP-05 "progress derivation"? Declared BC-2, realized by nothing executable (U-5) — currently owned by convention in plans/CONTEXT, i.e. partially by BC-6's stores. A capability whose declared owner and de facto store differ.

---

## 8 · Context Map (current state, as analysed)

```
                    HUMAN PO/ARB (authority origin — outside the software)
                          │ performative acts (committed artifacts)
                          ▼
              [Governance role registers]                       ← writer boundary is
                          │                                        convention-backed (C-4)
                          ▼
   ┌──────────────────────────────────────────────────────────────────────┐
   │  CBC-1  GOVERNED SESSION ORCHESTRATION  (candidate BC-7 — undeclared)│
   │  work items · assignments · roles · transitions · grants             │
   │  state: runtime/workflow/*.json (no provenance — C-3)                │
   └──────┬───────────────────────────────────────────────────────────────┘
          │ CONFORMIST: every role session conforms to the record
          │ (AST-016 refuses prose; I-10)                                    [Inferred]
          ▼
   BC-2 Implementation      BC-3 Verification       BC-4 Adversarial Review
   Guidance (advisory,      & Evidence (1 Tier-1    (capability realized BY
   Tier-2 — non-binding     blocking gate on        the role model — may
   suggestions to           product mutations)      belong inside CBC-1;
   sessions)                                        OQ-2)
          ▲                          ▲
          │ SHARED COMPONENT (C-1):  │
          │ CMP-004 hosts BC-2 AND   │
          │ CBC-1 mechanism          │
                                                     BC-1 Knowledge Governance
   BC-6 Session Continuity                           (rules-only; one or two
   (SUPPLIER to every session:                       systems inside — U-4)
   uniform bootstrap injection                       BC-5 Design & Decision
   at SessionStart)              [Inferred]          Support (by-reference
                                                     templates + registry;
                                                     registry misplaced — M-2)
   ───────────────────────────────────────────────────────────────────────
   PLATFORM  →  PRODUCT (PublicDigit / Election = declared CORE DOMAIN, AIP-14)
   one-way: platform reads/guards app/; product never invokes platform [Observed]
   relationship: SUPPORTING SUBDOMAIN with a one-way guard seam
```

Relationship labels (`CONFORMIST`, `SUPPLIER`) are **`Inferred`** strategic readings of measured facts; the arrows and one-way claims are the baseline's `Observed` facts.

---

## 9 · Open questions

| # | Question | Feeds |
|---|---|---|
| OQ-1 | Are EKP (`docs/knowledge/`) and PKS (`docs/pks/`) one knowledge system with two maturity tiers, or two systems needing a boundary between them? *(U-4)* | ADR-C4 |
| OQ-2 | Is Adversarial Review Support a rules-only context awaiting a component, or a policy of the role model living inside orchestration? | ADR-C7 |
| OQ-3 | Is "declared Core = Design & Decision Support" still the ARB's intent, given the measured centre of gravity (§3)? A Core declaration is strategy, not measurement — only the ARB can restate it | ADR-C2 |
| OQ-4 | Does the composition root belong in the component model (making the declared census 8), or outside it deliberately (keeping 7 and saying why)? *(from §6.4)* | ADR-C2 |
| OQ-5 | Which context owns the role model — orchestration's own language, or a governance-published language it consumes? *(OB-2)* | ADR-C1 |
| OQ-6 | Is the registry-first contract intended to be binding for hook wiring? If yes, the 3 unregistered hooks are violations; if no, the contract's scope needs restating *(C-6, M-4)* | ADR-C3 |
| OQ-7 | Where do platform governance artifacts live? *(U-2, M-5)* | ADR-C5 |
| OQ-8 | Does the evidence now on record (V-E; the probe findings) meet `R-37`'s bar for hardening the authority store and the writer boundary? *(C-3, C-4)* | ADR-C6 |

## 10 · ADR candidates *(candidates — drafting them is future work; deciding them is PO/ARB)*

| # | ADR candidate | Decides | Priority (`Inferred`) |
|---|---|---|---|
| ADR-C1 | **Recognize or decline BC-7 Governed Session Orchestration** — incl. a `CAP-` id for the orchestration capability either way, and the role-model ownership (OQ-5) | U-3, M-1, C-1, T-1's homelessness | **highest** — every other boundary question touches it |
| ADR-C2 | **Reconcile the declared census with the registry** — 7 vs 8, the composition root's home, and whether BC-5 remains the declared Core | §6.4, OQ-3, OQ-4 | high |
| ADR-C3 | **Registry-first scope** — binding or advisory for hook wiring; disposition of the 3 unregistered hooks | §6.2, C-6, M-4 | high |
| ADR-C4 | **One knowledge system or two** — BC-1's internal boundary | U-4, OQ-1 | medium |
| ADR-C5 | **Artifact-store boundary** — `ES-005.1` vs practice | U-2, M-5 | medium |
| ADR-C6 | **Authority-store provenance and writer-boundary hardening** — an `R-37` evidence-sufficiency ruling, not a mechanism design | C-3, C-4, OQ-8 | medium (impact high, but gated on R-37) |
| ADR-C7 | **BC-4's nature** — rules-only context vs role-model policy | OQ-2 | low — nothing currently blocks on it |

## 11 · Confidence summary

| Claim family | Class · confidence |
|---|---|
| All figures, censuses, dependency directions, state-store facts | inherited from the accepted baseline (`Observed`/`Declared` as marked there) |
| BC-6 confirm; BC-2 confirm-but-overloaded; M-1; M-3; C-1; C-3; C-6 | `Inferred` · **high** — single-step readings of `Observed` facts |
| BC-1/BC-3 confirms; BC-4/BC-5 challenges; CBC-1 promotion; M-2; C-2; C-4; relationship labels | `Inferred` · **medium** — strategic judgment on mixed-class evidence |
| CBC-2; the T-1 centre-of-gravity reading's consequences | `Inferred` · **low–medium** — flagged for deliberate weighing, not assertion |
| Everything touching U-4, U-7, U-9, OB-1/2/3 | `Unknown` — carried forward, not filled |

---

**Not done here (rule compliance):** no implementation architecture · no classes · no technologies · no databases · no code · no final bounded context approved · no mechanism proposed for C-3/C-4 (R-37 respected) · no baseline modification · no Phase B/C of the baseline work item.

**Next actor:** PO/ARB — weigh the verdicts, pick up ADR-C1 first if any. This terminal proposes boundaries; it does not approve them.

**Traceability:** accepted baseline v1.1 (`40026b12`, acceptance `378f6eaa`) §§1–11, U-1…U-9, I-1…I-10 · `AIP-14`/ADR-AIP-02 (as cited by the baseline) · `R-34` · `R-37` · `ES-001.1` parsimony ruling (as cited by the baseline §3.2).
