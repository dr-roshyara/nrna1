#!/usr/bin/env python3
"""Pre-registered analysis instrument v1 for Track B (B1 v2 §3). Model 0 made OPERATIONAL: for each hypothesis a test
statistic T and a null-model generator M0 that preserves exactly what "the reconstruction method + topic frequency"
explains, so a surviving effect is structure BEYOND those. Permutation p = (1 + #{T* ≥ T}) / (B + 1).

  H1  sequential clustering of change classes  M0: within-object permutation of classes at positions ≥ 2 (keeps FIRST,
      each object's class multiset → frequencies and object sizes preserved)          T: # adjacent equal classes (runs)
  H3  label dependency graph acyclic / ordered M0: directed degree-preserving edge swaps              T: # 2-cycles + 3-cycles
      (vacuity guard: < 10 edges between labels in the set → UNTESTABLE)
  H4  birth kinds ordered in time              M0: within-object exchange of dated birth positions across kinds
                                                                                   T: Kendall S (concordant − discordant)
  H5  implication (closure) structure of       M0: swap randomization (curveball) of the label × dimension FOUND matrix,
      FOUND absence dimensions                     preserving row sums and column sums  T: # exact implications, support ≥ 3
  H2  contradiction runs forward in A.10 time  M0: random orientation of each dated relation   T: # forward relations
      (declared PARTLY METHOD-CONSTRAINED: the contract orders contradicted_by by timeline predecessor)

Confirmatory family {H1..H5}: Holm–Bonferroni over the permutation p-values (FWER ≤ α under any dependence).
Exploratory (e.g. the individual implication pairs): Benjamini–Hochberg at q. No ML. Deterministic under the seed.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B ../../research/prereg_v1.py [B] [seed]
"""
import collections
import itertools
import json
import os
import random
import re
import sys

SID = re.compile(r"\bS\d{4}\b")
KIND_ORDER = ("lexical", "conceptual", "formal", "operational", "governance")


def perm_p(t, null):
    return (1 + sum(1 for x in null if x >= t)) / (len(null) + 1)


# ---------------------------------------------------------------- H1
def t_h1(seqs):
    return sum(a == b for s in seqs for a, b in zip(s[1:], s[2:]))


def m0_h1(seqs, rng):
    out = []
    for s in seqs:
        tail = list(s[1:])
        rng.shuffle(tail)
        out.append([s[0]] + tail)
    return out


# ---------------------------------------------------------------- H3
def t_h3(edges):
    E = set(edges)
    two = sum(1 for a, b in E if (b, a) in E) // 2
    succ = collections.defaultdict(set)
    for a, b in E:
        succ[a].add(b)
    three = sum(1 for a, b in E for c in succ[b] if (c, a) in E) // 3
    return two + three


def m0_h3(edges, rng, swaps=None):
    E = list(edges)
    S = set(E)
    for _ in range(swaps or 10 * len(E)):
        i, j = rng.randrange(len(E)), rng.randrange(len(E))
        (a, b), (c, d) = E[i], E[j]
        if len({a, b, c, d}) < 4 or (a, d) in S or (c, b) in S:
            continue
        S -= {(a, b), (c, d)}
        S |= {(a, d), (c, b)}
        E[i], E[j] = (a, d), (c, b)
    return E


# ---------------------------------------------------------------- H4
def t_h4(dated):
    s = 0
    for d in dated:                                   # d: {kind: date}, ≥ 2 kinds
        ks = [k for k in KIND_ORDER if k in d]
        for a, b in itertools.combinations(ks, 2):
            s += (d[a] < d[b]) - (d[a] > d[b])        # ties contribute 0 (reported separately)
    return s


def m0_h4(dated, rng):
    out = []
    for d in dated:
        ks, vs = list(d), list(d.values())
        rng.shuffle(vs)
        out.append(dict(zip(ks, vs)))
    return out


# ---------------------------------------------------------------- H5
def implications(rows, dims, support=3):
    out = []
    for a, b in itertools.permutations(dims, 2):
        sa = [r for r in rows if a in r]
        if len(sa) >= support and all(b in r for r in sa):
            out.append((a, b, len(sa)))
    return out


def m0_h5(rows, rng, steps=None):
    """Curveball algorithm: uniform over 0/1 matrices with the observed row and column sums (Strona et al. 2014)."""
    R = [set(r) for r in rows]
    for _ in range(steps or 5 * len(R)):
        i, j = rng.sample(range(len(R)), 2)
        common = R[i] & R[j]
        pool = sorted((R[i] | R[j]) - common)
        rng.shuffle(pool)
        k = len(R[i] - common)                        # row i keeps its size: row sums preserved
        R[i], R[j] = common | set(pool[:k]), common | set(pool[k:])   # each column's total unchanged
    return R


