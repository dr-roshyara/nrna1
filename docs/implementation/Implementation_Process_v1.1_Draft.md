# Implementation Process — v1.1 (DRAFT)

**Status:** DRAFT · **2026-07-07** · additive successor to the FROZEN `Implementation_Process_v1.0.md`. v1.0 remains authoritative until this draft is ratified; this draft is the **single home** for post-v1.0 process additions (so rules are not duplicated across the Decision Log / AKB).

**Rule:** engineering rules and process gates are **process**, and live **once** — here. Other documents *reference* them; they do not restate them.

---

## Engineering Rules — additions to §ER (v1.0 defines ER-01…ER-04)

**ER-05 — Architecture Convergence.** Every architectural iteration must **reduce or maintain** long-term architectural complexity.
- ✔ Generalize duplicate concepts · merge overlapping documents · retire obsolete/transition artifacts · replace implementation-specific concepts with domain abstractions · strengthen traceability.
- ✘ Create parallel frameworks · introduce synonyms · duplicate governance · add a *permanent* document without reducing future complexity.
- **Test for a new permanent governance artifact:** it must (1) eliminate an existing artifact, (2) simplify the architecture, (3) generalize knowledge already proven by multiple examples, or (4) directly support implementation. Otherwise it is transitional and is retired once its knowledge is folded in.
- *(Origin: 2026-07-07 governance maturity — architecture crossed from under-design risk to over-design risk; adopted via Decision Log D-13.)*

**ER-06 — Ubiquitous Language before Published Language.** Whenever an integration/published contract would change, **first** verify the **ubiquitous language** (is the change hiding a Primitive-Obsession concept? what is the business/constitutional term?). Only after the domain concept and its term are settled may the **published language** (events, integration contracts) evolve — and the contract change is then a *consequence* of the language, not a convenience. Prefer the **constitutional/governance term** over an invented software concept.
- *(Origin: 2026-07-07/08 — PB-004 DD-4: `TargetRef:string` (Primitive Obsession) → concept `ContestedOutcome` (certified BDR v1.1 term) + `TargetReference` VO, discovered by modeling meaning before reference; only then `DeterminationIssued v2`.)*

**ER-07 — Test behavior, not transport.** Wherever possible, RED tests verify **domain behavior** (aggregate decisions, emitted domain events, invariants) rather than serialization/infrastructure mechanics. Transport/wire format (payload shape, `schema_version`, JSON) is tested **separately** by the adapter/hydrator that owns the wire contract. A behavior test must not break when only the transport encoding changes.
- *(Origin: 2026-07-08 — PB-004 step 2: the RED that drove the design asserted the aggregate emitting `ContestedOutcomeRef` on its domain event, not the JSON payload; the wire shape was covered separately by the hydrator round-trip.)*

**ER-08 — Reviews Record, Implementations Repair.** Completion Reviews (EP-02) document implementation **conformance** and discovered **deviations**; they do **not** introduce behavioral changes. Behavioral corrections belong **exclusively** to a subsequent approved implementation slice, made under RED→GREEN. This keeps a review from silently becoming a hidden implementation session.
- *(Origin: 2026-07-08 — PB-004 step 3 EP-02: the timestamp (`appliedAt`) deviation was recorded during the review and fixed only in the next approved slice (4A.1), not during the review itself.)*

---

## Execution Rules — Engineering Process (EP) — new section

*EP rules govern **how a working session operates** (human or AI assistant, any provider — Claude, Copilot, Cursor, Gemini, Codex behave identically); ER rules govern **what the work must satisfy**. Distinct concerns, one home. Assistant configuration files (e.g. `.claude/CLAUDE.md`) may only **reference** EP rules, never restate them.*

**EP-01 — Plan First.** For every non-trivial engineering task, the **Planning Stage** is mandatory (the EP namespace already places it in the Engineering Process — no "Engineering-" prefix stutter). The implementer (human developer or AI assistant — any provider) shall:
1. **Understand** the request.
2. **Analyze** the relevant architecture (frozen artifacts win over memory).
3. **Produce a plan** (for PB tickets, the plan *is* the IDD → Architecture Review path of steps 2–3; for smaller governed work, a written plan in `.claude/plans/`).
4. Wait for explicit human approval. **APPROVAL APPLIES TO THE PLAN, NOT MERELY TO THE TASK REQUEST** — "implement X" authorizes *planning* X; only an approved plan authorizes *implementing* X.
5. **Implement only the approved plan.**
6. **Re-plan on invalidation:** if implementation reveals the approved plan is no longer valid, **STOP**, explain why, present the revised plan, and wait for approval before continuing — never silently change direction mid-implementation.

**EP-02 — Completion Review.** Implementation does not end at Verification. Verification answers *"does it work?"* (executable gates, PASS/FAIL); **Completion Review answers "did we implement the approved plan?"** — a human comparison of the delivered work against the approved plan/IDD before the work is called done. *(Names existing practice: the per-ticket Implementation Review and the step-14 Architecture Review Checklist already perform this; EP-02 makes it explicit for all planned work, not only PB tickets.)*

**EP-03 — Engineering Readiness Review (ERR).** EP-01 steps 1–2 ("understand, analyze"), made explicit and provider-independent: before any non-trivial implementation, the implementer performs a readiness review by answering a standard set of engineering questions — **deriving as many answers as possible from authoritative project knowledge** (ADRs, BDRs, the constitution and architecture artifacts, engineering standards, developer guides, source code, tests, and repository history) **and asking the human only what cannot be determined confidently.** The review's output *is* the EP-01 plan submitted for approval. This is the *Engineering Conversation*: think first; code never starts the conversation.

