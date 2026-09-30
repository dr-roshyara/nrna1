#!/usr/bin/env python3
"""R7 bounded context STATISTICS — "is the mathematical question defined?" (frozen addendum v2.3 §8; HD-6; DR-14/15).

estimate_v7 : Input → {STATISTICS-REJECTED, STATISTICS-NOT-ESTIMABLE, STATISTICS-VALID}. It is the only production path to
an estimate of adjudicated discordance. Formulas are UNCHANGED from the frozen specification (stratified HT; SRSWOR
variance Σ N_h²(1 − n_h/N_h) s_h²/n_h; hypergeometric zero bounds). v2.5-S (G-LOG-0093): the PRIMARY statement is the
exact finite-population upper bound (per stratum at α/H′, Bonferroni-combined); the normal 95% CI with FPC is reported only
when every sampled stratum has d ≥ 5 and n − d ≥ 5 (otherwise informational). This module
only enforces the domain: frozen frame (hash), the five strata names (TIER-X is a reporting domain, never a key), a
partition derived from the plan (MULTIROW = DECOMPOSED with row sources over > 1 unit), rates in [0, 1] (no cap),
realized n_h = round_half_up(rate · N_h) with π_h = n_h/N_h, a frozen record whose hash equals the governance anchor,
and the ACTUAL sample argument hashed against the frozen sample (the ML-exclusion guard). N_h = 0 is an empty stratum.
n_h = 0 < N_h ⇒ NOT-ESTIMABLE; n_h = 1 < N_h ⇒ variance and CI undefined ⇒ NOT-ESTIMABLE (point estimate informational).
No partial-identification bounds (HD-6). No ML. Depends on Universe only for its plan-derived frame attributes.
"""
import decimal
import fractions
import hashlib
import importlib.util
import json
import math
import os
import random
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("p3b_s5_r7_universe", os.path.join(_HERE, "p3b_s5_r7_universe.py"))
U = importlib.util.module_from_spec(_s)
_s.loader.exec_module(U)
r6 = U.r6
STRATA = ("HUB", "EMPTY", "MULTIROW", "DECOMPOSED", "SINGLE")


def sha(b):
    return hashlib.sha256(b).hexdigest()


def canon(x):
    return json.dumps(x, sort_keys=True, separators=(",", ":"), ensure_ascii=False).encode("utf-8")


def frame_attributes(plan, slices):
    """{label: {path, hub, multirow}} DERIVED from the plan and slices (never declared)."""
    out = {}
    for lab, L in plan["labels"].items():
        rows = set(L.get("row_sources") or [])
        units_with_rows = sum(1 for u in L.get("units") or [] if rows & set(u))
        out[lab] = {"path": L["path"], "hub": bool((slices.get(lab) or {}).get("hub")),
                    "multirow": L["path"] == "DECOMPOSED" and units_with_rows > 1}
    return out


def assign(frame):
    out = {h: [] for h in STRATA}
    for lab, a in sorted(frame.items()):
        h = "HUB" if a.get("hub") else "EMPTY" if a.get("path") == "EMPTY" else \
            "MULTIROW" if a.get("multirow") else "DECOMPOSED" if a.get("path") == "DECOMPOSED" else "SINGLE"
        out[h].append(lab)
    return out


def realized_n(rate, N):
    if rate == "CENSUS" or rate == 1:
        return N
    if isinstance(rate, bool) or not isinstance(rate, (int, float)) or not 0 <= rate <= 1:
        raise ValueError(f"rate {rate!r} outside [0, 1]")
    return int(decimal.Decimal(repr(rate * N)).quantize(decimal.Decimal(1), rounding=decimal.ROUND_HALF_UP))


def exact_upper_bound(d, n, N, alpha):
    """v2.5-S (G-LOG-0093): the exact one-sided (1 − alpha) finite-population upper bound on the stratum count D after d
    discordant in an SRSWOR sample of n from N: the largest D with P(X ≤ d | N, D, n) ≥ alpha (hypergeometric; the CDF is
    non-increasing in D). n = 0 → N (no information); census n = N → d (exact). Exact rational arithmetic."""
    if n <= 0:
        return N
    if n >= N:
        return d
    a = fractions.Fraction(repr(alpha))
    tot = math.comb(N, n)
    last = d
    for D in range(d, N - (n - d) + 1):
        if fractions.Fraction(sum(math.comb(D, k) * math.comb(N - D, n - k) for k in range(0, d + 1)), tot) < a:
            break
        last = D
    return last


def freeze(frame, rates, seed, spec_text, alpha=0.05):
    """The frozen record (ST11 complete): seed, spec hash, Python, frame hash, α (v2.5-S), per-stratum N, n, rate,
    members, sample."""
    members = assign(frame)
    rng = random.Random(seed)
    strata, sample = {}, {}
    for h in STRATA:
        pop = sorted(members[h])
        rate = rates.get(h, 0)
        n = realized_n(rate, len(pop))
        s = sorted(rng.sample(pop, n)) if n else []
        strata[h] = {"N": len(pop), "n": n, "rate": rate, "members": pop, "sample": s}
        sample[h] = s
    return {"seed": seed, "alpha": alpha, "spec_sha256": sha(spec_text.encode("utf-8")), "python": sys.version.split()[0],
            "frame_sha256": sha(canon(sorted(frame))), "strata": strata, "sample": sample, "sample_sha256": sha(canon(sample))}


