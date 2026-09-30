#!/usr/bin/env python3
"""P3b S5 contract revision 6 (§26; G-LOG-0083): the repair of the revision-5 audit findings R5-01…R5-08.

Principle: the verifier derives every critical verification state from AUTHORITATIVE inputs (the hash-verified slice,
the seal-aware resolver's bytes, the reader-written per-run logs, 02-FILES metadata, the manifest-bound plan hash);
agent or orchestrator declarations are assertions compared against that derivation, never ground truth.
PASS ⇒ P ∧ E ∧ R ∧ T ∧ L ∧ S (provenance, execution/run, reading state, temporal, load/plan, synthesis).

Revision 5 (never activated) is WITHDRAWN and superseded. Revision 6 is drafted and implemented, NOT activated. Pure
functions of their arguments; content bytes are passed in by the caller (seal-aware resolver). No ML anywhere.
Reused from revision 5 (unchanged): byte_pages, byte_page_record, ack_token, byte_page_coverage, is_binary,
packing_key, partition, dispatch_path, binary_preclassify, archive_member_seal, extraction_permitted.
"""
import datetime
import hashlib
import importlib.util
import json
import math
import os
import random
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("p3b_s5_r5", os.path.join(_HERE, "p3b_s5_r5.py"))
r5 = importlib.util.module_from_spec(_s)
_s.loader.exec_module(r5)

REVISION = 6
BUDGET = r5.BUDGET
READER_MODE = r5.READER_MODE
READING_STATES = r5.READING_STATES
EDGE_CLASSES = r5.EDGE_CLASSES
PAIR_SUPPORT = r5.PAIR_SUPPORT
VEC = r5.VEC
EMPTY_MARK = r5.EMPTY_MARK
KINDS = ("lexical", "conceptual", "formal", "operational", "governance")
FACT_KINDS = {"TIMELINE": ("change_candidate",), "BIRTH": ("birth_kind",), "ABSENCE": ("dimension", "finding"),
              "DEPENDENCY": ("target_label",), "OMQ14": ("type",)}
FINDINGS = ("DEFINES", "MENTIONS", "NONE")
# R5-06: only these timeline classes may rest on a non-WHOLE-FILE source; every other class, including any future
# class, requires whole-file evidence (a default-deny set, not a prefix test).
WHOLE_NOT_REQUIRED = frozenset({"FIRST", "RESTATES", "EXTENDS", "NOT-COMPARABLE"})
RUN6 = re.compile(r"^(OB\d{4})-R6-L(\d{2})(U(\d{2})|S)?$")
PAIR_TOKEN = re.compile(r"\bRP\d+\b")
UTC_RE = re.compile(r"^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d{1,6})?(Z|[+-]\d{2}:\d{2})$")
RANGE6 = re.compile(r"\bS\d{4}\s*(?:–|—|-|−|‒|―|~|\.\.\.?|…|to|through|thru|until|till|bis)\s*(?:S?\d{2,4})\b",
                    re.IGNORECASE)                  # "/" is an enumeration ("S0957/S2361"), not a range: excluded
POINTER6 = re.compile(r"\bsee (the )?register\b|\bresearch[- ]register\b|\bregister (record|id|entry)\b|\brs_id\b|"
                      r"\bcf\.? (the )?register\b", re.IGNORECASE)


def sha(b):
    return hashlib.sha256(b).hexdigest()


def canon_bytes(x):
    return json.dumps(x, sort_keys=True, separators=(",", ":"), ensure_ascii=False).encode("utf-8")


# ------------------------------------------------------------------ T: strict time
def parse_utc(s):
    """RFC 3339 with an explicit zone (Z or ±hh:mm) → aware UTC datetime; anything else → None (never a string compare)."""
    if not isinstance(s, str) or not UTC_RE.match(s):
        return None
    try:
        d = datetime.datetime.fromisoformat(s.replace("Z", "+00:00"))
    except ValueError:
        return None
    return d.astimezone(datetime.timezone.utc) if d.tzinfo else None


