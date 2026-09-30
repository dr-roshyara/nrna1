"""S5a mechanics on synthetic data: generators, control draw, symmetric removal, blocks, pools, pipeline, G-13."""
import copy
import itertools
import json
import random
import sys

import numpy as np

import s5a_test_fixtures as F
import p3b_s5_common as C
import p3b_s5a_cells as CELLS
import p3b_s5a_controls as K
import p3b_s5a_g13 as G13
import p3b_s5a_generators as G


def _build(gens=G.GENERATORS + G.SELF_DERIVED):
    ctx = F.synthetic_ctx()
    return ctx, G.build(ctx, gens, F.synthetic_objects(), F.synthetic_register())


# ------------------------------------------------------------------ generators
def test_generators_k2_k3_and_filters():
    ctx, (res, cand, ctrl, draws) = _build()
    sg = {tuple(c["members"]) for c in res["G-SHARED-GROUP"].candidates}
    assert ("lab-01", "lab-02") in sg and ("lab-03", "lab-04", "lab-07") in sg          # k = 2 and k = 3
    assert ("lab-13", "lab-14", "lab-15") in sg and len([s for s in sg if len(s) == 3 and "lab-13" in s]) == 3
    assert ("lab-05", "lab-08") not in sg                                                 # CO-OCCURRENCE filter
    assert not any("lab-06" in s or "lab-11" in s for s in sg)                           # STRING-SIMILARITY filter
    assert ("lab-09", "lab-10") in sg
    assert all(len(c["members"]) in (2, 3) for c in cand)
    nt = {tuple(c["members"]) for c in res["G-NOTATION"].candidates}
    assert ("lab-20", "lab-21") in nt and ("lab-20", "lab-21", "lab-22") in nt           # \alpha = α = alpha (A.4)
    assert ("lab-23", "lab-24") not in nt                                                 # single char case-sensitive
    assert ("lab-25", "lab-26") in nt                                                     # casefold (multi-char)
    ts = res["G-TYPE-SIM"]
    assert all(len(c["members"]) in (2, 3) for c in ts.candidates) and ts.candidates
    assert ts.parameters["table_version"] == G.TYPE_TABLE_VERSION
    # full list first, sorted by member tuple
    for g, r in res.items():
        mem = [tuple(c["members"]) for c in r.candidates]
        assert mem == sorted(mem) and len(mem) == len(set(mem)), g


def test_signature_shape_table():
    assert G.signature_shape({"domain": "A x B", "codomain": "Boolean", "arity": 2}) == "2|OTHER,OTHER|SCALAR"
    assert G.signature_shape({"domain": "S_t, Event", "codomain": "S_{t+1}", "arity": 2}) == "2|STATE,OTHER|STATE"
    assert G.signature_shape({"domain": "Q", "codomain": "2^S", "arity": 1}) == "1|OTHER|SET"
    assert G.signature_shape("f : X → Y → Z") == "1|OTHER|FUNCTION"
    assert G.signature_shape("K = (T,J,R)") == "0||OTHER"
    assert G.token_class("[0,1]") == "SCALAR" and G.token_class("\\mathbb{R}") != "FUNCTION"
    assert G.is_formal({"domain": "", "codomain": "x"}) and not G.is_formal({"domain": "", "codomain": None})
    assert G.split_domain("(Evidence,Reasoning,(Context,Time))") == ["Evidence", "Reasoning", "(Context,Time)"]


def test_ai_input_generators_and_self_derived():
    ctx, (res, cand, ctrl, draws) = _build()
    dep = {tuple(c["members"]) for c in res["G-DEPENDENCY"].candidates}
    # edges 30-31, 30-32, 32-33, 34-35 (34 -> OUTSIDE-X-LABEL dropped): connected k-subsets, undirected
    assert dep == {("lab-30", "lab-31"), ("lab-30", "lab-32"), ("lab-32", "lab-33"), ("lab-34", "lab-35"),
                   ("lab-30", "lab-31", "lab-32"), ("lab-30", "lab-32", "lab-33")}
    assert ("lab-31", "lab-32", "lab-33") not in dep                                     # not connected
    assert not any("OUTSIDE-X-LABEL" in s for s in dep)                                   # Tier-X / non-S5 dropped
    co = res["G-COCHANGE"]
    cs = {tuple(c["members"]) for c in co.candidates}
    assert ("lab-30", "lab-31", "lab-36") in cs                                           # S0002 (and S0001)
    assert co.excluded_inputs["sources_excluded"]["S0010"] == "DEGREE-ABOVE-CAP"
    assert "S0004" not in co.link_index.get("lab-30", set())                               # RESTATES never counts
    with_excl = [c for c in co.candidates if c["source_exclusions"]]
    assert with_excl and all(e["source_id"] == "S0010" for c in with_excl for e in c["source_exclusions"])
    top = [r for r in cand if r["generator"] in G.SELF_DERIVED]
    assert top and all(r["in_budget"] is None and r["in_test_family"] is False and r["self_derived"] for r in top)
    assert not any(r.get("generator") in G.SELF_DERIVED for r in ctrl)                     # never controlled/counted


def test_budget_rule():
    ctx = F.synthetic_ctx()
    gres = G.g_cochange(ctx, F.synthetic_objects())
    G.assign_ids(gres)
    big = copy.deepcopy(gres)
    extra = [{"members": [f"lab-{i:02d}", f"lab-{j:02d}"], "k": 2, "link_tokens": ["x"], "source_exclusions": []}
             for i, j in itertools.combinations(range(48), 2)]
    big.candidates = sorted(big.candidates + extra, key=lambda c: tuple(c["members"]))
    G.assign_ids(big)
    analysed = G.apply_budget(big)
    assert len(big.candidates) > 300 and len(analysed) == 300
    i_co = G.GENERATORS.index("G-COCHANGE")              # plan §G.3: §9E.1 table order
    assert G.GENERATORS == ("G-SHARED-GROUP", "G-DEPENDENCY", "G-NOTATION", "G-COCHANGE", "G-TYPE-SIM")
    want = random.Random(C.int_seed(20261001, 5, i_co)).sample(big.candidates, 300)
    assert {id(x) for x in want} == {id(c) for c in analysed}
    assert G.budget_seed("G-COCHANGE") == C.int_seed(20261001, 5, i_co) and G.budget_seed("G-TYPE-SIM") == C.int_seed(20261001, 5, 4)
    sg = G.g_shared_group(ctx)
    G.assign_ids(sg)
    assert len(G.apply_budget(sg)) == len(sg.candidates)                                   # full list


