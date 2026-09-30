#!/usr/bin/env python3
"""Independent exhaustive verifier for SPEC-G2-r2 (Python 3, standard library only).

Every model/instance/axiom-set is compiled into its explicit finite state space and its
MAXIMAL step relation (all triples x -k-> x' satisfying the axiom set).  All properties
are decided by exhaustive enumeration / breadth-first search over that finite graph.
See METHOD.md for the exactness argument and interpretation choices.
"""
import itertools
import json
import sys
from collections import deque

KINDS = ('GOV', 'EVID', 'EVIDREF', 'WORK', 'COMP')
GOV, EVID, EVIDREF, WORK, COMP = range(5)
ALL_KINDS = frozenset(range(5))
PV = ('gen', 'der')
SV = ('auth', 'prov', 'hist')
MV = ('NONE', 'RULE', 'EXC')
BASE_AXIOMS = ['A0', 'A1', 'A2e', 'A3g', 'A3s', 'A3m', 'A4', 'A5e', 'A5g', 'A5s', 'A6']

INSTANCES = [
    ('chain3', ['n0', 'n1', 'n2'], [('n0', 'n1'), ('n1', 'n2')]),
    ('V', ['bot', 'a', 'b'], [('bot', 'a'), ('bot', 'b')]),
    ('diamond', ['bot', 'a', 'b', 'top'], [('bot', 'a'), ('bot', 'b'), ('a', 'top'), ('b', 'top')]),
    ('antichain2', ['a', 'b'], []),
]

# extras: (component, domain or None for "bars"); all initial values are 0 / NONE, b := u
MODELS = [
    ('M0', [], []),
    ('M0b', [('b', None)], ['B6']),
    ('M1', [('x', (0, 1)), ('v', (0, 1))], ['X1', 'X2', 'X3', 'X4']),
    ('M2', [('m', (0, 1, 2)), ('ad', (0, 1))], ['Y1', 'Y2', 'Y2b', 'Y3', 'Y4']),
    ('M3a', [('au', (0, 1)), ('ad', (0, 1))], ['W1', 'W3', 'W4']),
    ('M3b', [('au', (0, 1)), ('ad', (0, 1))], ['W1', 'W3', 'W4', 'W2']),
]
NEEDS_BOTTOM = {'M1', 'M2', 'M3b'}

PROPS = ['D1', 'D2', 'D6', 'D3-history[N]', 'D3-history[PS]', 'D3+[N]', 'D5[N]', 'NV[N]',
         'D3-state-bar-event', 'D3-state-bar-inv', 'D3-state-floor-event', 'D3-state-floor-inv',
         'P-GUARD', 'P-BAR', 'P-PERSIST']


class Instance:
    def __init__(self, name, elems, pairs):
        self.name = name
        self.elems = elems
        n = self.n = len(elems)
        ix = {x: i for i, x in enumerate(elems)}
        leq = [[i == j for j in range(n)] for i in range(n)]
        for a, b in pairs:
            leq[ix[a]][ix[b]] = True
        for k in range(n):
            for i in range(n):
                for j in range(n):
                    if leq[i][k] and leq[k][j]:
                        leq[i][j] = True
        self.leq = leq
        minimal = [i for i in range(n) if not any(leq[j][i] and j != i for j in range(n))]
        self.bot = minimal[0] if len(minimal) == 1 else None
        self.allsets = list(range(1 << n))

        def is_up(m):
            return all(not (m >> i) & 1 or all((m >> j) & 1 for j in range(n) if leq[i][j])
                       for i in range(n))
        self.upsets = [m for m in self.allsets if is_up(m)]

    def mask_names(self, m):
        return [self.elems[i] for i in range(self.n) if (m >> i) & 1]

    def mask_of(self, names):
        m = 0
        for x in names:
            m |= 1 << self.elems.index(x)
        return m


def in_u(e, m):
    return (m >> e) & 1 == 1