# ------------------------------------------------------------------ L: plan derivation (authoritative)
def slice_required(sl):
    rows = {json.loads(r)["source_id"] for r in ((sl.get("bundle") or {}).get("rows_verbatim") or [])}
    s2 = set() if sl.get("hub") else set(sl.get("stage2_files") or [])
    return s2 | rows, rows


def plan_derive(batch, labels, slices, contents, files_meta):
    """The plan the verifier expects, derived ONLY from the manifest label order, the hash-verified slices, the
    resolver's bytes (sizes = byte lengths) and 02-FILES metadata (packing key). labels: the manifest entry order;
    L## = 1-based position (binds run ids to their owner label)."""
    out = {"batch_id": batch, "revision": REVISION, "labels": {}}
    for i, lab in enumerate(labels, 1):
        req, rows = slice_required(slices[lab])
        sizes = {s: len(contents[s]) for s in sorted(req)}
        path = r5.dispatch_path(len(req), sum(sizes.values()))
        L = f"{batch}-R6-L{i:02d}"
        entry = {"index": i, "path": path, "files": sorted(req), "row_sources": sorted(rows), "sizes": sizes,
                 "packing_order": [], "units": [], "runs": {}}
        if path == "SINGLE":
            entry["units"] = [sorted(req)]
            entry["runs"] = {L: {"role": "SINGLE", "files": sorted(req)}}
        elif path == "DECOMPOSED":
            order = sorted(rows, key=lambda s: r5.packing_key(s, files_meta.get(s)))
            pos = {s: k for k, s in enumerate(order)}
            units = r5.partition(req, rows, sizes, lambda s: pos[s])
            entry["packing_order"] = order
            entry["units"] = units
            entry["runs"] = {f"{L}U{k:02d}": {"role": "UNIT", "files": u} for k, u in enumerate(units, 1)}
            entry["runs"][f"{L}S"] = {"role": "SYNTHESIS", "files": []}
        out["labels"][lab] = entry
    return out


def plan_violations(declared, derived, declared_sha, manifest_sha):
    F = []
    if declared is None:
        return ["R6 L frozen plan missing"]
    if sha(canon_bytes(declared)) != manifest_sha or declared_sha not in (None, manifest_sha):
        F.append("R6 L plan hash ≠ the manifest-bound r6_plan_sha256")
    if canon_bytes(declared) != canon_bytes(derived):
        for lab in sorted(set(declared.get("labels", {})) | set(derived["labels"])):
            a, b = (declared.get("labels") or {}).get(lab), derived["labels"].get(lab)
            if a != b:
                keys = sorted(k for k in set(a or {}) | set(b or {}) if (a or {}).get(k) != (b or {}).get(k))
                F.append(f"R6 L {lab}: frozen plan ≠ independent derivation ({', '.join(keys)})")
        if not any("frozen plan ≠" in x for x in F):
            F.append("R6 L frozen plan ≠ independent derivation")
    return F


def run_owner(plan):
    """{run_id: (label, role, files)} — exactly one owner per run (by construction of plan_derive)."""
    return {run: (lab, r["role"], set(r["files"])) for lab, L in plan["labels"].items() for run, r in L["runs"].items()}


def reader_plan_check(plan, run, batch, sid, label):
    """Reader-side defence in depth (the verifier stays authoritative): a revision-6 read is allowed only for a
    planned reading run, for a file that run is permitted, with --label equal to the plan owner."""
    if plan is None or plan.get("batch_id") != batch:
        return False, "no frozen revision-6 plan for this batch"
    own = run_owner(plan).get(run)
    if own is None:
        return False, "run not in the frozen plan"
    lab, role, files = own
    if role == "SYNTHESIS":
        return False, "synthesis runs may not read (records-only synthesis)"
    if sid not in files:
        return False, "file not permitted for this run"
    if label != lab:
        return False, "--label differs from the run's plan owner"
    return True, None


