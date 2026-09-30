#!/usr/bin/env python3
"""R7 bounded context UNIVERSE — "what is allowed?" (frozen addendum v2.4 §0, §2, §3, §5.5, §5.8; G-LOG-0088, G-LOG-0089).

Owns: the plan (derivation + hash), the run namespace + legacy digest, the revision/contract binding, discovery (A) and
validation (B) of layer-A objects, the typing registry (F1) and consumer read sets (F2) — both LOADED FROM THE FROZEN
ADDENDUM, whose sha256 is checked, so the contract stays the sole authority — the claim enumeration, the run input
manifests I(run), the permitted write paths and the agent tool-capability grammar (W8's allowed set).
Depends on no other R7 context. Pure functions; file bytes are read only for hashing declared inputs. No ML.
Failure strings are tagged "R7-U".
"""
import hashlib
import importlib.util
import json
import os
import re

_HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("p3b_s5_r6", os.path.join(_HERE, "p3b_s5_r6.py"))
r6 = importlib.util.module_from_spec(_s)
_s.loader.exec_module(r6)
r5 = r6.r5

REVISION = 7
ADDENDUM_PATH = "prompts/20260926_2400_p3b-agent-contract-r7-addendum.md"
ADDENDUM_SHA256 = "4d4dac6b5684a6ca60384d6654d50d798273e39ac487e5966329ac8926f0cb87"      # v2.8 (package (a)–(g), G-LOG-0106)
# (v2.7 SLICE-VIEW, G-LOG-0104: 9523c712efd8c0e67d701b7e0003b85bf3f683e457d2b15aa2d6e1a3b854b367)
# (v2.6 + AF-1: 4916932802ca9acf68cee404aea6c0a7a725f2874df275386c64a3f7ac155e6e)
# (v2.6 hardened, G-LOG-0100: ead875a22895163f59dfa8797738cfe96f8564dc4d57371ce2f546d8a26fd32d)
# (v2.6 before the AG-1 hardening: 870a595245be478a3c5c618664430559a424ddd73ed99dae312d4bd6cf3b3dd6)
# (v2.5, G-LOG-0093: 63fdda65b02a2b9822984edfac2ab9a65136fe2b7889c2ab53611181072ad939)
# (v2.4, G-LOG-0089: fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674)
# (v2.3, G-LOG-0088: cdbf53cbe531cc92dc5decc9b29496188b9b6e7ffc0e2ec875bc5d4dc4a6f211)
BINARY_DECISIONS_PATH = "audit-p3b/20260928_BINARY-DECISIONS.json"                       # v2.6-DC3 (G-LOG-0099)
BINARY_DECISION_VALUES = ("FALSE-HIT", "NOT-CONSUMED-ESCALATED")
BINARY_DECIDED_METHOD = "BINARY-DECIDED"            # the disposition method of a hit on a file decided FALSE-HIT
SELF_REF_RULE ="own-object location: timeline[<S-id>].<key>, <S-id> ∈ timeline[*].source_id"      # DC-1, addendum §3.3
SELF_REF = re.compile(r"timeline\[(S\d{4})\]\.(order|date_applies_to_file|change_vs_previous)")
REV3_CONTRACT_PATH = "prompts/20260925_1204_p3b-agent-contract-r2.md"
KINDS = r6.KINDS
CLASSES = ("FIRST", "RESTATES", "EXTENDS", "NARROWS", "CHANGES-DEFINITION", "CHANGES-TYPE", "CHANGES-TERM", "CONTRADICTS",
           "RETRACTS", "NOT-COMPARABLE")
WHOLE_NOT_REQUIRED = r6.WHOLE_NOT_REQUIRED
RUN7 = re.compile(r"^(OB\d{4})-R7-L(\d{2})(U(\d{2})|S)?$")
SID = re.compile(r"\bS\d{4}\b")
SOURCE_KEYS = ("source_id", "sources", "supplied_by", "contradicts")
HANDBACK_TOOL = "SubagentHandback"
CATEGORIES = ("CONTRACT", "ADDENDUM", "PLAN", "SLICE", "SLICE-VIEW", "UNIT-RECORDS", "DECLARED-SYNTHESIS-INPUT")
SLICE_VIEW_HEADER = ("# SLICE-VIEW v1 (R7 v2.7): one line per JSON leaf of the frozen slice — <path> TAB <kind> TAB <payload>; "
                     "path = JSON array of keys/indices; kinds: N scalar, E empty container, S k/n string chunk (JSON)")
SLICE_VIEW_CHUNK = 1000                             # characters of a string per chunk line
SLICE_VIEW_MAX_LINE = 8000                          # worst case: 6-character escapes of a full chunk + the path
_VIEW_ESC = {" ": "\\u2028", " ": "\\u2029", "\u0085": "\\u0085"}   # line separators json.dumps leaves raw
UNIT_WRITES = ("file-reading-records.jsonl",)
FINAL_WRITES = ("file-reading-records.jsonl", "objects.jsonl", "register.jsonl", "p1-gap.jsonl", "claim-evidence.json",
                "S3-LINT.json")
