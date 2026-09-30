#!/usr/bin/env python3
"""R7 bounded context EVIDENCE — "what source bytes were observed and cited?" (frozen addendum v2.3 §6, W6; HD-5).

Coverage and reading state are derived from WITNESSED corpus-reader reads only (never from the agent READ-LOG, never from
input reads). A fact is evidence only if its quote occurs in the resolver bytes AT A BYTE OFFSET inside a page witnessed
by the fact's owning run (byte-contiguous pages of the same run merge; only the N-WS normalization, with an offset map,
is permitted). Every verifier-enumerated claim must resolve claim → fact → record → witnessed bytes. W6 reconciles the
agent READ-LOG against the witness. Verifier resolver reads here are integrity checks only; they create no Evidence
object. Depends on Witness and Universe (read-only). Failure strings are tagged "R7-E". Semantic entailment (does the
quote SUPPORT the claim?) is outside this context and remains a human-review boundary.
"""
import collections
import hashlib
import importlib.util
import os

_HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("p3b_s5_r7_universe", os.path.join(_HERE, "p3b_s5_r7_universe.py"))
U = importlib.util.module_from_spec(_s)
_s.loader.exec_module(U)
r5 = U.r5

READING_STATES = r5.READING_STATES
FACT_KINDS = {"TIMELINE": ("change_candidate",), "BIRTH": ("birth_kind",), "ABSENCE": ("dimension", "finding"),
              "DEPENDENCY": ("target_label",), "CONTRADICTION": ("contradicts_source",), "TWO-CONCEPT": ("concepts",),
              "LIFECYCLE": ("lifecycle",), "CENSUS": ("dimension",), "OMQ14": ("type",)}
WS = frozenset("\t\n\r ")


# ---------------------------------------------------------------- coverage from the witness
def coverage(reads):
    """{(run, sid): {have: set(pages), n, complete}} from witnessed, recomputation-verified corpus-reader reads."""
    out = {}
    for e in reads:
        if e.get("kind") != "read" or not e.get("verified"):
            continue
        k = (e["run"], e["source_id"])
        c = out.setdefault(k, {"have": set(), "n": e.get("n_pages")})
        c["have"].add(e["page"])
    for c in out.values():
        c["complete"] = bool(c["n"]) and c["have"] == set(range(1, c["n"] + 1))
    return out


def witnessed_intervals(reads, run, sid):
    """Byte intervals of pages of `sid` witnessed by `run`, merged only across byte-contiguous pages."""
    iv = sorted((e["byte_start"], e["byte_end"]) for e in reads
                if e.get("kind") == "read" and e.get("verified", True) and e["run"] == run and e["source_id"] == sid)
    merged = []
    for a, b in iv:
        if merged and merged[-1][1] == a:
            merged[-1] = (merged[-1][0], b)
        elif not merged or a > merged[-1][1]:
            merged.append((a, b))
        else:
            merged[-1] = (merged[-1][0], max(merged[-1][1], b))
    return merged


def _inside(a, b, intervals):
    return any(x <= a and b <= y for x, y in intervals)


def _nws(raw):
    """N-WS: every maximal run of ASCII whitespace → one space, with a map normalized-char index → original byte offset."""
    text = raw.decode("utf-8", errors="strict")
    out, offs, pos, prev_ws = [], [], 0, False
    for ch in text:
        n = len(ch.encode("utf-8"))
        if ch in WS:
            if not prev_ws:
                out.append(" ")
                offs.append((pos, pos + n))
            else:
                offs[-1] = (offs[-1][0], pos + n)
            prev_ws = True
        else:
            out.append(ch)
            offs.append((pos, pos + n))
            prev_ws = False
        pos += n
    return "".join(out), offs


