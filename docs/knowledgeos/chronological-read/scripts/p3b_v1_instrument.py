#!/usr/bin/env python3
"""S-Series V1 instrument validation: mechanical tooling (non-production). Infrastructure; G-LOG-0060.

It implements the deterministic parts of the V1 pre-registration
(`audit-p3b/20260925_1530_v1-instrument-validation-preregistration.md`):
  * segmentation (§5–§6);
  * the structural-coverage and quote checks (§9, §14);
  * the blinded, structure-equalized matching lists (§11);
  * the second-matcher sample (§12);
  * Cohen's kappa (§13);
  * the access and canary checks (§15);
  * the mechanical V1 decision (§17).

It executes no agent and decides nothing that the pre-registration does not fix.

Sub-commands:
  selftest                  synthetic data only; reads no corpus (allowed at any time)
  segment   --commit C      writes v1/SEGMENT-MAP.json            (corpus via resolver; needs V1 authorization)
  check     --commit C      writes v1/CHECK.json                  (corpus via resolver; needs V1 authorization)
  equalize  --commit C      writes v1/MATCH-LISTS.json + v1/SOURCE-KEY.json (needs V1 authorization)
  sample    --commit C      writes v1/SAMPLE-A02.json             (needs V1 authorization)
  result    --commit C      writes v1/V1-RESULT.json              (needs V1 authorization)

The execution sub-commands refuse unless `pilot-s5-decomp/v1/V1-AUTHORIZATION.json` exists and names the
pre-registration commit and the pre-registration file sha256. That file is written only after a human authorizes V1
execution. Content is read only through the seal-aware `discovery_resolver()`; checker reads are not agent reads.
"""
import hashlib
import importlib.util
import json
import math
import os
import random
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))

PREREG = "audit-p3b/20260925_1530_v1-instrument-validation-preregistration.md"
V1_DIR = "pilot-s5-decomp/v1"
PAGE_CHARS = 20000                  # the paged reader's page size (contract revision 3)
MAX_SEG = 6000                      # §5: maximum segment length in characters (ARBITRARY engineering value)
OPEN_TEXT = 80                      # §6: characters of whitespace-normalized segment opening shown in the map

LABEL_C = "knowledgeos-architecture-constitution-v01"
LABEL_S = "step-verify-programme"
# §2: the six files, fixed. (source_id, label, content_sha256, n_pages, chars), from PX0004-U01/U02 READ-LOGs
FILES = (
    ("S1022", LABEL_C, "2ccee923f1350a7e199689d442514f5e69797ccd33bf142bd9036817dadae0e2", 1, 15541),
    ("S1021", LABEL_C, "afca77203bf2429dc8179db857bc13f25c6844c32988ff86065931c3133fabbf", 2, 20086),
    ("S1484", LABEL_C, "2e5ee4495c2f84c079d76242583d0153c719e9c79864f0f5c070721c9f1fecba", 3, 59434),
    ("S1413", LABEL_S, "05a3b0100444bb07c2392c5563b8e4ed8acf7f11f91ab16a5964ddcacabdf639", 2, 24219),
    ("S1521", LABEL_S, "25decfad7a09927f1a6a28f8dedd6f6422d4808c0db1e9b66dbdd0b6d0420bbe", 2, 30455),
    ("S1541", LABEL_S, "0695b63b877d1cab182c99c8d8d9f27af1398c871568c727b8392dda930a3d25", 9, 174052),
)
# §3: runs, roles, model aliases (Agent tool enum) and label scope
RUNS = {
    "PX0106-U01": ("SEG", "opus", LABEL_C), "PX0106-U02": ("SEG", "opus", LABEL_S),
    "PX0106-U03": ("E1", "fable", LABEL_C), "PX0106-U04": ("E1", "fable", LABEL_S),
    "PX0106-U05": ("E2", "haiku", LABEL_C), "PX0106-U06": ("E2", "haiku", LABEL_S),
    "PX0106-A01": ("M1", "sonnet", None), "PX0106-A02": ("M2", "fable", None),
}
SOURCES = ("SEG", "E1", "E2")

