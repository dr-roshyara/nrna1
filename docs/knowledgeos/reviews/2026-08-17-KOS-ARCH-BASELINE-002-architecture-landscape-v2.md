# KnowledgeOS — Architecture Landscape v2
## KOS-ARCH-BASELINE-002 · The strategic model after ADR-AIP-03

**Work item:** `KOS-ARCH-BASELINE-002` · **Grant:** `G-KOS-ARCHBASE2-MODEL` (AUTHORIZED) · **Session:** `S4-architecture-landscape-v2`, ACTIVE, mutation owner (START recorded at seq 3, human act 2026-08-17) · **Date:** 2026-08-17

> ## PROPOSAL — decides nothing
> All three v2 deliverables (this landscape · Context Map v2 · Capability Map v2) are **proposals**. *Architecture may propose; the PO/ARB decides.* ADR-AIP-01's frozen context table is amended **only by the acceptance of these deliverables** — never silently, and not by this document's existence. Until acceptance, the authoritative strategic model remains ADR-AIP-01 as amended by ADR-AIP-03's signed decisions.
>
> **Attribution disclosure (assignment `executionContext`, mandatory):** performed by a **fresh Architecture terminal** (claude-code session, model Claude Fable 5) with **no prior estate history on this work item**. Separation is **Declared, not attestable** (`INV-ATTR-2`). Prior contact this session, disclosed: the terminal registered the seq-3 START into the work-item record, and **re-measured `AST-015` (`workflow-state.php`) at source** while verifying its own authority — those re-measurements are marked `Observed (re-measured 2026-08-17)` below. **R-34/P-2 forward constraint: this producer must not independently verify the v2 model.**
>
> **Discovery guard (registered with the START):** the task was executed as **discovery, not justification**. Every hypothesis the commission carried (the owned/not-owned candidate lists, the two relationship hypotheses) was **tested against evidence**; §3.5 records where the test *corrected* a candidate. The context name is treated as provisional (§3.6).

**Evidence base:** accepted Phase A v1.1 baseline (content `40026b12`, acceptance `378f6eaa`) — the sole measured current-state evidence · ADR-AIP-03 **ACCEPTED, both consequences taken** (BC-7 recognized · CAP-14 assigned · role model deferred to ADR-AIP-04) · Stage-2 Bounded Context Confirmation Report (`f4eb4f76`) · ADR-AIP-01 (the frozen six-context model this proposal amends by authority of ADR-AIP-03 + this work's acceptance) · the Capability Identity Invariant (*one capability identifier = one semantic owner*) · fresh source re-measurement of `AST-015` this session, marked where used.

---

# 1 · The landscape in one statement

**The platform's strategic model grows from six declared bounded contexts to seven: BC-7 Governed Session Orchestration — the context the platform was already organized around — receives its declared boundary, its capability (`CAP-14`), and its relationships.** Nothing physical changes: the CMP-004 placement stands as *physical implementation placement* (ADR-AIP-03's signed closing clause); this landscape changes the **model**, and every physical change remains future work under its own authorization (`R-37`).

The product/platform division is untouched: **PublicDigit (Election) remains the Core Domain; the entire platform, BC-7 included, is Supporting** (`AIP-14`, ADR-AIP-02, restated in ADR-AIP-03 §3 Option A).

# 2 · The seven contexts