def ht_stratum(N, n, d):
    """HT stratum total and its unbiased SRSWOR variance estimate (census → 0; n ≤ 1 → None)."""
    t = N * d / n
    if n >= N:
        return t, 0.0
    if n <= 1:
        return t, None
    p = d / n
    s2 = p * (1 - p) * n / (n - 1)
    return t, N * N * (1 - n / N) * s2 / n


def estimate_v7(rec, anchor, frame, sample, outcomes):
    def rej(why):
        return {"verdict": "STATISTICS-REJECTED", "reason": why}
    if sha(canon(rec)) != anchor:
        return rej("frozen record hash ≠ the governance anchor")
    if sha(canon(sorted(frame))) != rec.get("frame_sha256"):
        return rej("frame ≠ the frozen frame (missing or extra label)")
    alpha = rec.get("alpha")
    if isinstance(alpha, bool) or not isinstance(alpha, (int, float)) or not 0 < alpha < 1:
        return rej(f"frozen alpha {alpha!r} missing or outside (0, 1) (v2.5-S)")
    st = rec.get("strata") or {}
    if set(st) - set(STRATA):
        return rej(f"stratum names outside the five: {sorted(set(st) - set(STRATA))} (TIER-X is a reporting domain)")
    allocated = [l for h in st.values() for l in h.get("members") or []]
    if len(allocated) != len(set(allocated)) or set(allocated) != set(frame):
        return rej("strata are not a partition of the frame")
    derived = assign(frame)
    for h in STRATA:
        if sorted((st.get(h) or {}).get("members") or []) != derived[h]:
            return rej(f"stratum {h} membership ≠ the plan-derived membership")
        try:
            if (st.get(h) or {}).get("n") != realized_n((st.get(h) or {}).get("rate"), len(derived[h])):
                return rej(f"stratum {h}: frozen n ≠ realized round_half_up(rate · N)")
        except ValueError as e:
            return rej(str(e))
    if sha(canon(sample)) != rec.get("sample_sha256") or sample != rec.get("sample"):
        return rej("the sample argument is not the frozen sample (hash of the actual sample)")
    seen = set()
    for h in STRATA:
        s = sample.get(h) or []
        if not set(s) <= set(derived[h]) or len(s) != st[h]["n"] or seen & set(s):
            return rej(f"stratum {h}: sample not ⊆ its members, wrong size, or overlapping")
        seen |= set(s)
    if set(outcomes) != seen or any(v not in (0, 1) for v in outcomes.values()):
        return rej("outcomes must be exactly the sampled labels, valued 0/1 (no labels from outside the sample)")
    tot, var, N_all, per, info, undefined = 0.0, 0.0, len(frame), {}, {}, []
    for h in STRATA:
        N, n = st[h]["N"], st[h]["n"]
        if N == 0:
            continue
        if n == 0:
            undefined.append(f"{h}: n = 0 < N = {N} (τ not identified)")
            continue
        d = sum(outcomes[l] for l in sample[h])
        t, v = ht_stratum(N, n, d)
        per[h] = {"N": N, "n": n, "pi": n / N, "discordant": d, "ht_total": t, "var": v,
                  "zero_bound_share": r6.zero_bound_v6(n, N) if d == 0 else None}
        if v is None:
            undefined.append(f"{h}: n = 1 < N = {N} (variance undefined)")
            info[h] = t
            continue
        tot += t
        var += v
    if undefined:
        return {"verdict": "STATISTICS-NOT-ESTIMABLE", "reason": "; ".join(undefined),
                "informational": {"point_estimates": info, "strata": per}}
    share = tot / N_all
    se = math.sqrt(var) / N_all
    # v2.5-S (G-LOG-0093): the PRIMARY statement is the exact bound — census strata contribute d_h exactly; each sampled
    # stratum its exact bound at α / H′ (Bonferroni over the H′ sampled strata; P(D ≤ U) ≥ 1 − α by the union bound)
    sampled = [h for h in per if per[h]["n"] < per[h]["N"]]
    a_h = alpha / len(sampled) if sampled else alpha
    ub = 0
    for h, s in per.items():
        s["upper_bound"] = exact_upper_bound(s["discordant"], s["n"], s["N"], a_h)
        ub += s["upper_bound"]
    ci = (max(0.0, share - 1.96 * se), min(1.0, share + 1.96 * se))
    ok = all(per[h]["discordant"] >= 5 and per[h]["n"] - per[h]["discordant"] >= 5 for h in sampled)
    return {"verdict": "STATISTICS-VALID",
            "estimate": {"ht_total": tot, "var_total": var, "share": share, "alpha": alpha, "alpha_per_sampled_stratum": a_h,
                         "upper_bound_total": ub, "upper_bound_share": ub / N_all,
                         "ci95_share": ci if ok else None,
                         "ci95_informational": None if ok else {"interval": ci, "reason": "a sampled stratum has d < 5 or "
                                                                "n − d < 5: normal coverage not assured (v2.5-S)"},
                         "strata": per}}
