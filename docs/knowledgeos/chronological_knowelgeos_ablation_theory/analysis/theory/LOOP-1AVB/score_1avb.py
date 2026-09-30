"""1av-b de-cued pilot scorer (frozen before coding). Maps opaque ids E01..E36 back to event ids via ID-KEY.json, then:
(1) runs the frozen 1av scorer unchanged (P3 vs P4; each vs the 1ar audit);
(2) CUE SENSITIVITY: observation_type agreement of P3 and of P4 with the cued pilot coders P1 and P2 (labels that change = cue-driven).
Usage: python3 score_1avb.py"""
import json, os, subprocess, sys, tempfile
H = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(H)
key = {k["id"]: k["event_id"] for k in json.load(open(os.path.join(H, "ID-KEY.json")))["key"]}


def remap(p):
    d = json.load(open(p)); out = {"events": [dict(e, event_id=key[e["id"]]) for e in d["events"]]}
    f = tempfile.NamedTemporaryFile("w", suffix=".json", delete=False); json.dump(out, f); f.close(); return f.name


def types(p): return {e["event_id"]: (e.get("observation_type") or "UNKNOWN").upper() for e in json.load(open(p))["events"]}


p3, p4 = remap(os.path.join(H, "P3", "CODING.json")), remap(os.path.join(H, "P4", "CODING.json"))
base = json.loads(subprocess.check_output([sys.executable, os.path.join(TH, "LOOP-1AV", "score_1av.py"), p3, p4,
                                           os.path.join(TH, "LOOP-1AR", "A", "ANSWER.json"), os.path.join(TH, "LOOP-1AR", "B", "REVIEW.json")]))
T = {n: types(p) for n, p in (("P3", p3), ("P4", p4), ("P1", os.path.join(TH, "LOOP-1AV", "P1", "CODING.json")), ("P2", os.path.join(TH, "LOOP-1AV", "P2", "CODING.json")))}
ids = sorted(set.intersection(*[set(v) for v in T.values()]))
cue = {f"{a}~{b}": round(sum(T[a][i] == T[b][i] for i in ids) / len(ids), 3) for a in ("P3", "P4") for b in ("P1", "P2")}
changed = {i: {"cued_P1": T["P1"][i], "decued_P3": T["P3"][i], "decued_P4": T["P4"][i]} for i in ids if T["P3"][i] != T["P1"][i] or T["P4"][i] != T["P1"][i]}
print(json.dumps({"decued_scores": base, "cue_sensitivity_agreement": cue, "labels_changed_vs_cued": changed}, indent=1, ensure_ascii=False))
