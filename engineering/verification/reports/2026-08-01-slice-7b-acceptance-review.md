# Slice 7B — Acceptance Review

**Date:** 2026-08-01 · **Prepared by:** Recording Architect · **For:** ARB acceptance (queue item 9)
**Status:** 📋 **PREPARED — no acceptance decided.** One item requires an explicit ARB position (§5).
**Repository Integrity Gate:** ✅ PASSED.

---

## 1. Scope — was only authorized work implemented?

**Six files, all inside the authorized surface. Verified by diff, not asserted:**

| File | Kind |
|---|---|
| `Election/Domain/EvidencePreservationWindow.php` | production — the value |
| `Election/Application/Service/ResolvesEvidencePreservationWindow.php` | production — the collaborator |
| `Unit/…/EvidencePreservationWindowTest.php` · `Feature/…/EvidencePreservationWindowResolutionTest.php` | tests |
| `developer_guide/election/07_…md` + index | documentation |

**7C surface untouched** — `AuditCleanup`, `ElectionAuditService` and the audit tree carry **no change**. **No config key added** *(7A supplied them)*. **No DI binding added** — the port interface was already bound, so the container auto-wires the service. ✅

## 2. Verification — do the tests and the gate pass?

```
Slice 7B suite:  11 tests · 15 assertions — OK   (K1–K10; K10 carries two)
Architecture:    149 tests · 632 assertions — OK
Deptrac:         0 errors  (deptrac.yaml unmodified)
PHPStan max:     No errors  (zero suppressions, no baseline entry)
Regression:      266 tests · 665 assertions · 0 failures
MERGE GATE:      PASS
```

**No test was modified during GREEN.** PHPStan's 10 first-run errors were fixed **at root** — narrowing the legacy model's `mixed` attributes — not suppressed. ✅

## 3. Architecture — does it conform, without unauthorized change?

| Approved decision | Conformance |
|---|---|
| **Policy 2** — EPW is a domain concept, not a config value | ✅ a Value Object in `Election/Domain/` |
| **Ownership** — Election owns EPW | ✅ |
| **Construction** — private ctor + named static factory | ✅ `forElection()` — a fact, not a procedure |
| **Business values only** | ✅ no port, config, model or clock; the instant is an argument |
| **R-44 / R-57** — MAD via **Election's own** port | ✅ asserted by K7; **Deptrac 0** confirms no cross-context import |
| **AP-1** — fail closed, never clamp | ✅ non-positive terms throw |
| **AP-2** — MAD keeps one home | ✅ no second key |
| **P7B-2** — the absent-anchor fallback belongs to the service | ✅ |
| **R-45** — Election answers; Audit/Retention acts | ✅ the service answers; nothing acts |
| Domain purity | ✅ enforced by `test_greenfield_domain_is_framework_free` |

**No architectural change was introduced.** ✅

## 4. Handover — can 7C become next?

**Technically yes** — 7B is inert; nothing consumes the window. **Governance-wise, 7C requires its own authorization** (queue item 10), which is not part of this acceptance.

---

## 5. ⚠️ The one item requiring an explicit ARB position — the INTERIM anchor

**The ARB's challenge is correct, and I want to state it more plainly than my GREEN report did.**

`ResolvesEvidencePreservationWindow::anchorOf()` takes the first available of **`results_published_at` → `end_date` → `archived_at`**.

**My GREEN report defended this as *"it resolves which date to measure from and invents no duration."* That is true, and it answers the wrong objection.** The point is not whether a *duration* was invented — it is that **the software now behaves according to an ordering no authority chose.** Labelling it INTERIM documents the debt; **it does not make the code neutral.**

**Before:** Q-2 undecided. **After:** Q-2 undecided **and the system behaves as if `results_published_at` were the anchor whenever it is present.**

**This is the AP-1 defect class in a new form** — not an invented *value*, but an invented *rule*. **It should not enter the baseline as an implementation detail.**

### Two dispositions, and the choice is the ARB's

| | Disposition | Consequence |
|---|---|---|
| **(a)** | **Accept 7B, and record the interim anchor as an explicit, acknowledged debt** with Q-2 named as its owner | Fastest. The debt is on the governance record rather than buried in a private method, and Q-2's ruling later replaces one method body |
| **(b)** | **Require extraction before acceptance** — an `EvidenceAnchorResolver` port with a `TemporaryDefaultAnchorResolver` implementation, per the ARB's suggestion | Cleaner separation: Q-2's ruling then **swaps an implementation** rather than **editing service behaviour**, and the interim policy becomes a named, replaceable thing rather than a private method |

**📝 Recording Note.** I judge **(b)** architecturally better for the reason the ARB gave — it moves the interim policy from *hidden* to *named and replaceable*. **But it is a scope addition after GREEN**, so I have not done it: adding a port and an implementation on my own reading would be engineering widening an accepted slice. **Either disposition is defensible; what should not happen is acceptance that passes over the item in silence.**

---

## 6. Acceptance criteria summary

| # | Criterion | Result |
|---|---|---|
| 1 | Only authorized work implemented | ✅ |
| 2 | K1–K10 pass · merge gate passes | ✅ |
| 3 | Conforms to approved architecture; nothing unauthorized | ✅ |
| 4 | Definition of Done — guide filed, zero suppressions | ✅ |
| 5 | **Interim anchor policy** | ⚠️ **requires an explicit ARB position — (a) or (b)** |

**Criteria 1–4 are satisfied. Criterion 5 is not a defect in the delivered work; it is a decision the ARB has not yet made and which the code currently makes by default.**

---

**Traceability:** R-58 (execution) · R-57 / R-44 (Election's own port) · R-56 (plan) · R-45 · P7B-2 · Policy 2 · §142 · AP-1 · AP-2 · slice 7B RED and GREEN reports. **No acceptance decided · no code changed by this review · 7C untouched.**
