"""Evidence-phase engine (EVIDENCE-SCHEMA.md r1). Validates the evidence graph and derives, MECHANICALLY:
  source -> claim -> cell (one reader's assessment) --(counting rule)--> proposition outcome + diagnostics
  --(propositions.json x frozen model results, stamped with the formal basis)--> directional model constraints.
It never selects a model. Strongest outputs: INCONSISTENT-WITH and MODEL-FAMILY-INCOMPLETE-CANDIDATE (guarded; else
INCONCLUSIVE). MODEL-FAMILY-INCOMPLETE is an L0 research decision and is NEVER emitted by this engine.
`interpretation_confidence` = confidence in the reader's reading of the passage, never in the truth of the proposition.
Usage: python3 derive_constraints.py evidence.json [--basis formal_basis.json] [--root <repo root>]  > constraints.json
evidence.json = {"cells": [...], "events": [...]}   (a bare list is read as cells)"""
import argparse
import hashlib
import json
import os
import sys

H = os.path.dirname(os.path.abspath(__file__))
ASSESS = {"SUPPORTED", "REFUTED", "AMBIGUOUS", "SILENT", "OUT-OF-SCOPE"}
LAYERS = {"HISTORICAL-EVIDENCE", "RECONSTRUCTION", "INFERENCE", "FORMAL-CONSEQUENCE", "RESEARCH-HYPOTHESIS", "GOVERNANCE-DECISION"}
READERS = {"SELF", "SECONDARY_REVIEW", "INDEPENDENT"}
CONF = {"HIGH", "MEDIUM", "LOW"}
PRECISION = {"DAY", "MONTH", "YEAR", "UNKNOWN"}
EVENTS = {"AUTHORIZATION", "PROMOTION", "ADOPTION", "REVOCATION", "REASSESSMENT", "BAR_CHANGE", "EVIDENCE_CHANGE", "OTHER"}
PREC_STATUS = {"ESTABLISHED", "NOT-ESTABLISHED"}
BASIS = {"SECONDARY-REPRODUCED", "INDEPENDENT-REPRODUCED", "NOT-REPRODUCED"}
DECISIVE = {"SUPPORTED", "REFUTED"}
CELL_REQ = ("id", "claim_id", "proposition", "source_slot", "source_path", "source_sha256", "anchor", "exact_wording",
            "historical_date", "source_claim", "assessment", "interpretation_confidence", "layer", "reader_class", "reader_id", "l0_release_id")
CELL_OPT = {"context", "events", "precedence", "notes"}
EVENT_REQ = ("id", "type", "source_path", "source_sha256", "anchor", "exact_wording", "historical_date", "l0_release_id")
EVENT_OPT = {"context", "precedence", "notes"}
NORM = {"VACUOUS": "HOLDS", "HOLDS": "HOLDS", "FAILS": "FAILS", "REACHABLE": "REACHABLE", "NOT_REACHABLE": "NOT_REACHABLE"}


def _anchor(obj, root, v, oid):
    """Byte-exact anchoring: pinned source hash, and the quote occurs inside the anchored line span."""
    a, path = obj.get("anchor") or {}, os.path.join(root, obj.get("source_path", ""))
    if not os.path.isfile(path): v.append(f"{oid}: source not found"); return
    b = open(path, "rb").read()
    if hashlib.sha256(b).hexdigest() != obj.get("source_sha256"): v.append(f"{oid}: source sha256 mismatch"); return
    lines = b.split(b"\n"); s, e = a.get("start_line"), a.get("end_line")
    if not (isinstance(s, int) and isinstance(e, int) and 1 <= s <= e <= len(lines)): v.append(f"{oid}: anchor span invalid"); return
    q = (obj.get("exact_wording") or "").encode()
    if not q or q not in b"\n".join(lines[s - 1:e]): v.append(f"{oid}: exact_wording not found byte-exactly inside anchor span")


def _date(obj, v, oid):
    hd = obj.get("historical_date")
    if not isinstance(hd, dict) or hd.get("precision") not in PRECISION: v.append(f"{oid}: historical_date.precision invalid")


