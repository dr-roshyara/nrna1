# EXP-0002 — Is "no interpretation inside the instrument" enforced?

> ⛔ **Four stages, kept separate.** This is the discipline the `bar` episode taught: observation must not jump straight to theory.

---

## Stage 1 · TEST *(pre-registered, before any inspection)*

**Hypothesis.** `R-26` is **enforced**: instruments run → capture → return PASS/FAIL → stop, with no interpretation inside the instrument.

**Falsifier — stated before looking.** An instrument that does any of:

| | |
|---|---|
| **(a)** | assigns **severity** to its own findings |
| **(b)** | **recommends** an action |
| **(c)** | **auto-fixes** what it finds |
| **(d)** | emits a judgement **beyond PASS/FAIL/exit-code** |

**Prediction.** If the separation of powers is real in implementation: **0 instruments violate.**

**Population.** The six instruments the corpus names: `knowledge-lint` · `identifier-check` · `link-check` · `knowledge-graph` · `doc-placement` · `check_roles`.

---

## Stage 2 · RAW RESULT *(observation only — no interpretation)*

```
instrument             severity  recommend  autofix  verdict
knowledge-lint.php        26         6         1        18
identifier-check.php       5         2         0        13
link-check.php             4         0         2         2
knowledge-graph.php        0         1         2         0
doc-placement.php          0         4         0        12
check_roles.php            4         4         0         2
```

**Four specific observations, verbatim:**

1. `knowledge-lint.php` contains **21 severity-assigning calls** — 14 `$add('error', …)`, 7 `$add('warning', …)`.
2. `knowledge-lint.php:152–155` — *"Warn-only (D-4): **ALWAYS returns 0** — findings are a FIX BEFORE HANDOFF **recommendation to the author**, never a rejection."*
3. `link-check.php:285` — `file_put_contents($src, $txt);` under `--apply`, rewriting **source documents**. Followed by: *"APPLIED N deterministic repairs across N files."* Default path prints *"(report only — nothing changed; --apply repairs confidence >= 99 only)"*.
4. `docs/knowledge/schema/knowledge-schema.yaml:94–101` — *"Lint rules … enabled/severity are **data-driven**"*, then `frontmatter_present: {severity: error}`, `required_fields_present: {severity: error}`, and so on.

---

## Stage 3 · FORMAL INTERPRETATION

### The mitigation — observation 4 defeats falsifier (a)

Severity is **declared in the schema, per rule, externally**. An instrument that reports `frontmatter_present: error` is **applying a governance-declared severity**, not judging.

> ⭐ **This is `R-26` working exactly as intended:** the rule lives in governed configuration; the instrument reads it. **Falsifier (a) does not fire.**

### Two violations survive

**Violation 1 — `link-check --apply` repairs what it measures.** `[falsifier (c)]`

`ES-003.1` states: *"A qualification **never silently fixes what it finds**"*, and prescribes `Qualification → Findings → **Decision Authority approves corrections** → Engineer implements → Re-run → Verdict`.

⛔ **`--apply` removes the Decision Authority from that sequence.** The instrument finds, decides the repair is safe, and applies it. The `confidence ≥ 99` threshold and the `--apply` flag are **mitigations of blast radius, not of role**: the capability to act on its own findings exists inside the measuring instrument.

⚠️ And note what `confidence ≥ 99` is — **a numeric threshold governing an architectural action**, in a repository whose `ES-003.2` rules that numeric scores are *"conversational, never architectural."*

**Violation 2 — `knowledge-lint` returns no verdict.** `[falsifiers (b) and (d)]`

`R-26` requires *"returns PASS/FAIL → stops."* This instrument **always returns 0** and self-describes its output as *"a recommendation to the author, never a rejection."*

⛔ **A recommendation is an interpretation.** An instrument that cannot fail produces no verdict for an authority to accept — so for this instrument the first conjunct of `promote(k)` has no value to carry.

### Verdict

> ## ⚠️ **`R-26` is PARTIALLY ENFORCED.**
>
> **4 of 6 instruments comply.** Severity is correctly externalized — the mechanism's best feature. But **one instrument can repair what it measures**, and **one produces recommendations instead of verdicts**.

⛔ **The hypothesis as stated is FALSIFIED** — the prediction was 0 violations. ⚠️ **But the failure is specific and local, not systemic.**

---

## Stage 4 · IMPACT ON THE CANDIDATE THEORY

| Claim | Before | After |
|---|---|---|
| *The rules imply the separation* | `D*` | ⭐ **unchanged — still supported.** No governance text was contradicted |
| *The system exhibits the separation* | ⛔ untested | ⚠️ **PARTIALLY. 4 of 6 comply; 2 violate in different ways** |
| *Evidence and governance are independent roles* | `D*` | ⚠️ **`D*` retained — but now with a measured enforcement gap** |
| *"A qualification never silently fixes what it finds"* | `A` corpus fact | ⛔ **CONTRADICTED IN IMPLEMENTATION** by `link-check --apply` |

### ⭐ The genuinely new finding

> **The implementation contains a capability the governance forbids.**

This is not a theory defect. It is a **gap between prescription and realization**, and it is the first one this reconstruction has been able to measure. It makes the central question sharper:

⛔ **Not** *"is the mechanism real or only prescribed?"* — the answer is now **"prescribed, and realized unevenly."**
⭐ **But** *"does an unevenly enforced separation still function as a separation?"*

### What does **not** change

- ⛔ No seed item is promoted. `SI-0009` stays `D*` — this experiment tested *enforcement*, not the mechanism's operation.
- ⛔ The mechanism still has **zero observed promotion instances**. Instruments existing is not the loop running.
- ⛔ Nothing reaches `E` except `SI-0007`. This is an `E`-level result about **implementation conformance**, not about the theory.

### New open questions

| # | Question |
|---|---|
| `OT-0002a` | Is `--apply` ever used in practice, or is the capability dormant? *(git history / CI config — testable)* |
| `OT-0002b` | Does `knowledge-lint`'s always-0 exit make it a *qualification instrument* at all, or a different kind of tool the theory has no category for? |
| `OT-0002c` | ⭐ `confidence ≥ 99` is a numeric threshold governing an architectural action. Does `ES-003.2` apply to instruments, or only to *review records*? |

---

*`EXP-0002` · pre-registered · executed 2026-09-22 · population: 6 named instruments · hypothesis FALSIFIED with 2 specific violations and 1 mitigation · ⛔ no seed item promoted · four stages kept separate.*