# §7: proposition schema (closed sets)
TYPES = ("DEFINITION", "NOTATION", "CLAIM", "RULE", "FORMULA", "THEOREM-OR-RESULT", "ALGORITHM", "EXAMPLE",
         "HYPOTHESIS", "OPEN-QUESTION", "CORRECTION", "CONTRADICTION", "RELATION", "LINEAGE", "GOVERNANCE",
         "METHOD", "SCOPE")
STATUSES = ("ASSERTED", "EXAMPLE", "HYPOTHETICAL", "RETRACTED")
DECLINES = ("RESTATEMENT", "EXAMPLE-ONLY", "NON-MATERIAL", "OUT-OF-LABEL-SCOPE")
MATCH_STATUS = ("MATCH", "PARTIAL", "NONE")

# §17: engineering decision rules; every numeric value is ARBITRARY (engineering threshold, not scientific)
KAPPA_VALID, KAPPA_UNUSABLE = 0.60, 0.40
CAPTURE_VALID, CAPTURE_UNUSABLE = 0.80, 0.50
COVERAGE_UNUSABLE = 0.95
QUOTE_MISS_UNUSABLE = 0.05

WS = re.compile(r"\s+")
HEAD = re.compile(r"#{1,6}[ \t]+\S")


class V1Error(RuntimeError):
    pass


def sha(s):
    return hashlib.sha256(s.encode("utf-8")).hexdigest()


def norm(s):
    return WS.sub(" ", s).strip()


def seed_of(commit):
    """§12: the integer seed is the first 8 hex digits of the pre-registration commit."""
    if not re.fullmatch(r"[0-9a-f]{7,40}", commit or ""):
        raise V1Error("a pre-registration commit hash is required")
    return int(commit[:8], 16)


def canary(commit, run):
    """§15: a unique non-corpus string placed in each agent's prompt; its presence in another run's output is leakage."""
    return "CANARY-" + sha(f"{commit}:{run}")[:12].upper()


# ---------------------------------------------------------------- §5 segmentation (deterministic)
def _lines(text):
    out, off = [], 0
    for ln in text.splitlines(keepends=True):
        out.append((off, ln))
        off += len(ln)
    return out


def segment(text, max_seg=MAX_SEG):
    """Tile [0, len(text)) into segments: [{char_start, char_end, kind, heading_level}], contiguous and exhaustive."""
    if not text:
        return [{"char_start": 0, "char_end": 0, "kind": "EMPTY", "heading_level": 0}]
    lines, fence, info = _lines(text), False, []
    for off, ln in lines:
        stripped = ln.lstrip(" ")
        is_fence = len(ln) - len(stripped) <= 3 and stripped.startswith(("```", "~~~"))
        head = (not fence and not is_fence and HEAD.match(ln) is not None)
        level = len(ln) - len(ln.lstrip("#")) if head else 0
        info.append((off, ln, fence or is_fence, head, level))
        if is_fence:
            fence = not fence
    starts = [(0, info[0][4] if info[0][3] else 0, "HEADING" if info[0][3] else "PREAMBLE")]
    starts += [(off, lvl, "HEADING") for off, _, _, head, lvl in info if head and off > 0]
    raw = [(s, starts[i + 1][0] if i + 1 < len(starts) else len(text), lvl, kind)
           for i, (s, lvl, kind) in enumerate(starts)]
    # paragraph cut points: the start of a line that follows a blank line, outside a fence
    cuts = [info[i][0] for i in range(1, len(info)) if info[i - 1][1].strip() == "" and not info[i][2]]
    segs = []
    for s, e, lvl, kind in raw:
        cur, first = s, True
        while e - cur > max_seg:
            cand = [c for c in cuts if cur < c <= cur + max_seg]
            c = cand[-1] if cand else cur + max_seg
            segs.append({"char_start": cur, "char_end": c, "kind": kind if first else "SPLIT",
                         "heading_level": lvl if first else 0})
            cur, first = c, False
        segs.append({"char_start": cur, "char_end": e, "kind": kind if first else "SPLIT",
                     "heading_level": lvl if first else 0})
    merged = []                      # whitespace-only segments merge into the previous one (or the next, if first)
    for sg in segs:
        if text[sg["char_start"]:sg["char_end"]].strip() == "" and merged:
            merged[-1]["char_end"] = sg["char_end"]
        else:
            merged.append(dict(sg))
    if len(merged) > 1 and text[merged[0]["char_start"]:merged[0]["char_end"]].strip() == "":
        merged[1]["char_start"] = 0
        merged.pop(0)
    return merged