def anchored(quote, raw, intervals):
    """∃ i: bytes[i:i+|q|] = q ∧ [i, i+|q|) ⊆ witnessed intervals — or the same under N-WS via its offset map."""
    if not quote or raw is None or not intervals:
        return False
    q = quote.encode("utf-8")
    i = raw.find(q)
    while i != -1:
        if _inside(i, i + len(q), intervals):
            return True
        i = raw.find(q, i + 1)
    try:
        norm, offs = _nws(raw)
    except UnicodeDecodeError:
        return False
    nq = _nws(q)[0]
    j = norm.find(nq)
    while j != -1 and nq:
        a, b = offs[j][0], offs[j + len(nq) - 1][1]
        if _inside(a, b, intervals):
            return True
        j = norm.find(nq, j + 1)
    return False


# ---------------------------------------------------------------- records and facts
def facts_index(records_by_run, contents, reads):
    """{(run, sid, fact_id): fact + {_anchored}}; anchoring uses only this run's witnessed reader pages."""
    idx = {}
    for run, recs in records_by_run.items():
        for r in recs:
            sid = r.get("source_id")
            iv = witnessed_intervals(reads, run, sid)
            for f in r.get("facts") or []:
                g = dict(f)
                g["_anchored"] = anchored(f.get("quote"), contents.get(sid), iv)
                g["source_id"] = sid
                idx[(run, sid, f.get("fact_id"))] = g
    return idx


def record_violations(run, permitted, recs, cov, contents, reads):
    F = []
    seen = collections.Counter(x.get("source_id") for x in recs)
    if set(seen) != set(permitted) or any(n != 1 for n in seen.values()):
        F.append(f"R7-E {run}: file-reading records ≠ exactly one per permitted file")
    for x in recs:
        sid = x.get("source_id")
        c = cov.get((run, sid))
        st = x.get("reading_state")
        if st not in READING_STATES:
            F.append(f"R7-E {run} {sid}: reading_state {st!r} off-scale")
        if st == "WHOLE-FILE" and not (c and c["complete"]):
            F.append(f"R7-E {run} {sid}: WHOLE-FILE without complete witnessed coverage in the owning run")
        if st == "WHOLE-FILE" and c and c["complete"] and contents.get(sid) is not None:
            exp = [r5.ack_token(hashlib.sha256(contents[sid][a:e]).hexdigest()) for a, e in r5.byte_pages(contents[sid])]
            if sorted(x.get("ack_tokens") or []) != sorted(exp):
                F.append(f"R7-E {run} {sid}: acknowledgement tokens ≠ the witnessed page tokens (consistency check)")
        ids = collections.Counter(f.get("fact_id") for f in x.get("facts") or [])
        if any(n > 1 for n in ids.values()) or None in ids:
            F.append(f"R7-E {run} {sid}: missing or duplicate fact_id")
        iv = witnessed_intervals(reads, run, sid)
        for f in x.get("facts") or []:
            k = f.get("kind")
            if k not in FACT_KINDS:
                F.append(f"R7-E {run} {sid} fact {f.get('fact_id')!r}: kind {k!r} outside the closed fact kinds")
                continue
            if any(not f.get(fld) for fld in FACT_KINDS[k]):
                F.append(f"R7-E {run} {sid} fact {f.get('fact_id')!r}: missing field(s)")
            if not anchored(f.get("quote"), contents.get(sid), iv):
                F.append(f"R7-E {run} {sid} fact {f.get('fact_id')!r}: quote not anchored on a page witnessed by the owning run")
    return F


def claim_violations(claims, claim_map, idx, states, cov, reading_runs, label):
    """Every enumerated claim resolves to ≥1 valid ref: a fact of the right kind and fields, in a reading run of the
    label, anchored on that run's witnessed bytes, whole-file where required."""
    F = []
    for cl in claims:
        refs = (claim_map or {}).get(cl["id"]) or []
        good = False
        for ref in refs:
            run, sid, fid = ref.get("run"), ref.get("source_id"), ref.get("fact_id")
            f = idx.get((run, sid, fid))
            if run not in reading_runs or f is None or sid != cl["source"] or f.get("kind") != cl["kind"]:
                continue
            if any(f.get(k) != v for k, v in cl["fields"].items()) or not f.get("_anchored"):
                continue
            if cl["whole"] and not (states.get(sid) == "WHOLE-FILE" and (cov.get((run, sid)) or {}).get("complete")):
                continue
            good = True
            break
        if not good:
            F.append(f"R7-E {label}: claim {cl['id']} has no valid evidence path (claim → fact → record → witnessed bytes)")
    return F


