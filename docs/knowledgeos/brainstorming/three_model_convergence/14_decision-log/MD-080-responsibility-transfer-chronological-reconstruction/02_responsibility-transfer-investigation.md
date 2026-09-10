# MD-080 §02 — Responsibility-Transfer Investigation

Per the mission's own §4: object identity and semantic-responsibility identity are recorded
**separately** for every candidate below.

## `Det_r`'s own responsibility: per-instance evaluation → verdict

`Det_r`'s intended responsibility (Part VI, `[Def 6.18]`): given an evaluation of a specific
requirement/instance under a contract, produce a member of a satisfaction-status space.

### Candidate 1 — `Standing(p)` (`M0127`, 2026-09-02, four days before T21)

**Formal definition, verified directly this phase**:

```
Standing(p) = (S⁺(p), S⁻(p), R(p), P(p), Ctx(p), Cond(p))
```

— positive support, negative support, Reason (typed: `{Direct,Source,Rule,Observation,Interpretation}`),
Provenance, Context, Condition. Already, in this reconstruction's own earlier (pre-MD-067) work
(MD-061–063), empirically tested and found to preserve 12 of 14 adversarial scenarios (vs. 2/14 for a
classical Boolean collapse, 8/14 for a four-valued FDE collapse) — the richest, most rigorously tested
per-proposition evaluator anywhere in this entire object family, corpus-native, and **materially
stronger evidence than `Eval`/`EvalReq`/`Det_r` ever received** (zero invocations, MD-076).

**Object identity**: `RELATED OBJECT` to `Eval`'s own worked-example output vector
`⟨Support,CounterSupport,Uncertainty,Conflict,Dependencies,Assumptions,Justification⟩` (Part VI) —
structurally close (support/counter-support pair; reason/provenance/context/condition vs.
justification/dependencies/assumptions), but never source-stated as the same object, and operating over
a proposition `p` rather than a requirement `r`.

**Semantic-responsibility test**: does `Standing(p)` perform the responsibility `Eval`/`EvalReq`/`Det_r`
were meant to provide (produce a usable verdict-bearing structure for a given item, from evidence)?
**Yes, functionally** — `Standing(p)` is a complete, tested, corpus-native answer to essentially the
same question `Eval`'s own output vector gestures at.

**Was the responsibility transferred to T21?** **No — confirmed by direct grep, zero occurrences of
`Standing(` anywhere in any of the 21 T21 parts.** T21 does not cite, extend, reuse, or even acknowledge
`Standing(p)`'s existence. This is not a case of "the theory moved the responsibility forward under a
new name" — it is a case of **independent, non-cumulative re-invention**: T21's own `Eval`/`EvalReq`
apparatus appears to reattempt, four days later, essentially the same job `Standing(p)` had already
done more rigorously, without building on it. Classification: `DEFINED ELSEWHERE (temporally prior),
NOT CARRIED FORWARD` — the responsibility was completed once, then abandoned, then reattempted less
successfully.

### Candidate 2 — `kos/inquiry.py`'s own `Sat(K,r,E)` and `kos12`'s `Sat_c`/`Eval_c`

Already established (MD-078/079): both perform closely analogous responsibility (turning evidence
about a requirement into a satisfaction verdict), both are genuinely executable and tested, **neither
is demonstrated to map onto T21's own `r`/`EC`/`Γ`** (MD-079 §03). Semantic-responsibility verdict:
**performs the same general responsibility, via a self-contained, differently-typed alternative
system — not a transfer into T21's own apparatus, a parallel, independent solution.**

### Candidate 3 — `Warrant` / "surviving a defeater" (MD-037–041, reused, not redone)

This reconstruction's own much earlier work (MD-037: full read of 17 files in one research-document
series) already exhaustively searched for exactly this kind of object — a formal test of whether a
conclusion survives a challenge/defeater — and found: **"no formal definition, invariant, or derivation
rule for 'surviving a defeater' was found anywhere... the term is used consistently but never cashed out
operationally."** MD-038–041 extended this search across the same series' own sibling documents,
confirmed the same result, and found the series' own proposed "Semantic Kernel Equivalence" framework
would itself need the missing definition as an *input*, unable to supply it. **This finding is reused,
not redone, per the mission's own "reuse the frozen baseline" instruction.** `Warrant` is not a
responsibility-successor candidate — it never reached formal-object status itself.

