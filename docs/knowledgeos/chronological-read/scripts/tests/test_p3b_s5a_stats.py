"""S5a statistical core: exact p vs brute force, BH/BY family, gating, BCDD. Synthetic only; recomputed fresh."""
import itertools
import math
import random
import sys
from fractions import Fraction

import numpy as np

import s5a_test_fixtures as F  # noqa: F401  (sets sys.path and cwd)
import p3b_s5a_cells as CELLS

N_STRUCTURES = 500


def _random_structure(rnd):
    labels = [f"x{i}" for i in range(rnd.randint(3, 9))]
    units = {}
    for _ in range(rnd.randint(2, 9)):
        k = rnd.choice((2, 3)) if len(labels) >= 3 else 2
        m = sorted(rnd.sample(labels, k))
        key = "|".join(m)
        units[key] = {"members": m, "role": rnd.choice("CK"), "y": rnd.randint(0, 1)}
    return units


def _brute_p(units, blocks):
    """Enumerate every within-block assignment of the candidate roles (block candidate counts fixed)."""
    inf = []
    t_obs = 0
    for key, us in blocks.items():
        n_c = sum(1 for u in us if units[u]["role"] == "C")
        if 0 < n_c < len(us):
            inf.append((us, n_c))
            t_obs += sum(units[u]["y"] for u in us if units[u]["role"] == "C")
    if not inf:
        return None, t_obs
    choices = [list(itertools.combinations(us, n_c)) for us, n_c in inf]
    total = hit = 0
    for combo in itertools.product(*choices):
        t = sum(units[u]["y"] for part in combo for u in part)
        total += 1
        hit += t >= t_obs
    return Fraction(hit, total), t_obs


def _independent_components(units):
    comp = {}
    for u, v in units.items():
        comp[u] = set(v["members"])
    changed = True
    groups = [set([u]) for u in units]
    while changed:
        changed = False
        for i in range(len(groups)):
            for j in range(i + 1, len(groups)):
                li = set().union(*(comp[u] for u in groups[i]))
                lj = set().union(*(comp[u] for u in groups[j]))
                if li & lj:
                    groups[i] |= groups.pop(j)
                    changed = True
                    break
            if changed:
                break
    return groups


def test_exact_p_vs_brute_force():
    rnd = random.Random(20260925)
    max_diff, informative = 0.0, 0
    for _ in range(N_STRUCTURES):
        units = _random_structure(rnd)
        blocks = CELLS.build_blocks(units, "G-SHARED-GROUP")
        # blocks agree with an independent component computation x arity
        indep = _independent_components(units)
        want = sorted(sorted(sorted(u for u in g if len(units[u]["members"]) == k)) for g in indep for k in (2, 3)
                      if any(len(units[u]["members"]) == k for u in g))
        assert sorted(sorted(v) for v in blocks.values()) == want
        table = CELLS.block_table(units, blocks)
        inf = [b for b in table if b["informative"]]
        brute, t_obs = _brute_p(units, blocks)
        if brute is None:
            assert not inf
            continue
        informative += 1
        T = sum(b["t"] for b in inf)
        assert T == t_obs
        p = CELLS.exact_tail([(b["N"], b["K"], b["n_C"]) for b in inf], T)
        assert p == brute, (p, brute)
        max_diff = max(max_diff, abs(float(p) - float(brute)))
    assert informative >= 200, informative
    assert max_diff < 1e-12
    print(f"   brute force: {N_STRUCTURES} structures, {informative} with informative blocks, max |diff| = {max_diff:.3e}")
    return max_diff


def test_hand_computed_hypergeometric():
    assert CELLS.exact_tail([(5, 2, 2)], 1) == Fraction(7, 10)          # 1 - C(3,2)/C(5,2)
    assert CELLS.exact_tail([(5, 2, 2)], 2) == Fraction(1, 10)
    # two blocks: X1 ~ H(4,2,2) pmf (1/6, 4/6, 1/6); X2 ~ H(3,1,1) pmf (2/3, 1/3); sum >= 3 needs (2, 1)
    p = CELLS.exact_tail([(4, 2, 2), (3, 1, 1)], 3)
    assert p == Fraction(1, 6) * Fraction(1, 3)
    # sum >= 2: (2,0) 1/6*2/3 + (2,1) 1/6*1/3 + (1,1) 4/6*1/3
    assert CELLS.exact_tail([(4, 2, 2), (3, 1, 1)], 2) == Fraction(1, 9) + Fraction(1, 18) + Fraction(2, 9)
    assert CELLS.exact_tail([(4, 2, 2), (3, 1, 1)], 0) == 1
    assert CELLS.exact_tail([(10, 0, 3)], 1) == 0


