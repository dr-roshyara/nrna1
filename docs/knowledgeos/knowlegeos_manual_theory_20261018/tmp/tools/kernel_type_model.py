#!/usr/bin/env python3
"""Finite test of the extended kernel type (Ch. 9 §9.1 C6). A toy model, not KnowledgeOS: it tests the FORM of the
type, not its content. Written 2026-10-19/20 for the whole-book review S0100, stage 2, finding P4-1.

P4-1: the scaffold of §9.1 C2 (kernel_model.py) checks state invariants only. It cannot exclude
  (a) a record mechanism that also deletes old entries (breaks append-only, a TRANSITION property), or
  (b) a mechanism that records an act itself and grants standing on it (breaks the act's outside origin, Ch. 1 A1).
Both preserve the state invariants and meet Req. This script shows that, and that the extended type excludes both.

State s = (claims, acts, standing, used), each a subset of the items {a, b}.
  'used' is a history variable: the items whose external act has been consumed.
Input x = (item, e), e ∈ {act, none}: whether the input carries an act performed outside the kernel on that item.
State invariants I_S:  I1 standing ⊆ acts;  I2 acts ⊆ claims.
Transition invariants I_T over (s, x, s'):
  T1 append-only     claims(s) ⊆ claims(s') and acts(s) ⊆ acts(s')
  T2 origin          if acts or standing change, x carries an act (e = act) and only x's item changes
Trace invariant I_R over runs from s0:
  U  uniqueness      no external act (item, act) is consumed twice in a run
  With the history variable 'used', U becomes the transition invariant T3: a consuming step needs item ∉ used(s)
  and adds it to used(s').
Mechanisms (pool): record, record_del (records x and deletes claims without an act), grant (needs e = act; writes
used), forge (records an act on x and grants it whatever e is), revoke (needs e = act; writes used), grant_nh and
revoke_nh (as grant and revoke, but neither reads nor writes 'used'), refuse, noop.
Guard: g_origin refuses any step that breaks T2 (a constraint on others' transitions: the guard reading of C1).
Required capabilities R (reachability from s0 = (∅, ∅, ∅, ∅)): R1 a recorded claim; R2 a claim with standing.

Checks:
  P_S   state preservation (the scaffold's P, §9.1 C2)
  P_T   every step from a valid state also satisfies T1 and T2
  P_R   every run from s0 satisfies U (checked on the reachable (state, consumed) pairs)
  Kernel'(M, G) = P_S ∧ P_T ∧ P_R ∧ Req, with the steps of M filtered by the guards G.

Usage: python3 -I kernel_type_model.py
"""
from functools import lru_cache
from itertools import chain, combinations

ITEMS = ('a', 'b')
INPUTS = [(i, e) for i in ITEMS for e in ('act', 'none')]
BOT = None


def subsets(xs):
    xs = sorted(xs)
    return [frozenset(c) for c in chain.from_iterable(combinations(xs, k) for k in range(len(xs) + 1))]


SUB = subsets(ITEMS)
STATES = [(c, a, s, u) for c in SUB for a in SUB for s in SUB for u in SUB]


def inv_s(st):
    c, a, s, u = st
    return s <= a and a <= c


S_I = [st for st in STATES if inv_s(st)]


def record(st, x):
    c, a, s, u = st
    i, e = x
    return BOT if i in c else (c | {i}, a, s, u)


def record_del(st, x):                       # records x, and deletes every claim that carries no act
    c, a, s, u = st
    i, e = x
    return BOT if i in c else ((c & a) | {i}, a, s, u)


def grant(st, x):
    c, a, s, u = st
    i, e = x
    return BOT if e != 'act' or i not in c or i in s or i in u else (c, a | {i}, s | {i}, u | {i})


def forge(st, x):                            # the kernel records an act itself: e is ignored
    c, a, s, u = st
    i, e = x
    return BOT if i not in c or i in s else (c, a | {i}, s | {i}, u)


