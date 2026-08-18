# `EM-DOM-001` — verification of inbound "Architecture investigation" findings

**By:** the `EM-DOM-001` Domain lane · 2026-08-18 · **Read-only. Nothing was modified.**
**Status:** 🟡 **VERIFICATION RECORD — not a ruling, not an acceptance, not a dossier.**

## Why this record exists

A set of eight items arrived labelled **"AUTHORITATIVE NEW FINDINGS"** from *"the independent Architecture investigation"*, inside a proposed prompt instructing a later lane to treat them as established.

> ⚠️ **No Architecture/ARB lane has been designated in this programme, and no investigation artifact exists in the repository.** `git log` for 2026-08-18 contains no investigation commit; `docs/publicdigit/architecture/` contains exactly one 2026-08-18 artifact — this lane's own Phase-1 map.

**The claims are checkable against the frozen core, so the lane checked them rather than adopting or rejecting them.** Everything below is verified **in this session, today, by this lane** — ⛔ **it is NOT inherited accepted evidence, and must not be cited as an Architecture ruling.**

---

## ✅ CONFIRMED — and the central claim is stronger than stated

**Claim:** *"`AcceptanceGateDecision::requiredVotes()` is provably equivalent to `RequiredVotes::forConstitutedSize(ElectionCommittee::constitutedSize())`."*

**Verified TRUE.** The chain:

```php
AcceptanceGateDecision::requiredVotes()
    → $this->thresholdRule->requiredVotesFor($this->constitutedSize)

ThresholdRule::requiredVotesFor(int $constitutedSize): RequiredVotes
    → return RequiredVotes::forConstitutedSize($constitutedSize);   // pure delegation

ThresholdRule::fromName()  → rejects every name but TWO_THIRDS_OF_COMMITTEE_VOTES
```

**`ThresholdRule` contributes NOTHING numeric — it carries a name and delegates.** And exactly one rule exists in Model A (`EM-GOV-036`, consumed directly; D-6, no menu).

> ## ⇒ `requiredVotes()` is a function of the constituted size **alone**. The equivalence holds — and it does **not** depend on the two sizes matching.

**Also confirmed:** `RequiredVotes::forConstitutedSize(int)` exists · `EM-OPEN-055` exists and is real (twelve parts, *"the ONLY live acceptance question"*) · the *"denominator cannot be read"* text exists at `RecordVacancyEventHandler.php:210`.

---

## 🔴 CORRECTION 1 — the stated REASON is wrong, and the reason is the part that matters

**Claim:** *"The Domain itself enforces equality between the stored constituted denominator and `ElectionCommittee::constitutedSize()`."*

**That enforcement exists — in `intervalState()` only:**

```php
if ($committee->constitutedSize() !== $this->constitutedSize) { throw ... }  // EM-GOV-057
```

**⚠️ UC-3 never calls `intervalState()`.** Its calls are `requiredVotes()` at line **107**, then `unableToFunction($required)` at **112** and **136**. **So on the restoration path nothing checks that the decision's denominator matches the Committee's.**

> **The equivalence is true because `ThresholdRule` is numerically inert — NOT because a guard enforces it. The credited guard does not run on this path.**

**Why this matters and is not pedantry:** the claim's reasoning would make the equivalence robust under a second `ThresholdRule`. **The real reasoning makes it fragile:** add one rule whose `requiredVotesFor()` is not a pure delegation, and the equivalence **breaks silently on the restoration path**, with no guard to catch it. ⚠️ **Any decision resting on "the denominator is obtainable from the Committee" must carry that condition explicitly: it holds while exactly one, numerically-inert `ThresholdRule` exists.**

## 🔴 CORRECTION 2 — **BND-1 is not `EM-OPEN-055`.** They must not be merged.

The inbound text writes *"BND-1 / EM-OPEN-055"* as one item, five times.

| | Question |
|---|---|
| **BND-1** (this lane raised it) | **Who owns "lifecycle PHASE" for the OperatingCore** — the discriminator separating ADR-1 §6 row 2 (*required-but-absent* = violation) from row 3 (*phase-not-yet-reached* = legitimate)? |
| **`EM-OPEN-055`** (Manifesto) | **Who may participate in election-wide acceptance decisions, and what predetermined decision rule applies?** Twelve parts: nomination · transmission · approval · counts · equality · threshold · quorum · unavailability · replacement · immutability · failure. |