def test_no_randomness_in_p():
    rnd = random.Random(5)
    structs = [_random_structure(rnd) for _ in range(50)]

    def run(seed):
        random.seed(seed)
        np.random.seed(seed)
        out = []
        for u in structs:
            t = CELLS.block_table(u, CELLS.build_blocks(u, "G-NOTATION"))
            inf = [b for b in t if b["informative"]]
            out.append(CELLS.exact_tail([(b["N"], b["K"], b["n_C"]) for b in inf], sum(b["t"] for b in inf)) if inf else None)
        return out
    a, b, c = run(1), run(1), run(999)
    assert a == b == c


def test_registered_cells_K64():
    cells = CELLS.registered_cells()
    assert len(cells) == 64 == CELLS.K_REGISTERED
    ids = [c["cell_id"] for c in cells]
    assert len(set(ids)) == 64
    assert "G-SHARED-GROUP:IDENTITY-STATE-CONFLATION" in ids            # ISC is not an identity class (G-LOG-0041)
    assert "G-NOTATION:IDENTITY-STATE-CONFLATION" in ids
    for g, d in CELLS.DESCRIPTIVE.items():
        for c in d:
            assert f"{g}:{c}" not in ids
    assert "G-DEPENDENCY:EQUIVALENCE-CLASS" in ids and "G-COCHANGE:EQUIVALENCE-CLASS" in ids
    assert sum(1 for c in cells if c["generator"] == "G-DEPENDENCY") == 12
    assert CELLS.registered_cells() == cells                             # stable


def _fake_results(pvals, statuses=None):
    cells = CELLS.registered_cells()
    out = []
    for c in cells:
        st = (statuses or {}).get(c["cell_id"], "TESTED" if c["cell_id"] in pvals else "UNDERPOWERED")
        p = pvals.get(c["cell_id"], Fraction(1)) if st == "TESTED" else Fraction(1)
        out.append({"cell_index": c["cell_index"], "cell_id": c["cell_id"], "test_status": st, "_p_bh_input_exact": p,
                    "p_bh_input": float(p)})
    return out


def test_bh_family_and_untested_p1():
    ids = [c["cell_id"] for c in CELLS.registered_cells()]
    pv = {ids[0]: Fraction(1, 10000), ids[1]: Fraction(3, 1000), ids[2]: Fraction(1, 20)}
    res = _fake_results(pv, {ids[3]: "REPORT-ONLY-PARTIAL-BLIND"})
    fam = CELLS.bh_by(res)
    assert fam["K"] == 64 and fam["cells"] == ids
    assert all(v == 1.0 for k, v in fam["p_inputs"].items() if k not in pv)
    r = {x["cell_id"]: x for x in res}
    # thresholds i*0.10/64: rank1 0.0015625 (1e-4 passes), rank2 0.003125 (0.003 passes), rank3 0.0046875 (0.05 fails)
    assert r[ids[0]]["outcome"] == "DIFFERENTIATED" and r[ids[1]]["outcome"] == "DIFFERENTIATED"
    assert r[ids[2]]["outcome"] == "UNDIFFERENTIATED"
    assert r[ids[3]]["outcome"] == "REPORT-ONLY-PARTIAL-BLIND" and r[ids[4]]["outcome"] == "UNDERPOWERED"
    assert fam["bh_rejections"] == 2
    # BY: H_64 ~ 4.7439; rank1 threshold 0.10/(64*4.7439) = 3.29e-4 -> 1e-4 passes; rank2 6.59e-4 -> 0.003 fails
    assert fam["by_rejections"] == 1
    try:
        CELLS.bh_by(res[:-1])
        raise AssertionError("BH over a family other than the registered cells must fail")
    except CELLS.C.S5Error:
        pass


