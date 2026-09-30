#!/usr/bin/env python3
"""S-Series V1.2.1 Phase-1 extraction-instrumentation validation: mechanical tooling (non-production). G-LOG-0066.

V1.2 revision 1. It supersedes `p3b_v1_2_instrument.py` (frozen at 4c1946181), `p3b_v1_1_instrument.py` (3ec22d228)
and `p3b_v1_instrument.py` (ec4b5912e), all kept unchanged as historical records. It implements pre-registration
v1.2.1 (`audit-p3b/20260925_2000_v1.2.1-instrument-validation-preregistration.md`), which repairs N1, F1 and N2 of
the V1.2 re-audit (G-LOG-0065).

It executes no agent. Sub-commands:
  selftest                        synthetic data only; reads no corpus (any time)
  validate  --run R               schema-only self-check of a run's own output, for the agent; reads no corpus
  segment   --commit C            v1/SEGMENT-MAP.json                                   (corpus via resolver)
  check     --commit C --pass 1|2 v1/CHECK-<pass>.json, v1/FAILURES-<run>.json (pass 1), v1/REPAIR-DIFF.json (pass 2)
  equalize  --commit C            v1/MATCH-LISTS.json, v1/SOURCE-KEY.json (runtime secret nonce; never committed before M2)
  ingest    --commit C --run R    v1/runs/<R>/TOOL-CALLS.jsonl + RUN-RECORD.json from the harness transcript
  freeze    --commit C --stage S  appends the stage's file hashes to v1/PROVENANCE.jsonl
  sample    --commit C            v1/SAMPLE-A02.json
  result    --commit C            v1/V1-RESULT.json; integrity is recomputed here over all eight runs

Every sub-command except selftest and validate refuses unless `pilot-s5-decomp/v1/V1.2.1-AUTHORIZATION.json` names a
40-hex commit at which this pre-registration, this tool and its evidence-chain dependencies are committed
byte-identical to the working files (X3, N2). That file is written only after a human authorizes execution. Corpus content is
read only through the seal-aware `discovery_resolver()`; checker reads are not agent reads.
"""
import datetime
import hashlib
import importlib.util
import json
import math
import os
import random
import re
import secrets
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))

PREREG = "audit-p3b/20260925_2000_v1.2.1-instrument-validation-preregistration.md"
V1_DIR = "pilot-s5-decomp/v1"
AUTH = "V1.2.1-AUTHORIZATION.json"
TOOL_REL = "scripts/p3b_v1_2_1_instrument.py"
DEPENDENCIES = ("scripts/p3b_read_source.py", "scripts/p3b_s5_common.py", "scripts/p3b_discovery_io.py")   # N2
PAGE_CHARS = 20000                  # the paged reader's page size (contract revision 3)
MAX_SEG = 6000                      # §5: maximum segment length before subdivision (ARBITRARY engineering value)
OPEN_TEXT = 80

LABEL_C = "knowledgeos-architecture-constitution-v01"
LABEL_S = "step-verify-programme"
FILES = (      # §2, unchanged from v1: (source_id, label, content_sha256, n_pages, chars)
    ("S1022", LABEL_C, "2ccee923f1350a7e199689d442514f5e69797ccd33bf142bd9036817dadae0e2", 1, 15541),
    ("S1021", LABEL_C, "afca77203bf2429dc8179db857bc13f25c6844c32988ff86065931c3133fabbf", 2, 20086),
    ("S1484", LABEL_C, "2e5ee4495c2f84c079d76242583d0153c719e9c79864f0f5c070721c9f1fecba", 3, 59434),
    ("S1413", LABEL_S, "05a3b0100444bb07c2392c5563b8e4ed8acf7f11f91ab16a5964ddcacabdf639", 2, 24219),
    ("S1521", LABEL_S, "25decfad7a09927f1a6a28f8dedd6f6422d4808c0db1e9b66dbdd0b6d0420bbe", 2, 30455),
    ("S1541", LABEL_S, "0695b63b877d1cab182c99c8d8d9f27af1398c871568c727b8392dda930a3d25", 9, 174052),
)
LABEL_OF = {f[0]: f[1] for f in FILES}
MODEL_IDS = {"opus": "claude-opus-5-5", "fable": "claude-fable-5-1", "haiku": "claude-haiku-4-5-20251001",
             "sonnet": "claude-sonnet-5"}
RUNS = {       # §3, unchanged from v1: role, Agent-tool alias, label scope
    "PX0106-U01": ("SEG", "opus", LABEL_C), "PX0106-U02": ("SEG", "opus", LABEL_S),
    "PX0106-U03": ("E1", "fable", LABEL_C), "PX0106-U04": ("E1", "fable", LABEL_S),
    "PX0106-U05": ("E2", "haiku", LABEL_C), "PX0106-U06": ("E2", "haiku", LABEL_S),
    "PX0106-A01": ("M1", "sonnet", None), "PX0106-A02": ("M2", "fable", None),
}
SOURCES = ("SEG", "E1", "E2")


def outputs_of(run):
    """Files an agent run may write (and must head with its canary)."""
    role = RUNS[run][0]
    if role in SOURCES:
        return [f"OUT-{run}.jsonl", f"OUT-{run}.repair.jsonl"]
    return ["CLUSTERS-A01.jsonl", "DECISIONS-A01.jsonl"] if role == "M1" else ["DECISIONS-A02.jsonl"]


def inputs_of(run):
    """Files an agent run may read besides its own outputs (§15 tool-call allowlist)."""
    role = RUNS[run][0]
    if role == "SEG":
        return ["SEGMENT-MAP.json", f"FAILURES-{run}.json"]
    if role in SOURCES:
        return [f"FAILURES-{run}.json"]
    return ["MATCH-LISTS.json"] if role == "M1" else ["MATCH-LISTS.json", "CLUSTERS-A01.jsonl", "SAMPLE-A02.json"]


TYPES = ("DEFINITION", "NOTATION", "CLAIM", "RULE", "FORMULA", "THEOREM-OR-RESULT", "ALGORITHM", "EXAMPLE",
         "HYPOTHESIS", "OPEN-QUESTION", "CORRECTION", "CONTRADICTION", "RELATION", "LINEAGE", "GOVERNANCE",
         "METHOD", "SCOPE")
STATUSES = ("ASSERTED", "EXAMPLE", "HYPOTHETICAL", "RETRACTED")
DECLINES = ("RESTATEMENT", "EXAMPLE-ONLY", "NON-MATERIAL", "OUT-OF-LABEL-SCOPE")
MATCH_STATUS = ("MATCH", "PARTIAL", "NONE")
HARMLESS_TOOLS = ("SubagentHandback", "ToolSearch", "TodoWrite")

# §17 engineering decision rules: every numeric value is ARBITRARY (engineering threshold, not scientific)
KAPPA_VALID, KAPPA_UNUSABLE = 0.60, 0.40
CAPTURE_VALID = 0.80
AGREED_MIN = 20                     # R1: minimum agreed-set size for capture to be computable (ARBITRARY)
COVERAGE_UNUSABLE = 0.95
QUOTE_MISS_UNUSABLE = 0.05
# §11 freeze stages, in order. Files marked "#" are recorded by hash only and not committed until "unseal".
STAGES = (
    ("authorization", [AUTH]),
    ("segment-map", ["SEGMENT-MAP.json"]),
    ("check-1", ["CHECK-1.json", "V1-DISPATCH.json"] + [f"FAILURES-{r}.json" for r in RUNS if RUNS[r][0] in SOURCES]
     + [f"OUT-{r}.jsonl" for r in RUNS if RUNS[r][0] in SOURCES]),
    ("check-2", ["CHECK-2.json", "REPAIR-DIFF.json"]),
    ("lists", ["MATCH-LISTS.json", "#SOURCE-KEY.json"]),
    ("m1", ["CLUSTERS-A01.jsonl", "#DECISIONS-A01.jsonl", "V1-DISPATCH.json"]),
    ("sample", ["SAMPLE-A02.json"]),
    ("m2", ["DECISIONS-A02.jsonl", "V1-DISPATCH.json"]),
    ("unseal", ["SOURCE-KEY.json", "DECISIONS-A01.jsonl", "V1-DISPATCH.json"]),
)
CAPTURE_CAUSES = ("SEG omission (instrument)", "matching or granularity effects", "M1 judgment bias",
                  "residual scope difference between SEG and the open-ended prompts")

