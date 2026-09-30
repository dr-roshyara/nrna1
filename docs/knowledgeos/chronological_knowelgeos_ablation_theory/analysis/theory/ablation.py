"""Model ablation for the candidate transition theory (F-series). Deterministic; no corpus read.
Each established OBSERVATION is a witnessed pair (or an explicit source rule) plus the model components required to represent it:
  legality pair   : two events equal except in variable x, opposite rule-grounded outcomes → needs O, G and x
  effect contrast : two operations with different stated direct effects → needs O and F
  scope/target    : an act on one target does not extend to another → needs T
  history pair    : equal current state, different enabled futures → needs H (finite summary)
  choice pair     : both options legal, one chosen by a stated principle → needs a choice layer Ch (outside δ)
  conformance     : equal on all coded variables, opposite outcome, the source names role separation → needs C (fitted)
A model FAILS an observation iff it lacks a required component. Components with no witnessing observation are UNDETERMINED
(metadata), never 'redundant'. Usage: python3 ablation.py [--selftest]"""
import json
import sys

OBS = [
 {"id": "L-authority", "type": "legality", "needs": {"O", "G", "A"}, "witness": "ADOPT by the ARB Chief (refused: 'declines … in the Chief's own favour') vs by the Decision Authority (R-86, performed)", "strength": "minimal pair"},
 {"id": "L-kind", "type": "legality", "needs": {"O", "G", "K"}, "witness": "register entry: operational acceptance withdrawn (R-90) vs constitutional decision admitted", "strength": "minimal pair"},
 {"id": "L-state-proviso", "type": "legality", "needs": {"O", "G", "S"}, "witness": "START(WP-4B) refused at R-72 ('implementation does not begin') vs performed 08-03 16:10", "strength": "minimal pair (σ coding includes an interpretation of R-78)"},
 {"id": "L-state-4act", "type": "legality", "needs": {"O", "G", "S"}, "witness": "START(WP-8) refused under permission only (R-79) vs START(WP-4B) with authorization", "strength": "minimal pair"},
 {"id": "L-evidence", "type": "legality", "needs": {"O", "G", "E"}, "witness": "R-36: RAISE 'evidence-based (PB-004·005·006)' performed vs 'NOT promoted … (1 slice)'", "strength": "minimal pair (partly a scope reason)"},
 {"id": "S-multi-coordinate", "type": "state", "needs": {"S"}, "witness": "L493: the same item documentary CLOSED and machine OPEN", "strength": "source-stated"},
 {"id": "F-accept-vs-authorize", "type": "effect", "needs": {"O", "F"}, "witness": "'closure is an ACCEPTANCE OUTCOME, NOT AN AUTHORIZATION DECISION' (R-72); acceptance closes (R-43/R-62, R-55, R-67, R-93)", "strength": "source-stated contrast + recurrent"},
 {"id": "F-freeze", "type": "effect", "needs": {"O", "F", "S"}, "witness": "L205: the freeze blocks new minting while the existing set's standing is unchanged (frame {regime}, not {standing})", "strength": "source-stated + M0 expressiveness check"},
 {"id": "T-scope", "type": "scope", "needs": {"T"}, "witness": "'WP-4C-2 is NOT authorized, not implicitly and not by adjacency' (R-89); 'FOR THIS REPAIR ONLY' (R-81); '7B and 7C are NOT authorized' (R-47)", "strength": "source-stated, recurrent (5 rows)"},
 {"id": "T-evidence-target", "type": "scope", "needs": {"T", "E"}, "witness": "'evidence about the IMPLEMENTATION, not about the decision' (R-91); intent vs mechanism (R-85); invariant vs mechanism (R-44)", "strength": "source-stated, 3 rows"},
 {"id": "H-registry", "type": "history", "needs": {"H"}, "witness": "R-90 WITHDRAWN, number RETIRED vs a never-used number: same current holder state, different futures (M0 P-markov BFS)", "strength": "source-stated + BFS witness"},
 {"id": "H-drafting-window", "type": "history", "needs": {"H"}, "witness": "in-place correction licensed 'SAME DAY, BEFORE ANY ENTRY WAS CREATED' (R-96); census: 0/71 later-day decision edits", "strength": "source-stated rule + census"},
 {"id": "Ch-choice", "type": "choice", "needs": {"Ch"}, "witness": "R-94: retain · annotate · supersede — SUPERSEDE declined ('would blur chronology'), ANNOTATE chosen", "strength": "source-stated (1 row)"},
 {"id": "C-conformance", "type": "conformance", "needs": {"C"}, "witness": "R-86 vs R-91: ADOPT by the Decision Authority, equal on all coded variables, R-91 HELD for collapsing evidence submission and review; 'Engineering does not self-certify it' (R-71)", "strength": "fitted (R-86/R-91) + 1 supporting statement"},
]
NESTED = [("K0", {"S"}), ("K1", {"S", "O"}), ("K2", {"S", "O", "G"}), ("K3", {"S", "O", "G", "F"}), ("K4", {"S", "O", "G", "F", "E"}),
          ("K5", {"S", "O", "G", "F", "E", "A"}), ("K6", {"S", "O", "G", "F", "E", "A", "K"}), ("K7", {"S", "O", "G", "F", "E", "A", "K", "T"}),
          ("K8", {"S", "O", "G", "F", "E", "A", "K", "T", "H"})]
FULL = {"S", "O", "G", "F", "E", "A", "K", "T", "H", "Ch", "C"}
UNWITNESSED = {"R (route)": "route ≡ host family in this corpus (F-LOG-0133): no route-only pair can exist", "X (exception)": "no exception-only pair observed"}


def fails(model):
    return [o["id"] for o in OBS if not o["needs"] <= model]


def selftest():
    ok = [("K0 fails every legality pair", all(i in fails({"S"}) for i in ("L-authority", "L-kind", "L-evidence"))),
          ("full model fails nothing", fails(FULL) == []),
          ("removing E breaks the evidence pair", "L-evidence" in fails(FULL - {"E"}))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    nested = {k: {"components": sorted(m), "fails": fails(m), "n_fail": len(fails(m))} for k, m in NESTED}
    single = {c: {"fails_when_removed": fails(FULL - {c}), "status": "NECESSARY" if fails(FULL - {c}) else "NOT REQUIRED BY ANY OBSERVATION"} for c in sorted(FULL)}
    first_adequate = next((k for k, m in NESTED if not fails(m)), None)
    print(json.dumps({"labels": "MODEL-DERIVED from established observations (F-LOG-0115…0138); requirement tags are the analyst's, each with its witness",
                      "nested_ablation": nested, "first_adequate_nested_model": first_adequate or "NONE (K8 still fails the choice and conformance observations)",
                      "single_component_ablation_from_full": single, "unwitnessed_components": UNWITNESSED,
                      "minimal_adequate_structure": sorted(FULL), "observations": OBS}, indent=1, ensure_ascii=False, default=sorted))