# ------------------------------------------------------------------ R / P: records, facts, claims, evidence
def _in_text(quote, text):
    return bool(quote) and (quote in text or " ".join(quote.split()) in " ".join(text.split()))


def fact_violations(run, rec, content):
    """Every fact: unique id, known kind, required fields, and a quote that occurs in the AUTHORITATIVE bytes."""
    F, seen = [], set()
    text = content.decode("utf-8", errors="replace") if content is not None else ""
    for f in rec.get("facts") or []:
        fid, kind = f.get("fact_id"), f.get("kind")
        where = f"R6 P {run} {rec.get('source_id')} fact {fid!r}"
        if not fid or fid in seen:
            F.append(f"{where}: missing or duplicate fact_id")
        seen.add(fid)
        if kind not in FACT_KINDS:
            F.append(f"{where}: kind {kind!r} not in {sorted(FACT_KINDS)}")
            continue
        if any(not f.get(k) for k in FACT_KINDS[kind]):
            F.append(f"{where}: missing field(s) {[k for k in FACT_KINDS[kind] if not f.get(k)]}")
        if kind == "ABSENCE" and f.get("finding") not in FINDINGS:
            F.append(f"{where}: finding {f.get('finding')!r} off-scale")
        if kind == "BIRTH" and f.get("birth_kind") not in KINDS:
            F.append(f"{where}: birth_kind off-scale")
        if not _in_text(f.get("quote"), text):
            F.append(f"{where}: quote not found in the authoritative source bytes")
    return F


def enumerate_claims(obj):
    """The verifier's own enumeration of every source-requiring claim in an object (never a submitted list).
    Returns [(claim_id, spec)] with spec = {kind, source, fields, whole}."""
    out = []
    for p in obj.get("timeline") or []:
        if isinstance(p, dict) and p.get("source_id"):
            cls = p.get("change_vs_previous")
            out.append((f"timeline:{p['source_id']}", {"kind": "TIMELINE", "source": p["source_id"], "fields": {},
                                                        "whole": cls not in WHOLE_NOT_REQUIRED}))
    for dim, a in (obj.get("absences") or {}).items():
        if isinstance(a, dict) and a.get("resolution") == "FOUND":
            src = (a.get("supplied_by") or {}).get("source_id")
            out.append((f"absence:{dim}", {"kind": "ABSENCE", "source": src,
                                           "fields": {"dimension": dim, "finding": "DEFINES"}, "whole": True}))
    for d in obj.get("stage2_dispositions") or []:
        for dim, v in (d.get("by_dimension") or {}).items():
            if v == "FOUND":
                cid = f"stage2:{d.get('source_id')}:{d.get('hit_kind')}:{json.dumps(d.get('hit_key'))}:{d.get('term_index')}:{dim}"
                out.append((cid, {"kind": "ABSENCE", "source": d.get("source_id"),
                                  "fields": {"dimension": dim, "finding": "DEFINES"}, "whole": True}))
    for k, v in (obj.get("births") or {}).items():
        for s in r5.SID.findall(str(v)):
            out.append((f"birth:{k}:{s}", {"kind": "BIRTH", "source": s, "fields": {"birth_kind": k},
                                           "whole": not str(v).startswith("BIRTH-UNRESOLVED")}))
    for e in obj.get("dependency_edges") or []:
        if isinstance(e, dict) and e.get("edge_class") == "R2-EVIDENCED":
            out.append((f"edge:{e.get('target_label')}:{e.get('source_id')}",
                        {"kind": "DEPENDENCY", "source": e.get("source_id"),
                         "fields": {"target_label": e.get("target_label")}, "whole": False}))
    return out