def _prec(obj, v, oid):
    for pr in obj.get("precedence", []):
        if pr.get("status") not in PREC_STATUS or not pr.get("basis"): v.append(f"{oid}: precedence needs status + basis")


def validate(ev, props, root):
    """Violations list; any violation => REJECT (nothing derived)."""
    v, ids, pids = [], set(), {p["id"] for p in props}
    claim_src = {}
    for c in ev["cells"]:
        cid = c.get("id", "?")
        for k in CELL_REQ:
            if k not in c or c[k] in (None, ""): v.append(f"{cid}: missing {k}")
        if set(c) - set(CELL_REQ) - CELL_OPT: v.append(f"{cid}: unknown keys {sorted(set(c) - set(CELL_REQ) - CELL_OPT)}")
        if cid in ids: v.append(f"{cid}: duplicate id")
        ids.add(cid)
        if c.get("proposition") not in pids: v.append(f"{cid}: unknown proposition {c.get('proposition')}")
        for k, dom in (("assessment", ASSESS), ("layer", LAYERS), ("reader_class", READERS), ("interpretation_confidence", CONF)):
            if k in c and c[k] not in dom: v.append(f"{cid}: {k} not in closed vocabulary")
        _date(c, v, cid); _prec(c, v, cid)
        for e in c.get("events", []):
            if e.get("type") not in EVENTS or e.get("precision") not in PRECISION: v.append(f"{cid}: event invalid")
        # a claim is ONE source passage: every cell of the claim must point at the same pinned bytes and span
        key = (c.get("source_path"), c.get("source_sha256"), json.dumps(c.get("anchor"), sort_keys=True), c.get("exact_wording"))
        if claim_src.setdefault(c.get("claim_id"), key) != key: v.append(f"{cid}: claim_id {c.get('claim_id')} reused for a different passage")
        _anchor(c, root, v, cid)
    rpc = {}
    for c in ev["cells"]:
        k = (c.get("claim_id"), c.get("proposition"), c.get("reader_id"))
        if k in rpc: v.append(f"{c.get('id')}: reader {k[2]} assessed claim {k[0]} for {k[1]} twice")
        rpc[k] = 1
    for e in ev.get("events", []):
        eid = e.get("id", "?")
        for k in EVENT_REQ:
            if k not in e or e[k] in (None, ""): v.append(f"{eid}: missing {k}")
        if set(e) - set(EVENT_REQ) - EVENT_OPT: v.append(f"{eid}: unknown keys")
        if eid in ids: v.append(f"{eid}: duplicate id")
        ids.add(eid)
        if e.get("type") not in EVENTS: v.append(f"{eid}: event type not in closed vocabulary")
        _date(e, v, eid); _prec(e, v, eid); _anchor(e, root, v, eid)
    return v


def proposition_outcome(cs):
    """Counting rule: only HISTORICAL-EVIDENCE cells count; OUT-OF-SCOPE is ignored; nothing is averaged."""
    counted = [c for c in cs if c["layer"] == "HISTORICAL-EVIDENCE" and c["assessment"] != "OUT-OF-SCOPE"]
    if not counted: return "OUT-OF-SCOPE" if any(c["assessment"] == "OUT-OF-SCOPE" for c in cs) else "SILENT"
    a = {c["assessment"] for c in counted}
    if DECISIVE <= a: return "CONFLICTING"
    if "SUPPORTED" in a: return "SUPPORTED"
    if "REFUTED" in a: return "REFUTED"
    return "AMBIGUOUS" if "AMBIGUOUS" in a else "SILENT"