# ------------------------------------------------------------------ controls
def _check_controls(ctx, res, ctrl):
    by_cand = {}
    for r in ctrl:
        if r["record_type"] == "CONTROL":
            by_cand.setdefault(r["for_candidate"], []).append(r)
    cands = {c["candidate_id"]: (g, c) for g, rr in res.items() for c in rr.candidates}
    n = 0
    for cid, ctls in by_cand.items():
        g, c = cands[cid]
        gres = res[g]
        assert len(ctls) <= K.R
        used = set(c["members"])
        for r in ctls:
            m = r["members"]
            n += 1
            assert len(m) == c["k"]                                                  # exact arity
            assert set(m) <= set(gres.population)                                     # eligible population
            assert not gres.any_linked(m)                                             # no pair has the defining link
            assert used.isdisjoint(m)                                                 # disjoint from candidate/others
            used |= set(m)
            active = r["matching"]["matched_variables"]
            assert C.set_band(m, ctx.bands, active) == C.set_band(c["members"], ctx.bands, active)
            assert r["matching"]["relaxed_variables"] == list(K.RELAX_ORDER[:r["matching"]["relaxation_level"]])
            if g == "G-TYPE-SIM":
                assert "has_formal_rows" in active
    return n


def test_control_draw_invariants_r2():
    ctx, (res, cand, ctrl, draws) = _build(G.GENERATORS)
    assert _check_controls(ctx, res, ctrl) > 0
    for g, d in draws.items():
        for x in d:
            assert x["r_s"] in (0, 1, 2)
            assert x["status"] == {0: "NO-CONTROL-AVAILABLE", 1: "PARTIAL-R_S-1", 2: "OK"}[x["r_s"]]
            assert x["stream"]["root"] == 20261009 and x["stream"]["spawn_key"][0] == G.GEN_INDEX[g]
    assert any(x["r_s"] == 2 for d in draws.values() for x in d)


class _FakeGen:
    def __init__(self, generator, population, links=()):
        self.generator = generator
        self.population = tuple(sorted(population))
        self.link = {frozenset(p) for p in links}

    def any_linked(self, m):
        return any(frozenset(p) in self.link for p in itertools.combinations(m, 2))


def _bands(spec):
    return {lab: {"row": r, "pair": "0", "prov": "all-PRIMARY", "span": "<1d", "degree": d, "has_formal_rows": f}
            for lab, (r, d, f) in spec.items()}


def test_relaxation_order_and_formal_never_relaxed():
    # candidate (a, b): formal, row 1, degree <=5. Pool: c, d match everything but degree (-> level 1);
    # e, f match everything but has_formal_rows (never relaxed).
    spec = {"a": ("1", "<=5", True), "b": ("1", "<=5", True), "c": ("1", "6-10", True), "d": ("1", "6-10", True),
            "e": ("1", "<=5", False), "f": ("1", "<=5", False)}
    bands = _bands(spec)
    cand = [{"candidate_id": "X#0", "members": ["a", "b"], "k": 2}]
    d = K.draw_for_generator(_FakeGen("G-TYPE-SIM", spec, [("a", "b")]), bands, cand)[0]
    assert d["r_s"] == 1 and d["controls"][0]["members"] == ["c", "d"]
    assert d["controls"][0]["relaxation_level"] == 1 and d["controls"][0]["relaxed_variables"] == ["degree"]
    # without c, d: G-TYPE-SIM must NOT fall back to e, f (formal never relaxed) -> NO-CONTROL-AVAILABLE
    spec2 = {k: v for k, v in spec.items() if k not in "cd"}
    d2 = K.draw_for_generator(_FakeGen("G-TYPE-SIM", spec2, [("a", "b")]), _bands(spec2), cand)[0]
    assert d2["status"] == "NO-CONTROL-AVAILABLE" and d2["levels_tried"] == [0, 1, 2, 3, 4, 5]
    # the same pool for a generator without the formal variable matches e, f at level 0
    d3 = K.draw_for_generator(_FakeGen("G-NOTATION", spec2, [("a", "b")]), _bands(spec2), cand)[0]
    assert d3["controls"][0]["members"] == ["e", "f"] and d3["controls"][0]["relaxation_level"] == 0
    # a linked pool pair is never drawn
    d4 = K.draw_for_generator(_FakeGen("G-NOTATION", spec2, [("a", "b"), ("e", "f")]), _bands(spec2), cand)[0]
    assert d4["status"] == "NO-CONTROL-AVAILABLE"


def test_exact_arity_k3_and_two_disjoint_controls():
    spec = {x: ("1", "<=5", False) for x in "abcdefghij"}
    bands = _bands(spec)
    cand = [{"candidate_id": "X#0", "members": ["a", "b", "c"], "k": 3}]
    d = K.draw_for_generator(_FakeGen("G-SHARED-GROUP", spec, [("a", "b"), ("a", "c"), ("b", "c")]), bands, cand)[0]
    assert d["r_s"] == 2 and all(len(c["members"]) == 3 for c in d["controls"])
    s1, s2 = (set(c["members"]) for c in d["controls"])
    assert not s1 & s2 and not (s1 | s2) & {"a", "b", "c"}
    # determinism: same stream -> same draw; the rejection path (large pool) is exercised too
    big = {f"z{i:03d}": ("1", "<=5", False) for i in range(60)}
    big.update({"a": ("1", "<=5", False), "b": ("1", "<=5", False), "c": ("1", "<=5", False)})
    g = _FakeGen("G-SHARED-GROUP", big, [("a", "b"), ("a", "c"), ("b", "c")])
    x1 = K.draw_for_generator(g, _bands(big), cand)
    x2 = K.draw_for_generator(g, _bands(big), cand)
    assert x1 == x2 and x1[0]["r_s"] == 2


def test_family_greedy_label_disjoint():
    ctx, (res, cand, ctrl, draws) = _build(G.GENERATORS)
    for g in G.GENERATORS:
        rr = res[g]
        nca = {x["candidate_id"] for x in draws[g] if x["status"] == "NO-CONTROL-AVAILABLE"}
        used, want = set(), []
        for c in rr.candidates:
            if c["in_budget"] and c["candidate_id"] not in nca and used.isdisjoint(c["members"]):
                want.append(c["candidate_id"])
                used |= set(c["members"])
        assert [c["candidate_id"] for c in rr.candidates if c["in_test_family"]] == want
    # an NCA candidate is never in the family
    gres = res["G-SHARED-GROUP"]
    first = next(c for c in gres.candidates if c["in_test_family"])
    G.mark_test_family(gres, {first["candidate_id"]})
    assert not first["in_test_family"]