LEDGER = "ledger-p3b-r2"
TL_SUMMARY_KEYS = {f"first_{k}" for k in KINDS} | {"later_support", "later_refinement", "contradicted_by", "rejected_by",
                                                   "current_lifecycle"}


def sha(b):
    return hashlib.sha256(b).hexdigest()


def canon(x):
    return json.dumps(x, sort_keys=True, separators=(",", ":"), ensure_ascii=False).encode("utf-8")


def fsha(path):
    with open(path, "rb") as f:
        return sha(f.read())


# ------------------------------------------------------------------ contract tables (registry sovereignty, F1/F2)
def _table(text, begin, end):
    body = text.split(begin)[1].split(end)[0]
    rows = [l for l in body.strip().splitlines() if l.startswith("|")]
    hdr = [c.strip() for c in rows[0].strip("|").split("|")]
    return [dict(zip(hdr, [c.strip() for c in r.strip("|").split("|")])) for r in rows[2:]]


def load_contract(addendum_bytes):
    """-> (registry rows, consumers, violations). The tables are taken from the frozen addendum only."""
    F = []
    if sha(addendum_bytes) != ADDENDUM_SHA256:
        F.append("R7-U contract: the R7 addendum bytes differ from the frozen sha256 (G-LOG-0088)")
    text = addendum_bytes.decode("utf-8")
    reg = _table(text, "<!-- REGISTRY-BEGIN -->", "<!-- REGISTRY-END -->")
    cons = _table(text, "<!-- CONSUMERS-BEGIN -->", "<!-- CONSUMERS-END -->")
    for r in reg:
        r["_rx"] = _path_rx(r["path"])
        r["_disc"] = DISCRIMINATORS.get(r["discriminator"])
        if r["_disc"] is None:
            F.append(f"R7-U contract: registry discriminator without an implemented predicate: {r['discriminator']!r}")
    return reg, cons, F


def load_frozen_contract(cr_root):
    with open(os.path.join(cr_root, ADDENDUM_PATH), "rb") as f:
        return load_contract(f.read())


def _path_rx(p):
    s = re.escape(p)
    s = s.replace(re.escape("<kind>"), "(?:" + "|".join(KINDS) + ")")
    s = s.replace(re.escape("<text>"), r"(?!sources$)[^.\[\]]+")
    for ph in ("<dim>", "<field>"):
        s = s.replace(re.escape(ph), r"[^.\[\]]+")
    return re.compile("^" + s + "$")


def _any_found(el, _parent):
    return isinstance(el, dict) and any(v == "FOUND" for v in (el.get("by_dimension") or {}).values())


# the predicate language of the registry's discriminator column (closed; an unknown text is a contract failure)
DISCRIMINATORS = {
    "—": lambda el, parent: True,
    "change_vs_previous ∈ {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE}":
        lambda el, parent: isinstance(el, dict) and el.get("change_vs_previous") in WHOLE_NOT_REQUIRED,
    "change_vs_previous ∈ {NARROWS, CHANGES-DEFINITION, CHANGES-TYPE, CHANGES-TERM, CONTRADICTS, RETRACTS}":
        lambda el, parent: isinstance(el, dict) and el.get("change_vs_previous") in set(CLASSES) - WHOLE_NOT_REQUIRED,
    "value ∉ BIRTH-UNRESOLVED-*": lambda el, parent: isinstance(el, str) and not el.startswith("BIRTH-UNRESOLVED-"),
    "value ∈ BIRTH-UNRESOLVED-*": lambda el, parent: isinstance(el, str) and el.startswith("BIRTH-UNRESOLVED-"),
    "resolution = FOUND": lambda el, parent: isinstance(parent, dict) and parent.get("resolution") == "FOUND",   # parent =
    # the nearest ancestor that carries the discriminating key (resolution), see type_of
    "resolution = GENUINELY-UNDEFINED":
        lambda el, parent: isinstance(el, dict) and str(el.get("resolution")).startswith("GENUINELY-UNDEFINED"),
    "resolution ∉ {FOUND, GENUINELY-UNDEFINED}":
        lambda el, parent: isinstance(el, dict) and el.get("resolution") != "FOUND"
        and not str(el.get("resolution")).startswith("GENUINELY-UNDEFINED"),
    "any by_dimension = FOUND": _any_found,
    "no by_dimension = FOUND": lambda el, parent: isinstance(el, dict) and not _any_found(el, parent),
    "edge cites a source": lambda el, parent: isinstance(el, dict) and bool(el.get("source_id")),
}


