# EKS-07 — Multi-Process Coordination & Shared Work-State Integrity for AI Engineering

**Status:** **FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM** — registered on the PO/ARB acts of 2026-08-21. ⛔ **Not commissioned; activation requires a human authorization act.**
**Class:** platform-capability / coordination-boundary problem (AI Engineering Platform).
**Not:** an ADR · an adopted rule · an immediate implementation task.
**Registered by:** Governance (`b64828fe`). ⛔ **Approves no architecture, creates no capability, adopts no rule.**

> ### ⭐ **The insight this ticket exists to hold**
> **The problem is not *"we used two AI processes."* The problem is that KnowledgeOS currently has STRONG ROLE GOVERNANCE but WEAKER MULTI-PROCESS STATE SYNCHRONIZATION.**

---

## 1 · Problem

KnowledgeOS allows multiple AI/developer processes to work on the same repository and related work items, **but they do not yet share a sufficiently strong synchronization and provenance protocol.**

```
Process A  → creates evidence
Process B  → continues related work
Shared repository → contains artifacts with incomplete or ambiguous cross-process state
```

**The most recent example:**

```
dd639043   → produced the independent AMD6 Architecture review
bc1b47ef   → produced the AMD6 summary
Git        → records HUMAN commit identity, not the producing AI process
Governance → had to reconstruct provenance MANUALLY
```

A similar provenance-recovery problem was observed earlier in the architecture chain.

## 2 · ⭐ Measured evidence — `OBSERVED`, first-hand, not reconstructed

| # | Measured |
|---|---|
| **1** | **Six workflow transitions (seq 28–33) on `KOS-AIP04-DISCOVERY-001` were recorded by other windows** during a single Governance session — two lane registrations, two handoffs, two human STARTs. Governance discovered them by **re-folding, not by notification** |
| **2** | Governance registered *"no D5 analysis artifact exists"* — **true when measured, false when read.** The proposal existed as `10fbfb05`; the finding had to be marked **superseded by events** in the same session |
| **3** | **`AMD3`…`AMD6` were authored by other lanes while Governance registered their commissions** — the plan grew **293 → 1206 lines** in the same window |
| **4** | ⭐ **The AMD6 provenance split** (§1) — every commit in this estate carries one human author and one `Co-Authored-By` trailer, so **git cannot distinguish producing processes** |
| **5** | ⚠️ **Governance itself reported an artifact as untracked** after checking a path where the file does not exist — the error propagated into **five registered grants** before measurement corrected it |

⚠️ **Item 5 matters disproportionately: the coordination gap produced a GOVERNANCE error, not only an Architecture one.** The role whose purpose is preserving provenance mis-stated it, because it lacked cross-process state.
⚠️ **Item 4 is the SECOND occurrence of one root cause** — the 2026-08-19 provenance reconciliation had already recovered authorship from **write-class tool provenance** because commit metadata was not probative. **Two independent occurrences: evidence, not a standard** (`ES-006.1`).

## 3 · Business problem

The organization is increasingly using multiple AI agents/processes in parallel. Without shared process-state coordination we risk:

> **losing the connection between WHO produced knowledge, WHAT work item it belongs to, WHICH AUTHORITY authorized it, and WHICH LATER DECISION depends on it.**

**Consequences:** duplicated work · stale decisions · incorrect routing · missing evidence · ultimately **loss of trustworthy organizational memory.**

## 4 · Business value

```
multiple AI processes → one governed work state → clear artifact ownership
   → durable provenance → safe parallel development
```

Parallel AI development without losing governance integrity · less manual provenance reconstruction · fewer duplicate or colliding changes · reduced context-switching cost · safer handoffs between agents · better auditability · **scalable use of multiple AI providers/processes.**

## 5 · Scope for future architecture exploration — nine questions

| # | Question |
|---|---|
| **1** | **Process identity** — how does an AI process identify itself *reliably*? ⚠️ **see §7** |
| **2** | **Work-state synchronization** — how does a new process discover the current canonical state before acting? |
| **3** | **Artifact ownership** — how does a process know what it may *modify* versus merely *consume*? |
| **4** | **Handoff protocol** — how is work transferred between processes? |
| **5** | **Provenance** — how is producing-process identity preserved **when git metadata cannot establish it**? |
| **6** | **Concurrency** — how are two processes prevented from acting on **stale governance state**? |
| **7** | **Commit/evidence binding** — how are artifacts, commits, grants, reviews and work items linked? |
| **8** | **Stale-state detection** — how does a process know its context is **no longer current**? |
| **9** | **Multi-agent compatibility** — how can Claude, DeepSeek, Codex and others participate **without creating separate governance realities**? |

## 6 · Candidate rule and capability — ⛔ `PROPOSED`, nothing adopted