def segment_map(sid, text):
    out = []
    for i, sg in enumerate(segment(text), 1):
        a, b = sg["char_start"], sg["char_end"]
        out.append({"segment_id": f"{sid}-g{i:03d}", "source_id": sid, "char_start": a, "char_end": b,
                    "page_first": a // PAGE_CHARS + 1, "page_last": max(a, b - 1) // PAGE_CHARS + 1,
                    "kind": sg["kind"], "heading_level": sg["heading_level"],
                    "segment_sha256": sha(text[a:b]), "open_text": norm(text[a:b])[:OPEN_TEXT]})
    return out


def tiles(segs, n):
    return bool(segs) and segs[0]["char_start"] == 0 and segs[-1]["char_end"] == n and \
        all(segs[i]["char_end"] == segs[i + 1]["char_start"] for i in range(len(segs) - 1))


# ---------------------------------------------------------------- §9 quotes, §14 coverage, §7 schema
def quote_status(q, text, a=0, b=None):
    """EXACT / WHITESPACE (inside text[a:b]) / OUT-OF-RANGE (in the file, not in the range) / MISS."""
    region = text[a:b if b is not None else len(text)]
    if q and q in region:
        return "EXACT"
    if norm(q) and norm(q) in norm(region):
        return "WHITESPACE"
    if q and (q in text or norm(q) in norm(text)):
        return "OUT-OF-RANGE"
    return "MISS"


def check_inventory(records, segmap, texts):
    """SEG output: exactly one record per segment; schema; quotes inside their segment."""
    by_id = {s["segment_id"]: s for s in segmap}
    seen, res = {}, {"schema": [], "unknown_segments": [], "duplicate_segments": [], "quotes": {}}
    for r in records:
        sid = r.get("segment_id")
        if sid not in by_id:
            res["unknown_segments"].append(sid)
            continue
        if sid in seen:
            res["duplicate_segments"].append(sid)
        seen[sid] = r
        seg = by_id[sid]
        if r.get("result") == "NO-SUBSTANTIVE-PROPOSITION":
            if not str(r.get("reason", "")).strip() or r.get("propositions"):
                res["schema"].append((sid, "no-substantive without reason, or with propositions"))
            continue
        if r.get("result") != "PROPOSITIONS" or not r.get("propositions"):
            res["schema"].append((sid, "result"))
            continue
        for p in r["propositions"]:
            bad = [k for k, ok in (("proposition_type", p.get("proposition_type") in TYPES),
                                   ("status", p.get("status") in STATUSES),
                                   ("register_decision", p.get("register_decision") in ("PROMOTE", "DECLINE")),
                                   ("decline_reason", (p.get("register_decision") != "DECLINE")
                                    or p.get("decline_reason") in DECLINES),
                                   ("explicit", p.get("proposition_type") != "RELATION"
                                    or isinstance(p.get("explicit"), bool)),
                                   ("statement", bool(str(p.get("statement", "")).strip())),
                                   ("quote", bool(str(p.get("quote", "")).strip()))) if not ok]
            if bad:
                res["schema"].append((p.get("proposition_id"), bad))
            st = quote_status(p.get("quote", ""), texts[seg["source_id"]], seg["char_start"], seg["char_end"])
            res["quotes"][st] = res["quotes"].get(st, 0) + 1
    cov = {}
    for s in segmap:
        c = cov.setdefault(s["source_id"], [0, 0])
        c[1] += 1
        c[0] += s["segment_id"] in seen
    res["coverage"] = {k: v[0] / v[1] for k, v in sorted(cov.items())}
    return res


def check_open(records, allowed_sids, texts):
    res = {"schema": [], "quotes": {}}
    for r in records:
        if r.get("source_id") not in allowed_sids or not str(r.get("statement", "")).strip():
            res["schema"].append(r.get("item_id"))
            continue
        st = quote_status(r.get("quote", ""), texts[r["source_id"]])
        res["quotes"][st] = res["quotes"].get(st, 0) + 1
    return res


def miss_rate(q):
    n = sum(q.values())
    return (q.get("MISS", 0) + q.get("OUT-OF-RANGE", 0)) / n if n else 0.0


