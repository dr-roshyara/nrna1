#!/usr/bin/env python3
"""Formatting check for a step-2 integration (Rulebook §4, entry 52a). Checks formatting only; it does not replace
the claim-by-claim reasoning review. Usage: python3 -I integration_check.py <new.md> [<old.md>]
Counts: inline revision tags (RC-n, R1-n, 'pilot n, file n', 'batch n', 'added/revised/corrected/restated … after/at review|settlement|in window'),
INVALID markers in the prose outside the 'What does not hold' subsection, claim numbers present; words new vs old."""
import re, sys, pathlib
new = pathlib.Path(sys.argv[1]).read_text(encoding='utf-8')
old = pathlib.Path(sys.argv[2]).read_text(encoding='utf-8') if len(sys.argv) > 2 else None
body = re.split(r'\n### [\d.]+\.99 ', new)[0]
tags = re.findall(r'\bRC-\d+[a-z]?\b|\bR\d-\d+[a-z]?\b|pilot \d, file \d|\bbatch \d\b|(?:added|revised|corrected|restated|rewritten)[^.\n]{0,40}(?:review|settlement|window|rewrite)', body, re.I)
inval = re.findall(r'INVALID|invalid as stated', body)
nums = lambda t: sorted(set(re.findall(r'\*\*(\d+\.\d+(?:\.\d+)?-\d+)\.', t)))
print('revision tags:', len(tags), tags[:8])
print('INVALID markers outside "what does not hold":', len(inval))
print('claim numbers:', nums(new))
if old:
    print('claim numbers kept:', nums(old) == nums(new))
    print('words old/new:', len(old.split()), len(new.split()), '(diagnostic only)')
