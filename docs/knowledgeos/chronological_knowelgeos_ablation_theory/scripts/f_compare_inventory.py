#!/usr/bin/env python3
"""F-Series independent L1 audit (C13 §3 v1.2; remediation of F-06). Runs at CONTENT-EXTRACTED, before RECONSTRUCTED,
for the first text F-ID and every fifth (`is_due`). The auditor is a fresh agent that reads the file through the reader
under its own run FR-F####-9NN, writes AUDITOR-PAGE-DIGESTS.jsonl, AUDITOR-INVENTORY.jsonl and AUDITOR-ATTESTATION.json
without reading the extractor's L1 artifacts, and (optionally) AUDITOR-DISCREPANCIES.jsonl with its own observations.
This script then COMPUTES the comparison — the auditor does not assert it:

  python3 f_compare_inventory.py --auditor-run FR-F3082-901 --run FR-F3082-001 --verdict CONFIRMED|DISCREPANCY \
          [--human-ref F-LOG-####] F3082

It writes INDEPENDENT-AUDIT-L1.json (latest) and appends INDEPENDENT-AUDIT-L1-LOG.jsonl. `verify()` (called by the
RECONSTRUCTED gate) recomputes everything and refuses any mismatch, a missing auditor read, or a missing human
acceptance. CONFIRMED requires zero discrepancies, or explicit human acceptance of the listed ones.
"""
import json
import os
import re
import sys

import f_checks as K
import f_common as C
import f_integrity as I

GROUPS = {"DEFINITIONAL": {"DEFINITION", "TERM"}, "MATHEMATICAL": {"FORMULA", "NOTATION", "THEOREM"}}
AUDITOR_FILES = ["AUDITOR-PAGE-DIGESTS.jsonl", "AUDITOR-INVENTORY.jsonl", "AUDITOR-ATTESTATION.json"]


def is_due(fid):
    """First text F-ID and every fifth: counts earlier text F-IDs that reached READ-COMPLETE (incl. exceptions)."""
    man = C.manifest()
    n = 0
    for other, r in man.items():
        if r["list_order"] >= man[fid]["list_order"]:
            break
        if any(e["state"] == "READ-COMPLETE" for e in C.events(other)):
            n += 1
    return n % 5 == 0


def _items(fid, name):
    return C.read_jsonl(K.ledger_path(fid, name))


def compare(fid):
    """Deterministic comparison of the auditor's inventory with the extractor's (units, protected groups, terms)."""
    text = C.content_text(C.manifest()[fid])
    units = {u["unit_id"]: u for u in K.units_of(text)}
    ext, aud = _items(fid, "CONTENT-INVENTORY.jsonl"), _items(fid, "AUDITOR-INVENTORY.jsonl")
    disp = {u: d.get("disposition") for d in _items(fid, "UNIT-DISPOSITIONS.jsonl") for u in d.get("unit_ids") or []}
    invalid = []
    for it in aud:
        cu = it.get("covers_units") or []
        if it.get("category") not in K.INVENTORY_CATEGORIES or not cu or any(u not in units for u in cu):
            invalid.append(it.get("item_id"))
            continue
        span = text[min(units[u]["char_start"] for u in cu):max(units[u]["char_end"] for u in cu)]
        if not K.quote_in(it.get("verbatim_quote", ""), span):
            invalid.append(it.get("item_id"))

    def by_group(items):
        out = {g: set() for g in GROUPS}
        for it in items:
            for g, cats in GROUPS.items():
                if it.get("category") in cats:
                    out[g] |= set(it.get("covers_units") or [])
        return out

    eg, ag = by_group(ext), by_group(aud)
    disc = []
    for g in GROUPS:
        for u in sorted(ag[g] - eg[g]):
            disc.append({"kind": "MISSING-IN-EXTRACTION", "group": g, "unit": u})
    ext_units = {u for it in ext for u in it.get("covers_units") or []}
    for u in sorted({u for it in aud for u in it.get("covers_units") or []} - ext_units):
        disc.append({"kind": "AUDITOR-EXTRACTED-UNIT-NOT-COVERED", "unit": u, "extractor_disposition": disp.get(u)})
    e_terms = {K.normalize_term(it.get("term")) for it in ext if it.get("category") in GROUPS["DEFINITIONAL"]}
    for it in aud:
        t = K.normalize_term(it.get("term"))
        if it.get("category") == "DEFINITION" and t and t not in e_terms:
            disc.append({"kind": "DEFINITION-TERM-MISSING", "term": it.get("term"), "units": it.get("covers_units")})
    for iid in invalid:
        disc.append({"kind": "AUDITOR-ITEM-INVALID", "item_id": iid})
    cat = lambda xs: dict(sorted(__import__("collections").Counter(x.get("category") for x in xs).items()))
    return {"mechanical_discrepancies": disc, "extractor_categories": cat(ext), "auditor_categories": cat(aud),
            "extractor_items": len(ext), "auditor_items": len(aud)}


