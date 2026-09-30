#!/usr/bin/env python3
"""P3b S3b HOLD-OUT AND SAMPLE PLAN — §19.1 S3b row of the frozen core v1.6.5 (G-LOG-0012), H-19 addendum v1.5
(G-LOG-0016, variant S), Appendix A.5 with OMQ-15 / OMQ-09 (G-LOG-0017).

Steps:
  1 gate: S3 PASS outputs with the G-LOG-0015 body hashes; approved addendum and frozen protocol hashes.
  2 hold-out (addendum §3, variant S): graph E1 ledger rows · E2 files_touching · E3 P2a groups · E4 P3a pairs; rules
    R0–R7; all eligible components taken (below the 404 target). The label and file lists must equal the approved
    hashes (G-LOG-0016) or the run FAILS.
  3 assertions (addendum §4): 0 groups / pairs crossing the split; no discovery bundle (reconciliation object, verbatim
    rows, verbatim sources) names a hold-out label; no discovery bundle cites an HF source. HF S-id mentions remaining
    in discovery bundles are listed in the seal.
  4 derived files (addendum §2; S3 outputs untouched): P3B-DISCOVERY-SEARCH.jsonl (discovery labels; HF keys removed
    from ledger_hits and raw_hits; negatives recomputed; population_basis DISCOVERY-POPULATION (seal id));
    _batch_input_r2/bundle_index_discovery.jsonl (S3 index records of discovery labels, byte-identical);
    P3B-HOLDOUT-SEALED-S3.jsonl (hold-out labels' S3 search and index records; scripts only).
  5 sample plan (A.5) over the S5 population = discovery labels of Tier U/Z (Tier X is sampled in its own S6 run):
    purposive (OMQ-15) ∪ stratified random (row_count band × pair_count band × provenance mix), n = 200, f = 2,
    proportional allocation, largest remainder then stratum key, one random.Random(20260924) consumed by strata in
    ascending key order, labels sorted by working_label, rng.sample.
  6 discovery workload P3B-WORKLOAD-DISCOVERY.json (§19.1 S3b: "workload recomputed without sealed material"),
    computed like S3's workload body over discovery labels and discovery files.
  7 seal record P3B-HOLDOUT-SEAL.json (addendum §5), state SEALED. A real run requires the script to be committed and
    unmodified, and refuses to run if a seal already exists.

Implementation decisions (stated for review):
  a. Name matching (addendum §3): the label string in json.dumps(record, ensure_ascii=False) with no character of
     [A-Za-z0-9._-] immediately before or after; against the label set.
  b. R7 long lines are read through the S2b manifest (ContentResolver, sha256-verified).
  c. ledger_hits in the S3 search are keyed by the row's source_id, so removing HF keys removes exactly the rows from
     HF sources (addendum §2 (a)).
  d. Sample-plan strata: row_count = distinct rows (bundle index distinct_rows); band 0–1 · 2–3 · 4–9 · ≥ 10 (a label
     with no row falls in 0–1); pair_count band 0 · 1–2 · ≥ 3 (reconciliation_objects pair_count); provenance mix =
     PRIMARY-only if every row source's 02-FILES provenance is PRIMARY (vacuously for no rows), else ANY-NON-PRIMARY.
  e. The seal id is "HS-" + the first 12 hex digits of sha256(addendum sha256 + label-list sha256 + file-list sha256).
  g. Allocation: quota_k = max(f, n·|k|/N) (floor mass not subtracted from other strata), integer parts, then
     remainders by largest fractional part and stratum key; the total is asserted to equal n (FAIL otherwise).
     Stratum keys sort as strings ("r0-1|…" < "r10+|…" < "r2-3|…" < "r4-9|…").
  f. Dry run writes nothing to the repository; --emit=<dir outside the repository> writes the would-be outputs for
     review (dry run only).

Usage:  python3 p3b_s3b_holdout_sample_plan.py [--dry-run [--emit=<dir>]]
Exit:   0 PASS · 1 FAIL (stop; human escalation, §22) · 2 usage error
"""
import collections
import datetime
import glob
import importlib.util
import json
import os
import platform
import random
import re
import subprocess
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s3_mechanical_prep", os.path.join(_HERE, "p3b_s3_mechanical_prep.py"))
s3 = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(s3)
canon, sha256_bytes, CR, CR_REL, REPO_ROOT = s3.canon, s3.sha256_bytes, s3.CR, s3.CR_REL, s3.REPO_ROOT