# ---------------------------------------------------------------- §11 blinded, structure-equalized lists
def flatten_inventory(records):
    for r in records:
        for p in r.get("propositions") or []:
            yield {"orig_id": p["proposition_id"], "source_id": r["source_id"], "statement": p["statement"],
                   "quote": p["quote"]}


def equalize(outputs, commit):
    """outputs: {source: [records {orig_id, source_id, statement, quote}]} → (lists, key). Letters are permuted by
    seed; item ids are hashed; items are sorted by (source_id, item_id), which removes segment order."""
    rng = random.Random(seed_of(commit))
    letters = ["X", "Y", "Z"]
    rng.shuffle(letters)
    key = {"letter_of_source": dict(zip(SOURCES, letters)), "items": {}}
    lists = {}
    for src in SOURCES:
        L = key["letter_of_source"][src]
        items = []
        for r in outputs[src]:
            iid = "I" + sha(f"{commit}:{src}:{r['orig_id']}")[:10]
            key["items"][iid] = {"source": src, "orig_id": r["orig_id"]}
            items.append({"item_id": iid, "list": L, "source_id": r["source_id"], "statement": r["statement"],
                          "quote": r["quote"]})
        lists[L] = sorted(items, key=lambda x: (x["source_id"], x["item_id"]))
    return {k: lists[k] for k in sorted(lists)}, key


# ---------------------------------------------------------------- §12 second-matcher sample
def draw_sample(clusters, commit, frac=0.30, floor=5):
    """clusters: [{cluster_id, list, source_id}]. Stratum = (source_id, list); n = min(N, max(floor, ceil(frac·N)))."""
    rng = random.Random(seed_of(commit))
    strata = {}
    for c in clusters:
        strata.setdefault((c["source_id"], c["list"]), []).append(c["cluster_id"])
    out = []
    for k in sorted(strata):
        ids = sorted(strata[k])
        n = min(len(ids), max(floor, math.ceil(frac * len(ids))))
        out.extend(sorted(rng.sample(ids, n)))
    return out


# ---------------------------------------------------------------- §13 Cohen's kappa
def _kappa(pairs):
    n = len(pairs)
    if n == 0:
        return None
    po = sum(a == b for a, b in pairs) / n
    cats = {x for p in pairs for x in p}
    pe = sum((sum(a == c for a, _ in pairs) / n) * (sum(b == c for _, b in pairs) / n) for c in cats)
    return 1.0 if pe == 1 else (po - pe) / (1 - pe)


def kappas(dec1, dec2, sample):
    """dec: [{cluster_id, other_list, status, target_cluster}] per matcher. The decision unit is (cluster, other
    list) for each sampled cluster. κ_target (primary): label NONE or STATUS:target. κ_status: diagnostic."""
    def idx(dec):
        return {(d["cluster_id"], d["other_list"]): d for d in dec}
    i1, i2, S = idx(dec1), idx(dec2), set(sample)
    units = sorted(k for k in i1 if k[0] in S)
    missing = [u for u in units if u not in i2]

    def lab(d, with_target):
        return "NONE" if d["status"] == "NONE" else (f"{d['status']}:{d.get('target_cluster')}" if with_target
                                                     else d["status"])
    pt = [(lab(i1[u], True), lab(i2[u], True)) for u in units if u in i2]
    ps = [(lab(i1[u], False), lab(i2[u], False)) for u in units if u in i2]
    return {"units": len(units), "missing_in_m2": len(missing), "kappa_target": _kappa(pt),
            "kappa_status": _kappa(ps), "raw_agreement_target": (sum(a == b for a, b in pt) / len(pt)) if pt else None}


# ---------------------------------------------------------------- §16 capture of the E1∩E2-agreed set
def capture(clusters, dec1, key_letters):
    """Share of E1 clusters that M1 matched (MATCH) to an E2 cluster and that M1 also matched to a SEG cluster:
    strict = MATCH, lenient = MATCH or PARTIAL."""
    L = key_letters
    by = {(d["cluster_id"], d["other_list"]): d for d in dec1}
    agreed = [c["cluster_id"] for c in clusters if c["list"] == L["E1"]
              and by.get((c["cluster_id"], L["E2"]), {}).get("status") == "MATCH"]
    if not agreed:
        return {"agreed": 0, "strict": None, "lenient": None}
    seg = [by.get((cid, L["SEG"]), {}).get("status") for cid in agreed]
    return {"agreed": len(agreed), "strict": seg.count("MATCH") / len(agreed),
            "lenient": (seg.count("MATCH") + seg.count("PARTIAL")) / len(agreed)}


