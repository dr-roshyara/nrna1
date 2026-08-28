# S2-F014 · "C maps far more closely than A or B" — no measure is defined, and no null hypothesis is tested

**SOURCE SESSION-1 ARTIFACT** — `S1-F005`, Finding 3.

**FINDING CLASS** — CHALLENGE + TYPE ERROR

---

## OBSERVATION

`S1-F005` Finding 3 claims list C *"maps onto that far more closely than A or B do."* The claim is **unmeasured** in three independent ways: no similarity measure is defined, no control list is tested, and the mapping crosses element types.

## EVIDENCE

From `S1-F005`'s own table, and from v1.1 (`20260822-1402`) which Finding 3 itself nominates as the comparison target:

**v1.1's eleven items are all invariants — properties the model must satisfy:**
`AGENCY · AUTHORITY · CONTRADICTION · DECISION · DIMENSION · FAILURE · HISTORY · IDENTITY · PROJECTION · UNKNOWN · VERIFICATION`.
There is **no `PROVENANCE` invariant, no `TIME` invariant, no `STATE` invariant and no `TRANSITION` invariant.**

**C's eight items are primitives — things the kernel is made of:**
`Identity · Evidence · Authority · State · Transition · Provenance · Time · Invariant`.

## ANALYSIS

### (a) The mapping is not type-preserving

`S1-F005` asserts *"identity, state, transition, provenance/history, authority and invariant all appear."* Audited item by item:

| C primitive | What it is claimed to match | Same element type? |
|---|---|---|
| Identity | `INV-KOS-IDENTITY-001` | ✔ clean name match |
| Authority | `INV-KOS-AUTHORITY-001` | ✔ clean name match |
| Provenance | `INV-KOS-HISTORY-001` | ✘ **slash-mapped.** Provenance (*where did it come from*) and history (*what happened to it*) are joined by the artifact's own `provenance/history` shorthand, not by any stated equivalence |
| State | v1.1's seven epistemic states | ✘ matches **model content**, not an invariant |
| Transition | the §9 gate | ✘ matches a **mechanism**, not an invariant |
| Invariant | *the category of all eleven* | ✘ **altitude collapse** — see (c) |

**Two of six mappings are clean.** The other four match a C *primitive* against a v1.1 element of a different kind. Under lens 8 this is inferring identity from similarity, and under lens 7 it is comparing across altitudes — the two operations the framework specifically forbids.

### (b) There is no null hypothesis

Take list **A** (`Evidence · Provenance · Authority · Lifecycle`) and run the same permissive mapping: Evidence → evidence/justification ✔ · Provenance → HISTORY ✔ · Authority → AUTHORITY-001 ✔ · Lifecycle → the aggregate's lifecycle ✔. **A maps 4 of 4.** C maps 8 of 8 by the same generosity.

So the two lists score **identically** on hit-rate. C is not *closer* — **C is longer.** A measure that only counts hits and never penalises misses or extras rewards list length, and every list here is drawn from the same small vocabulary of abstract epistemic nouns, so almost any such list will "map closely."

What a real closeness claim would need: a symmetric measure that charges for v1.1 elements the list omits *and* for list items v1.1 has no counterpart to, applied to all three lists. None of that is present. The claim is therefore **not weaker than stated — it is unstated**, because no quantity was ever defined.

### (c) `Invariant` sits at a different altitude from its own list-mates

Seven of C's eight primitives name *things* — Identity, Evidence, Authority, State, Transition, Provenance, Time. The eighth, `Invariant`, names a *constraint on things*. A kernel whose members include the constraints on its own members has folded two altitudes into one list.

`S1-F005` transcribes the list faithfully and does not flag this. It matters because `Invariant` is precisely the item Finding 3 leans on to establish closeness to v1.1 — and it is the item whose "match" is to the *category* of the eleven invariants rather than to any of them. **The single most load-bearing correspondence in the claim is the one that is not a correspondence at all.**

## WHY IT MATTERS

Finding 3 is the artifact's most consequential claim, because it is the one that flirts with derivation. `S1-F005` handles that risk **correctly and explicitly** — *"⚠ It does not establish that v1.1 derives from C … Recorded as UNVERIFIED ATTRIBUTION; the claim available is co-presence, not descent."* That discipline is right and I confirm it.

But the caution is applied to the wrong layer. Session 1 guarded against over-claiming *derivation* while leaving the *similarity* itself unaudited. If the similarity is an artifact of list length and cross-type matching, then there is less co-presence to explain than the finding reports — and a future reader who accepts "strikingly close, 13 hours apart" will supply the derivation inference that Session 1 carefully declined to make.

## LENS

Lens 7 (boundary/altitude — the mapping crosses element types; `Invariant` crosses inside C) · Lens 8 (identity — `provenance/history` infers identity from similarity) · Lens 6 (evidence/justification — an unmeasured quantity reported as a measurement) · Lens 4 (temporal — "13 hours apart" is a real interval, and unaffected by this finding).

## POSSIBLE IMPACT

**On the extraction** — Finding 3's *interval* stands (00:21 vs 14:02, verifiable). Its *closeness ranking* does not, pending a defined measure. The UNVERIFIED ATTRIBUTION status stands and should not be strengthened.

**On adjudication** — none. This concerns how a research artifact measures similarity. Not routed to any Kernel question.

## PROVENANCE

Independent. Derived from `S1-F005`'s own table plus verification of v1.1's invariant identifiers. Does not descend from any prior Session-2 finding — though (a) applies the same type discipline as `S2-F007`, which it does not cite as support.

## STATUS

**OPEN.** Interval confirmed · closeness ranking unmeasured · four of six mappings cross element types · `Invariant`'s altitude unflagged by the source.