def quote_violations(obj, contents, reads, reading_runs):
    """Object-level QUOTE locations must be anchored on bytes witnessed by a reading run of the label."""
    F, lab = [], obj.get("working_label")
    qs = []
    for p in obj.get("timeline") or []:
        if isinstance(p, dict) and (p.get("states") or {}).get("quote"):
            qs.append((p.get("source_id"), p["states"]["quote"], "timeline"))
    for dim, a in (obj.get("absences") or {}).items():
        sb = (a or {}).get("supplied_by") if isinstance(a, dict) else None
        if isinstance(sb, dict) and sb.get("quote") and a.get("resolution") == "FOUND":
            qs.append((sb.get("source_id"), sb["quote"], f"absences.{dim}"))
    for e in obj.get("dependency_edges") or []:
        if isinstance(e, dict) and e.get("quote"):
            qs.append((e.get("source_id"), e["quote"], "dependency_edges"))
    for key in ("d4", "d5"):
        for e in (obj.get("semantic_evidence") or {}).get(key) or []:
            if isinstance(e, dict):
                qs.append((e.get("source_id"), e.get("quote"), f"semantic_evidence.{key}"))
    for e in obj.get("census_reading_disagreements") or []:
        if isinstance(e, dict) and e.get("quote"):
            qs.append((e.get("source_id"), e["quote"], "census_reading_disagreements"))
    for sid, q, where in qs:
        if not any(anchored(q, contents.get(sid), witnessed_intervals(reads, r, sid)) for r in reading_runs):
            F.append(f"R7-E {lab}: {where} quote for {sid} is not anchored on witnessed bytes")
    return F


def reading_rule_violations(obj, states, claims, decided_false_hit=frozenset()):
    """Whole-file claims need WHOLE-FILE sources; every non-WHOLE-FILE required file needs a CONTRACT-DEVIATION
    escalation naming it (carried over from R6). v2.6-DC3: a CONFORMING file decided FALSE-HIT needs none (its decision
    record is the justification)."""
    lab = obj.get("working_label")
    F = [f"R7-E {lab}: {c['id']} requires whole-file evidence but {c['source']} is {states.get(c['source'])}"
         for c in claims if c["whole"] and states.get(c["source"]) not in (None, "WHOLE-FILE")]
    cd = {m for e in obj.get("escalations") or [] if isinstance(e, dict) and e.get("reason") == "CONTRACT-DEVIATION"
          for m in U.SID.findall(str(e.get("detail") or ""))}
    F += [f"R7-E {lab}: {s} is {st} without a CONTRACT-DEVIATION escalation naming it"
          for s, st in sorted(states.items()) if st != "WHOLE-FILE" and s not in cd and s not in decided_false_hit]
    return F


