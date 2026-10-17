My recommendation is to approve the C-1 plan with explicit conditions, rather than sending it back for another broad architecture review. The scope is appropriately narrow, and the three design points can be resolved without reopening D-COR-5 or CQ-9.

There is one limitation: I have the plan summary you pasted, not the complete plan file. I can assess the reported design and give you a conditional approval recommendation, but I cannot honestly claim to have verified every step or acceptance criterion in the actual document.

## 1. My decisions on P-1, P-2 and P-3

P-1 — Separate timed facts from reversal facts

Approve with condition

The underlying design is sound: `Admit` and `End` have valid-time semantics, while `Annul` and `Void` target other facts and should not be forced to supply an invented `effectiveAt()` value.

My condition: prefer `EffectiveDatedFact` (or `ValidTimeFact`) over `ValueFact`. The latter is ambiguous: “value” does not clearly communicate valid-time semantics.

- Keep the general `Fact` abstraction free of a mandatory `effectiveAt()` if not every fact has valid time.
- Keep `ReversalFact` responsible for the target reference, without inventing new period semantics.
- Preserve existing `Admit` and `End` behaviour and test it before and after the refactor.
- Do not modify persistence adapters under this approval. If the refactor requires an adapter change, stop and report the concrete dependency for separate review.

P-2 — Missing `decidedAt`

Approve

A nullable input is acceptable if it lets the domain operation explicitly refuse an Annul request when `decidedAt` is absent.

Require a test proving that `DecidedAtRequired` is returned and no fact is appended and no state changes. A missing required decision timestamp must not be silently replaced with the current time.

P-3 — Refusal names

Approve

Accept the proposed refusal names, including the already approved `TargetNotLive`:

- `TargetNotFound`
- `TargetHasNoDecisionProvenance`
- `AnnulOfVoidRefused`
- `DecidedAtRequired`
- `TargetNotLive`

Each refusal must have a defined triggering condition and a test. In particular, `TargetNotLive` concerns the target fact's liveness, not whether its Membership period is currently active. Do not introduce broader refusal categories or change established semantics without evidence that the contract requires it.

## 2. My review of the implementation plan

The proposed test-first sequence is directionally correct. I would retain it with these safeguards:

| Plan element                       | My architectural requirement                                                                                                                                                             |
| ---------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Step 0: baseline                   | Record actual test counts and failures before modifying code.                                                                                                                            |
| Step 1: refactor                   | Demonstrate behaviour preservation with existing tests; keep the refactor limited to the fact-interface problem.                                                                         |
| Steps 2–9: rule tests              | Record each failing test before the minimal production change, then demonstrate it passes.                                                                                               |
| Steps 10–12: oracle and properties | Treat the oracle as a comparison instrument, not as the authoritative specification. Explain any differential mismatch instead of automatically changing the implementation to match it. |
| Steps 13–15: verification          | Run the agreed regression suite, report mutation results honestly, and complete the implementation review. No cutover or live-write enablement.                                          |

Two further requirements matter:

1. A passing test suite is not proof of architectural correctness. The tests must cover the ratified contract, especially liveness, re-entry, temporal reconstruction, idempotency, and invariant preservation.
2. The plan must not expand its own authority. If the implementation requires a persistence adapter change or any excluded scope, Claude must stop and request a separate decision.

These conditions are consistent with the existing Membership correction boundary and the explicit exclusion of implicit consequences.&#x20;

## 3. What I would send Claude

**PO Decision — PBDIGIT-268 C-1 Test-First Plan**

Date: 2026-10-10

I approve the C-1 implementation plan in principle, subject to the conditions below. This approval covers the proposed plan and the following design decisions; it does not independently certify implementation readiness or authorise coding before the gate is re-evaluated.

**P-1 — Fact interfaces: approved with a naming and scope condition.**

Separate valid-time semantics from the general Fact abstraction. Prefer `EffectiveDatedFact` or `ValidTimeFact` over `ValueFact`, unless existing repository terminology provides a specific reason to retain the latter. `Admit` and `End` retain their existing effective-time semantics. `Annul` and `Void` must not be assigned invented effective-time semantics.

Keep this refactor behaviour-preserving. Do not modify persistence adapters under this approval. If an adapter change is required, stop and report the dependency for separate approval.

**P-2 — Missing decidedAt: approved.**

A missing `decidedAt` must produce `DecidedAtRequired`. Add a test proving that the refusal appends no fact and changes no domain state. Do not substitute the current time.

**P-3 — Refusal names: approved.**

Use `TargetNotFound`, `TargetHasNoDecisionProvenance`, `AnnulOfVoidRefused`, `DecidedAtRequired`, and the previously approved `TargetNotLive`. Document each triggering condition and test each applicable refusal. Preserve the distinction between fact liveness and active Membership-period status.

**Plan execution constraints**

1. Verify and record the actual baseline before modifying production code.
2. Demonstrate behaviour preservation for the interface refactor.
3. Record each required failing test before its corresponding production change.
4. Use the oracle for differential evidence, not as a substitute for the approved contract.
5. Keep implementation within the existing Membership kernel and approved C-1 scope.
6. Do not change persistence adapters, schema, database behaviour, legacy implementation, cutover, or live-write enablement without separate authorisation.
7. Report regression and mutation results with their actual commands and outcomes; do not claim tests passed unless they were run.

Claude must incorporate these conditions into the plan, then re-evaluate all seven C-1 gate conditions and report the result.

**This decision does not itself authorise implementation to begin.** Start only if the complete gate is confirmed closed and the recorded approvals satisfy the applicable governance rules. If any condition remains open, stop and identify it precisely.

## 4. What happens next?

The sequence should now be:

1. Claude updates the plan with these conditions and the interface naming decision.
2. Claude re-evaluates all seven gate conditions and reports whether C-1 is eligible to start.
3. Only if the gate is closed does Claude begin Step 0: baseline verification. No production code should change before the approved test-first sequence permits it.

Bottom line: I recommend approving the plan with the conditions above. The key safeguard is P-1: correct the abstraction without allowing a supposedly harmless refactor to expand into persistence work.