def consumer_violations(reg, cons):
    """F2: every consumer's read set ⊆ {paths typed EVIDENTIARY or DERIVED} (value-discriminated paths allowed on
    their typed branch). A contract test."""
    types = {}
    for r in reg:
        types.setdefault(r["path"], set()).add(r["type"])
    F = []
    for c in cons:
        for p in [x.strip() for x in c["read_set"].split(";")]:
            ts = types.get(p)
            if not ts or not ts & {"EVIDENTIARY", "DERIVED"}:
                F.append(f"R7-U contract: consumer {c['consumer']!r} reads {p!r} typed {sorted(ts or [])}")
    return F


# ------------------------------------------------------------------ A: discovery (schema-independent; presence only)
def discover(obj):
    """[(normalized path, raw path list, value, element, parent_element)] of S-id-bearing strings and of values under a
    source-bearing key (INCLUDING null). Absence cannot be discovered here (that is validation's job)."""
    out = []

    def walk(x, raw, parent):
        if isinstance(x, dict):
            for k, v in x.items():
                p = raw + [k]
                if k in SOURCE_KEYS and not isinstance(v, (dict, list)):
                    out.append((norm(p), p, v, x, parent))
                walk(v, p, x)
        elif isinstance(x, list):
            for i, v in enumerate(x):
                walk(v, raw + [i], parent)
        elif isinstance(x, str) and SID.search(x):
            if raw and raw[-1] in SOURCE_KEYS:
                return                                     # already recorded as a source-key value
            out.append((norm(raw), raw, x, None, parent))
    walk(obj, [], None)
    return out


def norm(raw):
    """Normalized path; a '.' inside a key (e.g. status_basis key 'births.formal') is written '·' so that key
    boundaries survive and a registry placeholder matches exactly one key."""
    s = ""
    for k in raw:
        if isinstance(k, int):
            s += "[*]"
        else:
            k = str(k).replace(".", "·")
            s += "." + k if s else k
    return s


def _get(obj, raw):
    x = obj
    for k in raw:
        x = x[k]
    return x


def type_of(reg, obj, raw):
    """The registry row for the location `raw` (or its owning element when `raw` ends in a source key), or None.
    Type(p, v) = Registry(p, disc_p(v)) — exactly one row must match."""
    cands = [raw]
    if raw and raw[-1] in SOURCE_KEYS:
        cands.append(raw[:-1])
    for rp in cands:
        p = norm(rp)
        el = _get(obj, rp)
        parent = _get(obj, rp[:-1]) if len(rp) >= 1 else None
        for j in range(len(rp) - 1, 0, -1):                  # nearest ancestor carrying `resolution` (absences rows)
            a = _get(obj, rp[:j])
            if isinstance(a, dict) and "resolution" in a:
                parent = a
                break
        hits = [r for r in reg if r["_rx"].match(p) and r["_disc"] and r["_disc"](el, parent)]
        if len(hits) == 1:
            return hits[0], rp
        if len(hits) > 1:
            return "AMBIGUOUS", rp
    return None, raw


# ------------------------------------------------------------------ B: validation (closed schema Σ, R7 part)
def validation_violations(obj):
    """R7 schema Σ on top of the historical key-level SCHEMA gates: required + non-null sources, element types, closed
    enums, cardinality and the uniqueness keys K (a duplicate FAILS whether or not it agrees; no last-wins)."""
    lab = obj.get("working_label")
    F = []
    tl = obj.get("timeline")
    if tl is not None and not isinstance(tl, list):
        F.append("timeline is not a list")
        tl = []
    seen = set()
    for i, p in enumerate(tl or []):
        if not isinstance(p, dict):
            F.append(f"timeline[{i}] is not an object")
            continue
        if "source_id" not in p:
            F.append(f"timeline[{i}] without source_id (required)")
        elif not (isinstance(p["source_id"], str) and SID.fullmatch(p["source_id"])):
            F.append(f"timeline[{i}].source_id {p['source_id']!r} is not an S-id (required, non-null)")
        for k in ("change_vs_previous",):
            if k not in p:
                F.append(f"timeline[{i}] without {k} (required)")
            elif p[k] not in CLASSES:
                F.append(f"timeline[{i}].{k} {p[k]!r} outside the closed enum")
        s = p.get("source_id")
        if s in seen:
            F.append(f"timeline: duplicate point for {s} (cardinality 1 per source)")
        seen.add(s)
    ts_ = obj.get("timeline_summary")
    if ts_ is not None:
        if not isinstance(ts_, dict):
            F.append("timeline_summary is not an object")
        else:
            if set(ts_) - TL_SUMMARY_KEYS:
                F.append(f"timeline_summary: unknown keys {sorted(set(ts_) - TL_SUMMARY_KEYS)}")
            for fld in ("later_support", "later_refinement", "rejected_by"):
                v = ts_.get(fld, [])
                if not isinstance(v, list) or any(not (isinstance(x, str) and SID.fullmatch(x)) for x in v):
                    F.append(f"timeline_summary.{fld} must be a list of S-ids")
                elif len(v) != len(set(v)):
                    F.append(f"timeline_summary.{fld}: duplicate entry")
            cb = ts_.get("contradicted_by", [])
            if not isinstance(cb, list):
                F.append("timeline_summary.contradicted_by is not a list")
                cb = []
            pairs = []
            for j, e in enumerate(cb):
                if not isinstance(e, dict) or set(e) != {"source_id", "contradicts"}:
                    F.append(f"timeline_summary.contradicted_by[{j}] must be exactly {{source_id, contradicts}}")
                    continue
                if not all(isinstance(e[k], str) and SID.fullmatch(e[k]) for k in e):
                    F.append(f"timeline_summary.contradicted_by[{j}]: non-S-id member")
                pairs.append((e.get("source_id"), e.get("contradicts")))
            if len(pairs) != len(set(pairs)):
                F.append("timeline_summary.contradicted_by: duplicate relation")
    for j, e in enumerate(obj.get("superseded_by_sources") or []):
        if not isinstance(e, dict) or set(e) != {"source_id", "position"} or not SID.fullmatch(str(e.get("source_id"))):
            F.append(f"superseded_by_sources[{j}] must be exactly {{source_id: S-id, position}}")
    srcs = [e.get("source_id") for e in obj.get("superseded_by_sources") or [] if isinstance(e, dict)]
    if len(srcs) != len(set(srcs)):
        F.append("superseded_by_sources: duplicate source")
    for key in ("d4", "d5"):
        for j, e in enumerate((obj.get("semantic_evidence") or {}).get(key) or []):
            if not isinstance(e, dict) or not SID.fullmatch(str(e.get("source_id"))) or not e.get("quote"):
                F.append(f"semantic_evidence.{key}[{j}] needs source_id (S-id) and quote")
    return [f"R7-U {lab}: {x}" for x in F]


