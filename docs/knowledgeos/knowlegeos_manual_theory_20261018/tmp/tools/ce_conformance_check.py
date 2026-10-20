#!/usr/bin/env python3
"""§9.7.5 condition 4 (whole-book review S0100, finding P4-5): which conformance condition excludes the constant evaluators?
A toy defined CE over six evidenced cases takes all three values T, F, U. Implementations: the correct one, constant T,
constant U, constant F. Old condition 4: 'answer T wherever the defined CE answers T' (one direction).
New condition 4: soundness (impl = v ⇒ CE = v) and completeness (CE = v ⇒ impl = v), for each v ∈ {T, F, U}.
Usage: python3 -I ce_conformance_check.py"""
CASES = range(6)
CE = {0: 'T', 1: 'T', 2: 'F', 3: 'F', 4: 'U', 5: 'U'}
IMPLS = {'correct': dict(CE), 'constant T': {c: 'T' for c in CASES},
         'constant U': {c: 'U' for c in CASES}, 'constant F': {c: 'F' for c in CASES}}
old = lambda im: all(im[c] == 'T' for c in CASES if CE[c] == 'T')
sound = lambda im, v: all(CE[c] == v for c in CASES if im[c] == v)
compl = lambda im, v: all(im[c] == v for c in CASES if CE[c] == v)
new = lambda im: all(sound(im, v) and compl(im, v) for v in 'TFU')
for name, im in IMPLS.items():
    print(f'{name:<11} old condition 4: {old(im)!s:<5} new (sound and complete on T, F, U): {new(im)}')
assert old(IMPLS['constant T']) and not old(IMPLS['constant U']), 'old condition admits constant T'
assert new(IMPLS['correct']) and not any(new(IMPLS[k]) for k in ('constant T', 'constant U', 'constant F'))
print('all assertions passed')