def claim_evidence_violations(obj, claim_map, facts_index, states, coverage_ok, reading_runs):
    """P: every enumerated claim resolves to >= 1 valid evidence ref (run, source_id, fact_id).
    facts_index: {(run, sid, fact_id): fact}; states: {sid: reading_state}; coverage_ok: {sid: bool} (complete byte
    coverage in the label's own permitted run); reading_runs: the label's planned reading runs."""
    F = []
    for cid, spec in enumerate_claims(obj):
        refs = (claim_map or {}).get(cid) or []
        good = False
        for ref in refs:
            run, sid, fid = ref.get("run"), ref.get("source_id"), ref.get("fact_id")
            f = facts_index.get((run, sid, fid))
            if run not in reading_runs or f is None or sid != spec["source"] or f.get("kind") != spec["kind"]:
                continue
            if any(f.get(k) != v for k, v in spec["fields"].items()):
                continue
            if spec["whole"] and not (states.get(sid) == "WHOLE-FILE" and coverage_ok.get(sid)):
                continue
            good = True
            break
        if not good:
            F.append(f"R6 P {obj.get('working_label')}: claim {cid} has no valid evidence path "
                     f"(claim → fact → record → source bytes)")
    for dim, a in (obj.get("absences") or {}).items():               # census rule for GENUINELY-UNDEFINED
        if isinstance(a, dict) and a.get("resolution") == "GENUINELY-UNDEFINED-AFTER-CENSUS":
            if any(st != "WHOLE-FILE" for st in states.values()) or not all(coverage_ok.get(s) for s in states):
                F.append(f"R6 P {obj.get('working_label')}: absences.{dim} GENUINELY-UNDEFINED with an incomplete census")
            if any(f.get("kind") == "ABSENCE" and f.get("dimension") == dim and f.get("finding") == "DEFINES"
                   for f in facts_index.values()):
                F.append(f"R6 P {obj.get('working_label')}: absences.{dim} GENUINELY-UNDEFINED although a record fact DEFINES it")
    return F


def reading_rule_violations(obj, states):
    """R (R5-06, R5-14): non-WHOLE-FILE sources support no whole-file claim (all change classes except the explicit
    non-change set); every non-WHOLE-FILE required file carries a CONTRACT-DEVIATION escalation naming it."""
    F = list(r5.reading_state_violations(dict(obj, timeline=[]), states))        # dispositions, absences, births
    whole = {s for s, st in states.items() if st == "WHOLE-FILE"}
    for p in obj.get("timeline") or []:
        if isinstance(p, dict) and p.get("change_vs_previous") not in WHOLE_NOT_REQUIRED and p.get("source_id") not in whole:
            F.append(f"timeline: {p.get('change_vs_previous')} point on non-WHOLE-FILE source {p.get('source_id')}")
    cd = {m for e in obj.get("escalations") or [] if isinstance(e, dict) and e.get("reason") == "CONTRACT-DEVIATION"
          for m in r5.SID.findall(str(e.get("detail") or ""))}
    for s, st in states.items():
        if st != "WHOLE-FILE" and s not in cd:
            F.append(f"{s}: {st} required file without a CONTRACT-DEVIATION escalation naming it")
    return [f"R6 R {obj.get('working_label')}: {x}" for x in F]


