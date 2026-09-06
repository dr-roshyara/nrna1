"""RQ1-RQ8, H1-H11, adversarial A-J.  Every failure persists a minimal witness."""
import itertools, json, random
from zero_algebra import *
from generators import corpus

SEED = 20260902
N    = 1200                      # >= 1000 per family, per spec S4
TSET = list(TRANSFORMS); PSET = list(CONTRACTS)

def _w(kind, **kw): return {"witness_kind": kind, **kw}
def _rep(D): return [dict(token=i.token, source=i.source, uncertainty=i.uncertainty,
                          scope=i.scope, polarity=i.polarity) for i in D.items]

# ---------------------------------------------------------------- vacuity guard
def E0_vacuity():
    rng = random.Random(SEED); C = corpus(N, SEED); hits = []
    for c in C:
        t = rng.choice(TSET); p = rng.choice(PSET)
        if contract_is_vacuous(c["rep"], t, p):
            hits.append({"case": c["id"], "T": t, "Pi": p, "n": len(c["rep"])})
    return {"question": "are any (T,Pi) pairings VACUOUS -- every element Zero because "
                        "the transformation destroyed what the contract reads?",
            "n_cases": len(C), "n_vacuous": len(hits), "examples": hits[:5],
            "rule": ("a vacuous pairing must be EXCLUDED from algebraic conclusions: "
                     "'everything is Zero' there is an artefact, not a finding"),
            "excluded_pairs": sorted({(h["T"], h["Pi"]) for h in hits})}

def _valid(D, t, p): return not contract_is_vacuous(D, t, p)

# ---------------------------------------------------------------- RQ1 / H11
def E1_path_a_vs_path_b():
    rng = random.Random(SEED+1); C = corpus(N, SEED+1)
    agree = dis_ft = dis_tf = tot = 0; wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        for i in range(len(D)):
            f = zero(D, t, p, {i}); r = rule_eliminable(D, t, p, i); tot += 1
            if f == r: agree += 1
            elif f and not r:
                dis_ft += 1
                if len(wit) < 8: wit.append(_w("formal_zero_rule_false", case=c["id"],
                    T=t, Pi=p, idx=i, D=_rep(D)))
            else:
                dis_tf += 1
                if len(wit) < 16: wit.append(_w("rule_true_formal_nonzero", case=c["id"],
                    T=t, Pi=p, idx=i, D=_rep(D)))
    return {"question": "RQ1/H11 -- does the declared rule coincide with counterfactual Zero?",
            "n_element_tests": tot, "agree": agree,
            "formal_zero_but_rule_false": dis_ft, "rule_true_but_formal_nonzero": dis_tf,
            "agreement_rate": round(agree/tot, 4) if tot else None,
            "H11": "CONFIRMED" if dis_ft+dis_tf == 0 else "REFUTED",
            "witnesses": wit}

# ---------------------------------------------------------------- RQ2 / H1
def E2_idempotence():
    rng = random.Random(SEED+2); C = corpus(N, SEED+2)
    res = {op: {"idempotent":0, "non_idempotent":0} for op in OPERATORS}
    wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        for op, fn in OPERATORS.items():
            d1 = fn(D, t, p); d2 = fn(d1, t, p)
            if d1 == d2: res[op]["idempotent"] += 1
            else:
                res[op]["non_idempotent"] += 1
                if len(wit) < 10: wit.append(_w("non_idempotent", op=op, case=c["id"],
                    T=t, Pi=p, D=_rep(D), L1=_rep(d1), L2=_rep(d2)))
    verdict = {op: ("CONFIRMED" if v["non_idempotent"] == 0 else "REFUTED")
               for op, v in res.items()}
    return {"question": "RQ2/H1 -- is L idempotent?", "by_operator": res,
            "H1_by_operator": verdict, "witnesses": wit}