def typing_violations(reg, obj):
    """A-closure: every discovered location must be typed by exactly one registry row."""
    lab = obj.get("working_label")
    F = []
    for p, raw, v, el, parent in discover(obj):
        row, _ = type_of(reg, obj, raw)
        if row is None:
            F.append(f"R7-U {lab}: untyped S-id-bearing location {p!r} (default-deny)")
        elif row == "AMBIGUOUS":
            F.append(f"R7-U {lab}: location {p!r} matches more than one registry row")
        elif row["type"] == "NONE":
            F.append(f"R7-U {lab}: S-id at {p!r}, which is typed NONE")
        elif row["type"] == "EVIDENTIARY" and v is None and not row["kind_or_rule"].startswith("ABSENCE-CENSUS"):
            F.append(f"R7-U {lab}: null source at evidentiary location {p!r}")        # a negative census claim has no source
    return F


def meta_violations(reg, obj):
    """The rules of META rows (a META mention has no force, §3.0 INV-LEX; only its row's rule constrains it):
    '⊆ verified cited sources' — its S-ids must be ⊆ the sources of the object's claims (the Evidence context then
    verifies those claims); the DC-1 self-reference rule (v2.4) — the value must be exactly an own timeline location.
    META rows with rule '—' carry no rule."""
    lab = obj.get("working_label")
    cited = {c["source"] for c in claims(reg, obj)}
    points = {p.get("source_id") for p in obj.get("timeline") or [] if isinstance(p, dict)}
    F = []
    for p, raw, v, el, parent in discover(obj):
        row, _ = type_of(reg, obj, raw)
        if not (isinstance(row, dict) and row["type"] == "META"):
            continue
        if row["kind_or_rule"] == "⊆ verified cited sources":
            extra = set(SID.findall(str(v))) - cited
            if extra:
                F.append(f"R7-U {lab}: {p} names {sorted(extra)} outside the object's cited sources")
        elif row["kind_or_rule"] == SELF_REF_RULE:
            m = SELF_REF.fullmatch(str(v))
            if not m or m.group(1) not in points:
                F.append(f"R7-U {lab}: {p} {str(v)[:80]!r} is not an own-object location")
    return F