def revoke(st, x):
    c, a, s, u = st
    i, e = x
    return BOT if e != 'act' or i not in s or i in u else (c, a, s - {i}, u | {i})


def grant_nh(st, x):
    c, a, s, u = st
    i, e = x
    return BOT if e != 'act' or i not in c or i in s else (c, a | {i}, s | {i}, u)


def revoke_nh(st, x):
    c, a, s, u = st
    i, e = x
    return BOT if e != 'act' or i not in s else (c, a, s - {i}, u)


def refuse(st, x):
    return BOT


def noop(st, x):
    return st


POOL = {'record': record, 'record_del': record_del, 'grant': grant, 'forge': forge, 'revoke': revoke,
        'grant_nh': grant_nh, 'revoke_nh': revoke_nh, 'refuse': refuse, 'noop': noop}


def t1(s, s2):
    return s[0] <= s2[0] and s[1] <= s2[1]


def changed_items(s, s2):
    return (s[1] ^ s2[1]) | (s[2] ^ s2[2])


def t2(s, x, s2):
    ch = changed_items(s, s2)
    return not ch or (x[1] == 'act' and ch == {x[0]})


def consumes(s, x, s2):
    return x[1] == 'act' and bool(changed_items(s, s2))


def g_origin(s, x, s2):                      # a guard: pass only steps that keep T2
    return t2(s, x, s2)


GUARDS = {'g_origin': g_origin}
S0 = (frozenset(), frozenset(), frozenset(), frozenset())
R = {'R1 record a claim': lambda st: len(st[0]) > 0,
     'R2 give a claim standing': lambda st: len(st[2]) > 0}


def steps(M, G, st):
    for m in M:
        for x in INPUTS:
            nxt = POOL[m](st, x)
            if nxt is not BOT and all(GUARDS[g](st, x, nxt) for g in G):
                yield x, nxt


@lru_cache(maxsize=None)
def p_s(M, G=()):
    return all(inv_s(nxt) for st in S_I for _, nxt in steps(M, G, st))


@lru_cache(maxsize=None)
def p_t(M, G=()):
    return all(t1(st, nxt) and t2(st, x, nxt) for st in S_I for x, nxt in steps(M, G, st))


@lru_cache(maxsize=None)
def p_r(M, G=()):                            # U over runs: explore (state, consumed acts) from s0
    start = (S0, frozenset())
    seen, todo = {start}, [start]
    while todo:
        st, done = todo.pop()
        for x, nxt in steps(M, G, st):
            d2 = done
            if consumes(st, x, nxt):
                if x[0] in done:
                    return False
                d2 = done | {x[0]}
            if (nxt, d2) not in seen:
                seen.add((nxt, d2)); todo.append((nxt, d2))
    return True


@lru_cache(maxsize=None)
def reachable(M, G=()):
    seen, todo = {S0}, [S0]
    while todo:
        st = todo.pop()
        for _, nxt in steps(M, G, st):
            if nxt not in seen:
                seen.add(nxt); todo.append(nxt)
    return frozenset(seen)


def req(M, G=()):
    reach = reachable(M, G)
    return all(any(goal(st) for st in reach) for goal in R.values())


def kernel_old(M):                           # §9.1 C2: P (state invariants) ∧ Req
    return p_s(M) and req(M)


def kernel_new(M, G=()):                     # §9.1 C6
    return p_s(M, G) and p_t(M, G) and p_r(M, G) and req(M, G)


def proper_subsets(M):
    return [frozenset(c) for k in range(len(M)) for c in combinations(sorted(M), k)]


def minimal_new(M, G=()):
    return kernel_new(M, G) and not any(kernel_new(N, G) for N in proper_subsets(M))


def show(label, M, G=()):
    M = frozenset(M)
    print(f'{label:<38} P_S={p_s(M, G)!s:<5} P_T={p_t(M, G)!s:<5} P_R={p_r(M, G)!s:<5} Req={req(M, G)!s:<5} '
          f'Kernel(C2)={kernel_old(M) if not G else "n/a"!s:<5} Kernel(C6)={kernel_new(M, G)}')