# ---------------------------------------------------------------- RQ3 / H2
def E3_order_independence():
    rng = random.Random(SEED+3); C = corpus(N, SEED+3)
    comm = non = 0; wit = []; mech = {}
    pairs = list(itertools.combinations(TSET, 2))
    for c in C:
        D = c["rep"]; p = rng.choice(PSET); A, B = rng.choice(pairs)
        if not (_valid(D, A, p) and _valid(D, B, p)): continue
        ab = L_sequential(L_sequential(D, B, p), A, p)
        ba = L_sequential(L_sequential(D, A, p), B, p)
        if ab == ba: comm += 1
        else:
            non += 1; key = f"{A}|{B}"; mech[key] = mech.get(key, 0) + 1
            if len(wit) < 10: wit.append(_w("non_commuting", case=c["id"], A=A, B=B,
                Pi=p, D=_rep(D), AB=_rep(ab), BA=_rep(ba)))
    return {"question": "RQ3/H2 -- do elimination operators commute?",
            "commuting": comm, "non_commuting": non,
            "H2": "CONFIRMED" if non == 0 else "REFUTED",
            "non_commuting_pairs_ranked": sorted(mech.items(), key=lambda kv:-kv[1])[:8],
            "witnesses": wit}

# ---------------------------------------------------------------- RQ4 / H3,H4
def E4_fixed_point():
    rng = random.Random(SEED+4); C = corpus(N, SEED+4)
    conv = cyc = nonconv = stable = 0; iters = []; wit = []
    monotone_violations = 0
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        seen = [D]; cur = D; out = None
        for k in range(50):
            nxt = L_simultaneous(cur, t, p)
            if len(nxt) > len(cur): monotone_violations += 1
            if nxt == cur:
                out = ("stable" if k == 0 else "converged", k); break
            if nxt in seen:
                out = ("cycle", k); break
            seen.append(nxt); cur = nxt
        if out is None: out = ("non_converged", 50)
        kind, k = out
        if kind == "stable": stable += 1
        elif kind == "converged": conv += 1; iters.append(k)
        elif kind == "cycle":
            cyc += 1
            if len(wit) < 6: wit.append(_w("cycle", case=c["id"], T=t, Pi=p,
                                           D=_rep(D), at_iteration=k))
        else: nonconv += 1
    return {"question": "RQ4/H3,H4 -- does iterated elimination reach a fixed point?",
            "immediately_stable": stable, "converged": conv, "cycles": cyc,
            "non_converged": nonconv,
            "max_iterations_to_converge": max(iters) if iters else 0,
            "H3": "CONFIRMED" if cyc == 0 and nonconv == 0 else "REFUTED",
            "H4_monotone_decreasing": "CONFIRMED" if monotone_violations == 0 else "REFUTED",
            "monotone_violations": monotone_violations, "witnesses": wit}

# ---------------------------------------------------------------- RQ6
def E6_elimination_order():
    rng = random.Random(SEED+6); C = corpus(N, SEED+6)
    same = diff = 0; wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        sim = L_simultaneous(D, t, p)
        fwd = L_sequential(D, t, p, "forward")
        rev = L_sequential(D, t, p, "reverse")
        if sim == fwd == rev: same += 1
        else:
            diff += 1
            if len(wit) < 10: wit.append(_w("order_matters", case=c["id"], T=t, Pi=p,
                D=_rep(D), simultaneous=_rep(sim), forward=_rep(fwd), reverse=_rep(rev)))
    return {"question": "RQ6 -- simultaneous vs sequential vs reverse elimination",
            "identical": same, "divergent": diff,
            "verdict": "CONFIRMED identical" if diff == 0 else "REFUTED -- order matters",
            "witnesses": wit}

# ---------------------------------------------------------------- RQ7 / H8
def E7_contract_sensitivity():
    rng = random.Random(SEED+7); C = corpus(N, SEED+7)
    same = diff = 0; wit = []; pairdiff = {}
    ppairs = list(itertools.combinations(PSET, 2))
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p1, p2 = rng.choice(ppairs)
        if not (_valid(D, t, p1) and _valid(D, t, p2)): continue
        for i in range(len(D)):
            a, b = zero(D, t, p1, {i}), zero(D, t, p2, {i})
            if a == b: same += 1
            else:
                diff += 1; k = f"{p1}|{p2}"; pairdiff[k] = pairdiff.get(k, 0)+1
                if len(wit) < 10: wit.append(_w("contract_relative", case=c["id"], T=t,
                    Pi1=p1, Pi2=p2, idx=i, zero_under_Pi1=a, zero_under_Pi2=b, D=_rep(D)))
    return {"question": "RQ7/H8 -- is Zero independent of contract detail?",
            "same": same, "different": diff,
            "H8": "CONFIRMED" if diff == 0 else "REFUTED -- Zero IS contract-relative",
            "most_divergent_contract_pairs": sorted(pairdiff.items(), key=lambda kv:-kv[1])[:8],
            "witnesses": wit}

