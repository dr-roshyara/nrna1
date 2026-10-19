#!/usr/bin/env python3
"""Register-home check: every concept row must be visible, with a matching status, in its canonical text.

PROPOSED for a Rulebook revision (S0100 recommendation 1); NOT IN FORCE. Read-only: writes nothing.

Input:  --root DIR (holds 06-theory/; default: the lane).
Check, for every CONCEPT-REGISTER.tsv row whose book_location names a section file:
  H1  the file exists;
  H2  the file contains the row's CON-ID;
  H3  every status tag in the file for that ID (`[CON-nnnn · status …]`) agrees with the register status
      (INVALID ~ "invalid", "invalid as stated"; PROVISIONAL ~ "provisional"; VALIDATED ~ "validated");
      a row whose file carries no tag at all is reported as H3-untagged (information, not an error).
Output: one line per flag (ID, check, file, detail) and a count line. Exit 0 always (it lists; a person judges).
Limits: checks presence and tag agreement, not meaning; a restated form under an INVALID row is not detected here
(see PS8-9); IDs cited only in prose without a tag pass H2.
"""
import csv, pathlib, re, sys

a = sys.argv[1:]
root = pathlib.Path(a[a.index('--root') + 1]) if '--root' in a else pathlib.Path(__file__).resolve().parents[2]
T = root / '06-theory'
M = T / 'manuscript'
rows = [r for r in csv.reader(open(T / 'CONCEPT-REGISTER.tsv', encoding='utf-8'), delimiter='\t') if r and r[0].startswith('CON-')]
norm = lambda s: 'INVALID' if 'invalid' in s.lower() else 'PROVISIONAL' if 'provisional' in s.lower() else 'VALIDATED' if 'validated' in s.lower() else s.strip().upper()
flags, untagged = [], 0
for r in rows:
    cid, status, loc = r[0], r[7].strip(), (r[5] if len(r) > 5 else '')
    m = re.search(r'(chapter_\d\d/[0-9][0-9.]*[^ ;,)]*\.md)', loc)
    if not m:
        continue
    f = M / m.group(1)
    if not f.exists():
        flags.append((cid, 'H1 file missing', m.group(1), loc[:60])); continue
    t = f.read_text(encoding='utf-8')
    if not re.search(r'\b' + cid + r'\b', t):
        flags.append((cid, 'H2 ID not in its home', m.group(1), f'register status {status}')); continue
    tags = re.findall(r'`\[' + cid + r'\s*·\s*([^\]`]*)\]`', t)
    if not tags:
        untagged += 1; continue
    words = [x for x in tags if re.search(r'invalid|provisional|validated', x, re.I)]
    bad = sorted({x for x in words if norm(x) != norm(status)})
    if bad:
        flags.append((cid, 'H3 status disagrees', m.group(1), f'register {status}; text {bad}'))
    elif not words:
        flags.append((cid, 'H4 tag carries no status (information)', m.group(1), f'register {status}; text {sorted(set(tags))}'))
for x in flags:
    print(' | '.join(x))
print(f'# rows with a section home: {sum(1 for r in rows if re.search(r"chapter_\d\d/", r[5] if len(r) > 5 else ""))}; '
      f'flags: {len(flags)} (H1 {sum(1 for x in flags if x[1].startswith("H1"))}, H2 {sum(1 for x in flags if x[1].startswith("H2"))}, '
      f'H3 {sum(1 for x in flags if x[1].startswith("H3"))}, H4 {sum(1 for x in flags if x[1].startswith("H4"))}); untagged homes (information): {untagged}')
