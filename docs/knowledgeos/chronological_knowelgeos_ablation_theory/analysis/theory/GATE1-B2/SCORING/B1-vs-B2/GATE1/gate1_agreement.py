"""Gate 1 (SECONDARY same-family) agreement: sealed main coding vs blind headless coding. Deterministic."""
import json, collections
M = json.load(open("MAIN-CODING-SEALED.json"))["rows"]; Bc = json.load(open("BLIND-CODING.json"))
ids = sorted(M, key=lambda i: int(i[2:]))
def norm(v): return (v or "").split(" ")[0].split("(")[0].strip().upper()
def kappa(a, b):
    n = len(a); po = sum(x == y for x, y in zip(a, b)) / n
    ca, cb = collections.Counter(a), collections.Counter(b); pe = sum(ca[k] * cb[k] for k in set(ca) | set(cb)) / (n * n)
    return round(po, 3), (round((po - pe) / (1 - pe), 3) if pe < 1 else None)
out = {"n_rows": len(ids)}
V = {}
for v in ["V1", "V2", "V3", "V4", "V5", "V6", "V7"]:
    a = [norm(M[i]["V"][v]) for i in ids]; b = [norm(Bc[i]["V"][v]) for i in ids]
    po, k = kappa(a, b); V[v] = {"agreement": po, "kappa": k, "disagreements": {i: [x, y] for i, x, y in zip(ids, a, b) if x != y}}
out["judgements"] = V
a = [bool(M[i]["authority_named"]) for i in ids]; b = [bool(Bc[i]["authority_named"]) for i in ids]
out["authority_named"] = dict(zip(["agreement", "kappa"], kappa(a, b)), disagreements=[i for i, x, y in zip(ids, a, b) if x != y])
jac = {}
for i in ids:
    s, t = set(M[i]["ops"]), set(x.upper() for x in Bc[i]["ops"])
    jac[i] = round(len(s & t) / len(s | t), 2) if s | t else 1.0
out["ops_jaccard_mean"] = round(sum(jac.values()) / len(jac), 3); out["ops_jaccard_per_row"] = jac
allops = sorted({o for i in ids for o in M[i]["ops"]} | {o.upper() for i in ids for o in Bc[i]["ops"]})
out["per_op_presence"] = {o: dict(zip(["agreement", "kappa"], kappa([o in M[i]["ops"] for i in ids], [o in [x.upper() for x in Bc[i]["ops"]] for i in ids]))) for o in allops
                          if sum(o in M[i]["ops"] for i in ids) + sum(o in [x.upper() for x in Bc[i]["ops"]] for i in ids) >= 3}
def cov(c): n = len(c["ops"]) + len(c["not_modelled"]); return len(c["ops"]) / n if n else None
out["coverage_rowmean"] = {"main": round(sum(cov(M[i]) or 0 for i in ids) / len(ids), 3), "blind": round(sum(cov(Bc[i]) or 0 for i in ids) / len(ids), 3)}
out["blind_not_modelled"] = collections.Counter(x.upper() for i in ids for x in Bc[i]["not_modelled"]).most_common()
out["blind_violations"] = {v: [i for i in ids if norm(Bc[i]["V"][v]) == "VIOLATED"] for v in ["V1", "V2", "V3", "V4", "V5", "V6", "V7"]}
print(json.dumps(out, indent=1, ensure_ascii=False))
