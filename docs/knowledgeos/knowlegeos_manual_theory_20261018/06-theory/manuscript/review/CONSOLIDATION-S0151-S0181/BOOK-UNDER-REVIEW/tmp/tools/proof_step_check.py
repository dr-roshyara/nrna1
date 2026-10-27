#!/usr/bin/env python3
"""Proof-step helper: flags proof steps that use an object the same section declares undefined, without a premise.

PROPOSED for a Rulebook revision (S0100 recommendation 3); NOT IN FORCE. Read-only: writes nothing.
It supports, and never replaces, the reviewer's step-by-step proof check behind an "adequate" judgement.

Usage: proof_step_check.py [--root DIR] [section-file-glob …]   (default: every section)
For each section:
  1. objects declared undefined: a bold-led bullet or sentence naming a symbol (e.g. "**The evaluator** CE(…)")
     followed in the same line by "not yet defined", "is undefined", "not a function" or "not established";
  2. each lemma or theorem ("**Lemma …**", "**Theorem …**", "**Proposition …**"): its hypothesis line and its
     numbered proof steps up to "∎";
  3. a flag when a step uses an undefined object and neither the step says "(premise)" nor the hypothesis names it.
Output: section · lemma · step · the object · the step text. Exit 0. A flag is a question for the reviewer:
the object may be defined elsewhere, or the step may be justified in prose the helper cannot read.
Limits: only explicit "not yet defined"-type declarations in the same section; symbols are matched literally.
"""
import pathlib, re, sys

a = sys.argv[1:]
root = pathlib.Path(a[a.index('--root') + 1]) if '--root' in a else pathlib.Path(__file__).resolve().parents[2]
globs = [x for i, x in enumerate(a) if x != '--root' and (i == 0 or a[i - 1] != '--root')] or ['chapter_*/[0-9][0-9].[0-9][0-9]*.md']
M = root / '06-theory' / 'manuscript'
UNDEF = re.compile(r'not yet defined|is undefined|not a function|not established', re.I)
n_flags = n_lemmas = 0
for g in globs:
    for f in sorted(M.glob(g)):
        lines = f.read_text(encoding='utf-8').split('\n')
        undefined = set()
        for l in lines:
            if UNDEF.search(l):
                m = re.match(r'\s*[-*]?\s*\*\*[^*]+\*\*\s*([A-Z][A-Za-z]{0,5})\s*[\(:]', l)
                if m:
                    undefined.add(m.group(1))
        for i, l in enumerate(lines):
            m = re.match(r'\*\*(Lemma|Theorem|Proposition)\s*([^*]*)\*\*(.*)', l)
            if not m:
                continue
            n_lemmas += 1
            hyp = m.group(3)
            j = i + 1
            while j < len(lines) and '∎' not in lines[j - 1] and j < i + 40:
                s = lines[j]
                if re.match(r'\s*\d+\.\s', s):
                    for obj in undefined:
                        if re.search(rf'\b{obj}\b', s) and '(premise)' not in s and not re.search(rf'\b{obj}\b', hyp):
                            n_flags += 1
                            print(f'{f.name[:5]} | {m.group(1)} {m.group(2).strip()} | step "{s.strip()[:4]}" | {obj} declared undefined in this section | {s.strip()[:150]}')
                j += 1
print(f'# lemmas/theorems scanned: {n_lemmas}; flags: {n_flags}')
