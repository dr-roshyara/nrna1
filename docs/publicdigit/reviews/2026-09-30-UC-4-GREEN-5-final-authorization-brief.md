# UC-4 / `GREEN-5` — **FINAL AUTHORIZATION BRIEF**
### *"What exactly would I be authorizing if I choose A, B or C?"*

**Work item:** `EM-IMPL-002` · **Epic:** `PBDIGIT-EPIC-03` · **Date:** 2026-09-30
**Prepared by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Basis:** decision record **§§11–13** · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

> ⛔ **NO RECOMMENDATION. NO RANKING. NO OPTION SELECTED.** This brief answers one question — *what does each choice actually authorize* — and nothing else. Decision Blocks **A**, **B** and **State C** remain **BLANK** in the decision record.
>
> ⚠️ **Every §13 claim relied on below was re-verified from source by this process before being used** (`RecordedOperationalStatus` and `ElectionOperationalStatus` signatures · `PBDIGIT-72`'s cause table · the pin's guard mechanics). §13 itself was produced by a **separate session the human started** (`42e0287e…`), which met §9's independence bar in mechanism.

---

## 1 · What the independent audit changed (§13)

| | §11/§12 said | §13 corrected to |
|---|---|---|
| **`A-3`** | *"no authorized port can supply a `HaltedAtGate`"* (citing a UC-3 docblock of **2026-08-17**) | ⚠️ **The contract now EXISTS.** `Port/RecordedOperationalStatus` landed **2026-08-19** (Act B GREEN, `216552aa7`), returning `ElectionOperationalStatus`, which carries **`?HaltedAtGate`** and **`OperationalCondition`**. The blocker is no longer *"no contract"* — it is *"the contract exists and is **not wired**"*: retrieval-only, *"**NO ADAPTER AND NO CALLER IS AUTHORIZED**"*, and wiring it is **Acts C/D, unauthorized**. |
| **`A-3`** | the producer gap is `PBDIGIT-72`'s *"second"* cause | ❌ **Wrong.** It is **cause 1**; **cause 2** is *"UC-4 is itself unimplemented"*. |
| **`A-2`** | the pin fails `?? throw` and early return | ⚠️ **Overstated.** The pin **mechanically passes both**; it fails **only on silence**. The shape prohibitions come from **`ADR-1` §6(b) / `6-guard`**, not the pin. And **AG-3's early return is SANCTIONED** (gate Q4, constraint ⑦) — the constraint bites on **AG-1/AG-2** lookups only. |
| **`A-4`** | `M-7`: AG-2 enters only via `¬Inoperative`; restoration branch needs no gate | ⚠️ **Exceeds the evidence.** P-6 also takes `bool $recoverySucceeded`, which the fixtures suggest derives from AG-2 · `ElectionOperationalStatus::condition()` is another conceivable source of `¬Inoperative` · test 2 seeds a gate **and** an Inoperative committee. **All undetermined — the handler is unimplemented.** |
| **`A-4`** | §12: *"a 'no' would not block the restoration branch"* | ❌ **Conflates technical need with governance status.** The governing records block `GREEN-5` **as a whole**, not per branch (design map `:276`; Rule-8 sequence `:35-43`). |
| **`A-1`** | (uncited) | ➕ The hold's own steps end *"→ **only then GREEN-5 authorization**"* (`architecture-hold.md:34`). **The hold itself anticipates a further authorization act** — evidence bearing on *amendment vs hold release*, **not settling it**. |

---

## 2 · The question — unchanged

> ### **May UC-4 (`ReportPeriodExpiry`) assume that an established `AcceptanceGateDecision` exists, as an explicit scope boundary / command precondition?**

---

## 3 · IF YOU CHOOSE **A** — *"yes, UC-4 may assume it"*

### You would be authorizing exactly two things

1. **A recorded scope boundary / command precondition** on the UC-4 slice — *not* an Application-layer interpretation of `null`. `ADR-1` §6(b) and the `6-guard` continue to forbid a handler classifying the cause of absence.
2. **A classification of your own act** — either an **amendment** to the Rule-8 consequent sequence, or a **hold release** under the existing hold. §13 `A-1` supplies evidence for the latter reading (*"only then GREEN-5 authorization"*) but **does not settle it**, and no document in the corpus defines what constitutes an amendment to a Rule-8 sequence.

### You would NOT be authorizing

⛔ implementation of UC-4 — **a separate authorization act is still required**, which the hold's own text anticipates · ⛔ resolution of `ADR-1` §6(a)'s two `AcceptanceDecision` meanings · ⛔ `BND-1` · ⛔ `BND-3` · ⛔ the `ADR-1` §6(c) normalization slice (still BLOCKED at its Rule-8 gate) · ⛔ **Act C** or **Act D**.

### What stays blocked even after A — the operative part

