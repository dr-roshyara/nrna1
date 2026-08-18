# `EM-DOM-001` — critical-path note: **Act-B GREEN ≠ GREEN-5**, and the one act that is actually load-bearing

**By:** the governance recording session · 2026-08-18 · **Read-only.** No decision, no authorization, no design.
**Purpose:** record a distinction that has been implicit in the gate findings and is the main reason the work *looks* wholly blocked when it is not.

## 1 · Two different GREENs have been sharing one name

| | **Act-B GREEN** | **GREEN-5** |
|---|---|---|
| **What turns green** | the `H-1` **structural** RED — *"retrieval is absent"* — goes green **when the domain contract exists** | the `ADR-1` §6(c) **Application normalization** slice over UC-1/UC-2/UC-3 |
| **Needs `BND-3`?** | 🟢 **NO** *(gate finding `G-2`)* | not directly |
| **Needs `BND-1`?** | 🟢 **NO** | 🔴 **YES — and it is deferred** |
| **Needs act C (persistence)?** | 🟢 **NO** — `H-1` fails by **absence of the contract**, not by unreachability | 🔴 yes, eventually |
| **Needs act D (Application)?** | 🟢 **NO** | 🔴 **that IS act D** |
| **Status** | ⏸️ **reachable — two gates + one authorization away** | 🔴 **blocked indefinitely by deferred `BND-1`** |

> ## **`BND-1` blocks `GREEN-5`. It does not block Act-B GREEN. `BND-3` blocks neither.**

⚠️ **Recorded because conflating the two makes the whole slice appear stalled behind two deferrals, when only the broader one is.** *(The components were already in the post-decision gate — `G-2`, `G-3`, `G-6` — but never stated as one distinction.)*

## 2 · `BND-3` does not block Act B — as a standing finding, not a hope

**All three `BND-3` candidates** *(distinct aggregate · part of a lifecycle aggregate · projection over recorded facts)* **share the same retrieval key (`ElectionId`) and the same already-frozen return type (`ElectionOperationalStatus`).** A contract shaped `ElectionId → ElectionOperationalStatus` therefore **asserts none of them.** It does not say *"I am a repository for aggregate X"*, *"I persist aggregate Y"*, or *"`ElectionOperationalStatus` belongs to Z."*

⇒ **`D4`'s deferral is compatible with Act B proceeding.** ⚠️ **The one live hazard is `G-2a`: naming and placement can assert a boundary even when the signature does not.** **That, and only that, is what Architecture must rule on.**

## 3 · What actually stands between here and Act-B GREEN

| # | Step | Whose act | Where it came from |
|---|---|---|---|
| **1** | **Architecture** confirms `RecordedOperationalStatus` + `OperatingCore/Port/` as legitimate and **boundary-neutral** — ⛔ **deciding nothing about `BND-3`, persistence, Application wiring or lifecycle ownership** | an Architecture lane *(undesignated)* | the **PO's own review** *(`G-2a` is a real hazard)*; the design lane also asks for it in its §8 |
| **2** | **Independent verification** of the Phase-2A map against the frozen core and the governing decisions — ⛔ **verify, never redesign** | an independent verifier *(undesignated)* | **`EP-02` / `R-34`** *(a repo standing rule: engineering never accepts its own work)* + the design lane's own §0.1 prior-position disclosure |
| **3** | 🔴 **The `EM-DOM-001` authorization is still ANALYSIS-ONLY** and must be lifted | ⭐ **PO/ARB** | its own words: ***"The Domain lane may begin only with domain analysis/design and RED-test preparation."*** |
| **4** | Commit the `H-1` structural RED, then implement the contract, then Act-B GREEN | a fresh Domain lane, **STARTed** | Obligation 4 + `START` is a separate act |

> ## ⭐ **Step 3 is the one nobody has named.** **`D1` authorized Act B's SCOPE. It did not lift the analysis-only limitation.** **So even with both gates positive, no lane may implement the contract until the PO/ARB lifts that limitation — a one-line act.**

⚠️ **Without step 3, steps 1 and 2 can both succeed and the work still will not move.** *(Recorded so this does not become a silent stall.)*

## 4 · On the process critique — stated fairly

**The assessment that the workflow, not the architecture, is now the constraint is accepted as substantially correct.** For the record, so the PO can decide what to collapse:

- **Gate 1 is the PO's own requirement**, not this session's addition. **It can be collapsed by the PO** — the cost is that `G-2a` goes unruled and the contract's *placement* may assert the boundary `D4` deferred.
- **Gate 2 is a repository standing rule** (`EP-02`/`R-34`). **This session cannot waive it**; the PO can, and the design lane's §0.1 disclosure is the reason it was flagged as non-optional.
- **Nothing else remains.** ⛔ **No further Domain design, no further analysis, and no additional gate is required or recommended by this session.**

**The applicable principle, adopted as stated:** ***resolve only the domain question necessary for the current bounded change; do not resolve future boundaries merely because they might eventually matter.*** **For Act B the necessary question is exactly one:** *"is `RecordedOperationalStatus` an acceptable boundary-neutral Domain contract, and is `OperatingCore/Port/` a neutral place for it?"*

**Traceability:** post-decision Rule-8 gate `G-2`/`G-2a`/`G-3`/`G-6` · D1–D4 *(verbatim)* · `EM-DOM-001` authorization *(analysis-only wording, line 177/267)* · ADR-1 §6(c) · Phase-2A design map §§8/§G/§H-1 · `EP-02` · `R-34` · `1f4b4c5f`.
