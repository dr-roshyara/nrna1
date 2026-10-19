#!/usr/bin/env python3
"""Finite test of the kernel scaffold (extraction review, finding 1; Principal's settle instruction 1.1).

A toy model, not KnowledgeOS. It tests the FORM of the scaffold, not its content.

State s = (claims, acts, standing), each a subset of the item set X = {a, b}.
Invariants I (safety: what must never break):
  I1  standing ⊆ acts      no standing without a recorded act (cf. A1)
  I2  acts ⊆ claims        an act is about a recorded claim
Mechanisms m : S × X → S ∪ {⊥} (pool):
  record   adds x to claims; ⊥ if already recorded
  grant    records an act on x and gives x standing; ⊥ if x is not recorded
  grant2   behaves exactly as grant (a redundant twin)
  revoke   removes x's standing; ⊥ if x has none
  bad      gives x standing without an act (breaks I1)
  refuse   refuses every input (always ⊥): preserves everything, does nothing
  noop     returns the state unchanged: preserves everything, does nothing
Required capabilities R (liveness: what the kernel must be able to do), from s0 = (∅, ∅, ∅):
  R1  some state with a recorded claim is reachable
  R2  some state with a claim that has standing is reachable

Checks:
  P   (preservation with refusal)  ∀m ∈ M, s ∈ S_I, x: m(s, x) ∈ S_I ∪ {⊥}
  Req (required capabilities)      ∀r ∈ R: r is reachable from s0 using only M
  Kernel(M) = P ∧ Req;  Minimal(M) = Kernel(M) ∧ no proper subset is a Kernel.

Trivial cases (Rulebook §4): the empty set, refuse-everything, and the unchanged state (noop) each satisfy P and fail Req.

Usage: python3 -I kernel_model.py
"""
from itertools import chain, combinations

X = ('a', 'b')
BOT = None


def subsets(xs):
    xs = list(xs)
    return [frozenset(c) for c in chain.from_iterable(combinations(xs, k) for k in range(len(xs) + 1))]


STATES = [(c, a, s) for c in subsets(X) for a in subsets(X) for s in subsets(X)]


def inv(st):
    c, a, s = st
    return s <= a and a <= c          # I1 and I2


S_I = [st for st in STATES if inv(st)]


def record(st, x):
    c, a, s = st
    return BOT if x in c else (c | {x}, a, s)


def grant(st, x):
    c, a, s = st
    return BOT if x not in c else (c, a | {x}, s | {x})


def revoke(st, x):
    c, a, s = st
    return BOT if x not in s else (c, a, s - {x})


def bad(st, x):
    c, a, s = st
    return (c, a, s | {x})


def refuse(st, x):
    return BOT


def noop(st, x):
    return st


POOL = {'record': record, 'grant': grant, 'grant2': grant, 'revoke': revoke, 'bad': bad, 'refuse': refuse, 'noop': noop}
S0 = (frozenset(), frozenset(), frozenset())
R = {'R1 record a claim': lambda st: len(st[0]) > 0,
     'R2 give a claim standing': lambda st: len(st[2]) > 0}


def preserves(M):
    return all(POOL[m](st, x) is BOT or inv(POOL[m](st, x)) for m in M for st in S_I for x in X)


def reachable(M):
    seen, todo = {S0}, [S0]
    while todo:
        st = todo.pop()
        for m in M:
            for x in X:
                nxt = POOL[m](st, x)
                if nxt is not BOT and nxt not in seen:
                    seen.add(nxt); todo.append(nxt)
    return seen


def req(M):
    reach = reachable(M)
    return all(any(goal(st) for st in reach) for goal in R.values())


def kernel(M):
    return preserves(M) and req(M)


def proper_subsets(M):
    return [frozenset(c) for k in range(len(M)) for c in combinations(sorted(M), k)]


def minimal_old(M):        # the scaffold as written: preservation + "no proper subset preserves"
    return preserves(M) and not any(preserves(N) for N in proper_subsets(M))


def minimal_new(M):
    return kernel(M) and not any(kernel(N) for N in proper_subsets(M))


def show(label, M):
    M = frozenset(M)
    print(f'{label:<34} P={preserves(M)!s:<5} Req={req(M)!s:<5} Kernel={kernel(M)!s:<5} '
          f'Minimal(old)={minimal_old(M)!s:<5} Minimal(new)={minimal_new(M)}')


if __name__ == '__main__':
    print(f'states {len(STATES)}, valid states |S_I| = {len(S_I)}')
    show('M = ∅', [])
    show('M = {record}', ['record'])
    show('M = {record, grant}', ['record', 'grant'])
    show('M = {record, grant, revoke}', ['record', 'grant', 'revoke'])
    show('M = {record, grant, grant2}', ['record', 'grant', 'grant2'])
    show('M = {record, bad}', ['record', 'bad'])
    show('M = {refuse}', ['refuse'])
    show('M = {noop}', ['noop'])
    show('M = {refuse, noop}', ['refuse', 'noop'])
    allM = subsets(POOL)
    old = [sorted(M) for M in allM if minimal_old(M)]
    new = [sorted(M) for M in allM if minimal_new(M)]
    print('minimal sets, scaffold as written :', old)
    print('minimal kernels, with R and ⊥     :', new)
    assert old == [[]], 'as written, only ∅ should be minimal'
    assert not kernel(frozenset()), '∅ must fail once R is required'
    assert kernel(frozenset({'record', 'grant'})), 'a non-empty model must pass'
    assert all('bad' not in M for M in new), 'an invariant-breaking mechanism is never in a kernel'
    assert preserves(frozenset({'refuse'})) and not kernel(frozenset({'refuse'})), 'refusing everything preserves but must fail'
    assert all('refuse' not in M for M in new), 'a mechanism that refuses everything is never in a minimal kernel'
    assert preserves(frozenset({'noop'})) and not kernel(frozenset({'noop'})), 'the unchanged state preserves but must fail'
    assert all('noop' not in M for M in new), 'a mechanism that changes nothing is never in a minimal kernel'
    print('all assertions passed')