def axiom_defs(inst, I):
    leq, bot = inst.leq, inst.bot
    P, U, E, S, G = I['p'], I['u'], I['e'], I['s'], I['g']
    EV = (EVID, EVIDREF)

    def notbot(e):  # no bottom element: "e != bottom" is true for every e
        return bot is None or e != bot
    D = {
        'A1': (['p'], lambda s, k, t: t[P] == s[P]),
        'A2e': (['e'], lambda s, k, t: k != GOV or t[E] == s[E]),
        'A3g': (['g'], lambda s, k, t: k not in EV or t[G] == s[G]),
        'A3s': (['s'], lambda s, k, t: k not in EV or t[S] == s[S]),
        'A3m': (['e'], lambda s, k, t: (k != EVID or leq[s[E]][t[E]]) and
                (k != EVIDREF or leq[t[E]][s[E]])),
        'A4': ([], lambda s, k, t: k != COMP),
        'A5e': (['e'], lambda s, k, t: k != WORK or t[E] == s[E]),
        'A5g': (['g'], lambda s, k, t: k != WORK or t[G] == s[G]),
        'A5s': (['s'], lambda s, k, t: k != WORK or t[S] == s[S]),
        'A6': (['u'], lambda s, k, t: t[U] == s[U]),
    }
    if 'b' in I:
        B = I['b']
        D['B6'] = (['b'], lambda s, k, t: t[B] == s[B])
    if 'x' in I:
        X, V = I['x'], I['v']
        D['X1'] = (['x', 'v'], lambda s, k, t: not (t[X] != s[X] or t[V] != s[V]) or k == GOV)
        D['X2'] = (['x'], lambda s, k, t: not (s[X] == 0 and t[X] == 1) or
                   (notbot(s[E]) and not in_u(s[E], s[U])))
        D['X3'] = (['x', 'v'], lambda s, k, t: not (s[X] == 0 and t[X] == 1) or t[V] == 1)
        D['X4'] = (['x', 'v'], lambda s, k, t: t[X] >= s[X] and t[V] >= s[V])
    if 'm' in I:
        M, AD = I['m'], I['ad']
        D['Y1'] = (['m', 'ad'], lambda s, k, t: not (t[M] != s[M] or t[AD] != s[AD]) or k == GOV)
        D['Y2'] = (['m'], lambda s, k, t: not (s[M] == 0 and t[M] == 2) or
                   (notbot(s[E]) and not in_u(s[E], s[U])))
        D['Y2b'] = (['m'], lambda s, k, t: not (s[M] == 0 and t[M] == 1) or in_u(s[E], s[U]))
        D['Y3'] = (['m', 'ad'], lambda s, k, t: not (s[AD] == 0 and t[AD] == 1) or
                   ((t[M] == 1 and in_u(s[E], s[U])) or t[M] == 2))
        D['Y4'] = (['m', 'ad'], lambda s, k, t: t[AD] >= s[AD] and (s[M] == 0 or t[M] == s[M]))
    if 'au' in I:
        AU, AD = I['au'], I['ad']
        D['W1'] = (['au', 'ad'], lambda s, k, t: not (t[AU] != s[AU] or t[AD] != s[AD]) or k == GOV)
        D['W3'] = (['au', 'ad'], lambda s, k, t: not (s[AD] == 0 and t[AD] == 1) or t[AU] == 1)
        D['W4'] = (['au', 'ad'], lambda s, k, t: t[AU] >= s[AU] and t[AD] >= s[AD])
        D['W2'] = (['au'], lambda s, k, t: not (s[AU] == 0 and t[AU] == 1) or
                   (in_u(s[E], s[U]) or notbot(s[E])))
    return D