# ---------------------------------------------------------------- H2
def t_h2(orient):
    return sum(orient)


def m0_h2(orient, rng):
    return [rng.random() < 0.5 for _ in orient]


def holm(ps):
    order = sorted(ps, key=ps.get)
    adj, run = {}, 0.0
    for i, h in enumerate(order):
        run = max(run, min(1.0, (len(order) - i) * ps[h]))
        adj[h] = run
    return adj


def bh(ps, q=0.05):
    items = sorted(ps.items(), key=lambda kv: kv[1])
    m, k = len(items), 0
    for i, (_, p) in enumerate(items, 1):
        if p <= q * i / m:
            k = i
    return {h for h, _ in items[:k]}


def run(objs, meta, C, B=999, seed=20260927):
    rng = random.Random(seed)
    res = {}
    seqs = [[p.get("change_vs_previous") for p in o.get("timeline") or []] for o in objs if len(o.get("timeline") or []) >= 3]
    t = t_h1(seqs)
    res["H1"] = {"T": t, "p": perm_p(t, [t_h1(m0_h1(seqs, rng)) for _ in range(B)]), "n_objects": len(seqs)}
    labels = {o["working_label"] for o in objs}
    edges = sorted({(o["working_label"], e["target_label"]) for o in objs for e in o.get("dependency_edges") or []
                    if isinstance(e, dict) and e.get("target_label") in labels})
    res["H3"] = ({"status": "UNTESTABLE", "internal_edges": len(edges)} if len(edges) < 10 else
                 {"T": t_h3(edges), "p": perm_p(t_h3(edges), [t_h3(m0_h3(edges, rng)) for _ in range(B)])})
    dated, ties = [], 0
    for o in objs:
        d = {}
        for k, v in (o.get("births") or {}).items():
            ds = [C.file_position(s, meta) for s in SID.findall(str(v))]
            ds = [x for x in ds if x != "UNDATED"]
            if ds:
                d[k] = min(ds)
        if len(d) >= 2:
            dated.append(d)
            ties += sum(1 for a, b in itertools.combinations(d.values(), 2) if a == b)
    t = t_h4(dated)
    res["H4"] = {"T": t, "p": perm_p(t, [t_h4(m0_h4(dated, rng)) for _ in range(B)]), "n_objects": len(dated), "tied_pairs": ties}
    rows = [{k for k, a in (o.get("absences") or {}).items() if isinstance(a, dict) and a.get("resolution") == "FOUND"} for o in objs]
    dims = sorted({k for o in objs for k in (o.get("absences") or {})})
    t = len(implications(rows, dims))
    res["H5"] = {"T": t, "p": perm_p(t, [len(implications(m0_h5(rows, rng), dims)) for _ in range(B)])}
    orient = []
    for o in objs:
        for (s, tgt), prec in C.contradiction_precedence(o, meta).items():
            a, b = C.file_position(tgt, meta), C.file_position(s, meta)
            if "UNDATED" not in (a, b) and a != b:
                orient.append(a < b)
    res["H2"] = ({"status": "UNTESTABLE", "dated_relations": len(orient)} if len(orient) < 5 else
                 {"T": t_h2(orient), "p": perm_p(t_h2(orient), [t_h2(m0_h2(orient, rng)) for _ in range(B)])})
    tested = {h: r["p"] for h, r in res.items() if "p" in r}
    adj = holm(tested)
    for h in tested:
        res[h]["p_holm"] = adj[h]
    return {"B": B, "seed": seed, "results": res}


if __name__ == "__main__":
    HERE = os.path.dirname(os.path.abspath(__file__))
    TESTS = os.path.join(os.path.dirname(HERE), "scripts", "tests")
    sys.path[:0] = [TESTS, os.path.dirname(TESTS)]
    import test_p3b_s5_verify as T          # noqa: E402  (committed pilot objects; smoke test only, EXPLORATORY)
    import p3b_s5_r7_reconstruction as C    # noqa: E402
    B = int(sys.argv[1]) if len(sys.argv) > 1 else 999
    seed = int(sys.argv[2]) if len(sys.argv) > 2 else 20260927
    objs = [o for pb in ("PB02", "PB03", "PB04", "PB05") for o in T.nonhub_ctx(pb)["objs"]]
    print(json.dumps({"material": "20 committed rev3 S4 pilot objects — EXPLORATORY smoke test, not confirmatory",
                      **run(objs, C.c.files_meta(), C, B, seed)}, indent=1))