def diagnostics(cs):
    """Conflict typing. A diagnostic never changes an outcome; it explains it and blocks the INCOMPLETE guard."""
    counted = [c for c in cs if c["layer"] == "HISTORICAL-EVIDENCE" and c["assessment"] != "OUT-OF-SCOPE"]
    d = set(); by_claim = {}
    for c in counted: by_claim.setdefault(c["claim_id"], []).append(c)
    for cl in by_claim.values():  # same passage, different readers
        if len({c["assessment"] for c in cl}) > 1: d.add("READER-DISAGREEMENT")
    claim_view = {k: {c["assessment"] for c in cl} & DECISIVE for k, cl in by_claim.items()}
    sup = [k for k, s in claim_view.items() if s == {"SUPPORTED"}]; ref = [k for k, s in claim_view.items() if s == {"REFUTED"}]
    if sup and ref:  # different passages pointing in opposite directions
        d.add("SOURCE-CONFLICT"); first = {k: by_claim[k][0] for k in sup + ref}
        dates = lambda ks: {json.dumps(first[k]["historical_date"], sort_keys=True) for k in ks if first[k]["historical_date"].get("precision") != "UNKNOWN"}
        if dates(sup) and dates(ref) and dates(sup).isdisjoint(dates(ref)): d.add("TEMPORAL-CONFLICT")
        scope = lambda ks: {json.dumps((first[k].get("context") or {}).get("scope"), sort_keys=True) for k in ks}
        if scope(sup).isdisjoint(scope(ref)): d.add("SCOPE-CONFLICT")
    if any(c["assessment"] == "AMBIGUOUS" for c in counted): d.add("SEMANTIC-AMBIGUITY")
    if any(c["assessment"] == "OUT-OF-SCOPE" for c in cs if c["layer"] == "HISTORICAL-EVIDENCE"): d.add("SCOPE-CONFLICT")
    return sorted(d)


def stable(cs, outcome, diags):
    """INCOMPLETE-CANDIDATE guard: decided outcome, no diagnostic, every decisive cell HIGH interpretation confidence, and the
    reading is corroborated (>= 2 distinct readers agree, or >= 1 INDEPENDENT reader)."""
    if outcome not in DECISIVE or diags: return False
    dec = [c for c in cs if c["layer"] == "HISTORICAL-EVIDENCE" and c["assessment"] == outcome]
    return bool(dec) and all(c["interpretation_confidence"] == "HIGH" for c in dec) and \
        (len({c["reader_id"] for c in dec}) >= 2 or any(c["reader_class"] == "INDEPENDENT" for c in dec))


def model_values(results, prop, models):
    out = {}
    for m in models:
        vals = {NORM[results[m]["instances"][i]["properties"][prop]["class"]] for i in results[m]["instances"]}
        out[m] = vals.pop() if len(vals) == 1 else "INSTANCE-DEPENDENT"
    return out


def verdicts(p, direction, results, models):
    """Per-model verdict if the proposition is taken as `direction` (SUPPORTED or REFUTED)."""
    if p.get("property") is None and p.get("structural"):
        return {m: "STRUCTURALLY-CONSISTENT" if direction == "SUPPORTED" else "INCONSISTENT" for m in models}
    if p.get("property") is None:
        return {m: "CANNOT-EXPRESS" if direction == "SUPPORTED" else "NO-CONSTRAINT" for m in models}
    out = {}
    for m, val in model_values(results, p["property"], models).items():
        if val == "INSTANCE-DEPENDENT": out[m] = "INSTANCE-DEPENDENT"; continue
        out[m] = "CONSISTENT" if ((val == p["requires"]) == (direction == "SUPPORTED")) else "INCONSISTENT"
    return out


def no_primary_fit(pm, prim):
    return not any(pm.get(m) in ("CONSISTENT", "STRUCTURALLY-CONSISTENT") for m in prim) and \
        not all(pm.get(m) == "NO-CONSTRAINT" for m in prim)