# ------------------------------------------------------------------ claim enumeration (from typed locations)
def claims(reg, obj):
    """The verifier's own claim set: [{id, kind, source, fields, whole, target?}] derived from the registry-typed
    elements (never from a submitted list). SUMMARY entries inherit the referenced timeline point (no own claim)."""
    out, lab = [], obj.get("working_label")
    tl = {p.get("source_id"): p for p in obj.get("timeline") or [] if isinstance(p, dict)}
    for s, p in tl.items():
        if s:
            out.append({"id": f"timeline:{s}", "kind": "TIMELINE", "source": s, "fields": {},
                        "whole": p.get("change_vs_previous") not in WHOLE_NOT_REQUIRED})
    for k, v in (obj.get("births") or {}).items():
        for s in SID.findall(str(v)):
            out.append({"id": f"birth:{k}:{s}", "kind": "BIRTH", "source": s, "fields": {"birth_kind": k},
                        "whole": not str(v).startswith("BIRTH-UNRESOLVED-")})
    for e in ((obj.get("timeline_summary") or {}).get("contradicted_by") or []):
        if isinstance(e, dict):
            out.append({"id": f"contra:{e.get('source_id')}:{e.get('contradicts')}", "kind": "CONTRADICTION",
                        "source": e.get("source_id"), "fields": {"contradicts_source": e.get("contradicts")}, "whole": True})
    for e in obj.get("superseded_by_sources") or []:
        if isinstance(e, dict):
            out.append({"id": f"lifecycle:{e.get('source_id')}", "kind": "LIFECYCLE", "source": e.get("source_id"),
                        "fields": {"lifecycle": "SUPERSEDED"}, "whole": True})
    for dim, a in (obj.get("absences") or {}).items():
        if isinstance(a, dict) and a.get("resolution") == "FOUND":
            out.append({"id": f"absence:{dim}", "kind": "ABSENCE", "source": (a.get("supplied_by") or {}).get("source_id"),
                        "fields": {"dimension": dim, "finding": "DEFINES"}, "whole": True})
    for d in obj.get("stage2_dispositions") or []:
        if isinstance(d, dict):
            for dim, v in (d.get("by_dimension") or {}).items():
                if v == "FOUND":
                    out.append({"id": f"stage2:{d.get('source_id')}:{d.get('hit_kind')}:{json.dumps(d.get('hit_key'))}:"
                                      f"{d.get('term_index')}:{dim}", "kind": "ABSENCE", "source": d.get("source_id"),
                                "fields": {"dimension": dim, "finding": "DEFINES"}, "whole": True})
    for e in obj.get("dependency_edges") or []:
        if isinstance(e, dict) and e.get("source_id"):
            out.append({"id": f"edge:{e.get('target_label')}:{e.get('source_id')}", "kind": "DEPENDENCY",
                        "source": e.get("source_id"), "fields": {"target_label": e.get("target_label")}, "whole": False})
    for key, kind in (("d4", "CONTRADICTION"), ("d5", "TWO-CONCEPT")):
        for e in (obj.get("semantic_evidence") or {}).get(key) or []:
            if isinstance(e, dict) and e.get("source_id"):
                out.append({"id": f"{key}:{e['source_id']}", "kind": kind, "source": e["source_id"], "fields": {},
                            "whole": True, "quote": e.get("quote")})
    for e in obj.get("census_reading_disagreements") or []:
        if isinstance(e, dict) and e.get("source_id"):
            out.append({"id": f"census:{e.get('dimension')}:{e['source_id']}", "kind": "CENSUS", "source": e["source_id"],
                        "fields": {"dimension": e.get("dimension")}, "whole": True, "quote": e.get("quote")})
    return out


# ------------------------------------------------------------------ plan (carried over from R6 L; R7 run grammar)
def binary_decisions(raw, want_sha, want_authority):
    """v2.6-DC3 (G-LOG-0099): the governed per-file binary decision records, bound by the manifest header fields
    `r7_binary_decisions_sha256` and `r7_binary_decisions_authority`. -> ({source_id: decision}, {source_id: record},
    violations). ANY violation → NO decision is applied (all-or-nothing: an invalid artifact never partly applies).
    No header binding → no decisions (an undecided unread binary then fails G-04: fail-closed). EXTRACT is not
    executable under v2.6 (no validated extractor)."""
    if want_sha is None:
        return {}, {}, []
    if raw is None:
        return {}, {}, [f"R7-U binary decisions: {BINARY_DECISIONS_PATH} missing although the manifest header binds it"]
    if sha(raw) != want_sha:
        return {}, {}, ["R7-U binary decisions: file bytes ≠ the manifest header r7_binary_decisions_sha256"]
    try:
        doc = json.loads(raw.decode("utf-8"))
        recs = doc["decisions"]
        assert isinstance(recs, list)
    except (ValueError, AttributeError, UnicodeDecodeError, KeyError, TypeError, AssertionError):
        return {}, {}, ["R7-U binary decisions: not valid JSON with a `decisions` list"]
    F = []
    if doc.get("artifact") != "BINARY-DECISIONS":
        F.append(f"R7-U binary decisions: artifact {doc.get('artifact')!r} ≠ 'BINARY-DECISIONS'")
    if not want_authority or doc.get("authority") != want_authority:
        F.append(f"R7-U binary decisions: authority {doc.get('authority')!r} ≠ the header-bound {want_authority!r}")
    if not recs:
        F.append("R7-U binary decisions: the bound artifact contains no decision")
    out, full = {}, {}
    for r in recs:
        r = r if isinstance(r, dict) else {}
        s, d, h = r.get("source_id"), r.get("decision"), r.get("content_sha256")
        why = ("not an S-id" if not (isinstance(s, str) and SID.fullmatch(s)) else "duplicate" if s in out
               else "EXTRACT is not executable (no validated extractor)" if d == "EXTRACT"
               else f"unsupported decision {d!r}" if d not in BINARY_DECISION_VALUES
               else "content_sha256 missing" if not (isinstance(h, str) and re.fullmatch(r"[0-9a-f]{64}", h))
               else "record authority ≠ artifact authority" if r.get("authorized_by") != doc.get("authority") else None)
        if why:
            F.append(f"R7-U binary decisions: record {s!r}: {why}")
            continue
        out[s], full[s] = d, dict(r)
    return ({}, {}, F) if F else (out, full, [])


