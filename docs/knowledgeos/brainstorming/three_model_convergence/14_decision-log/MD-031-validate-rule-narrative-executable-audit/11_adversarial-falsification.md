# Adversarial Falsification

## H1 — `06-composition-rules.md` independently specifies the executable `Validate` rule

**Succeeds, with a precise qualification.** "Independently" is doing two jobs in this hypothesis and
they must be separated: (a) does `06` specify the rule *without relying on the executable code to
fill gaps*? Yes — confirmed by `03`'s cold read. (b) does `06` specify it *independently of the
executable code's own authorship*, i.e. as a second, separately-derived confirmation? **No** — `06`
of `provenance-and-temporal-analysis.md` classifies the relationship as `CONVERGENCE WITH
COMMON-CAUSE PROVENANCE`, not independent confirmation. **H1 succeeds under reading (a), fails under
reading (b).** Both readings are reported so neither is silently chosen.

## H2 — the executable rule merely reflects an implementation choice not established by the
narrative source

**Fails, given (a) above** — the narrative source does state the same rule, in prose, with its own
explanatory reasoning (the `Claim`/`entailment` restriction; the `Qualify`/`Evidence` dependency).
It is not merely a private implementation detail invisible to the narrative lane.

## H3 — the apparent convergence is caused by a common source rather than independent agreement

**Succeeds** — this is exactly `06`'s own finding: near-simultaneous mtimes (5ms), no cross-citation
in either direction, identical content down to explanatory prose, same numbered-document-series
membership. The most parsimonious account is common-cause, not independently arrived-at agreement.

## H4 — the two rules actually encode materially different semantics

**Fails**, per `05`/`09` — every checkable dimension matches exactly, at both the structural and
mathematical level (identical domain, codomain, and mapping for the shared baseline rule). No
semantic difference was found between `06`'s stated rule and `carriers.py`'s baseline (`V0`) rule.
**A material semantic difference does exist between `06` and the executable lane's `V6` variant** —
but `06` never engages with `V6` at all (`07`), so this is not a case of `06` contradicting the
executable lane; it is a case of `06` being silent about part of what the executable lane contains.

## Summary

| Hypothesis | Result |
|---|---|
| H1 (narrative independently specifies) | Succeeds under "without using code to fill gaps"; fails under "independent confirmation" |
| H2 (executable-only, not narratively established) | Fails |
| H3 (common-cause explains convergence) | Succeeds |
| H4 (materially different semantics) | Fails, for the baseline rule; not tested against `V6` since `06` doesn't address it |
