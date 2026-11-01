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
Version 2 (2026-11-01, settlement of the Req/A review; findings B F5, C-04, C-05, A-01, A-12):
  ALT      FINE plus a_evid2, a second, non-composite appender of evid (guarded by the root): an alternative route.
  COARSE-T COARSE under a transition invariant "at most one kind added per step" (an input carries one entry).
  GACT     acts split into grant and establishment kinds; Req_O cut two ways: "an act entry" (one O1) or per kind.
  HALT     FINE's appenders plus a guard g_halt (blocks every step once evid is present): Req_O (EF) against
           the enabledness-like condition AG EF(every kind present).
  The v1 line "every kernel realizes every kind" re-tests Req_O itself (A-12); it is kept as a sanity check, not as support.
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


def reach_from(s0, trans):
    seen, todo = {s0}, [s0]
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


# ---- version 2 ----
print('\n== version 2 ==')
ALT = dict(FINE, a_evid2=lambda s: (s | {'evid'}) if 'root' in s else BOT)
analyse('ALT (FINE + a_evid2, no composite)', list(ALT), lambda M: plain_transitions(ALT, M))


COARSE_T = {k: f for k, f in COARSE.items() if all(f(s) is BOT or len(f(s) - s) <= 1 for s in S_I)}
print('COARSE mechanisms meeting the transition invariant:', sorted(COARSE_T))
analyse('COARSE-T (one kind per step)', list(COARSE_T), lambda M: plain_transitions(COARSE_T, M))

# GACT: kinds grant, estab, evid, loss, root; I: (grant or estab) => root
K2 = ('grant', 'estab', 'evid', 'loss', 'root')
ST2 = subsets(K2)
SI2 = [s for s in ST2 if not ({'grant', 'estab'} & s) or 'root' in s]
GACT = {'a_grant': lambda s: (s | {'grant'}) if 'root' in s else BOT,
        'a_estab': lambda s: (s | {'estab'}) if 'root' in s else BOT,
        'a_evid': add('evid'), 'a_loss': add('loss'), 'a_root': add('root')}


def reach2(trans):
    seen, todo = {S0}, [S0]
    while todo:
        s = todo.pop()
        for t in trans:
            n = t(s)
            if n is not BOT and n not in seen:
                seen.add(n); todo.append(n)
    return seen


for cut_name, reqs in (('O1 = "an act entry"', [{'grant', 'estab'}, {'evid'}, {'loss'}, {'root'}]),
                       ('O1 per kind', [{'grant'}, {'estab'}, {'evid'}, {'loss'}, {'root'}])):
    subs = subsets(GACT)
    ker = []
    for M in subs:
        tr = [GACT[m] for m in M]
        if not all(t(s) is BOT or t(s) in SI2 for t in tr for s in SI2):
            continue
        r = reach2(tr)
        if all(any(s & q for s in r) for q in reqs):
            ker.append(M)
    mins = [M for M in ker if not any(N < M for N in ker)]
    print(f'== GACT, {cut_name}: {len(ker)} kernels; minimal ({len(mins)}): ' + '; '.join('{' + ', '.join(sorted(M)) + '}' for M in mins))

# HALT: guarded semantics over FINE's appenders plus g_halt
HA = {'a_act': FINE['a_act'], 'a_evid': FINE['a_evid'], 'a_loss': FINE['a_loss'], 'a_root': FINE['a_root']}


def halt_trans(M):
    halt = 'g_halt' in M
    return [lambda s, f=HA[m]: BOT if (halt and 'evid' in s) else f(s) for m in M if m in HA]


for M in (frozenset(HA), frozenset(HA) | {'g_halt'}):
    tr = halt_trans(M)
    r = reach(tr)
    req = all(any(k in s for s in r) for k in K)
    full = frozenset(K)
    agef = all(full in reach_from(s, tr) for s in r)
    print(f'== HALT {sorted(M)}: P={P(tr)} Req_O(EF)={req} AG EF(all kinds)={agef}')