# ------------------------------------------------------------------ symmetric removal, components, blocks
def _sets():
    return [{"candidate_id": "S1", "candidate": "A", "controls": ["X", "Y"]},
            {"candidate_id": "S2", "candidate": "B", "controls": ["Y", "Z"]},
            {"candidate_id": "S3", "candidate": "C", "controls": ["W"]}]


def test_symmetric_removal_cases():
    # candidate undetermined: whole set removed; its control X leaves; Y kept (serves S2)
    ret, att = CELLS.symmetric_removal(_sets(), lambda u: "UNDETERMINED" if u == "A" else None)
    assert [s["candidate_id"] for s in ret] == ["S2", "S3"]
    assert att["candidates_removed_undetermined_or_unscored"] == 1 and att["controls_removed_with_their_set"] == 1
    assert att["controls_kept_serving_another_retained_set"] == 1
    # control undetermined: removed from every set it serves
    ret, att = CELLS.symmetric_removal(_sets(), lambda u: "UNDETERMINED" if u == "Y" else None)
    assert [s["controls"] for s in ret] == [["X"], ["Z"], ["W"]] and att["controls_removed_undetermined_or_unscored"] == 1
    # control shared across sets and undetermined, plus a set losing all its controls
    ret, att = CELLS.symmetric_removal(_sets(), lambda u: "UNDETERMINED" if u in ("W", "Y") else None)
    assert [s["candidate_id"] for s in ret] == ["S1", "S2"] and att["sets_lost_no_determined_control"] == 1
    # closed form over random statuses
    rnd = random.Random(3)
    for _ in range(200):
        bad = {u for u in "ABCXYZW" if rnd.random() < 0.3}
        ret, att = CELLS.symmetric_removal(_sets(), lambda u: "U" if u in bad else None)
        want = [s["candidate_id"] for s in _sets() if s["candidate"] not in bad and any(c not in bad for c in s["controls"])]
        assert [s["candidate_id"] for s in ret] == want
        assert all(c not in bad for s in ret for c in s["controls"])
        assert att["sets_retained"] + att["sets_lost"] == 3


def test_components_and_exact_arity_blocks():
    units = {"a|b": {"members": ["a", "b"], "role": "C", "y": 1}, "b|c": {"members": ["b", "c"], "role": "K", "y": 0},
             "b|c|d": {"members": ["b", "c", "d"], "role": "K", "y": 1}, "e|f": {"members": ["e", "f"], "role": "K", "y": 0}}
    comp = CELLS.components({u: v["members"] for u, v in units.items()})
    assert comp["a|b"] == comp["b|c"] == comp["b|c|d"] == "a" and comp["e|f"] == "e"
    blocks = CELLS.build_blocks(units, "G-SHARED-GROUP")
    assert sorted(blocks.values()) == [["a|b", "b|c"], ["b|c|d"], ["e|f"]]              # arity splits the component
    bands = {x: {"has_formal_rows": x in "ab"} for x in "abcdef"}
    tb = CELLS.build_blocks(units, "G-TYPE-SIM", bands)
    assert len(tb) == 4                                                                  # formal profile splits a|b / b|c


def test_repeated_control_set_counted_once():
    units = {"c1|c2": ["c1", "c2"], "d1|d2": ["d1", "d2"], "k1|k2": ["k1", "k2"]}
    ms = [{"candidate_id": "S1", "candidate": "c1|c2", "controls": ["k1|k2"]},
          {"candidate_id": "S2", "candidate": "d1|d2", "controls": ["k1|k2"]}]
    disp = {u: {"unit_key": f"U{i + 1:06d}", "disposition": "ANALYSED", "classes": {"SHARED-INVARIANT": "RECORDED"}}
            for i, u in enumerate(units)}
    cell = {"cell_index": 0, "cell_id": "G-NOTATION:SHARED-INVARIANT", "generator": "G-NOTATION", "class_no": 10,
            "class": "SHARED-INVARIANT", "basis": "DISCOVERY", "unscoring_rule": None, "matched_sets": ms, "m": 2}
    r = CELLS.analyse_cell(cell, units, disp, "FULL", None, do_bcdd=False)
    assert r["counts"]["control_units"] == 1 and r["reuse"]["control_slots"] == 2
    assert r["reuse"]["max_sets_served_by_one_control_unit"] == 2
    assert sum(b["N"] for b in r["blocks"]["table"]) == 3                               # the control set once
    # label-disconnected candidates and controls -> no informative block -> UNDERPOWERED, p = 1
    assert r["blocks"]["informative"] == 0 and r["test_status"] == "UNDERPOWERED" and r["p_bh_input"] == 1.0


def test_undetermined_is_not_zero():
    units = {"a|b": ["a", "b"], "a|c": ["a", "c"]}
    ms = [{"candidate_id": "S1", "candidate": "a|b", "controls": ["a|c"]}]
    cell = {"cell_index": 0, "cell_id": "G-NOTATION:SHARED-INVARIANT", "generator": "G-NOTATION", "class_no": 10,
            "class": "SHARED-INVARIANT", "basis": "DISCOVERY", "unscoring_rule": None, "matched_sets": ms, "m": 1}
    disp = {"a|b": {"unit_key": "U000001", "disposition": "ANALYSED", "classes": {"SHARED-INVARIANT": "RECORDED"}},
            "a|c": {"unit_key": "U000002", "disposition": "DEFERRED", "reason": "evidence unreadable"}}
    r = CELLS.analyse_cell(cell, units, disp, "FULL", None, do_bcdd=False)
    assert r["attrition"]["sets_lost"] == 1 and r["counts"]["control_units"] == 0 and r["counts"]["candidate_units"] == 0
    try:
        CELLS.analyse_cell(cell, units, {"a|b": disp["a|b"]}, "FULL", None, do_bcdd=False)
        raise AssertionError("a missing disposition must refuse")
    except C.S5Error:
        pass
    # a class MISSING from `classes` is UNDETERMINED, never NOT-RECORDED: the control is removed, not counted as 0
    disp2 = {"a|b": disp["a|b"], "a|c": {"unit_key": "U000002", "disposition": "ANALYSED", "classes": {}}}
    r2 = CELLS.analyse_cell(cell, units, disp2, "FULL", None, do_bcdd=False)
    assert r2["attrition"]["by_cause_controls"] == {"CONTROL-UNDETERMINED-CLASS-MISSING": 1}
    assert r2["counts"]["control_units"] == 0


