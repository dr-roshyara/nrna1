# MD-055 §01 — Findings

## Part 1 — the unrepaired formula: `id = H(P, e, c, t, Π)`, `e.state` mutable

**Executed output** (`verify_id_mutability.py`, run twice, deterministic):

```
A1 constructed. id (before withdrawal) = 16d3e3c49ece
StructuralValid(K) immediately after asserting the relation: True (ok)

A1 evidence withdrawn (state: active -> withdrawn).
id (after withdrawal)  = ab5c2a9955bb
SAME id before/after?  = False

StructuralValid(K) AFTER the legal withdrawal operation: False
  (dangling endpoint: relation target id '16d3e3c49ece' not in K.A)
```

**Result: CONFIRMED.** The contradiction reproduces exactly as MD-054 §02 recorded it, under an
independent, clean-room implementation of only the stated definitions — not the source programme's own
code, not trusted from its self-report. A single stated-legal operation (evidence withdrawal, which the
source material itself lists among the required capabilities) changes an assertion's own
content-addressed identity, and a previously-valid relation edge referencing the old identity is left
dangling. `StructuralValid(K)` — itself one of the theory's own stated invariants — fails immediately
afterward.

**What this establishes**: the contradiction is not an artifact of the source programme's own
scratchpad implementation, and not merely asserted — it follows directly and mechanically from the
stated definitions (`id`'s formula; `e.state`'s declared mutability; `StructuralValid`'s "no dangling"
clause), independently re-derivable by anyone who implements them as written. **Evidence class: this
phase's own direct computation**, not inherited from MD-054.

**What this does NOT establish**: whether this is the *only* internal contradiction in the theory as
stated (not tested); whether a real running system built to this specification would actually exhibit
the failure at scale (not tested — this is a two-assertion, one-edge minimal witness, sufficient to
confirm the logical claim, not a stress test); anything about `K=(𝒜,ℛ)`'s other claimed properties
(minimality, the join-semilattice result, etc. — untouched).

## Part 2 — the source material's own proposed repair (TG-06)

`FINAL-THEORY-GAP-REGISTER.md` (admitted, MD-054) proposes: `id = H(P, {e.ref}, c, t, Π)` — project the
mutable `state` field out of the hash, keeping only the (immutable) evidence *reference* set.

**Executed output** (`verify_tg06_repair.py`):

```
id before withdrawal            = 1496dc466d1a
id after withdrawal (state only)= 1496dc466d1a
id after a NEW evidence ref     = c952353b0b21

(a) stable under state-only withdrawal? True  (want True)
(b) changes under genuine new evidence?  True  (want True)
```

**Result: CONFIRMED SOUND for the specific failure mode tested.** The repair is stable across a
state-only mutation (withdrawal no longer changes the assertion's identity) while still correctly
producing a different identity when the evidence reference *set* itself changes (a genuine content
change that identity should track). This is not a vacuous repair that merely stops tracking evidence
at all — it discriminates correctly between "the same evidence, differently qualified" and "different
evidence."

**What this does NOT establish**: MD-054 §02 (citing `KNOWLEDGE-STATE-FINAL-AUDIT.md` §3.2) already
recorded a **separate, still-open** defect for `merge`/deduplication — the same fact observed twice
under different provenance still produces two distinct assertions, because `Π` remains inside the hash
even under the TG-06 repair. **This phase's probe does not test that claim and does not resolve it.**
The repair fixes the specific contradiction this phase set out to test; it is not a general adequacy
proof for `K=(𝒜,ℛ)`'s identity model.

## Classification

| Claim | Status |
|---|---|
| The `id`/mutable-`e.state` contradiction, as stated in the admitted material | `CONFIRMED` — independent computation, this phase |
| TG-06's repair resolves the specific withdrawal-triggered failure | `CONFIRMED SOUND` — independent computation, this phase, for the tested case only |
| TG-06's repair resolves the separate merge/dedup defect (KNOWLEDGE-STATE-FINAL-AUDIT §3.2) | `NOT TESTED` — remains open, per MD-054's own record |
| `K=(𝒜,ℛ)`'s other properties (minimality, join-semilattice, etc.) | `UNTOUCHED` — out of scope this phase |