> **One repository, multiple processes, ONE GOVERNED STATE** — **not** *"one process, one repository"*, which would discard the complementarity the lanes have demonstrated.

**Candidate boundary:** `Process → Work item → Authorized surface → Evidence → Shared repository` — **not** `Process → whatever it finds → commit`.

**Candidate capability, named not created — Process Coordination / State Synchronization:**

```
PROCESS START → load canonical work state → load active grants → load active actors
→ load outstanding blockers → load artifact ownership → work
→ PRE-COMMIT SYNCHRONIZATION CHECK → commit → publish evidence → update shared state
```

**Eight start questions:** who am I · what work item · what canonical aggregate · what grants authorize me · what do I own · who else owns related artifacts · what changed since my last synchronization · **what must I NOT touch.**
**Four pre-commit questions:** what did another process change · what do I own · what do I only consume · **did I create evidence belonging to another process.**

## 7 · ⚠️ The hardest constraint, stated up front — it bounds questions 1 and 5

**`INV-ATTR-2` (adopted, `P-1`–`P-6`): self-declared identity must never be represented as independently attested.** `INV-ATTR-1`: identity is evidential only; **no gate reads it.**

⇒ **A coordination layer that asks *"who am I?"* receives a SELF-DECLARATION.** It can **record** process identity; it **cannot attest** it.
⇒ ⛔ **Any design premised on trustworthy self-identification is barred at the outset** — which is exactly why §1's case required *reconstruction* rather than a lookup, and why §2 item 4 recurred.
⭐ **Question 1 may therefore have no fully affirmative answer, and the exploration must be free to conclude that** — recording identity durably while attesting it never.

## 8 · Tests any investigation MUST apply

| Test | Requirement |
|---|---|
| **Existing capability first** | `ES-005.4` — the estate already holds `workflow-state.php` with **`Inv A`** (one authoritative record per work item), **`Inv C`** (single mutation owner), **`G-1`/`G-2`** (sole writer) and **`G-3`** (human START + predecessor handoff). ⭐ **Determine whether this is a NEW capability or an EXTENSION of that engine before proposing anything** |
| **Ownership** | a new capability only if **orthogonal ∧ necessary ∧ sufficient** |
| **Authority** | **coordination is not authority** — a synchronization layer must never become a decision-maker |
| **Evidence** | `Recording ≠ Asserting` · `Evidence ≠ Proof` · process identity stays **evidential, never attested** |

## 9 · Suggested acceptance criteria

```
Process A creates artifact → Process B discovers it
   → B knows: work item · authority · producer · artifact status · active blockers · owned surface
   → B can safely continue → provenance remains reconstructable
```

> ### ⭐ **And the decisive one: two processes must be able to work in parallel WITHOUT creating two different interpretations of the same governed work item.**

## 10 · ⛔ Important non-goals

**This item must NOT immediately change the current Track 2 governance model.** It must not: weaken reviewer independence · automate authority · change PO/ARB responsibility · create a new governance authority · alter the current durability migration · **retrofit a new provenance mechanism into the current chain.**

> **The current incidents are EVIDENCE FOR THE FUTURE EXPLORATION, not justification for changing the methodology mid-flight.**

## 11 · Why it is in the backlog now, and not further

**At least two independent observations of the same underlying weakness** — enough to justify a backlog exploration, **not enough to freeze a new architecture rule.** Hence `FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM`, and nothing promoted under `ES-006.1`.

**Next actor only if activated: Architecture Review Board / Principal Architecture Review.**

**Traceability:** PO/ARB acts 2026-08-21 (diagnosis; this ticket specification; the explicit no-retrofit instruction) · workflow record `KOS-AIP04-DISCOVERY-001` seq 28–33 · `DEC-D5 §2` and its supersession · plan commits `bb1708b7` `0a2fa71d` `7d3abc59` `8307beca` · the AMD6 provenance split · the five-grant untracked-artifact error and its correction · 2026-08-19 provenance reconciliation · `INV-ATTR-1`/`INV-ATTR-2` (adopted `P-1`–`P-6`) · `workflow-state.php` `Inv A`/`Inv C`/`G-1`/`G-2`/`G-3` · `ES-005.4` · `ES-006.1` · `EKS-05`

---

# 12 · ⛔ Addendum — 2026-08-22 · PO/ARB activation of ONE minimal correction (append-only; nothing above amended)

**Appended by:** the producing session of work item `KOS-SESSION-BOOTSTRAP-001` — self-declared, not attestable (`INV-ATTR-1`/`INV-ATTR-2`). **This addendum changes nothing above.** It records one bounded activation act and its exact scope, and re-asserts the ticket's status.

## 12.1 · The activation act — scope, and its outer limit

