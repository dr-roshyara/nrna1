# KOS-ARCH-BASELINE-001 Phase A — Producer Self-Review Disclosure

**Author:** the process holding `S4-architecture-baseline` — **the producer of Phase A** · 2026-08-17
**Type:** **P-2 Class-A ownership review.** **This is NOT independent verification and does NOT satisfy the verification gate.**

> **Phase A is unmodified.** `docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md` is byte-identical to `75bfcaae` (verified: empty diff). **Nothing below has been repaired** — repairing the artifact under verification would contaminate it. These are disclosed defects for the independent verifier to **confirm, reject, or extend**.

---

## Why this document exists

The PO/ARB directed that the independent verification proceed *"with D-1/D-2/D-3 disclosed as observations from the producer's self-review."* Those findings existed only in a conversation the verifying process cannot see. This records them where a startup check will reach them.

**Method:** each Phase-A claim was re-derived using a **different** measurement method than the original, deliberately seeking falsification.

---

## Defects found — all producer error, none temporal drift

### 🔴 D-1 · Hook count wrong: Phase A says 8, actual is 10

| | |
|---|---|
| **Phase A states** | "8 wired hooks" (§1), "wires 8 hooks at 4 runtime moments" (§6 diagram), and §11 evidence appendix — classified **`Observed`, confidence `high`** |
| **Actual** | `PreToolUse 5 · PostToolUse 2 · SessionStart 1 · Stop 2` = **10** |
| **Not drift** | `git diff 75bfcaae..HEAD -- .claude/settings.json` is **empty** — the file is unchanged since the baseline was written |
| **Cause** | the original method printed hook lists and the total was **eyeballed rather than computed** |
| **Reproduce** | `python3 -c "import json;s=json.load(open('.claude/settings.json'));print(sum(len(e.get('hooks',[])) for es in s['hooks'].values() for e in es))"` |

**Severity note:** this was recorded as a high-confidence *Observed* measurement. A wrong observation is worse than an acknowledged unknown.

### 🔴 D-2 · "Four of seven components have no executable substance" — false, and self-contradicting

| | |
|---|---|
| **Phase A §1 states** | "Four of the seven declared components have **no executable substance at all**" |
| **Actual** | **two** components have zero assets — `CMP-003` (knowledge_manager) and `CMP-006` (review_engine). `CMP-005` has 2 assets; `CMP-007` has 1 |
| **Aggravating** | **Phase A's own §3.1 table already says this** — it marks CMP-005 and CMP-007 as *PARTIAL*, not *NONE*. The executive summary contradicts the detail table **inside the same document** |
| **Defensible restatement** | *"two of eight registered components have no assets; two more are partial or by-reference"* |
| **Reproduce** | count `component: CMP-nnn` occurrences in the registry's `assets:` block, per component id |

### 🟠 D-3 · An unflagged declared-vs-registry discrepancy

The frozen `Phase-03A-Reference-Architecture.md` declares **"Seven components"** and its table does **not** contain `composition_root`. The live registry carries **eight** (`CMP-001…008`), including **`CMP-001 composition_root` with 2 assets**.

Phase A used "7" and "8" in different sentences and **never noted that they disagree**. For a document whose entire thesis is *declared ≠ executing*, missing a declared-vs-registry gap — and specifically the **composition root**, the component that wires everything — is a miss of exactly the kind it existed to catch.

---

## Correct as written — temporal change, not defect

Phase A's **"8 workflow records"** is now **13**. A baseline is a dated snapshot; it must be assessed **as at 2026-08-15**. This is not an error.

---

## Claims that survived re-derivation by different methods

| Claim | Independent method used | Result |
|---|---|---|
| 16 registered assets (correcting the handover's 24) | YAML id-set parse *(orig.: `grep -c`)* | ✅ contiguous `AST-001…016` |
| 3 wired-but-unregistered hooks | parsed `settings.json` commands, diffed against registry text | ✅ `claude-code-trigger` · `engineering-placement-guard` · `project-knowledge-guard` |
| 12 scripts on disk | direct count | ✅ |
| All six ES documents `PROPOSED` | header census | ✅ |
| `AST-016 → AST-015`, one-way via `proc_open`; AST-015 the dependency root | source read | ✅ |

**Phase A's central thesis is unaffected by D-1/D-2/D-3:** declared and executing architectures differ materially, and the direction of every finding is unchanged. What is damaged is the **precision and internal consistency** of three `Observed` claims.

---

## What the independent verifier should do with this

1. **Confirm or reject** each of D-1, D-2, D-3 independently — do not take them on this document's word; it is the producer's word.
2. **Extend.** These were found by re-deriving with different methods. **The producer had read Phase A repeatedly without noticing D-2, which was visible on its own page.** The defects still in there are, by construction, the ones the author cannot see — so the useful question is *what else is of D-2's shape?*
3. **Assess materiality** — do any of them change a Phase-A conclusion, or only its precision?
4. **Attack the weakest points named by Phase A itself** (§11): the self-authored elements · the nine unknowns (was any recorded as unknown where evidence existed?) · the low-confidence `Inferred` claim that governed session orchestration is a distinct bounded context (should it have been `Unknown`?) · whether any *target-architecture* judgment leaked into what was commissioned as pure reconstruction.
5. **Falsify, do not confirm.** A verification that re-reads the document and agrees adds nothing.

---

## State at the time of writing

```
KOS-ARCH-BASELINE-001   OPEN
  S4-architecture-baseline           architecture   HANDED_OFF   ← producer; disqualified from verifying
  S1-verification-baseline-phase-a   verification   ACTIVE (mutation owner)
  grants: G-KOS-ARCHBASE-A (produce) · G-KOS-ARCHBASE-A-VERIFY (verify) — both AUTHORIZED
```

**The verification assignment is ACTIVE, granted, and awaiting a process other than the producer.** Its `executionContext` (seq 4) states that requirement, marked self-declared and **not attestable** (`INV-ATTR-2`).

⚠️ **`AST-016` will misreport this lane.** It cannot read handoffs (**V-3**), so it reports a missing predecessor handoff that demonstrably exists at seq 5 — the mechanism accepted the START, which proves it. **Trust `workflow-state.php fold`, not the resolver, here.**

---

**Traceability:** Phase A `75bfcaae` (unmodified) · commission + evidence handover `2026-08-15-KOS-ARCH-BASELINE-001-phase-a-commission.md` · grants `G-KOS-ARCHBASE-A`, `G-KOS-ARCHBASE-A-VERIFY` · record seq 4 (REGISTER, independence requirement) · seq 5 (HANDOFF) · seq 6 (human START) · `R-34` · `A-1.4`/`D-5` · `P-2` Class-A/B · `INV-ATTR-2` · `V-3` (resolver handoff blindness) · the LCOM4 precedent for a disqualified process arming its successor (`32a35ddf`).

---

> **Producer self-review. Not verification. The gate remains open.**