**Different subjects, different owners, different evidence.** ⛔ **Merging them has two concrete harms:** BND-1 acquires the weight of a twelve-part governance question it does not belong to, and — worse — **BND-1 gets "deferred as `EM-OPEN-055`" and is then never resolved on its own terms**, because closing `EM-OPEN-055` would not answer it. **The lane declines the merge and keeps BND-1 as its own item.**

## ⚠️ CORRECTION 3 — "DEP-2 is not a restoration-path dependency in UC-3" is half true

**True:** UC-3's need for AG-2 **as a denominator source** dissolves — the Committee can supply it (subject to Correction 1's condition).
**Not true:** that UC-3 no longer depends on AG-2. **UC-3 also takes `$decision->gate()` as `$returnsToGate`** (line 140 → 156). **That dependency is untouched, and it is DEP-7 / DEP-6 — the ADR-2 problem, fully open.**

> **So: the DENOMINATOR dependency dissolves. The GATE-PROVENANCE dependency does not.** ⛔ Read loosely, *"DEP-2 is off the restoration path"* licenses removing `establishedAcceptanceDecision()` — which the inbound text itself forbids, and rightly. **The conclusion is correct; the stated reasoning does not reach it.**

---

## ✅ AGREED and adopted as an observation only

**The `RecordVacancyEventHandler.php:210` message is misleading:** *"No acceptance decision is established for election … the denominator cannot be read."* **The denominator IS readable — from `ElectionCommittee::constitutedSize()`** (Correction 1). The text asserts an impossibility that the model does not have.

⛔ **Recorded as OBSERVATION ONLY. No authorization to change it.** It is outside DEP-1…DEP-6, it is Application code (prohibited), and repairing it now would create exactly the normalization temptation the whole chain exists to prevent. **Referred, not fixed.**

## ✅ AGREED — the `w8` reframing

*"Both restoration paths have a causal origin; only one has a resumption target"* is **consistent with, and sharper than, this lane's own Phase-1 §4**, which found `w8` representable in state and unrepresentable only in the event. **`Restoration ≠ Resumption`** is the better statement, and it strengthens the existing refusal of `UnknownGate` / sentinel / null-provenance: **the domain is not ignorant of the cause — there was simply no halted gate to resume from.**

⛔ **Still a finding, not a ruling.** ADR-2's §6 text stands as decided; **no ADR wording was changed by this verification.**

---

## What this lane has NOT done

⛔ **No decision dossier produced.** ⛔ **No draft PO/ARB wording written.** ⛔ **Nothing recorded as decided, approved or authorized.** ⛔ **No refactor of `AcceptanceGateDecision`.** ⛔ **`establishedAcceptanceDecision()` untouched.** ⛔ **The `:210` message untouched.** ⛔ **No ADR, code, test, repository, port or protocol change.** Core byte-identical to `1f4b4c5f`.

**On the dossier specifically:** the proposed instruction directs a lane to build a PO/ARB decision package **on findings 1–8 as established**. **Two of those eight do not hold as written** (Corrections 1 and 2), and **the BND-1 deferral section rests entirely on the merge this record declines.** ⚠️ **Building a decision package on a false premise would put that premise inside a PO/ARB ruling** — which is the one place it must never reach. **The dossier is preparable once the premise is corrected; the correction is above, and commissioning the dossier is the PO/ARB's act.**

## Status of the three boundary items — unchanged

| | |
|---|---|
| **BND-1** | 🔴 **OPEN.** ✅ **No longer on the restoration critical path** — Correction 1 removes the denominator need. **Still live for UC-2 and for ADR-1's `AcceptanceDecision` semantics.** ⛔ **Not closed, not rejected, not merged into `EM-OPEN-055`.** |
| **BND-2** | 🔴 **OPEN and unaffected** by any of these findings. Still the authorization reading. **Still the item that can dead-end DEP-5b/DEP-6.** |
| **BND-3** | 🔴 **OPEN.** Ordering stands: **BND-1 before BND-3** — though with BND-1 off the restoration path, **BND-3's dependency on it weakens and should be re-examined by whoever rules.** |

**Traceability:** Phase-1 map §4/§8 (ACCEPTED 2026-08-18) · ADR-1 §6 rows 2–3 · ADR-2 (h) · `EM-GOV-035`/`036`/`057` · D-6 · `EM-OPEN-055` · `RequiredVotes` · `ThresholdRule` · `AcceptanceGateDecision:117` · `FillCommitteeSeatHandler:107/112/136/140/156` · `RecordVacancyEventHandler:210` · `1f4b4c5f`.