PROTOCOL = "prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md"
PROTOCOL_SHA256 = "b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7"
ADDENDUM = "prompts/20260924_1625_p3b-H19-holdout-addendum-v1.5.md"
ADDENDUM_SHA256 = "75a5e45d0f8a1e5726beb92ef1ac18c540355ce51cdec7b8dd6873399e806ae3"
S3_SEARCH_BODY = "c7edb3fbc758c018dc5121c6364c3f18b0c7b7cd0b9628d618a0ed970048bba3"      # G-LOG-0015
S3_INDEX_BODY = "155fbb5fda06982b3fedf89af58893d2efed869ca453ed77ac6205bf2a9cca86"       # G-LOG-0015
HOLDOUT_LABEL_SHA = "7febd747ad2d2a494e34db40cc29c95f33fe2003a8ea5b505324b74e1e13f0fc"   # G-LOG-0016
HOLDOUT_FILE_SHA = "46cdabe271768a9db7ea06967eaa6d3963c001f11680e2e0d896153f9e2d8a9d"    # G-LOG-0016
TARGET_SHARE, SEED = 0.20, 20260924
N_RANDOM, FLOOR = 200, 2                                                               # OMQ-09 (G-LOG-0017)
STRONG_LINEAGE = {"SOURCE-CLAIMED-REPLACEMENT", "SOURCE-CLAIMED-REDEFINITION", "SOURCE-CLAIMED-RETRACTION",
                  "SOURCE-CLAIMED-CONTRADICTION", "SOURCE-CLAIMED-SEPARATION"}
REVIEW_Q = {"MATH-QUESTION", "STAT-QUESTION", "TYPE-QUESTION"}
LABEL_CHARS = "A-Za-z0-9._-"
OUT_SEAL, OUT_PLAN = "P3B-HOLDOUT-SEAL.json", "P3B-SAMPLE-PLAN.jsonl"
OUT_DSEARCH, OUT_DINDEX, OUT_SEALED = ("P3B-DISCOVERY-SEARCH.jsonl", "_batch_input_r2/bundle_index_discovery.jsonl",
                                       "P3B-HOLDOUT-SEALED-S3.jsonl")
OUT_DWORK = "P3B-WORKLOAD-DISCOVERY.json"
R1_FILES = tuple(f"ledger-p3b/OB000{i}/objects.jsonl" for i in (1, 2, 3))
PREREGISTERED_OUTCOME = {                                                              # §9C.1 rule 1 (verbatim)
    "primary": "label yields >= 1 record, passing G-09 and G-12, of kind HYPOTHESIS, STRUCTURE-CANDIDATE, "
               "SCHEMA-LIMITATION or GAP-with-NOT-FOUND-AFTER-CENSUS",
    "secondary": "count of such records",
    "weighting": "every corpus-level rate from the random component weighted by inverse inclusion probability; "
                 "unweighted rates only as sample descriptives (§9C.1 rule 2)"}


def jl(path):
    with open(os.path.join(CR, path), "rb") as f:
        return [l for l in f.read().split(b"\n") if l]


def git(*a):
    return subprocess.run(["git", "-C", REPO_ROOT, *a], capture_output=True, text=True).stdout.strip()


def sha_list(xs):
    return sha256_bytes(json.dumps(sorted(xs)).encode())