| Blocker | Why A does not clear it |
|---|---|
| **Test 1 cannot go GREEN** | It requires a `HaltedAtGate`. The **contract exists** (`RecordedOperationalStatus` → `ElectionOperationalStatus`), but it is **unwired**, with no authorized adapter or caller. Wiring is **Acts C/D — unauthorized**. |
| **The pin (a fifth binding test) stays RED** | Scope-wide over every handler file. A UC-4 body must reach an **explicit, domain-authorized decision** at any possibly-absent **AG-1/AG-2** dereference. (AG-3's early return is sanctioned.) |
| **Four technical questions remain open** | Does AG-2 also feed `recoverySucceeded`? · Is AG-2 *the* source of `¬Inoperative`, or `ElectionOperationalStatus::condition()`? · Does the restoration branch need its own Inoperative guard? · Governance blocks `GREEN-5` **as a whole**, not per branch. |
| **`PBDIGIT-72` unchanged** | A addresses **cause 2** only. That ticket states: *"**A halt producer ALONE would not make `EM-GOV-063` reachable.** Any future authorization for this item must cover **both** causes."* |

---

## 4 · IF YOU CHOOSE **B** — *"no, the absence case must be defined first"*

### You would be authorizing

1. **Nothing to implement.** UC-4 remains held; `GREEN-5` remains blocked.
2. **Preservation of the Rule-8 consequent sequence**, unamended.
3. **That the next act is a PO/ARB authorization for a DOMAIN slice** — the act the Rule-8 gate §5 names as *"next act, and it is the PO's"*.

### ⚠️ But §13 changes what that slice is

The Rule-8 gate's 2026-08-18 framing was *"the contract §6(b) requires **does not exist**"*. That is now **only partly true**. Split it:

| Missing thing | Status today |
|---|---|
| A **discriminator** for `ADR-1` §6(a)'s two `AcceptanceDecision` meanings (gate **Q2**) | ⛔ **still genuinely absent** — this is `BND-1` |
| A **retrieval contract** for operational status incl. `?HaltedAtGate` | ✅ **exists** since 2026-08-19 — but **unwired** (Acts C/D unauthorized) |

⇒ **The domain slice B triggers may be narrower, or differently shaped, than the gate's original framing implies** — possibly a *discriminator* question plus a *wiring authorization*, rather than "create the contract". **Stated as evidence; this brief does not design that slice.**

### You would NOT be authorizing

⛔ any implementation · ⛔ resolution of `BND-1` or `BND-3` by this act · ⛔ design of the missing discriminator · ⛔ any fallback, sentinel, `Unknown`, exception meaning or lifecycle interpretation.

---

## 5 · IF YOU CHOOSE **STATE C** — *clarification before deciding*

### You would be authorizing

**Nothing.** You would return specific questions and the decision stays open.

### ⚠️ What C now buys that it did not buy in the first brief

§13 `A-4` names **four questions that are technical, not governance** — answerable by bounded reading and design work, **without** any PO/ARB decision:

1. Does `recoverySucceeded` derive from AG-2, or from elsewhere?
2. Is AG-2 the source of `¬Inoperative`, or is `ElectionOperationalStatus::condition()`?
3. Does the restoration branch require its own Inoperative guard?
4. What exactly would Act C/D wiring of `RecordedOperationalStatus` entail, and does it implicate `BND-3`?

⚠️ **Answering 1–3 would materially sharpen both A and B.** Answering 4 overlaps the Act-C fit analysis the roadmap already contemplates. ⛔ **This brief does not propose commissioning that work** — scoping an investigation is itself yours.

---

## 6 · True under all three options

- **Decision ≠ authorization ≠ implementation ≠ verification** — each is a separate act.
- **`BND-1` and `BND-3` remain DEFERRED** unless separately and explicitly addressed.
- **`ADR-1` and `ADR-2` remain DECIDED, and both Rule-8 gates remain BLOCKED.**
- **`PBDIGIT-72` requires both causes covered**; no option here covers both.
- **The `AbsentAggregateReferenceRedTest` pin stays RED** until the normalization slice runs — which is itself blocked.
- **`app/` and `tests/` are byte-untouched** by every document in this chain.

---

## 7 · Non-actions

⛔ No option selected · no ranking · no recommendation · no implementation · no RED changed or created · no domain contract designed, named or placed · `BND-1`/`BND-3` untouched · `ADR-1`/`ADR-2` unmodified · Act B not reopened · `GREEN-*` not renumbered · `PBDIGIT-72` unmodified · the stale pin docblock not repaired · no investigation commissioned.

> ## **Decision blocks remain BLANK. The next act is yours.**

**Traceability:** decision record §§11–13 (`c41afeaa1`) · scoping brief `2026-09-30-…-scoping-decision-brief.md` · `PBDIGIT-73` (`7ee452ee0`) + §§9–10 · `ADR_20260817_2145` §6(a)(b)(c)(d)/`6-guard`/⑦ · `rule8-gate-adr1.md` §1/§5/Q2/Q4 · `architecture-hold.md:3,:34` · `red-amendment-approval.md:10` · `Port/RecordedOperationalStatus.php:56-58` · `Condition/ElectionOperationalStatus.php:17-18,:27,:44` · `ExpiryConsequence.php:38-45,:73-77` · `PBDIGIT-72:40-47` · `R-34`/`EP-02`
