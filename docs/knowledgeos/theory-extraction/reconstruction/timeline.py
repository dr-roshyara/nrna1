#!/usr/bin/env python3
"""
LAYER 3 (mechanical spine) — birth points, timeline, and TYPE-CHANGE detection.

Consumes 02-DOC-RECORDS.jsonl. Emits:
  03-BIRTH-POINTS.tsv     symbol -> first document in ARGUMENT order (+ mtime order, if different)
  03-THEORY-TIMELINE.tsv  documents in argument order, with what each one first introduces
  03-FORM-CHANGES.jsonl   per symbol: the ordered sequence of distinct FORMS it takes

A "form" is a mechanically normalised shape of the symbol's usage:
    SIG:<domain>-><codomain>    a typed signature
    APP/<n>                     applied to n arguments
    TUPLE/<n>                   defined as an n-component tuple
    SET                         defined as a set / set-builder
    ELEM:<container>            declared a member of something
    REL:<op>                    related by =, ⊆, ≅, ⟺ ...
Form changes are CANDIDATE type changes. They are evidence to read, never a verdict:
chronology does not prove derivation, and a shared symbol does not prove a shared object.
"""
import json, re, collections, os

OUT = os.path.dirname(os.path.abspath(__file__))

def norm_ws(s):
    return re.sub(r'\s+', ' ', s).strip()

ARROW = r'(?:\\rightarrow|\\to|\\rightharpoonup|→|⇀|->)'
SUBSET = r'(?:\\subseteq|⊆|\\subset|⊂)'
CONG = r'(?:\\cong|≅|\\equiv|≡|⟺|\\iff)'

def forms_for(sym, math_blocks):
    """Extract the shapes `sym` takes inside these math blocks."""
    out = []
    esc = re.escape(sym)
    # a symbol may be written \mathcal{R}_req or R_req; match the tail token too
    alt = esc
    if sym.startswith("\\mathcal{"):
        inner = sym[len("\\mathcal{"):sym.index("}")]
        tail = sym[sym.index("}")+1:]
        alt = r'(?:' + esc + r'|' + re.escape(inner + tail) + r')'
    for raw in math_blocks:
        b = norm_ws(raw)
        if not re.search(alt, b):
            continue
        # typed signature:  S : A -> B
        m = re.search(alt + r'\s*(?::|\\colon)\s*(.{1,80}?)\s*' + ARROW + r'\s*([^\s,;]{1,60})', b)
        if m:
            out.append(("SIG:%s->%s" % (norm_ws(m.group(1))[:48], norm_ws(m.group(2))[:32]), b[:300])); continue
        # application:  S(a,b,c)
        m = re.search(alt + r'\s*\(([^()]{0,160})\)', b)
        if m:
            args = m.group(1).strip()
            n = 0 if args == "" else args.count(",") + 1
            out.append(("APP/%d" % n, b[:300])); continue
        # tuple definition:  S = (a,b,c)
        m = re.search(alt + r'\s*=\s*\(([^()]{0,200})\)', b)
        if m:
            out.append(("TUPLE/%d" % (m.group(1).count(",") + 1), b[:300])); continue
        # set definition:  S = { ... }
        if re.search(alt + r'\s*=\s*(?:\\\{|\{|\\mathcal)', b):
            out.append(("SET", b[:300])); continue
        # membership / inclusion
        m = re.search(alt + r'\s*(?:\\in|∈)\s*([^\s,;]{1,40})', b)
        if m:
            out.append(("ELEM:%s" % norm_ws(m.group(1))[:32], b[:300])); continue
        m = re.search(alt + r'\s*' + SUBSET + r'\s*([^\s,;]{1,40})', b)
        if m:
            out.append(("SUBSET:%s" % norm_ws(m.group(1))[:32], b[:300])); continue
        m = re.search(alt + r'\s*' + CONG + r'\s*([^\s,;]{1,40})', b)
        if m:
            out.append(("CONG:%s" % norm_ws(m.group(1))[:32], b[:300])); continue
        if re.search(alt + r'\s*=', b):
            out.append(("EQ", b[:300]))
    return out