| # | Bounded context | Subdomain class | Declared purpose | Executable realization (baseline v1.1 snapshot 2026-08-15) | Standing open questions |
|---|---|---|---|---|---|
| BC-1 | Knowledge Governance | Supporting | knowledge cards, authority×status, packages, lint | NONE — CMP-003 `deferred v0.0`; constitution + convention only | U-4 one-or-two knowledge systems (ADR-C4) |
| BC-2 | Implementation Guidance | Supporting | plan/WBS/DoD, discipline tripwires, step order | YES — 3 active Tier-2 hooks | **overload dissolved at model level by BC-7** (§6) |
| BC-3 | Verification & Evidence | Supporting | registered checks, immutable verdicts, traceability | PARTIAL — one Tier-1 guard live | — |
| BC-4 | Adversarial Review Support | Supporting | attempt-to-reject, producer ≠ reviewer | NONE as component — performed by role model + human acts | its nature: rules-only context vs role-model policy (OQ-2, ADR-C7) — **carried open, not decided here** |
| BC-5 | Design & Decision Support | **declared Core (platform) — challenge standing** | ADR/IDD drafting, capability modelling | PARTIAL — by-reference templates + registry data | Stage-2 Core challenge + registry placement (OQ-3, M-2, ADR-C2) — **carried open** |
| BC-6 | Session Continuity | Supporting | bootstrap, append-only session records, staleness | YES — cleanest boundary in the system | — |
| **BC-7** | **Governed Session Orchestration** *(NEW — recognized by ADR-AIP-03)* | **Supporting — never Product Core** | **the lifecycle of governed engineering work: work items, session assignments, roles, transitions, grants-as-records, handoffs, human-act registration, mutation ownership** | **LIVE — the platform's only executable governance surface: `AST-015` (record mechanism) + `AST-016` (resolver), one JSON record per work item** | role-model ownership (OQ-5/OB-2 → **ADR-AIP-04, expressly deferred**) · BC-4 line (ADR-C7) · store provenance (C-3, `R-37`-gated, ADR-C6) |
| ext | Project Governance · Product Engineering | external | upstream authority and fact sources | external by declaration | — |

*Census note:* today the mechanism holds **14 work-item records** against the baseline's 8 (`Observed` today, `ls runtime/workflow/`). Recorded as growth evidence that BC-7 executes continuously; the baseline's snapshot figures are **not** amended (its own V-E note explains why they cannot be re-audited).

---

# 3 · BC-7 boundary analysis (Required analysis 1)

## 3.1 Owned language — tested, not inherited

| Term | Verdict | Evidence and reasoning |
|---|---|---|
| **Work Item** | **OWNED** — the aggregate root | identity + invariants + persistence boundary = exactly one JSON record (baseline §7, `Inferred`/medium; schema `Observed`) |
| **Session Assignment** | **OWNED** — entity within Work Item | REGISTER creates it; role immutable per assignment (`R8`); state folded, never stored |
| **Lifecycle State / Transition** | **OWNED** | the transition vocabulary (REGISTER · HANDOFF · START · CONTINUATION · STOP · COMPLETE · FAIL · CANCEL) appears in no other context's language; state = fold of the append-only log (`Observed`, re-measured) |
| **Mutation Ownership** | **OWNED** | single-valued fold output; I-1's home |
| **Handoff** | **OWNED — with a seam**: the handoff *mechanics* (token required, owner-only, bootstrap rule) are BC-7's; the token *content* is a **reference into the knowledge estate**, whose meaning BC-7 never interprets (§5) |
| **Grant** | **SPLIT — the hypothesis needed correction.** BC-7 owns the grant **record**: its schema, registration discipline, status vocabulary, queryability. It does **not** own the grant's **authority content** — what the scope means, who may grant, whether granting was right. Source evidence: the grant path *refuses to exist without* `humanActRef` — *"the record never manufactures authority (G-2/R5b)"* (`Observed`, re-measured 2026-08-17) |
| **Human Act** | **CORRECTED — NOT owned.** The commission's candidate list placed it in the owned column; the test moves it out. The act is a performative act of the Human PO/ARB, existing **outside the software** as a committed artifact (baseline §5.3). What BC-7 owns is the act's **registration**: the requirement that a recorded act exists (G-3 precondition on START, `Observed` re-measured) and the reference to it (`humanActRef`). Owning the registration of a thing is not owning the thing |
| **Role** | **NOT decided — expressly deferred.** Measured facts only: BC-7 stores role **values** against a per-workflow declared role set (fixed at `init --roles`, `Observed` re-measured) and enforces role **immutability** (`R8`). Whether the role **model** (definitions, responsibilities, the §9 matrix) is BC-7's own language or a governance-published language BC-7 consumes is **ADR-AIP-04's question** (OQ-5/OB-2) and is not advanced one inch here. The measured shape — role sets arrive as *data at record creation* — is recorded as **evidence for the future discovery**, not as a conclusion |

