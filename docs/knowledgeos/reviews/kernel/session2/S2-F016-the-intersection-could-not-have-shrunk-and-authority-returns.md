# S2-F016 · The three-list intersection could not have shrunk — and C's return of `Authority` undercuts the instability reading

**SOURCE SESSION-1 ARTIFACT** — `S1-F005`, Finding 2 · cross-checked against `S1-F003` and `S1-F002`.

**FINDING CLASS** — CHALLENGE + NEW FINDING (consequence the source did not draw)

---

## PART 1 · "Intersection unchanged by C" was near-guaranteed

### OBSERVATION

`S1-F005` reports *"Intersection of all three remains `{Evidence, Provenance}` — unchanged by C."* The arithmetic is correct. The **result is not informative**, because C could barely have changed it.

### EVIDENCE

From the artifact's own table:

```
A  = {Evidence, Provenance, Authority, Lifecycle}
B  = {Evidence, Provenance, Observation, Assessment, Result, Rule-evaluation}
C  = {Evidence, Provenance, Authority, State, Identity, Transition, Time, Invariant}

A ∩ B       = {Evidence, Provenance}
A ∩ B ∩ C   = {Evidence, Provenance}
```

### ANALYSIS

Adding a third set to an intersection can only shrink it or leave it unchanged — **never grow it.** So the only way C could have altered the result was by *omitting* Evidence or Provenance. C contains eight members drawn from the same vocabulary and includes both. The reported "unchanged" is therefore the outcome of a test with almost no way to fail.

Stated informatively, the finding is: **"C contains both members of the existing intersection."** Stated as *"the intersection remains,"* it implies a survival test that had a real chance of failing and passed — which lends the pair `{Evidence, Provenance}` an appearance of stability it has not earned. Those two are the most generic terms available; they would survive intersection with nearly any list of epistemic nouns.

`S1-F005` does carry the right caution — *"⚠ an intersection of historical proposals is not a minimality result and not a conclusion about the Kernel"* — and I confirm it. My point is narrower: even as a *descriptive* statistic, "unchanged" reports the absence of an effect that had no mechanism to occur.

⚠ And per `S2-F007`, if the three lists answer different questions the intersection is **ill-typed** regardless of its stability. `S1-F005` supplies fresh support for that reading — see Part 2 and the note appended to `S2-F013`.

---

## PART 2 · `Authority` goes A ✔ → B ✘ → C ✔, and the source does not draw the consequence

### OBSERVATION

`S1-F005` notes that C *"reintroduces Authority, which B had silently dropped (see S1-F003)."* It records the fact and stops. **The consequence runs against `S1-F003`'s and `S1-F002`'s own framing, and is not stated.**

### EVIDENCE

`Authority` across the three lists, in chronological order:

| | A `20:32` | B `21:08` | C `00:21` |
|---|---|---|---|
| **Authority** | ✔ | **✘** | ✔ |

### ANALYSIS

`S1-F003` reads B's omission of `Authority` as evidence of divergence/instability in the Kernel member vocabulary. But instability predicts **drift** — a term dropped and staying dropped, or replaced by a rival term. What the three lists show is a term present, absent once, then present again with no rival in the gap.

A single-list absence flanked on both sides by presence is more economically explained by **B being a partial answer to a different question** — B enumerates `Observation · Assessment · Result · Rule-evaluation`, none of which is an authority concept, and all four of which are absent from A *and* from C. B is not a variant of A and C; **B is the outlier of the three, and it is outlying by content type, not by vocabulary choice.**

That reading is `S2-F007` / `S2-F010`, and `S1-F005` has now supplied independent support for it without noticing — the same artifact that adds a third list also supplies the pattern that undercuts the instability interpretation of the second.

⚠ **I do not resolve `S1-F003`.** Its status stands as Session 1 recorded it. Two readings are now live: *vocabulary instability* (Session 1) and *different questions* (`S2-F007`). Part 2 is evidence that shifts weight toward the second; it does not settle it, and settling it needs the corpus.

### WHAT SESSION 1 MISSED

A **cross-artifact consistency check**. `S1-F005` cites `S1-F003` twice and inherits its caution, but never asks whether its own new data changes `S1-F003`'s conclusion. Each finding is internally careful; the set is not checked against itself. That is a structural gap in the extraction method, not an error in any one document.

---

## WHY IT MATTERS

Part 1 keeps a non-result from hardening into a "stable two-member core" that later work would treat as established. Part 2 is the more important half: **new evidence arrived that bears on an earlier finding, and the earlier finding was cited rather than re-tested.**

## LENS

Lens 2 (Zero — B's missing `Authority`: dropped, or never in scope for B's question?) · Lens 7 (altitude/type — B's four members are a different kind of thing) · Lens 1 (DDD — one concept, or three answers to three questions?) · Lens 6 (an absent effect reported as a stable result).

## POSSIBLE IMPACT

**On the extraction** — recommend the intersection be reported as "C contains both members of the existing intersection," and that `S1-F003` be re-examined in light of `Authority`'s A✔ B✘ C✔ pattern. Both are Session-1 decisions; I recommend, and change nothing.

**On adjudication** — none. `W:C-7` is named in `S1-F005`'s relevance list; nothing here is routed to it.

## PROVENANCE

Part 1 is independent (set arithmetic on the artifact's own table). Part 2 extends `S2-F007` and `S2-F010`, which are my own prior findings — **so Part 2 does not corroborate them**; it is the same reading meeting new data.

## STATUS

**OPEN.** Intersection arithmetic confirmed, informativeness contested · `S1-F003` untouched, with weight shifted · cross-artifact consistency gap recorded as a method observation.