def chapman(n1, n2, m):
    """Diagnostic only (§16): Chapman's bias-corrected two-source estimate; valid only under independence."""
    return (n1 + 1) * (n2 + 1) / (m + 1) - 1


# ---------------------------------------------------------------- §17 decision
def decide(m):
    """m: {integrity_ok, schema_ok, coverage_min, quote_miss_max, kappa_target, capture_lenient} → (outcome, reasons)."""
    if not m["integrity_ok"]:
        return "NEEDS-REVISION", ["RUN-INVALID: protocol-integrity breach; no instrument conclusion is drawn"]
    r = []
    k, cap = m["kappa_target"], m["capture_lenient"]
    if m["coverage_min"] < COVERAGE_UNUSABLE:
        r.append("segment coverage below 0.95 after repair")
    if m["quote_miss_max"] > QUOTE_MISS_UNUSABLE:
        r.append("quote misses above 5% of a source after repair")
    if k is None or k < KAPPA_UNUSABLE:
        r.append("kappa_target below 0.40 or not computable")
    if cap is None or cap < CAPTURE_UNUSABLE:
        r.append("capture of the E1∩E2-agreed set below 0.50 or not computable")
    if r:
        return "NOT-USABLE", r
    if m["schema_ok"] and m["coverage_min"] == 1.0 and m["quote_miss_max"] == 0.0 and k >= KAPPA_VALID \
            and cap >= CAPTURE_VALID:
        return "INSTRUMENTS-VALID", ["all hard conditions met; engineering gates met"]
    return "NEEDS-REVISION", [x for x, bad in (("schema violations remain", not m["schema_ok"]),
                                               ("segment coverage < 1.0", m["coverage_min"] < 1.0),
                                               ("quote misses > 0", m["quote_miss_max"] > 0),
                                               ("kappa_target < 0.60", k < KAPPA_VALID),
                                               ("capture < 0.80", cap < CAPTURE_VALID)) if bad]


# ---------------------------------------------------------------- execution guard and CLI
def _cr():
    spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
    c = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(c)
    return c


def authorized(c, commit):
    p = os.path.join(c.CR, V1_DIR, "V1-AUTHORIZATION.json")
    if not os.path.exists(p):
        raise V1Error("V1 execution is not authorized (no V1-AUTHORIZATION.json)")
    with open(p, encoding="utf-8") as f:
        a = json.load(f)
    if a.get("prereg_commit", "")[:len(commit)] != commit or a.get("prereg_sha256") != c.sha256_file(PREREG):
        raise V1Error("V1 authorization does not match the pre-registration commit / file hash")
    return a


def texts_of(c):
    c.assert_sealed()
    res = c.dio.discovery_resolver()
    data = res.read_many([f[0] for f in FILES])
    out = {}
    for sid, _, csha, npages, chars in FILES:
        if res.rows[sid]["content_sha256"] != csha:
            raise V1Error(f"{sid}: content hash differs from the pre-registration")
        t = data[sid].decode("utf-8", errors="replace")
        if len(t) != chars:
            raise V1Error(f"{sid}: decoded length differs from the pre-registration")
        out[sid] = t
    return out


def selftest():
    t = "intro line\n\n# A\npara one\n\n```\n# not a heading\n```\n\n## B\n" + ("word " * 3000) + "\n\n   \n"
    segs = segment(t)
    assert tiles(segs, len(t)), "segments must tile"
    assert [s["kind"] for s in segs][:3] == ["PREAMBLE", "HEADING", "HEADING"], segs
    assert all(s["char_end"] - s["char_start"] <= MAX_SEG for s in segs)
    assert segment(t) == segs, "determinism"
    assert quote_status("para  one", t) == "WHITESPACE" and quote_status("zzz", t) == "MISS"
    assert _kappa([("a", "a"), ("b", "b"), ("a", "b")]) is not None
    assert decide({"integrity_ok": True, "schema_ok": True, "coverage_min": 1.0, "quote_miss_max": 0.0, "kappa_target": 0.7,
                   "capture_lenient": 0.9})[0] == "INSTRUMENTS-VALID"
    print("selftest PASS")
    return 0


