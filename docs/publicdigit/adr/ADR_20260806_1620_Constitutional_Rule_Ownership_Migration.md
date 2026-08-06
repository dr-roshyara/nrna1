# ADR — Migration from global election configuration to constitutional rule ownership

**Status: 🟡 PROPOSED — awaiting Product Owner / ARB acceptance. No code has been written, and none may be removed until this is accepted.**
**Date:** 2026-08-06 · **Origin:** `PBDIGIT-39` Defect B → `PBDIGIT-45` → the ownership discovery
**Evidence:** [`2026-08-06-who-owns-the-votes-per-ip-rule.md`](../reviews/2026-08-06-who-owns-the-votes-per-ip-rule.md) rev 1–3 — **this ADR asserts nothing the discovery did not establish**
**Author:** engineering (evidence and drafting only — `R-34`)

---

## 1 · Context

A defect report (*"an unset config value refuses every voter"*) was investigated as a comparison bug. **The discovery found something different: two independent authorities can deny a vote on the same concern.**

| Authority | Where the rule lives | Reaches a verdict via |
|---|---|---|
| **A · global configuration** | `MAX_USE_IP_ADDRESS` → `config/app.php:149` | `check_ip_address()` (`helpers.php`, catalogued as **H.3**) and an inline check in `VoteController` |
| **B · the Constitution** | `ElectionConstitutionSnapshot` → `elections.network_binding_strategy` + `.max_votes_per_ip`, hashed and versioned | `NetworkBindingPolicy` ("Article 1/4") → `LegitimacyOutcome` → the `D.0.3a` gate |

**Established facts:**

* **The constitutional layer never reads `MAX_USE_IP_ADDRESS`** — zero references in `app/Domain` or `app/Application`.
* **Authority B is richer**: it honours `'none'`, whitelisting, trust level, and the **election's own** limit. Authority A honours none of these.
* **Six readers of the global apply four different defaults** — `null`, `0`, `7`, `4`.
* **Authority A predates Authority B by four years** (2021-12 vs 2026-05), and the divergence instrumentation landed the same week as the snapshot.
* **Divergence between the two is measured and has never been read** (`SovereigntyDivergenceSummary`).

**Deliberately NOT established:** that the two paths are semantically equivalent, or that a feature flag was the intended retirement mechanism. **This ADR does not depend on either.**

## 2 · Decision (proposed)

> **A rule that can deny a vote belongs to the Election's constitutional snapshot. The orchestration layer supplies evidence and acts on the verdict; it does not hold, parameterise or re-decide the rule.**

**Corollaries:**

1. **One authority per concern.** Where two exist, one must be retired — not tuned into agreement.
2. **Vote-gating values are constitutional, not global.** If a value can change whether a vote is accepted, it belongs on the election, under the constitutional hash — **not in `.env`**.
3. **Controllers may compute evidence** (e.g. `votesFromThisIp`) **but may not decide** what the evidence means.
4. **Retirement is evidence-led.** A legacy path is retired when divergence data shows what retiring it changes — **not because it looks redundant.**

## 3 · Why this is not simply "delete the legacy check"

**Because the discovery does not prove the two paths agree**, and deleting on an assumption of agreement would silently change election outcomes. The honest sequence is:

```
read the divergence evidence
      ↓
establish whether the verdicts differ, and where
      ↓
retire Authority A  (a subtraction)  — or fix Authority B first if it is weaker
      ↓
migrate the six global readers to the constitutional value
```

**Step 1 is cheap and already built.** `SovereigntyDivergenceSummary` + `D02AggregateConstitutionalDivergence` exist for exactly this question and have never been run. **Any decision taken before reading them is a preference, not a decision.**

## 4 · Consequences if accepted

**Wanted:**

* One place answers *"may this person vote from this network?"*
* `'none'` means unlimited, per election — **which removes `PBDIGIT-45`'s central question rather than answering it.**
* Election rules become tamper-evident (already hashed) instead of environment-dependent.
* `PBDIGIT-39` Defect B dissolves: an unset global stops mattering because nothing gates on it.

**Unwelcome, and stated plainly:**

* **Behaviour will change for some elections.** Authority A is currently stricter in one respect (it ignores trust level and whitelisting, so it can deny where B would allow) and more permissive in another (`'none'` is meaningless to it). **Retirement is not behaviour-neutral, and pretending otherwise would be the same error as the fix this ADR replaces.**
* **Six call sites must be migrated**, three of which are not on the voting path (`CodeController`, `DemoCodeController`, `ElectionSettingsService`) and need their own decisions.
* **`elections.max_votes_per_ip = 6` becomes load-bearing.** It is currently a schema default nobody has ratified. **It must become a decision.**
* **The demo/real distinction remains** (`ADR_20260806_1520`) and is unaffected: demo does not enforce either authority.

## 5 · What this ADR explicitly does not decide

* **Whether Authority A is retired**, and when — that needs §3 step 1.
* **What `max_votes_per_ip` should be.**
* **Whether activation should validate network configuration** (`PBDIGIT-45` question 4 — the one question genuinely still open).
* **The three findings the discovery recorded** (`N-1` demo reads the wrong table · `N-2` four defaults · `N-3` `IpVelocityOverlay` counts election-wide and is blind while `PBDIGIT-42` stands). **Each is a defect in its own right and none is repaired by this ADR.**
* **Whether `ip_strict` and `whitelist_only` have evaluator branches** — **unchecked**, and it bears on whether the strategy vocabulary is real or aspirational.

## 6 · Generalisation — the principle beyond elections

> **Business policies belong to the domain authority that owns them, never to the orchestration layer.**

| Domain | Authority |
|---|---|
| Election rules | the election's Constitution |
| Membership rules | Membership policy |
| Organisation rules | Organisation policy |
| Financial rules | Financial policy |

**Recorded as an observation at n=1 for this repository, NOT promoted.** It is the same shape as the candidate filed for consistency boundaries (`docs/pks/2026-08-06-consistency-boundary-before-transaction-boundary-candidate.md`) and would extend the standing Development Discipline rule rather than become a standard of its own (`ES-005.4`). **A second independent occurrence is the precondition for promotion (`ES-006.1`).**

## 7 · First action if accepted

**Read the divergence evidence.** Nothing else. That single step either turns retirement into a subtraction or reveals that Authority B needs work first — and it costs one command against data the system has been collecting all along.

---

**Traceability:** the discovery report rev 1–3 (all evidence) · `PBDIGIT-39` · `PBDIGIT-45` · `ADR_20260806_1520_Demo_And_Production_Policy_Contexts` · `ElectionConstitutionSnapshot` · `NetworkBindingPolicy` · `TrustPolicyEvaluator:130-149` · `VoteController:1534,1549,1585-1601,3084-3114` · `DemoVoteController:1485,1493,1507` · `helpers.php:92-113` · `config/app.php:149` · `SovereigntyDivergenceSummary` · `D02AggregateConstitutionalDivergence` · `PBDIGIT-42` (blocks `N-3`) · `R-34` · `ES-002.2` · `ES-005.4` · `ES-006.1`
