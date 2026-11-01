#!/usr/bin/env python3
"""Finite test for the Req/A theory turn (Ch. 9 §9.19). A toy model, not KnowledgeOS: it tests the FORM of the
minimality claims relative to an interface assumption A, not the content of KnowledgeOS.

State: the set of entry kinds present in the record, a subset of K = {act, evid, loss, root}
  (O1 an act, O2 an evidence entry, O3 a recorded absence or loss, O4 a root act; counts are abstracted away).
Invariant I (one, of A1's shape, Int): act in s  =>  root in s   (no act entry without a root).
Req (the input part of §9.10's core, read as reachability, §9.10.5 R1/R4): for each kind k, EF(k in s) from s0 = {}.
Mechanisms are functions S -> S or BOT (input-free; a mechanism appends one or more kinds).

Pools (the granularity part of A):
  FINE    one appender per kind (a_act guarded by the root, a_evid, a_loss, a_root), the three trivial
          mechanisms (refuse, noop; the empty set is the empty subset) and one invariant-breaker (b_act).
  COARSE  FINE plus two composite mechanisms: c_root_act (root and act in one step) and c_all (all four).
  GUARDS  guards as separate components: u_act (an unguarded act appender), g_root (blocks an act step unless
          a root is present), g_block (blocks every act step), a_evid, a_loss, a_root. The semantics of a set M:
          each appender in M gives a transition, unless a guard in M blocks it.

Checks per pool, over all subsets M:
  P(M)       every transition of M from a state in S_I lands in S_I
  Req(M)     every kind is reachable from s0 using M's transitions
  Kernel     P and Req;   Minimal: Kernel and no proper subset is a Kernel
  structure  P closed under subsets; Req monotone; the kernel family closed under intersection;
             unique minimal kernel  <=>  kernel family closed under intersection  (Theorem T3, its finite instance)
  necessity  which mechanisms lie in every kernel; whether every kernel realizes every kind
  trivial    {}, {refuse}, {noop}: P holds, Req fails
Usage: python3 -I req_a_model.py
"""
from itertools import chain, combinations

K = ('act', 'evid', 'loss', 'root')
BOT = None


def subsets(xs):
    xs = sorted(xs)
    return [frozenset(c) for c in chain.from_iterable(combinations(xs, n) for n in range(len(xs) + 1))]


STATES = subsets(K)
S_I = [s for s in STATES if ('act' not in s) or ('root' in s)]
S0 = frozenset()


def add(*ks):
    return lambda s: s | frozenset(ks)


FINE = {
    'a_act': lambda s: (s | {'act'}) if 'root' in s else BOT,
    'a_evid': add('evid'),
    'a_loss': add('loss'),
    'a_root': add('root'),
    'refuse': lambda s: BOT,
    'noop': lambda s: s,
    'b_act': add('act'),
}
COARSE = dict(FINE, c_root_act=add('root', 'act'), c_all=add(*K))
assert len({tuple(f(s) for s in STATES) for f in COARSE.values()}) == len(COARSE), 'mechanisms not distinct'


def plain_transitions(pool, M):
    return [pool[m] for m in M]


GUARD_APPENDERS = {'u_act': add('act'), 'a_evid': add('evid'), 'a_loss': add('loss'), 'a_root': add('root')}
GUARDS = {'g_root': lambda s, t: 'act' in t - s and 'root' not in s,   # blocks an act step without a root
          'g_block': lambda s, t: 'act' in t - s}                     # blocks every act step


def guarded_transitions(M):
    gs = [GUARDS[m] for m in M if m in GUARDS]
    out = []
    for m in M:
        if m in GUARD_APPENDERS:
            f = GUARD_APPENDERS[m]
            out.append(lambda s, f=f: BOT if any(g(s, f(s)) for g in gs) else f(s))
    return out


def P(trans):
    return all(t(s) is BOT or t(s) in S_I for t in trans for s in S_I)


def reach(trans):
    seen, todo = {S0}, [S0]
    while todo:
        s = todo.pop()
        for t in trans:
            n = t(s)
            if n is not BOT and n not in seen:
                seen.add(n)
                todo.append(n)
    return seen


def Req(trans):
    r = reach(trans)
    return all(any(k in s for s in r) for k in K)


def analyse(name, names, trans_of):
    subs = subsets(names)
    p = {M: P(trans_of(M)) for M in subs}
    q = {M: Req(trans_of(M)) for M in subs}
    ker = [M for M in subs if p[M] and q[M]]
    kset = set(ker)
    minimal = [M for M in ker if not any(N < M for N in kset)]
    p_down = all(p[N] for M in subs if p[M] for N in subsets(M))
    q_mono = all(q[N] for M in subs if q[M] for N in subs if M <= N)
    cap = all((A & B) in kset for A in ker for B in ker)
    print(f'== {name}: {len(names)} mechanisms, {len(subs)} subsets, {len(ker)} kernels')
    print(f'   P closed under subsets: {p_down};  Req monotone: {q_mono};  kernels closed under intersection: {cap}')
    print(f'   minimal kernels ({len(minimal)}): ' + '; '.join('{' + ', '.join(sorted(M)) + '}' for M in minimal))
    assert (len(minimal) == 1) == cap, 'T3 instance fails'
    core = frozenset.intersection(*ker) if ker else frozenset()
    print(f'   in every kernel: {sorted(core)}')
    realizes = all(all(any(k in s for s in reach(trans_of(M))) for k in K) for M in ker)
    print(f'   every kernel realizes every kind: {realizes}')
    for triv in (frozenset(), frozenset({'refuse'}), frozenset({'noop'})):
        if triv <= frozenset(names):
            print(f'   trivial {sorted(triv) or "{}"}: P={p[triv]} Req={q[triv]}')
    return p, q, minimal


analyse('FINE', list(FINE), lambda M: plain_transitions(FINE, M))
analyse('COARSE', list(COARSE), lambda M: plain_transitions(COARSE, M))
p, q, _ = analyse('GUARDS', list(GUARD_APPENDERS) + list(GUARDS), guarded_transitions)
w1 = frozenset({'u_act', 'g_root', 'a_evid', 'a_loss', 'a_root'})
w2 = w1 | {'g_block'}
print(f'   witness, P not closed under subsets: P({sorted(w1)})={p[w1]}, P(without g_root)={p[w1 - {"g_root"}]}')
print(f'   witness, Req not monotone: Req({sorted(w1)})={q[w1]}, Req(+g_block)={q[w2]}')
