# FUTURE ARCHITECTURAL DIRECTION — NOT CURRENT SCOPE

## Common Cohesion Engine

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Status:** **FUTURE ARCHITECTURAL DIRECTION — NOT CURRENT SCOPE**, as the act requires it be marked.

---

## 1 · The direction

> **The long-term target is one authoritative implementation of the language-neutral L4/L5 cohesion and LCOM4 engine.**
> **Language-specific bindings produce the common L3 Fact Model.**
> **PHP is the first supported language binding and remains the current implementation scope.**
> **The existing PHP-specific LCOM4 calculation may be retired only after a language-neutral authoritative engine has been independently verified for semantic equivalence against the declared conformance specification.**

## 2 · What this direction is NOT — the act's own list

- ⛔ does **not** authorize implementation;
- ⛔ does **not** require Java/Python or another language now;
- ⛔ does **not** retire the existing PHP calculation;
- ⛔ does **not** alter the current implementation scope;
- ⛔ does **not** create a new work item.

**Accordingly no grant, assignment or transition was recorded for it.** It is a direction on the record, not authority to act — and Governance registered it as a document precisely so it cannot be mistaken for either.

## 3 · ⭐ One difference from the earlier draft, recorded because it matters

The passage that prompted this act read: *"**Python** is the planned authoritative implementation of the language-neutral L4/L5 cohesion engine."*

**The act as issued does not name a language.** It says *"one authoritative implementation"* — leaving the implementation language of the future engine **undecided**.

**Governance records the difference rather than treating the two as the same statement.** The generalized form is the stronger one: it fixes the *architectural* target (one engine, not one per language) without pre-committing a technology choice that would then arrive un-evaluated. **Nobody should later read "Python" into this direction; it is not there.**

## 4 · The retirement condition, stated precisely

The existing PHP calculator may be retired **only after** a language-neutral authoritative engine is **independently verified for semantic equivalence against the declared conformance specification**.

Note what this condition is measured against: **the declared conformance specification** — not against the PHP calculator's own output. This is the adopted conformance method applied to the retirement decision itself: *implementation agreement is diagnostic only and is not a conformance authority.* **A future engine that merely reproduces the PHP calculator's results would not satisfy this condition.**

## 5 · Relationship to the current design — no change required

The selected Architecture D already separates the language binding from the L4/L5 model, and `IMPL-ARCH-AMD1` requires that separation to survive even though only one binding exists. **The direction is therefore already served by the current design; it imposes no new requirement on the implementation architecture now in progress, and Governance did not amend that grant.**

*One thing worth keeping visible: this direction is only reachable if the L4/L5 engine stays genuinely separable from the PHP binding. That is exactly what the principle "PHP is the first language binding, not the definition of the language-neutral cohesion model" protects.*

**Traceability:** the PO/ARB act 2026-08-18 · language-scope registration + `IMPL-ARCH-AMD1` (binding/model separation) · `IMPL-ARCH-AMD2` (declared-specification conformance; agreement is diagnostic only) · Architecture D selection · Decisions 13.1/13.7
