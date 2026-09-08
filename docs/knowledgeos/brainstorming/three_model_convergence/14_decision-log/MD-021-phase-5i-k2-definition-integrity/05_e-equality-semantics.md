# Phase 5I — `e_equality.py` Executable-Semantics Audit

## A. Object construction

**The only one of the three scripts to construct actual data instances.** `A(P,e,c,t,Pi)` returns a
Python `dict` with keys `P,e,c,t,Pi,id`, where `id = H(P,e,c,t,Pi)` (a truncated SHA-256 hash of the
other five fields, via `json.dumps(...,sort_keys=True,default=str)`). Two concrete instances are built:
`a_scan` and `a_vendor`, identical in every field except `Pi` (`"origin:scan"` vs. `"origin:vendor"`).

## B. Equality

Four distinct, **genuinely computed** equality functions are defined:
- `eq_struct(x,y) = (x==y)` — literal Python equality on the state sets (sets of assertion IDs).
- `eq_semantic(x,y)`: projects each state to `{(m["P"],m["c"],m["t"]) for m in ... if m["id"] in k} |
  (k & {"a0"})` — **a real, executed transformation**, discarding `id`, `e`, and `Pi` from the
  comparison.
- `eq_obs(x,y,queries) = all(q(x)==q(y) for q in queries)` — a real higher-order function, genuinely
  executed against whatever `queries` list is passed.
- `eq_prov(x,y) = eq_semantic(x,y) and prov(x)==prov(y)` where `prov` extracts the `Pi` field — real,
  executed.

## C. Identity

`id = H(P,e,c,t,Pi)` is **explicitly a derived, content-addressed identifier, not an independent
field** — confirming this phase's own `01` register entry that `id` is *derived* in this script, in
contrast to D285-6's own prose, which lists `id` as one of the *named fields* of the unpacking
(`{id,c,t,Π}`) without indicating it is derived rather than primary. **This is a fourth, newly-found
discrepancy**: D285-6's prose treats `id` as a peer field alongside `c,t,Π`; the executable code treats
it as a computed hash *of* the peer fields (including `P` and `e`, which D285-6's own `{id,c,t,Π}`
grouping does not even list alongside it).

## D. Projection

No `π_K`-style K-1→K-2 projection is implemented in this file at all — it operates entirely within
K-2's own internal equality-relation question (Reviewer B's mandate E3/E4/E5), not the K-1↔K-2
question. **This file is not directly about the K-1→K-2 projection**, despite being cited as evidence
for it in Phase 5F/5G/5H — its relevance is *indirect*, via its `A(...)` constructor's field set, which
those phases used to help identify `e`.

## E. Query space

Two: cardinality (`QUERIES[0] = lambda k: len(k)`) and a **defined-but-unused, vacuous second query**
(`QUERIES[1] = lambda k: sorted(k)==sorted(k)`, comparing `sorted(k)` to itself — always `True`
regardless of input). **Re-confirmed this phase by direct re-execution**: only `QUERIES[0]` is ever
passed to `eq_obs` (line 39, `eq_obs(K1,K2,[QUERIES[0]])`) — `QUERIES[1]` is dead code, not an active
defect in the printed result. The printed observational-equality claim is honestly scoped in its own
comment ("equal under cardinality-only observation") — a narrower, more defensible claim than a
general "observationally equivalent" would be.

## F. Information loss

Not this script's own subject; its E4/E3/E5/E6 sections instead establish a genuinely valuable,
independent finding: that `delta(K,o1)=delta(K,o2)` does **not** imply `o1=o2` in general, and that
D285-5's own non-injectivity claim holds **only** under semantic equality (discarding `Pi`), not under
the corpus's own provenance-sensitive relation `D`. This is real, re-executable mathematics.

## G. Failure behavior

No error handling; if `a_scan`/`a_vendor` were missing a field, `H(...)` would still hash whatever
`None`/missing value was passed (Python's `json.dumps(default=str)` handles this gracefully but
silently) — no validation exists.

## H. Hidden assumptions

1. That `P` (a nested tuple like `("svc","D.tls","1.3")`) adequately represents "Proposition" without
   further internal structure being tested.
2. That excluding `id`/`e`/`Pi` from `eq_semantic`'s own projection correctly captures "same knowledge
   semantics" — the script's own comment discloses this is a **choice**, not a proof ("Decision 3 is
   OPEN").
3. That the single worked witness (`a_scan` vs. `a_vendor`, differing only in `Pi`) generalizes to the
   general claims drawn from it — the script itself is careful about this in E5 ("This establishes...
   NOT... a property of delta. It is a property of delta COMPOSED WITH that quotient"), an example of
   the corpus's own research correctly avoiding overgeneralization from one witness.

## Verdict

**The most rigorously executed of the three scripts**, and the only one to construct real data
instances with a real hash-based identity function. Its own `id` treatment reveals a fourth,
previously undisclosed discrepancy with D285-6's prose (`id` as derived vs. `id` as a peer field).