def test_disposition_schema():
    ok = {"unit_key": "U000001", "disposition": "ANALYSED", "classes": {c: "NOT-RECORDED" for c in CELLS.CLASSES}}
    assert CELLS.validate_disposition(ok) is ok
    assert set(CELLS.DISPOSITION_SCHEMA["properties"]["classes"]["properties"]) == set(CELLS.CLASSES)
    assert CELLS.DISPOSITION_SCHEMA["properties"]["disposition"]["enum"] == ["ANALYSED", "DEFERRED", "FAILED"]
    json.dumps(CELLS.DISPOSITION_SCHEMA)                                                   # exportable
    for y, cls in ((1, "RECORDED"), (0, "NOT-RECORDED"), (None, "UNDETERMINED")):
        d = {"unit_key": "U000001", "disposition": "ANALYSED", "classes": {"SHARED-INVARIANT": cls}}
        assert CELLS.outcome_status({"u": d}, "u", "SHARED-INVARIANT")[0] == y
    assert CELLS.outcome_status({"u": ok}, "u", "SHARED-INVARIANT")[0] == 0
    miss = {"unit_key": "U000001", "disposition": "ANALYSED", "classes": {}}
    assert CELLS.outcome_status({"u": miss}, "u", "SHARED-INVARIANT") == (None, "UNDETERMINED-CLASS-MISSING")
    for dz in ("DEFERRED", "FAILED"):
        d = {"unit_key": "U000001", "disposition": dz, "reason": "r"}
        assert all(CELLS.outcome_status({"u": d}, "u", c)[0] is None for c in CELLS.CLASSES)
    bad = [{**ok, "disposition": "FINDING-RECORDED"}, {**ok, "disposition": "NO-COMMON-STRUCTURE-FOUND"},
           {**ok, "classes": {"SHARED-INVARIANT": "YES"}}, {**ok, "classes": {"NEW-CLASS": "RECORDED"}},
           {**ok, "classes": {"SHARED-INVARIANT": None}}, {**ok, "extra": 1}, {**ok, "unit_key": "X1"},
           {"unit_key": "U000001", "disposition": "DEFERRED"},                                  # reason required
           {"unit_key": "U000001", "disposition": "FAILED", "reason": "r", "classes": {"SHARED-INVARIANT": "RECORDED"}},
           {"blind_id": "U000001", "disposition": "ANALYSED"}]
    for d in bad:
        try:
            CELLS.validate_disposition(d)
            raise AssertionError(f"not refused: {d}")
        except C.S5Error:
            pass
    try:
        CELLS.validate_dispositions([ok, ok], ["U000001"])
        raise AssertionError("duplicate unit_key must refuse")
    except C.S5Error:
        pass
    for keys in (["U000002"], ["U000001", "U000002"]):                                    # unknown / missing unit
        try:
            CELLS.validate_dispositions([ok], keys)
            raise AssertionError("unknown or missing unit must refuse")
        except C.S5Error:
            pass


# ------------------------------------------------------------------ pools, pipeline, determinism
def _pipeline(disp_seed=11, tables=150):
    ctx, (res, cand, ctrl, draws) = _build()
    blind, reveal = K.build_blind(ctx, cand, ctrl)
    pools = CELLS.build_pools(cand, ctrl, ctx.hubs, ctx.judged, K.withheld_sets(ctx, cand, ctrl))
    rnd = random.Random(disp_seed)
    disp = []
    for b in blind:
        if rnd.random() < 0.08:
            disp.append({"unit_key": b["unit_key"], "disposition": "DEFERRED", "reason": "synthetic"})
            continue
        disp.append({"unit_key": b["unit_key"], "disposition": "ANALYSED",
                     "classes": {c: rnd.choice(["RECORDED", "NOT-RECORDED", "NOT-RECORDED", "UNDETERMINED"])
                                 for c in CELLS.CLASSES}})
    blinding = {g: "FULL" for g in G.GENERATORS}
    body = CELLS.analyse(pools, reveal, disp, blinding, ctx.bands, True, tables)
    return ctx, cand, ctrl, blind, reveal, pools, body


def test_pools_unscoring_and_pipeline():
    ctx, cand, ctrl, blind, reveal, pools, body = _pipeline()
    assert len(pools["cells"]) == 64 and len(body["cells"]) == 64 and body["family"]["cells"] == body["registered_cells"]
    cells = {c["cell_id"]: c for c in pools["cells"]}
    for cid, c in cells.items():
        for ms in c["matched_sets"]:
            for u in [ms["candidate"]] + ms["controls"]:
                m = pools["units"][u]
                if c["unscoring_rule"] == "HUB":
                    assert not set(m) & ctx.hubs
                if c["unscoring_rule"] == "RC-13":
                    assert not any(frozenset(p) in ctx.judged for p in itertools.combinations(m, 2))
    assert cells["G-SHARED-GROUP:MISSING-TRANSITION"]["unscoring_rule"] == "HUB"
    assert cells["G-DEPENDENCY:EQUIVALENCE-CLASS"]["unscoring_rule"] == "RC-13"
    assert cells["G-SHARED-GROUP:IDENTITY-STATE-CONFLATION"]["unscoring_rule"] is None
    # the hub lab-03 sits in a family candidate of G-SHARED-GROUP -> removed only in the absence-based cells
    # (only if such a set survives the M4 residual withholding, which applies to every cell)
    kept_si = {ms["candidate"] for ms in cells["G-SHARED-GROUP:SHARED-INVARIANT"]["matched_sets"]}
    fam03 = [r for r in cand if r["generator"] == "G-SHARED-GROUP" and r["in_test_family"] and "lab-03" in r["members"]
             and CELLS.set_key(r["members"]) in kept_si]
    if fam03:
        assert cells["G-SHARED-GROUP:MISSING-INVARIANT"]["m"] < cells["G-SHARED-GROUP:SHARED-INVARIANT"]["m"]
    dep = cells["G-DEPENDENCY:EQUIVALENCE-CLASS"]
    assert dep["m"] <= cells["G-DEPENDENCY:SHARED-INVARIANT"]["m"]
    for r in body["cells"]:
        assert r["outcome"] in ("DIFFERENTIATED", "UNDIFFERENTIATED", "UNDERPOWERED", "REPORT-ONLY-PARTIAL-BLIND")
        if r["test_status"] != "TESTED":
            assert r["p_bh_input"] == 1.0
        assert r["attrition"]["sets_retained"] + r["attrition"]["sets_lost"] == r["attrition"]["sets_in"]
    # blind file: one entry per distinct set, no defining-link fields, re-keyed
    assert len({tuple(b["members"]) for b in blind}) == len(blind)
    assert all(set(b) == {"unit_key", "members", "evidence"} for b in blind)
    assert all(set(r) == {"unit_key", "members", "roles", "pointers_stripped", "shared_source_pointers_stripped"} for r in reveal)
    for b in blind:                                                        # pointers only: source_id, row_line, anchor
        assert all(set(p) == {"source_id", "row_line", "anchor"} for ptrs in b["evidence"].values() for p in ptrs)
    # G-COCHANGE: no remaining pointer of a COCHANGE set cites a shared co-change source of its candidate
    cand_by_id = {r["candidate_id"]: r for r in cand}
    rv = {r["unit_key"]: r for r in reveal}
    for b in blind:
        for role in rv[b["unit_key"]]["roles"]:
            if role["generator"] == "G-COCHANGE" and role["role"] in ("CANDIDATE", "CONTROL"):
                cid = role.get("candidate_id") or role["for_candidate"]
                toks = set(cand_by_id[cid]["defining_property"]["link_tokens"])
                assert not any(p["source_id"] in toks for ptrs in b["evidence"].values() for p in ptrs)
    assert any(r["pointers_stripped"] for r in reveal)


