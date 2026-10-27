#!/usr/bin/env python3
"""Finite test of the extended kernel type (Ch. 9 §9.1 C6). A toy model, not KnowledgeOS: it tests the FORM of the
type, not its content.

Version 2 (2026-10-20), after the turn-0 review of stage 2 (REPORT-A A-1, A-2; REPORT-B B-6). Changes from version 1:
- acts have their own identity: an input carries no act, or the act (item, k), k in {1, 2}. In version 1 an act was
  identified with its item, so `revoke` could never fire after `grant` (A-2, B-6);
- the history rule is now checked, in two forms: T3w (a consuming step needs its act unused and records it) and
  T3 (T3w, and the history variable never shrinks). A mechanism `clear_used` that empties the history and consumes
  nothing passes T3w and breaks uniqueness together with grant and revoke (A-1); T3 excludes it;
- the "history class" is derived from T3, not chosen by hand.

State s = (claims, acts, standing, used): claims, acts, standing are subsets of the items {a, b}; used is a subset of
the act identities {(a,1), (a,2), (b,1), (b,2)} (the history variable). s0 = (∅, ∅, ∅, ∅).
Input x = (item, k): k = 0 means no act; k in {1, 2} names an act performed outside the kernel (a trusted label:
A1-as-form, §9.9 T-b; the model cannot tell a true label from a false one).
State invariants I_S:  I1 standing ⊆ acts;  I2 acts ⊆ claims.
Transition invariants: T1 append-only (claims, acts); T2 origin (acts or standing change only on an input carrying an
act, and only for that input's item); T3w / T3 as above.
Trace invariant U: no act identity is consumed twice in a run (a step consumes its act if it carries one and changes
acts or standing).
Mechanisms: record, record_del, grant, forge, revoke, grant_nh, revoke_nh (no history), clear_used, refuse, noop.
Guard: g_origin (passes only steps that keep T2).
Req: R1 a recorded claim; R2 a claim with standing. R3 (a claim granted and then revoked: acts ∋ i, standing ∌ i) is
used only to show that revoke is reachable, not as a kernel requirement.

Usage: python3 -I kernel_type_model.py
"""
from functools import lru_cache
from itertools import chain, combinations

ITEMS = ('a', 'b')
ACTS = tuple((i, k) for i in ITEMS for k in (1, 2))
INPUTS = tuple((i, k) for i in ITEMS for k in (0, 1, 2))
BOT = None


def subsets(xs):
    xs = sorted(xs)
    return [frozenset(c) for c in chain.from_iterable(combinations(xs, k) for k in range(len(xs) + 1))]


SUB, USUB = subsets(ITEMS), subsets(ACTS)
STATES = [(c, a, s, u) for c in SUB for a in SUB for s in SUB for u in USUB]
S0 = (frozenset(), frozenset(), frozenset(), frozenset())


def inv_s(st):
    c, a, s, u = st
    return s <= a and a <= c


S_I = [st for st in STATES if inv_s(st)]


def record(st, x):
    c, a, s, u = st; i, k = x
    return BOT if i in c else (c | {i}, a, s, u)


def record_del(st, x):                       # records x, and deletes every claim that carries no act
    c, a, s, u = st; i, k = x
    return BOT if i in c else ((c & a) | {i}, a, s, u)


def grant(st, x):
    c, a, s, u = st; i, k = x
    return BOT if k == 0 or i not in c or i in s or (i, k) in u else (c, a | {i}, s | {i}, u | {(i, k)})


def forge(st, x):                            # the kernel records an act itself: the input's act is ignored
    c, a, s, u = st; i, k = x
    return BOT if i not in c or i in s else (c, a | {i}, s | {i}, u)


def revoke(st, x):
    c, a, s, u = st; i, k = x
    return BOT if k == 0 or i not in s or (i, k) in u else (c, a, s - {i}, u | {(i, k)})


def grant_nh(st, x):
    c, a, s, u = st; i, k = x
    return BOT if k == 0 or i not in c or i in s else (c, a | {i}, s | {i}, u)


def revoke_nh(st, x):
    c, a, s, u = st; i, k = x
    return BOT if k == 0 or i not in s else (c, a, s - {i}, u)


def clear_used(st, x):                       # empties the history variable; consumes no act
    c, a, s, u = st
    return BOT if not u else (c, a, s, frozenset())


def refuse(st, x):
    return BOT


def noop(st, x):
    return st


POOL = {'record': record, 'record_del': record_del, 'grant': grant, 'forge': forge, 'revoke': revoke,
        'grant_nh': grant_nh, 'revoke_nh': revoke_nh, 'clear_used': clear_used, 'refuse': refuse, 'noop': noop}


def changed(s, s2):
    return (s[1] ^ s2[1]) | (s[2] ^ s2[2])


def consumes(s, x, s2):
    return x[1] != 0 and bool(changed(s, s2))


def t1(s, x, s2):
    return s[0] <= s2[0] and s[1] <= s2[1]


def t2(s, x, s2):
    ch = changed(s, s2)
    return not ch or (x[1] != 0 and ch == {x[0]})


def t3w(s, x, s2):                           # a consuming step needs its act unused, and records it
    return not consumes(s, x, s2) or (x not in s[3] and x in s2[3])


def t3(s, x, s2):                            # T3w, and the history variable never shrinks
    return t3w(s, x, s2) and s[3] <= s2[3]


def g_origin(s, x, s2):
    return t2(s, x, s2)


GUARDS = {'g_origin': g_origin}
R = {'R1 record a claim': lambda st: len(st[0]) > 0,
     'R2 give a claim standing': lambda st: len(st[2]) > 0}