def main():
    args = sys.argv[1:]
    dry_run = "--dry-run" in args
    emit = next((a.split("=", 1)[1] for a in args if a.startswith("--emit=")), None)
    if [a for a in args if a != "--dry-run" and not a.startswith("--emit=")] or \
            (emit is not None and (not dry_run or os.path.abspath(emit).startswith(REPO_ROOT + os.sep))):
        print("usage error: [--dry-run [--emit=<dir outside the repository>]]", file=sys.stderr)
        return 2
    failures = []

    # ---- 1 gate ----
    s_lines, i_lines = jl("P3B-ABSENCE-SEARCH.jsonl"), jl("_batch_input_r2/bundle_index.jsonl")
    s_head, i_head = json.loads(s_lines[0])["header"], json.loads(i_lines[0])["header"]
    s_body_sha = sha256_bytes(b"\n".join(s_lines[1:]) + b"\n")
    i_body_sha = sha256_bytes(b"\n".join(i_lines[1:]) + b"\n")
    if not (s_head.get("result") == i_head.get("result") == "PASS"):
        failures.append("S3 outputs are not PASS")
    if s_body_sha != S3_SEARCH_BODY or i_body_sha != S3_INDEX_BODY:
        failures.append("S3 output bodies differ from G-LOG-0015")
    for rel, want in ((PROTOCOL, PROTOCOL_SHA256), (ADDENDUM, ADDENDUM_SHA256)):
        if s3.sha256_file(rel) != want:
            failures.append(f"{rel} hash mismatch")
    if os.path.exists(os.path.join(CR, OUT_SEAL)) and not dry_run:
        failures.append("a seal already exists: one seal per protocol run (addendum §7)")
    if not dry_run and git("status", "--porcelain", "--", os.path.abspath(__file__)) != "":
        failures.append("a real run requires this script to be committed and unmodified")
    if failures:
        for f_ in failures:
            print("  FAIL:", f_)
        print("S3b RESULT: FAIL — STOP; human escalation (§22)")
        return 1

    # ---- inputs ----
    derived = json.load(open(os.path.join(CR, "20-FAMILIES/_derived.json"), encoding="utf-8"))
    objs, nodes = derived["reconciliation_objects"], derived["nodes"]
    groups = list(derived["groups"].values()) if isinstance(derived["groups"], dict) else derived["groups"]
    labels = set(objs)
    pairs = [json.loads(l) for l in jl("31-RECONCILIATION-PAIRS.jsonl")]
    tiers = {json.loads(l)["working_label"]: json.loads(l)["tier"] for l in jl("P3B-TIERS.jsonl")[1:]}
    r1 = set()
    for fp in sorted(glob.glob(os.path.join(CR, "ledger-p3b", "OB000[123]", "objects.jsonl"))):
        for l in open(fp, encoding="utf-8"):
            if l.strip():
                o = json.loads(l)
                r1.add(o.get("working_label") or o.get("label"))
    files = [json.loads(l) for l in jl("02-FILES.jsonl")]
    F = {f["source_id"]: f for f in files}
    content = {s for s, f in F.items() if f["status"] == "CONTENT"}
    rows = [json.loads(l) for l in jl("03-CONTRIBUTIONS.jsonl")]
    overlap = [json.loads(l) for l in jl("08-OVERLAP-REGISTER.jsonl")]
    input_hashes = {rel: s3.sha256_file(rel) for rel in (
        "20-FAMILIES/_derived.json", "31-RECONCILIATION-PAIRS.jsonl", "P3B-TIERS.jsonl", "02-FILES.jsonl",
        "03-CONTRIBUTIONS.jsonl", "08-OVERLAP-REGISTER.jsonl", "P3B-IDENTITY-MANIFEST.jsonl",
        "P3B-ABSENCE-SEARCH.jsonl", "_batch_input_r2/bundle_index.jsonl", ADDENDUM, PROTOCOL, *R1_FILES)}

    # ---- 2 hold-out (addendum §3, variant S) ----
    src = collections.defaultdict(set)
    for r in rows:
        for lab in set(r.get("labels") or []):
            if lab in labels:
                src[lab].add(r["source_id"])
    for x in labels:                                                         # E2
        for s in objs[x].get("files_touching") or []:
            src[x].add(s)
    parent = {x: x for x in labels}

    def find(x):
        while parent[x] != x:
            parent[x] = parent[parent[x]]
            x = parent[x]
        return x

    def union(a, b):
        ra, rb = find(a), find(b)
        if ra != rb:
            parent[max(ra, rb)] = min(ra, rb)
    by_src = collections.defaultdict(list)
    for lab in sorted(src):
        for s in sorted(src[lab]):
            by_src[s].append(lab)
    for ls in by_src.values():                                               # E1 + E2
        for x in ls[1:]:
            union(ls[0], x)
    for g in groups:                                                         # E3
        m = [x for x in g["members"] if x in labels]
        for x in m[1:]:
            union(m[0], x)
    for q in pairs:                                                          # E4
        union(q["a"], q["b"])
    comps = collections.defaultdict(set)
    for x in labels:
        comps[find(x)].add(x)
    comps = sorted(comps.values(), key=lambda c: (-len(c), min(c)))
    giant = comps[0]                                                         # largest; tie → smallest label
    comp_src = [set().union(*(src[x] for x in c)) for c in comps]
    first_fail, per_rule = {}, collections.defaultdict(set)
    for i, c in enumerate(comps):
        checks = [("R0", c is giant), ("R1", any(tiers[x] not in ("U", "Z") for x in c)), ("R2", bool(c & r1)),
                  ("R3", not (comp_src[i] & content))]
        for rule, hit in checks:
            if hit:
                per_rule[rule].add(i)
                first_fail.setdefault(i, rule)
    cand = [i for i in range(len(comps)) if i not in first_fail]
    cand_labels = sorted(set().union(*(comps[i] for i in cand)), key=len, reverse=True)
    pat = re.compile(r"(?<![" + LABEL_CHARS + r"])(" + "|".join(map(re.escape, cand_labels)) + r")(?![" + LABEL_CHARS + r"])")
    comp_of = {x: i for i in cand for x in comps[i]}
    dumps = lambda o: json.dumps(o, ensure_ascii=False)
    for x in labels:                                                         # R4
        for m in set(pat.findall(dumps(objs[x]) + dumps(nodes.get(x, {})))):
            if comp_of[m] != comp_of.get(x):
                per_rule["R4"].add(comp_of[m])
    for r in rows:                                                           # R5
        for m in set(pat.findall(dumps(r))):
            if r["source_id"] not in comp_src[comp_of[m]]:
                per_rule["R5"].add(comp_of[m])
    for fr in files:                                                         # R6
        for m in set(pat.findall(dumps(fr))):
            if fr["source_id"] not in comp_src[comp_of[m]]:
                per_rule["R6"].add(comp_of[m])
    resolver = s3.ContentResolver.from_manifest()                            # R7 (full census; no seal exists yet)
    blobs = resolver.read_many(sorted(content))
    long_lines = {s: {ln.strip() for ln in b.decode("utf-8", errors="replace").split("\n") if len(ln.strip()) >= 40}
                  for s, b in blobs.items()}
    line_index = collections.defaultdict(set)
    for s, ls in long_lines.items():
        for ln in ls:
            line_index[ln].add(s)
    for i in cand:
        cs = comp_src[i]
        for s in sorted(cs & content):
            mine = long_lines[s]
            if mine:
                cnt = collections.Counter(o for ln in mine for o in line_index[ln] if o not in cs)
                if cnt and max(cnt.values()) / len(mine) >= 0.30:
                    per_rule["R7"].add(i)
            for q in overlap:
                if (q.get("a") == s and q.get("b") not in cs) or (q.get("b") == s and q.get("a") not in cs):
                    per_rule["R7"].add(i)
    for i in cand:
        for rule in ("R4", "R5", "R6", "R7"):
            if i in per_rule[rule]:
                first_fail.setdefault(i, rule)
                break
    eligible = [i for i in range(len(comps)) if i not in first_fail]
    s5_eligible = sum(1 for x in labels if tiers[x] in ("U", "Z"))
    target = round(TARGET_SHARE * s5_eligible)
    if sum(len(comps[i]) for i in eligible) <= target:
        chosen = eligible
    else:                                                                    # the specified draw (not needed today)
        order = sorted(eligible, key=lambda i: sha256_bytes(f"{SEED}|{min(comps[i])}".encode("utf-8")))
        chosen, total = [], 0
        for i in order:
            if total + len(comps[i]) <= target:
                chosen.append(i)
                total += len(comps[i])
    H = set().union(*(comps[i] for i in chosen))
    HF = set().union(*(comp_src[i] for i in chosen)) & content
    if sha_list(H) != HOLDOUT_LABEL_SHA or sha_list(HF) != HOLDOUT_FILE_SHA:
        failures.append("hold-out lists differ from the approved hashes (G-LOG-0016)")
    disc = labels - H

    # ---- 3 assertions (addendum §4) ----
    cross_groups = sum(1 for g in groups if set(g["members"]) & H and (set(g["members"]) & labels) - H)
    cross_pairs = sum(1 for q in pairs if (q["a"] in H) != (q["b"] in H))
    if cross_groups or cross_pairs:
        failures.append(f"groups/pairs cross the split: {cross_groups}/{cross_pairs}")
    hpat = re.compile(r"(?<![" + LABEL_CHARS + r"])(" + "|".join(map(re.escape, sorted(H, key=len, reverse=True))) +
                      r")(?![" + LABEL_CHARS + r"])")
    rows_lines, files_lines = jl("03-CONTRIBUTIONS.jsonl"), jl("02-FILES.jsonl")
    index = {json.loads(l)["working_label"]: l for l in i_lines[1:]}
    named, hf_src_bundles, mentions = [], [], collections.defaultdict(set)
    sid_pat = re.compile(r"\bS\d{4}\b")
    for lab in sorted(disc):
        rec = json.loads(index[lab])
        bundle, ok_b, _ = s3.materialize_bundle(rec, rows_lines, files_lines, objs)
        if not ok_b:
            failures.append(f"bundle {lab} failed materialization")
        text = dumps(bundle)
        if hpat.search(text):
            named.append(lab)
        if any(sv["source_id"] in HF for sv in rec["sources"]):
            hf_src_bundles.append(lab)
        for sid in set(sid_pat.findall(text)) & HF:
            mentions[sid].add(lab)
    if named:
        failures.append(f"{len(named)} discovery bundles name a hold-out label (first: {named[:3]})")
    if hf_src_bundles:
        failures.append(f"{len(hf_src_bundles)} discovery bundles cite an HF source")

    # ---- 5 sample plan (A.5 over discovery Tier U/Z) ----
    by_label = collections.defaultdict(list)
    for r in rows:
        for lab in set(r.get("labels") or []):
            if lab in labels:
                by_label[lab].append(r)

    def formal(ts):
        return any(v not in (None, "", [], {}) for v in ts.values()) if isinstance(ts, dict) else (
            isinstance(ts, str) and ts.strip() != "")

    def purposive(lab):
        rs, crit = by_label.get(lab, []), []
        if any("CONTRADICTION" in (r.get("types") or []) for r in rs):
            crit.append("CONTRADICTION-ROW")
        if sum(1 for r in rs if formal(r.get("type_signature"))) >= 2:
            crit.append("TWO-FORMAL-ROWS")
        if any(r.get("review_flag") in REVIEW_Q for r in rs):
            crit.append("MATH-STAT-TYPE-QUESTION")
        if any(isinstance(c, dict) and c.get("kind") in STRONG_LINEAGE for r in rs for c in (r.get("lineage_claims") or [])):
            crit.append("STRONG-LINEAGE")
        return crit

    def band(v, cuts):
        for lo, hi, name in cuts:
            if lo <= v <= hi:
                return name
    distinct_rows = {json.loads(l)["working_label"]: json.loads(l)["distinct_rows"] for l in i_lines[1:]}

    def stratum(lab):
        rb = band(distinct_rows[lab], [(0, 1, "r0-1"), (2, 3, "r2-3"), (4, 9, "r4-9"), (10, 10 ** 9, "r10+")])
        pb = band(objs[lab].get("pair_count") or 0, [(0, 0, "p0"), (1, 2, "p1-2"), (3, 10 ** 9, "p3+")])
        prov = "PRIMARY-only" if all(F[r["source_id"]].get("provenance") == "PRIMARY" for r in by_label.get(lab, [])) \
            else "ANY-NON-PRIMARY"
        return f"{rb}|{pb}|{prov}"
    pop = sorted(x for x in disc if tiers[x] in ("U", "Z"))
    crit = {x: purposive(x) for x in pop}
    strata = collections.defaultdict(list)
    for x in pop:
        if not crit[x]:
            strata[stratum(x)].append(x)
    total_np = sum(len(v) for v in strata.values())
    n = N_RANDOM
    floors = {k: min(FLOOR, len(v)) for k, v in strata.items()}
    n_raised = None
    if sum(floors.values()) > n:
        n_raised, n = sum(floors.values()), sum(floors.values())
    quota = {k: max(floors[k], n * len(v) / total_np) for k, v in strata.items()}
    alloc = {k: min(len(strata[k]), int(q)) for k, q in quota.items()}
    rest = n - sum(alloc.values())
    for k in sorted(strata, key=lambda k: (-(quota[k] - int(quota[k])), k)):
        if rest <= 0:
            break
        if alloc[k] < len(strata[k]):
            alloc[k] += 1
            rest -= 1
    if sum(alloc.values()) != n:
        failures.append(f"allocation total {sum(alloc.values())} != n {n}")
    rng = random.Random(SEED)
    sampled = set()
    for k in sorted(strata):
        sampled |= set(rng.sample(sorted(strata[k]), alloc[k]))
    plan = []
    for x in pop:
        st = None if crit[x] else stratum(x)
        plan.append({"working_label": x, "tier": tiers[x], "purposive_criteria": crit[x],
                     "in_checklist": bool(crit[x]) or x in sampled, "stratum": st,
                     "inclusion_probability": 1.0 if crit[x] else round(alloc[st] / len(strata[st]), 6),
                     "seed": SEED})

    # ---- 4 derived files ----
    seal_id = "HS-" + sha256_bytes((ADDENDUM_SHA256 + HOLDOUT_LABEL_SHA + HOLDOUT_FILE_SHA).encode())[:12]
    basis = f"DISCOVERY-POPULATION ({seal_id})"
    man_rows = resolver.rows
    disc_files = sorted(content - HF)
    idsum = {"stasis_unobservable": sum(1 for s in disc_files if man_rows[s]["historical_linkage"] == "STASIS-UNOBSERVABLE"),
             "p0_row_exception": sum(1 for s in disc_files if man_rows[s]["content_identity"] == "PATH-CONTENT-P0-ROW-MISALIGNED")}
    d_search, sealed_lines, hits_left = [], [], {}
    for l in s_lines[1:]:
        rec = json.loads(l)
        lab = rec["label"]
        if lab in H:
            sealed_lines.append(canon({"sealed_from": "P3B-ABSENCE-SEARCH.jsonl", "record": rec}))
            continue
        if rec["record"] == "LABEL-HITS":
            rec["ledger_hits"] = {s: v for s, v in rec["ledger_hits"].items() if s not in HF}
            rec["raw_hits"] = {s: v for s, v in rec["raw_hits"].items() if s not in HF}
            rec["population_basis"] = basis
            hits_left[lab] = sum(map(len, rec["ledger_hits"].values())) + sum(map(len, rec["raw_hits"].values()))
        else:
            rec.update({"negative_label": "NEGATIVE-CENSUS" if hits_left[lab] == 0 else None,
                        "scope": "DISCOVERY-POPULATION", "files_searched": len(disc_files),
                        "population_basis": basis, "identity_summary": idsum})
        d_search.append(canon(rec))
    for lab, l in index.items():
        if lab in H:
            sealed_lines.append(canon({"sealed_from": "_batch_input_r2/bundle_index.jsonl", "record": json.loads(l)}))
    d_index = [index[lab].decode("utf-8") for lab in sorted(disc)]
    dims = [json.loads(x) for x in d_search if json.loads(x)["record"] == "DIMENSION"]
    neg = sum(1 for d_ in dims if d_["negative_label"])
    raw_files = {json.loads(x)["label"]: sorted(json.loads(x)["raw_hits"]) for x in d_search
                 if json.loads(x)["record"] == "LABEL-HITS"}
    file_chars = {s: len(s3.norm_base(blobs[s].decode("utf-8", errors="replace"))) for s in disc_files}
    lwd = [x for x in sorted(disc) if objs[x].get("completeness_absences")]
    hd = sorted(hits_left[x] for x in lwd)
    q = lambda pr: hd[min(len(hd) - 1, int(pr * len(hd)))] if hd else 0
    fhl = collections.Counter(s for x in lwd for s in raw_files[x])
    workload = {
        "provisional": "discovery-population workload (S3b, without sealed material); S4 calibrates",
        "population_basis": basis, "absence_dimensions": len(dims), "labels_with_absence_dimensions": len(lwd),
        "dimensions_with_zero_stage1_hits": neg, "stage1_hits_total_over_labels_with_dimensions": sum(hd),
        "hits_per_label_distribution": {"p50": q(.5), "p90": q(.9), "p99": q(.99), "max": hd[-1] if hd else 0},
        "top_labels_by_hits": sorted(({"label": x, "hits": hits_left[x], "raw_files": len(raw_files[x])} for x in lwd),
                                     key=lambda r: (-r["hits"], r["label"]))[:15],
        "unique_files_hit": len(fhl),
        "per_file_label_concentration_top": [{"source_id": s, "labels": c} for s, c in fhl.most_common(10)],
        "stage2_whole_file_reading_chars_if_every_hit_file_read_once_per_label":
            sum(file_chars[s] for x in lwd for s in raw_files[x])}

    ok = not failures
    script_path = os.path.abspath(__file__)
    common = {"script_name": "p3b_s3b_holdout_sample_plan.py", "script_version": git("hash-object", script_path),
              "script_committed_unmodified": git("status", "--porcelain", "--", script_path) == "",
              "protocol": PROTOCOL, "protocol_sha256": PROTOCOL_SHA256, "addendum": ADDENDUM,
              "addendum_sha256": ADDENDUM_SHA256, "seal_id": seal_id, "input_hashes": input_hashes,
              "python_version": platform.python_version(), "result": "PASS" if ok else "FAIL", "failures": failures,
              "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")}
    body = lambda ls: ("\n".join(ls) + "\n").encode("utf-8")
    outs = {
        OUT_DSEARCH: (dict(common, artifact=OUT_DSEARCH, population_basis=basis, counts={
            "labels": len(disc), "dimensions": len(dims), "negative_census_discovery_basis": neg,
            "files_searched": len(disc_files), "stage1_hits_total": sum(hits_left.values())}), d_search),
        OUT_DINDEX: (dict(common, artifact=OUT_DINDEX, counts={"labels": len(d_index)}), d_index),
        OUT_SEALED: (dict(common, artifact=OUT_SEALED, access="scripts only while SEALED",
                          counts={"records": len(sealed_lines)}), sealed_lines),
        OUT_PLAN: (dict(common, artifact=OUT_PLAN, preregistered_outcome=PREREGISTERED_OUTCOME, parameters={
            "omq15": "narrow purposive (4 criteria) ∪ stratified random", "n": N_RANDOM, "n_used": n,
            "n_raised_to": n_raised, "floor": FLOOR, "seed": SEED, "population": "discovery labels, Tier U/Z",
            "purposive_criteria": ["CONTRADICTION-ROW: a row whose types contains CONTRADICTION",
                                   "TWO-FORMAL-ROWS: >= 2 rows with a non-empty type_signature",
                                   "MATH-STAT-TYPE-QUESTION: a row with review_flag in " + "/".join(sorted(REVIEW_Q)),
                                   "STRONG-LINEAGE: a lineage_claims kind in " + "/".join(sorted(STRONG_LINEAGE))],
            "strata": {"row_count": "distinct rows; bands r0-1 (0-row labels included), r2-3, r4-9, r10+",
                       "pair_count": "reconciliation_objects pair_count; bands p0, p1-2, p3+",
                       "provenance": "PRIMARY-only if every row source's 02-FILES provenance is PRIMARY "
                                     "(vacuously for 0 rows), else ANY-NON-PRIMARY",
                       "key": "row_band|pair_band|provenance, sorted as strings"},
            "allocation": "quota max(f, n*|k|/N); integer parts; remainders by largest fractional part then key; "
                          "total asserted = n; a stratum smaller than f is taken whole",
            "draw": "one random.Random(seed) consumed by strata in ascending key order; labels sorted; rng.sample"},
            counts={"population": len(pop), "purposive": sum(1 for x in pop if crit[x]),
                    "random_sampled": len(sampled), "strata": len(strata),
                    "checklist_total": sum(1 for p_ in plan if p_["in_checklist"])}), [canon(p_) for p_ in plan]),
    }
    for name, (hdr, lines) in outs.items():
        hdr["output_sha256"] = sha256_bytes(body(lines))
    work_header = dict(common, artifact=OUT_DWORK, output_sha256=sha256_bytes(canon(workload).encode("utf-8")))
    seal = dict(common, artifact=OUT_SEAL, variant="S", state="SEALED" if ok else "NOT-SEALED",
                holdout_labels=sorted(H), holdout_files=sorted(HF),
                holdout_label_list_sha256=sha_list(H), holdout_file_list_sha256=sha_list(HF),
                component_ids=sorted(min(comps[i]) for i in chosen),
                exclusions_first_rule=dict(sorted(collections.Counter(first_fail.values()).items())),
                exclusions_per_rule_R0_R3_over_all_R4_R7_over_candidates={k: len(v) for k, v in sorted(per_rule.items())},
                counts={"components": len(comps), "giant": len(giant), "holdout_components": len(chosen),
                        "holdout_labels": len(H), "holdout_files": len(HF), "s5_eligible": s5_eligible,
                        "target": target, "cross_groups": cross_groups, "cross_pairs": cross_pairs},
                hf_sid_mentions_in_discovery_bundles={s: sorted(v) for s, v in sorted(mentions.items())},
                derived_outputs=dict({k: v[0]["output_sha256"] for k, v in outs.items()}, **{OUT_DWORK: work_header["output_sha256"]}),
                sealed_at_utc=common["run_timestamp_utc"])

    print(f"S3b: components {len(comps)} (giant {len(giant)}) · hold-out {len(chosen)} comps / {len(H)} labels / {len(HF)} files "
          f"· exclusions first {seal['exclusions_first_rule']} per-rule {seal['exclusions_per_rule_R0_R3_over_all_R4_R7_over_candidates']} · target {target}")
    print(f"  cross groups/pairs {cross_groups}/{cross_pairs} · discovery bundles naming a hold-out label {len(named)} · "
          f"citing HF {len(hf_src_bundles)} · HF S-id mentions {sum(len(v) for v in mentions.values())} in "
          f"{len(set().union(*mentions.values())) if mentions else 0} bundles")
    print(f"  discovery search: {len(disc)} labels, {len(dims)} dims, NEGATIVE-CENSUS (discovery basis) {neg}, files {len(disc_files)}, "
          f"hits {sum(hits_left.values())} · identity_summary {idsum}")
    print(f"  sample plan: population {len(pop)}, purposive {outs[OUT_PLAN][0]['counts']['purposive']}, random {len(sampled)} "
          f"over {len(strata)} strata (n used {n}), checklist {outs[OUT_PLAN][0]['counts']['checklist_total']}")
    print(f"  discovery workload: hits {sum(hd)} over {len(lwd)} labels; p50 {q(.5)} p90 {q(.9)} p99 {q(.99)} max "
          f"{hd[-1] if hd else 0}; stage-2 reading {workload['stage2_whole_file_reading_chars_if_every_hit_file_read_once_per_label']/1e6:.1f} M chars")
    print(f"  seal id {seal_id} · label sha {seal['holdout_label_list_sha256'][:16]} · file sha {seal['holdout_file_list_sha256'][:16]}")
    for f_ in failures:
        print("  FAIL:", f_)

    def write(root):
        for name, (hdr, lines) in outs.items():
            p = os.path.join(root, os.path.basename(name) if root != CR else name)
            os.makedirs(os.path.dirname(p), exist_ok=True)
            with open(p, "wb") as f:
                f.write((canon({"header": hdr}) + "\n").encode("utf-8") + body(lines))
        with open(os.path.join(root, OUT_DWORK), "w", encoding="utf-8") as f:
            json.dump({"header": work_header, "body": workload}, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
        with open(os.path.join(root, OUT_SEAL), "w", encoding="utf-8") as f:
            json.dump(seal, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
    if emit is not None:
        os.makedirs(emit, exist_ok=True)
        write(emit)
        print(f"emitted would-be outputs to {emit} (review only)")
    if ok and not dry_run:
        write(CR)
        print(f"wrote {OUT_SEAL} (state SEALED), {OUT_PLAN}, {OUT_DSEARCH}, {OUT_DINDEX}, {OUT_SEALED}, {OUT_DWORK}")
    print("S3b RESULT:", "PASS" if ok else "FAIL — STOP; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