class System:
    """Explicit state space + maximal step relation for (model, instance, axiom set)."""

    def __init__(self, model, inst, axioms):
        self.model, self.inst, self.axioms = model, inst, frozenset(axioms)
        mname, extras, added = model
        self.mname = mname
        self.comps = ['p', 'u'] + [c for c, _ in extras] + ['e', 's', 'g']
        I = self.I = {c: i for i, c in enumerate(self.comps)}
        bars = inst.upsets if 'A0' in self.axioms else inst.allsets
        doms = []
        for c in self.comps:
            if c == 'p':
                doms.append(range(2))
            elif c == 's':
                doms.append(range(3))
            elif c == 'e':
                doms.append(range(inst.n))
            elif c == 'g':
                doms.append(range(2))
            elif c in ('u', 'b'):
                doms.append(bars)
            else:
                doms.append(dict(extras)[c])
        self.doms = [list(d) for d in doms]
        self.states = list(itertools.product(*self.doms))
        self.index = {st: i for i, st in enumerate(self.states)}
        nc = len(self.comps)
        defs = axiom_defs(inst, I)
        self.pre = []
        self.bucket = [[] for _ in range(nc)]
        for name in BASE_AXIOMS + added:
            if name == 'A0' or name not in self.axioms:
                continue
            deps, fn = defs[name]
            if not deps:
                self.pre.append(fn)
            else:
                self.bucket[max(I[d] for d in deps)].append(fn)
        self.succ = [self._successors(st) for st in self.states]
        # notions
        E, U, G = I['e'], I['u'], I['g']
        if mname == 'M0':
            self.N = lambda st: in_u(st[E], st[U]) and st[G] == 1
        elif mname == 'M0b':
            B = I['b']
            self.N = lambda st: in_u(st[E], st[B]) and st[G] == 1
        elif mname == 'M1':
            X = I['x']
            self.N = lambda st: (in_u(st[E], st[U]) and st[G] == 1) or (st[X] == 1 and st[G] == 1)
        else:
            AD = I['ad']
            self.N = lambda st: st[AD] == 1
        self.PS = lambda st: in_u(st[E], st[U]) and st[G] == 1
        if mname == 'M1':
            X = I['x']
            self.record = lambda st: st[X] == 1
        elif mname == 'M2':
            M = I['m']
            self.record = lambda st: st[M] == 2
        else:
            self.record = None
        extra_names = [c for c, _ in extras]

        def fresh(i):
            st = self.states[i]
            for c in extra_names:
                if c == 'b':
                    if st[I['b']] != st[U]:
                        return False
                elif st[I[c]] != 0:
                    return False
            return True
        self.is_fresh = fresh
        self.Nv = [self.N(st) for st in self.states]

    def _successors(self, s):
        out = []
        nc = len(self.comps)
        t = [None] * nc
        for k in range(5):
            if not all(f(s, k, None) for f in self.pre):
                continue

            def rec(d):
                if d == nc:
                    out.append((k, self.index[tuple(t)]))
                    return
                for val in self.doms[d]:
                    t[d] = val
                    if all(f(s, k, t) for f in self.bucket[d]):
                        rec(d + 1)
                t[d] = None
            rec(0)
        return out

    # ---------- helpers ----------
    def fmt(self, i):
        st = self.states[i]
        out = {}
        for c, v in zip(self.comps, st):
            if c == 'p':
                out[c] = PV[v]
            elif c == 's':
                out[c] = SV[v]
            elif c == 'e':
                out[c] = self.inst.elems[v]
            elif c in ('u', 'b'):
                out[c] = self.inst.mask_names(v)
            elif c == 'm':
                out[c] = MV[v]
            else:
                out[c] = v
        order = ['p', 's', 'e', 'g', 'u'] + [c for c in self.comps if c not in ('p', 's', 'e', 'g', 'u')]
        return {c: out[c] for c in order}

    def get(self, i, c):
        return self.states[i][self.I[c]]

    def e_in_u(self, i):
        st = self.states[i]
        return in_u(st[self.I['e']], st[self.I['u']])

    def witness(self, path_states, path_kinds):
        return {'length': len(path_kinds),
                'states': [self.fmt(i) for i in path_states],
                'kinds': [KINDS[k] for k in path_kinds]}

    def bfs(self, starts, kinds, goal, node_ok=None, flagf=None, flag0=0):
        """Multi-source BFS over (state, flag); returns shortest witness to a goal node or None."""
        parent = {}
        q = deque()
        for st in starts:
            if node_ok is not None and not node_ok(st):
                continue
            nd = (st, flag0)
            if nd not in parent:
                parent[nd] = None
                q.append(nd)
        while q:
            nd = q.popleft()
            st, fl = nd
            if goal(st, fl):
                ps, ks = [], []
                cur = nd
                while parent[cur] is not None:
                    prev, k = parent[cur]
                    ps.append(cur[0])
                    ks.append(k)
                    cur = prev
                ps.append(cur[0])
                return self.witness(ps[::-1], ks[::-1])
            for k, j in self.succ[st]:
                if k not in kinds:
                    continue
                if node_ok is not None and not node_ok(j):
                    continue
                nf = flagf(fl, k) if flagf else fl
                nn = (j, nf)
                if nn not in parent:
                    parent[nn] = (nd, k)
                    q.append(nn)
        return None

    def reach(self, starts, kinds=ALL_KINDS):
        parent, dist, order = {}, {}, []
        q = deque()
        for st in starts:
            if st not in parent:
                parent[st] = None
                dist[st] = 0
                q.append(st)
        while q:
            st = q.popleft()
            order.append(st)
            for k, j in self.succ[st]:
                if k in kinds and j not in parent:
                    parent[j] = (st, k)
                    dist[j] = dist[st] + 1
                    q.append(j)
        return parent, dist, order

    def path_to(self, parent, st, extra_step=None):
        ps, ks = [st], []
        cur = st
        while parent[cur] is not None:
            prev, k = parent[cur]
            ks.append(k)
            ps.append(prev)
            cur = prev
        ps, ks = ps[::-1], ks[::-1]
        if extra_step is not None:
            ks.append(extra_step[0])
            ps.append(extra_step[1])
        return self.witness(ps, ks)


