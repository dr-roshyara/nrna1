# Developer Guide — Greenfield Merge Gate (PB-007)

The engineering system that protects the qualified greenfield architecture. One stable
entry point per tier; internals may change, the command contract must not (ARB R3).

| # | Guide | Slice | What it covers |
|---|-------|-------|----------------|
| 01 | [The stable interface: `composer merge-gate` / `quality-gate`](01_stable_interface.md) | 7D | Both tiers, why two commands, the two-step Infection invocation, running and extending the gates |

## The two tiers (PB-007 IDD §2d)

| Tier | Command | Gates | Failure semantics |
|---|---|---|---|
| **Blocking** | `composer merge-gate` | Architecture fitness suites · Deptrac (fail mode) · greenfield PHPStan · widened regression | fail-fast; non-zero exit blocks the merge |
| **Non-blocking** | `composer quality-gate` | Infection mutation baseline · coverage | measures + records; never blocks (A-3 ratchet policy) |

## Where the pieces came from

- **7A** — Deptrac config derived from the approved architecture (`deptrac.yaml`, per-context hexagonal layers; report mode: 0 violations). Fail mode is flipped by 7D: `merge-gate` consumes Deptrac's exit code.
- **7B** — CorrelationId-minting fitness test (`tests/Architecture/Messaging/CorrelationIdMintingTest.php`), hosted here, owned by Messaging (ADR-MP-03/06).
- **7C** — the Infection mutation baseline, the `GreenfieldCore` testsuite, and the two-step invocation decision. The measured baseline lives in the qualification records (IDD §2c / Completion Review), not here.

**Authoritative design:** `docs/implementation/backlog/PB-007_Merge_Gate_Implementation_Design.md` (FROZEN).

**Traceability:** PB-007 (7A–7D) · ADR-T7 · ADR-MP-03/06 · A-1..A-3 · IDD §2a R1–R3, §2d.