def derive(ev, spec, results, basis):
    props = {p["id"]: p for p in spec["propositions"]}; prim, ctrl = spec["primary_models"], spec["control_models"]; allm = prim + ctrl
    stamp = {k: basis.get(k) for k in ("status", "results_path", "results_sha256")}  # only this layer changes when the basis changes
    rep = {"formal_basis": stamp, "propositions": {}, "family_flags": [], "open": [],
           "models": {m: {"inconsistent_with": [], "conditionally_inconsistent_with": [], "cannot_express": []} for m in allm},
           "events": [e["id"] for e in ev.get("events", [])]}
    for pid, p in props.items():
        cs = [c for c in ev["cells"] if c["proposition"] == pid]; out = proposition_outcome(cs); dg = diagnostics(cs)
        r = {"eq": p["eq"], "outcome": out, "diagnostics": dg, "claims": sorted({c["claim_id"] for c in cs}), "cells": [c["id"] for c in cs],
             "annotations": [c["id"] for c in cs if c["layer"] != "HISTORICAL-EVIDENCE"], "formal_basis": stamp}
        if out in DECISIVE:
            pm = verdicts(p, out, results, allm); r["per_model"] = pm
            for m, x in pm.items():
                if x == "INCONSISTENT": rep["models"][m]["inconsistent_with"].append(pid)
                if x == "CANNOT-EXPRESS": rep["models"][m]["cannot_express"].append(pid)
            if no_primary_fit(pm, prim):
                ok = stable(cs, out, dg)
                rep["family_flags"].append({"flag": "MODEL-FAMILY-INCOMPLETE-CANDIDATE" if ok else "MODEL-FAMILY-INCONCLUSIVE",
                                            "proposition": pid, "outcome": out, "guard_passed": ok, "formal_basis": stamp,
                                            "l0_research_decision_required": ok})
        elif out == "CONFLICTING":  # preserve BOTH directional constraints; the proposition stays unresolved
            r["if_supported"] = verdicts(p, "SUPPORTED", results, allm); r["if_refuted"] = verdicts(p, "REFUTED", results, allm)
            rep["open"].append(pid)
            for m in allm:
                for d, pm in (("SUPPORTED", r["if_supported"]), ("REFUTED", r["if_refuted"])):
                    if pm[m] == "INCONSISTENT": rep["models"][m]["conditionally_inconsistent_with"].append(f"{pid} if {d}")
            for d, pm in (("SUPPORTED", r["if_supported"]), ("REFUTED", r["if_refuted"])):
                if no_primary_fit(pm, prim):
                    rep["family_flags"].append({"flag": "MODEL-FAMILY-INCONCLUSIVE", "proposition": pid, "outcome": f"CONFLICTING (if {d})",
                                                "guard_passed": False, "formal_basis": stamp, "l0_research_decision_required": False})
        elif out == "AMBIGUOUS": rep["open"].append(pid)
        rep["propositions"][pid] = r
    for m, d in rep["models"].items():
        d["status"] = "INCONSISTENT-WITH " + ",".join(d["inconsistent_with"]) if d["inconsistent_with"] else "NOT-ELIMINATED"
        d["role"] = "control (never a candidate)" if m in ctrl else "primary"
    rep["rule"] = "constraints only; no model is selected; NOT-ELIMINATED != supported; SILENT constrains nothing; " \
                  "CONFLICTING keeps both directions unresolved; every constraint carries the formal basis"
    return rep


def load_basis(path, root):
    b = json.load(open(path))
    if b.get("status") not in BASIS: raise SystemExit("formal basis status not in closed vocabulary")
    rp = os.path.join(root, b["results_path"])
    if hashlib.sha256(open(rp, "rb").read()).hexdigest() != b["results_sha256"]: raise SystemExit("formal basis results hash mismatch")
    return b, json.load(open(rp))


def main():
    ap = argparse.ArgumentParser(); ap.add_argument("evidence")
    ap.add_argument("--basis", default=os.path.join(H, "formal_basis.json"))
    ap.add_argument("--props", default=os.path.join(H, "propositions.json"))
    ap.add_argument("--root", default=os.path.abspath(os.path.join(H, *[".."] * 5)))
    a = ap.parse_args()
    ev = json.load(open(a.evidence)); ev = {"cells": ev, "events": []} if isinstance(ev, list) else ev
    spec = json.load(open(a.props)); basis, results = load_basis(a.basis, a.root)
    v = validate(ev, spec["propositions"], a.root)
    if v: json.dump({"status": "REJECT", "violations": v}, sys.stdout, indent=1); print(); sys.exit(2)
    json.dump({"status": "DERIVED", **derive(ev, spec, results, basis)}, sys.stdout, indent=1, sort_keys=True); print()


if __name__ == "__main__":
    main()
