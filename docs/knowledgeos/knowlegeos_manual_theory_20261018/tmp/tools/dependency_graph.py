#!/usr/bin/env python3
"""Generated dependency graph of concepts and sections (Rulebook §12d, METHOD-VERSIONS entry 49).

Nodes:
  - sections: one per section file of the book (06-theory/manuscript/chapter_*/NN.MM-*.md), named by its number, e.g. §8.6;
  - concepts: one per register row (CON-nnnn), with its status.

Edges, all from EXPLICIT references only ("X → Y" means X depends on Y, so a change to Y may affect X):
  1. a section that cites a concept ID (`CON-nnnn`) or another section (`§N.M`, `§N.M.K`, `Ch. N §N.M`) depends on it;
  2. a section that hosts a concept (the register's book_location) depends on that concept: if the concept falls,
     its host section changes in substance;
  3. a concept depends on every concept named in its register row's "same_subject_as", "basis" or "meaning" fields,
     and on every section cited there.
  Self-references are dropped. Sub-section citations (§1.14.3) are mapped to the section file (§1.14); the cited
  sub-section is kept as a label on the edge.

What the graph does NOT see (stated in every report): implicit dependencies — a claim that uses an idea without citing
its home, a reading that relies on a source another section also reads, a citation to a chapter without a § number.
Those remain the task of core-loop step 8 and of the reviewers. No item is invalidated automatically: the list is a
list of items to reassess on evidence (Rulebook §12d).

Usage:
  python3 -I dependency_graph.py --down CON-0133 [CON-0122 §9.2 …]   # every downstream item, transitively, with one path each
  python3 -I dependency_graph.py --up §8.6                             # what an item depends on (direct)
  python3 -I dependency_graph.py --stats                               # node/edge counts, isolated sections, cycles
  add --md for a markdown table
"""
import csv, glob, pathlib, re, sys
from collections import defaultdict, deque

W = pathlib.Path(__file__).resolve().parents[2]
T = W / '06-theory'
M = T / 'manuscript'
SEC = re.compile(r'(?:Ch(?:apter)?\.?\s*\d+\s*)?§\s?(\d+)\.(\d+)(?:\.(\d+[a-z]?))?')
CON = re.compile(r'\bCON-(\d{4})\b')


def section_files():
    out = {}
    for f in sorted(glob.glob(str(M / 'chapter_*/[0-9][0-9].[0-9][0-9]*-*.md'))):
        p = pathlib.Path(f)
        m = re.match(r'(\d\d)\.(\d\d)', p.name)
        if not m or m.group(2) in ('00', '99'):
            continue
        out[f'§{int(m.group(1))}.{int(m.group(2))}'] = p
    return out