**Act:** PO/ARB approved plan `.claude/plans/sequential-leaping-koala.md` (2026-08-22) as the implementation authorization for **work item `KOS-SESSION-BOOTSTRAP-001`** — a **minimal operational correction** of the coordination-legibility deficiency (process identity in free-text `executionContext` · no machine-readable bootstrap result · the V-3 handoff fact unreadable). **Three binding conditions** are encoded in the plan and the boundary proposal.

**Outer limit, stated once:** this activation authorizes the **bootstrap correction ONLY**. It does **NOT** commission EKS-07, does NOT change this ticket's status, and does NOT authorize any of this ticket's §5 nine questions, §6 candidate capability, or §7 identity-attestation question.

## 12.2 · Measured evidence this correction responded to (live, first-hand)

| # | Measured | Reference |
|---|---|---|
| **1** | `session-resolve.php` → `{"verdict":"AMBIGUOUS","operable":false}`, 4 candidates — a session could not resolve its own lane | diagnostic registration §1 |
| **2** | lane `S5-architecture-dv-correction-review` `executionContext` embeds `claude-code-session:a8ce5a39` in free text; running processes were `8a525719`/`c11a5a12` | diagnostic registration §1 |
| **3** | **V-3**: no AST-015 read command exposes the predecessor-handoff fact (`fold` computes `handoffsTo` but never emits it) — no lawful interpreter could answer *"was I handed work?"* | AST-016 `finding_open` (registry) |
| **4** | recorded refusals *"I will not adopt another process's identity"* were **correct under the rules** — the tooling gave no lawful exit | §1 |

## 12.3 · ⭐ Explicit status statement — EKS-07 is NOT solved

**`EKS-07` remains `FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM`, NOT commissioned. `KOS-SESSION-BOOTSTRAP-001` is a minimal operational correction of ONE observed coordination deficiency — it is NOT an `EKS-07` implementation.** The correction reuses the existing workflow engine (AST-015) and declared role vocabulary per `ES-005.4`; it creates no second engine, identity system, role model, authority model, completion protocol, bounded context, or autonomous authority transfer.

## 12.4 · What the correction delivers (summary)

One read-only resolver (`.claude/scripts/session-bootstrap.php`, **AST-017**) producing one machine-readable `session_bootstrap` report (six-way separation: identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation), delegating ALL workflow interpretation to AST-015 as subprocess per AMENDMENT 2 — with **exactly one bounded exception**: a V-3 handoff read (`v3HandoffRead()`), pinned by regression **S16**. Cross-provider conformance pinned by **S17** (Claude-shaped vs DeepSeek-shaped env, byte-identical JSON). Contract suite S1–S17 (+S2b) — 18/18 GREEN, 210 assertions; full WorkflowEngine suite 47/47 untouched. **Adoption NOT claimed** — registry `planned → verify`, `verified:` block unfilled.

## 12.5 · Recorded `EKS-07 FOLLOW-UP` items (separate governed slices, NOT done here)

| # | Follow-up | Why deferred |
|---|---|---|
| **1** | **V-3 FULL remedy** — an AST-015 read command exposing the handoff fact, so the consumer-side bounded read becomes unnecessary | the clean remedy belongs in the mechanism; the bounded consumer read is a partial remedy for THIS correction |
| **2** | **SESSION_START wiring** of the bootstrap (`runtime_moments: [ON_DEMAND]` today) | V-3 binding: wiring is a fresh governed slice after the full remedy |
| **3** | process-identity attestation (§7 of this ticket) | barred at the outset by `INV-ATTR-1`/`INV-ATTR-2` — this correction records identity, it does not attest it |
| **4** | **Governed handoff-automation capability** — whether a future capability may safely perform `REGISTER`/`HANDOFF` automatically while keeping **human `START`** as the mandatory human authority boundary (G-3) | PO/ARB boundary ruling 2026-08-22: `REGISTER`/`HANDOFF`/`START` are **governed transitions, deliberately manual** — they change authority; automating them is a **new governance capability**, a separate design decision explicitly **NOT smuggled into AST-017** (which stays ON_DEMAND · read-only · no SESSION_START wiring · no automatic authority transfer). Boundary: *"make responsibility resolution automatic before making authority transitions automatic."* |

**STOP.** After this correction is verified, the session does **not** continue into migration governance or any other EKS-07 exploration. A deeper gap revealed during the work is recorded here as FOLLOW-UP, not implemented.

**Traceability (addendum):** work item `KOS-SESSION-BOOTSTRAP-001` · PO/ARB activation act 2026-08-22 · plan `sequential-leaping-koala.md` (three binding conditions) · boundary proposal `2026-08-22-KOS-SESSION-BOOTSTRAP-001-implementation-boundary-proposal.md` · diagnostic + activation registration `2026-08-22-KOS-SESSION-BOOTSTRAP-001-diagnostic-and-activation-registration.md` · commits `e850439a` (registry-first) · `b05cca61` (RED) · `170e3052` (GREEN) · `AST-017` · `AST-015` · `AST-016` · AMENDMENT 2 · `INV-ATTR-1`/`INV-ATTR-2` · `G-3` · `Inv E` · `R6`/`D-2` · `R8` · `C-1` · `ES-005.4` · `ES-004.3` · S16/S17

