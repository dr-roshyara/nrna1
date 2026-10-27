#!/usr/bin/env python3
"""Check every "…" quote on a line that cites EXT-nnn against those external files (EXTERNAL-SOURCES.md maps IDs to files).
Usage: python3 -I ext_quote_check.py <file.md>"""
import re, sys, pathlib
W = pathlib.Path(__file__).resolve().parents[2]
D = W.parent / 'brainstorming/philosophical_questions'
m = {g[1]: g[2] for l in open(W / '06-theory/EXTERNAL-SOURCES.md', encoding='utf-8') if (g := re.match(r'\| (EXT-\d{3}) \| `([^`]+)`', l))}
norm = lambda t: re.sub(r'[\s*_`$\\]+', ' ', t).strip().lower()
ok = bad = 0
for n, l in enumerate(open(sys.argv[1], encoding='utf-8'), 1):
    srcs = [e for e in re.findall(r'EXT-\d{3}', l) if e in m]
    for q in re.findall(r'"([^"]{8,})"', l) if srcs else []:
        if any(norm(q) in norm((D / m[e]).read_text(encoding='utf-8')) for e in srcs): ok += 1
        else: bad += 1; print(f'L{n} NOT FOUND in {srcs}: "{q[:80]}"')
print(f'{pathlib.Path(sys.argv[1]).name}: quotes found {ok}, not found {bad}')
