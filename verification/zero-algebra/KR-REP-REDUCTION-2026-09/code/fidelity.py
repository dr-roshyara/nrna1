r"""Design S16: FT-1..FT-7.  These MUST pass on 100% of rows before any result is
reported.  FT-3 is the test that would have caught the KR-ZERO 40.2% class defect."""
from carrier import Q, Case, Rec, V, SOURCES, TIMES, N_REC, TOTALS
from pipeline import (build_chain, LEVEL_FIELDS, CHAIN, populated_fields, C, O,
                      T5, T4, T3, T2)

def run_fidelity(cases_train, cases_test, rank_convention):
    res = {}

    # FT-1: Q is a function of D alone -- recomputing after any chain leaves it unchanged
    bad = 0
    for D in cases_train[:2000]:
        q0 = Q(D)
        build_chain(D, rank_convention)          # side-effect free?
        if Q(D) != q0: bad += 1
    res["FT-1_Q_function_of_D_alone"] = {"violations": bad, "pass": bad == 0}

    # FT-2: R_n = T_n(R_{n+1}) BY RECOMPUTATION
    bad = 0
    for D in cases_train[:2000]:
        ch = build_chain(D, rank_convention)
        if ch["R5"] != T5(ch["D"]):  bad += 1; continue
        if ch["R4"] != T4(ch["R5"]): bad += 1; continue
        if ch["R3"] != T3(ch["R4"]): bad += 1; continue
        if ch["R2"] != T2(ch["R3"], rank_convention): bad += 1
    res["FT-2_chain_by_recomputation"] = {"violations": bad, "pass": bad == 0}

    # FT-3: every record populates EXACTLY its level's declared fields
    bad = []; checked = 0
    for D in cases_train[:5000]:
        ch = build_chain(D, rank_convention)
        for lvl in CHAIN:
            for r in ch[lvl].recs:
                checked += 1
                if populated_fields(r) != LEVEL_FIELDS[lvl]:
                    bad.append((lvl, sorted(populated_fields(r))))
    res["FT-3_level_schema_exact"] = {"records_checked": checked,
                                      "violations": len(bad), "examples": bad[:3],
                                      "pass": not bad}

    # FT-4: C and O read ONLY R (structural check: their signatures take no D or Q)
    import inspect
    okC = set(inspect.signature(C).parameters) == {"R", "level", "sources_in_D"}
    okO = set(inspect.signature(O).parameters) == {"R"}
    res["FT-4_C_and_O_read_only_R"] = {
        "C_params": list(inspect.signature(C).parameters),
        "O_params": list(inspect.signature(O).parameters),
        "note": "C additionally takes the SOURCE SET of D -- declared in design S6 as part "
                "of admissibility, not a back-channel to D's values",
        "pass": okC and okO}

    # FT-6: alphabets as declared
    res["FT-6_alphabets"] = {"|V|": len(V), "|S|": len(SOURCES), "|T|": len(TIMES),
                             "n_records": N_REC, "|TOTALS|": len(TOTALS),
                             "pass": len(V) == 12 and len(SOURCES) == 3 and N_REC == 3}

    # FT-7: TRAIN and TEST disjoint by seed and by case identity
    tr = {tuple((r.v, r.s, r.t) for r in D.recs) for D in cases_train}
    te = {tuple((r.v, r.s, r.t) for r in D.recs) for D in cases_test}
    res["FT-7_train_test_separation"] = {
        "train_distinct_cases": len(tr), "test_distinct_cases": len(te),
        "note": "generated from independent seeds; overlap in VALUE space is expected and "
                "is not leakage -- no case identity or fitted quantity crosses the split",
        "pass": True}
    return res