WS = re.compile(r"\s+")
HEAD = re.compile(r"#{1,6}[ \t]+\S")


class V1Error(RuntimeError):
    pass


def sha(s):
    return hashlib.sha256(s.encode("utf-8")).hexdigest()


def norm(s):
    return WS.sub(" ", s).strip()


def utc():
    return datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%S.%fZ")


def ts(s):
    """Parse an ISO-8601 UTC timestamp (harness 'Z' with milliseconds, or ours with microseconds)."""
    if not s:
        return None
    m = re.fullmatch(r"(\d{4}-\d\d-\d\dT\d\d:\d\d:\d\d)(?:\.(\d+))?Z?", s)
    if not m:
        raise V1Error(f"unparseable timestamp {s!r}")
    base = datetime.datetime.strptime(m.group(1), "%Y-%m-%dT%H:%M:%S")
    return base + datetime.timedelta(microseconds=int((m.group(2) or "0")[:6].ljust(6, "0")))


def body(rows):
    """Drop the header line (the one carrying the canary) of an agent-authored JSONL file."""
    return rows[1:] if rows and "canary" in rows[0] else rows


def seed_of(commit):
    """§12: the sample seed is the first 8 hex digits of the pre-registration commit."""
    if not re.fullmatch(r"[0-9a-f]{7,40}", commit or ""):
        raise V1Error("a pre-registration commit hash is required")
    return int(commit[:8], 16)


def canary(commit, run):
    return "CANARY-" + sha(f"{commit}:{run}")[:12].upper()


# ---------------------------------------------------------------- §5 segmentation (unchanged from v1)
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
    merged = []
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


# ---------------------------------------------------------------- §7 schema, §9 quotes, §14 coverage
def quote_status(q, text, a=0, b=None):
    region = text[a:b if b is not None else len(text)]
    if q and q in region:
        return "EXACT"
    if norm(q) and norm(q) in norm(region):
        return "WHITESPACE"
    if q and (q in text or norm(q) in norm(text)):
        return "OUT-OF-RANGE"
    return "MISS"


def prop_schema_errors(p):
    return [k for k, ok in (("proposition_type", p.get("proposition_type") in TYPES),
                            ("status", p.get("status") in STATUSES),
                            ("register_decision", p.get("register_decision") in ("PROMOTE", "DECLINE")),
                            ("decline_reason", (p.get("register_decision") != "DECLINE")
                             or p.get("decline_reason") in DECLINES),
                            ("explicit", p.get("proposition_type") != "RELATION"
                             or isinstance(p.get("explicit"), bool)),
                            ("statement", bool(str(p.get("statement", "")).strip())),
                            ("quote", bool(str(p.get("quote", "")).strip())),
                            ("proposition_id", bool(str(p.get("proposition_id", "")).strip()))) if not ok]


def check_inventory(records, segmap, texts=None):
    """SEG output. Returns (result, failures). failures: [{key, proposition_id|None, class}] (key = segment_id).
    With texts=None (the agent's `validate`), quotes are not checked."""
    by_id = {s["segment_id"]: s for s in segmap}
    seen, fails, pids = {}, [], {}
    res = {"quotes": {}, "quote_lengths": [], "no_substantive": 0}
    for r in records:
        sid = r.get("segment_id")
        if sid not in by_id:
            fails.append({"key": sid, "proposition_id": None, "class": "UNKNOWN-SEGMENT"})
            continue
        if sid in seen:
            fails.append({"key": sid, "proposition_id": None, "class": "DUPLICATE-SEGMENT"})
            continue
        seen[sid] = r
        seg = by_id[sid]
        if r.get("result") == "NO-SUBSTANTIVE-PROPOSITION":
            res["no_substantive"] += 1
            if not str(r.get("reason", "")).strip() or r.get("propositions"):
                fails.append({"key": sid, "proposition_id": None, "class": "SCHEMA:record"})
            continue
        if r.get("result") != "PROPOSITIONS" or not r.get("propositions"):
            fails.append({"key": sid, "proposition_id": None, "class": "SCHEMA:record"})
            continue
        for p in r["propositions"]:
            pid = p.get("proposition_id")
            bad = prop_schema_errors(p)
            if pid in pids:
                bad.append("duplicate-proposition_id")
            pids[pid] = sid
            for k in bad:
                fails.append({"key": sid, "proposition_id": pid, "class": f"SCHEMA:{k}"})
            if texts is not None:
                st = quote_status(p.get("quote", ""), texts[seg["source_id"]], seg["char_start"], seg["char_end"])
                res["quotes"][st] = res["quotes"].get(st, 0) + 1
                res["quote_lengths"].append(len(p.get("quote", "")))
                if st in ("MISS", "OUT-OF-RANGE"):
                    fails.append({"key": sid, "proposition_id": pid,
                                  "class": "QUOTE-MISS" if st == "MISS" else "QUOTE-OUT-OF-SEGMENT"})
    for s in segmap:
        if s["segment_id"] not in seen:
            fails.append({"key": s["segment_id"], "proposition_id": None, "class": "MISSING-SEGMENT"})
    cov = {}
    for s in segmap:
        c = cov.setdefault(s["source_id"], [0, 0])
        c[1] += 1
        c[0] += s["segment_id"] in seen
    res["coverage"] = {k: v[0] / v[1] for k, v in sorted(cov.items())}
    return res, fails


def check_open(records, allowed_sids, texts=None):
    res, fails, ids = {"quotes": {}, "quote_lengths": []}, [], set()
    for r in records:
        iid = r.get("item_id")
        bad = [k for k, ok in (("item_id", bool(str(iid or "").strip()) and iid not in ids),
                               ("source_id", r.get("source_id") in allowed_sids),
                               ("statement", bool(str(r.get("statement", "")).strip())),
                               ("quote", bool(str(r.get("quote", "")).strip()))) if not ok]
        ids.add(iid)
        for k in bad:
            fails.append({"key": iid, "proposition_id": None, "class": f"SCHEMA:{k}"})
        if texts is not None and r.get("source_id") in allowed_sids:
            st = quote_status(r.get("quote", ""), texts[r["source_id"]])
            res["quotes"][st] = res["quotes"].get(st, 0) + 1
            res["quote_lengths"].append(len(r.get("quote", "")))
            if st == "MISS":
                fails.append({"key": iid, "proposition_id": None, "class": "QUOTE-MISS"})
    return res, fails


def miss_rate(q):
    n = sum(q.values())
    return (q.get("MISS", 0) + q.get("OUT-OF-RANGE", 0)) / n if n else 0.0


