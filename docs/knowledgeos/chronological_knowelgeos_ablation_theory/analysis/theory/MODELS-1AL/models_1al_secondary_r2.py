"""1al secondary r2 (labelled post-hoc; disclosed corrections of the secondary pass):
 (1) a conflicting pair with an UNK on the separating variables is UNDETERMINED (uninformative), not a failure;
 (2) an identity token 'same:<obj>' is known only for equality with the same token; against any other value it is UNK.
For each operation and each surviving signature: SEPARATED-KNOWN pairs vs UNDETERMINED pairs. A signature is DETERMINED iff it
has ≥ 1 known separation and 0 undetermined pairs; PARTIAL if some pairs are undetermined; VACUOUS if it separates none by known values."""
import importlib.util, itertools, json, os
H = os.path.dirname(os.path.abspath(__file__))
s = importlib.util.spec_from_file_location("m", os.path.join(H, "models_1al.py")); M = importlib.util.module_from_spec(s); s.loader.exec_module(M)
sm, obs = M.r4_observations(); U = sm.U
def known_diff(x, y):
    if U in (x, y): return None
    if (str(x).startswith("same:") or str(y).startswith("same:")) and x != y: return None
    return x != y
L = [e for e in obs if e["ground"] != "CHOICE"]; sig = M.signatures(sm, obs); out = {}
for o, r in sig.items():
    if not isinstance(r["signatures"], list): out[o] = r["signatures"]; continue
    evs = [e for e in L if e["o"] == o]
    confl = [(p, q) for p, q in itertools.combinations(evs, 2) if (p["outcome"] == "PERFORMED") != (q["outcome"] == "PERFORMED")]
    res = {}
    for S in r["signatures"]:
        sep = und = 0
        for p, q in confl:
            d = [known_diff(p[v], q[v]) for v in S]
            if any(x is True for x in d): sep += 1
            else: und += 1
        res["+".join(S)] = {"separated_known": sep, "undetermined": und, "status": "DETERMINED" if sep and not und else ("PARTIAL" if sep else "VACUOUS")}
    out[o] = res
print(json.dumps({"labels": "SECONDARY r2 / POST-HOC; FORMAL", "signature_determination": out}, indent=1))
