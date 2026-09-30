"""1al secondary (labelled post-hoc; the frozen models_1al.py output is unchanged):
 (a) VACUITY: a guard signature is NON-VACUOUS iff, for every conflicting pair of the operation under ∅, at least one signature
     variable is KNOWN in both events and differs. Signatures that 'separate' only through UNK are vacuous (open-world artefacts).
 (b) BEHAVIOUR-IRRELEVANT COORDINATES of the M3 ruling-status LTS: coordinate c is irrelevant iff every pair of reachable states
     that differ only in c lies in the same bisimulation class."""
import importlib.util, itertools, json, os
H = os.path.dirname(os.path.abspath(__file__))
m = importlib.util.spec_from_file_location("m", os.path.join(H, "models_1al.py")); M = importlib.util.module_from_spec(m); m.loader.exec_module(M)
sm, obs = M.r4_observations(); U = sm.U
L = [e for e in obs if e["ground"] != "CHOICE"]; sig = M.signatures(sm, obs); vac = {}
for o, r in sig.items():
    if not isinstance(r["signatures"], list): continue
    evs = [e for e in L if e["o"] == o]
    confl = [(p, q) for p, q in itertools.combinations(evs, 2) if (p["outcome"] == "PERFORMED") != (q["outcome"] == "PERFORMED")]
    vac[o] = {"+".join(s): all(any(p[v] != U and q[v] != U and p[v] != q[v] for v in s) for p, q in confl) for s in r["signatures"]}
# (b)
m3 = M.load("m3", os.path.join(os.path.dirname(H), "m3_check.py")); seen, trans = m3.bfs(m3.B0, m3.B_ops())
states = list(seen); succ = {k: [] for k in states}
for s, lab, t, _ in trans: succ[tuple(sorted(s.items()))].append((lab.split("(")[0], tuple(sorted(t.items()))))
part = {k: frozenset(l for l, _ in succ[k]) for k in states}
while True:
    sg = {k: (part[k], frozenset((l, part[t]) for l, t in succ[k])) for k in states}; ids = {}; new = {k: ids.setdefault(sg[k], len(ids)) for k in states}
    if len(set(new.values())) == len(set(part.values())): break
    part = new
coords = [c for c, _ in states[0]]; irr = {}
for i, c in enumerate(coords):
    pairs = [(x, y) for x, y in itertools.combinations(states, 2) if all(x[j] == y[j] for j in range(len(coords)) if j != i) and x[i] != y[i]]
    irr[c] = ("IRRELEVANT" if all(part[x] == part[y] for x, y in pairs) else "RELEVANT") if pairs else "CONSTANT (never varies)"
print(json.dumps({"labels": "SECONDARY / POST-HOC; FORMAL", "non_vacuous_signatures": vac, "ruling_LTS_coordinate_relevance": irr}, indent=1))