# ---------------------------------------------------------------- RQ8 boundary
def E8_boundary_preservation():
    """same positive result + changed boundary => NOT Zero."""
    rng = random.Random(SEED+8); C = corpus(N, SEED+8)
    tested = upheld = violated = 0; wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET)
        if not (_valid(D, t, "P1_result") and _valid(D, t, "P8_composite")): continue
        for i in range(len(D)):
            same_result = zero(D, t, "P1_result", {i})
            same_full   = zero(D, t, "P8_composite", {i})
            if same_result:
                tested += 1
                if not same_full:
                    upheld += 1
                    if len(wit) < 10: wit.append(_w("boundary_blocks_elimination",
                        case=c["id"], T=t, idx=i, D=_rep(D)))
                else: violated += 1
    return {"question": "RQ8 -- does boundary information prevent otherwise-tempting eliminations?",
            "cases_where_result_unchanged": tested,
            "blocked_by_boundary": upheld, "still_zero_under_full_contract": violated,
            "rate_blocked": round(upheld/tested, 4) if tested else None,
            "witnesses": wit}

# ---------------------------------------------------------------- group / H5,H6
def E9_group_zero():
    rng = random.Random(SEED+9); C = corpus(N, SEED+9)
    h5_ok = h5_bad = h6_ok = h6_bad = 0; wI = []; wJ = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p) or len(D) < 2: continue
        for i, j in itertools.combinations(range(len(D)), 2):
            zi, zj = zero(D,t,p,{i}), zero(D,t,p,{j})
            zij = zero(D,t,p,{i,j})
            if zi and zj:
                if zij: h5_ok += 1
                else:
                    h5_bad += 1
                    if len(wI) < 8: wI.append(_w("CASE_I_individually_zero_jointly_not",
                        case=c["id"], T=t, Pi=p, i=i, j=j, D=_rep(D)))
            if (not zi) and (not zj):
                if zij:
                    h6_bad += 1
                    if len(wJ) < 8: wJ.append(_w("CASE_J_individually_nonzero_jointly_zero",
                        case=c["id"], T=t, Pi=p, i=i, j=j, D=_rep(D)))
                else: h6_ok += 1
    return {"question": "H5/H6 -- is Zero element-wise?",
            "H5_individual_implies_group": {"holds": h5_ok, "violated": h5_bad,
                "verdict": "CONFIRMED" if h5_bad == 0 else "REFUTED"},
            "H6_group_decomposes_to_individual": {"holds": h6_ok, "violated": h6_bad,
                "verdict": "CONFIRMED" if h6_bad == 0 else "REFUTED"},
            "case_I_witnesses": wI, "case_J_witnesses": wJ}

# ---------------------------------------------------------------- H7 reference
def E10_reference_relativity():
    rng = random.Random(SEED+10); C = corpus(N, SEED+10)
    same = diff = 0; wit = []
    TRANSFORMS["_T5_refB"] = lambda D: T5_reference(D, REFERENCE_B)
    for c in C:
        D = c["rep"]; p = rng.choice(PSET)
        for i in range(len(D)):
            a = zero(D, "T5_reference", p, {i}); b = zero(D, "_T5_refB", p, {i})
            if a == b: same += 1
            else:
                diff += 1
                if len(wit) < 8: wit.append(_w("reference_relative", case=c["id"], Pi=p,
                    idx=i, zero_ref_A=a, zero_ref_B=b, D=_rep(D)))
    del TRANSFORMS["_T5_refB"]
    return {"question": "H7 -- is Zero invariant under change of reference frame?",
            "same": same, "different": diff,
            "H7": "CONFIRMED" if diff == 0 else "REFUTED -- Zero IS reference-relative",
            "witnesses": wit}

