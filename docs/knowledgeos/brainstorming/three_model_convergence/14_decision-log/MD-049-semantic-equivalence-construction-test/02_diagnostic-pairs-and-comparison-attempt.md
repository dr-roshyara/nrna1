# MD-049 §2 — Diagnostic Pairs: Attempted Comparison (Phases 3, 4)

## Step 0 — do any of the three candidates already carry the required Trace/Obs/behavior description?

Verified directly (not assumed), against the source of each candidate:

- **F1 (frozen K-1, 8-primitive tuple)**: `grep` of the entire frozen K-1/K2 decision-log record for
  `Trace(K`/`Obs_𝔠`/`⊑_𝔠`/`Beh_𝔠`/"simulation relation" → **zero hits**. F1's own characterization is
  a **governance-ratified naming act** over a tuple of primitives (per Phase 5N: naming ratified,
  object not ratified) — it has never been given an observable-behavior or trace description of any
  kind, in either the frozen record or the MinKer chain.
- **F3 (kernel-reduction C0/C0_plus)**: `grep` of the already-admitted narrative and executable
  material for the same terms → **zero hits**. F3 is genuinely **executable** (Python code exists),
  meaning it could in principle produce real trace/observable output if run — but the standing
  no-code-execution rule (repeated in every MD since MD-030) forbids running it, and its own written
  documentation (already characterized, MD-030) does not describe it in Trace/Obs terms either.
- **F5 (C1 DDD-aggregate "K-1," `KnowledgeAggregate`+`ConflictRecord`)**: this is a **narrative DDD
  architecture proposal** (entities, aggregate boundary, a Verification Port) — no executable form, no
  trace/observable description anywhere in its own source file or in any later characterization of it
  (Phase 4, Phase 6).
- **The "K_t" symbol found in the MinKer chain** (`01_...md`'s own verification): confirmed to be the
  chain's own generic epistemic-state-evolution notation (`K_t→^{E,Q,C,H}_ℐ K_{t+1}`), never connected
  to F1's own specific, governance-ratified `K_t` object — a second confirmed homonym, alongside
  `Challenge` (MD-047), not a genuine link.

**Conclusion at Step 0**: none of the three candidates has ever been described, anywhere in this
corpus, using the specific formal vocabulary (`Tr_K`, `Obs`, `Beh_𝔠`, `⪯_cap`) the comparison would
need to operate on. This is not merely "hard to find" — it was checked directly against each
candidate's own primary source and its own frozen/admitted characterizations.

## Phase 4 — the eight-question test, applied to each pre-registered pair

**(F1, F3) — frozen K-1 vs. kernel-reduction**: (1) F1's observable/required behavior — not
described in behavior terms anywhere; only a governance-ratified naming exists. (2) F3's observable
behavior — exists in principle (executable) but not accessible under the no-execution rule, and not
described in Trace/Obs terms in its own written documentation. (3) Can existing machinery represent
both — **no**, neither has an instantiation. (4)–(7) not reachable. **(8) Exact missing input: a
concrete `Obs`/`Beh_𝔠` description of F1 (an object-type conversion from "8-primitive tuple, ratified
naming only" to "observable behavior vector," which the frozen record itself never attempts), AND a
concrete `Obs`/`Beh_𝔠` description of F3 derived from its own written specification without running
its code.** Verdict: **INSUFFICIENTLY SPECIFIED.**

**(F1, F5) — frozen K-1 vs. C1 DDD-aggregate**: same structural problem from the F1 side; F5 adds its
own gap — a DDD aggregate design (entities, invariants, a Verification Port) has never been translated
into MinKer's own `Cap_K`/`Obs_K` vocabulary either, and Phase 4/6's own prior work already flagged
F1 and F5 as different *object types* (governance-ratified tuple vs. DDD aggregate design) before this
study reached the same conclusion from the trace-construction angle. Verdict: **INSUFFICIENTLY
SPECIFIED.**

**(F3, F5) — kernel-reduction vs. C1 DDD-aggregate**: F3's executable behavior (inaccessible under
the no-execution rule) vs. F5's narrative DDD design (no executable or trace form at all) — the two
candidates are not merely undescribed in the required vocabulary, they are not even the same *category*
of artifact (runnable code vs. architecture proposal). Verdict: **INSUFFICIENTLY SPECIFIED.**

## No pair reaches SEMANTIC EQUIVALENCE ESTABLISHED, ONE-WAY REFINEMENT, or even FUNCTIONAL/
STRUCTURAL CORRESPONDENCE

Every one of the three pre-registered pairs stops at the same point, for structurally the same
reason: **the required instantiation of `Tr_K`/`Obs`/`Beh_𝔠` does not exist for any of the three
candidates, and constructing one for any of them would require a new modelling decision — exactly the
kind of invention this phase (and every phase since MD-030) is bound not to make.**
