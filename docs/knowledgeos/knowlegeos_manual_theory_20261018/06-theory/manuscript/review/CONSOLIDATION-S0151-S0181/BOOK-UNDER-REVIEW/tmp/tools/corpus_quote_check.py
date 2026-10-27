#!/usr/bin/env python3
"""Check every "…" [Snnnn Lx(–y)] quote in a book file against the cited corpus lines (path from 02-FILES.jsonl).
Elided quotes ("…") are checked segment by segment, in order.
Usage: python3 -I corpus_quote_check.py <section.md> [...]"""
import re, sys, json, pathlib
W = pathlib.Path(__file__).resolve().parents[2]
R = W.parents[2]
paths = {o['source_id']: o['path'] for o in (json.loads(l) for l in open(W / '02-FILES.jsonl', encoding='utf-8'))}
_norm0 = lambda t: re.sub(r'[\s*_`$\\]+', ' ', t)
# consolidation S0101-S0130 (B, inherited failures): markup removed beside punctuation leaves a stray space
# ("**derived**," -> " derived ,"); spaces next to punctuation and brackets are dropped on both sides
norm = lambda t: re.sub(r'\s*([,.;:!?)\]])', r'\1', re.sub(r'([(\[])\s*', r'\1', _norm0(t))).strip().lower()
for f in sys.argv[1:]:
    ok = bad = 0
    for n, line in enumerate(open(f, encoding='utf-8'), 1):
        for q, sid, a, b in re.findall(r'"([^"]{6,})"\s*\[(S\d{4}) L(\d+)(?:[–-](\d+))?', line):
            src = (R / paths[sid]).read_text(encoding='utf-8').split('\n')
            a = int(a); b = int(b or a)
            text, pos, hit = norm(' '.join(src[a - 1:b])), 0, True
            for seg in [norm(x) for x in re.split(r'…|\.\.\.', q) if norm(x)]:   # an elided quote: each segment, in order
                k = text.find(seg, pos)
                if k < 0: hit = False; break
                pos = k + len(seg)
            if hit: ok += 1
            else: bad += 1; print(f'{pathlib.Path(f).name} L{n} NOT FOUND [{sid} L{a}-{b}]: "{q[:70]}"')
    print(f'{pathlib.Path(f).name}: {ok} found, {bad} not found')
