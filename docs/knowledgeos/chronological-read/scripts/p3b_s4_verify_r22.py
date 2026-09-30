#!/usr/bin/env python3
"""P3b S4 R2.2 VERIFIER — run S4-PILOT-R2-002 (PB02–PB05 re-run under core v1.6.6). Read-only on the corpus: it reads
batch outputs, slices, the reader log, the seal and 02-FILES; never a corpus file. Exit 1 if any batch fails.

Checks per batch (§20; any FAIL fails the batch):
  SLICES  every slice equals its manifest hash.
  SCHEMA  object / register / P1-gap records carry the contract's section-E keys.
  G-01    every cited S-id exists in 02-FILES; no FIREWALL-LIMITED id is cited.
  G-02    exactly the assigned labels, once each.
  G-04    every DIMENSION resolved; exactly one `stage2_dispositions` entry per stage-1 hit, keyed (source_id,
          hit_kind, hit_key, term_index); each entry's `by_dimension` covers every DIMENSION; GENUINELY-UNDEFINED with
          hits only if all its hit values are FALSE-HIT or UNSUPPLIED-DIMENSION; a FOUND resolution has a FOUND hit.
  G-05    closed values (contract section D), incl. births by full-match patterns per birth kind.
  G-07    provenance ids on every record (run_id, batch_id, contract_sha256, input_manifest_sha256 on objects;
          model_id; generation_parameters).
  G-08    no FOUND and no ESTABLISHED/MOVED birth rests solely on a SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or
          unknown-provenance file (02-FILES; MOVED parsed on its first bracket argument); a FOUND needs a supplied_by
          with source, anchor and quote (§11.2).
  G-09    no STATUS field; register lifecycle within the closed list; HYPOTHESIS / STRUCTURE-CANDIDATE profile;
          test_plan_sha256 at TEST-DEFINED; PROPOSED:* topics defined; checklist_examined when in_checklist.
  G-11    record_status PROPOSED.
  G-12    no research-record id (full or short form) and no register pointer in any object-record field (values of
          `quote` fields are excluded from the pointer check).
  v1.6.6  no FOUND on a NEGATIVE-CENSUS dimension; an ESTABLISHED birth on an MTIME file outside a BULK block needs a
          CONFIRMED timeline point (else BIRTH-UNRESOLVED-MTIME-ONLY); a FOUND source has a whole-file step-7 read by
          the reader (not the scanner); a STAGE-2A-2B disposition has a matching scanner log entry and a term of
          ≤ 2 code points. census_reading_disagreements are counted and reported.
  AUDIT   cited S-ids in slices or non-refused reads of the batch; no hold-out id; stage-2 coverage (every
          stage2_files entry read at step 7 by the reader or the scanner).

Usage:  python3 p3b_s4_verify_r22.py [--write]      (--write stores audit-p3b/S4-R22-VERIFY.json)
"""
import collections
import glob
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("prep", os.path.join(_HERE, "p3b_s4_prepare_r22.py"))
prep = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(prep)
CR, RUN, s3 = prep.CR, prep.RUN_ID, prep.s3
OUT_DIR = os.path.join(CR, "ledger-p3b-r2", "S4-R22")
SID = re.compile(r"\bS\d{4}\b")
RSID = re.compile(r"\b(?:S4-PILOT-R2-\d{3}:)?PB\d{2}:\d+\b")
POINTER = re.compile(r"\bsee (the )?register\b|\bresearch register\b|\bregister (record|id|entry)\b|\brs_id\b|"
                     r"\bregister (OBSERVATION|GAP|HYPOTHESIS|STRUCTURE-CANDIDATE|SCHEMA-LIMITATION|METHODOLOGICAL-DEFICIENCY|SUGGESTION-\w+)\b", re.I)
WEAK = {"SECONDARY-SYNTHESIS", "PROVENANCE-UNRESOLVED"}
KINDS = ("lexical", "conceptual", "formal", "operational", "governance")
E = prep.ENUMS
UNDEF_OK = {"FALSE-HIT", "UNSUPPLIED-DIMENSION"}


