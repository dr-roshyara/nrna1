#!/usr/bin/env python3
"""Inbound-citation check: which other places cite a changed or corrected claim?

PROPOSED for a Rulebook revision (S0100 recommendations 1b and 2); NOT IN FORCE. Read-only: writes nothing.

Usage: inbound_citations.py [--root DIR] "§5.7 C3" ["§8.11 C1" "1.14.3-2" CON-0145 …]
For each target it lists every line in another section file, CONCEPT-REGISTER.tsv, THEORY-MAP.md or BOOK-OUTLINE.md that
  - cites the claim exactly ("§5.7 C3", "§5.7, C3", "Ch. 5 §5.7 C3", "5.7 C3"; "1.14.3-2" for numbered claims), or
  - cites the concept ID hosted at the claim (found from its `[CON-nnnn · …]` tag), or
  - cites only the section (weaker; listed separately, so a person can check whether the claim is meant).
Output: target · kind (claim / concept / section-only) · file:line · the line, truncated. Exit 0.
Limits: explicit citations only. Paraphrase without a citation is not found; "C3" alone after a section cited
earlier in the paragraph is found only as section-only. The output is a list to read, not a verdict.
"""
import pathlib, re, sys

a = sys.argv[1:]
root = pathlib.Path(a[a.index('--root') + 1]) if '--root' in a else pathlib.Path(__file__).resolve().parents[2]
targets = [x for i, x in enumerate(a) if x != '--root' and (i == 0 or a[i - 1] != '--root')]
T = root / '06-theory'
M = T / 'manuscript'
files = sorted(M.glob('chapter_*/*.md')) + [T / 'CONCEPT-REGISTER.tsv', T / 'THEORY-MAP.md', M / 'BOOK-OUTLINE.md', T / 'BOOK-OUTLINE.md']
files = [f for f in files if f.exists()]


def home(sec):
    ch, s = sec.split('.')[:2]
    hits = list(M.glob(f'chapter_{int(ch):02d}/{int(ch):02d}.{int(s):02d}-*.md'))
    return hits[0] if hits else None


for tgt in targets:
    m = re.match(r'§?\s*(\d+)\.(\d+)\s*,?\s*(C\d+[a-z]?)?$', tgt.strip())
    n = re.match(r'(\d+)\.(\d+)\.(\d+)-(\d+)$', tgt.strip())
    pats, sec_pat, hf, cons = [], None, None, set()
    if m:
        ch, s, c = m.groups()
        hf = home(f'{ch}.{s}')
        sec_pat = re.compile(rf'§\s?{ch}\.{s}(?![\d.])')
        if c:
            pats.append(re.compile(rf'(?:§\s?)?\b{ch}\.{s}\s*,?\s*{c}\b'))
            if hf:
                t = hf.read_text(encoding='utf-8')
                head = re.search(rf'^\*\*{c}\.[^\n]*', t, re.M)
                if head:
                    cons |= set(re.findall(r'CON-\d{4}', head.group(0)))
    elif n:
        pats.append(re.compile(re.escape(tgt.strip()) + r'(?!\d)'))
        hf = home(f'{n.group(1)}.{n.group(2)}')
        sec_pat = re.compile(rf'§\s?{n.group(1)}\.{n.group(2)}(?![\d.])')
    elif re.match(r'CON-\d{4}$', tgt):
        cons.add(tgt)
    out = {'claim': [], 'concept': [], 'section-only': []}
    for f in files:
        same = hf is not None and f == hf
        for i, line in enumerate(f.read_text(encoding='utf-8').split('\n'), 1):
            where = f'{f.relative_to(root)}:{i}'
            if any(p.search(line) for p in pats):
                if not same:
                    out['claim'].append((where, line))
            elif any(re.search(rf'\b{c}\b', line) for c in cons):
                if not same:
                    out['concept'].append((where, line))
            elif sec_pat and sec_pat.search(line) and not same:
                out['section-only'].append((where, line))
    print(f'== {tgt}  (home: {hf.relative_to(root) if hf else "—"}; concept IDs at the claim: {sorted(cons) or "none"})')
    for k in ('claim', 'concept', 'section-only'):
        print(f'  -- {k}: {len(out[k])}')
        for w, l in out[k]:
            print(f'     {w}  {l.strip()[:160]}')