def build(fid, run, auditor_run, verdict, human_ref, writing=True):
    f = []
    ce = I.active_freezes(fid).get("CONTENT-EXTRACTED")
    ce_ev = [e for e in C.events(fid) if e["state"] == "CONTENT-EXTRACTED"]
    if not ce or not ce_ev or ce_ev[-1].get("run_id") != run:
        f.append(f"{fid} has no CONTENT-EXTRACTED freeze under run {run}")
    if writing and C.current_state(fid) != "CONTENT-EXTRACTED":     # the audit is performed at CONTENT-EXTRACTED only
        f.append(f"{fid} must be CONTENT-EXTRACTED to be audited (it is {C.current_state(fid)})")
    if not re.fullmatch(rf"FR-{fid}-9\d\d", auditor_run or ""):
        f.append("auditor run must be FR-<F-ID>-9NN (a separate run)")
    cov = K.coverage(fid, auditor_run, "AUDITOR-PAGE-DIGESTS.jsonl")
    if not cov["complete"]:
        f.append(f"auditor read coverage incomplete: {json.dumps(cov)}")
    ok, af = K.check_isolation_attestation(fid, auditor_run, role="AUDITOR", name="AUDITOR-ATTESTATION.json")
    f += af
    if not _items(fid, "AUDITOR-INVENTORY.jsonl"):
        f.append("AUDITOR-INVENTORY.jsonl missing or empty (the auditor's own inventory)")
    cmp_ = compare(fid)
    declared = _items(fid, "AUDITOR-DISCREPANCIES.jsonl")
    n_disc = len(cmp_["mechanical_discrepancies"]) + len(declared)
    if verdict not in ("CONFIRMED", "DISCREPANCY"):
        f.append("verdict must be CONFIRMED or DISCREPANCY")
    if verdict == "CONFIRMED" and not I.human_ref_ok(human_ref, fid):
        f.append("CONFIRMED needs --human-ref: the human accepts L1 (and any listed discrepancy) in an F-LOG entry naming the F-ID")
    if verdict == "CONFIRMED" and n_disc and not I.human_ref_ok(human_ref, fid):
        f.append(f"{n_disc} discrepancies listed; CONFIRMED only with explicit human acceptance")
    rec = {"f_id": fid, "audited_run": run, "auditor_run": auditor_run, "utc": C.utc(), "verdict": verdict,
           "human_ref": human_ref, "auditor_coverage": cov, "extractor_frozen": ce,
           "auditor_artifacts": {n: (C.sha256_file(K.ledger_path(fid, n)) if os.path.exists(K.ledger_path(fid, n)) else None)
                                 for n in AUDITOR_FILES + ["AUDITOR-DISCREPANCIES.jsonl"]},
           "declared_discrepancies": declared, **cmp_}
    return rec, f


def verify(fid, run):
    """RECONSTRUCTED gate: recompute and compare; refuse forged, stale or unaccepted audits."""
    f = []
    p = K.ledger_path(fid, "INDEPENDENT-AUDIT-L1.json")
    a = K.load_json(p)
    if not a:
        return False, ["independent L1 audit is due and INDEPENDENT-AUDIT-L1.json is missing (F-06)"]
    rec, bf = build(fid, run, a.get("auditor_run"), a.get("verdict"), a.get("human_ref"), writing=False)
    f += bf
    for k in ("mechanical_discrepancies", "extractor_frozen", "auditor_artifacts", "declared_discrepancies"):
        if a.get(k) != rec.get(k):
            f.append(f"INDEPENDENT-AUDIT-L1.json field {k} does not match the recomputation (forged or stale, F-06)")
    if a.get("verdict") != "CONFIRMED":
        f.append(f"independent L1 audit verdict is {a.get('verdict')}: re-extract (CONTENT-EXTRACTED with --human-ref)")
    return not f, f


def main(argv):
    def arg(n):
        return argv[argv.index(n) + 1] if n in argv and argv.index(n) + 1 < len(argv) else None
    fid = argv[-1] if argv else None
    if not fid or fid not in C.manifest() or not arg("--run") or not arg("--auditor-run") or not arg("--verdict"):
        print(__doc__, file=sys.stderr)
        return 2
    I.require_approval()
    rec, f = build(fid, arg("--run"), arg("--auditor-run"), arg("--verdict"), arg("--human-ref"))
    if f:
        print("REFUSED:\n  " + "\n  ".join(f), file=sys.stderr)
        print(json.dumps({k: rec[k] for k in ("mechanical_discrepancies", "extractor_categories", "auditor_categories")},
                         indent=1, ensure_ascii=False))
        return 1
    with open(C.guard(K.ledger_path(fid, "INDEPENDENT-AUDIT-L1.json")), "w", encoding="utf-8") as fh:
        json.dump(rec, fh, indent=1, ensure_ascii=False)
    C.append_jsonl(K.ledger_path(fid, "INDEPENDENT-AUDIT-L1-LOG.jsonl"), rec)
    print(json.dumps({k: rec[k] for k in ("verdict", "human_ref", "mechanical_discrepancies", "extractor_items",
                                          "auditor_items")}, indent=1, ensure_ascii=False))
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