def binary_decision_integrity(records, contents, required):
    """v2.6-DC3: the decisions agree with the resolver bytes of this batch's required files. A decided file must be one
    the reader REFUSES (r5.is_binary — reader/verifier agreement: a decision must never let an agent skip a text file),
    with the recorded content sha256; a required binary WITHOUT a decision is a missing decision. Decisions for files
    outside this batch are ignored here (other batches)."""
    F = []
    for s in sorted(set(records) & set(required)):
        b = contents.get(s)
        if b is None:
            F.append(f"R7-U binary decisions: {s} decided but its bytes are not available to the verifier")
            continue
        if not r5.is_binary(b):
            F.append(f"R7-U binary decisions: {s} is decided but not binary (the reader would not refuse it)")
        if sha(b) != records[s].get("content_sha256"):
            F.append(f"R7-U binary decisions: {s} bytes ≠ the decided content sha256")
    for s in sorted(set(required) - set(records)):
        if contents.get(s) is not None and r5.is_binary(contents[s]):
            F.append(f"R7-U binary decisions: undecided binary required file {s} ({r5.is_binary(contents[s])})")
    return F


def plan_derive_v7(batch, labels, slices, contents, files_meta, decisions=None):
    p = r6.plan_derive(batch, labels, slices, contents, files_meta)
    txt = canon(p).decode("utf-8").replace(f"{batch}-R6-L", f"{batch}-R7-L")
    p = json.loads(txt)
    p["revision"] = REVISION
    for e in p["labels"].values():                    # v2.6-DC3: the decided binaries among the label's required files
        bd = {s: decisions[s] for s in e["files"] if s in (decisions or {})}
        if bd:
            e["binary_decisions"] = bd
    return p


def plan_violations(declared, derived, manifest_sha):
    F = [x.replace("R6 L", "R7-U plan").replace("r6_plan_sha256", "r7_plan_sha256")
         for x in r6.plan_violations(declared, derived, None, manifest_sha)]
    return F


def run_owner(plan):
    return r6.run_owner(plan)


# ------------------------------------------------------------------ namespace + legacy digest + revision binding
def legacy_digest(ledger_dir, batch, planned, assembly):
    h = hashlib.sha256()
    for d in sorted(os.listdir(ledger_dir)) if os.path.isdir(ledger_dir) else []:
        if not d.startswith(batch + "-") or d in planned or d == assembly:
            continue
        for base, _, fs in sorted(os.walk(os.path.join(ledger_dir, d))):
            for f in sorted(fs):
                p = os.path.join(base, f)
                h.update(f"{os.path.relpath(p, ledger_dir)}\0{fsha(p)}\n".encode())
    return h.hexdigest()


def namespace_violations(ledger_dir, batch, planned, legacy_expected, legacy_dirs_expected):
    """Every <batch>-* directory is a planned run, the assembly, or a frozen legacy directory; legacy digest equal."""
    assembly = f"{batch}-R7"
    F = []
    present = sorted(d for d in os.listdir(ledger_dir) if d.startswith(batch + "-")) if os.path.isdir(ledger_dir) else []
    for d in present:
        if d in planned or d == assembly:
            continue
        if d not in set(legacy_dirs_expected or []):
            F.append(f"R7-U namespace: directory {d} is neither a planned run, the assembly nor a frozen legacy directory")
    if legacy_digest(ledger_dir, batch, planned, assembly) != legacy_expected:
        F.append("R7-U namespace: legacy directories differ from the digest frozen at activation (legacy_ledger_sha256)")
    return F


def revision_violations(mhdr):
    c = mhdr.get("contract") or {}
    F = []
    if type(c.get("revision")) is not int or c.get("revision") != REVISION:
        F.append(f"R7-U binding: manifest contract.revision {c.get('revision')!r} is not the integer 7")
    if c.get("sha256") != ADDENDUM_SHA256:
        F.append("R7-U binding: manifest contract.sha256 is not the frozen R7 addendum sha256")
    if c.get("path") != ADDENDUM_PATH:
        F.append("R7-U binding: manifest contract.path is not the R7 addendum")
    return F


# ------------------------------------------------------------------ I(run): run input manifests (FR-1, Option A)
PROHIBITED = re.compile(r"(^|/)(app|routes|resources|database|tests)/|chronological_knowelgeos_ablation_theory|"
                        r"P3B-HOLDOUT|H-19|holdout", re.I)