**Follow-up #4 added:** PO/ARB boundary ruling 2026-08-22 (authority transitions remain governed + manual; handoff-automation is a separate design decision, not AST-017 scope) · `KOS-SESSION-BOOTSTRAP-001` workflow record creation (V-8 ruling sequence step 2) · V-8 determination registration `2026-08-22-KOS-SESSION-BOOTSTRAP-001-V8-DETERMINATION-registration.md`

## Corroborating evidence added 2026-09-08 — a business-language account of a live, recurring incident

**The problem, in plain terms:** two separate work sessions were both adding new backlog tickets to
this same list on the same day, repeatedly, at close to the same time. Each session looked at the
list, found the highest number already used, and picked the next one — the obvious, reasonable thing
to do. Because neither session could see what the other was doing at that exact moment, they picked
the *same* next number three separate times, for six completely unrelated problems. This happened
not once, not twice, but **three times in a row over the course of one session**, between the same
two sessions (`three_model_convergence` and Lane T / theory extraction) — `EKS-12`, then `EKS-13`,
then `EKS-14` all collided in turn.

**Why this matters for the business, not just for tidiness:** a backlog is only trustworthy if a
reader can rely on an ID pointing to exactly one thing. When two different problems briefly shared
the same ID, anyone reading the list, a report, or a cross-reference in that window would have seen
an ambiguous or misleading entry. In every case the mistake was caught quickly by the sessions
themselves and fixed by hand (`EKS-12` → `EKS-13` → `EKS-14` → `EKS-15`, see that ticket's own note)
before anyone outside relied on the wrong number — but that was good luck and attentiveness,
repeated three times, not a guarantee the process provides. Attentiveness that has to fire correctly
three times in one session is not a control; it is a streak. If a third party — another session, or
a human reader — had acted on any of the colliding numbers in any of those three windows, the record
would have been wrong and no one would have been warned.

**What this confirms, not proposes:** this is not a new problem — it is the *exact* problem `EKS-07`
already names (independent AI work sessions sharing state with no way to see each other's concurrent
changes). This entry now records a **third**, real, same-day, same-session-pair occurrence of it, in
a different shared artifact (a document list, not code) than the incidents already on file, and the
repetition rate itself (three times in one session, between the same two parties) is new evidence
about how *frequently* this class of problem actually manifests, not only that it can. It changes
nothing about this ticket's own status, scope, or recommendations — it is evidence, filed the same
way `EKS-09` already established for a different concurrency incident. No mechanism is proposed here;
that remains this ticket's own, still-open question — though the recurrence count is itself a fact a
future disposition decision should weigh.

**Traceability:** `EKS-14-out-of-root-evidence-admission-mechanism.md` (the ticket that collided
twice) · `EKS-13-cross-lane-dependency-without-change-notification.md` and
`EKS-12-theory-governance-scope-gap.md` (the two tickets it collided with) · `docs/knowledgeos/
backlog/00_index.md` (the shared list both sessions were editing).

### Fourth occurrence, 2026-09-08 — the same race, this time caught *before* it happened

⭐ **A fourth instance of the identical race occurred later the same day, between the same two
sessions.** Lane T prepared a new ticket intending to file it as `EKS-15`; the number had already
been taken by the concurrent `three_model_convergence` session in the interval. **The difference is
the outcome, not the cause:** Lane T listed the folder immediately before writing, saw the number was
gone, and filed at `EKS-16` instead — so **no ambiguous entry ever existed** and nothing had to be
renamed afterwards.

⚠️ **This is evidence in two directions, and both should be weighed.** It confirms the race is
**routine rather than exceptional** — four occurrences, one day, one session pair. It also shows the
failure is **cheaply avoidable by a read immediately before the write**, which narrows the remedy
space considerably: the exposure window is the gap between choosing an identifier and committing it,
and it shrinks toward zero as that gap shrinks. ⛔ **That observation is not a proposed mechanism** —
a check that must be remembered is still attentiveness, not a control, exactly as the note above
says. This ticket's own question remains open.

**Traceability:** `EKS-16-derivation-without-consulting-existing-theory.md` (filed at 16 after the
near-miss) · `EKS-15-out-of-root-evidence-admission-mechanism.md` (the number it would have taken) ·
`docs/knowledgeos/theory-extraction/72-P54-CAPABILITY-GRANULARITY-RESOLUTION-ARCHAEOLOGY-AUDIT.md` §10.