def register():
    rows = [r for r in csv.reader(open(T / 'CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t') if r and r[0].startswith('CON-')]
    return {r[0]: r for r in rows}


def sec_key(m):
    return f'§{int(m.group(1))}.{int(m.group(2))}'


def build():
    secs, reg = section_files(), register()
    edges = defaultdict(dict)          # edges[x][y] = label   (x depends on y)
    for s, p in secs.items():
        text = p.read_text(encoding='utf-8')
        for m in SEC.finditer(text):
            k = sec_key(m)
            if k != s and k in secs:
                edges[s].setdefault(k, m.group(0).strip())
        for m in CON.finditer(text):
            c = f'CON-{m.group(1)}'
            if c in reg:
                edges[s].setdefault(c, 'cites')
    for c, r in reg.items():
        loc = r[5] if len(r) > 5 else ''
        fm = re.search(r'chapter_\d\d/(\d\d)\.(\d\d)', loc)
        if fm:
            host = f'§{int(fm.group(1))}.{int(fm.group(2))}'
            if host in secs:
                edges[host].setdefault(c, 'hosts')
        for field in (r[6] if len(r) > 6 else '', r[8] if len(r) > 8 else '', r[2] if len(r) > 2 else ''):
            for m in CON.finditer(field):
                d = f'CON-{m.group(1)}'
                if d != c and d in reg:
                    edges[c].setdefault(d, 'register')
            for m in SEC.finditer(field):
                k = sec_key(m)
                if k in secs:
                    edges[c].setdefault(k, 'register ' + m.group(0).strip())
    return secs, reg, edges


def reverse(edges):
    rev = defaultdict(dict)
    for x, ys in edges.items():
        for y, lab in ys.items():
            rev[y][x] = lab
    return rev


def downstream(rev, start):
    """Breadth-first: every item that depends, directly or transitively, on `start`; with one shortest path each."""
    seen, q, out = {start: [start]}, deque([start]), []
    while q:
        y = q.popleft()
        for x in sorted(rev.get(y, {})):
            if x not in seen:
                seen[x] = seen[y] + [x]
                out.append((x, seen[x]))
                q.append(x)
    return out


def norm(a):
    a = a.strip()
    if re.fullmatch(r'\d{4}', a):
        return 'CON-' + a
    m = SEC.fullmatch(a) or SEC.match(a)
    return sec_key(m) if m and a.startswith('§') else a


def cycles(edges, nodes):
    """Strongly connected components with more than one node (Tarjan)."""
    idx, low, st, on, out, n = {}, {}, [], set(), [], [0]
    sys.setrecursionlimit(10000)

    def sc(v):
        idx[v] = low[v] = n[0]; n[0] += 1; st.append(v); on.add(v)
        for w in edges.get(v, {}):
            if w not in idx:
                sc(w); low[v] = min(low[v], low[w])
            elif w in on:
                low[v] = min(low[v], idx[w])
        if low[v] == idx[v]:
            comp = []
            while True:
                w = st.pop(); on.discard(w); comp.append(w)
                if w == v:
                    break
            if len(comp) > 1:
                out.append(sorted(comp))
    for v in nodes:
        if v not in idx:
            sc(v)
    return out


LIMIT = '(Limit, Rulebook §12d: this graph sees only explicit references — CON-IDs and § citations in the sections, host sections, and register fields. Implicit dependencies remain the task of step 8 and of the reviewers. Nothing is invalidated automatically.)'


def main():
    a = sys.argv[1:]
    md = '--md' in a
    secs, reg, edges = build()
    rev = reverse(edges)
    status = lambda x: f' [{reg[x][7]}]' if x in reg else ''
    if '--stats' in a or not a:
        nodes = set(secs) | set(reg)
        ne = sum(len(v) for v in edges.values())
        iso = [s for s in secs if not edges.get(s) and not rev.get(s)]
        print(f'nodes: {len(secs)} sections, {len(reg)} concepts; edges: {ne}')
        print(f'sections with no explicit link in or out: {iso or "none"}')
        cy = cycles(edges, sorted(nodes))
        print(f'cycles (mutual citation groups, not errors in themselves): {len(cy)}')
        for c in cy[:10]:
            print('   ', ' ↔ '.join(c[:12]) + (' …' if len(c) > 12 else ''))
    if '--up' in a:
        for x in a[a.index('--up') + 1:]:
            if x.startswith('--'):
                break
            x = norm(x)
            print(f'== {x}{status(x)} depends on (direct): ' + ', '.join(f'{y}{status(y)}' for y in sorted(edges.get(x, {}))))
    if '--down' in a:
        for x in a[a.index('--down') + 1:]:
            if x.startswith('--'):
                break
            x = norm(x)
            ds = downstream(rev, x)
            if md:
                print(f'\n**{x}{status(x)}** — {len(ds)} downstream item(s)\n\n| Item | Status | Path (depends on …) |\n|---|---|---|')
                for y, path in ds:
                    print(f'| {y} | {reg[y][7] if y in reg else "section"} | {" ← ".join(reversed(path))} |')
            else:
                print(f'== {x}{status(x)}: {len(ds)} downstream item(s)')
                for y, path in ds:
                    print(f'   {y}{status(y)}   via {" ← ".join(reversed(path))}')
    print('\n' + LIMIT)


if __name__ == '__main__':
    main()