## 3.2 Invariants — owned versus preserved *(the sharpest boundary instrument)*

The distinction that keeps the boundary honest: an invariant a context **owns** is one whose *reason* lives in that context; an invariant it **preserves** is one it enforces on behalf of an owner elsewhere. (Same discipline as the product side's rule: anonymity is constitutional, Messaging only preserves it.)

| Invariant (baseline §8) | BC-7 owns or preserves? | Reasoning |
|---|---|---|
| I-1 single mutation owner | **OWNS** | exists to prevent record-integrity collisions (F1/F2/F4/F6) — a process-integrity reason, native to BC-7 |
| I-2 activation = HANDOFF ∧ human START (G-3) | **OWNS the conjunction mechanics; PRESERVES its authority premise** | that *both* facts must be recorded is BC-7's machine; that a *human* must originate the act is Governance's policy (`R-34` lineage) |
| I-3 sticky STOPPED | **OWNS** | pure lifecycle semantics |
| I-4 Session Registry ≠ Authority State, never merged | **OWNS** | realized in BC-7's own schema (disjoint records, disjoint writer rules) — the context's central structural rule |
| I-5 only Governance writes authority | **PRESERVES** — Governance owns it | the writer policy is the rulebook's (`G-2`); BC-7 enforces it mechanically on 2 of 5 transition types only (`recordedBy ∈ {governance, human}` checked on COMPLETE/CONTINUATION; free string on REGISTER/HANDOFF/START — `Observed`, re-measured; the C-4 limitation stands, recorded not remedied, `R-37`/ADR-C6) |
| I-6 role immutable per assignment | **OWNS the enforcement**; the role *semantics* deferred (§3.1) | `R8` refusal in source |
| I-7 process identity never authorizes | **PRESERVES** — a governance principle realized by *absence* | no code path reads process identity for a gate (`Observed`) |
| I-8 engineering never accepts its own work | **PRESERVES only** — Governance/`R-34` owns it | BC-7's contribution is making role separation *recordable and checkable*, never deciding it |
| I-10 the record outranks prose | **OWNS** | `AST-016` refuses prose input; the fold is the machine-truth |

## 3.3 Responsibilities

1. **Record** governed-work lifecycle facts (append-only transitions) and authority registrations (grants), in two never-merged records per work item.
2. **Refuse** illegal transitions — the machine's only decisions are *record-consistency* decisions (unregistered START, tokenless handoff, non-owner handoff, exit from sticky STOPPED). `Observed`, re-measured.
3. **Fold** — derive current state (sessions, mutation owner, work-item state) from the log; never store derived state.
4. **Answer** — `fold` / `identity` / `authorized` as read-only queries; the resolver (`AST-016`) as the delegating read surface (its V-3 specification gap stands as registered — open finding, not repaired here).
5. **Reference, never contain** — authority origins (`humanActRef`), handoff tokens (`tokenRef`), and knowledge artifacts are held **by reference into the estates that own them**.

## 3.4 Exclusions — what BC-7 does NOT own *(tested; the list grew)*

The commission's candidates, all **confirmed** excluded, each with its evidence:

* **Policy** — the mechanism cannot evaluate authority even when asked: `authorized` answers *"does any AUTHORIZED grant carry exactly this scope string"* by string equality and **never consults the session** (`Observed`, re-measured — the D-2 probe finding confirmed at source). A context that cannot bind a grant to a session cannot be pretending to own authorization policy; the "defect" is boundary-conforming behaviour.
* **Evidence meaning** — grants and tokens **link to, never contain**, the acts and artifacts they cite (source comment at the `identity` linkage, `Observed`).
* **Architecture decisions** — BC-7 records that an ADR-shaped activity happened; the decision lives in the ADR series (BC-5/Governance).
* **Implementation** — mechanically enforced on this very work item: the workflow's declared role set excludes `implementation`.
* **Knowledge lifecycle** — BC-1's (and ADR-C4's) territory; see §5.

Discovered additions, from the same tests:

