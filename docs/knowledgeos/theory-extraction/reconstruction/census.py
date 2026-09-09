#!/usr/bin/env python3
"""
KnowledgeOS Chronological Theory Reconstruction — LAYER 1: immutable corpus census.

Mechanical only. NO semantic judgment. Produces one record per queue entry.

Ordering hierarchy (commission §2), highest-confidence key first:
  1 explicit research sequence / step number   (filename or first 4KB of body)
  2 explicit timestamp inside the document     (YAML/bold 'Date:' in first 4KB)
  3 filename datestamp                          (YYYYMMDD-HHMMSS prefix)
  4 queue position                              (line number in the queue file)
  5 filesystem mtime
  6 lexical filename order
Each record states WHICH key supplied its ordering value (`order_evidence`).

FIREWALL: three_model_convergence/ entries are counted and dispositioned, never opened.
"""
import os, re, sys, csv, json, hashlib, collections

REPO = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1"
QUEUE = os.path.join(REPO, "docs/knowledgeos/brainstorming/20260909-185001_files-to-read-one-by-one.log.md")
OUTDIR = os.path.join(REPO, "docs/knowledgeos/theory-extraction/reconstruction")
FIREWALL = "three_model_convergence"

MONTHS = {m: i + 1 for i, m in enumerate(
    "Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec".split())}

RE_QUEUE = re.compile(r'^(?P<mon>[A-Z][a-z]{2})\s+(?P<day>\d{1,2})\s+(?P<hh>\d{2}):(?P<mm>\d{2})\s+(?P<path>\S.*)$')
RE_FNDATE = re.compile(r'(?P<d>\d{8})-(?P<t>\d{4,6})')
RE_STEP_FN = re.compile(r'(?P<series>[a-z0-9\-]*?)step[_\-]?(?P<n>\d{1,4})(?P<sfx>[a-z])?(?![0-9])', re.I)
RE_STEP_BODY = re.compile(r'^\s*#{1,3}\s*STEP\s+(?P<n>\d{1,4})(?P<sfx>[A-Z])?\b', re.I | re.M)
RE_DATE_BODY = re.compile(r'^\s*\**\s*(?:date|Date)\s*\**\s*[::]\s*(?P<v>[0-9]{4}-[0-9]{2}-[0-9]{2}[T0-9:+\-]*)', re.M)

# Mechanical triage signals. Presence raises the score; the gate errs toward READING.
SIGNALS = [
    ("math_display",  re.compile(r'\$\$|\\boxed|\\begin\{')),
    ("defn",          re.compile(r'\b(?:is defined as|Define|Definition|:=|\\triangleq|\\iff|⟺)')),
    ("typed_arrow",   re.compile(r'(?:→|\\rightarrow|\\rightharpoonup|->)\s*\w')),
    ("glyph",         re.compile(r'[𝒪𝒯𝒦𝒟ℛΣΠΘΛΓδπσλ]|\\mathcal|\\Sigma|\\mathcal\{R\}')),
    ("verdict",       re.compile(r'\b(?:RATIFIED|ADOPTED|PROPOSED|REFUTED|SUPERSEDE[SD]?|WITHDRAWN|CLOSED|OPEN|DERIVED|COMMISSIONED)\b')),
    ("claimword",     re.compile(r'\b(?:Theorem|Proposition|Lemma|Corollary|Axiom|Invariant|Criterion|minimal(?:ity)?)\b', re.I)),
    ("kos_object",    re.compile(r'\b(?:K_t|O_core|R_req|EC_t|Sat|EVal|Det|Contr|Sigma|Adequacy|Qualify|Assert|Replay)\b')),
]
HEAD_BYTES = 4096


def parse_queue(path):
    out = []
    with open(path, encoding="utf-8", errors="replace") as fh:
        for i, line in enumerate(fh, 1):
            m = RE_QUEUE.match(line.rstrip("\n"))
            if m:
                out.append((i, m.group("mon"), int(m.group("day")),
                            m.group("hh"), m.group("mm"), m.group("path").strip()))
    return out


def mtime_key(mon, day, hh, mm):
    # queue has no year; corpus spans 2026 only. Month/day/time is sufficient for ordering.
    return f"2026-{MONTHS.get(mon,0):02d}-{day:02d}T{hh}:{mm}"


def probe(abspath):
    """Read only the head of the file for ordering + triage. Cheap."""
    info = {"step": "", "step_series": "", "body_date": "", "signals": [], "score": 0}
    try:
        with open(abspath, encoding="utf-8", errors="replace") as fh:
            head = fh.read(HEAD_BYTES)
    except Exception:
        return info, ""
    m = RE_STEP_BODY.search(head)
    if m and head[:m.start()].strip() == "":
        info["step"] = (m.group("n") + (m.group("sfx") or "")).lower()
    m = RE_DATE_BODY.search(head)
    if m:
        info["body_date"] = m.group("v")
    for name, rx in SIGNALS:
        if rx.search(head):
            info["signals"].append(name)
    info["score"] = len(info["signals"])
    return info, head