def birth_ok(kind, v):
    pats = [rf"ESTABLISHED-{kind.upper()}-BIRTH\[S\d{{4}}\]", r"MOVED\[S\d{4}, .+\]", r"UNORDERED-BLOCK\[BULK-\d+\]",
            r"BIRTH-UNRESOLVED-MTIME-ONLY\[S\d{4}\]", r"ESCALATED\[TIMESTAMP-ANOMALY: .+\]",
            r"ESCALATED\[G-08: sole basis S\d{4} is (SECONDARY-SYNTHESIS|PROVENANCE-UNRESOLVED|UNKNOWN)\]", r"NOT-EVIDENCED-IN-CAPTURE"]
    return isinstance(v, str) and any(re.fullmatch(p, v, re.S) for p in pats)


def birth_basis(v):
    m = re.match(r"(?:ESTABLISHED-[A-Z]+-BIRTH|MOVED)\[(S\d{4})", v or "")
    return m.group(1) if m else None


def strip_quotes(x):
    if isinstance(x, dict):
        return {k: (None if k == "quote" else strip_quotes(v)) for k, v in x.items()}
    if isinstance(x, list):
        return [strip_quotes(v) for v in x]
    return x


def jl(path):
    out = []
    if os.path.exists(path):
        for n, l in enumerate(open(path, encoding="utf-8"), 1):
            if l.strip():
                try:
                    out.append(json.loads(l))
                except json.JSONDecodeError as e:
                    out.append({"__parse_error__": f"{os.path.basename(path)}:{n}: {e}"})
    return out