**Stopping condition — the ERR ends only when:** ✓ sufficient understanding exists · ✓ every remaining unknown has been explicitly identified (and routed to the human) · ✓ a plan can be produced. **Otherwise implementation shall not begin.**

| Domain | The questions (derive first; ask only gaps) |
|---|---|
| Business | What problem? Who benefits? Which business/constitutional invariant applies — what can never be violated? |
| DDD | Which bounded context? Existing or new aggregate? Ubiquitous-language changes? Domain events, policies, invariants? |
| Architecture | Which style and constraints govern here? Which ADR governs this change? |
| Process | Does an approved plan exist? If not: STOP — produce one (EP-01). |
| TDD | Existing or new behavior? Where is the failing test? Can RED be written first — and if not, why? |
| Design | Which existing pattern applies (strategy · factory · state · specification · repository · application/domain service)? Reusing or inventing? |
| Impact | What changes — aggregate, API, database, UI, contract, developer guide, ADR? |
| Verification | How will correctness be known — tests, architecture tests, PHPStan gate, mutation, fitness functions, human review? |
| Completion | DoD boxes, developer guide, evidence, commit shape, review path? |

**Depth scales with the task** (same review, different depth): a typo answers "trivial/skip" in seconds; a PB ticket answers in full — **for PB tickets the 17-section IDD *is* the ERR's complete form** (this rule generalizes the IDD's discipline to all non-trivial work, it does not duplicate it). The derived answers are stated in the plan (e.g. "Derived: context = Election, aggregate = existing, ADR = ADR-PL-01 · Cannot determine: extend aggregate vs. new policy — please decide"), so the human sees what was derived and decides only what remains.

*(Origin: 2026-07-08 ARB direction — "the questions belong to the engineering process, not to Claude". Deferred per freeze: modeling `EngineeringConversation` as a platform aggregate — retrospective candidate, evidence first.)*

**"Non-trivial" — decision table** (so planning does not degenerate into ceremony):

| Task | Planning required? |
|---|---|
| Architecture-touching change | ✅ Yes |
| New feature | ✅ Yes |
| Refactoring | ✅ Yes |
| Database schema / migration | ✅ Yes |
| ADR / process / governance change | ✅ Yes |
| API / contract change | ✅ Yes |
| Domain model change | ✅ Yes |
| Bug fix affecting behavior | ✅ Yes |
| Typo / formatting / comments / documentation spelling / import cleanup | ❌ Usually no (judgment; ER rules and gates still apply) |
| Questions, explanations, translations, text review — analysis without repository mutation | ❌ No |

*(Origin: 2026-07-08, ARB/mentor review of the Engineering Platform construction — codifies the already-practiced loop: plan → review → approve → implement → verify. Step 6 is the load-bearing clause. Note for ratification review: decide whether EP remains a separate namespace or folds into ER; the distinction proposed here is execution-mode vs. work-product rules.)*

**EP-01-Light — lightweight planning form (clarification, ARB 2026-07-10).** Under a frozen baseline most changes are small; the approval gate stays, the ceremony shrinks. For any repository mutation below IDD weight, the implementer presents this form (30–60 seconds) and **waits for approval** before executing:

```text
EP-01 Implementation Review
Objective        — one sentence
Classification   — □ Record only  □ Documentation  □ Runtime  □ Product  □ Architecture
Risk             — Low / Medium / High
Files affected   — list
Expected evidence— what proves it worked
Not changing     — what stays deliberately untouched
Awaiting ARB approval.
```

Pure *Record-only* actions (classifying an observation into the retrospective inbox, appending a session-log entry, an ARB-dictated verbatim record) need no separate form — the classification statement itself is the plan; everything else uses the form as the minimum. **Role separation preserved:** the AI is the engineer (plans, implements, reports) · an independent reviewer may assess the plan · the human ARB approves. *(Origin: 2026-07-10 ARB — "restore the explicit approval gate before any change"; the freeze changed most work into record/defer/reject, which had quietly thinned the gate.)*

**EP-02 — Implementation Report format (clarification, ARB 2026-07-10).** Every implementation (and every EP-01-Light execution) ends with a concise report answering, in order: **(1) changes made · (2) changes deliberately NOT made · (3) verification/evidence · (4) git commit ref(s) · (5) next recommended action.** This is what makes each commit auditable without reading the diff; it is the standing output format, not an optional courtesy.

---

## Process gates — additions

**ARR Gate (Architecture Readiness / Reuse gate).** Every PB ticket that touches a **frozen Platform Capability** (e.g. the Messaging Platform) must explicitly answer, in its IDD, before any code:
1. Does this ticket **consume** the Platform Capability **unchanged**?
2. Does it require any **modification** to the Platform Capability?
3. Does it **violate** any Architecture Principle (incl. PGP-01…05)?
4. Does it require a **new ADR**?

**Routing:** if the answers are *consume-only · no modification · no violation · no ADR* → the ticket may proceed to RED. **Otherwise STOP and request ARB review** (a change to a frozen capability is an architecture decision, not an implementation detail). This prevents accidental drift into Shared.

---

## Pending fold-ins (queued for ratification into v1.1 body)
- Messaging Architecture Verification Matrix + the Platform Capability Readiness questions (from PB-003 C6A/C6B).
- ER-05 · ER-06 · ER-07 · ER-08 + ARR Gate (above).
- **EP-01 Plan First + EP-02 Completion Review + EP-03 Engineering Readiness Review (above) + the EP-vs-ER namespace decision.**
- ADR template alignment for the ADR-MP / PGP style (principles referenced by decisions).

*(On ratification, this draft is renumbered to `Implementation_Process_v1.1.md` and v1.0's frozen body is superseded additively — no rule content lost.)*