def binary_decision_violations(obj, decided, states, reads, reading_runs, claims, stage2_files=None):
    """v2.6-DC3 B-form (G-LOG-0099): each decided binary of the label (from the frozen plan) must be never read, have a
    NOT-CONSUMED record, not be a claim source, and be disposed exactly as decided — FALSE-HIT: method BINARY-DECIDED and
    every dimension FALSE-HIT; NOT-CONSUMED-ESCALATED: that method, every dimension ESCALATED, and a CONTRACT-DEVIATION
    escalation naming the file. -> (failures, conforming files, conforming FALSE-HIT files)."""
    lab, F, ok, ok_fh = obj.get("working_label"), [], set(), set()
    cd = {m for e in obj.get("escalations") or [] if isinstance(e, dict) and e.get("reason") == "CONTRACT-DEVIATION"
          for m in U.SID.findall(str(e.get("detail") or ""))}
    cited = {c["source"] for c in claims}
    for s, dec in sorted(decided.items()):
        bad = []
        if any(r.get("source_id") == s and r.get("run") in reading_runs for r in reads):
            bad.append("read although decided")
        if states.get(s) != "NOT-CONSUMED":
            bad.append(f"record state {states.get(s)!r} ≠ NOT-CONSUMED")
        if s in cited:
            bad.append("cited as a claim source")
        ds = [d for d in obj.get("stage2_dispositions") or [] if isinstance(d, dict) and d.get("source_id") == s]
        if not ds and (stage2_files is None or s in stage2_files):   # AF-1: a row-only decided file has no hit to dispose
            bad.append("no disposition")
        want_m, want_v = ((U.BINARY_DECIDED_METHOD, "FALSE-HIT") if dec == "FALSE-HIT"
                          else ("NOT-CONSUMED-ESCALATED", "ESCALATED"))
        for d in ds:
            vals = set((d.get("by_dimension") or {}).values())
            if d.get("method") != want_m or not vals or vals != {want_v}:
                bad.append(f"disposition {d.get('method')!r}/{sorted(vals)} ≠ {want_m}/{want_v}")
        if dec == "NOT-CONSUMED-ESCALATED" and s not in cd:
            bad.append("no CONTRACT-DEVIATION escalation naming it")
        if bad:
            F.append(f"R7-E {lab}: binary decision {dec} for {s} not honoured: {'; '.join(sorted(set(bad)))}")
        else:
            ok.add(s)
            if dec == "FALSE-HIT":
                ok_fh.add(s)
    return F, ok, ok_fh


def census_violations(obj, states, cov, idx, reading_runs, decided_false_hit=frozenset()):
    F, lab = [], obj.get("working_label")
    for dim, a in (obj.get("absences") or {}).items():
        if isinstance(a, dict) and str(a.get("resolution")).startswith("GENUINELY-UNDEFINED"):
            incomplete = [s for s, st in states.items() if st != "WHOLE-FILE" and s not in decided_false_hit]
            if incomplete:
                F.append(f"R7-E {lab}: absences.{dim} GENUINELY-UNDEFINED with an incomplete census ({len(incomplete)} file(s))")
            if any(f.get("kind") == "ABSENCE" and f.get("dimension") == dim and f.get("finding") == "DEFINES"
                   for (run, _, _), f in idx.items() if run in reading_runs):
                F.append(f"R7-E {lab}: absences.{dim} GENUINELY-UNDEFINED although a witnessed fact DEFINES it")
    return F


# ---------------------------------------------------------------- W6: READ-LOG reconciliation
def readlog_violations(witness_reads, readlog_entries, witness_refusals):
    w = collections.Counter((e["run"], e["source_id"], e["page"], e["page_sha256"]) for e in witness_reads
                            if e.get("kind") == "read")
    lg = collections.Counter((x.get("run_id"), (x.get("source_ids") or [None])[0], (x.get("page") or {}).get("page"),
                              (x.get("page") or {}).get("page_sha256"))
                             for x in readlog_entries if not x.get("refused") and isinstance(x.get("page"), dict))
    F = [f"R7-E W6: witnessed read missing from READ-LOG: {k[0]} {k[1]} p{k[2]}" for k in sorted((w - lg).keys(), key=str)]
    F += [f"R7-E W6: READ-LOG read with no witnessed call: {k[0]} {k[1]} p{k[2]}" for k in sorted((lg - w).keys(), key=str)]
    lref = sum(1 for x in readlog_entries if x.get("refused"))
    if lref != witness_refusals:
        F.append(f"R7-E W6: refused calls in READ-LOG ({lref}) ≠ witnessed refusals ({witness_refusals})")
    return F
