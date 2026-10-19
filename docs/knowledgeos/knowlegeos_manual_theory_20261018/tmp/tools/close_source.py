#!/usr/bin/env python3
"""Mark a corpus source as read or consulted (Rulebook v1.3 + Amendment 32a). Enforces, not merely records:

  * full / skim       accepted ONLY for the next source in index order after the reading position;
  * branch-consult    requires --passages; never moves the reading position;
  * reading position  = the furthest full/skim row after the last RESTART marker in the read log;
  * quotes            every "…" [Snnnn Lnn] quote in the manuscript must match the source (meaning-level);
  * concept tags      book tags must equal the register; a concept whose origin is a branch (idea_ids
                      'BRANCH-nn') may not be VALIDATED unless review/BRANCH-nn-*/REVIEW-REPORT.md exists.
  * topic-full        Trial T1: the whole source read for one topic (--topic NAME); never moves the reading position;
  * validity gate     a concept whose origin is a branch or a trial (idea_ids 'BRANCH-nn' / 'T1') may be VALIDATED
                      only if review/<origin>-*/REVIEW-REPORT.md has a table row naming that concept with a
                      VALIDATED (or VALIDATE) verdict cell; a review file merely existing is not enough;
  * effort            every row carries start= and stop= times: start is recorded by --start, stop is the close.
A row is written only if every check passes.

Usage:
  close_source.py --start S0036                      (record the start time; required before closing)
  close_source.py S0031 full|skim "book: where it went" ["note"] [--dry]
  close_source.py S0208 branch-consult "BRANCH-01" ["note"] --passages "L19-30, L210-241" [--dry]
  close_source.py S0412 --depth topic-full --topic F11-execute "book: where it went" ["note"] [--dry]
  close_source.py --position
  close_source.py --open-branch BRANCH-02 slug      (Amendment 32b: snapshot of the theory map before the branch)
"""
import sys, json, hashlib, csv, re, glob, datetime, pathlib, unicodedata

W = pathlib.Path(__file__).resolve().parents[2]
REPO = W.parents[2]
LOG = W / 'tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv'
INDEX = [r for r in map(json.loads, open(W / '02-FILES.jsonl', encoding='utf-8'))]
ORDER = {r['source_id']: i for i, r in enumerate(INDEX)}


def reading_position():
    """Index position of the furthest full/skim row after the last RESTART marker (-1 if none)."""
    rows = list(csv.reader(open(LOG, encoding='utf-8'), delimiter='\t'))[1:]
    last_restart = max([i for i, r in enumerate(rows) if r and r[1] == 'RESTART'], default=-1)
    pos = -1
    for r in rows[last_restart + 1:]:
        if len(r) > 5 and r[5] in ('full', 'skim') and r[1] in ORDER:
            pos = max(pos, ORDER[r[1]])
    return pos, rows, last_restart


def counts():
    pos, rows, lr = reading_position()
    after = rows[lr + 1:]
    read = sorted({r[1] for r in after if len(r) > 5 and r[5] in ('full', 'skim')}, key=ORDER.get)
    consulted = sorted({r[1] for r in after if len(r) > 5 and r[5] == 'branch-consult'}, key=ORDER.get)
    return pos, read, consulted, sum(1 for r in after if len(r) > 5 and r[5] == 'scan')


PRE_RULE_BRANCHES = {'BRANCH-01'}   # predate Amendment 32b; no faithful snapshot exists, none is reconstructed


def snapshot_ok(branch):
    """True if the branch has a THEORY-MAP-BEFORE.md whose sha256 matches its recorded fingerprint."""
    if branch in PRE_RULE_BRANCHES:
        return True
    for d in glob.glob(str(W / f'06-theory/manuscript/review/{branch}-*')):
        snap, fp = pathlib.Path(d) / 'THEORY-MAP-BEFORE.md', pathlib.Path(d) / 'THEORY-MAP-BEFORE.sha256'
        if snap.is_file() and fp.is_file():
            return hashlib.sha256(snap.read_bytes()).hexdigest() == fp.read_text().split()[0]
    return False


