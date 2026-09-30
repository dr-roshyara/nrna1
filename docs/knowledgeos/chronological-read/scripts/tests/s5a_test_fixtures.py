"""Shared synthetic fixtures and a tiny runner for the S5a tests (no repository data, no sealed material)."""
import os
import random
import sys
import traceback

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
CR = os.path.dirname(SCRIPTS)
if SCRIPTS not in sys.path:
    sys.path.insert(0, SCRIPTS)
os.chdir(CR)                     # the S3 module resolves the repository root with `git rev-parse` from the cwd

import p3b_s5_common as C  # noqa: E402
import p3b_s5a_generators as G  # noqa: E402

ROWS = ("1", "2-3", "4-9")
PAIRS = ("0", "1", ">=2")
SPANS = ("<1d", "1-7d", ">7d")


def synthetic_ctx(n=48, seed=7):
    rnd = random.Random(seed)
    labels = [f"lab-{i:02d}" for i in range(n)]
    files = {f"S{i:04d}": {"source_id": f"S{i:04d}", "provenance": "PRIMARY"} for i in range(1, 13)}
    files["S0009"]["provenance"] = "SECONDARY-SYNTHESIS"
    degree = {s: 3 for s in files}
    degree["S0010"] = 12                                   # above the H-18 cap
    del degree["S0011"]                                    # a discovery file cited by no row
    shapes = [{"domain": "A x B", "codomain": "Boolean", "arity": 2},
              {"domain": "S_t, Event", "codomain": "S_{t+1}", "arity": 2},
              {"domain": "Q", "codomain": "2^S", "arity": 1}]
    rows, bands = {}, {}
    for i, lab in enumerate(labels):
        rs = []
        for j in range(rnd.randint(1, 3)):
            s = rnd.choice(["S0001", "S0002", "S0003", "S0004", "S0005", "S0009"])
            ts = shapes[i % 3] if i % 4 == 0 else None
            rs.append({"line": 1000 + 10 * i + j, "source_id": s, "anchor": f"anchor {lab} {j}",
                       "statement": f"statement about {lab} number {j}", "type_signature": ts})
        if i in (5, 6):                                    # rows only from the above-cap file -> fail the filter
            rs = [{"line": 5000 + i, "source_id": "S0010", "anchor": "a", "statement": "s", "type_signature": None}]
        rows[lab] = rs
        bands[lab] = {"row": rnd.choice(ROWS[:2]), "pair": rnd.choice(PAIRS[:2]), "prov": "all-PRIMARY",
                      "span": rnd.choice(SPANS[::2]), "degree": "<=5",
                      "has_formal_rows": any(r["type_signature"] for r in rs)}
    groups = [
        {"group_id": "G0001", "kind": "EXACT-STRING-REUSE", "members": ["lab-01", "lab-02"]},
        {"group_id": "G0002", "kind": "POSSIBLY-RELATION", "members": ["lab-03", "lab-04", "lab-07"]},
        {"group_id": "G0003", "kind": "CO-OCCURRENCE", "members": ["lab-05", "lab-08"]},       # lab-05 fails filter
        {"group_id": "G0004", "kind": "CO-OCCURRENCE", "members": ["lab-09", "lab-10"]},
        {"group_id": "G0005", "kind": "STRING-SIMILARITY", "members": ["lab-06", "lab-11", "lab-12"]},  # fails
        {"group_id": "G0006", "kind": "SHARED-ALIAS", "members": ["lab-13", "lab-14", "lab-15", "lab-16"]},
        {"group_id": "G0007", "kind": "UNKNOWN-CANDIDATE-GROUP", "members": ["lab-17"]},
    ]
    nodes = {lab: {"notations": [], "aliases": []} for lab in labels}
    nodes["lab-20"]["notations"] = ["\\alpha"]
    nodes["lab-21"]["notations"] = ["α"]
    nodes["lab-22"]["notations"] = ["alpha"]           # ASCII variant of α
    nodes["lab-23"]["notations"] = ["K"]
    nodes["lab-24"]["notations"] = ["k"]               # single characters are case-sensitive: no link to K
    nodes["lab-25"]["aliases"] = ["Zero Lens"]
    nodes["lab-26"]["aliases"] = ["zero lens"]
    judged = {frozenset(("lab-01", "lab-02")), frozenset(("lab-30", "lab-31"))}
    ctx = G.Ctx(labels, bands, groups, nodes, rows, files, degree, hubs={"lab-03", "lab-40"}, judged=judged)
    ctx.assert_discovery_only()
    return ctx


def synthetic_objects():
    edges = {"lab-30": ["lab-31", "lab-32"], "lab-32": ["lab-33"], "lab-34": ["lab-35", "OUTSIDE-X-LABEL"]}
    objs = []
    for a in [f"lab-{i:02d}" for i in range(30, 40)]:
        objs.append({"working_label": a,
                     "dependency_edges": [{"kind": "USAGE", "target_label": b, "source_id": "S0001"} for b in edges.get(a, [])],
                     "timeline": [{"source_id": "S0001", "change_vs_previous": "FIRST"},
                                  {"source_id": "S0002" if a in ("lab-30", "lab-31", "lab-36") else "S0003",
                                   "change_vs_previous": "REFINES"},
                                  {"source_id": "S0004", "change_vs_previous": "RESTATES"},
                                  {"source_id": "S0010", "change_vs_previous": "REFINES"}]})
    return objs


def synthetic_register():
    return [{"rs_id": "R1", "kind": "HYPOTHESIS", "working_label": "lab-01", "related_labels": ["lab-01", "lab-02", "lab-03"],
             "topics": ["T-A"]},
            {"rs_id": "R2", "kind": "OBSERVATION", "working_label": "lab-04", "related_labels": ["lab-05"], "topics": ["T-A"]}]


def run_all(namespace):
    tests = [(k, v) for k, v in sorted(namespace.items()) if k.startswith("test_") and callable(v)]
    passed, failed = 0, []
    for name, fn in tests:
        try:
            fn()
            passed += 1
            print(f"PASS {name}")
        except Exception:                                  # noqa: BLE001
            failed.append(name)
            print(f"FAIL {name}\n{traceback.format_exc()}")
    print(f"\n{passed} passed, {len(failed)} failed")
    return 0 if not failed else 1
