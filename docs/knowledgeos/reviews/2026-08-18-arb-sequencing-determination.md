# Registration — ARB sequencing determination after the portfolio reconciliation

**Registered by:** Governance · 2026-08-18
**⚠ This records ARB DIRECTION and RECOMMENDATIONS. The four lane dispositions it recommends are NOT executed by this registration — each needs an explicit act (ES-001.2: a recommendation is never an authorization).**

## 1 · The reconciliation is adopted as portfolio truth

> **"The reconciliation itself is high quality and should now be treated as the current portfolio truth."**

Adopted. The 2026-08-18 reconciliation is the authoritative portfolio state.

## 2 · The sequencing determination

> **Python Stage-2 verification → portfolio quiet → ADR-AIP-04 capability discovery → role/capability consequences → Implementation Architecture → Implementation.**

**ADR-AIP-04 comes before implementation architecture**, and the ARB's architectural reason is registered because it is the load-bearing part:

> **BC-7 was accepted with the role-model question explicitly deferred to ADR-AIP-04. Beginning implementation architecture first "risks encoding an incomplete role model."**

**Governance concurs** — this matches the accepted BC-7 model's own open state (`OQ-10` open; role ownership deferred).

## 3 · The capability-first guard on ADR-AIP-04 — registered before the discovery is commissioned

**ADR-AIP-04 must NOT be framed as** *"let's add a Knowledge Engineer and a Communication Engineer."* That is premature and inverts the model.

**The governing question stays:**

> **What capabilities does a sustainable AI Engineering Platform require?**

**Evaluated in this order — capability first, people and agents last:**

```
capability → ownership → bounded context / stewardship → human role → agent → shared platform service
```

This preserves the capability-first principle established earlier in the programme. Registered **now**, before commissioning, so the discovery cannot drift into org design.

## 4 · Priority one, needing no governance act

**`KOS-CONTRACT-NEUTRALITY-001` · `S1-verification-python-stage2`** — the estate's only genuinely unfinished assurance activity. **The lane is already ACTIVE; no registration, grant or START is outstanding.** What it needs is a **fresh independent process** to perform it — the implementing process is disqualified twice over by its own `executionContext`.

## 5 · Four dispositions RECOMMENDED by the ARB — awaiting explicit acts

| # | Lane | ARB recommendation | Governance note |
|---|---|---|---|
| 1 | `KOS-ARCH-BASELINE-003` · `S4-architecture-bc7-refinement` | **CANCEL with explicit reason** — *"rather than leaving a dead HANDOFF forever"* | Produced no output; correctly not COMPLETEd. `CANCEL` is terminal in this engine — there is no un-cancel edge |
| 2 | `KOS-AI-ORCH-001-INC1` · `S1-verify` | **SUPERSEDED/CANCEL unless evidence says it must run** — *"do not infer"* supersession | The supersession by `KOS-OQ-001` has never been recorded; the ARB explicitly forbids inferring it |
| 3 | `KOS-ACTIVATION-REPORTING-001` | **RETIRE unless a real commissioned purpose exists** | Empty record — no sessions, grants or transitions. The engine has no "retire" transition; retirement would be a governance registration, not a workflow act |
| 4 | `KOS-ATTR-ARCH-001` · `S4-architecture-attr-stage2` | handoff + START, **or** explicit deferral | Its baseline gate is satisfied; the ARB's sequence implies deferral until after Python Stage-2, but does not say so |

**None performed.** Each is a one-line act.

## 6 · What is architecturally settled

> **"You have finished the BC-7 strategic and tactical architecture arc."**

**Do not reopen BC-7** absent new evidence or explicit change pressure — the ARB's DDD maturity point: *"once a bounded-context model has been accepted, subsequent work should be driven by new evidence or explicit change pressure, not by endless architectural polishing."*

**Traceability:** ARB review 2026-08-18 · reconciliation `7eceacc6` · BC-7 acceptance `48bc0dfc` · accepted strategic model `a265e1b7` · `ES-001.2` (recommendation ≠ authorization) · `OQ-10` (open) · ADR-AIP-03 consequence (b) role model deferred to ADR-AIP-04
