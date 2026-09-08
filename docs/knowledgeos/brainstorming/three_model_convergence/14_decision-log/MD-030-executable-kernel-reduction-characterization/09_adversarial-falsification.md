# Adversarial Falsification

## Attack 1 — falsify: "the executable lane is merely implementation detail and cannot materially
affect the MD-029 specification-sufficiency question"

**Falsified.** `kr/carriers.py` contains a concrete, on-point candidate answer
(`{Claim,Evidence}`/`{Hypothesis,Evidence}` → `Verdict`) to exactly the question MD-029 left open,
and `kr/capabilities.py` reveals that `Verdict` is *also* load-bearing for two capabilities
(`C16`/`C17`, uncertainty/assumptions) that no prior MD-02x artifact had connected to `Validate`
before this study. This is new, material, on-point information — the proposition as stated is too
strong and does not survive.

## Attack 2 — falsify: "the executable lane necessarily supplies the missing Validate specification"

**Also falsified — the proposition is too strong in the other direction.** Three independent
findings block it:
1. The rule is one of ≥2 tested alternatives within the same lane's own `variants.py` (`V6`) — the
   lane itself does not present it as a settled specification.
2. The rule is completely untracked in git — weaker provenance than the narrative lane already
   found insufficient for direct admission without a human decision (MD-025–028).
3. The lane's own README explicitly disclaims canonical status.
Neither proposition, as originally stated, survives — the correct position is the intermediate one
already reached in `08`: relevant, potentially necessary, not currently admissible.

## Remaining required self-attacks (per this project's own established 10-point discipline, applied
here even though the authorization named only 2 propositions to falsify)

| # | Attack | Result |
|---|---|---|
| 3 | Did this study silently treat "relevant" as "admissible"? | No — `08` keeps them explicitly separate throughout, with a dedicated section |
| 4 | Did this study silently treat "corroborated by MD-029's own construction" as "independently confirmed"? | No — `06`'s crosswalk row explicitly flags the non-independence (both readings trace to the same two admitted capability rows) |
| 5 | Did this study execute `run_all.py` or otherwise generate new computed output? | No — confirmed by the tool-call record; `05` states reproducibility as a source claim, not independently verified |
| 6 | Did this study read `06-composition-rules.md` (out of scope)? | No — confirmed; `08`'s closing section states this explicitly as an acknowledged limitation, not silently worked around |
| 7 | Did this study open `knowledgeos-sim/` or `verification/` (out of scope)? | No — confirmed by `01`'s boundary-confirmation section |
| 8 | Did this study modify any frozen MD-024–029 artifact or `classification-register.tsv`? | No — see `12`'s verification suite |
| 9 | Did this study infer authorship, execution, or governance authority beyond what the evidence directly shows? | No — `02` explicitly marks execution as "NOT DIRECTLY EVIDENCED" rather than assuming it from the presence of `results/*.json` |
| 10 | Did this study upgrade the executable lane's provenance status relative to the narrative lane's own `RECONSTRUCTED PROVENANCE`? | No — `02` explicitly finds it *weaker* (zero git history vs. a dated commit), not equal or stronger |
