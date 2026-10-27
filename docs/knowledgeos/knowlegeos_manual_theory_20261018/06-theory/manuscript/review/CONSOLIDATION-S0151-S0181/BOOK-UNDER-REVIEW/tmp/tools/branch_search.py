#!/usr/bin/env python3
"""Topic search for a research branch: searches ONLY files on the allowed list (CORPUS-ALLOWED.tsv).
Files not on the list are never opened, so sealed, hold-out and quarantine files cannot surface.

Usage: python3 -I branch_search.py "<regex>" [max_files] [--log "BRANCH-nn: limits"]
With --log, a 'scan' row is written to the read log: pattern, scope (the allowed list, by sha256), match count, limits.
A scan never advances the reading position (close_source.py counts only full/skim rows).
Output: one row per matching allowed file, in index order: S-id, path, hit count, first hit lines.
"""
import sys, re, csv, json, pathlib, hashlib, datetime

W = pathlib.Path(__file__).resolve().parents[2]
REPO = W.parents[2]
argv = sys.argv[1:]
logmsg = None
if '--log' in argv:
    k = argv.index('--log'); logmsg = argv[k + 1]; del argv[k:k + 2]
pat = re.compile(argv[0], re.IGNORECASE)
limit = int(argv[1]) if len(argv) > 1 else 60
order = {r['source_id']: i for i, r in enumerate(map(json.loads, open(W / '02-FILES.jsonl', encoding='utf-8')))}
rows = list(csv.DictReader(open(W.parent / 'knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv', encoding='utf-8'), delimiter='\t'))
hits = []
for r in rows:
    p = REPO / r['path']
    if not p.is_file():
        continue
    try:
        lines = p.read_text(encoding='utf-8', errors='replace').split('\n')
    except Exception:
        continue
    found = [i + 1 for i, l in enumerate(lines) if pat.search(l)]
    if found:
        hits.append((order.get(r['source_id'], 10**9), r['source_id'], r['path'], len(found), found[:6]))
hits.sort()
print(f'pattern {argv[0]!r}: {len(hits)} allowed files match (of {len(rows)} allowed)')
if logmsg:
    al = W.parent / 'knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv'
    now = datetime.datetime.now(datetime.timezone.utc).strftime('%Y-%m-%dT%H:%M:%SZ')
    with open(W / 'tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv', 'a', encoding='utf-8') as fh:
        fh.write(f"{now}\tSCAN\tscope: CORPUS-ALLOWED.tsv ({len(rows)} files)\t{hashlib.sha256(al.read_bytes()).hexdigest()}\t{len(hits)}\tscan\tpattern: {argv[0]}\t{logmsg}; word search only; matches are findings within scope, not proof of absence\n")
    print('scan row written')
for _, sid, path, n, first in hits[:limit]:
    print(f'{sid}\t{n}\t{path}\tL{",".join(map(str, first))}')