def res(relevant_exists, witness, value_if_no_witness=True):
    if not relevant_exists:
        return {'value': True, 'class': 'VACUOUS', 'witness': None}
    if witness is not None:
        return {'value': False, 'class': 'FAILS', 'witness': witness}
    return {'value': value_if_no_witness, 'class': 'HOLDS', 'witness': None}


def evaluate(sy):
    inst = sy.inst
    bot = inst.bot
    n = len(sy.states)
    NV = sy.Nv
    E = sy.I['e']
    allst = range(n)
    fresh = [i for i in allst if sy.is_fresh(i)]
    fresh_starts = [i for i in fresh if not sy.e_in_u(i) and sy.get(i, 'g') == 0 and not NV[i]]
    parent, dist, order = sy.reach(fresh)
    reach_N = [i for i in order if NV[i]]
    out = {'state_count': n, 'fresh_state_count': len(fresh), 'fresh_start_count': len(fresh_starts),
           'reachable_states': len(order), 'reachable_N_states': len(reach_N)}
    P = {}

    # D1
    starts = [i for i in allst if not sy.e_in_u(i)]
    w = sy.bfs(starts, {GOV}, lambda st, fl: sy.e_in_u(st))
    P['D1'] = res(bool(starts), w)
    # D2
    starts = [i for i in allst if sy.get(i, 'g') == 0]
    w = sy.bfs(starts, {EVID, EVIDREF, WORK}, lambda st, fl: sy.get(st, 'g') == 1)
    P['D2'] = res(bool(starts), w)
    # D6
    w = None
    for i in allst:
        for k, j in sy.succ[i]:
            if sy.get(j, 'p') != sy.get(i, 'p'):
                w = sy.witness([i, j], [k])
                break
        if w:
            break
    P['D6'] = res(True, w)

    # D3-history / D3+
    def d3(notion, ev_kinds):
        rp, _, rord = sy.reach(fresh_starts)
        relevant = any(notion(sy.states[i]) for i in rord)
        flagf = lambda fl, k: fl | (1 if k in ev_kinds else 0) | (2 if k == GOV else 0)
        w = sy.bfs(fresh_starts, ALL_KINDS, lambda st, fl: notion(sy.states[st]) and fl != 3,
                   flagf=flagf)
        return res(relevant, w)
    P['D3-history[N]'] = d3(sy.N, (EVID, EVIDREF))
    P['D3-history[PS]'] = d3(sy.PS, (EVID, EVIDREF))
    P['D3+[N]'] = d3(sy.N, (EVID,))
    # D5
    rel, w = False, None
    for i in allst:
        if not NV[i]:
            continue
        for k, j in sy.succ[i]:
            if k == EVID:
                rel = True
                if not NV[j]:
                    w = sy.witness([i, j], [k])
                    break
        if w:
            break
    P['D5[N]'] = res(rel, w)
    # NV
    w = sy.bfs(fresh_starts, ALL_KINDS, lambda st, fl: NV[st])
    P['NV[N]'] = {'value': w is not None, 'class': 'HOLDS' if w is not None else 'FAILS',
                  'witness': w}

    # reachable PromotionEvents, in BFS order (shortest prefix first)
    events = []
    for i in order:
        if NV[i]:
            continue
        for k, j in sy.succ[i]:
            if k == GOV and NV[j]:
                events.append((i, j))

    def first_bad_event(relevant, bad):
        rel = False
        for i, j in events:
            if relevant(j):
                rel = True
                if bad(j):
                    return rel, sy.path_to(parent, i, (GOV, j))
        return rel, None
    rel, w = first_bad_event(lambda j: True, lambda j: not sy.e_in_u(j))
    P['D3-state-bar-event'] = res(rel, w)
    rel, w = first_bad_event(lambda j: not sy.e_in_u(j), lambda j: bot is not None and sy.get(j, 'e') == bot)
    P['D3-state-floor-event'] = res(rel, w)

    def first_bad_state(relevant, bad):
        rel = False
        for i in order:
            if relevant(i):
                rel = True
                if bad(i):
                    return rel, sy.path_to(parent, i)
        return rel, None
    rel, w = first_bad_state(lambda i: NV[i], lambda i: not sy.e_in_u(i))
    P['D3-state-bar-inv'] = res(rel, w)
    rel, w = first_bad_state(lambda i: NV[i] and not sy.e_in_u(i),
                             lambda i: bot is not None and sy.get(i, 'e') == bot)
    P['D3-state-floor-inv'] = res(rel, w)
    # P-GUARD
    starts = [i for i in fresh_starts if bot is not None and sy.get(i, 'e') == bot]
    w = sy.bfs(starts, ALL_KINDS - {EVID}, lambda st, fl: NV[st])
    P['P-GUARD'] = res(bool(starts), w)
    # P-BAR
    rec = sy.record
    rel, w = first_bad_state(lambda i: NV[i] and not sy.e_in_u(i),
                             lambda i: rec is None or not rec(sy.states[i]))
    if rec is None:
        P['P-BAR'] = res(True, w)  # models without records: holds iff no such state reachable
    else:
        P['P-BAR'] = res(rel, w)
    # P-PERSIST
    rel, w = False, None
    for i in order:
        if not NV[i]:
            continue
        for k, j in sy.succ[i]:
            if k == EVIDREF:
                rel = True
                if not NV[j]:
                    w = sy.path_to(parent, i, (k, j))
                    break
        if w:
            break
    P['P-PERSIST'] = res(rel, w)
    out['properties'] = P
    return out


