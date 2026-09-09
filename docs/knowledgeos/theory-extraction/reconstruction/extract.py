#!/usr/bin/env python3
"""
LAYER 2 (mechanical half) — per-document theory extraction.

Runs over every readable, non-firewalled, non-empty census entry and emits:
  02-DOC-RECORDS.jsonl   one structured record per document
  02-SYMBOL-INDEX.jsonl  symbol -> ordered occurrences with the FORMS it takes

No semantic judgment. It records what the text says, verbatim, with location.
Symbols are DISCOVERED, not supplied from a list, so today's vocabulary cannot
steer the extraction (commission §1: never search backward from current terms).
"""
import os, re, json, csv, collections

REPO = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1"
OUT = os.path.join(REPO, "docs/knowledgeos/theory-extraction/reconstruction")
CENSUS = os.path.join(OUT, "01-CORPUS-CENSUS.tsv")

RE_DISPLAY = re.compile(r'\$\$(.+?)\$\$', re.S)
RE_BOXED   = re.compile(r'\\boxed\{')
RE_HEAD    = re.compile(r'^\s{0,3}(#{1,4})\s+(.+?)\s*$', re.M)
RE_STATUS  = re.compile(r'\b(RATIFIED|ADOPTED|PROPOSED|COMMISSIONED|REFUTED|WITHDRAWN|SUPERSEDED|SUPERSEDES|RETIRED|DISPUTED|ACCEPTED|CONDITIONALLY ACCEPTED|CLOSED|OPEN|DERIVED|STIPULATED|UNRESOLVED|NOT ACHIEVED|ACHIEVED|PARTIAL|BLOCKED|NORMATIVE|EMPIRICAL|FINAL|CERTIFIED)\b')
RE_DEFN    = re.compile(r'^(.{0,200}?)\b(?:is defined as|are defined as|We define|Define|Definition)\b(.{0,200})$', re.M)
RE_SUPERSEDE = re.compile(r'^\**\s*(?:Supersedes|Superseded by|Replaces|Replaced by)\s*\**\s*[::]?\s*(.{0,160})$', re.M | re.I)
RE_CITE_STEP = re.compile(r'\bStep\s+(\d{1,4}[A-Za-z]?)\b')
RE_CITE_SEC  = re.compile(r'\b(\d{2,4}[A-Za-z]?\.\d{1,2}(?:\.\d{1,2})?)\b')

# ---- symbol discovery -------------------------------------------------------
# A "symbol" is an identifier that plausibly names a formal object.
RE_SYM_ASCII = re.compile(r'\b([A-Za-z][A-Za-z]{0,14})(?:_\{?([A-Za-z0-9\+\-\*]{1,12})\}?)?\b')
RE_SYM_CAL   = re.compile(r'\\mathcal\{([A-Za-z])\}(?:_\{?([A-Za-z0-9]{1,12})\}?)?')
RE_SYM_UNI   = re.compile(r'([\u2100-\u214F\U0001D49C-\U0001D4CF\u0391-\u03A9\u03B1-\u03C9\u2130-\u2134])(?:_\{?([A-Za-z0-9]{1,12})\}?)?')
# words that are prose, not objects
STOP = set("""the a an and or of to in is are be was were it its this that these those for with as by on at from not no if then else we our you they he she
Define Definition We define is are The A An And Or Of To In It This That For With As By On At From Not No If Then Else
Step step Date Status Purpose Authority Predecessor Successor Next Result Note Yes No None All Any Each Every Some Such
where which what when who whom whose there here also only just even both either neither than more most less least
Table Figure Section Part Appendix Chapter Page Line Row Column Item List Set Map Type Case Test Rule Law Fact Data
must may can will shall should would could does did done have has had been being make made take taken give given
true false True False null None NULL text right left over under above below same other another new old first last
""".split())
MIN_SYM_DOCS = 1


def load_census():
    with open(CENSUS) as fh:
        return list(csv.DictReader(fh, delimiter="\t"))


def strip_code(s):
    return re.sub(r'```.*?```', ' ', s, flags=re.S)


RE_TEX_CMD = re.compile(r'\\[A-Za-z]+')

