#!/usr/bin/env python3
"""MRP-1 quote-anchor check (M-01 W-6).

For each extraction record, verify that the source file still has the recorded
sha256, and that every quote line

    > Q<n> L<a>-<b>: "<verbatim text>"

occurs inside lines a..b of that source. An exact match is PASS. A match after
collapsing runs of whitespace to one space is PASS-WS (reported separately).
Anything else is FAIL. A record whose source cannot be verified is
INCONCLUSIVE, never PASS.

Prints quote ids and results only, never source text.
Usage: python3 -I quote_anchor_check.py --repo <repo root> <E-*.md> [...]
Exit: 0 all PASS/PASS-WS · 1 any FAIL · 2 any INCONCLUSIVE (and no FAIL)
"""
import argparse
import hashlib
import os
import re
import sys

QUOTE = re.compile(r'^>\s*(Q\d+)\s+L(\d+)-(\d+):\s*"(.*)"\s*$')
FIELD = re.compile(r'^(source_path|sha256):\s*(\S.*?)\s*$')


def collapse(s):
    return re.sub(r'\s+', ' ', s).strip()


def check(record, repo):
    fields, quotes = {}, []
    with open(record, encoding='utf-8') as f:
        for n, line in enumerate(f, 1):
            m = FIELD.match(line)
            if m and m.group(1) not in fields:
                fields[m.group(1)] = m.group(2)
            q = QUOTE.match(line.rstrip('\n'))
            if q:
                quotes.append((n, q.group(1), int(q.group(2)), int(q.group(3)), q.group(4)))

    path, sha = fields.get('source_path'), fields.get('sha256')
    if not path or not sha:
        return [('-', 'INCONCLUSIVE', 'source_path or sha256 missing')]
    src = os.path.join(repo, path)
    if not os.path.isfile(src):
        return [('-', 'INCONCLUSIVE', 'source not found')]
    raw = open(src, 'rb').read()
    if hashlib.sha256(raw).hexdigest() != sha:
        return [('-', 'INCONCLUSIVE', 'sha256 differs from record')]
    if not quotes:
        return [('-', 'INCONCLUSIVE', 'no quote lines found')]

    lines = raw.decode('utf-8', errors='replace').splitlines()
    results = []
    for rec_line, qid, a, b, text in quotes:
        if not text:
            results.append((qid, 'FAIL', f'empty quote (record line {rec_line})'))
            continue
        if a < 1 or b < a or b > len(lines):
            results.append((qid, 'FAIL', f'range L{a}-{b} outside 1..{len(lines)}'))
            continue
        span = '\n'.join(lines[a - 1:b])
        if text in span:
            results.append((qid, 'PASS', f'L{a}-{b}'))
        elif collapse(text) in collapse(span):
            results.append((qid, 'PASS-WS', f'L{a}-{b}'))
        else:
            results.append((qid, 'FAIL', f'not found in L{a}-{b} (record line {rec_line})'))
    return results


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--repo', required=True)
    ap.add_argument('records', nargs='+')
    args = ap.parse_args()
    worst = 0
    for rec in args.records:
        for qid, result, detail in check(rec, args.repo):
            print(f'{os.path.basename(rec)}\t{qid}\t{result}\t{detail}')
            if result == 'FAIL':
                worst = 1
            elif result == 'INCONCLUSIVE' and worst == 0:
                worst = 2
    sys.exit(worst)


if __name__ == '__main__':
    main()