### `Det_r`'s responsibility — conclusion

**`GENUINELY UNDEFINED AFTER CENSUS` is too strong a classification for the responsibility as a whole.**
The correct classification, per the mission's own §5 vocabulary, is **`DEFINED ELSEWHERE` (temporally
prior, `Standing(p)`) `AND` `DEFINED IN MULTIPLE STAGES, NEVER RECONCILED` (the two later, independent,
executable-research attempts) — but never carried into, cited by, or demonstrated to complete T21's own
specific `Det_r`/`EvalReq` formulation.** The responsibility has been discharged, more than once, by
different objects, at different times, in different lanes — but never *by* the object T21 itself
introduced to discharge it.

## `EC.Rules`'/`standard`'s own responsibility: a checkable acceptance/policy rule

### Candidates investigated

- **`AcceptanceCondition`**: zero occurrences anywhere (MD-078, reconfirmed).
- **`Warrant`**: no formal definition anywhere (MD-037–041, reused).
- **`Assessment(...)`**: found, extensively, but verified this phase (direct source read) to be
  **external-literature-extraction vocabulary**, not a native KnowledgeOS formal object — each
  occurrence belongs to a distinct paper-extraction file analyzing a *different* external philosopher's
  own framework through a "KnowledgeOS lens" (`Assessment(H|E,S,C)` in a Titelbaum extraction;
  `Assessment(E,H,...)` in an I.J. Good extraction; `Assessment(A,S^epi,Mode)` in a Rice
  legal-argument extraction) — each paper's own borrowed notion, mutually inconsistent argument lists
  (confirming, not contradicting, this pattern being *external* rather than *native*), never presented
  as a KnowledgeOS-internal `EC.Rules` candidate by any of the source documents themselves.
- **`Verdict(`**: zero occurrences as a formal function anywhere in the corpus (direct grep, this
  phase).
- **`criterion`/`validation rule`**: bare-word presence confirmed in 35 `kernel/` and 58 `verification/`
  files (grep count, this phase) — not individually opened this phase; this reconstruction's own
  extensive prior work in exactly these lanes (MD-042–057, dozens of files read in full for precisely
  this kind of object) never surfaced a formal, typed, `EC.Rules`-shaped criterion object; treated as
  corroborating, not dispositive, negative evidence.

### `EC.Rules`'s responsibility — conclusion

**`GENUINELY UNDEFINED AFTER CENSUS` stands** — this is the one component of the whole family where
the stronger classification is warranted, now on a broader evidentiary base than GAP-001 (MD-068) or
MD-078 alone supplied: no corpus-native formal object, anywhere, in any searched lane, at any point in
the corpus's chronology, performs this specific responsibility. The one genuinely close candidate
(`Standing(p)`'s own `Cond(p)` field, "Condition") is a single field inside a *different* object
(addressing per-proposition evaluation, not contract-level policy), not itself a policy/rule structure.

## Summary table

| Responsibility | Held by (T21-native) | Discharged elsewhere? | Transferred into T21? |
|---|---|---|---|
| per-instance evaluation → verdict | `Eval`/`EvalReq`/`Det_r` (zero invocations, no body) | **Yes** — `Standing(p)` (tested, abandoned before T21); `kos/inquiry.py`'s `Sat`; `Sat_c`/`Eval_c` (both post-hoc, un-mapped) | **No** — none cited or reused by T21 |
| contract-level acceptance/policy rule | `EC.Rules`/`standard` | **No** — exhaustive multi-lane, multi-era search finds nothing | n/a |
| proposition-level aggregation, given `Sat` | `Det(K,p,EC,Γ)` | **Yes, twice, independently, within T21 itself** (Part III, Part 21) — a genuine, robust `A`-classification result | n/a — never left corpus-native, never needed transfer |
| `Determination⇏Decision` separation | multiple T21 parts | **Yes, ≥4 times, independently, within T21 itself** | n/a |
