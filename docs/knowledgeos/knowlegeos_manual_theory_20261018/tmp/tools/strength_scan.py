#!/usr/bin/env python3
"""Strength scan: list every strong word in a book section, so that each can be justified or weakened before review.

Words (and their forms): always · never · only · all · none · necessary · sufficient · impossible · proves.
Text inside quotation marks ("…" quotes from sources) is reported separately: those are the source's words, not the book's.

Usage: python3 -I strength_scan.py <section.md>
"""
import re, sys, pathlib

WORDS = r'always|never|only|all|none|necessar(?:y|ily)|sufficien(?:t|tly|cy)|insufficient|impossib(?:le|ility)|prove[sdn]?|proof'
PAT = re.compile(r'\b(' + WORDS + r')\b', re.IGNORECASE)
QUOTE = re.compile(r'"[^"\n]*"')

path = pathlib.Path(sys.argv[1])
own, quoted = [], []
for n, line in enumerate(path.read_text(encoding='utf-8').split('\n'), 1):
    spans = [m.span() for m in QUOTE.finditer(line)]
    for m in PAT.finditer(line):
        inside = any(a <= m.start() < b for a, b in spans)
        ctx = line[max(0, m.start() - 50):m.end() + 50].strip()
        (quoted if inside else own).append((n, m.group(1).lower(), ctx))

print(f'{path.name}: {len(own)} strong words in the book\'s own text, {len(quoted)} inside source quotes')
for n, w, ctx in own:
    print(f'  L{n:<4} {w:<12} …{ctx}…')
if quoted:
    print('inside quotes (the source\'s words):')
    for n, w, ctx in quoted:
        print(f'  L{n:<4} {w:<12} …{ctx}…')