def main():
    write = "--write" in sys.argv[1:]
    manifest = json.load(open(os.path.join(CR, prep.OUT_MANIFEST)))
    seal = json.load(open(os.path.join(CR, "P3B-HOLDOUT-SEAL.json")))
    HF = set(seal["holdout_files"])
    files = {json.loads(l)["source_id"]: json.loads(l) for l in open(os.path.join(CR, "02-FILES.jsonl")) if l.strip()}
    prov = lambda s: files.get(s, {}).get("provenance") or "UNKNOWN"
    firewall = {s for s, f in files.items() if f["status"] == "FIREWALL-LIMITED"}
    readlog = jl(os.path.join(CR, "ledger-p3b-r2", RUN, "READ-LOG.jsonl"))
    r1 = {}
    for fp in sorted(glob.glob(os.path.join(CR, "ledger-p3b", "OB000[123]", "objects.jsonl"))):
        for o in jl(fp):
            r1[o.get("working_label")] = o
    obj_keys = [k for k in prep.SCHEMA["object_record"] if k != "checklist_examined"]
    reg_keys = [k for k in prep.SCHEMA["register_record"] if not k.startswith("profile") and k != "proposed_topic_definitions"]
    gap_keys = list(prep.SCHEMA["p1_gap_record"])
    dup_keys_total, step_label_notes = [0], []
    report, disp_total, agree, disagreements = {"run_id": RUN, "batches": {}}, collections.Counter(), collections.defaultdict(lambda: [0, 0]), 0
    for batch, labels in sorted(manifest["batches"].items()):
        F = []
        sl_labels = set()
        bdir = os.path.join(OUT_DIR, batch)
        objs, reg, gap = (jl(os.path.join(bdir, n)) for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl"))
        F += ["parse " + r["__parse_error__"] for r in objs + reg + gap if "__parse_error__" in r]
        slices, slice_text = {}, {}
        for l in labels:
            raw = open(os.path.join(CR, prep.SLICE_DIR, f"{l}.json"), encoding="utf-8").read().rstrip("\n")
            slice_text[l], slices[l] = raw, json.loads(raw)
            if s3.sha256_bytes(raw.encode()) != manifest["slices"][l]:
                F.append(f"SLICES {l}: hash differs from the manifest")
        blog = [e for e in readlog if e.get("batch_id") == batch and not e.get("refused")]
        sl_labels = {r.get("working_label") for r in reg if isinstance(r, dict) and r.get("kind") == "SCHEMA-LIMITATION"}
        if sorted(o.get("working_label") for o in objs if "__parse_error__" not in o) != sorted(labels):
            F.append("G-02 label set differs from assignment")
        for o in objs:
            lab = o.get("working_label")
            if lab not in slices:
                continue
            sl = slices[lab]
            miss = [k for k in obj_keys if k not in o]
            if miss:
                F.append(f"SCHEMA {lab}: missing {miss}")
            esc = {x.get("field") for x in o.get("escalations") or [] if isinstance(x, dict)}
            if esc and lab not in sl_labels:
                F.append(f"G-09 {lab}: escalations without a SCHEMA-LIMITATION record")
            for fld in ("type_status", "mathematical_status"):
                v = o.get(fld)
                if v not in E[fld] or (v is None and fld not in esc):
                    F.append(f"G-05 {lab}: {fld}={v!r}" + (" (null without escalation)" if v is None else ""))
            for fld, key in (("primary_layer", "primary_layer"), ("record_status", "record_status"), ("tier", "tier")):
                if o.get(fld) not in E[key]:
                    F.append(f"G-05 {lab}: {fld}={o.get(fld)!r}")
            if o.get("semantic_status") is not None:
                F.append(f"G-05 {lab}: semantic_status must be null")
            for role in o.get("secondary_roles") or []:
                if role not in E["secondary_roles[]"]:
                    F.append(f"G-05 {lab}: secondary_role {role!r}")
            for ed in o.get("dependency_edges") or []:
                if not isinstance(ed, dict) or ed.get("kind") not in E["dependency_edges[].kind"]:
                    F.append(f"G-05 {lab}: edge {str(ed)[:60]}")
            for tp in o.get("timeline") or []:
                for fld, key in (("order", "timeline[].order"), ("date_applies_to_file", "timeline[].date_applies_to_file"),
                                 ("change_vs_previous", "timeline[].change_vs_previous")):
                    if not isinstance(tp, dict):
                        continue
                    v = tp.get(fld)
                    escaped = v is None and f"timeline[{tp.get('source_id')}].{fld}" in esc   # run-level rule (b), G-LOG-0023
                    if v not in E[key] and not escaped:
                        F.append(f"G-05 {lab}: timeline {fld}={v!r}")
            births = o.get("births") or {}
            if set(births) != set(KINDS):
                F.append(f"SCHEMA {lab}: births keys {sorted(births)}")
            confirmed = {tp.get("source_id") for tp in o.get("timeline") or [] if isinstance(tp, dict) and tp.get("date_applies_to_file") == "CONFIRMED"}
            for kind in KINDS:
                v = births.get(kind)
                if not birth_ok(kind, v):
                    F.append(f"G-05 {lab}: births.{kind}={str(v)[:70]!r}")
                s = birth_basis(v)
                if s and prov(s) in WEAK | {"UNKNOWN"}:
                    F.append(f"G-08 {lab}: birth {kind} rests on {s} ({prov(s)})")
                if s and str(v).startswith("ESTABLISHED") and s not in confirmed:
                    mt = files.get(s, {}).get("best_historical_date_basis") == "MTIME" and not str(files[s].get("mtime_block") or "").startswith("BULK-")
                    F.append(f"§14.2 {lab}: ESTABLISHED birth {kind} on {s} without a CONFIRMED date "
                             f"(→ {'BIRTH-UNRESOLVED-MTIME-ONLY' if mt else 'ESCALATED[TIMESTAMP-ANOMALY]'})")
                g8 = re.fullmatch(r"ESCALATED\[G-08: sole basis (S\d{4}) is (\S+)\]", str(v))
                if g8 and not (g8.group(2) == "UNKNOWN" and g8.group(1) not in sl.get("source_meta", {})) \
                        and g8.group(2) != prov(g8.group(1)):
                    F.append(f"G-08 {lab}: births.{kind} names {g8.group(1)} as {g8.group(2)}, 02-FILES says {prov(g8.group(1))}")
            if (o.get("run_id"), o.get("batch_id"), o.get("contract_sha256"), o.get("input_manifest_sha256")) != \
                    (RUN, batch, sl["contract_sha256"], sl["input_manifest_sha256"]) or not o.get("model_id") or "generation_parameters" not in o:
                F.append(f"G-07 {lab}: provenance ids")
            if any(k in o for k in ("status", "STATUS", "corpus_status")):
                F.append(f"G-09 {lab}: STATUS field")
            if sl.get("in_checklist") and not o.get("checklist_examined"):
                F.append(f"G-09 {lab}: checklist_examined missing (in_checklist)")
            txt, ptxt = json.dumps(o, ensure_ascii=False), json.dumps(strip_quotes(o), ensure_ascii=False)
            m = RSID.search(txt) or POINTER.search(ptxt)
            if m:
                F.append(f"G-12 {lab}: register id or pointer in layer A ({m.group(0)!r})")
            # G-04 / v1.6.6 on dispositions
            dims = {r["dimension"]: r for r in sl["search_records"] if r["record"] == "DIMENSION"}
            lh = [r for r in sl["search_records"] if r["record"] == "LABEL-HITS"][0]
            terms = lh["terms"]
            expect = collections.Counter()
            for s, hits in lh["raw_hits"].items():
                for off, ti in hits:
                    expect[(s, "raw", json.dumps(off), ti)] += 1
            for s, hits in lh["ledger_hits"].items():
                for anc, ti in hits:
                    expect[(s, "ledger", json.dumps(anc), ti)] += 1
            got, found_hits, dim_vals = collections.Counter(), collections.defaultdict(set), collections.defaultdict(list)
            scans = {(s, e.get("term")) for e in blog if e.get("working_label") == lab and e.get("tool") for s in e.get("source_ids", [])}
            whole = {s for e in blog if e.get("working_label") == lab and not e.get("tool") and not e.get("refused") and e.get("step") in (1, 7) for s in e.get("source_ids", [])}
            whole7 = {s for e in blog if e.get("working_label") == lab and e.get("step") == 7 and not e.get("tool") for s in e.get("source_ids", [])}
            for d in o.get("stage2_dispositions") or []:
                if not isinstance(d, dict):
                    F.append(f"SCHEMA {lab}: stage2 entry not an object")
                    continue
                got[(d.get("source_id"), d.get("hit_kind"), json.dumps(d.get("hit_key")), d.get("term_index"))] += 1
                bd = d.get("by_dimension") or {}
                if set(bd) != set(dims):
                    F.append(f"G-04 {lab}: hit {d.get('source_id')}@{d.get('hit_key')} by_dimension keys ≠ dimensions")
                for dim, v in bd.items():
                    if v not in E["stage2_dispositions[].by_dimension.<dimension>"]:
                        F.append(f"G-05 {lab}: disposition {v!r}")
                    dim_vals[dim].append(v)
                    disp_total[v] += 1
                    if v == "FOUND":
                        found_hits[dim].add(d.get("source_id"))
                if d.get("method") not in E["stage2_dispositions[].method"]:
                    F.append(f"G-05 {lab}: method {d.get('method')!r}")
                if d.get("method") == "STAGE-2A-2B":
                    if "FOUND" in bd.values():
                        F.append(f"v1.6.6 {lab}: FOUND inside a STAGE-2A-2B entry ({d.get('source_id')}@{d.get('hit_key')})")
                    ti = d.get("term_index")
                    term = terms[ti] if isinstance(ti, int) and 0 <= ti < len(terms) else None
                    if term is None or len(term) > 2:
                        F.append(f"v1.6.6 {lab}: STAGE-2A-2B on a term of more than 2 code points ({term!r})")
                    elif (d.get("source_id"), s3.norm_base(term).strip()) not in scans:
                        F.append(f"v1.6.6 {lab}: STAGE-2A-2B without a scanner log entry ({d.get('source_id')}, {term!r})")
            # a ledger key (source, anchor, term) cannot distinguish repeated occurrences within one row (S3 records one
            # ledger hit per occurrence): each DISTINCT key needs exactly one disposition (R2.2 verification, diagnosed)
            if set(got) != set(expect) or any(c != 1 for c in got.values()):
                F.append(f"G-04 {lab}: dispositions cover {len(set(got) & set(expect))} of {len(set(expect))} distinct hit keys "
                         f"(missing {len(set(expect) - set(got))}, extra {len(set(got) - set(expect))}, repeated {sum(1 for c in got.values() if c != 1)})")
            dup_keys_total[0] += sum(c - 1 for c in expect.values())
            ab = o.get("absences") or {}
            if set(dims) - set(ab):
                F.append(f"G-04 {lab}: unresolved dimensions {sorted(set(dims) - set(ab))[:4]}")
            for dim, a in ab.items():
                if not isinstance(a, dict):
                    F.append(f"SCHEMA {lab}: absences.{dim} not an object")
                    continue
                res = a.get("resolution")
                if res not in E["absences.<dimension>.resolution"]:
                    F.append(f"G-05 {lab}: absences.{dim}.resolution={res!r}")
                neg = dims.get(dim, {}).get("negative_label") == "NEGATIVE-CENSUS"
                if res == "FOUND":
                    sb = a.get("supplied_by")
                    if neg:
                        F.append(f"v1.6.6 {lab}: FOUND on NEGATIVE-CENSUS dimension {dim}")
                    if not (isinstance(sb, dict) and sb.get("source_id") and sb.get("anchor") and sb.get("quote")):
                        F.append(f"G-08/§11.2 {lab}: FOUND {dim} without supplied_by source/anchor/quote")
                    else:
                        s = sb["source_id"]
                        if prov(s) in WEAK | {"UNKNOWN"}:
                            F.append(f"G-08 {lab}: FOUND {dim} rests on {s} ({prov(s)})")
                        if s not in found_hits.get(dim, set()):
                            F.append(f"G-04 {lab}: FOUND {dim} from {s} without a FOUND hit disposition for it")
                        if s not in whole:
                            F.append(f"v1.6.6 {lab}: FOUND {dim} source {s} has no whole-file read by the reader")
                        elif s not in whole7:
                            step_label_notes.append(f"{lab}: FOUND source {s} read whole at step 1, not re-logged at step 7")
                if res == "GENUINELY-UNDEFINED-AFTER-CENSUS" and not neg and any(v not in UNDEF_OK for v in dim_vals.get(dim, [])):
                    F.append(f"G-04 {lab}: GENUINELY-UNDEFINED {dim} but a hit is not FALSE-HIT/UNSUPPLIED")
            disagreements += len(o.get("census_reading_disagreements") or [])
            b1 = r1.get(lab)
            if b1:
                for fld in ("type_status", "mathematical_status", "primary_layer"):
                    agree[fld][1] += 1
                    agree[fld][0] += o.get(fld) == b1.get(fld)
                for kind in KINDS:
                    agree["births." + kind][1] += 1
                    agree["births." + kind][0] += str(births.get(kind)).split("[")[0] == str((b1.get("births") or {}).get(kind)).split("[")[0]
        kinds = collections.Counter()
        for r in reg:
            if "__parse_error__" in r:
                continue
            kinds[r.get("kind")] += 1
            rid = r.get("rs_id")
            if [k for k in reg_keys if k not in r]:
                F.append(f"SCHEMA {rid}: missing {[k for k in reg_keys if k not in r]}")
            for fld, key in (("lifecycle_stage", "lifecycle_stage (register)"), ("lens", "lens (register)"),
                             ("scale", "scale (register)"), ("output_layer", "output_layer (register)")):
                if r.get(fld) not in E[key]:
                    F.append(f"G-05/G-09 {rid}: {fld}={r.get(fld)!r}")
            if (r.get("run_id"), r.get("batch_id"), r.get("contract_sha256")) != (RUN, batch, manifest["contract"]["sha256"]) or not r.get("model_id"):
                F.append(f"G-07 {rid}: provenance ids")
            if any(k in r for k in ("status", "STATUS", "corpus_status")):
                F.append(f"G-09 {rid}: STATUS field")
            if r.get("kind") in ("HYPOTHESIS", "STRUCTURE-CANDIDATE") and r.get("lifecycle_stage") != "TEST-DEFINED":
                F.append(f"G-09 {rid}: {r.get('kind')} not at TEST-DEFINED")
            if r.get("kind") in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"):
                m2 = [k for k in ("falsification_condition", "validation_question", "competing_hypotheses", "contradicting_evidence",
                                  "temporal_scope", "claim_type", "evidence_level") if k not in r]
                if m2:
                    F.append(f"G-09 {rid}: missing {m2}")
            if r.get("lifecycle_stage") == "TEST-DEFINED" and not r.get("test_plan_sha256"):
                F.append(f"G-09 {rid}: TEST-DEFINED without test_plan_sha256")
            defs = r.get("proposed_topic_definitions") or {}
            for tp in r.get("topics") or []:
                if isinstance(tp, str) and tp.startswith("PROPOSED:") and not defs.get(tp):
                    F.append(f"G-12 {rid}: {tp} without a definition")
        for g in gap:
            if "__parse_error__" in g:
                continue
            if [k for k in gap_keys if k not in g]:
                F.append(f"SCHEMA {g.get('gap_id')}: missing {[k for k in gap_keys if k not in g]}")
            if (g.get("run_id"), g.get("batch_id"), g.get("contract_sha256")) != (RUN, batch, manifest["contract"]["sha256"]) or not g.get("model_id"):
                F.append(f"G-07 {g.get('gap_id')}: provenance ids")
        cited = set()
        for r in objs + reg + gap:
            cited |= set(SID.findall(json.dumps(r)))
        slice_ids = set().union(*(set(SID.findall(t)) for t in slice_text.values()))
        read_ok = {s for e in blog for s in e.get("source_ids", [])}
        if cited - slice_ids - read_ok:
            F.append(f"AUDIT cited ids neither in slices nor read: {sorted(cited - slice_ids - read_ok)[:8]}")
        if (cited | read_ok) & HF:
            F.append(f"AUDIT hold-out ids: {sorted((cited | read_ok) & HF)}")
        if [s for s in cited if s not in files]:
            F.append(f"G-01 unknown ids {sorted(s for s in cited if s not in files)[:8]}")
        ev = set()

        def ev_walk(x, key=None):
            if isinstance(x, dict):
                if x.get("source_id") and ("quote" in x or key in ("supplied_by", "timeline", "semantic_evidence", "dependency_edges", "supporting_evidence")):
                    ev.add(x["source_id"])
                for k, v in x.items():
                    ev_walk(v, k)
            elif isinstance(x, list):
                for v in x:
                    ev_walk(v, key)
        for r in objs + reg + gap:
            ev_walk(r)
            for v in (r.get("births") or {}).values() if isinstance(r.get("births"), dict) else []:
                b = birth_basis(v)
                if b:
                    ev.add(b)
        if ev & firewall:
            F.append(f"G-01 FIREWALL-LIMITED file used as evidence: {sorted(ev & firewall)}")
        cost = {}
        for lab in labels:
            le = [e for e in blog if e.get("working_label") == lab]
            s7 = {s for e in le if e.get("step") == 7 for s in e.get("source_ids", [])}
            anyread = {s for e in le if e.get("step") in (1, 7) and not e.get("refused")
                       and ((not e.get("tool")) or e.get("step") == 7) for s in e.get("source_ids", [])}
            need = set(slices[lab]["stage2_files"])
            if need - anyread:
                F.append(f"STAGE-2 {lab}: {len(need - anyread)} of {len(need)} hit files neither read whole nor scanned")
            if (need - s7) & anyread:
                step_label_notes.append(f"{lab}: {len((need - s7) & anyread)} hit file(s) read whole at step 1, not re-logged at step 7")
            cost[lab] = {f"step{st}": sum(sum(e.get("bytes", {}).values()) for e in le if e.get("step") == st) for st in (1, 7, 10)}
            cost[lab]["scanner_calls"] = sum(1 for e in le if e.get("tool"))
        report["batches"][batch] = {"result": "PASS" if not F else "FAIL", "failures": F, "objects": len(objs),
                                    "research_records": len(reg), "p1_gap_records": len(gap),
                                    "records_by_kind": dict(kinds), "reading_bytes": cost}
    n = sum(disp_total.values())
    report["totals"] = {"batches_pass": sum(1 for b in report["batches"].values() if b["result"] == "PASS"),
                        "dispositions_per_hit_dimension": dict(disp_total),
                        "rates": {k: round(v / n, 4) for k, v in disp_total.items()} if n else {},
                        "census_reading_disagreements": disagreements,
                        "repeated_ledger_occurrences_collapsed": dup_keys_total[0], "step_label_notes": step_label_notes,
                        "r1_r2_agreement": {k: {"agree": a, "n": m, "rate": round(a / m, 3) if m else None} for k, (a, m) in sorted(agree.items())},
                        "read_log_entries": len(readlog), "refused": sum(1 for e in readlog if e.get("refused"))}
    print(json.dumps(report["totals"], indent=1))
    for b, v in report["batches"].items():
        print(b, v["result"], f"objects {v['objects']} research {v['research_records']} gaps {v['p1_gap_records']}",
              *(["\n    " + x for x in v["failures"][:14]] or [""]))
    if write:
        with open(os.path.join(CR, "audit-p3b", "S4-R22-VERIFY.json"), "w", encoding="utf-8") as f:
            json.dump(report, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
    return 0 if report["totals"]["batches_pass"] == len(report["batches"]) else 1


if __name__ == "__main__":
    sys.exit(main())
