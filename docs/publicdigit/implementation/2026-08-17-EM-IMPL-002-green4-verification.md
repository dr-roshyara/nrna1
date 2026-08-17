# `EM-IMPL-002` GREEN-4 — Governance verification record (UC-3)

**Date:** 2026-08-17 · **Commit 4:** `4651a3e7` · **Verified by Governance's own runs and scans.**

## 1 · Observed — the gate held

| Check | Result |
|---|---|
| Footprint | ✅ one modified file; no domain/test/abstraction change |
| Application suite | **20/32 → 15/37** — the **5** that moved are UC-3's four + its refusal row |
| Guards · frozen · domain | ✅ **16/16** · **42 passed / 2434** · **byte-identical** |
| Still throwing | ✅ six bodies (2 handlers + 4 queries) |
| Arithmetic / time / policy port | ✅ **none** — and **`ServicePolicySnapshot` is absent from the constructor entirely**, so no allowance, duration or binding *could* be created (I-13 preserved structurally, not by discipline) |
| W-8 (`Unachievable → OPEN`) | ✅ **never executed as an act** — the fill changes recorded facts and `intervalState()` re-derives; the test asks the aggregate afterwards with `positions()` empty: **ability restored, nothing deemed decided** |

## 2 · ⚠️ THREE ITEMS WERE RECOMMENDED BEFORE GREEN-4 AND NONE WAS RESOLVED

Recorded as a fact of sequence, not a complaint: GREEN-4 proceeded while **(i)** the denominator-lookup divergence awaited a ruling, **(ii)** the unpinned already-begun guard awaited a RED pin, and **(iii)** the unknown-aggregate taxonomy awaited closure — all three of which Governance *and* the lane had recommended settling first. **Consequence: item (iii) has now produced a concrete defect (§3), and item (ii) remains unprotected across two increments.**

## 3 · 🔴 GOVERNANCE'S OWN FINDING — condition 3 produced a robustness REGRESSION

**The guard styles now diverge three ways:**

| Handler | Absent-aggregate handling |
|---|---|
| UC-1 | inline `?? throw new InvalidArgumentException(...)` (2 sites) |
| UC-2 | `?? throw $this->unresolvedReference(...)` (named method, 3 sites) |
| **UC-3** | 🔴 **NOTHING — `$decision->requiredVotes()` and `$committee->unableToFunction()` are called on possibly-null values** |

**Why this happened, stated fairly:** condition 3 said *"do not propagate UC-1's exception choice as a pattern"* — intended to stop a **pattern choice** hardening before the taxonomy closed. The lane read it as *"use no such handling at all"*, removed its placeholder (correctly refusing to throw `SeatNotVacant`, which would have asserted a false meaning), and left **no** handling. **That reading is defensible; its consequence is that UC-3 is less safe than its siblings — an absent aggregate faults as a raw PHP error rather than any governed outcome, and the lane says so itself.**

**⚠️ And no test covers absent-aggregate paths in ANY handler** — verified by search. So this is unprotected in all three.

> **Recommendation: close the taxonomy NOW, before GREEN-5, and bring all three handlers to one shape in the same slice.** Each further use case adds sites, and the divergence is already three-way after three increments.

## 4 · The lane's second STOP — `Q-RESTORE`, now architecturally load-bearing

**P-7 (`ResumptionTarget`) takes a `HaltedAtGate`, and no port in the authorized six-port universe can supply one** — the protocol is append-only with no read side, AG-3 carries no gate, AG-2 records no halt. **The lane therefore did not call P-7 and did not construct a `HaltedAtGate`** (constructing one would have invented the halt fact — correct restraint). `ElectionRestored` instead names the election's *established* acceptance decision.

**Governance's assessment:** ✅ exact in Model A today (one established decision) · 🔴 **it becomes wrong the day two designations are established at once, and the lane authored no tie-break and no fallback — deliberately.** **This is the same shape as A-9: a domain-side fact the frozen core does not model.** Candidates the lane names, none chosen: a domain-side halt record (future authorized **domain** slice) · a protocol read port (**new port — needs authorization**) · carrying the gate on the restoration period.

## 5 · On the PO's desk (four items, ordered by consequence)

1. 🔴 **The unknown-aggregate taxonomy** — now producing a real null-dereference path; close it and unify all three handlers.
2. 🔴 **`Q-RESTORE`** — the resumption-target proxy; correct today, silently wrong at two established gates.
3. **The unpinned already-begun guard** (GREEN-3) — still unprotected; one RED test closes it.
4. **The denominator-lookup divergence** (GREEN-3) — still unruled; UC-3 reused the same scan, so the choice is now in two handlers.

**Traceability.** Commits `1f4b4c5f` → `d2a0fe7c` → `8ea13835` → `cdc45f99` → `4651a3e7` · GREEN-3 verification record · the five registered conditions · the ten gate conditions.