# ------------------------------------------------------------------ S: S1, S3, edges, EMPTY
def s1_violations_v6(pairs, label, files, records, contents, obj, register):
    """S1 with exact pair tokens and quotes verified against the authoritative bytes (R5-07, R5-10)."""
    need = set()
    for p in pairs:
        if label not in (p.get("a"), p.get("b")):
            continue
        cited = set(r5.SID.findall(json.dumps(p.get("what_says_this"), ensure_ascii=False) + json.dumps(p.get("basis"))))
        need |= {(p["pair_id"], s) for s in cited & set(files)}
    got = {}
    for r in records:
        for ck in r.get("pair_evidence_checks") or []:
            got[(ck.get("pair_id"), r.get("source_id"))] = ck
    F = [f"R6 S1 {label}: no pair-evidence check for pair {p} in {s}" for p, s in sorted(need - set(got))]
    esc = {m.group(1) for e in obj.get("escalations") or [] if isinstance(e, dict) and e.get("reason") == "OTHER"
           for m in [re.match(r"^VERDICT-EVIDENCE-CONFLICT: (RP\d+)\b", str(e.get("detail") or ""))] if m}
    for (pid, sid), ck in sorted(got.items(), key=lambda x: (str(x[0][0]), str(x[0][1]))):
        sup = ck.get("supports")
        if sup not in PAIR_SUPPORT:
            F.append(f"R6 S1 {label}: pair {pid} in {sid}: supports {sup!r} off-scale")
            continue
        if sup in ("YES", "NO"):
            text = (contents.get(sid) or b"").decode("utf-8", errors="replace")
            if not _in_text(ck.get("quote"), text):
                F.append(f"R6 S1 {label}: pair {pid} in {sid}: {sup} quote not found in the authoritative bytes")
        if sup == "NO":
            reg = any(VEC in (x.get("topics") or []) and pid in PAIR_TOKEN.findall(json.dumps(x, ensure_ascii=False))
                      for x in register)
            if not reg:
                F.append(f"R6 S1 {label}: pair {pid}: NO without a {VEC} register record naming it exactly")
            if pid not in esc:
                F.append(f"R6 S1 {label}: pair {pid}: NO without an escalation (reason OTHER, detail "
                         f"'{VEC}: {pid}')")
    return F


def s3_lint_v6(records, label, run, batch, quarantine_hits=None, sets=None, layer_a=()):
    """S3 on object + register + P1-gap: broadened ranges, case-insensitive pointers, register ids, quarantine.
    `layer_a`: indices the caller KNOWS are layer-A objects (the verifier passes the object's position), so an object
    stripped of its layer-A keys cannot escape the pointer check (audit H3); key sniffing remains as a fallback."""
    out = []
    rsid = re.compile(r"\b(?:" + re.escape(run) + r":)?" + re.escape(batch) + r":\d+\b|\b" + re.escape(batch) + r"#\d+\b")
    for i, r in enumerate(records):
        txt = json.dumps(r, ensure_ascii=False)
        if RANGE6.search(txt):
            out.append(f"R6 S3 {label}: record {i}: S-id range")
        if i in layer_a or (r.get("working_label") and ("timeline" in r or "births" in r or "absences" in r)):
            body = json.dumps({k: v for k, v in r.items() if k not in ("run_id", "batch_id")}, ensure_ascii=False)
            if POINTER6.search(body) or rsid.search(body):
                out.append(f"R6 S3 {label}: record {i}: register id or pointer in layer A")
        if quarantine_hits is not None:
            nl, nf = quarantine_hits(txt, label, sets)
            if nl or nf:
                out.append(f"R6 S3 {label}: record {i}: quarantine hit")
    return out


def empty_violations_v6(obj):
    """R5-08: EMPTY is a classification, not an exemption."""
    F = [f"R6 S {obj.get('working_label')}: {x}" for x in r5.empty_label_violations(obj)]
    lab = obj.get("working_label")
    if obj.get("timeline"):
        F.append(f"R6 S {lab}: EMPTY object with timeline points")
    if obj.get("dependency_edges"):
        F.append(f"R6 S {lab}: EMPTY object with dependency edges")
    return F


def ack_equal_violations(run, sid, tokens, cov, content):
    """R5-11: the listed tokens must equal, as a MULTISET, the per-page tokens derived from the authoritative bytes
    (identical pages legitimately share a token; any missing, extra or surplus-duplicate token fails)."""
    if not cov or not cov["complete"] or content is None:
        return []
    expected = [r5.ack_token(sha(content[a:e])) for a, e in r5.byte_pages(content)]
    if sorted(tokens or []) != sorted(expected):
        return [f"R6 R {run} {sid}: acknowledgement tokens ≠ the page tokens (missing, extra or duplicate)"]
    return []