def scenarios(model, inst, full_axioms):
    mname = model[0]
    sy = System(model, inst, full_axioms)
    u0 = inst.mask_of(['n2'])
    U = sy.I['u']
    has_b = 'b' in sy.I

    def starts_for(s, e_name):
        e = inst.elems.index(e_name)
        return [i for i, st in enumerate(s.states)
                if st[s.I['e']] == e and st[s.I['g']] == 0 and st[s.I['u']] == u0 and s.is_fresh(i)]

    def u_const(s):
        return lambda i: s.states[i][s.I['u']] == u0

    def ub_const(s):
        if 'b' in s.I:
            return lambda i: s.states[i][s.I['u']] == u0 and s.states[i][s.I['b']] == u0
        return u_const(s)

    def q(s, st, kinds, goal, ok, flagf=None):
        w = s.bfs(st, kinds, goal, node_ok=ok, flagf=flagf)
        return {'reachable': w is not None, 'witness': w}
    GW = frozenset({GOV, WORK})
    R = {}
    R['S0'] = q(sy, starts_for(sy, 'n2'), ALL_KINDS, lambda i, f: sy.Nv[i], u_const(sy))
    rec = sy.record
    base_ok = ub_const(sy)
    ok1 = (lambda i: base_ok(i) and not rec(sy.states[i])) if rec else base_ok
    R['S1'] = q(sy, starts_for(sy, 'n1'), GW, lambda i, f: sy.Nv[i], ok1)
    gflag = lambda fl, k: fl | (1 if k == GOV else 0)
    R['S2'] = {'N': q(sy, starts_for(sy, 'n1'), GW, lambda i, f: sy.Nv[i] and f == 1, base_ok, gflag),
               'PS': q(sy, starts_for(sy, 'n1'), GW, lambda i, f: sy.PS(sy.states[i]) and f == 1,
                       base_ok, gflag)}
    R['S3'] = q(sy, starts_for(sy, 'n0'), GW, lambda i, f: sy.Nv[i], base_ok)
    # S4
    s4 = {}
    keys = [('u_changes', 'u')] + ([('b_changes', 'b')] if has_b else [])
    for key, c in keys:
        w = None
        C = sy.I[c]
        for i, st in enumerate(sy.states):
            for k, j in sy.succ[i]:
                if sy.states[j][C] != st[C]:
                    w = sy.witness([i, j], [k])
                    break
            if w:
                break
        s4[key] = {'answer': w is not None, 'witness': w}
    R['S4'] = s4
    # S4-T
    if mname == 'M0':
        st_sys = System(model, inst, set(full_axioms) - {'A6'})
        R['S4-T'] = q(st_sys, starts_for(st_sys, 'n1'), GW, lambda i, f: st_sys.Nv[i], None)
        R['S4-T']['setting'] = 'A6 removed, u free'
    elif mname == 'M0b':
        st_sys = System(model, inst, set(full_axioms) - {'B6'})
        R['S4-T'] = q(st_sys, starts_for(st_sys, 'n1'), GW, lambda i, f: st_sys.Nv[i], u_const(st_sys))
        R['S4-T']['setting'] = 'B6 removed, b free, u constant'
    else:
        R['S4-T'] = 'NOT_RUN'
    # S5
    if mname == 'M1':
        V = sy.I['v']
        R['S5'] = q(sy, starts_for(sy, 'n1'), GW, lambda i, f: sy.Nv[i] and sy.states[i][V] == 1,
                    u_const(sy))
    else:
        R['S5'] = 'NOT_REPRESENTABLE'
    # S6
    R['S6'] = q(sy, starts_for(sy, 'n2'), ALL_KINDS,
                lambda i, f: sy.Nv[i] and not sy.e_in_u(i), u_const(sy))
    return R


