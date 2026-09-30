"""semobs r3 (disclosed): r2 corrections + one consistency decision + state pairs from ALREADY-READ rows (no new corpus exposure).
Consistency decision: for START, `s` = the authorization state OF THE ACT'S TARGET (not a programme-level state). R-47's two
events therefore get target-specific states (7A authorized; 7B not authorized), as R-47 itself states them.
Added events (all source-stated in rows read earlier): R-56 'Execution of 7B … has NOT been issued' / R-58 'Engineering is
authorized to begin Slice 7B RED' / R-58 'Slice 7C remains unauthorized' / R-65 'Slice 7C … engineering may begin RED'.
Outcome for these = LEGALITY (authorized / not authorized), coded PERFORMED / REFUSED for the instrument's two classes."""
import importlib.util, json, os
d = os.path.dirname(os.path.abspath(__file__))
s = importlib.util.spec_from_file_location("sm", os.path.join(d, "semobs.py")); sm = importlib.util.module_from_spec(s); s.loader.exec_module(sm)
E, U = sm.E, sm.U
obs = []
for e in sm.OBS:
    e = dict(e)
    if e["event_id"].startswith("AUTHORIZE-PLAN R-95"): continue
    if e["event_id"] == "START WP-4B 08-03 16:10": e["s"] = U
    if e["event_id"] == "START 7A after AUTHORIZE(7A)": e["s"] = "authorized"
    if e["event_id"] == "START 7B after AUTHORIZE(7A)": e["s"] = "not-authorized"
    obs.append(e)
obs += [E("START 7B at R-56 (plan approved, execution not issued)", "R-56", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="not-authorized", t="7B"),
        E("START 7B at R-58 (execution authorized)", "R-58", "register-row", "performed", "PERFORMED", "n/a", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="authorized", t="7B"),
        E("START 7C at R-58 (remains unauthorized)", "R-58", "register-row", "performed", "REFUSED", "RULE", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="not-authorized", t="7C"),
        E("START 7C at R-65 (authorized)", "R-65", "register-row", "performed", "PERFORMED", "n/a", o="START", r="EXECUTION", a="ENGINEERING", k="work", s="authorized", t="7C")]
st, po = sm.pairs(obs); V = sm.verdicts(st, po)
print(json.dumps({"n_observations": len(obs), "verdicts": {sm.NAMES[v]: {k: V[v][k] for k in ("verdict", "independent_strict", "independent_clusters", "possible_witnesses")} for v in sm.VARS}}, indent=1))
