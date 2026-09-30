#!/usr/bin/env python3
"""P3b S5 quote checker (plan §B.8: "a script checks every quote"; ops spec §1.3). Infrastructure; G-LOG-0041.

For every quote in a batch's outputs (object, register and P1-gap records) that is paired with a `source_id`, the
checker confirms the quote occurs in that source's content: exactly, or after whitespace normalization (reported
separately). Content is read ONLY through the seal-aware `discovery_resolver()`; a sealed file is refused by the
resolver and counted as a failure (a seal breach, P3B-ESC). Checker reads are not agent reads and are not written to
the batch READ-LOG.

  p3b_s5_quotes.py --batch OB#### [--run RUN] [--root DIR] [--out FILE]

Output: audit-p3b/S5-QUOTES-<run>.json ({header: §19.5, body}); exit 0 PASS (no miss), 1 FAIL, 2 refused.
"""
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
c = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(c)

QUOTE_KEYS = ("quote",)
WS = re.compile(r"\s+")


def iter_quotes(obj, sid=None, path=""):
    """Yield (source_id, quote, json-path) for every quote paired with the nearest enclosing source_id."""
    if isinstance(obj, dict):
        sid = obj.get("source_id", sid) if isinstance(obj.get("source_id", sid), str) else sid
        for k, v in obj.items():
            p = f"{path}.{k}"
            if k in QUOTE_KEYS and isinstance(v, str) and v.strip():
                yield sid, v, p
            else:
                yield from iter_quotes(v, sid, p)
    elif isinstance(obj, list):
        for i, x in enumerate(obj):
            yield from iter_quotes(x, sid, f"{path}[{i}]")


def check(records, resolver):
    cache, res = {}, {"exact": 0, "whitespace": 0, "miss": [], "no_source_id": 0, "refused": 0}
    for ri, rec in enumerate(records):
        for sid, q, p in iter_quotes(rec):
            if not sid or not re.fullmatch(r"S\d{4}", sid):
                res["no_source_id"] += 1
                continue
            if sid not in cache:
                try:
                    cache[sid] = resolver.read_many([sid])[sid].decode("utf-8", errors="replace")
                except c.s3.IdentityError:
                    cache[sid] = None
            text = cache[sid]
            if text is None:
                res["refused"] += 1
                res["miss"].append({"record": ri, "path": p, "source_id": "S####(refused)"})
            elif q in text:
                res["exact"] += 1
            elif WS.sub(" ", q).strip() in WS.sub(" ", text):
                res["whitespace"] += 1
            else:
                res["miss"].append({"record": ri, "path": p, "source_id": sid, "quote_prefix": q[:40]})
    return res


def main(argv=None, resolver=None):
    a = argv if argv is not None else sys.argv[1:]
    if "--batch" not in a or not re.fullmatch(r"OB\d{4}", a[a.index("--batch") + 1]):
        print(__doc__, file=sys.stderr)
        return 2
    bid = a[a.index("--batch") + 1]
    run = a[a.index("--run") + 1] if "--run" in a else bid + "-R2"
    root = a[a.index("--root") + 1] if "--root" in a else c.CR
    out = a[a.index("--out") + 1] if "--out" in a else os.path.join(root, "audit-p3b", f"S5-QUOTES-{run}.json")
    c.assert_sealed()
    resolver = resolver or c.dio.discovery_resolver()
    records, inputs = [], []
    for name in ("objects", "register", "p1-gap-capture"):
        rel = f"ledger-p3b-r2/{run}/{name}.jsonl"
        c.check_inputs([rel])
        fp = os.path.join(root, rel)
        if os.path.exists(fp):
            inputs.append(fp)
            with open(fp, encoding="utf-8") as f:
                records += [json.loads(l) for l in f if l.strip()]
    res = check(records, resolver)
    body = {"batch_id": bid, "run_id": run, "records": len(records), "exact": res["exact"],
            "whitespace_normalized": res["whitespace"], "misses": res["miss"], "no_source_id": res["no_source_id"],
            "refused_reads": res["refused"], "result": "PASS" if not res["miss"] else "FAIL"}
    txt = c.canon(body)
    if os.path.exists(out):
        print(f"REFUSED: {out} exists (append-only)", file=sys.stderr)
        return 2
    os.makedirs(os.path.dirname(out), exist_ok=True)
    hdr = c.header(__file__, [], {"batch_id": bid, "run_id": run}, txt,
                   {"input_files_sha256": {os.path.relpath(x, root): c.sha256_file(x) for x in inputs}})
    with open(out, "w", encoding="utf-8") as f:
        f.write(c.canon({"header": hdr, "body": body}) + "\n")
    print(f"S5 QUOTES {run}: {res['exact']} exact, {res['whitespace']} whitespace, {len(res['miss'])} misses → {body['result']}")
    c.assert_sealed()
    return 0 if not res["miss"] else 1


if __name__ == "__main__":
    sys.exit(main())