def _vj(x):
    s = json.dumps(x, ensure_ascii=False, separators=(",", ":"))
    for k, v in _VIEW_ESC.items():
        s = s.replace(k, v)
    return s


def slice_view(slice_text):
    """EG-2 (R7 v2.7, G-LOG-0104): the deterministic, lossless, multi-line view of a frozen one-line slice. One line per
    JSON leaf in document (canonical, key-sorted) order; long strings as numbered chunks. Every line ≤ SLICE_VIEW_MAX_LINE;
    no raw line-separator code point. The slice and its hash are unchanged; the view is derived and verified."""
    out = [SLICE_VIEW_HEADER]

    def walk(x, path):
        p = _vj(path)
        if isinstance(x, dict) and x:
            for k in x:
                walk(x[k], path + [k])
        elif isinstance(x, list) and x:
            for i, v in enumerate(x):
                walk(v, path + [i])
        elif isinstance(x, (dict, list)):
            out.append(f"{p}\tE\t{_vj(x)}")
        elif isinstance(x, str):
            chunks = [x[i:i + SLICE_VIEW_CHUNK] for i in range(0, len(x), SLICE_VIEW_CHUNK)] or [""]
            for k, ch in enumerate(chunks, 1):
                out.append(f"{p}\tS {k}/{len(chunks)}\t{_vj(ch)}")
        else:
            out.append(f"{p}\tN\t{_vj(x)}")
    walk(json.loads(slice_text), [])
    if max(len(l) for l in out) > SLICE_VIEW_MAX_LINE:
        raise ValueError("slice view line exceeds SLICE_VIEW_MAX_LINE (a key path is too long)")
    return "\n".join(out) + "\n"


def slice_view_parse(view_text):
    """The inverse of slice_view; raises ValueError on any malformed, missing, duplicated or reordered line."""
    lines = view_text.split("\n")
    if len(lines) < 2 or lines[0] != SLICE_VIEW_HEADER or lines[-1] != "":
        raise ValueError("not a SLICE-VIEW v1 text")
    holder, pending = {}, None

    def put(path, val):
        if not path:
            if "root" in holder:
                raise ValueError("duplicate root")
            holder["root"] = val
            return
        if "root" not in holder:
            holder["root"] = [] if isinstance(path[0], int) else {}
        cur = holder["root"]
        for i, seg in enumerate(path):
            last = i == len(path) - 1
            nxt = None if last else ([] if isinstance(path[i + 1], int) else {})
            if isinstance(seg, int) and isinstance(cur, list):
                if seg == len(cur):
                    cur.append(val if last else nxt)
                elif seg != len(cur) - 1 or last:
                    raise ValueError(f"list index out of order at {path}")
                cur = cur[seg]
            elif isinstance(seg, str) and isinstance(cur, dict):
                if last:
                    if seg in cur:
                        raise ValueError(f"duplicate key at {path}")
                    cur[seg] = val
                else:
                    cur = cur.setdefault(seg, nxt)
            else:
                raise ValueError(f"container type conflict at {path}")
            if not last and not isinstance(cur, (dict, list)):
                raise ValueError(f"scalar where a container is required at {path}")

    for line in lines[1:-1]:
        parts = line.split("\t", 2)
        if len(parts) != 3:
            raise ValueError("malformed view line")
        p, kind, payload = parts
        path, val = json.loads(p), json.loads(payload)
        if not isinstance(path, list) or not all(isinstance(s, (int, str)) and not isinstance(s, bool) for s in path):
            raise ValueError("malformed path")
        if kind.startswith("S "):
            k, n = (int(x) for x in kind[2:].split("/"))
            if not isinstance(val, str):
                raise ValueError("string chunk payload is not a string")
            if pending is None:
                if k != 1:
                    raise ValueError("string chunk out of order")
                pending = [path, n, [val]]
            elif pending[0] != path or k != len(pending[2]) + 1 or n != pending[1]:
                raise ValueError("string chunk out of order")
            else:
                pending[2].append(val)
            if len(pending[2]) == pending[1]:
                put(path, "".join(pending[2]))
                pending = None
            continue
        if pending is not None:
            raise ValueError("incomplete string chunks")
        if kind == "E" and val in ({}, []):
            put(path, val)
        elif kind == "N" and (val is None or isinstance(val, (bool, int, float))):
            put(path, val)
        else:
            raise ValueError(f"bad kind {kind!r}")
    if pending is not None or "root" not in holder:
        raise ValueError("incomplete view")
    return holder["root"]


def slice_view_violations(slice_text, view_text, where):
    """R7-U: the SLICE-VIEW must be exactly the derived view of the frozen slice and parse back to it byte-exactly."""
    if view_text is None:
        return [f"R7-U slice view {where}: missing"]
    F = []
    if view_text != slice_view(slice_text):
        F.append(f"R7-U slice view {where}: bytes ≠ the deterministic view of the frozen slice")
    try:
        if canon(slice_view_parse(view_text)).decode("utf-8") != slice_text:
            F.append(f"R7-U slice view {where}: does not parse back to the frozen slice")
    except (ValueError, TypeError) as e:
        F.append(f"R7-U slice view {where}: unparseable ({str(e)[:60]})")
    return F