def _jl(path):
    if not os.path.exists(path):
        return []
    with open(path, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


def _dump(c, name, obj):
    os.makedirs(os.path.join(c.CR, V1_DIR), exist_ok=True)
    with open(os.path.join(c.CR, V1_DIR, name), "w", encoding="utf-8") as f:
        json.dump(obj, f, indent=1, sort_keys=True, ensure_ascii=False)
        f.write("\n")


def _load(c, name):
    with open(os.path.join(c.CR, V1_DIR, name), encoding="utf-8") as f:
        return json.load(f)


def with_repair(records, repairs, key):
    """§9: one repair round. A repair record replaces the record with the same key; {key, "withdrawn": true}
    removes it; a repair record with a new key is added (e.g. a missing segment)."""
    rep = {r[key]: r for r in repairs}
    out = [rep.pop(r[key]) if r.get(key) in rep else r for r in records]
    out += list(rep.values())
    return [r for r in out if not r.get("withdrawn")]


def output(c, run, repaired=True):
    """Agent output file v1/OUT-<run>.jsonl: line 1 is the header {run_id, model_self_report, canary}; the rest are
    records. Repairs are in v1/OUT-<run>.repair.jsonl."""
    rows = _jl(os.path.join(c.CR, V1_DIR, f"OUT-{run}.jsonl"))
    head, recs = (rows[0], rows[1:]) if rows else ({}, [])
    if repaired:
        recs = with_repair(recs, _jl(os.path.join(c.CR, V1_DIR, f"OUT-{run}.repair.jsonl")),
                           "segment_id" if RUNS[run][0] == "SEG" else "item_id")
    return head, recs


def access(c, commit, texts):
    """§15: read discipline from each run's READ-LOG and canary isolation across all outputs."""
    breaches = []
    for run, (role, _, label) in RUNS.items():
        log = _jl(os.path.join(c.CR, "pilot-s5-decomp", run, "READ-LOG.jsonl"))
        allowed = {f[0] for f in FILES if label is None or f[1] == label}
        pages = {}
        for r in log:
            if r.get("refused"):
                breaches.append((run, "refused read"))
            for sid in r.get("source_ids", []):
                if sid not in allowed:
                    breaches.append((run, "read outside the fixed files"))
            pg = r.get("page") or {}
            if pg:
                a, b = pg["char_start"], pg["char_end"]
                ok = pg["source_id"] in texts and sha(texts[pg["source_id"]][a:b]) == pg["page_sha256"]
                if not ok:
                    breaches.append((run, "page hash mismatch"))
                pages.setdefault(pg["source_id"], set()).add(pg["page"])
        if role in SOURCES:                   # extractors must read every page of each file of their label
            for sid, lab, _, n, _ in FILES:
                if lab == label and pages.get(sid, set()) != set(range(1, n + 1)):
                    breaches.append((run, f"incomplete page proof {sid}"))
    outs = {}
    for run in RUNS:
        p = os.path.join(c.CR, V1_DIR, f"OUT-{run}.jsonl")
        if os.path.exists(p):
            with open(p, encoding="utf-8") as f:
                outs[run] = f.read()
    for run, txt in outs.items():
        head = json.loads(txt.splitlines()[0]) if txt.strip() else {}
        if head.get("canary") != canary(commit, run):
            breaches.append((run, "own canary missing"))
        for other in RUNS:
            if other != run and canary(commit, other) in txt:
                breaches.append((run, f"canary of {other} present (leakage)"))
    return breaches


def cmd_segment(c, commit):
    texts = texts_of(c)
    files = {}
    for sid, label, *_ in FILES:
        m = segment_map(sid, texts[sid])
        if not tiles(m, len(texts[sid])):
            raise V1Error(f"{sid}: segments do not tile")
        files[sid] = {"label": label, "n_segments": len(m), "segments": m}
    _dump(c, "SEGMENT-MAP.json", {"prereg_commit": commit, "max_seg": MAX_SEG, "files": files})


def cmd_check(c, commit):
    texts = texts_of(c)
    segmap = [s for f in _load(c, "SEGMENT-MAP.json")["files"].values() for s in f["segments"]]
    res = {"prereg_commit": commit, "runs": {}}
    for run, (role, _, label) in RUNS.items():
        if role not in SOURCES:
            continue
        sids = {f[0] for f in FILES if f[1] == label}
        for stage, rep in (("before_repair", False), ("after_repair", True)):
            head, recs = output(c, run, rep)
            if role == "SEG":
                r = check_inventory(recs, [s for s in segmap if s["source_id"] in sids], texts)
            else:
                r = check_open(recs, sids, texts)
            r["quote_miss_rate"] = miss_rate(r["quotes"])
            r["model_self_report"] = head.get("model_self_report")
            res["runs"].setdefault(run, {})[stage] = r
    res["access_breaches"] = access(c, commit, texts)
    _dump(c, "CHECK.json", res)


def cmd_equalize(c, commit):
    outs = {s: [] for s in SOURCES}
    for run, (role, _, _) in RUNS.items():
        if role == "SEG":
            outs[role] += list(flatten_inventory(output(c, run)[1]))
        elif role in SOURCES:
            outs[role] += [{"orig_id": r["item_id"], "source_id": r["source_id"], "statement": r["statement"],
                            "quote": r["quote"]} for r in output(c, run)[1]]
    lists, key = equalize(outs, commit)
    _dump(c, "MATCH-LISTS.json", {"prereg_commit": commit, "lists": lists})
    _dump(c, "SOURCE-KEY.json", key)


def cmd_sample(c, commit):
    cl = _jl(os.path.join(c.CR, V1_DIR, "CLUSTERS-A01.jsonl"))
    _dump(c, "SAMPLE-A02.json", {"prereg_commit": commit, "sample": draw_sample(cl, commit)})


def cmd_result(c, commit):
    chk, key = _load(c, "CHECK.json"), _load(c, "SOURCE-KEY.json")
    cl = _jl(os.path.join(c.CR, V1_DIR, "CLUSTERS-A01.jsonl"))
    d1 = _jl(os.path.join(c.CR, V1_DIR, "DECISIONS-A01.jsonl"))
    d2 = _jl(os.path.join(c.CR, V1_DIR, "DECISIONS-A02.jsonl"))
    smp = _load(c, "SAMPLE-A02.json")["sample"]
    after = {run: v["after_repair"] for run, v in chk["runs"].items()}
    cov = [x for run, v in after.items() if RUNS[run][0] == "SEG" for x in v["coverage"].values()]
    k = kappas(d1, d2, smp)
    cap = capture(cl, d1, key["letter_of_source"])
    m = {"integrity_ok": not chk["access_breaches"],
         "schema_ok": all(not v["schema"] and not v.get("unknown_segments") and not v.get("duplicate_segments")
                          for v in after.values()),
         "coverage_min": min(cov) if cov else 0.0,
         "quote_miss_max": max(v["quote_miss_rate"] for v in after.values()),
         "kappa_target": k["kappa_target"], "capture_lenient": cap["lenient"]}
    outcome, reasons = decide(m)
    n = {s: sum(1 for x in cl if x["list"] == key["letter_of_source"][s]) for s in SOURCES}
    L = key["letter_of_source"]
    m12 = sum(1 for d in d1 if d["status"] == "MATCH" and d["other_list"] == L["E2"]
              and any(x["cluster_id"] == d["cluster_id"] and x["list"] == L["E1"] for x in cl))
    diag = {"kappa": k, "capture": cap, "clusters_per_source": n,
            "chapman_E1_E2_diagnostic_only": chapman(n["E1"], n["E2"], m12) if n["E1"] and n["E2"] else None}
    _dump(c, "V1-RESULT.json", {"prereg_commit": commit, "metrics": m, "diagnostics": diag,
                                "outcome": outcome, "reasons": reasons})


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    if not a:
        print(__doc__, file=sys.stderr)
        return 2
    if a[0] == "selftest":
        return selftest()
    cmds = {"segment": cmd_segment, "check": cmd_check, "equalize": cmd_equalize, "sample": cmd_sample,
            "result": cmd_result}
    if a[0] not in cmds or "--commit" not in a:
        print(__doc__, file=sys.stderr)
        return 2
    commit = a[a.index("--commit") + 1]
    seed_of(commit)
    c = _cr()
    try:
        authorized(c, commit)
        cmds[a[0]](c, commit)
    except V1Error as e:
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