# ------------------------------------------------------------------ T: temporal integrity
def temporal_violations(label, path, runs, logs_by_run, stages, manifests, record_hashes_now):
    """T (R5-02). stages: {"units-validated": {utc, record_hashes}, "synthesis-dispatched": {utc}} for this label.
    manifests: {run: RUN-MANIFEST.json dict}. record_hashes_now: {run: sha of its file-reading-records.jsonl}."""
    F = []
    tag = f"R6 T {label}"
    read_times = {}
    for run, es in logs_by_run.items():
        for e in es:
            t = parse_utc(e.get("utc"))
            if t is None:
                F.append(f"{tag}: {run}: read-log utc {e.get('utc')!r} is not strict RFC 3339 with a zone")
            else:
                read_times.setdefault(run, []).append(t)
    reading = [r for r, (role) in runs.items() if role in ("UNIT", "SINGLE")]
    final = next((r for r, role in runs.items() if role in ("SYNTHESIS", "SINGLE")), None)
    if path == "EMPTY":
        return F
    man = manifests.get(final) or {}
    produced = parse_utc(man.get("produced_utc"))
    if produced is None:
        F.append(f"{tag}: final run {final} has no strict produced_utc in RUN-MANIFEST.json")
    if man.get("input_record_hashes") != {r: record_hashes_now.get(r) for r in reading}:
        F.append(f"{tag}: final run's manifest does not bind the exact current record files of the reading runs")
    last_read = max((t for r in reading for t in read_times.get(r, [])), default=None)
    if path == "SINGLE":
        if produced is not None and last_read is not None and last_read > produced:
            F.append(f"{tag}: reads logged after the object was produced")
        return F
    uv = parse_utc((stages.get("units-validated") or {}).get("utc"))
    sd = parse_utc((stages.get("synthesis-dispatched") or {}).get("utc"))
    if uv is None or sd is None:
        F.append(f"{tag}: units-validated / synthesis-dispatched stage missing or not strict RFC 3339")
        return F
    if last_read is not None and last_read > uv:
        F.append(f"{tag}: unit reads logged after units-validated (R19)")
    if not uv < sd:
        F.append(f"{tag}: synthesis not strictly after unit validation (R19)")
    if produced is not None and produced < sd:
        F.append(f"{tag}: synthesis output produced before synthesis dispatch")
    if (stages.get("units-validated") or {}).get("record_hashes") != {r: record_hashes_now.get(r) for r in reading}:
        F.append(f"{tag}: unit record files changed after validation (or validation did not bind them)")
    return F


# ------------------------------------------------------------------ statistical specification (R5-16)
STRATUM_PRECEDENCE = ("HUB", "EMPTY", "MULTIROW", "DECOMPOSED", "SINGLE")


def assign_strata(labels):
    """Mutually exclusive, collectively exhaustive strata by precedence. labels: {label: {path, hub, multirow}}.
    TIER-X is a reporting DOMAIN (a nested attribute), not a stratum."""
    out = {h: [] for h in STRATUM_PRECEDENCE}
    for lab, a in sorted(labels.items()):
        h = "HUB" if a.get("hub") else "EMPTY" if a.get("path") == "EMPTY" else \
            "MULTIROW" if a.get("multirow") else "DECOMPOSED" if a.get("path") == "DECOMPOSED" else "SINGLE"
        out[h].append(lab)
    return out


def validate_strata(population, strata):
    allocated = [l for v in strata.values() for l in v]
    F = []
    if len(allocated) != len(set(allocated)):
        F.append("strata not mutually exclusive")
    if set(allocated) != set(population):
        F.append("strata not collectively exhaustive over the population")
    return F


def draw_sample_v6(population, strata, seed, rates):
    """Validated frame; rate 'CENSUS' or a fraction in [0, 1]; a rate of 0 draws nothing (n = 0, stated)."""
    bad = validate_strata(population, strata)
    if bad:
        raise ValueError("; ".join(bad))
    rng = random.Random(seed)
    out = {}
    for h in STRATUM_PRECEDENCE:
        pop = sorted(strata.get(h, []))
        rate = rates.get(h, 0)
        n = len(pop) if rate == "CENSUS" else min(len(pop), math.ceil(rate * len(pop))) if rate else 0
        out[h] = {"labels": sorted(rng.sample(pop, n)) if n else [], "N": len(pop), "n": n,
                  "pi": (n / len(pop)) if pop else 0.0}
    return out


