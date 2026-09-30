"""Gate 1 (A′) comparator. Committed BEFORE any non-Claude return exists. Deterministic; symmetric (no ground truth).
Usage: python3 compare_gate1_aprime.py EXTERNAL_CODING.json
Compares the external coding (bundle -02, record ids R01..R36) with two Claude references, each reported separately:
  REF-C  = LOOP-1AX/A/CODING.json (the 1ax coder)
  REF-CR = REF-C with the 1ax reviewer's machine-readable corrections applied (field act.<f> / norm.<f> only; others reported)
Measures, per reference: agreement + Cohen's kappa on act.state and norm.deontic (values upper-cased, trimmed);
  dataset membership agreement (ACT: act.state not in {NONE,UNKNOWN}; NORM: norm.deontic not in {NONE,UNKNOWN}) and a 4-cell
  membership table; per-record disagreements with both values; UNKNOWN shares; missing / extra record ids.
No re-coding, no adjudication: disagreements are reported for review, never resolved here. A′ is not modified by this script."""
import collections, json, os, sys
H = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(H)
def kappa(a, b):
    n = len(a)
    if not n: return None, None
    po = sum(x == y for x, y in zip(a, b)) / n; ca, cb = collections.Counter(a), collections.Counter(b)
    pe = sum(ca[k] * cb[k] for k in set(ca) | set(cb)) / (n * n)
    return round(po, 3), (round((po - pe) / (1 - pe), 3) if pe < 1 else None)
def load(p): return {r["record_id"]: r for r in json.load(open(p))["records"]}
def val(r, comp, f): return str(((r or {}).get(comp) or {}).get(f) or "UNKNOWN").strip().upper()
ext = load(sys.argv[1]); refc = load(os.path.join(TH, "LOOP-1AX", "A", "CODING.json"))
import copy; refcr = copy.deepcopy(refc); unapplied = []
for c in json.load(open(os.path.join(TH, "LOOP-1AX", "B", "REVIEW.json"))).get("corrected", []):
    parts = str(c.get("field", "")).split(".")
    if c.get("record_id") in refcr and len(parts) == 2 and parts[0] in ("act", "norm") and "reviewer" in c:
        refcr[c["record_id"]][parts[0]][parts[1]] = c["reviewer"]
    else: unapplied.append(c.get("field"))
out = {"labels": "symmetric comparison; Claude references are NOT ground truth", "n_external": len(ext), "refs": {}}
for name, ref in (("REF-C", refc), ("REF-CR", refcr)):
    ids = sorted(set(ext) & set(ref)); o = {"missing_in_external": sorted(set(ref) - set(ext)), "extra_in_external": sorted(set(ext) - set(ref))}
    for comp, f in (("act", "state"), ("norm", "deontic")):
        a = [val(ext.get(i), comp, f) for i in ids]; b = [val(ref.get(i), comp, f) for i in ids]
        ag, k = kappa(a, b)
        o[f"{comp}.{f}"] = {"agreement": ag, "kappa": k, "unknown_share_external": round(a.count("UNKNOWN") / len(a), 3) if a else None,
                            "disagreements": {i: {"external": x, name: y} for i, x, y in zip(ids, a, b) if x != y}}
    mem = lambda r: (val(r, "act", "state") not in ("NONE", "UNKNOWN"), val(r, "norm", "deontic") not in ("NONE", "UNKNOWN"))
    table = collections.Counter((mem(ext.get(i)), mem(ref.get(i))) for i in ids)
    o["membership"] = {"agreement": round(sum(mem(ext.get(i)) == mem(ref.get(i)) for i in ids) / len(ids), 3) if ids else None,
                       "table": {f"ext{a}_ref{b}": n for (a, b), n in table.items()}}
    out["refs"][name] = o
out["REF-CR_unapplied_corrections"] = unapplied
print(json.dumps(out, indent=1, ensure_ascii=False, default=str))
