"""1bj core-instrument comparator. Frozen and committed BEFORE any core coding return exists. Deterministic; symmetric; no ground truth.
Usage: python3 compare_core.py LABEL1=CODING1.json LABEL2=CODING2.json [LABEL3=CODING3.json ...]
For every coder pair and each field (ACT_REPORTED, ACT_SELF_PERFORMED, NORM_STATED): agreement and Cohen's kappa over the
common record ids (R01..R36 expected). Values are upper-cased and trimmed; a missing or empty value counts as UNKNOWN.
The `kappa` function is copied verbatim from GATE1-APRIME/compare_gate1_aprime.py (178e86214).
Pre-registered pass rule (LOOP-1BJ/PREREG.md): every pair x field kappa >= 0.67. Where kappa is undefined (no variance),
the agreement proportion is reported, and the value counts as a pass only if agreement >= 0.90 AND a reviewer confirms the field is determinate.
That confirmation is flagged here as NEEDS_REVIEW, never auto-passed. No adjudication; disagreements are listed per record."""
import collections, itertools, json, sys
FIELDS = ("ACT_REPORTED", "ACT_SELF_PERFORMED", "NORM_STATED")
def kappa(a, b):
    n = len(a)
    if not n: return None, None
    po = sum(x == y for x, y in zip(a, b)) / n; ca, cb = collections.Counter(a), collections.Counter(b)
    pe = sum(ca[k] * cb[k] for k in set(ca) | set(cb)) / (n * n)
    return round(po, 3), (round((po - pe) / (1 - pe), 3) if pe < 1 else None)
def load(p): return {r["record_id"]: r for r in json.load(open(p))["records"]}
def val(r, f): return str((r or {}).get(f) or "UNKNOWN").strip().upper()
coders = dict(a.split("=", 1) for a in sys.argv[1:]); data = {k: load(v) for k, v in coders.items()}
ids = sorted(set.intersection(*(set(d) for d in data.values())))
out = {"labels": "symmetric; no ground truth", "coders": coders, "n_common": len(ids),
       "missing": {k: sorted(set(r for d in data.values() for r in d) - set(d)) for k, d in data.items()},
       "pairs": {}, "distributions": {}, "verdict_rule": "all pair x field kappa >= 0.67"}
for k, d in data.items():
    out["distributions"][k] = {f: dict(collections.Counter(val(d[i], f) for i in ids)) for f in FIELDS}
ok = True
for x, y in itertools.combinations(sorted(data), 2):
    pr = {}
    for f in FIELDS:
        a = [val(data[x][i], f) for i in ids]; b = [val(data[y][i], f) for i in ids]; ag, kp = kappa(a, b)
        if kp is not None: st = "PASS" if kp >= 0.67 else "FAIL"
        else: st = "NEEDS_REVIEW" if (ag or 0) >= 0.90 else "FAIL"
        ok = ok and st == "PASS"
        pr[f] = {"agreement": ag, "kappa": kp, "status": st,
                 "disagreements": {i: {x: a[n], y: b[n]} for n, i in enumerate(ids) if a[n] != b[n]}}
    out["pairs"][f"{x}~{y}"] = pr
out["all_pass"] = ok
print(json.dumps(out, indent=1, sort_keys=True))
