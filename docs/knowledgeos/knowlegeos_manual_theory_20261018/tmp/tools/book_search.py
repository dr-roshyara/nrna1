#!/usr/bin/env python3
"""Book search (core-loop step 3, Principal 2026-10-19): before writing a new section, find the concept's existing homes in the book.

Searches the book's own text, not the corpus:
  - every chapter section (06-theory/manuscript/chapter_*/*.md), by heading of the enclosing claim or section;
  - the concept register (06-theory/CONCEPT-REGISTER.tsv): term and meaning;
  - the theory map (06-theory/THEORY-MAP.md).
Review files and TODOs are skipped (they are about the book, not the book).

Output: per term, the register concepts that match, then the sections that match, ranked by hit count, each with
its nearest heading and up to three line numbers. Paste the result (or --md) into the window's step-8 file as
"existing homes".

Usage:
  python3 -I book_search.py "actor|authoriz" "residual trust" [--md] [--max 12]
  Each argument is one term (a case-insensitive regex). Terms are reported separately.
Limits: a lexical search. A miss is not proof that the concept is absent (synonyms, other wording);
a hit is a place to read, not a finding.
"""
import csv, glob, pathlib, re, sys
from collections import defaultdict

W = pathlib.Path(__file__).resolve().parents[2]
T = W / '06-theory'
HEAD = re.compile(r'^(#{1,6} .+|\*\*C\d+[a-z]?\..+?\*\*)')


def args():
    a = sys.argv[1:]
    md = '--md' in a
    mx = 12
    if '--max' in a:
        k = a.index('--max'); mx = int(a[k + 1]); del a[k:k + 2]
    terms = [t for t in a if t != '--md']
    if not terms:
        print(__doc__); sys.exit(2)
    return terms, md, mx


def sections():
    for f in sorted(glob.glob(str(T / 'manuscript/chapter_*/*.md'))):
        p = pathlib.Path(f)
        if p.name == 'TODO.md':
            continue
        yield p, p.read_text(encoding='utf-8').split('\n')


def search(term, mx):
    pat = re.compile(term, re.IGNORECASE)
    reg = []
    for r in csv.reader(open(T / 'CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t'):
        if r and r[0].startswith('CON-') and (pat.search(r[1]) or pat.search(r[2])):
            reg.append((r[0], r[1][:70], r[7], r[5]))
    hits = defaultdict(list)          # (file, heading) -> [line numbers]
    for p, lines in sections():
        head = p.name
        for n, l in enumerate(lines, 1):
            m = HEAD.match(l)
            if m:
                head = re.sub(r'[*`]', '', m.group(1)).strip('# ')[:80]
            if pat.search(l):
                hits[(f'{p.parent.name}/{p.name}', head)].append(n)
    tmap = [n for n, l in enumerate((T / 'THEORY-MAP.md').read_text(encoding='utf-8').split('\n'), 1) if pat.search(l)]
    ranked = sorted(hits.items(), key=lambda kv: -len(kv[1]))[:mx]
    return reg, ranked, tmap, len(hits)


def main():
    terms, md, mx = args()
    for term in terms:
        reg, ranked, tmap, nsec = search(term, mx)
        if md:
            print(f'\n**Book search `{term}`:** {len(reg)} register concepts, {nsec} sections, {len(tmap)} theory-map lines.\n')
            if reg:
                print('| Concept | Term | Status | Home |\n|---|---|---|---|')
                for c in reg:
                    print(f'| {c[0]} | {c[1]} | {c[2]} | {c[3]} |')
            if ranked:
                print('\n| Section | Nearest heading | Hits | Lines |\n|---|---|---|---|')
                for (f, h), ls in ranked:
                    print(f'| {f} | {h} | {len(ls)} | {", ".join("L" + str(x) for x in ls[:3])} |')
        else:
            print(f'== {term}: {len(reg)} concepts · {nsec} sections · map lines {tmap[:8]}')
            for c in reg:
                print(f'   {c[0]} [{c[2]}] {c[1]}  ({c[3]})')
            for (f, h), ls in ranked:
                print(f'   {len(ls):>3}  {f}  §{h}  {ls[:3]}')
    print('\n(lexical search: a miss is not absence; a hit is a place to read)')


if __name__ == '__main__':
    main()
