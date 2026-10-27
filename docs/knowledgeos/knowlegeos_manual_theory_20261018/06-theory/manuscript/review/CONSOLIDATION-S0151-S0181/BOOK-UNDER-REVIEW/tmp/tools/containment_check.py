#!/usr/bin/env python3
"""Brute-force check of Ch. 2 §2.8.4's containment and overlap rules against supervaluation.
Written 2026-10-20 for the whole-book review S0100, stage 2, finding P2-3.

A scope specification gives each of k dimensions a set X ⊆ V or the marker ⊥ (unspecified: some X ∈ 𝒫(V) is
intended, unknown which). A comparison is T (F) if T (F) for every completion of the ⊥s, U otherwise.
Regions are products; A ⊆ B iff A is empty or Xᵢ ⊆ Yᵢ for every i.

Rules checked:
  containment, as written in §2.8.4 (empty case first, then per dimension, strong Kleene);
  containment, corrected (P2-3): as written, but a result F becomes U when some Xᵢ of A is ⊥, since ⊥ may complete
  to ∅ and make A empty;
  overlap, as written in §2.8.4.
Usage: python3 -I containment_check.py
"""
from itertools import chain, combinations, product

V = ('a', 'b')
K = 2
BOT = 'BOT'


def subsets(xs):
    return [frozenset(c) for c in chain.from_iterable(combinations(xs, n) for n in range(len(xs) + 1))]


SETS = subsets(V)
SPECS = SETS + [BOT]
FULL = frozenset(V)


def completions(spec):
    return product(*[SETS if x == BOT else [x] for x in spec])


def contained(A, B):
    return any(not x for x in A) or all(x <= y for x, y in zip(A, B))


def overlaps(A, B):
    return all(x & y for x, y in zip(A, B))


def superval(rel, A, B):
    vals = {rel(a, b) for a in completions(A) for b in completions(B)}
    return 'T' if vals == {True} else 'F' if vals == {False} else 'U'


def kleene_and(vs):
    return 'F' if 'F' in vs else 'U' if 'U' in vs else 'T'


def cont_dim(x, y):
    if x != BOT and y != BOT:
        return 'T' if x <= y else 'F'
    if x != BOT:
        return 'U'
    return 'T' if y == FULL else 'U'


def cont_written(A, B):
    if any(x != BOT and not x for x in A):
        return 'T'
    return kleene_and([cont_dim(x, y) for x, y in zip(A, B)])


def cont_corrected(A, B):
    r = cont_written(A, B)
    return 'U' if r == 'F' and BOT in A else r


def over_dim(x, y):
    if x != BOT and y != BOT:
        return 'T' if x & y else 'F'
    if (x != BOT and not x) or (y != BOT and not y):
        return 'F'
    return 'U'


def over_written(A, B):
    return kleene_and([over_dim(x, y) for x, y in zip(A, B)])


def sound(rule, truth):          # a rule may say U where the truth is definite; it may never contradict the truth
    return rule == truth or rule == 'U'


if __name__ == '__main__':
    pairs = [(A, B) for A in product(SPECS, repeat=K) for B in product(SPECS, repeat=K)]
    res = {'containment as written': cont_written, 'containment corrected': cont_corrected, 'overlap as written': over_written}
    rel = {'containment as written': contained, 'containment corrected': contained, 'overlap as written': overlaps}
    for name, rule in res.items():
        wrong = [(A, B) for A, B in pairs if not sound(rule(A, B), superval(rel[name], A, B))]
        exact = sum(rule(A, B) == superval(rel[name], A, B) for A, B in pairs)
        print(f'{name:<24} pairs {len(pairs)}  unsound {len(wrong):>4}  exact {exact:>4}')
        for A, B in wrong[:3]:
            show = lambda s: tuple('⊥' if x == BOT else ''.join(sorted(x)) or '∅' for x in s)
            print('   e.g. A =', show(A), 'B =', show(B), 'rule', rule(A, B), 'truth', superval(rel[name], A, B))
    A, B = (BOT, frozenset({'a'})), (FULL, frozenset({'b'}))
    assert cont_written(A, B) == 'F' and superval(contained, A, B) == 'U', 'the settlement case'
    assert all(sound(cont_corrected(A, B), superval(contained, A, B)) for A, B in pairs)
    assert all(sound(over_written(A, B), superval(overlaps, A, B)) for A, B in pairs)
    print('all assertions passed')