def test_determinism_generators_controls_and_cells():
    a = _pipeline()
    b = _pipeline()
    h = lambda recs: C.sha256_bytes(G.body_text(recs).encode())
    assert h(a[1]) == h(b[1]) and h(a[2]) == h(b[2]) and h(a[3]) == h(b[3]) and h(a[4]) == h(b[4])
    dump = lambda x: json.dumps(x, sort_keys=True)
    assert dump(a[5]) == dump(b[5]) and dump(a[6]) == dump(b[6])
    random.seed(12345)
    np.random.seed(54321)
    c = _pipeline()
    assert h(a[2]) == h(c[2]) and dump(a[6]) == dump(c[6])                                # global seeds irrelevant


def _row(line, src, anchor, ts=None, statement="x"):
    return {"line": line, "source_id": src, "anchor": anchor, "statement": statement, "type_signature": ts}


def _ptrs(ctx, members):
    return {m: list(ctx.rows_by_label.get(m, [])) for m in members}


def test_blinding_token_rule():
    ctx = F.synthetic_ctx()
    ctx.rows_by_label["lab-20"] = [_row(1, "S0001", "we write α for it")]
    ctx.rows_by_label["lab-21"] = []
    assert K.set_reveals_link(ctx, "G-NOTATION", ["α"], _ptrs(ctx, ["lab-20", "lab-21"]))
    ctx.rows_by_label["lab-23"] = [_row(2, "S0001", "the k value")]
    ctx.rows_by_label["lab-24"] = []
    assert not K.set_reveals_link(ctx, "G-NOTATION", ["K"], _ptrs(ctx, ["lab-23", "lab-24"]))   # case-sensitive
    ctx.rows_by_label["lab-01"] = [_row(3, "S0001", "see group G0001")]
    ctx.rows_by_label["lab-02"] = []
    assert K.set_reveals_link(ctx, "G-SHARED-GROUP", ["G0001"], _ptrs(ctx, ["lab-01", "lab-02"]))
    # edge endpoint pair: needs both endpoints' terms in ONE item; the owner's own name does not count
    ctx.rows_by_label["lab-30"] = [_row(4, "S0001", "lab 30 uses lab-31")]
    ctx.rows_by_label["lab-31"] = []
    assert not K.set_reveals_link(ctx, "G-DEPENDENCY", ["lab-30~lab-31"], _ptrs(ctx, ["lab-30", "lab-31"]))
    ctx.rows_by_label["lab-32"] = [_row(5, "S0001", "lab-30 and lab-31 both")]
    assert K.set_reveals_link(ctx, "G-DEPENDENCY", ["lab-30~lab-31"], _ptrs(ctx, ["lab-30", "lab-31", "lab-32"]))
    # opaque shape strings are NOT text tokens: the shape string in a quote does not count ...
    ctx.rows_by_label["lab-40"] = [_row(6, "S0001", "shape 1|OTHER|SET here")]
    ctx.rows_by_label["lab-41"] = []
    assert not K.set_reveals_link(ctx, "G-TYPE-SIM", ["1|OTHER|SET"], _ptrs(ctx, ["lab-40", "lab-41"]))
    # ... an evidence row whose type_signature normalizes to the defining shape does
    ctx.rows_by_label["lab-41"] = [_row(7, "S0002", "a", ts={"domain": "Q", "codomain": "2^S", "arity": 1})]
    assert K.set_reveals_link(ctx, "G-TYPE-SIM", ["1|OTHER|SET"], _ptrs(ctx, ["lab-40", "lab-41"]))
    # G-COCHANGE: a shared source id as a remaining pointer, or written in the text
    assert K.set_reveals_link(ctx, "G-COCHANGE", ["S0002"], _ptrs(ctx, ["lab-40", "lab-41"]))
    assert K.set_reveals_link(ctx, "G-COCHANGE", ["S0009"], {"x": [_row(8, "S0001", "cf. S0009, table 2")]})
    assert not K.set_reveals_link(ctx, "G-COCHANGE", ["S0009"], {"x": [_row(8, "S0001", "no source named")]})


def _blind_case(ctx, generator, cand_members, cand_tokens, ctrl_members):
    """One candidate + one control of `generator`, run through the real blind builder and blinding_levels."""
    cand = [{"record_type": "CANDIDATE", "candidate_id": f"{generator}#000000", "generator": generator,
             "members": cand_members, "in_budget": True, "in_test_family": True, "self_derived": False,
             "defining_property": {"property": "p", "link_tokens": cand_tokens}}]
    ctrl = [{"record_type": "CONTROL", "control_id": f"{generator}#000000/C1", "generator": generator,
             "for_candidate": f"{generator}#000000", "members": ctrl_members}]
    blind, reveal = K.build_blind(ctx, cand, ctrl)
    return blind, reveal, K.blinding_levels(ctx, blind, reveal, cand)[generator]


