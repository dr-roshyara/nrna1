#!/usr/bin/env python3
"""Book search (core-loop step 3): before writing a new section, find the concept's existing homes in the book.

Two modes, run both:

1. LEXICAL (default): each argument is a case-insensitive regex, searched in
     - every chapter section (06-theory/manuscript/chapter_*/*.md), by the nearest heading;
     - the concept register (term and meaning);
     - the theory map.
2. MEANING (--meaning "a sentence describing the idea"): ranks register concepts (term + meaning) and section
   paragraphs by TF-IDF cosine similarity to the description (Principal, 2026-10-19: "search by meaning").
     - tokens are lower-cased and lightly stemmed (plural / -ing / -ed / -ion / -ity endings);
     - the query is expanded with the synonym sets in tmp/tools/book_synonyms.tsv (one set per line, tab-separated);
     - each query token is also matched fuzzily (difflib ratio ≥ 0.86) against the book's vocabulary, so spelling
       variants and compounds ("serialise" / "serialization") count.
   Results above --min (default 0.08) are listed, best first.

Reporting rule: a search with no result is reported as "nothing found by this search" — never as "the idea is
new" and never as "no home". A hit is a place to read, not a finding.

Usage:
  python3 -I book_search.py "regex" ["regex" …] [--max 12] [--md]
  python3 -I book_search.py --meaning "the session must hold the current rule when it starts" [--max 10] [--min 0.08]
"""
import csv, difflib, glob, math, pathlib, re, sys
from collections import Counter, defaultdict

W = pathlib.Path(__file__).resolve().parents[2]
T = W / '06-theory'
SYN = pathlib.Path(__file__).with_name('book_synonyms.tsv')
HEAD = re.compile(r'^(#{1,6} .+|\*\*C\d+[a-z]?\..+?\*\*)')
STOP = set('a an and are as at be been but by can for from has have if in into is it its not of on or so such than that the their then there these this those to was were which while who will with without within also only may must one two same each any all no nor its it\'s does do did very more most'.split())


def args():
    a = sys.argv[1:]
    opt = {'--max': 12, '--min': 0.08}
    for k in ('--max', '--min'):
        if k in a:
            i = a.index(k); opt[k] = (int if k == '--max' else float)(a[i + 1]); del a[i:i + 2]
    meaning = None
    if '--meaning' in a:
        i = a.index('--meaning'); meaning = a[i + 1]; del a[i:i + 2]
    md = '--md' in a
    terms = [t for t in a if t != '--md']
    if not terms and meaning is None:
        print(__doc__); sys.exit(2)
    return terms, meaning, md, opt


def sections():
    for f in sorted(glob.glob(str(T / 'manuscript/chapter_*/*.md'))):
        p = pathlib.Path(f)
        if p.name == 'TODO.md':
            continue
        yield p, p.read_text(encoding='utf-8').split('\n')


