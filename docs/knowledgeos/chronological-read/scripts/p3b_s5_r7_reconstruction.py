#!/usr/bin/env python3
"""R7 bounded context RECONSTRUCTION — "what follows, and is it well-formed?" (frozen addendum v2.3 §4, §13; DR-01/01b,
DR-02/02b; FD-1′a/b/c, FD-2′, FD-3′).

Summary relations are OBJECT-LEVEL relations, not projections of the adjacent `change_vs_previous` class: membership +
inheritance + one-directional class constraints (CONTRADICTS ⇒ ∈ contradicted_by targeting its predecessor; RETRACTS ⇒
∈ rejected_by), never equality. The later-list assignment (FD-1′b, frozen): RESTATES → later_support; EXTENDS, NARROWS,
CHANGES-* → later_refinement (a relationship classification, not a truth judgment). Lateness requires ESTABLISHED
precedence (FD-1′c). A contradiction is a relation {source_id, contradicts}; its precedence is COMPUTED from A.10 dated
positions only (never array, list, source_id, UNORDERED-BLOCK or STEP-NUMBER order). Plus lifecycle (FD-3′, derived
position), S1 (carried over; duplicate pair checks FAIL), S2, S3 with the CANONICAL range matcher and normalized pointer
matching, EMPTY, edge classes. Depends on Evidence/Universe only through its inputs. Failure strings are tagged "R7-R".
"""
import importlib.util
import json
import os
import re

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


U = _load("p3b_s5_r7_universe")
c = _load("p3b_s5_common")
r5, r6 = U.r5, U.r6
SUPPORT_CLASSES = {"RESTATES"}                                                        # FD-1′b (frozen)
REFINEMENT_CLASSES = {"EXTENDS", "NARROWS", "CHANGES-DEFINITION", "CHANGES-TYPE", "CHANGES-TERM"}
POINTER7 = re.compile(r"\bsee (the )?register\b|\bresearch[- ]register\b|\bregister (record|id|entry)\b|\brs[-_ ]?id\b|"
                      r"\bcf\.? (the )?register\b", re.IGNORECASE)


# ---------------------------------------------------------------- A.10 dated positions and precedence
def dated_position(sid, point, meta):
    """The A.10 dated position (ISO day) of a timeline point, or None (undated)."""
    m = meta.get(sid) or {}
    if m.get("best_historical_date_basis") != "EXPLICIT" or m.get("order_evidence") == "SOURCE_ID" \
            or str(m.get("mtime_block") or "").startswith("BULK-"):
        return None
    if point is not None and point.get("date_applies_to_file") != "CONFIRMED":
        return None
    d = c.to_date(m.get("best_historical_date"))
    return d.isoformat() if d else None


def file_position(sid, meta):
    """A.10 FILE-level dated position (for superseded_by_sources): the file test only; else 'UNDATED'."""
    return dated_position(sid, None, meta) or "UNDATED"


def precedence(t, s, points, meta):
    """ESTABLISHED iff both points have A.10 dated positions and date(t) < date(s), strictly; otherwise NOT-ESTABLISHED."""
    a, b = dated_position(t, points.get(t), meta), dated_position(s, points.get(s), meta)
    return "ESTABLISHED" if a and b and a < b else "NOT-ESTABLISHED"


# ---------------------------------------------------------------- summary relations (PF-1/PF-2)
def summary_violations(obj, meta):
    lab = obj.get("working_label")
    tl = [p for p in obj.get("timeline") or [] if isinstance(p, dict)]
    pts = {p.get("source_id"): p for p in tl}
    ts_ = obj.get("timeline_summary") or {}
    cb = [e for e in ts_.get("contradicted_by") or [] if isinstance(e, dict)]
    F = []
    for i, p in enumerate(tl):
        cls, s = p.get("change_vs_previous"), p.get("source_id")
        if cls in ("CONTRADICTS", "RETRACTS"):
            if i == 0:
                F.append(f"{cls} point {s} has no predecessor in the timeline list (one-directional constraint unsatisfiable)")
                continue
            pred = tl[i - 1].get("source_id")
            if cls == "CONTRADICTS" and not any(e.get("source_id") == s and e.get("contradicts") == pred for e in cb):
                F.append(f"CONTRADICTS point {s} missing from contradicted_by (target = its predecessor {pred})")
            if cls == "RETRACTS" and s not in (ts_.get("rejected_by") or []):
                F.append(f"RETRACTS point {s} missing from rejected_by")
    births = obj.get("births") if isinstance(obj.get("births"), dict) else {}
    for k in U.KINDS:                                                    # DERIVED: first_<kind> = births.<kind>
        if f"first_{k}" in ts_ and set(U.SID.findall(str(ts_.get(f"first_{k}") or ""))) != set(U.SID.findall(str(births.get(k) or ""))):
            F.append(f"timeline_summary.first_{k} ≠ births.{k} (DERIVED equality)")
    for fld in ("later_support", "later_refinement", "rejected_by"):
        for s in ts_.get(fld) or []:
            if s not in pts:
                F.append(f"{fld} entry {s} is not a timeline point of this object")
    for s in ts_.get("later_support") or []:
        if s in pts and pts[s].get("change_vs_previous") not in SUPPORT_CLASSES:
            F.append(f"later_support entry {s} is {pts[s].get('change_vs_previous')} (FD-1′b: only RESTATES)")
    for s in ts_.get("later_refinement") or []:
        if s in pts and pts[s].get("change_vs_previous") not in REFINEMENT_CLASSES:
            F.append(f"later_refinement entry {s} is {pts[s].get('change_vs_previous')} "
                     f"(FD-1′b: EXTENDS, NARROWS, CHANGES-*)")
    dated = sorted((d, s) for s, p in pts.items() for d in [dated_position(s, p, meta)] if d)
    first = dated[0][1] if dated else None
    for fld in ("later_support", "later_refinement"):
        for s in ts_.get(fld) or []:
            if s in pts and (first is None or precedence(first, s, pts, meta) != "ESTABLISHED"):
                F.append(f"{fld} entry {s} asserts lateness without ESTABLISHED precedence over the earliest dated point")
    for e in cb:
        s, t = e.get("source_id"), e.get("contradicts")
        if s not in pts or t not in pts:
            F.append(f"contradicted_by {s} → {t}: a member is not a timeline point of this object")
        elif s == t:
            F.append(f"contradicted_by {s} contradicts itself")
    if obj.get("semantic_status") == "CONTESTED" and obj.get("semantic_status_rule") == "ROW-1" and cb:
        d4 = {x.get("source_id") for x in (obj.get("semantic_evidence") or {}).get("d4") or [] if isinstance(x, dict)}
        if not any(e.get("source_id") in d4 for e in cb):
            F.append("CONTESTED (ROW-1, D4) without a semantic_evidence.d4 entry for a contradicting source")
    return [f"R7-R {lab}: {x}" for x in F]


