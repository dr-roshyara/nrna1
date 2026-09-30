#!/usr/bin/env python3
"""Track B, pass 0: candidate structures in the ALREADY-COMMITTED rev3 S4 reconstruction objects (PB02–PB05, 20 objects).

Status of the material: agent reconstructions (rev3), unaudited, NOT S5 evidence. Every result here is at most
RECONSTRUCTED / HYPOTHESIS. No corpus text is read; only committed objects and the 02-FILES metadata (dates).
Each candidate is a predicate over the objects; the script searches for counterexamples (∃) and reports counts
(∀ over this finite set is a CHECK, not a proof). No ML: at n = 20 a rule-based baseline is the right instrument.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B ../../research/structures_v0.py
"""
import collections
import itertools
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
TESTS = os.path.join(os.path.dirname(HERE), "scripts", "tests")
sys.path[:0] = [TESTS, os.path.dirname(TESTS)]
import test_p3b_s5_verify as T              # noqa: E402  (committed-object loader)
import p3b_s5_r7_reconstruction as C        # noqa: E402  (A.10 dated positions)

SID = re.compile(r"\bS\d{4}\b")
KIND_ORDER = ("lexical", "conceptual", "formal", "operational", "governance")
objs = [o for pb in ("PB02", "PB03", "PB04", "PB05") for o in T.nonhub_ctx(pb)["objs"]]
meta = C.c.files_meta()
out = {"n_objects": len(objs)}

# H1 — the timeline is a transition system that starts in FIRST, and FIRST occurs exactly once
bad1 = [o["working_label"] for o in objs
        if not o.get("timeline") or o["timeline"][0].get("change_vs_previous") != "FIRST"
        or sum(p.get("change_vs_previous") == "FIRST" for p in o["timeline"]) != 1]
trans = collections.Counter((a.get("change_vs_previous"), b.get("change_vs_previous"))
                            for o in objs for a, b in zip(o.get("timeline") or [], (o.get("timeline") or [])[1:]))
out["H1_first_unique_initial"] = {"counterexamples": len(bad1), "transitions": {f"{a}→{b}": n for (a, b), n in trans.most_common()}}

# H2 — contradiction is acyclic and runs forward in A.10 time (contradicts ⇒ ESTABLISHED precedence)
q = collections.Counter()
cyc = 0
for o in objs:
    rel = C.contradiction_precedence(o, meta)
    q.update(rel.values())
    edges = set(rel)
    cyc += any((b, a) in edges for a, b in edges)
out["H2_contradiction"] = {"relations": sum(q.values()), "precedence": dict(q), "two_cycles": cyc}

# H3 — the label dependency graph is a DAG
g = collections.defaultdict(set)
for o in objs:
    for e in o.get("dependency_edges") or []:
        if isinstance(e, dict) and e.get("target_label"):
            g[o["working_label"]].add(e["target_label"])
nodes = set(g) | {t for ts in g.values() for t in ts}


def has_cycle():
    colour = {}

    def dfs(u):
        colour[u] = 1
        for v in g.get(u, ()):
            if colour.get(v) == 1 or (v not in colour and dfs(v)):
                return True
        colour[u] = 2
        return False
    return any(u not in colour and dfs(u) for u in nodes)


out["H3_dependency_graph"] = {"nodes": len(nodes), "edges": sum(len(v) for v in g.values()), "cyclic": has_cycle(),
                              "edges_to_labels_in_set": sum(1 for v in g.values() for t in v if t in {o["working_label"] for o in objs})}

# H4 — birth kinds form a time chain: lexical ≤ conceptual ≤ formal ≤ operational ≤ governance (A.10 file dates)
chain = collections.Counter()
for o in objs:
    d = {}
    for k, v in (o.get("births") or {}).items():
        ds = [C.file_position(s, meta) for s in SID.findall(str(v))]
        ds = [x for x in ds if x != "UNDATED"]
        if ds:
            d[k] = min(ds)
    for a, b in itertools.combinations([k for k in KIND_ORDER if k in d], 2):
        chain["consistent" if d[a] <= d[b] else "VIOLATION"] += 1
    chain["objects_with_≥2_dated_births"] += len(d) >= 2
out["H4_birth_chain"] = dict(chain)

# H5 — absence dimensions: implication structure (a closure system / formal-concept candidate) vs independence baseline
found = {o["working_label"]: frozenset(k for k, a in (o.get("absences") or {}).items()
                                       if isinstance(a, dict) and a.get("resolution") == "FOUND") for o in objs}
dims = sorted({k for o in objs for k in (o.get("absences") or {})})
n = len(found)
marg = {d_: sum(d_ in s for s in found.values()) / n for d_ in dims}
impl = []
for a, b in itertools.permutations(dims, 2):
    sup_a = sum(a in s for s in found.values())
    if sup_a >= 3 and all(b in s for s in found.values() if a in s):     # exact implication with support ≥ 3
        impl.append((a, b, sup_a, round(marg[b] ** 1, 2)))               # baseline: P(b) under independence
intents = {s for s in found.values()}
out["H5_absence_implications"] = {"dimensions": len(dims), "distinct_FOUND_sets": len(intents),
                                  "exact_implications_support_ge_3": len(impl),
                                  "non_trivial(P(b)<1)": sum(1 for x in impl if x[3] < 1.0),
                                  "examples": [f"{a} ⇒ {b} (support {s}; baseline P(b)={p})" for a, b, s, p in impl[:8]]}
print(json.dumps(out, indent=1, ensure_ascii=False))
