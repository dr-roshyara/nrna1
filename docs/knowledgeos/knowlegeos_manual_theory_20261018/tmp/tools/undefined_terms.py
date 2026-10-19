#!/usr/bin/env python3
"""Undefined-term candidate check, narrowed.

PROPOSED for a Rulebook revision (S0100 recommendation 1c); NOT IN FORCE. Read-only: writes nothing.

Usage: undefined_terms.py [--root DIR] [--sections GLOB …] [--no-corpus]
Tokens: identifiers (e.g. RX-3, BC-7, OQ-5: [A-Z][A-Za-z]{0,6}-\\d+, or [A-Z]{2,}\\d+) and words ([a-z]{4,}, plural folded).
A token is DEFINED if the book, map or register shows a definition form for it: a bold or table-first-column entry
followed by a gloss (`**X**:` `**X** (` `**X** —` `| X |` `- **X**`), "X: " at a line start, "X means"/"X is the",
an expansion "X (Capitalised …)", or a register term containing it.
Families with a defining table are excluded (CON-, S-IDs, E-S…, F-forks in the map, T-/SEQ- to-dos, RC-, PS8-).
Tiers (output, most suspect first):
  A  undefined, and absent from every allowed corpus source: an identifier or word that nothing in the corpus or
     the book explains (planted or fabricated codes, invented terms);
  B  undefined identifier present in the corpus: a source code used without a book definition (first-use rule);
  C  undefined word present in the corpus, ranked: occurs in >= 3 sections, >= 8 times, never defined, and is
     marked as a term in the corpus (backticked or bold at least twice). Ranked by section count. Judgement needed.
Output: tier · token · book count · sections · corpus files · first location. Exit 0.
Limits: definition forms are heuristics (a gloss in running prose may be missed: a false positive; a bold word with
any colon counts as defined: a false negative). Tier C is a reading list, not a finding. Corpus scan reads the
allowed list only (CORPUS-ALLOWED.tsv), never sealed or hold-out files.
"""
import collections, csv, pathlib, re, sys

a = sys.argv[1:]
root = pathlib.Path(a[a.index('--root') + 1]) if '--root' in a else pathlib.Path(__file__).resolve().parents[2]
use_corpus = '--no-corpus' not in a
T = root / '06-theory'
M = T / 'manuscript'
REPO = pathlib.Path(__file__).resolve().parents[5]
secs = [f for f in sorted(M.glob('chapter_*/*.md')) if re.match(r'\d\d\.\d\d', f.name)]
aux = [p for p in (T / 'THEORY-MAP.md', T / 'CONCEPT-REGISTER.tsv') if p.exists()]
ID = re.compile(r'(?<![\w-])([A-Z][A-Za-z]{0,6}-\d+[a-z]?|[A-Z]{2,}\d+[a-z]?)(?![\w-])')
WD = re.compile(r'(?<![\w-])([a-z]{4,})(?![\w-])')
FAM = re.compile(r'^(CON|S\d|E|F\d|T\d|SEQ|RC|PS8|C\d|L\d|Q\d|EXT|PT|OE|TT)')
fold = lambda w: w[:-1] if w.endswith('s') and not w.endswith('ss') and len(w) > 4 else w