def contradiction_precedence(obj, meta):
    """Computed qualifier per relation (reported; never taken from the object)."""
    pts = {p.get("source_id"): p for p in obj.get("timeline") or [] if isinstance(p, dict)}
    return {(e.get("source_id"), e.get("contradicts")): precedence(e.get("contradicts"), e.get("source_id"), pts, meta)
            for e in (obj.get("timeline_summary") or {}).get("contradicted_by") or [] if isinstance(e, dict)}


def lifecycle_violations(obj, meta):
    lab = obj.get("working_label")
    F = []
    life = str((obj.get("timeline_summary") or {}).get("current_lifecycle") or "")
    sbs = [e for e in obj.get("superseded_by_sources") or [] if isinstance(e, dict)]
    if ("SUPERSEDED" in life) != bool(sbs):
        F.append(f"current_lifecycle {life!r} vs superseded_by_sources ({len(sbs)}): SUPERSEDED ⇔ non-empty (FD-3′)")
    for e in sbs:
        want = file_position(e.get("source_id"), meta)
        if e.get("position") != want:
            F.append(f"superseded_by_sources {e.get('source_id')}: position {e.get('position')!r} ≠ derived {want!r} (A.10 file test)")
    return [f"R7-R {lab}: {x}" for x in F]


# ---------------------------------------------------------------- S1, S3, EMPTY
def s1_violations(pairs, label, files, records, contents, obj, register):
    F = []
    seen = {}
    for r in records:
        for ck in r.get("pair_evidence_checks") or []:
            k = (ck.get("pair_id"), r.get("source_id"))
            if k in seen:
                F.append(f"R7-R S1 {label}: duplicate pair check for {k[0]} in {k[1]} (no last-wins)")
            seen[k] = ck
    return F + [x.replace("R6 S1", "R7-R S1") for x in r6.s1_violations_v6(pairs, label, files, records, contents, obj, register)]


def _pointer_norm(text):
    t = c._range_norm(text)
    t = re.sub(r"\s+", " ", t)
    return re.sub(r"\s*:\s*", ":", t)


def s3_violations(records, label, run, batch, quarantine_hits=None, sets=None, layer_a=()):
    """S3 on object + register + P1-gap: canonical SID_RANGE/SID_BETWEEN (after the canonical normalization), and
    layer-A pointers / register ids matched after Unicode and whitespace normalization."""
    out = []
    rsid = re.compile(r"\b(?:" + re.escape(run) + r":)?" + re.escape(batch) + r":\d+\b|\b" + re.escape(batch) + r"#\d+\b")
    for i, r in enumerate(records):
        txt = json.dumps(r, ensure_ascii=False)
        n = c._range_norm(txt)
        if c.SID_RANGE.search(n) or c.SID_BETWEEN.search(n):
            out.append(f"R7-R S3 {label}: record {i}: S-id range")
        if i in layer_a:
            body = _pointer_norm(json.dumps({k: v for k, v in r.items() if k not in ("run_id", "batch_id")}, ensure_ascii=False))
            if POINTER7.search(body) or rsid.search(body):
                out.append(f"R7-R S3 {label}: record {i}: register id or pointer in layer A")
        if quarantine_hits is not None:
            nl, nf = quarantine_hits(txt, label, sets)
            if nl or nf:
                out.append(f"R7-R S3 {label}: record {i}: quarantine hit")
    return out


def empty_violations(obj, claims):
    F = [x.replace("R6 S", "R7-R") for x in r6.empty_violations_v6(obj)]
    if claims:
        F.append(f"R7-R {obj.get('working_label')}: EMPTY label carries {len(claims)} source-requiring claim(s)")
    ts_ = obj.get("timeline_summary") or {}
    if any(ts_.get(k) for k in ("later_support", "later_refinement", "contradicted_by", "rejected_by")) or obj.get("superseded_by_sources"):
        F.append(f"R7-R {obj.get('working_label')}: EMPTY label carries summary relations or lifecycle sources")
    return F


def edge_and_s2_violations(obj, rows):
    lab = obj.get("working_label")
    return [f"R7-R {lab}: {x}" for x in r5.edge_class_violations(obj, rows)] + \
           [x if x.startswith("R7") else f"R7-R {x}" for x in r5.s2_violations(obj, rows)]
