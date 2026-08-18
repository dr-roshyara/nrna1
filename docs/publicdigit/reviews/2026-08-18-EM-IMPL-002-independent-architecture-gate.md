# `EM-IMPL-002` — Independent Architecture Gate: RELAYED RECORD

**Type:** Architecture-gate evidence record · **Date:** 2026-08-18 · **Created to satisfy R-2** *(the gate was not a durable repository artifact; an ADR citing a "second independent gate" that no auditor can find violates the provenance discipline this programme exists to enforce).*

> ## ⚠️ PROVENANCE — READ FIRST
> **This is a RELAY, not the reviewer's own text.** The independent review reached this repository **only through the PO's message**; the reviewer's report was never written to the repository. Governance therefore records **what it received, marked as received**, and does **not** present it as the primary document. **Anything below that Governance could verify against the repository is marked VERIFIED; everything else is RELAYED.**
> ⛔ **This record is EVIDENCE of the architecture gate. It is NOT an ADR · NOT a PO/ARB decision · NOT implementation authorization.**

## 1 · Verdict, preserved exactly

> ## **READY FOR PO/ARB DECISION**

**With the accompanying instruction: architecture refinement STOPS; the next actor is Governance for bounded record corrections, then PO/ARB for the decisions.**

## 2 · Scope and evidence basis (relayed)

A cross-ADR architecture gate over `ADR_20260817_2145` (absence semantics) and `ADR_20260817_2300` (recovery-origin provenance), their evidence base (the `Q-RESTORE` investigation), the three committed handlers, and the frozen domain core.

## 3 · The `w8` factual verification — ✅ **VERIFIED by Governance**

**The gate's sharpest factual correction:** `w8` creates a **`CommitteeRestoration`** recovery process but **no `HaltedElectionRecovery`** period. **Therefore `w8` proves *no prior HALT*, not *no RecoveryProcess*.**
✅ **Governance confirms this distinction is now carried in ADR-2 §5a/§5b** and that it materially changes the model: the earlier implicit chain `Restoration → RecoveryProcess → Halt` asserted a causality the evidence does not establish.

## 4 · ADR-1 review (relayed) · 5 · ADR-2 review (relayed)

**Both core models assessed SOUND.** The gate found no defect in the analysis; it found that **two true conclusions were never connected to each other** — the finding that produced the H-2 cross-ADR condition.
**Recorded as the correct level of abstraction:** the unresolved question is *"what domain fact establishes the causal relationship between Restoration and a prior Halt, if such a relationship exists?"* — **not** *"where do we put `originatingGate`?"*
✅ **VERIFIED restraint:** neither ADR prescribes a nullable `originatingGate`, a sentinel, an `UNKNOWN` origin, or a replacement type. *(Checked in both files.)*

## 6 · DDD critical check · 7 · Cross-ADR consistency check (relayed, partially verified)

**Frozen conclusions the gate recorded as sound and not to be reopened:** protocol ≠ causal authority · application ≠ domain classifier · provenance ≠ data retrieval · legitimate absence needs explicit meaning · bounded contexts own concepts · reconstruction from protocol history forbidden.
✅ **VERIFIED:** the cross-ADR consistency condition is present in **both** ADRs and the ADRs remain separate and unmerged.

## 8 · Governance check — the two requirements

**R-1 — commit before signature:** the pre-signature corrections must exist as an immutable commit before PO/ARB acts. ✅ **satisfied by the commit carrying this record.**
**R-2 — missing review artifact:** ✅ **this record exists because of it** — and its own provenance caveat (above) is part of satisfying it honestly.

## 9 · The bounded pre-signature corrections — ALL VERIFIED REAL, then applied

| # | Defect | Governance verification | Applied |
|---|---|---|---|
| **P-1** | ADR-2 §5b's third causal question sat OUTSIDE the table as a stray fragment | ✅ confirmed | folded into the table, meaning preserved |
| **P-2** | ADR-1 §1.1 gave UC-2's guard count as **3** | ✅ confirmed wrong **by the table's own metric**: guard SITES are lines 91 and 209 = **2**; the third occurrence is the method **declaration** | corrected to **2**; conclusion unchanged |
| **P-3** | duplicate `5b` headings; §6 items ordered `a b c d e g h f` | ✅ confirmed *(the duplicate was Governance's own earlier insertion)* | the subordinate recommendation renumbered **§5a.1**; §6 reordered to **(a)–(h)**; no question added or removed |
| **P-4** | garbled clause *"the halt whose gate provenance is about"* | ✅ confirmed | rewritten grammatically, same meaning: it is this halt's gate that provenance would identify, **if the ruling requires provenance at all** |
| **§8.2** | Option C's audit argument overstated | ✅ **confirmed against code**: `RefusalRecord(string $requestedAct, string $reason, RecordedInstant $refusedAt)` — **no `ElectionId`** | ADR-1 §4d records the limitation, expressly **not** a rejection of C, **not** a selection, **not** a domain authorization |

## 10 · What this record does NOT do

⛔ It selects no option · fills no decision block · authorizes no implementation or domain change · adds no recommendation of its own · and converts no relayed recommendation into a decision. **Both ADR §6 blocks remain blank.**

**Traceability.** `ADR_20260817_2145` · `ADR_20260817_2300` · the `Q-RESTORE` investigation (unchanged) · commits `1f4b4c5f` → `d2a0fe7c` → `8ea13835` → `cdc45f99` → `4651a3e7` · the operating rules (Rules 1–9).
