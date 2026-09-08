# Phase 5J — Π/π Denotation Adjudication (now 3 sources, up from 2 in Phase 5I)

## Evidence, restated with the new source

| Source | Symbol | Usage | Denotation |
|---|---|---|---|
| D285-6 §3 | `Π` (capital, unexpanded) | One of 4 technical fields (`{id,c,t,Π}`) | Glossed elsewhere in the same broader corpus tradition as "Policy" (per this reconstruction's own prior citations) |
| `e_equality.py` | `Pi` (code variable) | A constructor parameter, populated with `"origin:scan"`/`"origin:vendor"` in worked examples | **Provenance-shaped content** — a tag naming where an assertion came from |
| Step 272B §272A.18 (**new, this phase**) | `\Pi` (capital, exactly 1 occurrence) and `\pi` (lowercase, 4 occurrences) | `Assess:(A,E,\Pi,C,\pi)→AssessmentResult`; lowercase `\pi` elaborated as "the policy... determines what counts as sufficient support or refutation" | Lowercase `\pi` = **Policy**, confirmed by direct elaboration; capital `\Pi`'s own denotation is **not separately elaborated** anywhere in the 2,126-line document |

## Testing the required A–F classification

- **A. Π = Policy**: supported by D285-6's own gloss and by the *pattern* of Step 272B's lowercase
  `\pi` usage (though not by its capital `\Pi`, which is never itself glossed).
- **B. Π = Provenance**: supported only by `e_equality.py`'s own concrete worked-example content — the
  single most concrete, executable piece of evidence available, arguably outweighing an unglossed
  symbol occurrence.
- **C. Π changed meaning over time**: **not evidenced** — no document states this, and the dating
  evidence (`04`) cannot establish which source is "later" with enough confidence to support a
  meaning-drift story.
- **D. Policy and Provenance were conflated**: **plausible** — this reading would explain why one
  source's own worked *content* looks like Provenance while its neighboring prose glosses the *symbol*
  as Policy; a single author under time pressure conflating two similarly-structured "where does this
  come from / under what rule does this operate" concepts is a natural, if unproven, explanation.
- **E. Two different variables were given the same notation**: **also plausible**, and not
  distinguishable from D on the evidence available — both D and E predict the same observed pattern
  (the symbol looks like it means one thing in prose, another in code).
- **F. Unresolved**: **the correct final verdict** — D and E are the two best-supported readings, and
  this phase cannot discriminate between them; A and B are each individually evidenced but mutually
  exclusive as stated, and C is not evidenced at all.

## Verdict

**F — UNRESOLVED**, with the specific finding that the balance of *prose* evidence (2 of 3 elaborated
sources) favors "Policy" while the sole *executable, worked* evidence favors "Provenance" — **this
phase does not break the tie by majority count**, since evidence quality (a concrete worked example vs.
an unglossed symbol) matters more than evidence count, and the two are of different, not directly
comparable, kinds.
