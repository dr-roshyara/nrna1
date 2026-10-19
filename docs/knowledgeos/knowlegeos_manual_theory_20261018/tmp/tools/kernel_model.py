#!/usr/bin/env python3
"""Finite test of the kernel scaffold (Ch. 9 §9.1). A toy model, not KnowledgeOS: it tests the FORM of the scaffold, not its content.

Revised 2026-10-19 after the group-C7 review (finding 1): the earlier pool had 'grant2' bound to the same
function as 'grant', so its "two minimal kernels" were one kernel under two labels. Mechanisms are functions;
the pool now holds only functionally distinct mechanisms, and the script asserts that.

State s = (claims, acts, standing), each a subset of the item set X = {a, b}.
Invariants I (safety: what must never break):
  I1  standing ⊆ acts      no standing without a recorded act (the shape of Ch. 1 A1)
  I2  acts ⊆ claims        an act is about a recorded claim
Mechanisms m : S × X → S ∪ {⊥} (pool):
  record    adds x to claims; ⊥ if already recorded
  grant     records an act on x and gives x standing; ⊥ if x is not recorded
  recgrant  records x and grants it in one step; ⊥ if x is already recorded (a coarser mechanism)
  revoke    removes x's standing; ⊥ if x has none
  bad       gives x standing without an act (breaks I1)
  refuse    refuses every input (always ⊥)
  noop      returns the state unchanged
Required capabilities R (reachability from s0 = (∅, ∅, ∅)):
  R1  some state with a recorded claim is reachable
  R2  some state with a claim that has standing is reachable

Checks:
  P   (preservation with refusal)  ∀m ∈ M, s ∈ S_I, x: m(s, x) ∈ S_I ∪ {⊥}
  Req (required capabilities)      ∀r ∈ R: some state satisfying r is reachable from s0 using only M
  Kernel(M) = P ∧ Req;  Minimal(M) = Kernel(M) ∧ no proper subset is a Kernel.
Trivial cases (Rulebook §4): the empty set, refuse-everything and the unchanged state (noop) each satisfy P and fail Req.
Structural facts checked over all subsets: P is closed under union and under subsets; Req is monotone.
Hence a kernel exists iff the set of all individually preserving pool mechanisms satisfies Req.

Usage: python3 -I kernel_model.py
"""
from itertools import chain, combinations

X = ('a', 'b')
BOT = None


def subsets(xs):
    xs = sorted(xs)
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


def recgrant(st, x):
    c, a, s = st
    return BOT if x in c else (c | {x}, a | {x}, s | {x})


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


POOL = {'record': record, 'grant': grant, 'recgrant': recgrant, 'revoke': revoke,
        'bad': bad, 'refuse': refuse, 'noop': noop}
S0 = (frozenset(), frozenset(), frozenset())
R = {'R1 record a claim': lambda st: len(st[0]) > 0,
     'R2 give a claim standing': lambda st: len(st[2]) > 0}


def behaviour(f):
    return tuple(f(st, x) for st in STATES for x in X)


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


def req(M, reqs=R):
    reach = reachable(M)
    return all(any(goal(st) for st in reach) for goal in reqs.values())


def kernel(M, reqs=R):
    return preserves(M) and req(M, reqs)


def proper_subsets(M):
    return [frozenset(c) for k in range(len(M)) for c in combinations(sorted(M), k)]


def minimal_old(M):        # the scaffold as written: preservation + "no proper subset preserves"
    return preserves(M) and not any(preserves(N) for N in proper_subsets(M))


def minimal_new(M, reqs=R):
    return kernel(M, reqs) and not any(kernel(N, reqs) for N in proper_subsets(M))


def show(label, M):
    M = frozenset(M)
    print(f'{label:<34} P={preserves(M)!s:<5} Req={req(M)!s:<5} Kernel={kernel(M)!s:<5} '
          f'Minimal(old)={minimal_old(M)!s:<5} Minimal(new)={minimal_new(M)}')


if __name__ == '__main__':
    names = sorted(POOL)
    assert len({behaviour(POOL[n]) for n in names}) == len(names), 'pool mechanisms must be functionally distinct'
    allM = subsets(POOL)
    print(f'states {len(STATES)}, valid states |S_I| = {len(S_I)}, pool {len(POOL)} mechanisms, {len(allM)} subsets')
    for label, M in [('M = ∅', []), ('M = {record}', ['record']), ('M = {record, grant}', ['record', 'grant']),
                     ('M = {recgrant}', ['recgrant']), ('M = {record, grant, revoke}', ['record', 'grant', 'revoke']),
                     ('M = {record, grant, recgrant}', ['record', 'grant', 'recgrant']),
                     ('M = {record, bad}', ['record', 'bad']), ('M = {refuse}', ['refuse']),
                     ('M = {noop}', ['noop']), ('M = {refuse, noop}', ['refuse', 'noop'])]:
        show(label, M)
    old = [sorted(M) for M in allM if minimal_old(M)]
    new = [sorted(M) for M in allM if minimal_new(M)]
    print('minimal sets, scaffold as written :', old)
    print('minimal kernels, with R and ⊥     :', new)
    union_closed = all(preserves(A | B) == (preserves(A) and preserves(B)) for A in allM for B in allM)
    monotone = all(req(B) for A in allM for B in allM if A <= B and req(A))
    pres_all = frozenset(m for m in POOL if preserves({m}))
    print('P closed under union and subsets  :', union_closed)
    print('Req monotone                      :', monotone)
    print('union of preserving mechanisms    :', sorted(pres_all), '-> Kernel =', kernel(pres_all))
    small = {k for k in POOL if k != 'recgrant'}
    print('pool without recgrant, minimal    :', [sorted(M) for M in subsets(small) if minimal_new(M)])
    print('with R = {}, minimal kernels      :', [sorted(M) for M in allM if minimal_new(M, {})])
    assert old == [[]], 'as written, only ∅ should be minimal'
    assert not kernel(frozenset()), '∅ must fail once R is required'
    assert kernel(frozenset({'record', 'grant'})), 'a non-empty model must pass'
    assert all('bad' not in M for M in new), 'an invariant-breaking mechanism is never in a kernel'
    for t in ('refuse', 'noop'):
        assert preserves(frozenset({t})) and not kernel(frozenset({t})), f'{t} preserves but must fail'
        assert all(t not in M for M in new), f'{t} is never in a minimal kernel'
    assert union_closed and monotone and kernel(pres_all) == bool(new), 'existence = Kernel(union of preserving)'
    assert sorted(map(tuple, new)) == [('grant', 'record'), ('recgrant',)], 'minimal kernels depend on the pool'
    assert [sorted(M) for M in subsets(small) if minimal_new(M)] == [['grant', 'record']], 'unique without recgrant'
    assert [sorted(M) for M in allM if minimal_new(M, {})] == [[]], 'with no requirements, ∅ is the kernel again'
    print('all assertions passed')