texts = {f: f.read_text(encoding='utf-8') for f in secs}
alltext = '\n'.join(texts.values()) + '\n' + '\n'.join(p.read_text(encoding='utf-8') for p in aux)
reg_terms = ' '.join(r[1] for r in csv.reader(open(T / 'CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t') if r and r[0].startswith('CON-')).lower()


DEFS = set()
for pat in (r'\*\*([^*\n]{1,60})\*\*\s*[:(—–=,]', r'^\s*[-*]\s*\*\*([^*\n]{1,60})\*\*', r'^\|\s*\**([^|*\n]{1,60})\**\s*\|',
            r'^\s*([A-Za-z][\w -]{0,40})\s*:\s', r'\b([\w-]+)\s+(?:means|denotes|is defined as)\b',
            r'(?<![\w-])([A-Z][A-Za-z]{0,6}-\d+[a-z]?|[A-Z]{2,}\d+[a-z]?)\s*\((?:the |a |an )?[A-Z][a-z]'):
    for m in re.finditer(pat, alltext, re.M):
        span = m.group(1).strip()
        # a definition names a term: a short span (at most four words), not a quotation or a sentence
        if len(span.split()) > 4 or re.search(r'["“”‘’…]', span) or span.endswith(('.', ':')):
            continue
        for w in re.findall(r'[A-Za-z][\w-]*', span):
            DEFS.add(w if re.search(r'\d', w) else fold(w.lower()))
REG = set(fold(w) for w in re.findall(r'[a-z][\w-]*', reg_terms)) | set(re.findall(r'[A-Z][A-Za-z]{0,6}-\d+[a-z]?', reg_terms.upper()))


def defined(tok):
    return tok in DEFS or tok in REG


ids, words = collections.Counter(), collections.Counter()
id_secs, w_secs, first = collections.defaultdict(set), collections.defaultdict(set), {}
for f, t in texts.items():
    for m in ID.finditer(t):
        x = m.group(1)
        if FAM.match(x):
            continue
        ids[x] += 1; id_secs[x].add(f.name[:5]); first.setdefault(x, f'{f.name[:5]} L{t[:m.start()].count(chr(10)) + 1}')
    for m in WD.finditer(t):
        x = fold(m.group(1))
        words[x] += 1; w_secs[x].add(f.name[:5]); first.setdefault(x, f'{f.name[:5]} L{t[:m.start()].count(chr(10)) + 1}')

cand_ids = [x for x in ids if not defined(x)]
cand_words = [x for x in words if not defined(x)]
corpus_files, corpus_term = collections.Counter(), collections.Counter()
if use_corpus:
    want_i = set(cand_ids)
    want_w = set(cand_words)
    for r in csv.reader(open(REPO / 'docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv', encoding='utf-8'), delimiter='\t'):
        if len(r) < 3:
            continue
        try:
            t = (REPO / r[2]).read_text(encoding='utf-8', errors='ignore')
        except OSError:
            continue
        seen_i = set(m.group(1) for m in ID.finditer(t)) & want_i
        tl = t.lower()
        seen_w = set(fold(m.group(1)) for m in WD.finditer(tl)) & want_w
        for x in seen_i | seen_w:
            corpus_files[x] += 1
        for m in re.finditer(r'[`*]{1,2}([a-z]{4,})[`*]{1,2}', tl):
            if fold(m.group(1)) in want_w:
                corpus_term[fold(m.group(1))] += 1
everyid = set(ids)
fam = collections.Counter(re.match(r'[A-Za-z]+', x).group(0) for x in everyid)
MATH = re.compile(r'[≤≥∈∉⊆⊂⊇∧∨¬⟺⇔→⇒≔∅Σ∀∃]|\bR≤|\biff\b|:=|^\s*[-*]\s*\*\*[^*]+:\*\*|^\*\*[^*]+\.\*\*')
lines = [l for t in texts.values() for l in t.split('\n')]


def formal(x):
    pat = re.compile(r'(?<![\w-])' + re.escape(x) + r's?(?![\w-])', re.I)
    return any(MATH.search(l) for l in lines if pat.search(l))


# Tier A: undefined and absent from the allowed corpus; identifiers only if their prefix family has one member in the
# book (a code family the book cites from a source has several members); words only if used in a formal line.
rowsA = [('A', x, ids[x], len(id_secs[x])) for x in cand_ids if corpus_files[x] == 0 and fam[re.match(r'[A-Za-z]+', x).group(0)] <= 1] + \
        [('A', x, words[x], len(w_secs[x])) for x in cand_words if corpus_files[x] == 0 and use_corpus and formal(x)]
# Tier B: undefined identifiers that the corpus uses, and the book uses in >= 3 sections (a widely used code with no
# book definition: the first-use rule).
rowsB = [('B', x, ids[x], len(id_secs[x])) for x in cand_ids if corpus_files[x] > 0 and len(id_secs[x]) >= 3]
# Tier C (per section): an undefined word used >= 3 times in the section, used >= 5 times per cited source in the
# section's own cited sources (S-IDs), and marked as a term (backticked or bold) there at least once: a source's
# technical word carried into the book without a definition. A reading list per section; a person judges.
STOP = set('would could should might shall must also only this that with from into than then them they their there these those which while where when what will been were have more most such each other some very much many'.split())
paths = {}
if use_corpus:
    for r in csv.reader(open(REPO / 'docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv', encoding='utf-8'), delimiter='\t'):
        if len(r) > 2:
            paths[r[1]] = r[2]
rowsC, undefined_w = [], set(cand_words)
for f, t in texts.items():
    cited = sorted(set(re.findall(r'S\d{4}', t)) & set(paths))
    if not cited:
        continue
    src = ' '.join((REPO / paths[x]).read_text(encoding='utf-8', errors='ignore').lower() for x in cited)
    cnt = collections.Counter(fold(m.group(1)) for m in WD.finditer(t))
    for w, n in cnt.items():
        if w in STOP or w not in undefined_w or n < 3:
            continue
        k = len(re.findall(r'\b' + w + r's?\b', src))
        mark = len(re.findall(r'[`*]{1,2}' + w + r's?[`*]{1,2}', src))
        if k >= 5 * len(cited) and mark >= 1:
            rowsC.append(('C', w, n, f.name[:5]))
rowsC.sort(key=lambda r: (r[3], -r[2]))
for tier in (rowsA, rowsB, rowsC):
    for t_, x, n, s in (sorted(tier, key=lambda r: (-r[3], -r[2])) if tier is not rowsC else tier):
        if tier is rowsC:
            print(f'C | {x} | in section {s}: {n} uses | corpus files {corpus_files[x]}')
        else:
            print(f'{t_} | {x} | book {n} | sections {s} | corpus files {corpus_files[x]} | first {first[x]}')
print(f'# candidates: tier A {len(rowsA)} · tier B {len(rowsB)} · tier C {len(rowsC)} '
      f'(from {len(ids)} identifiers and {len(words)} word types; {len(cand_ids)} ids and {len(cand_words)} words undefined by the forms)')
