#!/usr/bin/env python3
"""EG-3 probe (synthetic only; no production file read). Builds synthetic hub slices shaped like
p3b_s5_prepare.Context.slice (keys, LABEL-HITS/DIMENSION records, source_meta), renders them with the PRODUCTION
slice_view (p3b_s5_r7_universe), then:
  P1 cost: reads at 25 lines for FULL vs the proposed REQUIRED set (K full + M targeted), chars displayed;
  P2 witness: the required line set is computable from view bytes alone (paths on each line);
  P3 detection: displayed-range coverage vs required set (full / targeted / skip-dims / skip-search / hits-only).
Fake S-ids S9xxx; fake label names."""
import importlib.util, json, os, random, sys
CR = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/chronological-read"
sp = importlib.util.spec_from_file_location("u", os.path.join(CR, "scripts/p3b_s5_r7_universe.py"))
U = importlib.util.module_from_spec(sp); sp.loader.exec_module(U)
canon = lambda x: json.dumps(x, sort_keys=True, ensure_ascii=False, separators=(",", ":"))
META = ("status", "provenance", "best_historical_date", "best_historical_date_basis", "explicit_dates", "mtime_block", "file_mtime")

def synth(n_hit_files, hits_per_file, n_dims, n_rows, n_dates=3, seed=1):
    rnd = random.Random(seed)
    lab = "synthetic-hub-label"
    own = [f"S9{i:03d}" for i in range(n_rows)]                       # row sources (plan files)
    hitf = [f"S8{i:03d}" for i in range(n_hit_files)]                  # hit-only files
    terms = [f"term{i}" for i in range(6)]
    raw = {s: sorted([[rnd.randrange(10**6), rnd.randrange(6)] for _ in range(hits_per_file)]) for s in hitf}
    led = {s: [[f"a{k}", 0] for k in range(2)] for s in own[:2]}
    lh = {"record": "LABEL-HITS", "label": lab, "terms": terms, "ledger_hits": led, "raw_hits": raw, "population_basis": "X"}
    dims = [{"record": "DIMENSION", "label": lab, "dimension": f"dim{d}", "hits_ref": lab, "scope": "DISCOVERY-POPULATION",
             "files_searched": 5000, "firewall_skipped": [f"S70{k:02d}" for k in range(12)], "negative_label": None,
             "population_basis": "X", "identity_summary": {"stasis_unobservable": 1, "p0_row_exception": 0}} for d in range(n_dims)]
    rows = [canon({"source_id": s, "anchor": "x", "text": "r" * 200}) for s in own]
    hub_record = {"working_label": lab, "tier": "U", "in_checklist": False, "predicted_dispositions": 9999, "distinct_hit_keys": 99,
                  "absence_dimensions": n_dims, "predicted_stage2_bytes": 1, "predicted_stage2_file_reads": n_hit_files,
                  "rule": "T4", "threshold": 4081, "reason": "LOAD", "treatment": "t", "evidence_unavailable": "e"}
    sl = {"working_label": lab, "batch_id": "OB9999", "run_id": "OB9999-R2", "contract_sha256": "0" * 64, "input_manifest_sha256": "0" * 64,
          "tier": "U", "in_checklist": False, "population_basis": "X", "bundle": {"rows_verbatim": rows, "sources_verbatim": [], "reconciliation_object": None},
          "family_md": "f" * 3000, "family_md_status": "OK", "family_md_sha256": "0" * 64,
          "source_tracks": {s: {"track_tag": "TRACK-A", "d23_track": "A"} for s in own},
          "search_records": [lh] + dims, "stage2_files": sorted(set(raw) | set(led)),
          "p3a_pairs": [], "pairs_touching_missing": [], "semantic_status_mechanical": None,
          "hub": True, "hub_record": hub_record, "quarantine": {"p3a_pairs_withheld": 0}}
    sids = sorted(set(own) | set(hitf) | {f"S70{k:02d}" for k in range(12)})
    sl["source_meta"] = {s: {"status": "CONTENT", "provenance": "PRIMARY", "best_historical_date": "2026-01-01",
                             "best_historical_date_basis": "EXPLICIT", "explicit_dates": ["2026-01-0%d" % (k + 1) for k in range(n_dates)],
                             "mtime_block": None, "file_mtime": "2026-01-01T00:00:00Z"} for s in sids}
    return canon(sl), own

def paths(view):
    ls = view.split("\n")[1:-1]
    return [json.loads(l.split("\t", 1)[0]) for l in ls]          # view line n (1-based file line = i+2)

def required(view, run_files, tracks):
    """Proposed HUB-REQUIRED set (1-based file line numbers). K = every key except search_records, source_meta;
    M-a = search_records[0] leaves except EVERY leaf of the ledger_hits/raw_hits subtrees (rule 3 as drafted);
    M-b = search_records[i>=1] (DIMENSION records) in full; M-c = source_meta[S] for S in run files ∪ source_tracks."""
    req = {1}
    T = set(run_files) | set(tracks)
    for i, p in enumerate(paths(view)):
        n = i + 2
        k = p[0]
        if k not in ("search_records", "source_meta"):
            req.add(n)
        elif k == "search_records":
            if p[1] != 0:
                req.add(n)
            elif len(p) >= 3 and p[2] in ("ledger_hits", "raw_hits"):
                pass                                   # rule 3 (v2): EVERY hit-map leaf excluded (incl. an E-kind empty map)
            else:
                req.add(n)
        elif k == "source_meta" and len(p) >= 2 and p[1] in T:
            req.add(n)
    return req

