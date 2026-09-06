# 11 — Human Decision Register

**A gap is classified NORMATIVE only when corpus, mathematics, execution and empirical evidence all
fail to determine it. Two qualify. Neither blocks the theory.**

---

## ND-282-1 — `unask` deletion semantics for the inquiry register

**Question:** Should `Q_t` support an `unask(p)` operation, and if so, does *"p was once asked"* remain
recoverable from `Q` alone?

**Why mathematics cannot decide:** both designs are formally sound. `Q_t` is a projection of History, so
**nothing is lost from the system either way** — History retains `ask` and `unask` regardless. Executed:
`ask(p); unask(p) → Q = ∅`, with both events still in History.

**Why evidence cannot decide:** the real EKP has no inquiry register at all, so no observation exists.

| Option | Consequence |
|---|---|
| **A — no `unask`** | `Q` is monotone; *"once asked, always asked"*. Simplest; `Q` grows without bound. |
| **B — `unask` present, `Q` non-monotone** | supports retraction of an inquiry; `Q` alone becomes lossy, History remains complete. |
| **C — `unask` present, `Q` carries a tombstone** | fully recoverable from `Q`; **violates the M3 minimality that selected `Q_t` in the first place.** |

**Recommendation:** **A**. `Q_t` was selected in Step 281 precisely because it is the minimal ΔR passing
M1/M2/M3. **C reintroduces structure M3 excludes. B is defensible but answers a need no test exercised.**

**Invariant consequences:** A preserves all 8 Step-281 invariants. C would add a component to `Q_t` and
require re-running the minimality proof.

---

## ND-282-2 — governance ratification of the canonical theory

**Question:** Who ratifies the canonical model, and by what act?

**Why mathematics cannot decide:** this is the corpus's own result, not a gap —
> *"The mechanism records authority; it does not grant authority."* (20/20 production grants carry
> `humanActRef`.)

**Why evidence cannot decide:** ratification **is** the human act; no execution can substitute for it.

**Options:** ARB/HPA ratification act · staged ratification per bounded context · defer until `EC` improves.

**Recommendation:** **none offered — this is outside verifier authority.** Recording it as a decision is
the correct action; recommending an outcome would be exactly the overreach the corpus's own principle
forbids.

**Invariant consequences:** none formal. `GC` remains `NOT CLAIMED` until such an act occurs.

---

**No other item met the four-way test.** T-3, T-4, I-1, I-2, I-3, E-1, E-2, E-3, G-P1 and `Q_t`
replay/serialization were all decided by corpus, mathematics or execution — **not by preference.**