if sys.argv[1:2] == ['--open-branch']:
    branch, slug = sys.argv[2], sys.argv[3]
    assert re.fullmatch(r'BRANCH-\d{2,}', branch), 'branch id must look like BRANCH-02'
    if glob.glob(str(W / f'06-theory/manuscript/review/{branch}-*/THEORY-MAP-BEFORE.md')) or branch in PRE_RULE_BRANCHES:
        sys.exit(f'REFUSED: {branch} already has a snapshot (or predates the rule)')
    d = W / f'06-theory/manuscript/review/{branch}-{slug}'
    d.mkdir(parents=True, exist_ok=True)
    data = (W / '06-theory/THEORY-MAP.md').read_bytes()
    (d / 'THEORY-MAP-BEFORE.md').write_bytes(data)
    now = datetime.datetime.now(datetime.timezone.utc).strftime('%Y-%m-%dT%H:%M:%SZ')
    (d / 'THEORY-MAP-BEFORE.sha256').write_text(f'{hashlib.sha256(data).hexdigest()}  THEORY-MAP-BEFORE.md  taken {now}\n')
    print(f'{branch} opened: snapshot {d.relative_to(W)}/THEORY-MAP-BEFORE.md sha256 {hashlib.sha256(data).hexdigest()}')
    sys.exit(0)

STARTS = W / 'tmp/corpus-reading/START-TIMES.tsv'


def utcnow():
    return datetime.datetime.now(datetime.timezone.utc).strftime('%Y-%m-%dT%H:%M:%SZ')


if sys.argv[1:2] == ['--start']:
    sid = sys.argv[2]
    assert sid in ORDER, f'unknown source {sid}'
    with open(STARTS, 'a', encoding='utf-8') as fh:
        fh.write(f'{sid}\t{utcnow()}\n')
    print(f'{sid} start recorded')
    sys.exit(0)

if sys.argv[1:] == ['--position']:
    pos, read, consulted, scans = counts()
    nxt = INDEX[pos + 1]['source_id'] if pos + 1 < len(INDEX) else None
    print(f'reading position: {INDEX[pos]["source_id"] if pos >= 0 else "none"}; next in order: {nxt}')
    print(f'read in order (full/skim): {len(read)}; consulted in branches only: {len([c for c in consulted if c not in read])} {consulted}; scans: {scans}')
    sys.exit(0)

argv = sys.argv[1:]
dry = '--dry' in argv
opts = {}
for flag in ('--passages', '--depth', '--topic'):
    if flag in argv:
        k = argv.index(flag); opts[flag] = argv[k + 1]; del argv[k:k + 2]
passages, topic = opts.get('--passages'), opts.get('--topic')
args = [a for a in argv if a != '--dry']
if '--depth' in opts:
    sid, depth, rest = args[0], opts['--depth'], args[1:]
else:
    sid, depth, rest = args[0], args[1], args[2:]
book = rest[0]
note = rest[1] if len(rest) > 1 else ''
errors = []
if depth not in ('full', 'skim', 'branch-consult', 'topic-full'):
    errors.append(f'depth must be full, skim, branch-consult or topic-full (scan rows come from branch_search.py --log), got {depth!r}')
if depth == 'topic-full' and not topic:
    errors.append('topic-full requires --topic NAME')
started = [l.split('\t')[1].strip() for l in open(STARTS, encoding='utf-8') if l.split('\t')[0] == sid] if STARTS.exists() else []
if not started:
    errors.append(f'no start time recorded for {sid} (run --start {sid} before reading)')
pos, _, _ = reading_position()
if depth in ('full', 'skim'):
    nxt = INDEX[pos + 1]['source_id'] if pos + 1 < len(INDEX) else None
    if sid != nxt:
        errors.append(f'chronological order: next source is {nxt}, not {sid}')
if depth == 'branch-consult' and not passages:
    errors.append('branch-consult requires --passages "Lnn-mm, …"')
if depth == 'branch-consult':
    bm = re.match(r'(BRANCH-\d+)', book)
    if not bm:
        errors.append('branch-consult: the third argument must start with the branch id, e.g. "BRANCH-02"')
    elif not snapshot_ok(bm.group(1)):
        errors.append(f'branch-consult: {bm.group(1)} has no valid theory-map snapshot (open it with --open-branch; Amendment 32b)')

