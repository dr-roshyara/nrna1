#!/usr/bin/env python3
"""P3b protocol — freeze integrity check (Appendix A.9, protocol v1.6+). Read-only.

Usage: python3 p3b_freeze_check.py <protocol.md>

Rules (A.9): 1 no literal regex backreferences / placeholders outside code; 2 no stale version outside code spans;
3 every § reference resolves (references to named external documents -- ".md", "report", "audit" within the
preceding 120 characters -- are exempt); 4 H-/OMQ-/D-/G- ids defined, referenced ids defined, no duplicates
("H-11" is the documented shorthand for H-11a-d); 5 S-steps defined in §19.1; 6 every P3B- **artifact** (name ending
.json/.jsonl/.md) appears in §24 -- P3B- **identifiers** (P3B-R1, P3B-RS-#####, P3B-ESC-####, P3B-COR-####) are
exempt; 7 top-level heading numbering has no gaps or duplicates.
Prints per-rule results, counts, the file's sha256 and PASS/FAIL. Record the output and this script's git blob sha
with any approval (§26 item 6).
"""
import hashlib, re, sys, collections
path = sys.argv[1]
raw = open(path, 'rb').read(); t = raw.decode('utf-8')
lines = t.split('\n')
VER = r'v(\d+\.\d+(?:\.\d+)?)'  # accepts patch versions, e.g. v1.6.1
m = re.search(r'OPERATING PROTOCOL · ' + VER, t); own = m.group(1)
results = {}

def strip_code(l):
    return re.sub(r'`[^`]*`', '', l)

# fenced code blocks excluded for rule 1
in_fence = False; r1 = []
for i, l in enumerate(lines, 1):
    if l.strip().startswith('```'): in_fence = not in_fence; continue
    if in_fence: continue
    x = strip_code(l)
    if re.search(r'\\[1-9]', x) or re.search(r'__[A-Z]+__', x) or re.search(r'\b(TODO|TBD|XXX)\b', x):
        r1.append(i)
results['1 backrefs/placeholders'] = r1

r2 = []
for i, l in enumerate(lines, 1):
    for v in re.findall(r'approve ' + VER, strip_code(l)):
        if v != own: r2.append((i, v))
    for v in re.findall(r'Status: PROPOSED ' + VER, l):
        if v != own: r2.append((i, v))
results['2 stale version'] = r2

heads = []; top = []
for l in lines:
    mm = re.match(r'^(#{1,4})\s+(\d+[A-Z]?(?:\.\d+[a-z]?)*)\.?\s', l)
    if mm:
        heads.append(mm.group(2).rstrip('.'))
        if mm.group(1) == '#' and re.fullmatch(r'\d+', mm.group(2).rstrip('.')): top.append(int(mm.group(2).rstrip('.')))
hs = set(heads)
r3 = []
for i, l in enumerate(lines, 1):
    for mm in re.finditer(r'§(\d+[A-Z]?(?:\.\d+[a-z]?)*)', l):
        ref = mm.group(1).rstrip('.')
        if ref in hs: continue
        before = l[max(0, mm.start() - 120):mm.start()]
        if '.md' in before or 'report' in before or 'audit' in before.lower(): continue  # named external document
        r3.append((i, ref))
results['3 unresolved §'] = r3

def ids(defpat, refpat):
    d = re.findall(defpat, t, re.M); r = set(re.findall(refpat, t))
    dup = [k for k, c in collections.Counter(d).items() if c > 1]
    return set(d), r, dup
out4 = {}
for name, dp, rp in [
    ('H', r'^\| \*{0,2}(H-\d+[a-d]?)\*{0,2} \|', r'\b(H-\d+[a-d]?)\b'),
    ('OMQ', r'^\| \*\*(OMQ-\d+)\*\* \|', r'\b(OMQ-\d+)\b'),
    ('D', r'^\*\*(D-\d+)\b', r'\b(D-\d+)\b'),
    ('G', r'^\| (G-\d+) ', r'\b(G-\d+)\b')]:
    d, r, dup = ids(dp, rp)
    undefined = sorted(x for x in r - d if not (name == 'H' and x == 'H-11'))  # H-11 = shorthand for H-11a–d
    out4[name] = {'defined': len(d), 'undefined_refs': undefined, 'duplicates': dup}
results['4 ids'] = out4

steps_def = set(re.findall(r'\| \*\*(S\d[a-z]?) [A-Z]', t))
steps_ref = set(re.findall(r'\b(S\d[a-z]?)\b(?![\d#])', t))
results['5 S-steps undefined'] = sorted(steps_ref - steps_def)

i24 = t.index('# 24. OUTPUT ARTIFACTS'); j24 = t.index('# 25. ', i24); sec24 = t[i24:j24]
arts = set(re.findall(r'`(P3B-[A-Z0-9-]+\.(?:jsonl|json|md))`', t))
results['6 P3B artifacts missing from §24'] = sorted(a for a in arts if a not in sec24)

seq = sorted(top); gaps = [n for n in range(seq[0], seq[-1] + 1) if n not in seq]
dups = [k for k, c in collections.Counter(top).items() if c > 1]
results['7 top-level numbering'] = {'gaps': gaps, 'duplicates': dups, 'range': (seq[0], seq[-1])}

# rule 8 (v1.6.4): a table row must end with its closing pipe; content after it is dropped by renderers
in_fence = False; r8 = []
for i, l in enumerate(lines, 1):
    if l.strip().startswith('```'): in_fence = not in_fence; continue
    if not in_fence and l.startswith('|') and not l.rstrip().endswith('|'):
        r8.append(i)
results['8 table rows not ending with |'] = r8

ok = (not r1 and not r2 and not r3 and all(not v['undefined_refs'] and not v['duplicates'] for v in out4.values())
      and not results['5 S-steps undefined'] and not results['6 P3B artifacts missing from §24'] and not gaps and not dups
      and not r8)
print('file', path.split('/')[-1], '| own version', own, '| headings', len(heads), '| sha256', hashlib.sha256(raw).hexdigest())
for k, v in results.items(): print(f'  {k}: {v}')
print('FREEZE INTEGRITY CHECK:', 'PASS' if ok else 'FAIL')
