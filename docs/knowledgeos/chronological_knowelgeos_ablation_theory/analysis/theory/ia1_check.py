"""1w I-A1 test (spec IA1-WP4B/SPEC.json, frozen a6533817d). Deterministic; prints hashes/times/subject prefixes only."""
import importlib.util as u, json, re, subprocess
s = u.spec_from_file_location("m", __file__.replace("ia1_check.py", "register_history_check.py")); m = u.module_from_spec(s); s.loader.exec_module(m)
root = subprocess.run(["git", "rev-parse", "--show-toplevel"], capture_output=True, text=True, check=True).stdout.strip()
def git(*a): return subprocess.run(["git", *a], capture_output=True, text=True, check=True, cwd=root).stdout
# row first-record committer times
first = {}
for h, d, p in m.history():
    r = m.rows_at(h, p)
    if not r: continue
    t = git("log", "-1", "--format=%cI", h).strip()
    for rid in r[0]:
        first.setdefault(rid, (t, h))
T = {k: first[k][0] for k in ("R-72", "R-77", "R-86")}
log = git("log", "--format=@@%h|%cI|%s", "--name-only").split("@@")[1:]
impl = []
for blk in log:
    head, *files = blk.strip().split("\n")
    h, t, subj = head.split("|", 2)
    if re.search(r"wp-?4b", subj, re.I) and any(f.startswith(("app/", "tests/")) for f in files if f):
        impl.append({"hash": h, "time": t, "subject": subj[:80], "batch7": bool(re.search(r"isolat|reconcil|K2|keystone|model[- ]B|redrive", subj, re.I)),
                     "paths": sorted({f.split("/")[0] + "/" + (f.split("/")[1] if "/" in f else "") for f in files if f.startswith(("app/", "tests/"))})})
impl.sort(key=lambda x: x["time"])
def viol(bound, pred=lambda c: True): return [c for c in impl if pred(c) and c["time"] < bound]
res = {"bounds": {k: {"time": T[k], "commit": first[k][1]} for k in T}, "implementation_commits": len(impl),
       "first_impl": impl[0] if impl else None, "last_impl": impl[-1] if impl else None, "batch7_commits": sum(c["batch7"] for c in impl)}
for name, bound, pred in (("P-a", T["R-72"], lambda c: True), ("P-b", T["R-77"], lambda c: True), ("P-c", T["R-86"], lambda c: c["batch7"])):
    v = viol(bound, pred)
    res[name] = "UNTESTABLE" if not impl else ("SUPPORTED" if not v else {"VIOLATED": [(c["hash"], c["time"], c["subject"]) for c in v]})
if impl:
    lo, hi = T["R-77"], impl[0]["time"]
    res["secondary: rows first recorded between T_R77 and first impl"] = sorted(k for k, (t, h) in first.items() if lo <= t <= hi)
res["all_impl"] = [(c["hash"], c["time"], c["batch7"], c["subject"]) for c in impl]
print(json.dumps(res, indent=1, ensure_ascii=False))