row = next(r for r in INDEX if r['source_id'] == sid)
path = row['path']
allowed = (W.parent / 'knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv').read_text(encoding='utf-8')
if path not in allowed:
    errors.append(f'{path} NOT on the allowed list')
    data = b''
else:
    data = (REPO / path).read_bytes()
sha, nlines = hashlib.sha256(data).hexdigest(), data.count(b'\n')
src_lines = data.decode('utf-8', errors='replace').split('\n')


def norm(s):
    s = unicodedata.normalize('NFKC', s)
    s = re.sub(r'[*_`~]', '', s)
    s = ''.join(ch for ch in s if unicodedata.category(ch)[0] != 'S' or ch in '→←≠⟂⊆⊂≤≥∧∨×·—–-')
    s = s.replace('“', '"').replace('”', '"').replace('’', "'").replace('‘', "'")
    return re.sub(r'\s+', ' ', s).strip().lower()


QUOTE = re.compile(r'"([^"\n]{6,}?)"\s*\[' + sid + r'\s+L(\d+)(?:\s*[–-]\s*L?(\d+))?')
checked, fails = 0, []
for f in sorted(glob.glob(str(W / '06-theory/manuscript/**/*.md'), recursive=True)):
    for i, line in enumerate(open(f, encoding='utf-8'), 1):
        for m in QUOTE.finditer(line):
            q, a, b = m.group(1), int(m.group(2)), int(m.group(3) or m.group(2))
            window = norm(' '.join(src_lines[max(0, a - 1):b]))
            parts = [norm(p) for p in re.split(r'…|\.\.\.', q) if norm(p)]
            checked += 1
            if not all(p in window for p in parts):
                fails.append(f'{pathlib.Path(f).relative_to(W)}:{i}  "{q[:60]}" [L{a}-{b}]')

reg = list(csv.DictReader(open(W / '06-theory/CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t'))
st = {r['concept_id']: r['status'].lower() for r in reg}
bad = [(pathlib.Path(f).name, c, s) for f in glob.glob(str(W / '06-theory/manuscript/chapter_0*/*.md'))
       for l in open(f, encoding='utf-8') for c, s in re.findall(r'\[(CON-\d{4}) · (\w+)\]', l) if st.get(c) != s]
def review_validates(origin, cid):
    """True if a REVIEW-REPORT.md of this origin has a table row naming cid with a VALIDATED/VALIDATE verdict cell."""
    for f in glob.glob(str(W / f'06-theory/manuscript/review/{origin}-*/REVIEW-REPORT.md')):
        for line in open(f, encoding='utf-8'):
            if line.lstrip().startswith('|') and cid in line:
                # a verdict cell carries its scope ("VALIDATED (as non-sufficiency only)"): its first word decides
                words = [re.sub(r'[*`_]', '', c).strip().upper().split() for c in line.split('|')]
                if any(w and w[0] in ('VALIDATED', 'VALIDATE') for w in words):
                    return True
    return False


for r in reg:
    b = re.match(r'(BRANCH-\d+|T\d+)\b', r.get('idea_ids', '') or '')
    if b and r['status'] == 'VALIDATED' and not review_validates(b.group(1), r['concept_id']):
        bad.append(('register', r['concept_id'], f'VALIDATED without a VALIDATED verdict in the {b.group(1)} review'))

ok = not errors and not fails and not bad
now = utcnow()
if ok and not dry:
    purpose = {'branch-consult': f'{book}; passages {passages}', 'topic-full': f'topic {topic}; {book}'}.get(depth, book)
    with open(LOG, 'a', encoding='utf-8') as fh:
        fh.write(f'{now}\t{sid}\t{path}\t{sha}\t{nlines}\t{depth}\t{purpose}\tallowed-list PASS; quotes PASS ({checked}); tags PASS; start={started[-1]}; stop={now}; {note}\n')
print(f'{sid} {path}\n sha256 {sha} lines {nlines} depth {depth}\n quotes checked {checked}, failed {len(fails)}\n tag/register problems {bad}')
for e in errors: print(' REFUSED', e)
for x in fails: print(' QUOTE FAIL', x)
print(' READ-LOG: ' + ('dry run, not written' if dry else ('written' if ok else 'NOT written (fix first)')))
