#!/usr/bin/env python3
"""F-Series checkpoints (C13 §4, C09 §2 v1.2; remediation of F-15, F-16, RN-01, RN-02, RN-08, RN-09).

  python3 f_checkpoint.py status
  python3 f_checkpoint.py open [--final]      # snapshot CP-##: populations, hypotheses, data boundary
  python3 f_checkpoint.py run CP-##           # tests — refused while HDR-2 / HDR-3 are undecided

Cadence: every N AUDITED text F-IDs (N = 50, the INITIAL cadence for CP-01, reviewed empirically at CP-01; RN-01) and at
the end of the list. READING is refused while a checkpoint is due and not opened (f_transition). A checkpoint is
MONITORING-ONLY until the human decides temporal semantics (HDR-2) and multiplicity rules (HDR-3): it snapshots and
reports, it tests nothing, and nothing observed at a monitoring checkpoint counts as confirmation.
Near-duplicates are a SIGNAL (5-word shingles, Jaccard ≥ 0.8), never an equivalence claim (RN-09).
"""
import itertools
import json
import os
import re
import sys

import f_checks as K
import f_common as C
import f_integrity as I

N = int(os.environ.get("F_SERIES_CP_N", "50")) if C.TEST_ROOT else 50
NEAR_DUP_JACCARD = 0.8
CP_DIR = os.path.join(C.FDIR, "checkpoints")


def text_audited():
    return [f for f in C.manifest() if C.current_state(f) == "AUDITED"
            and any(e["state"] == "READ-COMPLETE" for e in C.events(f))]


def opened():
    if not os.path.isdir(CP_DIR):
        return []
    return sorted(d for d in os.listdir(CP_DIR) if re.fullmatch(r"CP-\d\d", d))


def due_unopened():
    k = len(opened())
    return f"CP-{k + 1:02d}" if len(text_audited()) // N > k else None


def shingles(text, n=5):
    w = re.findall(r"\w+", text.lower())
    return {" ".join(w[i:i + n]) for i in range(max(1, len(w) - n + 1))}


def populations():
    man = C.manifest()
    audited = [f for f in man if C.current_state(f) == "AUDITED"]
    texts = text_audited()
    first_by_sha, unique = {}, []
    for f in texts:
        h = man[f]["content_sha256"]
        if h not in first_by_sha:
            first_by_sha[h] = f
            unique.append(f)
    sh = {f: shingles(C.content_text(man[f])) for f in unique}
    pairs = []
    for a, b in itertools.combinations(unique, 2):
        j = len(sh[a] & sh[b]) / max(1, len(sh[a] | sh[b]))
        if j >= NEAR_DUP_JACCARD:
            pairs.append({"a": a, "b": b, "jaccard": round(j, 4)})
    hist = {}
    for f in texts:
        fr = (C.read_jsonl(K.ledger_path(f, "files.jsonl")) or [{}])[0]
        hist[f] = {"list_mtime": man[f].get("list_mtime"), "file_mtime": man[f].get("file_mtime"),
                   "explicit_dates": fr.get("explicit_dates", []), "order_evidence": fr.get("order_evidence")}
    return {"F-ID-population": [{"f_id": f, "disposition": [e["state"] for e in C.events(f)][-2]} for f in audited],
            "text-audited": texts,
            "unique-content": unique,
            "exact-duplicates-collapsed": {f: first_by_sha[man[f]["content_sha256"]] for f in texts
                                           if first_by_sha[man[f]["content_sha256"]] != f},
            "F-specific-content": [f for f in unique if not man[f].get("content_equals_s_sources")],
            "content-identical-to-S": [f for f in unique if man[f].get("content_equals_s_sources")],
            "near-duplicate-signal": {"method": "5-word shingles, Jaccard", "threshold": NEAR_DUP_JACCARD,
                                      "status": "SIGNAL — not an equivalence claim (RN-09)", "pairs": pairs},
            "historical-time-evidence": hist}


def hypotheses():
    out = []
    for f in text_audited():
        fr = I.active_freezes(f).get("RESEARCHED", {})
        p = K.ledger_path(f, "research.jsonl")
        cur = C.sha256_file(p) if os.path.exists(p) else None
        for r in C.read_jsonl(p):
            if r.get("kind") in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"):
                out.append({"f_id": f, "rs_id": r["rs_id"], "frozen_research_sha256": fr.get("research.jsonl"),
                            "current_sha256": cur, "immutable": fr.get("research.jsonl") == cur,
                            "preregistration": r.get("preregistration")})
    return out


def main(argv):
    if not argv or argv[0] not in ("status", "open", "run"):
        print(__doc__, file=sys.stderr)
        return 2
    I.require_approval()
    if argv[0] == "status":
        print(json.dumps({"N": N, "text_audited": len(text_audited()), "opened": opened(), "due": due_unopened(),
                          "temporal_semantics": I.decision("temporal_semantics"),
                          "multiplicity_rules": I.decision("multiplicity_rules")}, indent=1))
        return 0
    if argv[0] == "run":
        if I.decision("temporal_semantics") is None or I.decision("multiplicity_rules") is None:
            print("REFUSED: HUMAN DECISION REQUIRED — checkpoint tests are blocked until HDR-2 (temporal semantics) and "
                  "HDR-3 (multiplicity rules) are recorded in F-DECISIONS.json (F-12, F-15)", file=sys.stderr)
            return 1
        print("REFUSED: checkpoint test execution (C11) is specified but not implemented in v1.2", file=sys.stderr)
        return 1
    cp = due_unopened()
    if not cp and "--final" in argv and C.next_fid() is None:
        cp = f"CP-{len(opened()) + 1:02d}"
    if not cp:
        print("REFUSED: no checkpoint is due", file=sys.stderr)
        return 1
    d = os.path.join(CP_DIR, cp)
    C.guard(os.path.join(d, "STATE.jsonl"))
    os.makedirs(d, exist_ok=True)
    pops, hyps = populations(), hypotheses()
    mode = "MONITORING-ONLY" if I.decision("temporal_semantics") is None or I.decision("multiplicity_rules") is None \
        else "TESTS-PERMITTED"
    for name, obj in (("POPULATIONS.json", pops), ("HYPOTHESES.json", hyps)):
        with open(C.guard(os.path.join(d, name)), "w", encoding="utf-8") as f:
            json.dump(obj, f, indent=1, ensure_ascii=False)
    C.append_jsonl(os.path.join(d, "STATE.jsonl"), {
        "checkpoint": cp, "state": "OPEN", "utc": C.utc(), "mode": mode, "N": N,
        "data_boundary_last_audited": pops["text-audited"][-1] if pops["text-audited"] else None,
        "populations_sha256": C.sha256_file(os.path.join(d, "POPULATIONS.json")),
        "hypotheses_sha256": C.sha256_file(os.path.join(d, "HYPOTHESES.json")),
        "mutable_hypotheses": [h["rs_id"] for h in hyps if not h["immutable"]]})
    print(f"{cp} OPEN ({mode}); populations and hypotheses snapshotted in {os.path.relpath(d, C.FDIR)}")
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
