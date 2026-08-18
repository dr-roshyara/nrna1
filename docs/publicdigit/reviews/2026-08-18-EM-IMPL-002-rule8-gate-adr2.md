# Rule-8 Authorization / Dependency Gate — ADR-2 (Recovery Origin Provenance Ownership)

**Date:** 2026-08-18 · **Lane:** Session 2 — Governance · **Trigger:** the PO/ARB ADR-2 ruling of 2026-08-18, which states verbatim: *"This ADR signature is not blanket implementation authorization."*
**Domain core state:** `app/Contexts/Election/Domain/OperatingCore` — **byte-identical to the RED baseline `1f4b4c5f`** (`git diff --stat` empty). Frozen, as the ruling requires.

## Verdict

> # 🔴 BLOCKED — a separately authorized DOMAIN slice is required before any application normalization.

The block follows from the ruling's own §(e)/§(h), not from a Governance preference.

## Evidence

| # | Question the ruling forces | Finding in the frozen core |
|---|---|---|
| **Q1** | Does the domain represent **why a `RecoveryProcess` exists** (ruling (a)(i), (c) recovery-origin case)? | 🔴 **No.** `RecoveryProcess` carries `ElectionId`, `PeriodKind`, `PolicyBinding`, intervals, `active` — **no causal origin**. `RecoveryPeriodStarted` carries the same four values — **no origin**. |
| **Q2** | Does any domain type express **why a Restoration is permitted**, independently of recovery (ruling (a)(ii))? | 🔴 **No.** No `RestorationOrigin`, no equivalent concept, no named alternative anywhere in the 56 domain files. |
| **Q3** | Can `ElectionRestored` represent the **`w8` no-`RecoveryProcess` path** (ruling (h))? | 🔴 **No.** `public GateDesignation $returnsToGate` is **non-nullable** and the event carries no origin. **This is exactly the incompatibility (h) names**, and (h) rules it is *"a DOMAIN-MODEL GAP requiring a separately authorized domain slice."* |
| **Q4** | Does the model distinguish the **two causal paths** at all (ruling (a), (c))? | 🔴 **No.** Nothing expresses *unachievable-condition-resolved-without-recovery*. |
| **Q5** | Who constructs the causal claim **today**? | 🔴 **The Application layer, at exactly one site.** `new ElectionRestored(...)` appears **once in the whole repository** — `FillCommitteeSeatHandler.php:156` — fed by `$decision->gate()` from line 140. |

## The finding that matters most

**UC-3 obtains the restoration's causal claim by taking the gate of whichever `AcceptanceGateDecision` its unguarded scan happened to find** (`establishedAcceptanceDecision()`, line 206 → line 106 → line 140 → line 156).

That single lookup is doing **two different jobs at once**:
- it is the **ADR-1 absence question** (is a decision *required but absent*, or *not yet reached*?), and
- it is the **ADR-2 provenance source** (which gate does restoration return to, and why is restoration legitimate?).

⚠️ **Therefore the two ADR dependencies are NOT independent — they converge on the same lines of UC-3.** The registered observation (*"weigh the ADR-1 and ADR-2 domain dependencies together before either slice is authorized"*) is now **substantiated, not merely prudent**: normalizing either one alone would rewrite the same code twice, and the first rewrite would have to invent an answer to the other ADR's question in order to compile.

## Consolidated domain gap register (both ADRs)

| Gap | Source | Where it lands |
|---|---|---|
| G-a | ADR-1 | No contract exposes **Committee required-existence** |
| G-b | ADR-1 | No contract **discriminates the two `AcceptanceDecision` meanings** (not-yet-reached vs required-but-absent) |
| G-c | ADR-1 | **No policy P-1…P-7** accepts or returns an absence concept |
| G-d | ADR-2 | No **recovery-origin** invariant on AG-3 / `RecoveryPeriodStarted` |
| G-e | ADR-2 | No **restoration-permission** invariant; `ElectionRestored.$returnsToGate` non-nullable blocks the `w8` path |

**Five gaps · one frozen aggregate set (AG-1, AG-2, AG-3 + `Event/`) · one convergent call site.**

## What Governance has NOT done

⛔ No domain file touched · no RED written · no port proposed · no `RestorationOrigin` authored · no UC-3 change · the existing RED pin left reporting its two sites · **no domain slice requested on Governance's own authority.**

**Per the ruling: requesting and granting that authorization is the PO/ARB's act, not Governance's.** Governance reports the block and the evidence; it does not clear its own gate.

**Traceability:** ADR-2 §6 (a)–(h) · ADR-1 §6 constraint ⑥ · Rule-8 gate for ADR-1 (`7514f145`) · `1f4b4c5f` baseline freeze · `4651a3e7` UC-3 · A-9 precedent · `EM-GOV-059`(c) · `EM-GOV-062` · W-8.