def test_blinding_required_cases():
    ctx = F.synthetic_ctx()
    # (1) COCHANGE: the candidate's shared source S0005 is stripped from its pointers -> no role cue -> FULL
    ctx.rows_by_label["lab-36"] = [_row(10, "S0005", "first"), _row(11, "S0001", "second")]
    ctx.rows_by_label["lab-37"] = [_row(12, "S0005", "third")]
    ctx.rows_by_label["lab-38"] = [_row(13, "S0002", "fourth")]
    ctx.rows_by_label["lab-39"] = [_row(14, "S0003", "fifth")]
    # M4-R1 scope A'1: lab-37's only row is the stripped token source -> the candidate unit is withheld
    blind, reveal, lv = _blind_case(ctx, "G-COCHANGE", ["lab-36", "lab-37"], ["S0005"], ["lab-38", "lab-39"])
    assert not any(b["members"] == ["lab-36", "lab-37"] for b in blind) and lv["sets_checked"] == 1
    ctx.rows_by_label["lab-37"] = [_row(12, "S0005", "third"), _row(15, "S0006", "sixth")]
    blind, reveal, lv = _blind_case(ctx, "G-COCHANGE", ["lab-36", "lab-37"], ["S0005"], ["lab-38", "lab-39"])
    assert lv["blinding_level"] == "FULL" and lv["sets_checked"] == 2
    rv = {r["unit_key"]: r for r in reveal}
    cand_unit = next(b for b in blind if b["members"] == ["lab-36", "lab-37"])
    assert rv[cand_unit["unit_key"]]["pointers_stripped"] == 2
    assert [p["source_id"] for ptrs in cand_unit["evidence"].values() for p in ptrs] == ["S0001", "S0006"]
    assert all("pointers_stripped" not in b for b in blind)                         # the count is in the reveal only
    # ... without stripping (the shared source still cited in the text) it is PARTIAL
    ctx.rows_by_label["lab-36"][1] = _row(11, "S0001", "as in S0005")
    assert _blind_case(ctx, "G-COCHANGE", ["lab-36", "lab-37"], ["S0005"], ["lab-38", "lab-39"])[2]["blinding_level"] == "PARTIAL"
    # (2) TYPE-SIM: the members' evidence shows the defining shape -> PARTIAL
    sig = {"domain": "S_t, Event", "codomain": "S_{t+1}", "arity": 2}
    ctx.rows_by_label["lab-42"] = [_row(20, "S0001", "a", ts=sig)]
    ctx.rows_by_label["lab-43"] = [_row(21, "S0002", "b", ts=sig)]
    ctx.rows_by_label["lab-44"] = [_row(22, "S0003", "c")]
    ctx.rows_by_label["lab-45"] = [_row(23, "S0004", "d")]
    shape = G.signature_shape(sig)
    assert _blind_case(ctx, "G-TYPE-SIM", ["lab-42", "lab-43"], [shape], ["lab-44", "lab-45"])[2]["blinding_level"] == "PARTIAL"
    # (3) NOTATION: a quote contains the shared symbol -> PARTIAL; without it -> FULL
    ctx.rows_by_label["lab-20"] = [_row(30, "S0001", "we write α for the rate")]
    ctx.rows_by_label["lab-21"] = [_row(31, "S0002", "plain text")]
    ctx.rows_by_label["lab-46"] = [_row(32, "S0003", "e")]
    ctx.rows_by_label["lab-47"] = [_row(33, "S0004", "f")]
    assert _blind_case(ctx, "G-NOTATION", ["lab-20", "lab-21"], ["α"], ["lab-46", "lab-47"])[2]["blinding_level"] == "PARTIAL"
    ctx.rows_by_label["lab-20"] = [_row(30, "S0001", "we write it for the rate")]
    assert _blind_case(ctx, "G-NOTATION", ["lab-20", "lab-21"], ["α"], ["lab-46", "lab-47"])[2]["blinding_level"] == "FULL"


# ------------------------------------------------------------------ G-13
def test_corpus_derivation_vocabulary():
    cls, sg, nt = "SHARED-INVARIANT", "G-SHARED-GROUP:SHARED-INVARIANT", "G-NOTATION:SHARED-INVARIANT"
    gens = ["G-SHARED-GROUP", "G-NOTATION"]
    d = lambda o: G13.corpus_derivation(cls, gens, o)[0]
    assert d({sg: "DIFFERENTIATED", nt: "DIFFERENTIATED"}) == "DIFFERENTIATED"
    assert d({sg: "DIFFERENTIATED", nt: "UNDIFFERENTIATED"}) == "UNDIFFERENTIATED"
    assert d({sg: "DIFFERENTIATED", nt: "UNDERPOWERED"}) == "UNDERPOWERED"
    assert d({sg: "UNDERPOWERED", nt: "REPORT-ONLY-PARTIAL-BLIND"}) == "REPORT-ONLY-PARTIAL-BLIND"
    assert d({sg: "UNDIFFERENTIATED", nt: "UNDERPOWERED"}) == "UNDERPOWERED"
    assert G13.corpus_derivation("EQUIVALENCE-CLASS", gens, {})[0] == "NOT-APPLICABLE"       # all DESCRIPTIVE
    assert "NOT-DIFFERENTIATED" not in open(G13.__file__, encoding="utf-8").read()


def _results():
    cells = []
    for c in CELLS.registered_cells():
        out = "DIFFERENTIATED" if c["cell_id"] in ("G-SHARED-GROUP:SHARED-INVARIANT", "G-NOTATION:SHARED-INVARIANT") \
            else "UNDIFFERENTIATED"
        cells.append({"cell_id": c["cell_id"], "generator": c["generator"], "class": c["class"], "outcome": out})
    body = {"artifact": "S5A-CELL-RESULTS.json", "cells": cells}
    h = {"output_sha256": C.sha256_bytes(json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False).encode())}
    return h, body


def _cross(rs, gen, cls, cell, outcome, sha, basis="DISCOVERY"):
    return {"rs_id": rs, "scale": "CROSS-OBJECT", "kind": "STRUCTURE-CANDIDATE",
            "participating_objects": [{"working_label": "lab-01", "source_evidence": [{"source_id": "S0001", "anchor": "q"}],
                                       "acceptance_state": "PROPOSED/H-06-ACCEPTED", "tier": "U"}],
            "derived_from_records": ["OB0004-R2:lab-01"], "common_structure": "x", "differences": [],
            "competing_explanation": "y", "disconfirmation": {"A": "done"}, "falsification_condition": "z",
            "temporal_scope": "t", "generator_basis": {"generator": gen, "basis": basis}, "structure_class": cls,
            "control_comparison": ({"cell_id": cell, "results_sha256": sha, "outcome": outcome}
                                   if basis == "DISCOVERY" else "NOT-APPLICABLE"), "research_status": "ANALYSED"}