def zero_bound_v6(n, N=None, alpha=0.05):
    """One-sided (1 - alpha) upper bound on the discordant share after 0 discordant in n: n = 0 → 1.0 (no
    information); census (n = N) → 0.0; finite N → hypergeometric (largest D with P(0 | D) >= alpha); else binomial."""
    if n <= 0:
        return 1.0
    if N is not None:
        if n >= N:
            return 0.0
        return r5.zero_bound(n, N, alpha)
    return r5.zero_bound(n, None, alpha)


def ht_with_variance(sample, discordant, frozen_sha=None, sample_sha=None):
    """Stratified HT total/share of labels with D_i = 1 and its SRSWOR variance
    Var = Σ_h N_h² (1 − n_h/N_h) s_h² / n_h (census and n_h <= 1 strata: exact/unestimable, reported), plus a normal
    CI on the share with finite-population correction, and per-stratum exact one-sided bounds for zero strata.
    Refuses discordant labels outside the probability sample and a sample that is not the frozen one (ML exclusion)."""
    if frozen_sha is not None and frozen_sha != sample_sha:
        raise ValueError("the sample is not the frozen probability sample")
    sampled = {l for s in sample.values() for l in s["labels"]}
    if set(discordant) - sampled:
        raise ValueError("discordant labels outside the probability sample (ML queues never enter the estimate)")
    tot, var, N_all, per = 0.0, 0.0, 0, {}
    for h, s in sample.items():
        n, N = s["n"], s["N"]
        N_all += N
        d = sum(1 for l in s["labels"] if l in discordant)
        est = (d / s["pi"]) if s["pi"] else 0.0
        v = 0.0
        if 1 < n < N:
            p = d / n
            s2 = p * (1 - p) * n / (n - 1)
            v = N * N * (1 - n / N) * s2 / n
        per[h] = {"n": n, "N": N, "discordant": d, "ht_total": est, "var": v,
                  "zero_bound_share": zero_bound_v6(n, N) if d == 0 else None}
        tot += est
        var += v
    share = tot / N_all if N_all else 0.0
    se = math.sqrt(var) / N_all if N_all else 0.0
    return {"strata": per, "ht_total": tot, "var_total": var, "share": share,
            "ci95_share": (max(0.0, share - 1.96 * se), min(1.0, share + 1.96 * se))}


def freeze_sample_record(spec_text, seed, sample):
    """The frozen record: spec hash, seed, drawn labels, sample hash, runtime version (written at activation)."""
    body = {"spec_sha256": sha(spec_text.encode("utf-8")), "seed": seed, "sample": sample,
            "python": sys.version.split()[0]}
    body["sample_sha256"] = sha(canon_bytes(sample))
    return body


# ------------------------------------------------------------------ scan (R5-13)
SCAN_OUTSIDE6 = re.compile(r"\bopen\s*\(|\bcat\s+[^|;&]*docs/knowledgeos|\$\{?[A-Za-z_]+\}?\s+(show|cat-file)\b")


def scan_level_v6(call, run, batch, corpus_tokens, holdout_tokens):
    """Every reader call in a command is checked (not only the first); extra outside-reader patterns."""
    lvl, why = r5.scan_level(call, run, batch, corpus_tokens, holdout_tokens)
    if why:
        return lvl, why
    text = json.dumps(call.get("input"), ensure_ascii=False)
    if SCAN_OUTSIDE6.search(text):
        return 2, "ACCESS-OUTSIDE-READER"
    for m in r5.READER.finditer(text):
        a = m.group("args")
        if not r5.SID.search(a):
            continue
        r, b = re.search(r"--run\s+(\S+)", a), re.search(r"--batch\s+(\S+)", a)
        if not r or not b or r.group(1) != run or b.group(1) != batch:
            return 2, "READER-FOREIGN-RUN-OR-BATCH"
    return lvl, why
