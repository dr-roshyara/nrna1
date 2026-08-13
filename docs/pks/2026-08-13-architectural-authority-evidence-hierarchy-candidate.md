# Code Ownership and Architectural Placement — Evidence Hierarchy

**Type:** PKS **CANDIDATE** — *not a standard, not authoritative, not operational.*
**Date:** 2026-08-13 · **Author:** Engineering (Session 4) · **Maturity:** research · one repository of evidence
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=pks` → `docs/pks` (exit 0)
**Decision record it serves:** [`ADR_20260813_1722_Architecture_Authority_And_Code_Placement`](../publicdigit/adr/ADR_20260813_1722_Architecture_Authority_And_Code_Placement.md) (PROPOSED)
**Evidence:** [`2026-08-13-election-context-architecture-archaeology.md`](../publicdigit/reviews/2026-08-13-election-context-architecture-archaeology.md)

> **Status discipline (`ES-006.1`).** This is an **observation with a proposed procedure**, at **n=1 repository**. It is **not** promoted, **not** an engineering standard, and **must not** be cited as binding. Promotion requires operational evidence of necessity plus a second independent adopter, and is **Human-decides**. Until then it is input to governance review, nothing more.

---

## 1 · The observation

A capable analysis of this repository concluded that `app/Contexts/Elections/Domain/` was *"authoritative for Election rules"* because it had the shape of a modern DDD bounded context. Investigation established the opposite: that directory is downstream of the actual rule owner, holds one capability in 15 files, has no service provider, and is absent from the declared architecture. A sibling directory differing by one character (`app/Contexts/Election/`) is the governed context — and its own source disclaims lifecycle ownership.

**The generalisable observation:**

> **When a repository contains multiple architectural generations, directory structure becomes an actively misleading signal of architectural authority — and it is the cheapest signal available, so it is the one that gets used.**

The failure is structural, not careless. Nothing in the repository recorded *"who owns this capability today"*, so every reader re-derived it, and folder shape was the lowest-cost heuristic.

### 1.1 Preconditions — when this observation applies at all

This is not a universal rule. It applies where **all three** hold:

1. **Multiple architectural generations coexist** in one codebase (this repository has five — see the archaeology §15).
2. **Migrations are incomplete by design**, with authority moved and consumers left behind. (`ADR_20260807_1500` names this the platform's *"recurring migration failure"*.)
3. **Structural markers are unreliable** — a `Contexts/` directory does not imply governance. Here, of 11 such directories, 4 are in deptrac and 6 have a registered provider.

In a single-generation codebase with enforced structure, folder location may be perfectly adequate evidence. **Say so rather than applying this procedure ceremonially** — a criterion that never rejects is suspect (DDD Methodological Fitness Rule).

---

## 2 · Canonical Discovery result (`ES-005.4` — why this is not a new standard)

Before drafting, the existing corpus was searched for a rule that already owns this concern. Result:

| Existing rule | Covers | Gap |
|---|---|---|
| **`ES-005.1/.2/.3`** (three-concern, folder rule, placement litmus) | repository organization and **document** placement | says nothing about **code** authority |
| **`ES-005.4`** Never a Copy | one rule → one home | tells you not to duplicate; not how to find the existing home when evidence conflicts |
| **Operating Loop Phase 2** (Strategic DDD) | *mandates* "which bounded context OWNS this capability? … never proceed with ownership unresolved" | **supplies no procedure for resolving it** |
| **Operating Loop Phase 3** (Canonical Discovery) | *mandates* "search before modelling … if one exists, consume or extend it" | tells you to search; **not how to adjudicate contradictory findings** |
| **DDD `DMT`** | dormant code classified by evidence, never default labels | classifies *unused* code; not *competing live* code |
| **DDD `ADP`** | derivation chain for **new** tactical artifacts | not authority over **existing** code |
| **`ADR_20260806_1620` §6** | "one authority per concern", n=1, **not promoted** | states the goal; no method for locating the authority |

**Conclusion: the mandate exists (Phase 2/3); the decision procedure does not.** This candidate is therefore proposed as **the missing procedure for a rule the Operating Loop already requires** — an extension, never a competitor.

**Per `ES-001.1` and the Standards Index stopping rule** — *"The ES document set is complete … the first question is 'Which existing ES document owns this?' — never 'Should we create ES-007?'"* — if this is ever promoted, the owning question is:

> **Does this extend `ES-005` (Repository), or does it extend the standing Development Discipline rule via `ES-002`?**

Engineering's recommendation, for governance to accept or reject: **`ES-005`**, because the concern is "where things legitimately live and who owns them," which is what ES-005 already hosts for documents. **This recommendation is not a decision** (`ES-006.1`, `R-34`).

---

## 3 · The evidence hierarchy

| Level | Evidence | Concretely, in this repository |
|---|---|---|
| **1** | Explicit ADR / ARB ruling / approved architecture | `docs/publicdigit/adr/`, `docs/adr/`, `engineering/architecture/adr/` |
| **2** | Declared architecture constraints | `deptrac.yaml`; `tests/Architecture/*` fitness tests |
| **3** | DI / provider / runtime wiring | `app/Providers/AppServiceProvider.php`; `config/app.php` providers |
| **4** | Production dependency direction and call sites | imports; constructor injection; grep of callers |
| **5** | Executing implementation | the method body that runs |
| **6** | Documentation / docblocks | class and method comments |
| **7** | Filesystem / namespace / naming | directory and class names |

> **The load-bearing sentence:**
> **Directory structure is evidence of organization, not by itself evidence of architectural authority.**
> A directory name can never override runtime or architectural evidence.

**On the word "hierarchy."** The table is a **ranking of evidence classes**, and what this candidate actually specifies is an **evidence-resolution procedure** (§4 + §4.1) — not an absolute precedence law in which the lowest-numbered level automatically wins. Every caveat in §3.1 is a case where mechanical precedence gives the wrong answer. Read the ranking as *"where to look first and what a finding is worth"*, never as *"level N beats level N+1, unconditionally."* If a future revision ever collapses the procedure back into bare precedence, that is a substantive regression, not a simplification.

### 3.1 Four caveats — the ordering is a proposal, not a law

Applying the ranking mechanically produces wrong answers. Each caveat below is grounded in a specific artifact.

**A · Rank applies only within an artifact's declared scope. Silence is not a negative finding.**
`deptrac.yaml` analyses four paths and explicitly excludes `app/Domain`, `app/Application`, `app/Models` as *"approved external platform dependencies … intentionally outside the analysed paths TODAY."* Mechanically, "absent from deptrac" would mean the lifecycle has no owner — absurd. **Read scope before applying rank.**

**B · Level-1 strength is gated by status and modality.**
*Status:* PROPOSED cannot outrank executing code; ACCEPTED can. *Modality:* a **descriptive** ADR records what is; a **normative** ADR mandates what shall be.

**C · An ACCEPTED normative ADR conflicting with executing code means both are true — of different things.**
`ADR_20260807_1500` (ACCEPTED) states the legacy state columns are *"decided on by nothing"*; two `Election` model methods still read them. Not a contradiction — the ADR's own finding was that *"the authority was declared but the migration was unfinished."*

> **The ADR governs what NEW code must do. The code describes what HAPPENS today. The delta is migration debt with an owner.**

An agent reading only the ADR believes the migration is complete. An agent reading only the code concludes the ADR is dead. **Both errors have occurred here.**

**D · Level 3 needs the binding *and* its resolution site; Level 6 is not uniformly weak.**
- A binding can be a **no-op**: `bind(ElectionOnlyPolicy::class, ElectionOnlyPolicy::class)` proves nothing. A binding can be **bypassed**: `ElectionLifecycleEngine` is bound as an interface, but every production site resolves the concrete `…Impl`. **Level 3 = binding + verified live resolution.**
- A docblock is weak as a *claim* but strong as evidence of *intent* — and a docblock that **disclaims** its own authority is unusually reliable, because it argues against its author's interest. The single most decisive artifact in the whole archaeology was a Level-6 ACL comment.

---

## 4 · The procedure

To be followed before creating, modifying, or moving code that carries a business rule.

```
 1. NAME THE BUSINESS CAPABILITY
      In business language, without reference to code.
      Cannot name it? → STOP and ask. Do not infer. (Operating Loop Phase 1)

 2. STATE THE INVARIANT AT STAKE
      What must remain true? Who owns it — and does this capability
      OWN it or merely PRESERVE it?

 3. LEVEL 1 — search ADR / ARB rulings
      Search ALL ADR locations, not the first one found.
      Record status (PROPOSED vs ACCEPTED) and modality (descriptive vs normative).

 4. LEVEL 2 — declared architecture
      deptrac.yaml + tests/Architecture/*.
      Check SCOPE first (Caveat A). Out-of-scope silence proves nothing.

 5. LEVEL 3 — DI / provider wiring
      Find the binding AND a production site that resolves it (Caveat D).
      Self-bindings and bypassed interfaces are not Level-3 evidence.

 6. LEVEL 4 — dependency direction and call sites
      Which imports which? Who actually calls it?
      A module that imports another's vocabulary is DOWNSTREAM of it
      and cannot be the authority for that vocabulary.

 7. LEVEL 5 — read the executing code
      Not the name. Not the comment. The body that runs.

 8. CLASSIFY the answer
      CURRENT AUTHORITY · CURRENT CAPABILITY OWNER ·
      COMPATIBILITY/STRANGLER · FUTURE INTENTION · UNKNOWN
      Never conflate. Never round UNKNOWN to the nearest guess.

 9. IF LEVELS CONFLICT → resolution procedure (§4.1). Do NOT choose by preference.

10. IF STILL UNRESOLVED → STOP. Report the conflict. Request clarification.
      An unresolved authority is a legitimate deliverable.
      A guessed one is not.
```

### 4.1 Conflict resolution

1. Check **scope** (Caveat A) — out-of-scope silence is not evidence.
2. Check Level-1/2 **status and modality** (Caveat B).
3. ACCEPTED + **normative** vs code → **both hold** (Caveat C). The artifact governs new code; the code describes today; the delta is migration debt. **Record it; do not repair it inside an unrelated ticket.**
4. ACCEPTED + **descriptive** vs code → the artifact is **stale**. Report it.
5. Otherwise the higher level wins; record the disagreement as a finding.
6. None of the above resolves it → **UNRESOLVED. Escalate.**

### 4.2 Prohibitions

**Never relocate or claim authority for code because:**
- another directory looks newer;
- another directory is called `Contexts/` (or `Domain/`, or anything else);
- a folder looks more DDD-like;
- a greenfield structure exists nearby;
- a class name suggests ownership;
- a namespace is deeper, more layered, or better organised.

**Never perform architecture migration as an incidental consequence of implementing a business ticket.** Authority reassignment requires explicit authorization. Reviews *record* deviations; implementation repairs them in a later authorized slice.

**Never delete a competing implementation on the assumption that it is redundant.** Retirement is evidence-led (`ADR_20260806_1620` corollary 4): a legacy path retires when evidence shows what retiring it changes — not because it looks superfluous.

### 4.3 DDD discipline — the anti-equivalences

| Do NOT assume | Because |
|---|---|
| folder = bounded context | 11 `app/Contexts/*` dirs; 4 are governed |
| namespace = bounded context | namespaces track filesystem, nothing more |
| class = aggregate | `app/Models/Election.php` is the de-facto aggregate; `Contexts/Election/Domain/Election.php` is a different, narrower aggregate |
| service = domain service | a Domain Service exists only when an operation cannot belong to one aggregate (DDD `RMSP` companion) |
| `Domain/` = strategic domain ownership | `app/Domain/Election/Enum/VoterSourceStrategy` imports Eloquent models — a Domain-layer purity deviation, yet still the concept's owner |
| two similar names = two versions of one thing | `Election` and `Elections` are unrelated capabilities from different generations |

**Do not invent a bounded context because directories exist.** If discovery finds no home for a classification, **name what is missing and return it to governance; invent nothing** (Operating Loop Phase 3).

---

## 5 · Worked outcomes (regression cases)

These are the procedure's expected outputs. If a future revision changes any outcome, that is a substantive change requiring re-review.

| Case | Outcome |
|---|---|
| Where does EM-VOT-002 live? | Rule → `ElectionConstitution` (Domain). Command enforcement → `ConstitutionalTransitionGuard`. Computed enforcement → `ElectionLifecycleEngineImpl` rule 5. **Both** paths, because neither can reach the other. **Not** `Contexts/Elections`. |
| Where does Election-Only eligibility live? | Decision → `Contexts/Elections/Domain/Policies` (narrow current authority). Mode vocabulary stays in `Domain/Election/Enum/VoterSourceStrategy` — dependency direction forbids the reverse. |
| Should `Contexts/Elections` become the Election BC? | **NO.** Levels 1–4 all decline. Requires an explicit ADR. |
| Move `Domain/Election` into `Contexts/` because it's "newer DDD"? | **STOP.** Level-7 reasoning. Destination disclaims lifecycle ownership in its own source. Would invert proven dependency direction. Escalate as an architecture proposal; never execute in a business ticket. |
| Folder names disagree with runtime DI? | **DI wins** — after verifying the binding is live and not a self-binding (Caveat D). Record the loser as a duplicate authority. |
| Architecture docs disagree with executing code? | **Never choose silently.** Apply §4.1: classify status and modality, then report. Silence is the only forbidden response. |

---

## 6 · If this is ever promoted — automation note

The procedure is **deliberately not automated here**, and the Standards Index position is that *"the smallest automation set justified by evidence: ZERO new hooks"*, with automation being *"an implementation of governance, never governance itself."*

Levels 2–5 and 7 are mechanically checkable (deptrac scope, provider registration, import direction, call-site existence, path). **Levels 1 and 6 are not**, and Level 1 is the highest-ranked — so an automated gate would enforce the *weakest* levels while silently skipping the strongest. That is worse than no gate: it would manufacture confidence.

**Recommended if promoted:** a *reporting* instrument (which capabilities have Level-1 coverage; which authority-map rows are stale) rather than a blocking gate. Governance decides; this is a recommendation.

---

## 7 · Honest limits of this candidate

**What it does NOT solve**

- **It makes Level-1 evidence no easier to find.** ADRs sit in three directories under four naming schemes with no capability index. **This session's own archaeology missed two applicable ADRs** — the top of the hierarchy is its hardest step, which is a real weakness in the procedure as written, not a hypothetical one. (ADR ambiguity **AMB-1**.)
- **It costs more than reading a folder name.** Steps 3–7 are real work. Justified for business-rule placement; disproportionate for a typo fix. No threshold is proposed — that needs governance.
- **It does not resolve any existing duplicate authority.** It makes them visible and requires they be recorded.
- **The ordering is unproven.** It is a reasoned proposal with four documented counterexample classes, from one repository.

**Falsification targets** — for independent verification (Session 1), the useful attacks are:
1. Find a capability in this repository the procedure classifies **wrongly**.
2. Find a capability where it yields **UNRESOLVED** but authority is in fact obvious — i.e. the procedure is uselessly conservative.
3. Find a **fifth** caveat class the four do not cover.
4. Show that steps 3–7 disagree with each other more often than the resolution procedure can handle.
5. Show the ordering should differ — e.g. a case where executing code should outrank an ACCEPTED normative ADR.
6. **(Highest priority — raised by the Principal Architect, 2026-08-13.)** Independently re-derive the runtime authority for the **Election-Only eligibility decision** — do not accept this candidate's answer. The claim under test: `ElectionOnlyPolicy::decideForContext()` is the current runtime authority, reached via the `VoterEligibilityPolicy` port bound to `EloquentVoterEligibilityQueryService`. Establish by actual runtime path — not grep alone — **who calls** `ElectionOnlyPolicy`, `VoterEligibilityPolicy`, `EloquentVoterEligibilityQueryService`, and `VoterEligibilityService`, and **which** of those paths serves each of: voter verification · voter import · voter assignment · voting access · eligibility listing. Then classify each per §4 step 8 (CURRENT AUTHORITY · DUPLICATE · DORMANT · COMPATIBILITY). This capability is central to Election-Only, so a wrong answer here is the most expensive failure mode this candidate could have.

**Promotion preconditions (`ES-006.1`)** — all three, none yet met:
1. A second independent adopter (a different repository or product).
2. Operational evidence of necessity beyond the single misclassification that prompted it.
3. A governance ruling on the owning question in §2 — extends `ES-005`, or `ES-002`, or neither.

---

## 8 · Established · Inferred · Undecided

**ESTABLISHED**
- The misclassification occurred and was refuted from evidence.
- The three preconditions in §1.1 hold in this repository, each with citations.
- All four caveats in §3.1 are grounded in specific named artifacts.
- No existing rule supplies this decision procedure (§2 canonical discovery).
- The six §5 outcomes follow from the procedure applied to real evidence.

**INFERRED**
- That the ranking's order is correct.
- That folder-name inference *caused* the original error (consistent with the artifact; the reasoning was not observed).
- That `ES-005` is the right owning standard if promoted.
- That a reporting instrument beats a blocking gate.

**UNDECIDED**
- Whether this is promoted at all, and to which home.
- The effort threshold above which the full procedure is required.
- Everything in the ADR's §6 and AMB-1…AMB-7.

---

**Traceability:** `ADR_20260813_1722_Architecture_Authority_And_Code_Placement` (PROPOSED) · `2026-08-13-election-context-architecture-archaeology.md` · `ADR_20260807_1500` (ACCEPTED) · `ADR_20260806_1620` (PROPOSED, §6 "one authority per concern" at n=1) · `ES-001.1` · `ES-002.1/.2` · `ES-005.1–.4` · `ES-006.1` · `ES-006.4` · DDD Tactical Governance Principles (`ASP` · `ADP` · `DMT` · `RMSP` · Methodological Fitness Rule) · Operating Loop Phases 1–3 · `R-34` · `2026-08-05-manual-code-editing-observation.md` (same maturity tier and precedent format)