* **Authority evaluation of any kind** — no gate in the machine reads *who* is acting (I-7); `recordedBy` is a recorded claim, not an authenticated identity.
* **Actor identity attestation** — attribution is *Declared, never attested* (`INV-ATTR-2`); BC-7 records declarations and must never be read as attesting them.
* **Acceptance and closure decisions** — COMPLETE is validated only as to *recorder class* (governance/human); the decision to close is a governance act (`G-1`) BC-7 registers.
* **Time and scheduling** — the machine holds no clocks, deadlines, or timers; sequence is `seq`, never wall-time authority.

## 3.5 Hypothesis test results (commission inputs → verdicts)

| Commission candidate | Verdict |
|---|---|
| Work Item · Assignment · Handoff · Lifecycle State · Mutation Ownership · Transition → owned | **CONFIRMED** |
| Grant → owned | **REFINED: record owned; authority content excluded** |
| Human Act → owned | **CORRECTED: registration owned; the act itself excluded** |
| Policy · Evidence meaning · Architecture decisions · Implementation · Knowledge lifecycle → not owned | **CONFIRMED**, with four discovered additions (§3.4) |

## 3.6 Name-fitness observation *(observation for the PO/ARB — not a proposal to rename)*

The discovered language centres on the **Work Item** (the aggregate root); *sessions* are entities within it, and the context also owns grants-as-records and human-act registration, which are not session concepts. "Governed **Session** Orchestration" therefore names the context by one of its entities. Candidate truer names, should the BC-7 Domain Model stage confirm the observation: *Governed Work Orchestration* · *Governance Execution*. **Recommendation: keep the recognized name** — it is the name ADR-AIP-03 signed, and renaming on one analysis would be churn; re-test at the domain-model stage. Registered here so the ubiquitous language, not the first label, decides eventually — per the discovery guard.

---

# 4 · Relationship study 1 — BC-7 ↔ Governance *(policy versus process)* (Required analysis 2a)

**Hypothesis under test:** Governance = authority **policy** ("who may approve?"); BC-7 = authority **execution lifecycle** ("was approval recorded?").

**"Governance" disambiguated first** — the term names two things the study must keep apart:
1. **Project Governance, the external upstream domain** — the Human PO/ARB, the rulebook (`A-1…A-8`), ES standards, ADR series. The origin of all policy and all authority.
2. **Governance, the role** — an actor (S2 lane) that *registers* human acts and *routes* work, acting **through** BC-7's mechanism.

**Tests run against the re-measured mechanism:**