def discover_symbols(math_blobs):
    """Only look inside math for symbols — prose produces noise.
    LaTeX control words are stripped first: \\boxed, \\neq, \\ldots are not objects."""
    found = collections.Counter()
    for blob in math_blobs:
        blob = RE_TEX_CMD.sub(' ', blob)
        for m in RE_SYM_CAL.finditer(blob):
            base = "\\mathcal{%s}" % m.group(1)
            found[base + (("_" + m.group(2)) if m.group(2) else "")] += 1
        for m in RE_SYM_UNI.finditer(blob):
            found[m.group(1) + (("_" + m.group(2)) if m.group(2) else "")] += 1
        for m in RE_SYM_ASCII.finditer(blob):
            base = m.group(1)
            if base in STOP or len(base) > 14:
                continue
            if base.islower() and len(base) > 6:      # long lowercase words = prose
                continue
            found[base + (("_" + m.group(2)) if m.group(2) else "")] += 1
    return found


def main():
    rows = [r for r in load_census()
            if r["firewalled"] == "False" and r["exists"] == "True" and int(r["bytes"]) > 0]
    rows.sort(key=lambda r: (r["order_key"], int(r["queue_position"])))

    sym_index = collections.defaultdict(list)
    n = 0
    with open(os.path.join(OUT, "02-DOC-RECORDS.jsonl"), "w") as fout:
        for r in rows:
            path = os.path.join(REPO, r["path"])
            try:
                raw = open(path, encoding="utf-8", errors="replace").read()
            except Exception:
                continue
            body = strip_code(raw)
            math = [m.group(1).strip() for m in RE_DISPLAY.finditer(body)]
            math = [m for m in math if len(m) < 2000]
            heads = [f"{len(h.group(1))}|{h.group(2)[:120]}" for h in RE_HEAD.finditer(body)][:200]
            defns = [(a.strip()[-120:] + " ⟪def⟫ " + b.strip()[:120]) for a, b in RE_DEFN.findall(body)][:60]
            status = collections.Counter(RE_STATUS.findall(body))
            supers = [s.strip() for s in RE_SUPERSEDE.findall(body)][:20]
            cites_step = sorted(set(RE_CITE_STEP.findall(body)))[:60]
            cites_sec = sorted(set(RE_CITE_SEC.findall(body)))[:80]
            syms = discover_symbols(math)

            rec = {
                "document_id": r["document_id"], "path": r["path"], "lane": r["lane"],
                "step": r["step"], "order_key": r["order_key"], "order_evidence": r["order_evidence"],
                "queue_position": int(r["queue_position"]), "mtime_key": r["mtime_key"],
                "filename_datestamp": r["filename_datestamp"], "body_date": r["body_date"],
                "bytes": int(r["bytes"]), "lines": int(r["lines"]),
                "duplicate_group": r["duplicate_group"],
                "n_math": len(math), "n_headings": len(heads),
                "headings": heads[:60],
                "definitions": defns,
                "math": math[:120],
                "status_tokens": dict(status),
                "supersession_lines": supers,
                "cites_steps": cites_step, "cites_sections": cites_sec,
                "symbols": dict(syms.most_common(80)),
                "read_status": "MECHANICAL-ONLY",
            }
            fout.write(json.dumps(rec, ensure_ascii=False) + "\n")

            for s, c in syms.items():
                sym_index[s].append({
                    "doc": r["document_id"], "order_key": r["order_key"],
                    "mtime": r["mtime_key"], "step": r["step"],
                    "lane": r["lane"], "count": c, "path": r["path"],
                })
            n += 1

    with open(os.path.join(OUT, "02-SYMBOL-INDEX.jsonl"), "w") as fh:
        for s, occ in sorted(sym_index.items(), key=lambda kv: -len(kv[1])):
            if len(occ) < MIN_SYM_DOCS:
                continue
            occ.sort(key=lambda o: (o["order_key"], o["mtime"]))
            fh.write(json.dumps({
                "symbol": s, "n_docs": len(occ),
                "total_occurrences": sum(o["count"] for o in occ),
                "first": occ[0], "last": occ[-1],
                "lanes": sorted({o["lane"] for o in occ}),
                "occurrences": occ,
            }, ensure_ascii=False) + "\n")

    print(json.dumps({
        "documents_extracted": n,
        "distinct_symbols": len(sym_index),
        "symbols_in_2plus_docs": sum(1 for v in sym_index.values() if len(v) >= 2),
        "symbols_in_10plus_docs": sum(1 for v in sym_index.values() if len(v) >= 10),
    }, indent=2))


if __name__ == "__main__":
    main()
