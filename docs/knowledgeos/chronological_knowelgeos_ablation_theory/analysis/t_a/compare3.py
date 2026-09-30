"""Three-way T-A ledger comparison (rules fixed in F-LOG-0048 before the INDEPENDENT content was inspected).
Pairwise over (SELF, BLIND-SECONDARY, INDEPENDENT). No averaging, no majority vote, no truth field. Read-only.
"""
import json, re, sys, itertools
RELEASED = ("d61bf5e84", "d63202b8c", "aee484e9c", "da565a213", "c71f7d689", "8d1df4b1d", "43682264d", "668cc7b22",
            "F0018", "ES-006", "all 7", "revision", "objects/")
def load(p): return [json.loads(l) for l in open(p, encoding="utf-8") if l.strip()]
def fam(r): return "F0018" if "F0018" in (r["source_id"] + r["source_version"]) else "ES-006"
def toks(s): return {w for w in re.findall(r"[a-z0-9≥]+", s.lower()) if len(w) >= 3}
def sim(a, b):
    x, y = toks(a["original_wording"]), toks(b["original_wording"])
    if not x or not y: return 0.0
    return max(len(x & y) / len(x | y), len(x & y) / min(len(x), len(y)) - 0.5 if len(x & y) / min(len(x), len(y)) >= 0.80 else 0.0) if False else \
        (1.0 if len(x & y) / min(len(x), len(y)) >= 0.80 else len(x & y) / len(x | y))
def op(r): return r.get("operation") or {}
def effset(r): return frozenset(k for k, v in (r.get("effect") or {}).items() if v)
def released(r): return any(t in (r["source_id"] + " " + r["source_version"]) for t in RELEASED)
def pclass(a, b):
    if not released(a) or not released(b): return "PROVENANCE_OR_DATA_DISCREPANCY"
    ca, cb = a["classification"], b["classification"]
    if ca != cb and "AMBIGUOUS" not in (ca, cb): return "SUBSTANTIVE_CLASSIFICATION_DISAGREEMENT"
    if ca != cb or (ca == "AMBIGUOUS" and a.get("competing_classification") != b.get("competing_classification")): return "AMBIGUITY_DISAGREEMENT"
    if a["test"].startswith("F-") and (op(a).get("assigned_kind"), op(a).get("typing_basis")) != (op(b).get("assigned_kind"), op(b).get("typing_basis")): return "TYPING_DISAGREEMENT"
    if a["test"].startswith("F-") and effset(a) != effset(b): return "EFFECT_INTERPRETATION_DISAGREEMENT"
    return "EXACT_AGREEMENT" if a["original_wording"].strip() == b["original_wording"].strip() else "EQUIVALENT_WORDING"
def compare(A, B):
    cand = sorted(((sim(a, b), i, j) for i, a in enumerate(A) for j, b in enumerate(B)
                   if a["test"] == b["test"] and fam(a) == fam(b)), key=lambda t: (-t[0], t[1], t[2]))
    ua, ub, rows = set(), set(), []
    for s, i, j in cand:
        if s < 0.30 or i in ua or j in ub: continue
        ua.add(i); ub.add(j)
        rows.append({"class": pclass(A[i], B[j]), "test": A[i]["test"], "a": i, "b": j, "sim": round(s, 2),
                     "a_cls": A[i]["classification"], "a_comp": A[i].get("competing_classification"),
                     "b_cls": B[j]["classification"], "b_comp": B[j].get("competing_classification"),
                     "a_kind": op(A[i]).get("assigned_kind"), "b_kind": op(B[j]).get("assigned_kind")})
    rows += [{"class": "EVIDENCE_ANCHOR_DISAGREEMENT", "side": "a-only", "test": A[i]["test"], "a": i, "a_cls": A[i]["classification"],
              "a_comp": A[i].get("competing_classification"), "wording": A[i]["original_wording"][:100]} for i in range(len(A)) if i not in ua]
    rows += [{"class": "EVIDENCE_ANCHOR_DISAGREEMENT", "side": "b-only", "test": B[j]["test"], "b": j, "b_cls": B[j]["classification"],
              "b_comp": B[j].get("competing_classification"), "wording": B[j]["original_wording"][:100]} for j in range(len(B)) if j not in ub]
    summ = {}
    for r in rows: summ[r["class"]] = summ.get(r["class"], 0) + 1
    return {"summary": summ, "rows": rows}
if __name__ == "__main__":
    names = ("SELF", "BLIND-SECONDARY", "INDEPENDENT")
    L = {n: load(p) for n, p in zip(names, sys.argv[1:4])}
    out = {"rule": "F-LOG-0048", "pairs": {f"{x}|{y}": compare(L[x], L[y]) for x, y in itertools.combinations(names, 2)}}
    json.dump(out, sys.stdout, indent=1, ensure_ascii=False); print()