def test_gating_rules():
    # one informative block with enough data, FULL blinding -> TESTED; PARTIAL -> report-only (p = 1); m < 20 -> UNDERPOWERED
    ms, units, disp = [], {}, {}
    for i in range(25):
        c, k = f"c{i}a|c{i}b", f"k{i}a|c{i}b"
        units[c], units[k] = c.split("|"), k.split("|")
        ms.append({"candidate_id": f"X#{i}", "candidate": c, "controls": [k]})
        disp[c] = {"unit_key": f"U{2 * i + 1:06d}", "disposition": "ANALYSED",
                   "classes": {"SHARED-INVARIANT": "RECORDED" if i < 15 else "NOT-RECORDED"}}
        disp[k] = {"unit_key": f"U{2 * i + 2:06d}", "disposition": "ANALYSED", "classes": {"SHARED-INVARIANT": "NOT-RECORDED"}}
    cell = {"cell_index": 9, "cell_id": "G-NOTATION:SHARED-INVARIANT", "generator": "G-NOTATION", "class_no": 10,
            "class": "SHARED-INVARIANT", "basis": "DISCOVERY", "unscoring_rule": None, "matched_sets": ms, "m": 25}
    r = CELLS.analyse_cell(cell, units, disp, "FULL", None, do_bcdd=False)
    assert r["test_status"] == "TESTED" and r["T"] == 15 and r["m_inf"] == 25
    # each block has 1 candidate + 1 control; K_b = 1 where candidate shows -> p = prod(1/2) over 15 blocks
    assert abs(r["p_exact"] - 0.5 ** 15) < 1e-15
    r2 = CELLS.analyse_cell(cell, units, disp, "PARTIAL", None, do_bcdd=False)
    assert r2["test_status"] == "REPORT-ONLY-PARTIAL-BLIND" and r2["p_bh_input"] == 1.0
    r3 = CELLS.analyse_cell({**cell, "m": 19}, units, disp, "FULL", None, do_bcdd=False)
    assert r3["test_status"] == "UNDERPOWERED" and r3["p_bh_input"] == 1.0
    disp2 = {u: ({**v, "classes": {"SHARED-INVARIANT": "RECORDED" if u in ("c0a|c0b", "c1a|c1b") else "NOT-RECORDED"}}
                 if u.startswith("c") else v) for u, v in disp.items()}
    r4 = CELLS.analyse_cell(cell, units, disp2, "FULL", None, do_bcdd=False)
    assert r4["test_status"] == "UNDERPOWERED" and r4["T"] == 2                   # x_min < 3


def test_bcdd():
    assert CELLS.bcdd([], Fraction(1, 640), 0, 0)["status"] == "NOT-COMPUTABLE"
    a = CELLS.bcdd([(40, 80, 0.1)], Fraction(5, 100), 3, 1, tables=300)
    b = CELLS.bcdd([(40, 80, 0.1)], Fraction(5, 100), 3, 1, tables=300)
    assert a == b and a["status"] == "REACHED" and 0.05 <= a["bcdd"] <= 0.5
    # N=2, n_C=1: the smallest attainable p is 1/2 > q/K -> never rejects -> NOT-REACHED when phat + delta caps at 1
    c = CELLS.bcdd([(1, 1, 0.5)], CELLS.Q / 64, 0, 0, tables=100)
    assert c["status"] == "NOT-REACHED" and c["last_delta"] == 0.5
    # the vectorized float tail equals the exact tail
    rng = np.random.default_rng(1)
    shape = [(5, 2), (4, 1), (6, 3)]
    Ks = np.array([[rng.integers(0, 6), rng.integers(0, 5), rng.integers(0, 7)] for _ in range(50)])
    T = rng.integers(0, 7, size=50)
    v = CELLS.tail_vectorized(shape, Ks, T)
    for i in range(50):
        ex = CELLS.exact_tail([(N, int(Ks[i, j]), n) for j, (N, n) in enumerate(shape)], int(T[i]))
        assert abs(float(ex) - v[i]) < 1e-12


if __name__ == "__main__":
    sys.exit(F.run_all(dict(globals())))