# ---------------------------------------------------------------- H9 invariant vs Zero
def E11_invariant_vs_zero():
    rng = random.Random(SEED+11); C = corpus(N, SEED+11)
    tab = {"inv_and_zero":0, "inv_not_zero":0, "zero_not_inv":0, "neither":0}
    wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        out = TRANSFORMS[t](D)
        for i, it in enumerate(D.items):
            invariant = it in out.items                 # survives T unchanged
            z = zero(D, t, p, {i})
            k = ("inv_and_zero" if invariant and z else "inv_not_zero" if invariant else
                 "zero_not_inv" if z else "neither")
            tab[k] += 1
            if k in ("inv_not_zero","zero_not_inv") and len(wit) < 10:
                wit.append(_w("invariant_zero_differ", case=c["id"], T=t, Pi=p, idx=i,
                              invariant=invariant, zero=z, D=_rep(D)))
    coincide = tab["inv_not_zero"] == 0 and tab["zero_not_inv"] == 0
    return {"question": "H9 -- do 'invariant under T' and 'Zero under Pi' coincide?",
            "cross_tabulation": tab,
            "H9": "CONFIRMED" if coincide else "REFUTED -- they are independent classifications",
            "witnesses": wit}

# ---------------------------------------------------------------- H10 remainder
def E12_remainder():
    rng = random.Random(SEED+12); C = corpus(N, SEED+12)
    agree_ab = agree_ac = agree_bc = tot = 0; wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET); p = rng.choice(PSET)
        if not _valid(D, t, p): continue
        tot += 1
        A = sorted(i.token for i in L_simultaneous(D, t, p).items)   # D - Eliminated
        B = sorted(i.token for i in TRANSFORMS[t](D).items)          # transformation residual
        Cc= sorted(D.items[i].token for i in range(len(D))
                   if not zero(D, t, p, {i}))                        # contract-unresolved
        agree_ab += (A == B); agree_ac += (A == Cc); agree_bc += (B == Cc)
        if not (A == B == Cc) and len(wit) < 10:
            wit.append(_w("remainder_constructions_diverge", case=c["id"], T=t, Pi=p,
                          A_not_eliminated=A, B_transformation_residual=B,
                          C_contract_unresolved=Cc, D=_rep(D)))
    return {"question": "H10 -- do the three Remainder constructions coincide?",
            "n": tot, "A_equals_B": agree_ab, "A_equals_C": agree_ac, "B_equals_C": agree_bc,
            "all_three_coincide": agree_ab == agree_ac == agree_bc == tot,
            "H10": "CONFIRMED" if agree_ab == tot else "REFUTED -- Remainder != D - Eliminated",
            "witnesses": wit}

# ---------------------------------------------------------------- secondary: regimes
def E13_reasoning_regimes():
    """R_classical: a contradictory state has NO determinate content.
       R_paraconsistent: a contradictory state retains its non-contradictory content."""
    def Pi_classical(D):
        pos = any(i.polarity is True for i in D.items)
        neg = any(i.polarity is False for i in D.items)
        return ("EXPLOSION",) if (pos and neg) else tuple(i.token for i in D.items)
    def Pi_paraconsistent(D):
        return (tuple(i.token for i in D.items),
                "conflicting" if (any(i.polarity is True for i in D.items) and
                                  any(i.polarity is False for i in D.items)) else "ok")
    CONTRACTS["_Rc"] = Pi_classical; CONTRACTS["_Rp"] = Pi_paraconsistent
    rng = random.Random(SEED+13); C = corpus(N, SEED+13)
    same = diff = 0; wit = []
    for c in C:
        D = c["rep"]; t = rng.choice(TSET)
        for i in range(len(D)):
            a, b = zero(D, t, "_Rc", {i}), zero(D, t, "_Rp", {i})
            if a == b: same += 1
            else:
                diff += 1
                if len(wit) < 8: wit.append(_w("regime_relative", case=c["id"], T=t, idx=i,
                    zero_classical=a, zero_paraconsistent=b, D=_rep(D)))
    del CONTRACTS["_Rc"]; del CONTRACTS["_Rp"]
    return {"question": "SECONDARY -- can Zero differ between reasoning regimes?",
            "same": same, "different": diff,
            "verdict": "regime-relative [EXP]" if diff else "no regime difference observed",
            "caveat": ("a difference here is only a legitimate REGIME difference if it "
                       "survives checks on applicability, representation, contract, "
                       "composition and semantics -- see results.md"),
            "witnesses": wit}