def register():
    return [r for r in csv.reader(open(T / 'CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t') if r and r[0].startswith('CON-')]


# ---------- lexical ----------
def lexical(term, mx):
    pat = re.compile(term, re.IGNORECASE)
    reg = [(r[0], r[1][:70], r[7], r[5]) for r in register() if pat.search(r[1]) or pat.search(r[2])]
    hits = defaultdict(list)
    for p, lines in sections():
        head = p.name
        for n, l in enumerate(lines, 1):
            m = HEAD.match(l)
            if m:
                head = re.sub(r'[*`]', '', m.group(1)).strip('# ')[:80]
            if pat.search(l):
                hits[(f'{p.parent.name}/{p.name}', head)].append(n)
    tmap = [n for n, l in enumerate((T / 'THEORY-MAP.md').read_text(encoding='utf-8').split('\n'), 1) if pat.search(l)]
    return reg, sorted(hits.items(), key=lambda kv: -len(kv[1]))[:mx], tmap, len(hits)


# ---------- meaning ----------
def stem(w):
    for suf in ('ations', 'ation', 'itions', 'ition', 'ities', 'ity', 'ings', 'ing', 'ions', 'ion', 'ed', 'es', 's'):
        if len(w) > len(suf) + 3 and w.endswith(suf):
            return w[:-len(suf)]
    return w


def tokens(text):
    return [stem(w) for w in re.findall(r'[a-zà-ÿ0-9]+', text.lower()) if w not in STOP and len(w) > 2]


def synonym_sets():
    sets = []
    if SYN.exists():
        for line in SYN.read_text(encoding='utf-8').split('\n'):
            if line.strip() and not line.startswith('#'):
                sets.append({stem(w) for w in re.split(r'\t+', line.strip().lower()) for w in [w] if w})
    return sets


def documents():
    docs = []
    for r in register():
        docs.append(('concept', f'{r[0]} [{r[7]}] {r[1][:60]}', r[5], f'{r[1]} {r[2]}'))
    for p, lines in sections():
        head, buf, start = p.name, [], 1
        for n, l in enumerate(lines + [''], 1):
            m = HEAD.match(l)
            if m:
                head = re.sub(r'[*`]', '', m.group(1)).strip('# ')[:80]
            if l.strip() == '' or m:
                if buf:
                    docs.append(('section', f'{p.parent.name}/{p.name} §{head}', f'L{start}', ' '.join(buf)))
                buf, start = ([l] if m else []), n + (0 if m else 1)
            else:
                buf.append(l)
    return docs


def meaning_search(query, mx, mn):
    docs = documents()
    toks = [Counter(tokens(d[3])) for d in docs]
    df = Counter(t for c in toks for t in c)
    N = len(docs)
    idf = {t: math.log((N + 1) / (df[t] + 1)) + 1 for t in df}
    vocab = list(df)
    q = Counter(tokens(query))
    expanded = Counter(q)
    for s in synonym_sets():
        if s & set(q):
            for w in s:
                expanded[w] = max(expanded[w], 0.7)
    for w in list(q):
        for v in difflib.get_close_matches(w, vocab, n=3, cutoff=0.86):
            expanded[v] = max(expanded[v], 0.6)
    qv = {t: wt * idf.get(t, 0) for t, wt in expanded.items() if t in idf}
    qn = math.sqrt(sum(v * v for v in qv.values())) or 1
    out = []
    for d, c in zip(docs, toks):
        dv = {t: (1 + math.log(f)) * idf[t] for t, f in c.items()}
        dn = math.sqrt(sum(v * v for v in dv.values())) or 1
        sc = sum(qv[t] * dv.get(t, 0) for t in qv) / (qn * dn)
        if sc >= mn:
            out.append((sc, d))
    out.sort(key=lambda x: -x[0])
    return [o for o in out if o[1][0] == 'concept'][:mx], [o for o in out if o[1][0] == 'section'][:mx], sorted(expanded)


def main():
    terms, meaning, md, opt = args()
    for term in terms:
        reg, ranked, tmap, nsec = lexical(term, opt['--max'])
        print(f'== {term}: {len(reg)} concepts · {nsec} sections · map lines {tmap[:8]}')
        if not reg and not ranked and not tmap:
            print('   nothing found by this search')
        for c in reg:
            print(f'   {c[0]} [{c[2]}] {c[1]}  ({c[3]})')
        for (f, h), ls in ranked:
            print(f'   {len(ls):>3}  {f}  §{h}  {ls[:3]}')
    if meaning is not None:
        cons, secs, used = meaning_search(meaning, opt['--max'], opt['--min'])
        print(f'== meaning: "{meaning}"  (terms used after synonyms and fuzzy matching: {", ".join(used[:25])})')
        if not cons and not secs:
            print(f'   nothing found by this search (TF-IDF cosine ≥ {opt["--min"]})')
        for sc, d in cons:
            print(f'   {sc:.2f}  {d[1]}  ({d[2]})')
        for sc, d in secs:
            print(f'   {sc:.2f}  {d[1]}  {d[2]}')
    print('\n(a miss means "nothing found by this search", not that the idea is new; a hit is a place to read)')


if __name__ == '__main__':
    main()