def test_g13_positive_and_negative():
    h, body = _results()
    sha = h["output_sha256"]
    ok = _cross("R1", "G-SHARED-GROUP", "SHARED-INVARIANT", "G-SHARED-GROUP:SHARED-INVARIANT", "DIFFERENTIATED", sha)
    ok2 = _cross("R2", "G-NOTATION", "SHARED-INVARIANT", "G-NOTATION:SHARED-INVARIANT", "DIFFERENTIATED", sha)
    desc = _cross("R3", "G-SHARED-GROUP", "EQUIVALENCE-CLASS", None, None, sha, basis="DESCRIPTIVE")
    corpus = {**_cross("R4", "G-SHARED-GROUP", "SHARED-INVARIANT", None, None, sha), "scale": "CORPUS",
              "participating_findings": ["R1", "R2"], "underlying_objects": ["lab-01"],
              "independent_occurrences": {"count": 2, "list": [], "excluded": []}, "exceptions": [],
              "explanatory_content": "e",
              "control_comparison": {"cell_id": ["G-SHARED-GROUP:SHARED-INVARIANT", "G-NOTATION:SHARED-INVARIANT"],
                                     "results_sha256": sha, "outcome": "DIFFERENTIATED"}}
    hubs = {"lab-40"}
    assert G13.verify([ok, ok2, desc, corpus], h, body, hubs) == []

    def checks(rec, reg=None):
        return {v["check"] for v in G13.verify((reg or []) + [rec], h, body, hubs) if v["rs_id"] == rec["rs_id"]}
    wrong_cell = copy.deepcopy(ok); wrong_cell["control_comparison"]["cell_id"] = "G-NOTATION:SHARED-INVARIANT"
    assert "generator-class" in checks(wrong_cell)
    missing_cell = copy.deepcopy(ok); missing_cell["control_comparison"]["cell_id"] = "G-SHARED-GROUP:EQUIVALENCE-CLASS"
    assert "cell-id" in checks(missing_cell)                                          # DESCRIPTIVE: not registered
    wrong_hash = copy.deepcopy(ok); wrong_hash["control_comparison"]["results_sha256"] = "0" * 64
    assert "results-sha256" in checks(wrong_hash)
    cls_mis = copy.deepcopy(ok); cls_mis["structure_class"] = "SHARED-TRANSITION"
    assert "generator-class" in checks(cls_mis)
    out_mis = copy.deepcopy(ok); out_mis["control_comparison"]["outcome"] = "UNDIFFERENTIATED"
    assert "outcome" in checks(out_mis)
    hub = copy.deepcopy(ok); hub["derived_from_records"] = [{"record_type": "ABSENCE-RESOLUTION", "working_label": "lab-40",
                                                             "absence_dimension": "d1"}]
    assert "hub-absence" in checks(hub)
    hub_s = copy.deepcopy(ok); hub_s["derived_from_records"] = ["absence resolution of lab-40 dimension 3"]
    assert "hub-absence" in checks(hub_s)
    nonhub = copy.deepcopy(ok); nonhub["derived_from_records"] = [{"record_type": "ABSENCE-RESOLUTION", "working_label": "lab-41"}]
    assert "hub-absence" not in checks(nonhub)
    miss = copy.deepcopy(ok); del miss["falsification_condition"]
    assert "mandatory-content" in checks(miss)
    noanchor = copy.deepcopy(ok); noanchor["participating_objects"][0]["source_evidence"] = [{"source_id": "S0001"}]
    assert "mandatory-content" in checks(noanchor)
    mapping = copy.deepcopy(desc); mapping["generator_basis"]["basis"] = "DISCOVERY"
    assert "mapping" in checks(mapping)
    desc_cell = copy.deepcopy(desc); desc_cell["control_comparison"] = {"cell_id": "G-SHARED-GROUP:SHARED-INVARIANT"}
    assert "control-comparison" in checks(desc_cell)
    c_bad = copy.deepcopy(corpus); c_bad["control_comparison"]["cell_id"] = "G-SHARED-GROUP:SHARED-INVARIANT"
    assert "cell-id" in checks(c_bad, [ok, ok2])                                       # CORPUS needs a list
    c_out = copy.deepcopy(corpus); c_out["participating_findings"] = ["R1", "R5"]
    r5 = _cross("R5", "G-TYPE-SIM", "SHARED-INVARIANT", "G-TYPE-SIM:SHARED-INVARIANT", "UNDIFFERENTIATED", sha)
    c_out["control_comparison"]["cell_id"] = ["G-SHARED-GROUP:SHARED-INVARIANT", "G-TYPE-SIM:SHARED-INVARIANT"]
    assert "outcome" in checks(c_out, [ok, r5])                                        # item 8: every cell must be DIFF.
    c_out["control_comparison"]["outcome"] = "UNDIFFERENTIATED"               # both cells tested (frozen vocabulary)
    assert checks(c_out, [ok, r5]) == set()
    c_list = copy.deepcopy(corpus); c_list["control_comparison"]["cell_id"] = ["G-SHARED-GROUP:SHARED-INVARIANT"]
    assert "generator-class" in checks(c_list, [ok, ok2])                             # list != contributing cells
    body2 = copy.deepcopy(body); body2["cells"][0]["outcome"] = "DIFFERENTIATED"
    assert any(v["check"] == "results-artifact" for v in G13.verify([ok], h, body2, hubs))



