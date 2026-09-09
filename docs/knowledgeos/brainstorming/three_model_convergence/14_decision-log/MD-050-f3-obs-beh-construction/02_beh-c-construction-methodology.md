# MD-050 §2 — The `Beh_𝔠` Construction (RESEARCH CONSTRUCTION — not corpus-established fact)

## The construction

F3's own already-existing, deterministic machinery (`kr/atoms.py`, `kr/carriers.py`, `kr/reach.py`,
`kr/operators.py`) supplies, verbatim:

- **Atoms** (14 total): named, semantically glossed capability-contributions (e.g.
  `A_WARRANT = "warrant-assessment"`, `A_QUALIFICATION = "evidential-qualification"`).
- **Carrier kinds** (9 ambient + 14 derived = 23 total): typed state categories (`OBSERVATION`,
  `EVIDENCE`, `CLAIM`, `VERDICT`, `GAP`, ...).
- **`DERIVATION_RULES`**: `(required input kinds, required atoms) → produced kind`, 19 explicit rules
  plus a `DISCRIMINATION` special case.
- **`reach(ops)`**: the least fixpoint of "apply one operator's atoms to the currently available
  kinds, add whatever the derivation table yields" — monotone, terminating, operator-name-independent
  by the source's own explicit design comment.

**This construction proposes**: `Beh_𝔠(K) := Reach(Ops(K))` — the set of carrier kinds reachable from
the ambient carriers using `K`'s own operator set, via the already-existing fixpoint. Then, directly
matching MinKer's own already-stated formula (MD-044/045):

$$K_1 \preceq_{cap} K_2 \iff Beh_𝔠(K_1) \subseteq Beh_𝔠(K_2) \iff Reach(Ops(K_1)) \subseteq Reach(Ops(K_2))$$

## Why this is a RESEARCH CONSTRUCTION, not an extraction

**Equating "observable behavior" with "the set of carrier kinds reachable"** is a genuine, disclosed
modelling choice — F3's own source never itself claims `Reach(ops)` IS an `Obs`/`Beh_𝔠` in MinKer's
sense; that identification is proposed here, for the first time, by this phase. It is a well-motivated
choice (both `reach.py`'s own docstring and MinKer's own `Beh_𝔠` are trying to capture "what this
system can, in principle, produce"), and every component of it is taken verbatim from F3's own already
-written specification — no new atom, carrier, rule, or operator is invented. But the identification
itself is new, and is reported as such, not as something F3's own author already asserted.

## Method: manual fixpoint tracing, not code execution

The standing no-code-execution rule was honored throughout — `reach()` was traced by hand, applying
its own stated deterministic rule mechanically, the same discipline this reconstruction has used
since MD-030 to hand-trace `DERIVATION_RULES` matches. Two independent methods were used to cross-
check the result:

1. **Round-by-round fixpoint simulation** (mechanically applying `derive_kinds` per operator, per the
   code's own stated iteration order).
2. **A simpler, more robust atom-pool/input-availability argument** (does the operator set's combined
   atom pool cover a rule's required atoms, and are the rule's required input kinds transitively
   reachable) — used specifically to cross-verify the two headline results (§3), since it does not
   depend on getting round-ordering exactly right.

Both methods agree on every result reported in `03_...md`. This is disclosed so a future check —
including simply running F3's own `reach.py`, which this phase deliberately did not do — can verify
independently.