def input_manifest(root, batch, slice_root, plan, run, persisted="ALLOWED"):
    """Derive I(run) from the plan and the run's role (never from the agent): closed categories, hash-bound."""
    own = run_owner(plan).get(run)
    if own is None:
        return None
    lab, role, _files = own
    entries = [("CONTRACT", REV3_CONTRACT_PATH), ("ADDENDUM", ADDENDUM_PATH),
               ("PLAN", f"{slice_root}/{batch}.R7-PLAN.json"), ("SLICE", f"{slice_root}/{batch}/{lab}.json"),
               ("SLICE-VIEW", f"{slice_root}/{batch}/{lab}.view.txt")]                   # v2.7, EG-2
    if role == "SYNTHESIS":
        for u, d in sorted(plan["labels"][lab]["runs"].items()):
            if d["role"] == "UNIT":
                entries.append(("UNIT-RECORDS", f"{LEDGER}/{u}/file-reading-records.jsonl"))
    out = []
    for cat, p in entries:
        ap = os.path.join(root, p)
        out.append({"path": p, "owner_run": run, "category": cat, "declared_role": role,
                    "sha256": fsha(ap) if os.path.isfile(ap) else None})
    m = {"run": run, "role": role, "entries": out, "persisted_outputs": persisted}
    m["manifest_sha256"] = sha(canon(m))
    return m


def input_manifest_violations(m, plan, batch, slice_root):
    """Hard prohibitions (R7-U preparation failure; reported, never removed) and closed categories."""
    F = []
    own = run_owner(plan).get(m.get("run"))
    if own is None:
        return [f"R7-U inputs: manifest for unplanned run {m.get('run')!r}"]
    lab, role, _ = own
    allowed_units = {f"{LEDGER}/{u}/file-reading-records.jsonl" for u, d in plan["labels"][lab]["runs"].items()
                     if d["role"] == "UNIT"}
    for e in m.get("entries") or []:
        p, cat = e.get("path") or "", e.get("category")
        where = f"R7-U inputs {m['run']}: {p}"
        if cat not in CATEGORIES:
            F.append(f"{where}: category {cat!r} outside the closed enum")
        if PROHIBITED.search(p):
            F.append(f"{where}: prohibited path (hold-out / H-19 / F-Series / application code)")
        if cat == "SLICE" and p != f"{slice_root}/{batch}/{lab}.json":
            F.append(f"{where}: not the run's own label slice")
        if cat == "SLICE-VIEW" and p != f"{slice_root}/{batch}/{lab}.view.txt":
            F.append(f"{where}: not the run's own label slice view")
        if cat == "UNIT-RECORDS" and (role != "SYNTHESIS" or p not in allowed_units):
            F.append(f"{where}: unit records not of the same label's units (or run not the synthesis run)")
        if cat == "PLAN" and p != f"{slice_root}/{batch}.R7-PLAN.json":
            F.append(f"{where}: not the batch's frozen R7 plan")
        if cat in ("CONTRACT", "ADDENDUM") and p not in (REV3_CONTRACT_PATH, ADDENDUM_PATH):
            F.append(f"{where}: not the frozen contract/addendum")
        if cat == "DECLARED-SYNTHESIS-INPUT":
            F.append(f"{where}: DECLARED-SYNTHESIS-INPUT is empty unless the frozen contract lists one (it lists none)")
        if e.get("owner_run") != m["run"]:
            F.append(f"{where}: owner_run ≠ the run")
        if not isinstance(e.get("sha256"), str):
            F.append(f"{where}: entry without sha256 (input absent at dispatch)")
    if m.get("persisted_outputs") not in ("ALLOWED", "NONE"):
        F.append(f"R7-U inputs {m['run']}: persisted_outputs {m.get('persisted_outputs')!r}")
    body = {k: v for k, v in m.items() if k != "manifest_sha256"}
    if sha(canon(body)) != m.get("manifest_sha256"):
        F.append(f"R7-U inputs {m['run']}: manifest_sha256 does not match the manifest")
    return F


def permitted_writes(run, role):
    names = FINAL_WRITES if role in ("SINGLE", "SYNTHESIS") else UNIT_WRITES
    return {f"{LEDGER}/{run}/{n}" for n in names}


def canonical_reader(reader_abs):
    """The one allowed reader invocation form (addendum §5.4): literal values, nothing else in the command."""
    return re.compile(r"^python3 -B " + re.escape(reader_abs) + r" --run (OB\d{4}-R7-L\d{2}(?:U\d{2})?) --batch (OB\d{4}) "
                      r"--label ([A-Za-z0-9][A-Za-z0-9._-]*) --step (1|7|10) --mode bytes --page ([1-9]\d{0,4}) (S\d{4})$")
