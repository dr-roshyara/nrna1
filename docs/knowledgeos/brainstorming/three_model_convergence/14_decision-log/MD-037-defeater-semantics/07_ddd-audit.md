# DDD Audit — `Defeater`

## Is `Defeater` evidenced as a carrier/type label, value object, entity, aggregate member, domain
event, domain-service input, invariant-bearing concept, or notation only?

**Carrier/type label, consistently across every document in the series — no promotion found
anywhere.** `13-ddd-analysis.md` (the series' own dedicated DDD document) types it only as the
output of `Challenge`'s own row (`Claim → Defeater`), with no lifecycle, identity, or independent
behavior stated. `11`'s own input-requirement passage treats it the same way. Not capitalized-into-
significance anywhere; the series' own consistent operator/atom/carrier/capability taxonomy (already
established in MD-033/034/036) governs `Defeater` exactly as it governs every other carrier.

## Does "surviving a defeater" change domain responsibility, command semantics, aggregate
invariants, lifecycle, or bounded-context meaning — or only the mathematical/type-system layer?

**Only the type-system layer, confirmed again by the wider series.** `18` §5.3's own `I9` discussion
is the single place the series comes closest to an invariant-flavored treatment of `Defeater`/
`Verdict` — and even there, it is framed explicitly as an analytical **cost dimension for evaluating
candidate kernel reductions** ("`custody(I,K)`... a candidate reduction must report... for every
invariant it claims to preserve"), a methodological instrument for this experiment's own comparative
analysis, not a domain-model invariant asserted about KnowledgeOS itself. No document treats
`Defeater`, `Challenge`, or the survival requirement as bearing on aggregate boundaries, command
dispatch, or lifecycle.

## `13`'s own aphorism, reconsidered DDD-wise

*"unchallenged ≠ validated"* (`13` line 16) is a compact domain-rationale statement, not a
formalized DDD artifact (no aggregate, invariant, or business rule identifier attached to it
anywhere in the series). It functions as a one-line design justification, the same register as a
code comment explaining *why* a rule exists — useful context, not a DDD construct in its own right.

## Net finding

Unchanged from MD-036 `08`: the V0→V6 difference, and everything the wider series adds about
`Defeater`, remains confined to the type-system/derivation-table layer. No DDD promotion is
warranted by anything found in this study.