def test_m4a_shared_source_stripping():
    """M4-A (G-LOG-0045): in units with a G-SHARED-GROUP role, pointers to sources shared by >= 2 members are stripped
    for candidates and controls alike (role-symmetric); other units are untouched; the build-time check refuses a
    retained shared pointer."""
    class Ctx:
        rows_by_label = {"a": [{"source_id": "S0001", "line": 1}, {"source_id": "S0002", "line": 2}],
                         "b": [{"source_id": "S0001", "line": 3}, {"source_id": "S0003", "line": 4}],
                         "c": [{"source_id": "S0001", "line": 5}, {"source_id": "S0004", "line": 6}],
                         "d": [{"source_id": "S0005", "line": 7}]}
    cand = [{"candidate_id": "g1", "generator": "G-SHARED-GROUP", "members": ["a", "b"], "in_budget": True,
             "in_test_family": True, "defining_property": {"property": "p", "link_tokens": ["G0001"]}},
            {"candidate_id": "n1", "generator": "G-NOTATION", "members": ["c", "d"], "in_budget": True,
             "in_test_family": True, "defining_property": {"property": "p", "link_tokens": ["x"]}}]
    ctrl = [{"record_type": "CONTROL", "generator": "G-SHARED-GROUP", "control_id": "k1", "for_candidate": "g1",
             "members": ["b", "c"]}]
    blind, reveal = K.build_blind(Ctx, cand, ctrl)
    by_members = {tuple(b["members"]): b for b in blind}
    rv = {tuple(r["members"]): r for r in reveal}
    for m in (("a", "b"), ("b", "c")):                         # candidate and control: shared S0001 stripped from both
        srcs = [p["source_id"] for ptrs in by_members[m]["evidence"].values() for p in ptrs]
        assert "S0001" not in srcs and rv[m]["shared_source_pointers_stripped"] == 2
    srcs = [p["source_id"] for ptrs in by_members[("c", "d")]["evidence"].values() for p in ptrs]
    assert "S0001" in srcs and rv[("c", "d")]["shared_source_pointers_stripped"] == 0   # non-SHARED-GROUP untouched
    bad = [dict(b) for b in blind]
    for b in bad:
        if tuple(b["members"]) == ("a", "b"):
            b["evidence"] = {"a": [{"source_id": "S0009", "row_line": 1}], "b": [{"source_id": "S0009", "row_line": 3}]}
    try:
        K.check_m4a(bad, reveal)
        raise AssertionError("check_m4a accepted a retained shared pointer")
    except C.S5Error:
        pass



def test_m4_residual_r1_cap():
    """M4-R1, scope A'1 (§26 annex): EVERY candidate/control unit of any generator with a member left without a
    pointer (after M4-A on G-SHARED-GROUP units) is withheld, for both roles; every remaining candidate/control unit
    shows exactly one pointer per member (the lowest (source_id, row_line)); the pools remove withheld units
    symmetrically; the build-time check refuses violations."""
    class Ctx:
        rows_by_label = {"a": [{"source_id": "S0001", "line": 1}],                                  # only shared rows
                         "b": [{"source_id": "S0001", "line": 2}, {"source_id": "S0007", "line": 9}],
                         "c": [{"source_id": "S0005", "line": 4}, {"source_id": "S0003", "line": 3},
                               {"source_id": "S0003", "line": 1}],
                         "d": [{"source_id": "S0006", "line": 5}, {"source_id": "S0008", "line": 6}],
                         "e": [{"source_id": "S0009", "line": 7}, {"source_id": "S0010", "line": 8}],
                         "f": []}
    def cand(cid, gen, members):
        return {"candidate_id": cid, "generator": gen, "members": members, "in_budget": True, "in_test_family": True,
                "defining_property": {"property": "p", "link_tokens": ["x"]}}
    cands = [cand("g1", "G-SHARED-GROUP", ["a", "b"]), cand("g2", "G-SHARED-GROUP", ["c", "d"]),
             cand("n1", "G-NOTATION", ["d", "e"])]
    ctrls = [{"record_type": "CONTROL", "generator": "G-SHARED-GROUP", "control_id": "k1", "for_candidate": "g2",
              "members": ["a", "e"]},                                                    # 'a' keeps S0001: not shared here
             {"record_type": "CONTROL", "generator": "G-SHARED-GROUP", "control_id": "k2", "for_candidate": "g1",
              "members": ["b", "e"]},
             {"record_type": "CONTROL", "generator": "G-NOTATION", "control_id": "k3", "for_candidate": "n1",
              "members": ["e", "f"]}]                                                      # G-NOTATION control, 'f' empty
    blind, reveal = K.build_blind(Ctx, cands, ctrls)
    by = {tuple(b["members"]): b for b in blind}
    assert ("a", "b") not in by                                                  # candidate withheld (a empty after M4-A)
    assert K.withheld_sets(Ctx, cands, ctrls) == [("a", "b"), ("e", "f")]            # A'1: G-NOTATION unit too
    assert ("e", "f") not in by
    assert by[("c", "d")]["evidence"] == {"c": [{"source_id": "S0003", "row_line": 1, "anchor": None}],
                                          "d": [{"source_id": "S0006", "row_line": 5, "anchor": None}]}   # cap 1, lowest
    assert all(len(v) == 1 for u in (("a", "e"), ("b", "e")) for v in by[u]["evidence"].values())      # controls capped too
    assert by[("d", "e")]["evidence"]["d"] == [{"source_id": "S0006", "row_line": 5, "anchor": None}]   # A'1: capped
    # SELF-DERIVED-only units are outside M4-R1: never withheld, never capped
    sd = [dict(cand("s1", "G-SHARED-GROUP", ["a", "c"]), self_derived=True, in_budget=False)]
    blind_sd, _ = K.build_blind(Ctx, sd, [])
    assert K.withheld_sets(Ctx, sd, []) == [] and len(blind_sd[0]["evidence"]["c"]) == 3
    # role symmetry: the same members as a CONTROL are withheld too
    ctrl_only = [{"record_type": "CONTROL", "generator": "G-SHARED-GROUP", "control_id": "k9", "for_candidate": "g2",
                  "members": ["a", "b"]}]
    assert K.withheld_sets(Ctx, [cands[1]], ctrl_only) == [("a", "b")]
    # pools: the withheld unit is removed symmetrically with cause WITHHELD-M4R1
    retained, attr = CELLS.symmetric_removal(
        [{"candidate_id": "g1", "candidate": CELLS.set_key(["a", "b"]), "controls": [CELLS.set_key(["b", "e"])]}],
        lambda u: "WITHHELD-M4R1" if u == CELLS.set_key(["a", "b"]) else None)
    assert not retained and attr["by_cause_sets"] == {"CANDIDATE-WITHHELD-M4R1": 1}
    # build-time check refuses an uncapped or empty-member G-SHARED-GROUP unit
    for bad_ev in ({"c": [{"source_id": "S0003", "row_line": 1}, {"source_id": "S0005", "row_line": 4}],
                    "d": [{"source_id": "S0006", "row_line": 5}]},
                   {"c": [], "d": [{"source_id": "S0006", "row_line": 5}]}):
        bad = [dict(b, evidence=bad_ev) if tuple(b["members"]) == ("c", "d") else b for b in blind]
        try:
            K.check_m4a(bad, reveal)
            raise AssertionError("check_m4a accepted a cap / R1 violation")
        except C.S5Error:
            pass


if __name__ == "__main__":
    sys.exit(F.run_all(dict(globals())))
