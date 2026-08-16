# Decision Request — the threshold catalogue and each rule's definition *(`EM-OPEN-074` + `EM-OPEN-076`, one act)*

**Type:** Governance decision request (Session 2) · **Date:** 2026-08-16
**⛔ No rule chosen. The arithmetic is computed; the selection is the PO's. Architecture ⏸️ · Session 3 🛑.**

---

## 1 · What each named rule's definition must state

**A named rule is not a percentage. To be executable without the Service Provider deciding anything, each must specify five things:**

| # | Component | Why it cannot be omitted |
|---|---|---|
| 1 | **population** | Committee Votes or Representation Votes — `EM-GOV-030` keeps them distinct |
| 2 | **denominator** | for representation, *candidates who selected* (`EM-OPEN-065`, Option B) |
| 3 | **rounding** | ⌈⌉ or ⌊⌋ or *strictly greater* — **this decides safety at small N, not the fraction** |
| 4 | **applicability** | any population floor below which the rule does not apply |
| 5 | **behaviour below the floor** | ⛔ **must be governed, never inferred** |

## 2 · ⚠️ An irreducible fact at N = 2

> **At two votes, "a genuine supermajority" and "no individual veto" are mathematically incompatible.** **Requiring both votes is an individual veto; requiring one is not a supermajority. No rule can be both.**

**This is not a gap to be closed — it is a choice that every candidate rule must make, and the act should make it explicitly rather than letting a rounding convention make it silently.**

## 3 · The candidates, computed

| Form | N=2 | N=3 | N=4 | N=5 | N=7 | N=20 | Individual veto | Distorts two-thirds at |
|---|---|---|---|---|---|---|---|---|
| **two-thirds, rounded up** | **2** | 2 | 3 | 4 | 5 | 14 | **yes, N≤2** | never |
| **two-thirds, rounded down** | 1 | 2 | **2** | **3** | **4** | **13** | never | **N = 2, 4, 5, 7, 20 …** *(50%, 60%, 57%, 65%)* |
| **two-thirds, rounded up, capped at N−1** | 1 | 2 | 3 | 4 | 5 | 14 | **never** | **N = 2 only** |
| **at least half, rounded up** | 1 | 2 | 2 | 3 | 4 | 10 | never | *(not a supermajority rule)* |

## 4 · ⚠️ A rule form that has not yet been named

> **`TWO_THIRDS_ROUNDED_UP_CAPPED` — required = min(⌈2N/3⌉, N−1)**, i.e. *"two-thirds rounded up, but never so many that no participant could dissent."*

**It is identical to plain two-thirds-rounded-up at every population of three or more, and it can never produce an individual veto, because at least one dissent is always tolerable by construction.** **Its only deviation is at N=2, where it requires 1 — and §2 shows that some deviation at N=2 is unavoidable in any rule.**

**Compare round-down, which was the other way of avoiding the veto: it deviates at N = 2, 4, 5, 7, 20 …, delivering 50% at N=4 and 65% at N=20.** ⚠️ **Governance offers the capped form as a CANDIDATE, not a recommendation — but records the comparison, because "round down" and "cap" are not equally costly and the difference is computable rather than a matter of taste.**

## 5 · The two populations need not share a menu

✅ **The Committee has a guaranteed minimum of three (`EM-GOV-033`), so `N ≤ 2` cannot arise there and plain `TWO_THIRDS_OF_COMMITTEE_VOTES` rounded up is safe at every possible size.** **The small-population problem is representative-side only, and the act may legitimately give the two sides different rules.**

## 6 · What must not be delegated

⛔ **The Service Provider must not decide what a rule means, how it rounds, when it applies, or what happens below a floor** (`EM-GOV-006`, `EM-GOV-032`, `EM-GOV-035`, the `EM-OPEN-047` resolution). **It renders the permitted menu and executes the selected rule.**

## 7 · What this act does NOT decide

**`EM-OPEN-075`** — what applies below a floor, if a floor is chosen *(and `EM-OPEN-077`'s three consequences bear on it: the deadlock risk returning through the floor, two-candidate elections excluded, and Model C's "both must pass" becoming ill-defined)*. **A capped rule with no floor would leave `075` with nothing to decide — recorded as a consequence, not as an argument for it.**

**Traceability.** `EM-GOV-006`/`022`/`030`/`032`/`033`/`034`/`035`/`036`/`037` · `EM-OPEN-047`/`065`/`074`/`075`/`076`/`077` · threshold arithmetic (`a00423b3`).