def ranges_of(lines):
    out, s = [], None
    for n in sorted(lines):
        if s is None or n != e + 1:
            if s is not None: out.append((s, e))
            s = n
        e = n
    if s is not None: out.append((s, e))
    return out

def reads_for(ranges, page=25):
    return sum(-(-(b - a + 1) // page) for a, b in ranges)

def displayed(ranges, page=25):
    """Simulate harness Reads with offset/limit<=page over the given ranges -> list of [min,max] displayed ranges."""
    out = []
    for a, b in ranges:
        x = a
        while x <= b:
            y = min(b, x + page - 1); out.append([x, y]); x = y + 1
    return out

def coverage_missing(req, disp):
    cov = set()
    for a, b in disp: cov.update(range(a, b + 1))
    return req - cov

def run(name, **kw):
    s, own = synth(**kw)
    v = U.slice_view(s)
    import re
    d = json.loads(s)
    for k in ('search_records', 'stage2_files', 'source_meta'): d.pop(k)
    T = sorted(set(re.findall(r'\bS\d{4}\b', canon(d))))
    assert U.slice_view_violations(s, v, "probe") == []
    nlines = len(v.split("\n")) - 1
    req = required(v, T, [])
    assert T == sorted(own), 'T(L) must equal the synthetic citable S-ids'
    e2(v, req, T)
    rr = ranges_of(req)
    L = v.split("\n")
    chars_full = sum(len(l) + 8 for l in L[1:])
    chars_req = sum(len(L[n - 1]) + 8 for n in req)
    print(f"{name}: view lines {nlines}; FULL reads {-(-nlines // 25)} ({chars_full:,} chars); "
          f"REQUIRED lines {len(req)} in {len(rr)} ranges -> reads {reads_for(rr)} ({chars_req:,} chars)")
    # P3 detection scenarios
    full = displayed([(1, nlines)])
    tgt = displayed(rr)
    ps = paths(v)
    dimlines = {i + 2 for i, p in enumerate(ps) if p[0] == "search_records" and p[1] != 0}
    srl = {i + 2 for i, p in enumerate(ps) if p[0] == "search_records"}
    hitsonly = {i + 2 for i, p in enumerate(ps) if p[0] == "search_records" and len(p) > 2 and p[2] == "raw_hits"}
    sc = {"full": full, "targeted": tgt, "skip-dimension-records": displayed(ranges_of(req - dimlines)),
          "skip-all-search_records": displayed(ranges_of(req - srl)),
          "hits-only-search_records": displayed(ranges_of((req - srl) | hitsonly))}
    for k, d in sc.items():
        m = coverage_missing(req, d)
        print(f"   {k:28s} displayed-ranges {len(d):5d}  required-missing {len(m):5d}  -> {'COVERED' if not m else 'NOT-COVERED'}")
    return nlines, len(req)

def e2(v, req, T):
    """RED test E2 as written in SPEC-A §5.2: exact set equality with the independent expectation."""
    exp, ps = {1}, paths(v)
    for i, p in enumerate(ps):
        n = i + 2
        hit = p[0] == "search_records" and p[1] == 0 and len(p) >= 3 and p[2] in ("ledger_hits", "raw_hits")
        meta_out = p[0] == "source_meta" and (len(p) < 2 or p[1] not in T)
        if not hit and not meta_out:
            exp.add(n)
        assert not (hit and n in req), f"E2: hit leaf {p} required"
        assert not (meta_out and n in req), f"E2: source_meta outside T(L) {p} required"
    for key in ("label", "population_basis", "record", "terms"):
        assert any(ps[n - 2][:3] == ["search_records", 0, key] for n in req if n > 1), f"E2: search_records[0].{key} missing"
    assert req == exp, "E2: set inequality"
    print("   E2 PASS (no hit-map leaf, no source_meta outside T(L); required == expected)")

if __name__ == "__main__":
    print("losslessness check uses production slice_view/slice_view_parse")
    run("small ", n_hit_files=40, hits_per_file=30, n_dims=4, n_rows=3)
    run("median", n_hit_files=150, hits_per_file=16, n_dims=5, n_rows=4)
    run("large ", n_hit_files=1500, hits_per_file=40, n_dims=6, n_rows=40)


def e2_discriminates():
    """E2 negative control: the superseded first-leaf variant (v1 probe) must FAIL E2."""
    import re
    s, own = synth(n_hit_files=40, hits_per_file=30, n_dims=4, n_rows=3)
    v = U.slice_view(s)
    req = required(v, own, [])
    ps = paths(v)
    first = next(i + 2 for i, p in enumerate(ps) if p[:3] == ["search_records", 0, "raw_hits"])
    try:
        e2(v, req | {first}, own)
    except AssertionError as ex:
        print(f"   E2 negative control: first-leaf variant REJECTED ({ex})")
        return
    raise SystemExit("E2 does not discriminate the first-leaf variant")


if __name__ == "__main__":
    e2_discriminates()
