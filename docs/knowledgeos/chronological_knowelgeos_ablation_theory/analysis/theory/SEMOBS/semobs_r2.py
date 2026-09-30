"""semobs data correction r2 (disclosed): two F-LOG-0141 encodings violated schema v1's own rule (no silent inference):
  (1) R-95 'in force' was inferred from the ABSENCE of a PREPARED marker → outcome UNK (NOT-RECORDED ≠ in force);
  (2) START WP-4B 08-03 16:10 state 'auth-full' rested on an INTERPRETATION of R-78 (F-LOG-0129: I-A1 UNDETERMINED) → s = UNK.
semobs.py (the instrument) is imported read-only; only these two data values change. An event with an unknown outcome cannot
enter a witness, so R-95 is removed from the legality set rather than guessed."""
import importlib.util, json, os
s = importlib.util.spec_from_file_location("sm", os.path.join(os.path.dirname(os.path.abspath(__file__)), "semobs.py")); sm = importlib.util.module_from_spec(s); s.loader.exec_module(sm)
obs = []
for e in sm.OBS:
    e = dict(e)
    if e["event_id"].startswith("AUTHORIZE-PLAN R-95"): continue                 # outcome not established
    if e["event_id"] == "START WP-4B 08-03 16:10": e["s"] = sm.U                  # state rests on an interpretation
    obs.append(e)
st, po = sm.pairs(obs); V = sm.verdicts(st, po)
print(json.dumps({"n_observations": len(obs), "verdicts": {sm.NAMES[v]: {k: V[v][k] for k in ("verdict", "independent_strict", "independent_clusters", "possible_witnesses")} for v in sm.VARS}}, indent=1))