def run_model_instance(model, inst):
    mname, extras, added = model
    if mname in NEEDS_BOTTOM and inst.bot is None:
        return {'applicable': False, 'status': 'NOT_APPLICABLE',
                'properties': {p: {'value': None, 'class': 'NOT_APPLICABLE', 'witness': None}
                               for p in PROPS}}
    full = set(BASE_AXIOMS) | set(added)
    full_eval = evaluate(System(model, inst, full))
    out = {'applicable': True, 'full_axiom_set': BASE_AXIOMS + added}
    out.update(full_eval)
    if inst.name == 'chain3':
        out['scenarios'] = scenarios(model, inst, full)
    # ablation
    abl = {}
    for ax in BASE_AXIOMS + added:
        ev = evaluate(System(model, inst, full - {ax}))
        abl[ax] = {'state_count': ev['state_count'], 'reachable_states': ev['reachable_states'],
                   'reachable_N_states': ev['reachable_N_states'],
                   'properties': {p: ev['properties'][p]['class'] for p in PROPS}}
    out['ablation'] = abl
    # minimal sets over subsets of added axioms (base kept)
    holds = {}
    for r in range(len(added) + 1):
        for sub in itertools.combinations(added, r):
            if set(sub) == set(added):
                ev = full_eval
            else:
                ev = evaluate(System(model, inst, set(BASE_AXIOMS) | set(sub)))
            holds[frozenset(sub)] = {p: ev['properties'][p]['value'] for p in PROPS}
    minimal = {}
    for p in PROPS:
        if not full_eval['properties'][p]['value']:
            continue
        good = [s for s in holds if holds[s][p]]
        mins = [s for s in good if not any(o < s for o in good)]
        minimal[p] = sorted([sorted(s, key=added.index) for s in mins], key=lambda l: (len(l), [added.index(a) for a in l]))
    out['minimal_sets'] = minimal
    used = set()
    for p, ms in minimal.items():
        for s in ms:
            used |= set(s)
    out['redundant_added_axioms'] = [a for a in added if a not in used]
    return out


def main():
    insts = [Instance(*t) for t in INSTANCES]
    results = {
        'spec': 'SPEC-G2-r2',
        'kinds': list(KINDS),
        'base_axioms': BASE_AXIOMS,
        'property_ids': PROPS,
        'instances': {i.name: {'elements': i.elems,
                               'bottom': i.elems[i.bot] if i.bot is not None else None,
                               'admissible_bars': [i.mask_names(m) for m in i.upsets]}
                      for i in insts},
        'models': {},
    }
    for model in MODELS:
        results['models'][model[0]] = {}
        for inst in insts:
            print('running', model[0], inst.name, file=sys.stderr, flush=True)
            results['models'][model[0]][inst.name] = run_model_instance(model, inst)
    path = sys.argv[1] if len(sys.argv) > 1 else 'results.json'
    with open(path, 'w') as f:
        json.dump(results, f, indent=1, sort_keys=False)
        f.write('\n')


if __name__ == '__main__':
    main()