def main():
    docs = [json.loads(l) for l in open(os.path.join(OUT, "02-DOC-RECORDS.jsonl"))]
    docs.sort(key=lambda d: (d["order_key"], d["queue_position"]))

    seen = {}
    timeline = []
    for i, d in enumerate(docs):
        introduced = [s for s in d["symbols"] if s not in seen]
        for s in introduced:
            seen[s] = d
        timeline.append((d, introduced))

    # by mtime, to expose where writing order != argument order
    by_mtime = sorted(docs, key=lambda d: (d["mtime_key"], d["queue_position"]))
    mtime_first = {}
    for d in by_mtime:
        for s in d["symbols"]:
            mtime_first.setdefault(s, d)

    # ---- birth points ----
    docspread = collections.Counter()
    for d in docs:
        for s in d["symbols"]:
            docspread[s] += 1
    with open(os.path.join(OUT, "03-BIRTH-POINTS.tsv"), "w") as fh:
        fh.write("symbol\tn_docs\tbirth_doc\tbirth_order_key\tbirth_step\tbirth_mtime\tbirth_lane\t"
                 "mtime_first_doc\tmtime_first_mtime\torder_disagrees\tbirth_path\n")
        for s, n in docspread.most_common():
            a = seen[s]; b = mtime_first[s]
            fh.write("\t".join([s, str(n), a["document_id"], a["order_key"], a["step"] or "",
                                a["mtime_key"], a["lane"], b["document_id"], b["mtime_key"],
                                "YES" if a["document_id"] != b["document_id"] else "",
                                a["path"]]) + "\n")

    # ---- timeline ----
    with open(os.path.join(OUT, "03-THEORY-TIMELINE.tsv"), "w") as fh:
        fh.write("seq\tdocument_id\torder_key\torder_evidence\tstep\tmtime\tlane\tbytes\tn_math\t"
                 "n_introduced\tintroduced_top\tpath\n")
        for i, (d, intro) in enumerate(timeline, 1):
            intro_sorted = sorted(intro, key=lambda s: -d["symbols"].get(s, 0))
            fh.write("\t".join([str(i), d["document_id"], d["order_key"], d["order_evidence"],
                                d["step"] or "", d["mtime_key"], d["lane"], str(d["bytes"]),
                                str(d["n_math"]), str(len(intro)),
                                ",".join(intro_sorted[:14]), d["path"]]) + "\n")

    # ---- form changes ----
    targets = [s for s, n in docspread.items() if n >= 3]
    n_changed = 0
    with open(os.path.join(OUT, "03-FORM-CHANGES.jsonl"), "w") as fh:
        for s in targets:
            seq = []
            for d in docs:
                if s not in d["symbols"]:
                    continue
                fs = forms_for(s, d["math"])
                if not fs:
                    continue
                shapes = []
                for shape, ex in fs:
                    if shape not in [x[0] for x in shapes]:
                        shapes.append((shape, ex))
                seq.append({"doc": d["document_id"], "order_key": d["order_key"],
                            "step": d["step"], "mtime": d["mtime_key"], "lane": d["lane"],
                            "shapes": [x[0] for x in shapes],
                            "examples": [x[1] for x in shapes][:3],
                            "path": d["path"]})
            if not seq:
                continue
            distinct = []
            for e in seq:
                for sh in e["shapes"]:
                    if sh not in distinct:
                        distinct.append(sh)
            if len(distinct) < 2:
                continue
            n_changed += 1
            fh.write(json.dumps({"symbol": s, "n_docs": docspread[s],
                                 "n_distinct_shapes": len(distinct),
                                 "shape_sequence": distinct,
                                 "trace": seq[:60]}, ensure_ascii=False) + "\n")

    print(json.dumps({
        "documents": len(docs),
        "symbols_with_birth_point": len(seen),
        "symbols_whose_birth_differs_by_ordering_key":
            sum(1 for s in seen if seen[s]["document_id"] != mtime_first[s]["document_id"]),
        "symbols_examined_for_form_change": len(targets),
        "symbols_with_2plus_distinct_shapes": n_changed,
    }, indent=2))


if __name__ == "__main__":
    main()