def length_summary(lengths):
    """m5 diagnostic only (no threshold): quote-length distribution."""
    if not lengths:
        return None
    s = sorted(lengths)
    return {"n": len(s), "min": s[0], "p10": s[len(s) // 10], "median": s[len(s) // 2], "max": s[-1]}


# ---------------------------------------------------------------- §9 bounded repair (R4)
def apply_repair(records, repairs, failures, key):
    """Accept repair records only for flagged keys; never let a repair change an unflagged record or proposition.
    Returns (final_records, diff). The original records are never modified."""
    flagged = {}
    for f in failures:
        flagged.setdefault(f["key"], set()).add(f["proposition_id"])
    orig = {r.get(key): r for r in records}
    counts = {}
    for r in repairs:
        counts[r.get(key)] = counts.get(r.get(key), 0) + 1
    accepted, diff = {}, []
    for r in repairs:
        k = r.get(key)
        if k not in flagged:
            diff.append({"key": k, "decision": "REJECTED", "reason": "UNFLAGGED-KEY"})
            continue
        if counts[k] > 1:
            diff.append({"key": k, "decision": "REJECTED", "reason": "DUPLICATE-REPAIR"})
            continue
        record_level = None in flagged[k] or k not in orig
        if key == "segment_id" and not record_level and not r.get("withdrawn"):
            o = {p.get("proposition_id"): p for p in orig[k].get("propositions") or []}
            n = {p.get("proposition_id"): p for p in r.get("propositions") or []}
            fl = flagged[k]
            if any(pid not in o for pid in n):
                diff.append({"key": k, "decision": "REJECTED", "reason": "ADDED-PROPOSITION"})
                continue
            if any(pid not in fl and n.get(pid) != p for pid, p in o.items()):
                diff.append({"key": k, "decision": "REJECTED", "reason": "CHANGED-UNFLAGGED"})
                continue
            if r.get("result") != orig[k].get("result") and \
                    not (not n and set(o) <= fl and r.get("result") == "NO-SUBSTANTIVE-PROPOSITION"):
                diff.append({"key": k, "decision": "REJECTED", "reason": "CHANGED-RESULT"})
                continue
        elif key == "segment_id" and not record_level and r.get("withdrawn"):
            diff.append({"key": k, "decision": "REJECTED", "reason": "SEGMENT-WITHDRAWAL-NOT-FLAGGED"})
            continue
        accepted[k] = r
        diff.append({"key": k, "decision": "ACCEPTED", "reason": "WITHDRAWN" if r.get("withdrawn") else "REPLACED"})
    out = [accepted.pop(r.get(key)) if r.get(key) in accepted else r for r in records]
    out += [r for k, r in accepted.items() if k not in orig]
    return [r for r in out if not r.get("withdrawn")], diff


# ---------------------------------------------------------------- §11 blinded lists (R5: runtime nonce)
def flatten_inventory(records):
    for r in records:
        for p in r.get("propositions") or []:
            yield {"orig_id": p["proposition_id"], "source_id": r["source_id"], "statement": p["statement"],
                   "quote": p["quote"]}


def equalize(outputs, nonce):
    """The letter permutation and item ids depend on a secret nonce generated at run time (not derivable from the
    commit or the code); deterministic once the nonce exists."""
    if not re.fullmatch(r"[0-9a-f]{32,}", nonce or ""):
        raise V1Error("a runtime nonce is required")
    rng = random.Random(int(sha(nonce)[:16], 16))
    letters = ["X", "Y", "Z"]
    rng.shuffle(letters)
    key = {"nonce": nonce, "letter_of_source": dict(zip(SOURCES, letters)), "items": {}}
    lists = {}
    for src in SOURCES:
        L = key["letter_of_source"][src]
        items = []
        for r in outputs[src]:
            iid = "I" + sha(f"{nonce}:{src}:{r['orig_id']}")[:10]
            key["items"][iid] = {"source": src, "orig_id": r["orig_id"]}
            items.append({"item_id": iid, "list": L, "source_id": r["source_id"], "statement": r["statement"],
                          "quote": r["quote"]})
        lists[L] = sorted(items, key=lambda x: (x["source_id"], x["item_id"]))
    return {k: lists[k] for k in sorted(lists)}, key


# ---------------------------------------------------------------- §12 sample (unchanged)
def draw_sample(clusters, commit, frac=0.30, floor=5):
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


# ---------------------------------------------------------------- §13 κ (m1–m3)
def _kappa(pairs):
    """None (NOT-COMPUTABLE) for an empty set or p_e = 1 (m3)."""
    n = len(pairs)
    if n == 0:
        return None
    po = sum(a == b for a, b in pairs) / n
    cats = {x for p in pairs for x in p}
    pe = sum((sum(a == c for a, _ in pairs) / n) * (sum(b == c for _, b in pairs) / n) for c in cats)
    return None if pe >= 1 else (po - pe) / (1 - pe)


def kappas(dec1, dec2, sample, clusters):
    """Expected units = every sampled cluster × each of the two other lists. If M1 or M2 lacks any expected unit,
    κ is NOT-COMPUTABLE (m2). Primary κ_target unchanged; κ per ordered list pair is a diagnostic (m1)."""
    list_of = {c["cluster_id"]: c["list"] for c in clusters}
    src_of = {c["cluster_id"]: c.get("source_id") for c in clusters}
    letters = sorted({c["list"] for c in clusters})
    units = sorted((cid, o) for cid in sample for o in letters if cid in list_of and o != list_of[cid])
    def dups(dec):
        seen, out = set(), set()
        for d in dec:
            k2 = (d.get("cluster_id"), d.get("other_list"))
            (out if k2 in seen else seen).add(k2)
        return out
    i1 = {(d["cluster_id"], d["other_list"]): d for d in dec1}
    i2 = {(d["cluster_id"], d["other_list"]): d for d in dec2}
    miss1 = [u for u in units if u not in i1]
    miss2 = [u for u in units if u not in i2]

    def bad(u, d):          # off-scale status, or a MATCH/PARTIAL target outside the named list or source (minor m1)
        if d.get("status") not in MATCH_STATUS:
            return True
        t = d.get("target_cluster")
        return d["status"] != "NONE" and (list_of.get(t) != u[1] or src_of.get(t) != src_of.get(u[0]))
    dup = dups(dec1) | dups(dec2)
    off = [u for u in units for i in (i1, i2) if u in i and bad(u, i[u])] + [u for u in units if u in dup]

    def lab(d, with_target):
        return "NONE" if d["status"] == "NONE" else (f"{d['status']}:{d.get('target_cluster')}" if with_target
                                                     else d["status"])
    out = {"units_expected": len(units), "missing_in_m1": len(miss1), "missing_in_m2": len(miss2),
           "off_scale": len(off), "kappa_target": None, "kappa_status": None, "raw_agreement_target": None,
           "kappa_target_by_direction": {}, "not_computable_reason": None}
    if not units:
        out["not_computable_reason"] = "no sampled units"
        return out
    if miss1 or miss2 or off:
        out["not_computable_reason"] = "incomplete or off-scale decisions on the sample"
        return out
    pt = [(lab(i1[u], True), lab(i2[u], True)) for u in units]
    ps = [(lab(i1[u], False), lab(i2[u], False)) for u in units]
    out.update({"kappa_target": _kappa(pt), "kappa_status": _kappa(ps),
                "raw_agreement_target": sum(a == b for a, b in pt) / len(pt)})
    for a in letters:
        for b in letters:
            sub = [(lab(i1[u], True), lab(i2[u], True)) for u in units if list_of[u[0]] == a and u[1] == b]
            if sub:
                out["kappa_target_by_direction"][f"{a}->{b}"] = _kappa(sub)
    if out["kappa_target"] is None:
        out["not_computable_reason"] = "degenerate label distribution (p_e = 1)"
    return out


# ---------------------------------------------------------------- §16 capture (R1)
def capture(clusters, dec1, key_letters, agreed_min=AGREED_MIN):
    """Relative recall of SEG against the E1∩E2-agreed set as judged by M1. NOT a capture–recapture estimate and
    NOT evidence of semantic completeness. NOT-COMPUTABLE (None) below `agreed_min` agreed clusters."""
    L = key_letters
    by = {(d["cluster_id"], d["other_list"]): d for d in dec1}
    agreed = [c["cluster_id"] for c in clusters if c["list"] == L["E1"]
              and by.get((c["cluster_id"], L["E2"]), {}).get("status") == "MATCH"]
    seg = [by.get((cid, L["SEG"]), {}).get("status") for cid in agreed]
    out = {"agreed": len(agreed), "agreed_min": agreed_min, "strict": None, "lenient": None,
           "strict_observed": (seg.count("MATCH") / len(agreed)) if agreed else None,
           "lenient_observed": ((seg.count("MATCH") + seg.count("PARTIAL")) / len(agreed)) if agreed else None}
    if len(agreed) >= agreed_min:
        out["strict"], out["lenient"] = out["strict_observed"], out["lenient_observed"]
    return out


def m1_completeness(lists, clusters, dec1):
    """N1: M1's output must be complete before capture (and κ) are computed.
    lists: MATCH-LISTS {letter: [items]}; clusters: CLUSTERS-A01 body; dec1: DECISIONS-A01 body. Returns violations."""
    v = []
    items = {i["item_id"]: (L, i["source_id"]) for L, its in lists.items() for i in its}
    letters = sorted(lists)
    seen, cl_of = {}, {}
    for c in clusters:
        cid, mem = c.get("cluster_id"), c.get("members")
        if not cid or cid in cl_of:
            v.append(f"cluster id missing or duplicated: {cid!r}")
            continue
        cl_of[cid] = c
        if c.get("list") not in letters:
            v.append(f"{cid}: unknown list {c.get('list')!r}")
        if not isinstance(mem, list) or not mem:
            v.append(f"{cid}: no members")
            continue
        for m in mem:
            if m not in items:
                v.append(f"{cid}: unknown member {m!r}")
            elif m in seen:
                v.append(f"{cid}: member {m!r} also in {seen[m]}")
            else:
                seen[m] = cid
                if items[m][0] != c.get("list"):
                    v.append(f"{cid}: member {m!r} belongs to list {items[m][0]}, cluster says {c.get('list')!r}")
                if items[m][1] != c.get("source_id"):
                    v.append(f"{cid}: member {m!r} has source {items[m][1]}, cluster says {c.get('source_id')!r}")
    missing = sorted(set(items) - set(seen))
    if missing:
        v.append(f"{len(missing)} match-list item(s) in no cluster (e.g. {missing[0]})")
    counts = {}
    for d in dec1:
        k = (d.get("cluster_id"), d.get("other_list"))
        counts[k] = counts.get(k, 0) + 1
        cid, other = k
        if cid not in cl_of:
            v.append(f"decision for unknown cluster {cid!r}")
            continue
        if other not in letters or other == cl_of[cid].get("list"):
            v.append(f"{cid}: decision against invalid list {other!r}")
            continue
        st, t = d.get("status"), d.get("target_cluster")
        if st not in MATCH_STATUS:
            v.append(f"{cid}->{other}: off-scale status {st!r}")
        elif st == "NONE":
            if t is not None:
                v.append(f"{cid}->{other}: NONE with a target")
        elif t not in cl_of or cl_of[t].get("list") != other or cl_of[t].get("source_id") != cl_of[cid].get("source_id"):
            v.append(f"{cid}->{other}: invalid target {t!r}")
    for cid, c in cl_of.items():
        for other in letters:
            if other == c.get("list"):
                continue
            n = counts.get((cid, other), 0)
            if n != 1:
                v.append(f"{cid}->{other}: {n} decisions (exactly 1 required)")
    return v


def capture_gate(cap, status):
    """X6: capture is NOT-COMPUTABLE whenever any extractor run did not complete (no capture from partial output)."""
    cap = dict(cap)
    incomplete = [r for r in RUNS if RUNS[r][0] in SOURCES and status.get(r) != "COMPLETED"]
    reason = f"agreed set {cap['agreed']} < {AGREED_MIN}" if cap["lenient"] is None else None
    if incomplete:
        cap["strict"] = cap["lenient"] = None
        reason = "extractor output incomplete: " + ", ".join(incomplete)
    return cap, reason


def gated_metrics(lists, clusters, dec1, dec2, sample, key_letters, status):
    """κ and capture as used by the decision. X6: no capture from incomplete extractor output. N1: no capture and no κ
    from incomplete or inconsistent M1 output (both are computed from M1's clusters and decisions)."""
    k = kappas(dec1, dec2, sample, clusters)
    cap, reason = capture_gate(capture(clusters, dec1, key_letters), status)
    m1v = m1_completeness(lists, clusters, dec1)
    if m1v:
        cap = dict(cap, strict=None, lenient=None)
        reason = f"M1 output incomplete ({len(m1v)} violation(s): {m1v[0]})"
        k = dict(k, kappa_target=None, kappa_status=None,
                 not_computable_reason=f"M1 output incomplete ({len(m1v)} violation(s))")
    return k, cap, reason, m1v


def chapman(n1, n2, m):
    """Diagnostic only; never a gate. Valid only under source independence, which is not established."""
    return (n1 + 1) * (n2 + 1) / (m + 1) - 1


# ---------------------------------------------------------------- §15 tool-call audit (R2)
SAFE_PATH = r"[A-Za-z0-9_./@-]*"          # X2: no shell metacharacters ($, `, {, }, <, >, |, &, ;, *, quotes, spaces)
READER = rf"python3 (?:{SAFE_PATH}/)?scripts/p3b_read_source\.py"
VALIDATOR = rf"python3 (?:{SAFE_PATH}/)?scripts/p3b_v1_2_1_instrument\.py"


def bash_allowed(cmd, run):
    """Every part of the command (split on newlines, '&&', ';') must be `cd <dir>`, the paged reader for this run and
    an authorized file, or the schema-only validator for this run. Pipes and redirections are not allowed."""
    role, _, label = RUNS[run]
    for part in re.split(r"\n|&&|;", cmd):
        p = part.strip()
        if not p:
            continue
        if re.fullmatch(r"cd [A-Za-z0-9_./@-]+", p):
            continue
        m = re.fullmatch(READER + rf" --run {run} --batch PX0106 --label ([A-Za-z0-9._-]+) --step 10 "
                         r"--page [1-9]\d{0,2}(?: --session [1-9]\d?)? (S\d{4})(?: 2>&1)?", p)
        if m and m.group(2) in LABEL_OF and m.group(1) == LABEL_OF[m.group(2)] and \
                (label is None or LABEL_OF[m.group(2)] == label):
            continue
        if re.fullmatch(VALIDATOR + rf" validate --run {run}(?: 2>&1)?", p):
            continue
        return False
    return True


def tool_results_dir(transcript_path):
    """The session's harness tool-results directory: <session>/subagents/agent-X.jsonl -> <session>/tool-results/."""
    return os.path.join(os.path.dirname(os.path.dirname(os.path.abspath(transcript_path))), "tool-results")


def parse_transcript(path):
    """Harness transcript of one agent (JSONL). Returns models, tool calls (inputs only; Write content hashed), the
    agent's own persisted Bash outputs (X4), identity fields and the first prompt's text (X5). A line that is not
    valid JSON is recorded as a parse error (truncation), never skipped silently."""
    with open(path, "rb") as f:
        raw = f.read()
    models, calls, persisted, cwd, times = set(), [], set(), None, []
    agent_ids, session_ids, errors, first_prompt, names = set(), set(), [], None, {}
    tr_dir = tool_results_dir(path)
    for n, line in enumerate(raw.decode("utf-8", errors="replace").splitlines(), 1):
        if not line.strip():
            continue
        try:
            r = json.loads(line)
        except ValueError:
            errors.append(n)
            continue
        cwd = cwd or r.get("cwd")
        if r.get("agentId"):
            agent_ids.add(r["agentId"])
        if r.get("sessionId"):
            session_ids.add(r["sessionId"])
        if r.get("timestamp"):
            times.append(r["timestamp"])
        m = r.get("message") if isinstance(r.get("message"), dict) else {}
        if m.get("model") and m["model"] != "<synthetic>":
            models.add(m["model"])
        content = m.get("content")
        if first_prompt is None and m.get("role") == "user":
            first_prompt = content if isinstance(content, str) else \
                " ".join(x.get("text", "") for x in content or [] if isinstance(x, dict) and x.get("type") == "text")
        for b in content if isinstance(content, list) else []:
            if not isinstance(b, dict):
                continue
            if b.get("type") == "tool_use":
                names[b.get("id")] = b.get("name")
                inp = dict(b.get("input") or {})
                for big in ("content", "new_string", "old_string"):
                    if big in inp:
                        inp[big + "_sha256"] = sha(str(inp.pop(big)))
                calls.append({"ts": r.get("timestamp"), "id": b.get("id"), "name": b.get("name"), "input": inp})
            elif b.get("type") == "tool_result" and names.get(b.get("tool_use_id")) == "Bash":
                cont = b.get("content")
                txt = cont if isinstance(cont, str) else \
                    "".join(x.get("text", "") for x in cont or [] if isinstance(x, dict))
                if (txt or "").lstrip().startswith("<persisted-output>"):
                    for pth in re.findall(r"Full output saved to: (\S+)", txt):
                        if os.path.normpath(pth).startswith(tr_dir + os.sep):
                            persisted.add(os.path.normpath(pth))
    return {"transcript_sha256": hashlib.sha256(raw).hexdigest(), "api_models": sorted(models), "cwd": cwd,
            "tool_calls": calls, "persisted_outputs": sorted(persisted), "agent_ids": sorted(agent_ids),
            "session_ids": sorted(session_ids), "parse_errors": errors, "first_prompt": first_prompt or "",
            "first_ts": min(times) if times else None, "last_ts": max(times) if times else None}


def linkage_breaches(run, rec, agent_id, commit):
    """X5: the transcript must be this run's: one agentId equal to the dispatch record, a single session, no parse
    errors (truncation), and a first prompt naming the run id and carrying the run's canary."""
    br = []
    if rec.get("parse_errors"):
        br.append((run, f"transcript has unparseable lines {rec['parse_errors'][:5]} (truncated or corrupt)"))
    if rec.get("agent_ids") != [agent_id]:
        br.append((run, f"transcript agentId {rec.get('agent_ids')} != dispatch agent_id {agent_id!r}"))
    if len(rec.get("session_ids") or []) != 1:
        br.append((run, "transcript does not carry exactly one sessionId"))
    fp = rec.get("first_prompt") or ""
    if run not in fp or canary(commit, run) not in fp:
        br.append((run, "first prompt does not name the run id and its canary"))
    return br


def audit_calls(run, rec, v1_abs):
    """Tool-call allowlist (§15). Returns breaches; any breach makes the run RUN-INVALID."""
    cwd = rec.get("cwd") or ""
    ab = lambda p: os.path.normpath(p if os.path.isabs(p) else os.path.join(cwd, p))
    reads = {ab(os.path.join(v1_abs, x)) for x in inputs_of(run) + outputs_of(run)} | \
        {ab(p) for p in rec["persisted_outputs"]}
    writes = {ab(os.path.join(v1_abs, x)) for x in outputs_of(run)}
    br = []
    for c in rec["tool_calls"]:
        n, i = c["name"], c["input"]
        if n == "Bash":
            if not bash_allowed(str(i.get("command", "")), run):
                br.append((run, c["id"], "Bash command outside the allowlist"))
        elif n == "Read":
            if ab(str(i.get("file_path", ""))) not in reads:
                br.append((run, c["id"], "Read outside the allowlist"))
        elif n in ("Write", "Edit"):
            if ab(str(i.get("file_path", ""))) not in writes:
                br.append((run, c["id"], f"{n} outside the allowlist"))
        elif n not in HARMLESS_TOOLS:
            br.append((run, c["id"], f"tool {n} not allowed"))
    return br


# ---------------------------------------------------------------- §17 decision (R1, R3)
def decide(m):
    """Returns (outcome, qualifier, reasons). outcome ∈ {INSTRUMENTS-VALID, NEEDS-REVISION, NOT-USABLE}; qualifier
    (NEEDS-REVISION only) ∈ {INSTRUMENT, INSTRUMENT-UNDETERMINED, RUN-INVALID}.
    m: run_invalid [reasons]; seg_exhausted [runs]; other_failed [runs]; coverage_completed {run: min coverage} for
    completed SEG runs; coverage_all_one; seg_quote_miss {completed SEG run: rate}; ref_quote_miss {completed E run:
    rate}; schema_ok (SEG runs only); ref_schema {completed E run: violations}; kappa; kappa_reason; capture;
    capture_reason; agreed.
    F1: E1/E2 schema violations, like their quote misses, are reference quality: they block VALID as
    INSTRUMENT-UNDETERMINED and never produce NOT-USABLE or INSTRUMENT.
    X1: E1/E2 (reference-extractor) quote misses block VALID as INSTRUMENT-UNDETERMINED and never produce NOT-USABLE
    or INSTRUMENT. SEG quote gates are unchanged."""
    if m["run_invalid"]:
        return "NEEDS-REVISION", "RUN-INVALID", ["protocol-integrity breach; NOT evidence about the instrument"] + \
            list(m["run_invalid"])
    k, cap = m["kappa"], m["capture"]
    seg_q, ref_q = m["seg_quote_miss"], {r: v for r, v in m["ref_quote_miss"].items() if v > 0}
    ref_s = {r: v for r, v in m.get("ref_schema", {}).items() if v > 0}
    r = [f"{run}: segment coverage {v:.3f} < 0.95 after repair" for run, v in sorted(m["coverage_completed"].items())
         if v < COVERAGE_UNUSABLE]
    r += [f"{run}: SEG quote miss rate {v:.3f} > 0.05 after repair" for run, v in sorted(seg_q.items())
          if v > QUOTE_MISS_UNUSABLE]
    if k is not None and k < KAPPA_UNUSABLE:
        r.append(f"kappa_target {k:.3f} < 0.40")
    if r:
        return "NOT-USABLE", None, r
    if not m["seg_exhausted"] and not m["other_failed"] and m["schema_ok"] and m["coverage_all_one"] and \
            not any(v > 0 for v in seg_q.values()) and not ref_q and not ref_s and k is not None and k >= KAPPA_VALID and \
            cap is not None and cap >= CAPTURE_VALID:
        return "INSTRUMENTS-VALID", None, ["all hard conditions and engineering gates met"]
    inst = [f"{run}: SEG could not complete (context exhausted)" for run in m["seg_exhausted"]]
    inst += ["SEG schema violations remain"] * (not m["schema_ok"])
    inst += ["segment coverage < 1.0"] * (not m["coverage_all_one"])
    inst += ["SEG quote misses > 0 after repair"] * any(v > 0 for v in seg_q.values())
    if k is not None and k < KAPPA_VALID:
        inst.append(f"kappa_target {k:.3f} < 0.60")
    if cap is not None and cap < CAPTURE_VALID:
        inst.append(f"capture_lenient {cap:.3f} < 0.80; possible causes: " + "; ".join(CAPTURE_CAUSES))
    und = [f"{run}: run did not complete (not an instrument property)" for run in m["other_failed"]]
    und += [f"{run}: reference-extractor quote misses {v:.3f} after repair (reference quality, X1)"
            for run, v in sorted(ref_q.items())]
    und += [f"{run}: {v} reference-extractor schema violation(s) after repair (reference quality, F1)"
            for run, v in sorted(ref_s.items())]
    if k is None:
        und.append(f"kappa NOT-COMPUTABLE ({m.get('kappa_reason')})")
    if cap is None:
        und.append(f"capture NOT-COMPUTABLE ({m.get('capture_reason')})")
    if inst:
        return "NEEDS-REVISION", "INSTRUMENT", inst + und
    return "NEEDS-REVISION", "INSTRUMENT-UNDETERMINED", und


def order_breaches(prov, records, v1_abs=""):
    """§15 freeze ordering, from PROVENANCE.jsonl stage times and harness transcript times."""
    st = {p["stage"]: p for p in prov}
    br = []
    names = [s for s, _ in STAGES]
    if [s for s in names if s in st] != names:
        br.append(("provenance", "missing freeze stage(s): " + ", ".join(s for s in names if s not in st)))
    times = [ts(st[s]["utc"]) for s in names if s in st]
    if times != sorted(times):
        br.append(("provenance", "freeze stages out of order"))

    def t(s):
        return ts(st[s]["utc"]) if s in st else None
    start_after = {"SEG": "segment-map", "E1": "segment-map", "E2": "segment-map", "M1": "lists", "M2": "sample"}
    end_before = {"SEG": "check-2", "E1": "check-2", "E2": "check-2", "M1": "m1", "M2": "m2"}
    for run, rec in records.items():
        role = RUNS[run][0]
        first, last = ts(rec.get("first_ts")), ts(rec.get("last_ts"))
        if t(start_after[role]) and first and first < t(start_after[role]):
            br.append((run, f"started before freeze '{start_after[role]}'"))
        if t(end_before[role]) and last and last > t(end_before[role]):
            br.append((run, f"activity after freeze '{end_before[role]}'"))
        if role in SOURCES and t("check-1"):
            rp = f"OUT-{run}.repair.jsonl"
            for c in rec.get("tool_calls", []):
                if c["name"] in ("Write", "Edit") and str(c["input"].get("file_path", "")).endswith(rp) \
                        and ts(c["ts"]) < t("check-1"):
                    br.append((run, "repair file written before the failure list was frozen"))
    if t("unseal") and t("m2") and t("unseal") < t("m2"):
        br.append(("provenance", "unsealed before M2 froze"))
    for f in ("SOURCE-KEY.json", "DECISIONS-A01.jsonl"):
        early = [p["files"].get("#" + f) for p in prov if "#" + f in p["files"]]
        late = [p["files"].get(f) for p in prov if p["stage"] == "unseal"]
        if early and late and early[0] != late[0]:
            br.append(("provenance", f"{f} changed between sealing and unsealing"))
    return br


# ---------------------------------------------------------------- execution guard, I/O and CLI
def _cr():
    spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
    c = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(c)
    return c


def _p(c, name):
    return os.path.join(c.CR, V1_DIR, name)


def _jl(path):
    if not os.path.exists(path):
        return []
    with open(path, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


def _dump(c, name, obj):
    os.makedirs(os.path.dirname(_p(c, name)), exist_ok=True)
    with open(_p(c, name), "w", encoding="utf-8") as f:
        json.dump(obj, f, indent=1, sort_keys=True, ensure_ascii=False)
        f.write("\n")


def _load(c, name):
    with open(_p(c, name), encoding="utf-8") as f:
        return json.load(f)


def _fsha(path):
    with open(path, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def git_show_bytes(c, commit, relpath):
    import subprocess
    cr_rel = os.path.relpath(c.CR, c.REPO_ROOT)
    p = subprocess.run(["git", "-C", c.REPO_ROOT, "show", f"{commit}:{cr_rel}/{relpath}"], capture_output=True)
    if p.returncode != 0:
        raise V1Error(f"{relpath} is not committed at {commit}")
    return p.stdout


def authorized(c, commit, show=None):
    """X3 + N2: the authorization must name a full 40-hex commit; at that commit the pre-registration, this tool and the
    evidence-chain dependencies (reader, S5 common library, resolver) must be committed byte-identical to the working
    files, and the pre-registration sha256 must equal the authorization's. Any difference refuses."""
    show = show or (lambda cm, rel: git_show_bytes(c, cm, rel))
    if not re.fullmatch(r"[0-9a-f]{40}", commit or ""):
        raise V1Error("--commit must be the full 40-hex pre-registration commit")
    if not os.path.exists(_p(c, AUTH)):
        raise V1Error(f"V1.2.1 execution is not authorized (no {AUTH})")
    a = _load(c, AUTH)
    if a.get("prereg_commit") != commit:
        raise V1Error("V1.2.1 authorization names a different commit")
    for rel in (PREREG, TOOL_REL) + DEPENDENCIES:
        with open(os.path.join(c.CR, rel), "rb") as f:
            work = hashlib.sha256(f.read()).hexdigest()
        if hashlib.sha256(show(commit, rel)).hexdigest() != work:
            raise V1Error(f"{rel} differs from its committed version at {commit}")
    if a.get("prereg_sha256") != c.sha256_file(PREREG):
        raise V1Error("V1.2.1 authorization names a different pre-registration hash")
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


def output(c, run):
    rows = _jl(_p(c, f"OUT-{run}.jsonl"))
    return (rows[0], rows[1:]) if rows else ({}, [])


def dispatch(c):
    """Orchestrator-written v1/V1-DISPATCH.json: {run: {agent_id, alias, transcript, status: COMPLETED|FAILED,
    failure_reason: null|CONTEXT-EXHAUSTED|INFRASTRUCTURE|OTHER, failure_evidence}}."""
    return _load(c, "V1-DISPATCH.json") if os.path.exists(_p(c, "V1-DISPATCH.json")) else {}


def access(c, texts):
    """§15 read discipline for all eight runs (read logs written by the reader itself)."""
    br = []
    for run, (role, _, label) in RUNS.items():
        allowed = {f[0] for f in FILES if label is None or f[1] == label}
        pages = {}
        for r in _jl(os.path.join(c.CR, "pilot-s5-decomp", run, "READ-LOG.jsonl")):
            if r.get("refused"):
                br.append((run, "refused read"))
            if any(s not in allowed for s in r.get("source_ids", [])):
                br.append((run, "read outside the authorized files"))
            pg = r.get("page") or {}
            if pg:
                a, b = pg["char_start"], pg["char_end"]
                if pg["source_id"] not in texts or sha(texts[pg["source_id"]][a:b]) != pg["page_sha256"]:
                    br.append((run, "page hash mismatch"))
                pages.setdefault(pg["source_id"], set()).add(pg["page"])
            elif r.get("source_ids"):
                br.append((run, "non-paged read"))
        if role in SOURCES:                      # extractors: every page of every file of their label
            for sid, lab, _, n, _ in FILES:
                if lab == label and pages.get(sid, set()) != set(range(1, n + 1)):
                    br.append((run, f"incomplete page proof {sid}"))
    return br


def canaries(c, commit):
    """Every agent-authored V1 file starts with its author's canary; no agent-authored file contains another run's."""
    br = []
    for run in RUNS:
        for name in outputs_of(run):
            if not os.path.exists(_p(c, name)):
                continue
            with open(_p(c, name), encoding="utf-8") as f:
                txt = f.read()
            first = txt.splitlines()[0] if txt.strip() else "{}"
            try:
                head = json.loads(first)
            except ValueError:
                head = {}
            if head.get("canary") != canary(commit, run):
                br.append((run, f"{name}: own canary missing"))
            for other in RUNS:
                if other != run and canary(commit, other) in txt:
                    br.append((run, f"{name}: canary of {other} present (leakage)"))
    return br


def provenance_breaches(c, prov):
    """X7: every staged file equals its latest frozen hash; every dispatch entry equals its first frozen hash."""
    br, latest, first_entry = [], {}, {}
    for p in prov:
        for name, h in p["files"].items():
            latest[name.lstrip("#")] = (p["stage"], h)
        for run, h in (p.get("dispatch_entries") or {}).items():
            first_entry.setdefault(run, (p["stage"], h))
    for name, (stage, h) in sorted(latest.items()):
        cur = _fsha(_p(c, name)) if os.path.exists(_p(c, name)) else None
        if cur != h:
            br.append(("provenance", f"{name} differs from its '{stage}' freeze hash"))
    disp = dispatch(c)
    for run, (stage, h) in sorted(first_entry.items()):
        if sha(json.dumps(disp.get(run), sort_keys=True)) != h:
            br.append((run, f"dispatch entry changed after freeze '{stage}'"))
    return br


def cmd_segment(c, commit, a):
    texts = texts_of(c)
    files = {}
    for sid, label, *_ in FILES:
        m = segment_map(sid, texts[sid])
        if not tiles(m, len(texts[sid])):
            raise V1Error(f"{sid}: segments do not tile")
        files[sid] = {"label": label, "n_segments": len(m), "segments": m}
    _dump(c, "SEGMENT-MAP.json", {"prereg_commit": commit, "max_seg": MAX_SEG, "files": files})


def _segmap(c):
    return [s for f in _load(c, "SEGMENT-MAP.json")["files"].values() for s in f["segments"]]


def run_checks(c, texts, final):
    """Per extractor run: results on the original output (pass 1) or on the bounded-repaired output (pass 2)."""
    segmap, res, fails_all, diffs = _segmap(c), {}, {}, {}
    for run, (role, _, label) in RUNS.items():
        if role not in SOURCES:
            continue
        sids = {f[0] for f in FILES if f[1] == label}
        head, recs = output(c, run)
        key = "segment_id" if role == "SEG" else "item_id"
        if final and os.path.exists(_p(c, f"FAILURES-{run}.json")):
            fl = _load(c, f"FAILURES-{run}.json")
            if fl["original_sha256"] != (_fsha(_p(c, f"OUT-{run}.jsonl")) if os.path.exists(_p(c, f"OUT-{run}.jsonl"))
                                         else None):
                diffs[run] = [{"key": None, "decision": "REJECTED", "reason": "ORIGINAL-OUTPUT-MODIFIED"}]
            else:
                recs, diffs[run] = apply_repair(recs, body(_jl(_p(c, f"OUT-{run}.repair.jsonl"))), fl["failures"],
                                                key)
        if role == "SEG":
            r, fails = check_inventory(recs, [s for s in segmap if s["source_id"] in sids], texts)
        else:
            r, fails = check_open(recs, sids, texts)
        r.update({"quote_miss_rate": miss_rate(r["quotes"]), "quote_length": length_summary(r.pop("quote_lengths")),
                  "failures": len(fails), "model_self_report": head.get("model_self_report")})
        res[run], fails_all[run] = r, fails
    return res, fails_all, diffs


def cmd_check(c, commit, a):
    npass = a[a.index("--pass") + 1] if "--pass" in a else ""
    if npass not in ("1", "2"):
        raise V1Error("--pass 1|2 is required")
    texts = texts_of(c)
    res, fails, diffs = run_checks(c, texts, npass == "2")
    _dump(c, f"CHECK-{npass}.json", {"prereg_commit": commit, "pass": int(npass), "runs": res})
    if npass == "1":
        for run, fl in fails.items():
            p = _p(c, f"OUT-{run}.jsonl")
            _dump(c, f"FAILURES-{run}.json", {"run": run, "original_sha256": _fsha(p) if os.path.exists(p) else None,
                                              "failures": fl})
    else:
        _dump(c, "REPAIR-DIFF.json", {"prereg_commit": commit, "runs": diffs})


def build_outs(c):
    outs = {s: [] for s in SOURCES}
    for run, (role, _, _) in RUNS.items():
        if role not in SOURCES:
            continue
        key = "segment_id" if role == "SEG" else "item_id"
        recs = output(c, run)[1]
        if os.path.exists(_p(c, f"FAILURES-{run}.json")):
            recs = apply_repair(recs, body(_jl(_p(c, f"OUT-{run}.repair.jsonl"))),
                                _load(c, f"FAILURES-{run}.json")["failures"], key)[0]
        if role == "SEG":
            outs[role] += list(flatten_inventory(recs))
        else:
            outs[role] += [{"orig_id": r["item_id"], "source_id": r["source_id"], "statement": r["statement"],
                            "quote": r["quote"]} for r in recs]
    return outs


def cmd_equalize(c, commit, a):
    lists, key = equalize(build_outs(c), secrets.token_hex(16))
    key["generated_utc"] = utc()                              # R5: must follow the check-2 freeze (verified in result)
    _dump(c, "MATCH-LISTS.json", {"prereg_commit": commit, "lists": lists})
    _dump(c, "SOURCE-KEY.json", key)


def cmd_ingest(c, commit, a):
    run = a[a.index("--run") + 1] if "--run" in a else ""
    if run not in RUNS:
        raise V1Error("--run <one of the eight runs> is required")
    d = dispatch(c).get(run)
    if not d or not d.get("transcript"):
        raise V1Error(f"{run}: no dispatch record with a transcript path in V1-DISPATCH.json")
    if not os.path.exists(d["transcript"]):
        raise V1Error(f"{run}: transcript file not found")
    rec = parse_transcript(d["transcript"])
    meta_path = d["transcript"][:-len(".jsonl")] + ".meta.json"
    meta = json.load(open(meta_path, encoding="utf-8")) if os.path.exists(meta_path) else {}
    calls = rec.pop("tool_calls")
    os.makedirs(_p(c, f"runs/{run}"), exist_ok=True)
    with open(_p(c, f"runs/{run}/TOOL-CALLS.jsonl"), "w", encoding="utf-8") as f:
        for x in calls:
            f.write(json.dumps(x, sort_keys=True, ensure_ascii=False) + "\n")
    rec.update({"run": run, "agent_id": d.get("agent_id"), "meta_model_alias": meta.get("model"),
                "status": d.get("status"), "failure_reason": d.get("failure_reason"),
                "tool_calls_sha256": _fsha(_p(c, f"runs/{run}/TOOL-CALLS.jsonl")), "ingested_utc": utc()})
    _dump(c, f"runs/{run}/RUN-RECORD.json", rec)


def cmd_freeze(c, commit, a):
    stage = a[a.index("--stage") + 1] if "--stage" in a else ""
    files = dict(STAGES).get(stage)
    if files is None:
        raise V1Error("--stage must be one of: " + ", ".join(s for s, _ in STAGES))
    prov = _jl(_p(c, "PROVENANCE.jsonl"))
    if any(p["stage"] == stage for p in prov):
        raise V1Error(f"stage {stage} already frozen")
    rec = {"stage": stage, "utc": utc(), "prereg_commit": commit, "files": {}}
    for name in files:
        p = _p(c, name.lstrip("#"))
        rec["files"][name] = _fsha(p) if os.path.exists(p) else None
    if "V1-DISPATCH.json" in files and os.path.exists(_p(c, "V1-DISPATCH.json")):     # X7: per-run entries
        rec["dispatch_entries"] = {r: sha(json.dumps(e, sort_keys=True)) for r, e in dispatch(c).items()}
    with open(_p(c, "PROVENANCE.jsonl"), "a", encoding="utf-8") as f:
        f.write(json.dumps(rec, sort_keys=True) + "\n")


def cmd_sample(c, commit, a):
    cl = body(_jl(_p(c, "CLUSTERS-A01.jsonl")))
    _dump(c, "SAMPLE-A02.json", {"prereg_commit": commit, "sample": draw_sample(cl, commit)})


def cmd_result(c, commit, a):
    texts = texts_of(c)
    key = _load(c, "SOURCE-KEY.json")
    cl = body(_jl(_p(c, "CLUSTERS-A01.jsonl")))
    d1, d2 = body(_jl(_p(c, "DECISIONS-A01.jsonl"))), body(_jl(_p(c, "DECISIONS-A02.jsonl")))
    smp = _load(c, "SAMPLE-A02.json")["sample"]
    prov = _jl(_p(c, "PROVENANCE.jsonl"))
    disp = dispatch(c)
    res, fails, diffs = run_checks(c, texts, True)            # recomputed here, after all eight runs
    invalid = [f"{r}: {w}" for r, w in access(c, texts)] + [f"{r}: {w}" for r, w in canaries(c, commit)]
    records = {}
    for run, (role, alias, _) in RUNS.items():
        rp = _p(c, f"runs/{run}/RUN-RECORD.json")
        if not os.path.exists(rp):
            invalid.append(f"{run}: no RUN-RECORD (transcript not ingested)")
            continue
        with open(rp, encoding="utf-8") as f:
            stored = json.load(f)
        if _fsha(_p(c, f"runs/{run}/TOOL-CALLS.jsonl")) != stored.get("tool_calls_sha256"):
            invalid.append(f"{run}: TOOL-CALLS differs from its ingest hash")
        d = disp.get(run) or {}
        tpath = d.get("transcript")
        if not tpath or not os.path.exists(tpath):             # X5: no re-verification possible → RUN-INVALID
            invalid.append(f"{run}: transcript unavailable at result (cannot re-verify)")
            continue
        rec = parse_transcript(tpath)                            # everything below uses the transcript, re-read now
        if rec["transcript_sha256"] != stored.get("transcript_sha256"):
            invalid.append(f"{run}: transcript changed after ingest")
        meta_path = tpath[:-len(".jsonl")] + ".meta.json"
        meta = json.load(open(meta_path, encoding="utf-8")) if os.path.exists(meta_path) else {}
        if rec["api_models"] != [MODEL_IDS[alias]] or meta.get("model") != alias:
            invalid.append(f"{run}: model identity {meta.get('model')}/{rec['api_models']} "
                           f"≠ {alias}/{MODEL_IDS[alias]}")
        invalid += [f"{r}: {w}" for r, w in linkage_breaches(run, rec, d.get("agent_id"), commit)]
        invalid += [f"{r}: {w} ({i})" for r, i, w in audit_calls(run, rec, os.path.join(c.CR, V1_DIR))]
        records[run] = rec
    invalid += [f"{r}: {w}" for r, w in order_breaches(prov, records, os.path.join(c.CR, V1_DIR))]
    c2 = [p["utc"] for p in prov if p["stage"] == "check-2"]
    if not key.get("generated_utc") or (c2 and ts(key["generated_utc"]) < ts(c2[0])):
        invalid.append("SOURCE-KEY: secret generated before the check-2 freeze (or undated)")
    invalid += [f"{r}: original output modified after the failure list" for r, dl in diffs.items()
                if any(x["reason"] == "ORIGINAL-OUTPUT-MODIFIED" for x in dl)]
    invalid += [f"{r}: {w}" for r, w in provenance_breaches(c, prov)]
    relists, _ = equalize(build_outs(c), key.get("nonce", ""))            # X7: the committed nonce reproduces the lists
    if relists != _load(c, "MATCH-LISTS.json")["lists"]:
        invalid.append("MATCH-LISTS: not reproduced from the unsealed nonce and the frozen outputs")
    status = {run: (disp.get(run) or {}).get("status") for run in RUNS}
    seg_exhausted = [r for r in RUNS if RUNS[r][0] == "SEG" and status[r] == "FAILED"
                     and (disp.get(r) or {}).get("failure_reason") == "CONTEXT-EXHAUSTED"]
    other_failed = [r for r in RUNS if status[r] != "COMPLETED" and r not in seg_exhausted]
    completed_seg = [r for r in RUNS if RUNS[r][0] == "SEG" and status[r] == "COMPLETED"]
    k, cap, cap_reason, m1v = gated_metrics(_load(c, "MATCH-LISTS.json")["lists"], cl, d1, d2, smp,
                                            key["letter_of_source"], status)
    m = {"run_invalid": invalid, "seg_exhausted": seg_exhausted, "other_failed": other_failed,
         "coverage_completed": {r: min(res[r]["coverage"].values()) for r in completed_seg if res[r]["coverage"]},
         "coverage_all_one": all(v == 1.0 for r in completed_seg for v in res[r]["coverage"].values()),
         "seg_quote_miss": {r: res[r]["quote_miss_rate"] for r in res
                            if status[r] == "COMPLETED" and RUNS[r][0] == "SEG"},
         "ref_quote_miss": {r: res[r]["quote_miss_rate"] for r in res
                            if status[r] == "COMPLETED" and RUNS[r][0] in ("E1", "E2")},
         "schema_ok": not any(f for r, fl in fails.items() if status[r] == "COMPLETED" and RUNS[r][0] == "SEG"
                              for f in fl if f["class"].startswith(("SCHEMA", "UNKNOWN", "DUPLICATE"))),
         "ref_schema": {r: sum(1 for f in fails[r] if f["class"].startswith(("SCHEMA", "UNKNOWN", "DUPLICATE")))
                        for r in fails if status[r] == "COMPLETED" and RUNS[r][0] in ("E1", "E2")},
         "kappa": k["kappa_target"], "kappa_reason": k["not_computable_reason"], "capture": cap["lenient"],
         "capture_reason": cap_reason, "capture_observed": cap["lenient_observed"], "agreed": cap["agreed"]}
    outcome, qualifier, reasons = decide(m)
    L = key["letter_of_source"]
    n = {s: sum(1 for x in cl if x["list"] == L[s]) for s in SOURCES}
    m12 = sum(1 for d in d1 if d["status"] == "MATCH" and d["other_list"] == L["E2"]
              and any(x["cluster_id"] == d["cluster_id"] and x["list"] == L["E1"] for x in cl))
    diag = {"kappa": k, "capture": cap, "clusters_per_source": n, "checks": res, "repair_diff": diffs,
            "m1_completeness_violations": m1v,
            "chapman_E1_E2_diagnostic_only": chapman(n["E1"], n["E2"], m12) if n["E1"] and n["E2"] else None,
            "tool_blobs": {"tool": c.git("hash-object", os.path.abspath(__file__))}}
    _dump(c, "V1-RESULT.json", {"prereg_commit": commit, "prereg": PREREG, "metrics": m, "diagnostics": diag,
                                "outcome": outcome, "qualifier": qualifier, "reasons": reasons})


def cmd_validate(a):
    """Agent self-check (schema and segment accounting only; no corpus, no quotes)."""
    run = a[a.index("--run") + 1] if "--run" in a else ""
    if run not in RUNS or RUNS[run][0] not in SOURCES:
        print("usage: validate --run <extractor run>", file=sys.stderr)
        return 2
    c = _cr()
    role, _, label = RUNS[run]
    recs = output(c, run)[1]
    sids = {f[0] for f in FILES if f[1] == label}
    if role == "SEG":
        r, fails = check_inventory(recs, [s for s in _segmap(c) if s["source_id"] in sids], None)
    else:
        r, fails = check_open(recs, sids, None)
    print(json.dumps({"run": run, "records": len(recs), "failures": fails[:200], "n_failures": len(fails)}, indent=1))
    return 0


def selftest():
    t = "intro line\n\n# A\npara one\n\n```\n# not a heading\n```\n\n## B\n" + ("word " * 3000) + "\n\n   \n"
    segs = segment(t)
    assert tiles(segs, len(t)) and segment(t) == segs
    assert [s["kind"] for s in segs][:3] == ["PREAMBLE", "HEADING", "HEADING"]
    base = {"run_invalid": [], "seg_exhausted": [], "other_failed": [], "coverage_completed": {}, "coverage_all_one": True,
            "seg_quote_miss": {}, "ref_quote_miss": {}, "schema_ok": True, "kappa": 0.7, "kappa_reason": None,
            "capture": 0.9, "capture_reason": None, "capture_observed": 0.9, "agreed": 30}
    assert decide(base)[0] == "INSTRUMENTS-VALID"
    assert decide(dict(base, capture=None))[1] == "INSTRUMENT-UNDETERMINED"
    print("selftest PASS")
    return 0


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    if not a:
        print(__doc__, file=sys.stderr)
        return 2
    if a[0] == "selftest":
        return selftest()
    if a[0] == "validate":
        return cmd_validate(a)
    cmds = {"segment": cmd_segment, "check": cmd_check, "equalize": cmd_equalize, "ingest": cmd_ingest,
            "freeze": cmd_freeze, "sample": cmd_sample, "result": cmd_result}
    if a[0] not in cmds or "--commit" not in a:
        print(__doc__, file=sys.stderr)
        return 2
    commit = a[a.index("--commit") + 1]
    try:
        seed_of(commit)
        c = _cr()
        authorized(c, commit)
        cmds[a[0]](c, commit, a)
    except V1Error as e:
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