def main():
    entries = parse_queue(QUEUE)
    by_hash = collections.defaultdict(list)
    records = []

    for qpos, mon, day, hh, mm, relpath in entries:
        rec = {
            "document_id": "", "queue_position": qpos, "path": relpath,
            "lane": "", "firewalled": False, "exists": False,
            "bytes": 0, "lines": 0, "hash": "",
            "mtime_key": mtime_key(mon, day, hh, mm),
            "filename_datestamp": "", "step": "", "step_series": "", "body_date": "",
            "order_key": "", "order_evidence": "",
            "signals": "", "triage_score": 0,
            "theory_bearing": "", "read_status": "NOT-READ",
            "duplicate_group": "",
        }
        # lane
        p = relpath
        if p.startswith("docs/knowledgeos/brainstorming/"):
            tail = p[len("docs/knowledgeos/brainstorming/"):]
            rec["lane"] = tail.split("/")[0] if "/" in tail else "(root)"
        else:
            rec["lane"] = "(other)"

        if FIREWALL in relpath:
            rec["firewalled"] = True
            rec["read_status"] = "FIREWALLED"
            rec["theory_bearing"] = "UNKNOWN-FIREWALLED"
            rec["lane"] = "3MC"
            rec["order_key"] = rec["mtime_key"]
            rec["order_evidence"] = "5-mtime"
            records.append(rec)
            continue

        m = RE_FNDATE.search(os.path.basename(relpath))
        if m:
            d, t = m.group("d"), m.group("t").ljust(6, "0")
            rec["filename_datestamp"] = f"{d[:4]}-{d[4:6]}-{d[6:8]}T{t[:2]}:{t[2:4]}:{t[4:6]}"
        m = RE_STEP_FN.search(os.path.basename(relpath))
        if m:
            fn_step = (m.group("n") + (m.group("sfx") or "")).lower()
            ser = (m.group("series") or "").strip("-_")
            # a trailing datestamp fragment is not a series name
            rec["step_series"] = "" if re.fullmatch(r'\d*', ser) else ser
        else:
            fn_step = ""

        abspath = os.path.join(REPO, relpath)
        if os.path.isfile(abspath):
            rec["exists"] = True
            data = open(abspath, "rb").read()
            rec["bytes"] = len(data)
            rec["lines"] = data.count(b"\n")
            rec["hash"] = hashlib.md5(data).hexdigest()
            info, _ = probe(abspath)
            rec["step"] = fn_step or info["step"]
            rec["body_date"] = info["body_date"]
            rec["signals"] = "|".join(info["signals"])
            rec["triage_score"] = info["score"]
            by_hash[rec["hash"]].append(qpos)
        else:
            rec["read_status"] = "MISSING"
            rec["step"] = fn_step

        # ---- ordering hierarchy, §2 ----
        if rec["step"] and not rec["step_series"]:
            n = re.match(r'(\d+)([a-z]?)', rec["step"])
            rec["order_key"] = f"1|{int(n.group(1)):05d}{n.group(2) or ' '}"
            rec["order_evidence"] = "1-step"
        elif rec["body_date"]:
            rec["order_key"] = f"2|{rec['body_date']}"
            rec["order_evidence"] = "2-internal-timestamp"
        elif rec["filename_datestamp"]:
            rec["order_key"] = f"3|{rec['filename_datestamp']}"
            rec["order_evidence"] = "3-filename-datestamp"
        else:
            rec["order_key"] = f"4|{qpos:06d}"
            rec["order_evidence"] = "4-queue-position"

        # ---- triage gate: err toward reading ----
        if rec["exists"]:
            if rec["bytes"] == 0:
                rec["theory_bearing"] = "NO-EMPTY"
            elif rec["triage_score"] >= 2:
                rec["theory_bearing"] = "YES"
            elif rec["triage_score"] == 1:
                rec["theory_bearing"] = "LIKELY"
            else:
                rec["theory_bearing"] = "REVIEW"   # never auto-excluded
        records.append(rec)

    # duplicate groups
    gid = 0
    hash_to_gid = {}
    for h, positions in by_hash.items():
        if len(positions) > 1:
            gid += 1
            hash_to_gid[h] = f"D{gid:04d}"
    for rec in records:
        if rec["hash"] in hash_to_gid:
            rec["duplicate_group"] = hash_to_gid[rec["hash"]]

    # stable document ids in queue order
    for rec in records:
        rec["document_id"] = f"Q{rec['queue_position']:05d}"

    os.makedirs(OUTDIR, exist_ok=True)
    cols = list(records[0].keys())
    with open(os.path.join(OUTDIR, "01-CORPUS-CENSUS.tsv"), "w", newline="") as fh:
        w = csv.DictWriter(fh, fieldnames=cols, delimiter="\t", extrasaction="ignore")
        w.writeheader()
        for r in records:
            w.writerow(r)

    # summary
    tot = len(records)
    fw = sum(1 for r in records if r["firewalled"])
    ok = [r for r in records if not r["firewalled"] and r["exists"]]
    miss = sum(1 for r in records if not r["firewalled"] and not r["exists"])
    tb = collections.Counter(r["theory_bearing"] for r in ok)
    oe = collections.Counter(r["order_evidence"] for r in ok)
    lanes = collections.Counter(r["lane"] for r in ok)
    dupfiles = sum(1 for r in ok if r["duplicate_group"])
    summary = {
        "queue_entries": tot, "firewalled_3mc": fw,
        "readable_existing": len(ok), "readable_missing": miss,
        "distinct_hashes": len(by_hash), "files_in_duplicate_groups": dupfiles,
        "duplicate_groups": gid,
        "total_bytes": sum(r["bytes"] for r in ok),
        "total_lines": sum(r["lines"] for r in ok),
        "theory_bearing": dict(tb), "order_evidence": dict(oe), "lanes": dict(lanes),
    }
    with open(os.path.join(OUTDIR, "01-CENSUS-SUMMARY.json"), "w") as fh:
        json.dump(summary, fh, indent=2, sort_keys=True)
    print(json.dumps(summary, indent=2, sort_keys=True))


if __name__ == "__main__":
    main()