R3 = lambda st: bool(st[1] - st[2])          # granted, then revoked


@lru_cache(maxsize=None)
def steps_of(m):                             # all steps of one mechanism from valid states
    out = []
    for st in S_I:
        for x in INPUTS:
            nxt = POOL[m](st, x)
            if nxt is not BOT:
                out.append((st, x, nxt))
    return tuple(out)


def holds_step(M, G, test):
    return all(test(s, x, s2) for m in M for (s, x, s2) in steps_of(m) if all(GUARDS[g](s, x, s2) for g in G))


def p_s(M, G=()):
    return holds_step(M, G, lambda s, x, s2: inv_s(s2))


def p_t(M, G=(), hist=None):                 # T1 ∧ T2 (∧ T3w or T3 when hist is given)
    extra = {None: lambda s, x, s2: True, 'T3w': t3w, 'T3': t3}[hist]
    return holds_step(M, G, lambda s, x, s2: t1(s, x, s2) and t2(s, x, s2) and extra(s, x, s2))


def succ(M, G, st):
    for m in M:
        for x in INPUTS:
            nxt = POOL[m](st, x)
            if nxt is not BOT and all(GUARDS[g](st, x, nxt) for g in G):
                yield x, nxt


@lru_cache(maxsize=None)
def p_r(M, G=()):                            # U over runs from s0, tracking the acts consumed so far
    start = (S0, frozenset())
    seen, todo = {start}, [start]
    while todo:
        st, done = todo.pop()
        for x, nxt in succ(M, G, st):
            d2 = done
            if consumes(st, x, nxt):
                if x in done:
                    return False
                d2 = done | {x}
            if (nxt, d2) not in seen:
                seen.add((nxt, d2)); todo.append((nxt, d2))
    return True


@lru_cache(maxsize=None)
def reachable(M, G=()):
    seen, todo = {S0}, [S0]
    while todo:
        st = todo.pop()
        for _, nxt in succ(M, G, st):
            if nxt not in seen:
                seen.add(nxt); todo.append(nxt)
    return frozenset(seen)


def req(M, G=()):
    reach = reachable(M, G)
    return all(any(goal(st) for st in reach) for goal in R.values())


def kernel_old(M):
    return p_s(M) and req(M)


def kernel_new(M, G=()):
    return p_s(M, G) and p_t(M, G) and p_r(M, G) and req(M, G)


def F(*names):
    return frozenset(names)


if __name__ == '__main__':
    print(f'states {len(STATES)}, valid |S_I| = {len(S_I)}, inputs {len(INPUTS)}, pool {len(POOL)}')
    # (1) both counter-models pass C2 and fail C6; {record, grant} passes both
    for M in (F('record_del', 'grant'), F('record', 'forge')):
        assert kernel_old(M) and not kernel_new(M), sorted(M)
    assert kernel_new(F('record', 'grant'))
    print('(1) deleting record and forge: kernels under C2, not under C6')
    # (2) revocation is reachable now (A-2, B-6)
    assert any(R3(st) for st in reachable(F('record', 'grant', 'revoke')))
    assert kernel_new(F('record', 'grant', 'revoke'))
    print('(2) revoke fires: a granted-then-revoked state is reachable in {record, grant, revoke}')
    # (3) trivial cases fail Req
    for M in (F(), F('refuse'), F('noop')):
        assert p_s(M) and p_t(M) and not req(M)
    print('(3) ∅, refuse, noop: preserve, fail Req')
    # (4) result 4: uniqueness is not a per-mechanism condition (acts distinct from items)
    assert p_r(F('record', 'grant_nh')) and p_r(F('record', 'revoke_nh'))
    assert p_s(F('record', 'grant_nh', 'revoke_nh')) and p_t(F('record', 'grant_nh', 'revoke_nh'))
    assert not p_r(F('record', 'grant_nh', 'revoke_nh'))
    print('(4) grant_nh, revoke_nh: each keeps U alone; together a run grants (a,1), revokes (a,2), grants (a,1) again')
    # (5) A-1: T3w is not enough; T3 (history never shrinks) is
    cu = F('clear_used')
    assert p_s(cu) and p_t(cu, hist='T3w') and p_r(cu) and not p_t(cu, hist='T3')
    assert not p_r(F('record', 'grant', 'revoke', 'clear_used'))
    print('(5a) clear_used passes T1, T2, T3w and U alone; with record, grant, revoke it breaks U; T3 excludes it')
    cls = [m for m in POOL if p_s(F(m)) and p_t(F(m), hist='T3')]
    print('     class of mechanisms satisfying I_S, T1, T2, T3 (derived):', sorted(cls))
    assert all(p_r(M) for M in subsets(cls)), 'T3 + history empty at s0 => U for every union in the class'
    print('(5b) every union of mechanisms in that class keeps U (', len(subsets(cls)), 'subsets checked)')
    U = F(*cls)
    assert kernel_new(U) == any(kernel_new(M) for M in subsets(cls))
    print('(5c) within the class, a kernel exists iff the union of the class is a kernel:', kernel_new(U))
    allpres = F(*[m for m in POOL if p_s(F(m)) and p_t(F(m)) and p_r(F(m))])
    print('     union of all mechanisms preserving I_S, T1, T2, U alone:', sorted(allpres), '-> kernel:', kernel_new(allpres))
    assert not kernel_new(allpres)
    # (6) guards
    assert kernel_new(F('record', 'forge'), ('g_origin',))
    print('(6) {record, forge} with the guard g_origin is a kernel; a guard only removes steps')
    print('all assertions passed')