if __name__ == '__main__':
    allM = subsets(POOL)
    print(f'states {len(STATES)}, valid |S_I| = {len(S_I)}, inputs {len(INPUTS)}, pool {len(POOL)}, {len(allM)} subsets')
    cases = [('{record, grant}', ['record', 'grant']), ('{record_del, grant}  (deleting record)', ['record_del', 'grant']),
             ('{record, forge}  (forge)', ['record', 'forge']), ('{record, grant, revoke}', ['record', 'grant', 'revoke']),
             ('{record, grant_nh}', ['record', 'grant_nh']), ('{record, revoke_nh}', ['record', 'revoke_nh']),
             ('{record, grant_nh, revoke_nh}', ['record', 'grant_nh', 'revoke_nh']),
             ('∅', []), ('{refuse}', ['refuse']), ('{noop}', ['noop'])]
    for label, M in cases:
        show(label, M)
    show('{record, forge} + guard g_origin', ['record', 'forge'], ('g_origin',))
    # (1) the scaffold admits both counter-models; the extended type excludes both
    for M in (('record_del', 'grant'), ('record', 'forge')):
        assert kernel_old(frozenset(M)) and not kernel_new(frozenset(M)), M
    assert kernel_new(frozenset({'record', 'grant'}))
    # (2) trivial cases still fail Req
    for M in ([], ['refuse'], ['noop']):
        assert not kernel_new(frozenset(M)) and p_s(frozenset(M)) and p_t(frozenset(M)), M
    # (3) C3 re-checked. P_S ∧ P_T is a condition on each mechanism: closed under union and subsets.
    pst = lambda M: p_s(M) and p_t(M)
    closed_st = all(pst(A | B) == (pst(A) and pst(B)) for A in allM for B in allM)
    # U as a trace invariant is NOT: grant_nh and revoke_nh each satisfy it alone, not together
    assert p_r(frozenset({'record', 'grant_nh'})) and p_r(frozenset({'record', 'revoke_nh'}))
    assert not p_r(frozenset({'record', 'grant_nh', 'revoke_nh'}))
    closed_r = all(p_r(A | B) == (p_r(A) and p_r(B)) for A in allM for B in allM)
    # with the history variable read and written by every consuming mechanism, U holds for every union of such
    hist = frozenset({'record', 'grant', 'revoke', 'refuse', 'noop'})
    assert all(p_r(A) for A in subsets(hist))
    monotone = all(req(B) for A in allM for B in allM if A <= B and req(A))
    U = frozenset(m for m in POOL if pst(frozenset({m})) and p_r(frozenset({m})))
    print('P_S ∧ P_T closed under union and subsets :', closed_st)
    print('P_R (U as a trace invariant) closed      :', closed_r)
    print('Req monotone (closed reading)            :', monotone)
    print('mechanisms preserving S, T, R alone      :', sorted(U), '-> Kernel(C6) =', kernel_new(U))
    assert closed_st and not closed_r and monotone
    # existence by one check, per class: union of individually preserving mechanisms within the history class
    Uh = U & hist
    assert kernel_new(Uh) == any(kernel_new(M) for M in subsets(hist)), 'C3 for S, T and history-encoded R'
    print('within the history class, existence = Kernel(C6)(U ∩ hist) :', kernel_new(Uh))
    # (4) the guard reading: a guard turns forge into a kernel, and adding a guard can only remove steps
    assert kernel_new(frozenset({'record', 'forge'}), ('g_origin',))
    assert all(reachable(frozenset(M), ('g_origin',)) <= reachable(frozenset(M)) for M in allM)
    print('minimal kernels (C6), no guards          :', sorted(sorted(M) for M in allM if minimal_new(M)))
    print('all assertions passed')
