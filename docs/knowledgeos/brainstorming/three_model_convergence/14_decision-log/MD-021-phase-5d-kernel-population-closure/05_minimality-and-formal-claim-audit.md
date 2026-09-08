# Phase 5D — Minimality and Formal-Claim Audit (extended to the 6 new candidates)

## Method (reused from Phase 5C, not reinvented)

Three-way distinction: **(A)** mere word occurrence ("minimal"/"reduced" appears) · **(B)** informal
minimality language (calls something minimal/reduced, no formal criterion) · **(C)** formal
minimality proposition (states what is minimized, representation, admissible transformations,
constraints, equivalence relation, criterion, comparison set, test evidence). Only (C) qualifies as
a formally testable minimality claim. Model B's own representation-dependent minimality result is not
imported as evidence here (unchanged rule).

## Per-candidate audit

| Candidate | Word occurs? | Informal language? | Formal proposition (C)? | Verdict |
|---|---|---|---|---|
| NEW-OBJ-01 (K1-K8) | Yes ("kernel-reduction candidate") | Yes — called "the strongest kernel-reduction candidate yet" | **Not evidenced at this phase's inspection depth** — no stated representation/comparison-set/criterion found in the digest excerpt | **(B) — informal only** |
| NEW-OBJ-02 (DeepSeek's 4 hypotheses) | No | No | No | **(none)** — not a minimality claim at all |
| NEW-OBJ-03 (`K_OS=(A,T,P,E,I,S,X,R)`) | No explicit "minimal"/"reduced" language in the digest excerpt | No | No | **(none) at this inspection depth** — flagged for a raw-source check given its own "confirmed via direct comparison" framing; the comparison concerns *distinctness* (overlap of 3/8), not minimality |
| NEW-OBJ-04 (GN-31 ratification) | No | No | No | **(none)** — a governance event, not a minimality claim |
| NEW-OBJ-05 (`K=(K,C,T,E,A)`, "Minimal Architectural Kernel") | Yes, prominently ("Minimal Architectural Kernel," "Reduced Candidate Kernel") | Yes | **Partially evidenced**: the source states a derived-properties table (`SI=f(K,C,T)`, etc.) and a lineage function `L=History(T)` — this is closer to (C) than any other candidate found this phase, since it names what is derived from what. **However**, the digest excerpt does not show an explicit *comparison set* (what larger candidate this was reduced from, by what admissible transformations) or an explicit *equivalence relation* under which "minimal" is claimed — the raw-source spot-check (`09`) confirms the title's own framing but does not resolve whether a full (C)-level criterion is stated inside the document itself | **(B)+, the strongest informal candidate found; NOT confirmed as a full (C)-level formally tested minimality claim without further raw-source work beyond this phase's own spot-check depth** |
| NEW-OBJ-06 (`𝔎_5=(G,σ,θ,λ,π)`) | No explicit "minimal" language in the digest excerpt (the finding is about *heterogeneity*, not minimality) | No | No | **(none)** — a distinctness/structure finding, not a minimality claim |

## Extension to P1 generally (restated, not re-derived)

Phase 5C already found the word "minimal" appears in 116/116 P1 files (a corpus-wide lexical
saturation — echoed by this phase's own count of "minimal"/"reduced"-adjacent language recurring
across at least a dozen of the newly-read digest entries: 0080's "minimal trusted core," 0144's
"kernel-reduction candidate," 0856's "Minimal Architectural Kernel," among others) but only seq 0080
was previously distinguished as carrying an explicit non-literal disclaimer. **This phase adds seq
0856 as the second-strongest candidate for a genuinely formal minimality proposition found anywhere
in the P1 population** — still not confirmed at level (C), and explicitly not promoted to one.

## Verdict

**No formally tested minimality claim (level C) is confirmed among the 6 new candidates.** Seq 0856
(NEW-OBJ-05) is the single most promising candidate for further, separately authorized formal-claim
verification — flagged, not adjudicated. **This finding is scoped to the inspected population** (P1
plus targeted spot-checks) and is not asserted as a corpus-wide claim about `phase_measure_theory/`'s
remaining ~230 unsampled files.