| # | Test | Result |
|---|---|---|
| T-a | Can BC-7 answer a policy question? `authorized` does scope-string equality against AUTHORIZED grants; never consults the session, never interprets scope | **Cannot** — supports the hypothesis (`Observed`, re-measured) |
| T-b | Can BC-7 create authority? Grant registration hard-refuses without `humanActRef`; writer restricted to `governance` | **Cannot** — authority enters only by registered reference (`Observed`, re-measured) |
| T-c | Does START validate entitlement? It validates the *form* (non-empty human act ∧ recorded handoff), never whether the human was entitled | **No** — process, not policy (`Observed`, re-measured) |
| T-d | Refutation attempt: does the machine ever decide anything policy-like? Its refusals (non-owner handoff, tokenless handoff, sticky STOPPED) are record-consistency decisions whose reasons are process-integrity findings (F1/F2/F4/F6), not authority content | **Refutation fails** — the hypothesis survives |
| T-e | Change-reason coupling: what moves the machine? Rulebook amendments (`A-1…A-8`) become machine preconditions; guidance-tripwire changes never touch it | Governance is the **policy upstream** (`Observed` in the source's rule citations: G-1/G-2/G-3/R8/Inv A–I) |

**Discovered relationship (the hypothesis, refined):** the hypothesis's own phrase "execution lifecycle" needs one correction — **BC-7 does not execute approvals; humans do.** BC-7 is a **registry of governed occurrence**: it makes the lifecycle of governed work *recordable, foldable, and refusable*. Governance is upstream **twice**, in two different capacities:

```
PROJECT GOVERNANCE (external domain — policy + authority origin)
   │
   │ ① POLICY FLOW — rulebook amendments become machine preconditions
   │    pattern: CUSTOMER–SUPPLIER, Governance upstream            [Inferred · high]
   ▼
BC-7 GOVERNED SESSION ORCHESTRATION
   ▲
   │ ② OPERATING FLOW — the Governance ROLE writes registrations
   │    through BC-7's record schema (grants: sole writer, I-5)
   │    pattern: PUBLISHED LANGUAGE at the writing seam            [Inferred · high]
```

**The boundary sentence:** *Governance decides whether an act was authorized; BC-7 answers whether it was recorded — and refuses to answer anything else.* The business-rule ≠ business-process distinction holds, strengthened: the mechanism's measured inability to evaluate policy (T-a) is not a gap to fix but **the boundary, realized as designed** (`R-37`: authority deliberately not automated — baseline §1.4).

**Standing limitation carried, not resolved:** the writer boundary is convention-backed on the three creation transitions (C-4). Whether the evidence now on record meets `R-37`'s bar for hardening is **ADR-C6's question** — named, untouched.

---

# 5 · Relationship study 2 — BC-7 ↔ Knowledge Engineering *(knowledge identity versus workflow history)* (Required analysis 2b)

**Hypothesis under test:** Knowledge Engineering answers *"what is known?"*; BC-7 answers *"how did governed work progress?"* — do not merge.

**Counterpart disambiguated:** in today's declared map, "Knowledge Engineering" is **BC-1 Knowledge Governance plus the knowledge estate** (EKP `docs/knowledge/`, PKS `docs/pks/`, the ADR/review corpus). The term itself is the Principal's forward vocabulary (a candidate ADR-AIP-04 role); this study analyses the boundary against the estate **as it exists**, deciding neither U-4/ADR-C4 (one knowledge system or two) nor anything about roles.

**Tests:**

| # | Test | Result |
|---|---|---|
| T-f | Reference direction: transitions carry `tokenRef` (paths into the estate), grants carry `humanActRef` (committed acts). Does BC-7 machine-read any knowledge artifact? The mechanism reads **only its own JSON** (`Observed`, re-measured: dependency root, no `require`/`include`/estate reads) | **One-way reference by identity** — BC-7 points, never parses |
| T-g | Reverse direction: knowledge narrates workflow (session logs, registration artifacts) — is narrative authoritative over the record? I-10: the record outranks prose; `AST-016` refuses prose | **No** — narrative describes, never determines |
| T-h | Lifecycle discipline: knowledge artifacts are committed, versioned, promoted through adjudication (authority: generated → human-reviewed; ES-006 ladder). Workflow records are append-only, folded, **gitignored — no provenance** (C-3) | **Two different truth disciplines** — a fold is not an adjudication |
| T-i | Refutation attempt: "workflow history is itself knowledge — merge them." Fails on T-h: knowledge truth is **adjudicated** (a human accepts a meaning); workflow truth is **folded** (a deterministic computation over recorded facts). Merging would force one authority model onto both — precisely the two-models-one-boundary defect ADR-AIP-03 just repaired | **Refutation fails** — do-not-merge holds |

**Discovered relationship (the hypothesis, sharpened):** the boundary test is **adjudicated identity versus folded occurrence**. Knowledge Engineering owns what a thing *is* and whether it is accepted as true; BC-7 owns *that and in what order* governed activity occurred around it.

```
KNOWLEDGE ESTATE (BC-1 + corpus)                BC-7
adjudicated artifacts, stable identities  ◄──── references by identity
(paths · commits · ADR ids · act texts)         (tokenRef · humanActRef)     [Observed]
                                          ────► narrated into prose
                                                (session logs, registrations)
                                                prose NEVER authoritative (I-10) [Observed]
        pattern: mutual reference across a PUBLISHED-LANGUAGE seam;
        NO shared model; NO shared lifecycle discipline               [Inferred · high]
```

**The boundary sentence:** *Knowledge Engineering holds the meaning; BC-7 holds the movement. Each cites the other by identifier and neither interprets the other's content.*

**Tension recorded, not resolved (boundary-confirming, and still a real problem):** BC-7's own store violates the knowledge estate's provenance discipline — the authority ledger is the one governance artifact git cannot see (C-3, V-E's real cost already demonstrated). That the two stores obey *different* rules is evidence the boundary is real; whether BC-7's store *should* adopt provenance is **ADR-C6's `R-37` question** — named, untouched, no mechanism proposed.

---

# 6 · What changes in the model — and what stands

| Changes (on acceptance of these deliverables) | Stands unchanged |
|---|---|
| Context map: 6 → 7 (BC-7 added, Supporting) | **All physical placement** — CMP-004 keeps the mechanism assets on disk; ADR-AIP-03's closing clause: *physical implementation placement, not the final strategic domain boundary* |
| ADR-AIP-01 context table amended by this acceptance (never silently) | folders · components · code · hooks · registrations (`R-37`) |
| Capability map: `CAP-14` (see Capability Map v2) — the T-1 homelessness finding closed *in the model* | ADR-AIP-04 and the role model — **expressly deferred, untouched** |
| CMP-004's mechanism assets (`AST-015`/`AST-016`) **re-labelled in the model** as BC-7's — dissolving the two-languages-in-one-component smell (C-1/M-1) at the model level | ADR-C2…C7 — every one carried open (§7) |
| Stage-2 M-1 re-classified: *resolved in the model; physical placement standing* | the accepted Phase A v1.1 baseline — reconstruction, not amended by this proposal |

# 7 · What this landscape does NOT decide

The composition-root census and the declared-Core question (**ADR-C2**; BC-5's Core challenge carried open in §2) · registry-first scope (**ADR-C3**) · one-or-two knowledge systems (**ADR-C4**/U-4) · artifact-store boundary (**ADR-C5**/U-2/M-5) · authority-store provenance and writer-boundary hardening (**ADR-C6**, `R-37`-gated; C-3/C-4 carried as standing limitations) · BC-4's nature (**ADR-C7**/OQ-2 — the BC-7↔BC-4 line is drawn *dashed* in Context Map v2) · **role-model ownership (ADR-AIP-04 — deferred by the signed consequence decision; §3.1 records measured evidence for that discovery and decides nothing)** · any implementation, folder, technology, or physical component change.

# 8 · Confidence summary

| Claim family | Class · confidence |
|---|---|
| Mechanism behaviour cited as re-measured (grant refusal · START conjunction · `authorized` string-match · `recordedBy` validation surface · role-set-at-init · dependency root) | `Observed` (re-measured 2026-08-17 at `AST-015` source) · high |
| All baseline figures and censuses | inherited from accepted v1.1 (`Observed`/`Declared`/`Inferred` as marked there) |
| Owned/preserved invariant split; the two relationship patterns; grant/human-act ownership corrections | `Inferred` · high — single-step readings of `Observed` mechanism facts |
| Name-fitness observation (§3.6) | `Inferred` · medium — flagged for the domain-model stage, not asserted |
| Everything touching U-4, U-7, OB-1/2/3, role semantics | `Unknown` / deferred — carried, not filled |

---

**Companion deliverables:** `2026-08-17-KOS-ARCH-BASELINE-002-context-map-v2.md` · `2026-08-17-KOS-ARCH-BASELINE-002-capability-map-v2.md`

**Next actor:** PO/ARB — accept, amend, or decline the v2 set. On acceptance: ADR-AIP-01's table is amended by reference to these deliverables; the BC-7 Domain Model becomes the next commissionable step (per the recorded roadmap), then ADR-AIP-04 discovery. Independent verification of this producer's work precedes acceptance if the PO/ARB so routes (`R-34`/P-2).

**Traceability:** commission `2026-08-17-KOS-ARCH-BASELINE-002-commission.md` · grant `G-KOS-ARCHBASE2-MODEL` · START seq 3 (2026-08-17) · accepted baseline v1.1 `40026b12` (acceptance `378f6eaa`) §§1–11 · ADR-AIP-03 (accepted; consequences `CAP-14`, role-model deferral) · ADR-AIP-01 · Stage-2 report `f4eb4f76` §§2–10 · `AST-015` source (re-measured) · registry `AST-015`/`AST-016` entries incl. open finding V-3 · `R-34` · `R-37` · `ES-001.1` · `ES-005.4` · `AIP-14`.
