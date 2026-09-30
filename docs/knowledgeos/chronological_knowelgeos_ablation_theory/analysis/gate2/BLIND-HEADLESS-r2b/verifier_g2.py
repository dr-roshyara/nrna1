#!/usr/bin/env python3
"""Independent exhaustive verifier for SPEC-G2-r2.md (Python 3, standard library only).

Every model/instance/axiom-set is compiled into an explicit finite labelled
transition graph (all states of the admissible state space, all steps that
satisfy the axiom set), and every property is decided by exhaustive
enumeration / breadth-first search on that graph.  BFS gives shortest witnesses.
"""
import itertools
import json
import sys
from collections import deque

P = ('gen', 'der')
S = ('auth', 'prov', 'hist')
KINDS = ('GOV', 'EVID', 'EVIDREF', 'WORK', 'COMP')
ALLK = frozenset(KINDS)
BASE_AXIOMS = ['A0', 'A1', 'A2e', 'A3g', 'A3s', 'A3m', 'A4', 'A5e', 'A5g', 'A5s', 'A6']
PROPS = ['D1', 'D2', 'D6', 'D3-history[N]', 'D3-history[PS]', 'D3+[N]', 'D5[N]', 'NV[N]',
         'D3-state-bar-event', 'D3-state-bar-inv', 'D3-state-floor-event',
         'D3-state-floor-inv', 'P-GUARD', 'P-BAR', 'P-PERSIST']

# state tuple layout: (p, s, e, g, u, a0, a1)   p,s,e indices; u bitmask over E


# ---------------------------------------------------------------- instances
class Inst:
    def __init__(self, name, elems, pairs):
        self.name = name
        self.elems = list(elems)
        n = len(elems)
        self.n = n
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
        self.allmasks = list(range(1 << n))
        self.upsets = [m for m in self.allmasks
                       if all(not (m >> i & 1) or (m >> j & 1)
                              for i in range(n) for j in range(n) if leq[i][j])]

    def idx(self, name):
        return self.elems.index(name)


INSTANCES = [
    Inst('chain3', ['n0', 'n1', 'n2'], [('n0', 'n1'), ('n1', 'n2')]),
    Inst('V', ['bot', 'a', 'b'], [('bot', 'a'), ('bot', 'b')]),
    Inst('diamond', ['bot', 'a', 'b', 'top'],
         [('bot', 'a'), ('a', 'top'), ('bot', 'b'), ('b', 'top')]),
    Inst('antichain2', ['a', 'b'], []),
]


def inu(e, u):
    return (u >> e) & 1 == 1


def PS(I, x):
    return inu(x[2], x[4]) and x[3] == 1


# ---------------------------------------------------------------- models
# Added-axiom predicates: f(I, x, k, y); they only read y's added components
# (indices >= 5) and the source x.  (Cross-checked against brute force.)
def imp(a, b):
    return (not a) or b


def not_bot(I, e):
    return I.bot is None or e != I.bot


MODELS = {}

MODELS['M0'] = dict(
    added=[], init=lambda x: (),
    N=lambda I, x: PS(I, x),
    record=None, axioms={}, needs_bot=False)

MODELS['M0b'] = dict(
    added=[('b', 'BARS')], init=lambda x: (x[4],),
    N=lambda I, x: inu(x[2], x[5]) and x[3] == 1,
    record=None,
    axioms={'B6': lambda I, x, k, y: y[5] == x[5]}, needs_bot=False)

MODELS['M1'] = dict(
    added=[('x', (0, 1)), ('v', (0, 1))], init=lambda x: (0, 0),
    N=lambda I, x: PS(I, x) or (x[5] == 1 and x[3] == 1),
    record=lambda x: x[5] == 1,
    axioms={
        'X1': lambda I, x, k, y: imp(y[5] != x[5] or y[6] != x[6], k == 'GOV'),
        'X2': lambda I, x, k, y: imp(x[5] == 0 and y[5] == 1,
                                     not_bot(I, x[2]) and not inu(x[2], x[4])),
        'X3': lambda I, x, k, y: imp(x[5] == 0 and y[5] == 1, y[6] == 1),
        'X4': lambda I, x, k, y: y[5] >= x[5] and y[6] >= x[6],
    }, needs_bot=True)

MODELS['M2'] = dict(
    added=[('m', ('NONE', 'RULE', 'EXC')), ('ad', (0, 1))], init=lambda x: ('NONE', 0),
    N=lambda I, x: x[6] == 1,
    record=lambda x: x[5] == 'EXC',
    axioms={
        'Y1': lambda I, x, k, y: imp(y[5] != x[5] or y[6] != x[6], k == 'GOV'),
        'Y2': lambda I, x, k, y: imp(x[5] == 'NONE' and y[5] == 'EXC',
                                     not_bot(I, x[2]) and not inu(x[2], x[4])),
        'Y2b': lambda I, x, k, y: imp(x[5] == 'NONE' and y[5] == 'RULE', inu(x[2], x[4])),
        'Y3': lambda I, x, k, y: imp(x[6] == 0 and y[6] == 1,
                                     (y[5] == 'RULE' and inu(x[2], x[4])) or y[5] == 'EXC'),
        'Y4': lambda I, x, k, y: y[6] >= x[6] and imp(x[5] != 'NONE', y[5] == x[5]),
    }, needs_bot=True)

_W = {
    'W1': lambda I, x, k, y: imp(y[5] != x[5] or y[6] != x[6], k == 'GOV'),
    'W3': lambda I, x, k, y: imp(x[6] == 0 and y[6] == 1, y[5] == 1),
    'W4': lambda I, x, k, y: y[5] >= x[5] and y[6] >= x[6],
}
MODELS['M3a'] = dict(
    added=[('au', (0, 1)), ('ad', (0, 1))], init=lambda x: (0, 0),
    N=lambda I, x: x[6] == 1, record=None, axioms=dict(_W), needs_bot=False)
_W2 = dict(_W)
_W2['W2'] = lambda I, x, k, y: imp(x[5] == 0 and y[5] == 1,
                                   inu(x[2], x[4]) or not_bot(I, x[2]))
MODELS['M3b'] = dict(
    added=[('au', (0, 1)), ('ad', (0, 1))], init=lambda x: (0, 0),
    N=lambda I, x: x[6] == 1, record=None,
    axioms={k: _W2[k] for k in ('W1', 'W2', 'W3', 'W4')}, needs_bot=True)

MODEL_ORDER = ['M0', 'M0b', 'M1', 'M2', 'M3a', 'M3b']


# ---------------------------------------------------------------- base axioms
def base_ok(I, ax, x, k, y):
    """Full base-axiom predicate for one step (A0 is the state-space condition)."""
    EV = k in ('EVID', 'EVIDREF')
    if 'A1' in ax and y[0] != x[0]: return False
    if 'A2e' in ax and k == 'GOV' and y[2] != x[2]: return False
    if 'A3g' in ax and EV and y[3] != x[3]: return False
    if 'A3s' in ax and EV and y[1] != x[1]: return False
    if 'A3m' in ax:
        if k == 'EVID' and not I.leq[x[2]][y[2]]: return False
        if k == 'EVIDREF' and not I.leq[y[2]][x[2]]: return False
    if 'A4' in ax and k == 'COMP': return False
    if 'A5e' in ax and k == 'WORK' and y[2] != x[2]: return False
    if 'A5g' in ax and k == 'WORK' and y[3] != x[3]: return False
    if 'A5s' in ax and k == 'WORK' and y[1] != x[1]: return False
    if 'A6' in ax and y[4] != x[4]: return False
    return True


# ---------------------------------------------------------------- graph
class Graph:
    def __init__(self, mname, I, ax):
        M = MODELS[mname]
        self.M, self.I, self.ax, self.mname = M, I, frozenset(ax), mname
        bars = I.upsets if 'A0' in ax else I.allmasks
        self.bars = bars
        adoms = [bars if d == 'BARS' else d for _, d in M['added']]
        self.adoms = adoms
        states = list(itertools.product(range(2), range(3), range(I.n), (0, 1), bars, *adoms))
        self.states = states
        self.index = {st: i for i, st in enumerate(states)}
        self.Nv = [M['N'](I, x) for x in states]
        self.PSv = [PS(I, x) for x in states]
        added_ax = [f for name, f in M['axioms'].items() if name in ax]
        addprod = list(itertools.product(*adoms))
        succ = {k: [] for k in KINDS}
        idx = self.index
        for x in states:
            p, s, e, g, u = x[:5]
            for k in KINDS:
                out = []
                succ[k].append(out)
                if 'A4' in ax and k == 'COMP':
                    continue
                EV = k in ('EVID', 'EVIDREF')
                ps = (p,) if 'A1' in ax else range(2)
                ss = (s,) if (('A3s' in ax and EV) or ('A5s' in ax and k == 'WORK')) else range(3)
                es = list(range(I.n))
                if 'A2e' in ax and k == 'GOV': es = [e]
                if 'A5e' in ax and k == 'WORK': es = [e]
                if 'A3m' in ax and k == 'EVID': es = [f for f in es if I.leq[e][f]]
                if 'A3m' in ax and k == 'EVIDREF': es = [f for f in es if I.leq[f][e]]
                gs = (g,) if (('A3g' in ax and EV) or ('A5g' in ax and k == 'WORK')) else (0, 1)
                us = (u,) if 'A6' in ax else bars
                # added part: predicates read only source x and y[5:]
                adds = []
                for a in addprod:
                    yy = x[:5] + a
                    if all(f(I, x, k, yy) for f in added_ax):
                        adds.append(a)
                for y5 in itertools.product(ps, ss, es, gs, us):
                    for a in adds:
                        out.append(idx[y5 + a])
        self.succ = succ
        self.fresh = [i for i, x in enumerate(states) if tuple(x[5:]) == M['init'](x)]

    def naive_edges(self):
        """Brute force: every (x,k,y) pair checked with the full predicate."""
        M, I, ax = self.M, self.I, self.ax
        added_ax = [f for name, f in M['axioms'].items() if name in ax]
        res = {k: [] for k in KINDS}
        for x in self.states:
            for k in KINDS:
                res[k].append([j for j, y in enumerate(self.states)
                               if base_ok(I, ax, x, k, y) and all(f(I, x, k, y) for f in added_ax)])
        return res

    # ---- rendering
    def render(self, i):
        x = self.states[i]
        I = self.I
        d = {'p': P[x[0]], 's': S[x[1]], 'e': I.elems[x[2]], 'g': x[3],
             'u': [I.elems[j] for j in range(I.n) if inu(j, x[4])]}
        for (name, dom), val in zip(self.M['added'], x[5:]):
            d[name] = [I.elems[j] for j in range(I.n) if inu(j, val)] if dom == 'BARS' else val
        return d

    def path(self, nodes, kinds):
        return {'length': len(kinds), 'states': [self.render(i) for i in nodes], 'kinds': list(kinds)}

    # ---- search
    def bfs(self, sources, kinds, target, allowed=None):
        """Multi-source BFS; returns (node_list, kind_list) of a shortest path to a target, or None."""
        kl = [k for k in KINDS if k in kinds]
        par = {}
        dq = deque()
        for s in sources:
            if allowed is not None and not allowed(s):
                continue
            if s not in par:
                par[s] = None
                if target(s):
                    return [s], []
                dq.append(s)
        while dq:
            i = dq.popleft()
            for k in kl:
                for j in self.succ[k][i]:
                    if j in par or (allowed is not None and not allowed(j)):
                        continue
                    par[j] = (i, k)
                    if target(j):
                        return self._unwind(par, j)
                    dq.append(j)
        return None

    @staticmethod
    def _unwind(par, j):
        nodes, ks = [j], []
        while par[j] is not None:
            i, k = par[j]
            nodes.append(i)
            ks.append(k)
            j = i
        return nodes[::-1], ks[::-1]

    def reach(self):
        par = {}
        order = []
        dq = deque()
        for s in self.fresh:
            par[s] = None
            order.append(s)
            dq.append(s)
        while dq:
            i = dq.popleft()
            for k in KINDS:
                for j in self.succ[k][i]:
                    if j not in par:
                        par[j] = (i, k)
                        order.append(j)
                        dq.append(j)
        return par, order


# ---------------------------------------------------------------- properties
def res(cls, witness=None, note=None):
    d = {'class': cls, 'holds': cls in ('HOLDS', 'VACUOUS') if cls != 'NOT_APPLICABLE' else None}
    if witness is not None:
        d['witness'] = witness
    if note:
        d['note'] = note
    return d


def evaluate(G, want_witness=True):
    I, M = G.I, G.M
    st = G.states
    n = len(st)
    N, PSv = G.Nv, G.PSv
    e_in_u = [inu(x[2], x[4]) for x in st]
    is_bot = [I.bot is not None and x[2] == I.bot for x in st]
    rec = M['record']
    par, order = G.reach()
    reach_set = par
    out = {}
    W = (lambda nodes, ks: G.path(nodes, ks)) if want_witness else (lambda nodes, ks: None)

    def path_to(i, extra=None):
        nodes, ks = Graph._unwind(par, i)
        if extra:
            nodes = nodes + [extra[1]]
            ks = ks + [extra[0]]
        return W(nodes, ks)

    fresh_starts = [i for i in G.fresh if not e_in_u[i] and st[i][3] == 0 and not N[i]]

    # D1
    srcs = [i for i in range(n) if not e_in_u[i]]
    r = G.bfs(srcs, {'GOV'}, lambda j: e_in_u[j])
    out['D1'] = res('VACUOUS') if not srcs else (res('HOLDS') if r is None else res('FAILS', W(*r)))
    # D2
    srcs = [i for i in range(n) if st[i][3] == 0]
    r = G.bfs(srcs, {'EVID', 'EVIDREF', 'WORK'}, lambda j: st[j][3] == 1)
    out['D2'] = res('VACUOUS') if not srcs else (res('HOLDS') if r is None else res('FAILS', W(*r)))
    # D6
    wit, anystep = None, False
    for i in range(n):
        for k in KINDS:
            for j in G.succ[k][i]:
                anystep = True
                if st[j][0] != st[i][0]:
                    wit = ([i, j], [k]); break
            if wit: break
        if wit: break
    out['D6'] = res('VACUOUS') if not anystep else (res('HOLDS') if wit is None else res('FAILS', W(*wit)))

    # D3-history / D3+
    def d3(target, first_missing):
        if not fresh_starts or G.bfs(fresh_starts, ALLK, target) is None:
            return res('VACUOUS')
        best = None
        for excl in (first_missing, {'GOV'}):
            r = G.bfs(fresh_starts, ALLK - excl, target)
            if r is not None and (best is None or len(r[1]) < len(best[1])):
                best = r
        return res('HOLDS') if best is None else res('FAILS', W(*best))
    out['D3-history[N]'] = d3(lambda j: N[j], {'EVID', 'EVIDREF'})
    out['D3-history[PS]'] = d3(lambda j: PSv[j], {'EVID', 'EVIDREF'})
    out['D3+[N]'] = d3(lambda j: N[j], {'EVID'})

    # D5
    rel, wit = False, None
    for i in range(n):
        if N[i]:
            for j in G.succ['EVID'][i]:
                rel = True
                if not N[j]:
                    wit = ([i, j], ['EVID']); break
            if wit: break
    out['D5[N]'] = res('VACUOUS') if not rel else (res('HOLDS') if wit is None else res('FAILS', W(*wit)))

    # NV
    r = G.bfs(fresh_starts, ALLK, lambda j: N[j]) if fresh_starts else None
    out['NV[N]'] = res('FAILS') if r is None else dict(res('HOLDS'), example=W(*r))
    if not fresh_starts:
        out['NV[N]']['note'] = 'no fresh start exists'

    # reachable promotion events, in order of (distance of source, discovery)
    pe_rel = pe_wit = fl_rel = fl_wit = None
    for i in order:
        if N[i]:
            continue
        for j in G.succ['GOV'][i]:
            if N[j]:
                pe_rel = True
                if pe_wit is None and not e_in_u[j]:
                    pe_wit = (i, j)
                if not e_in_u[j]:
                    fl_rel = True
                    if fl_wit is None and is_bot[j]:
                        fl_wit = (i, j)
    out['D3-state-bar-event'] = res('VACUOUS') if not pe_rel else (
        res('HOLDS') if pe_wit is None else res('FAILS', path_to(pe_wit[0], ('GOV', pe_wit[1]))))
    out['D3-state-floor-event'] = res('VACUOUS') if not fl_rel else (
        res('HOLDS') if fl_wit is None else res('FAILS', path_to(fl_wit[0], ('GOV', fl_wit[1]))))

    # invariants over reachable N-states (order = BFS order => shortest)
    nrel = [i for i in order if N[i]]
    bad = [i for i in nrel if not e_in_u[i]]
    out['D3-state-bar-inv'] = res('VACUOUS') if not nrel else (
        res('HOLDS') if not bad else res('FAILS', path_to(bad[0])))
    badf = [i for i in bad if is_bot[i]]
    out['D3-state-floor-inv'] = res('VACUOUS') if not bad else (
        res('HOLDS') if not badf else res('FAILS', path_to(badf[0])))

    # P-GUARD
    srcs = [i for i in fresh_starts if is_bot[i]]
    r = G.bfs(srcs, ALLK - {'EVID'}, lambda j: N[j]) if srcs else None
    out['P-GUARD'] = res('VACUOUS') if not srcs else (res('HOLDS') if r is None else res('FAILS', W(*r)))

    # P-BAR
    if rec is None:
        out['P-BAR'] = res('HOLDS', note='model has no exception record; no reachable N and e not in u state') \
            if not bad else res('FAILS', path_to(bad[0]))
    else:
        badr = [i for i in bad if not rec(st[i])]
        out['P-BAR'] = res('VACUOUS') if not bad else (
            res('HOLDS') if not badr else res('FAILS', path_to(badr[0])))

    # P-PERSIST
    rel, wit = False, None
    for i in nrel:
        for j in G.succ['EVIDREF'][i]:
            rel = True
            if not N[j]:
                wit = (i, j); break
        if wit: break
    out['P-PERSIST'] = res('VACUOUS') if not rel else (
        res('HOLDS') if wit is None else res('FAILS', path_to(wit[0], ('EVIDREF', wit[1]))))

    counts = {'state_count': n, 'reachable_states': len(order),
              'reachable_N_states': len(nrel)}
    return counts, out


# ---------------------------------------------------------------- scenarios (chain3)
def scenarios(mname, I, full_ax, G):
    M = MODELS[mname]
    st = G.states
    N = G.Nv
    u0 = 1 << I.idx('n2')

    def starts(Gx, ename):
        e = I.idx(ename)
        return [i for i in Gx.fresh
                if Gx.states[i][2] == e and Gx.states[i][3] == 0 and Gx.states[i][4] == u0]

    def ans(r, Gx):
        if r is None:
            return {'answer': False}
        return {'answer': True, 'witness': Gx.path(*r)}

    GW = {'GOV', 'WORK'}
    out = {}
    out['S0'] = ans(G.bfs(starts(G, 'n2'), ALLK, lambda j: N[j]), G)

    if mname == 'M1':
        allowed = lambda j: st[j][5] == 0
    elif mname == 'M2':
        allowed = lambda j: st[j][5] != 'EXC'
    else:
        allowed = None
    out['S1'] = ans(G.bfs(starts(G, 'n1'), GW, lambda j: N[j], allowed), G)

    # S2: augmented BFS node (state, gov_seen)
    def s2(target):
        src = starts(G, 'n1')
        par, dq = {}, deque()
        for s in src:
            node = (s, 0)
            if node not in par:
                par[node] = None
                dq.append(node)
        while dq:
            node = dq.popleft()
            i, f = node
            for k in ('GOV', 'WORK'):
                for j in G.succ[k][i]:
                    nn = (j, 1 if (f or k == 'GOV') else 0)
                    if nn in par:
                        continue
                    par[nn] = (node, k)
                    if nn[1] == 1 and target(j):
                        nodes, ks = [nn], []
                        while par[nn] is not None:
                            pn, kk = par[nn]
                            nodes.append(pn); ks.append(kk); nn = pn
                        return [x[0] for x in nodes[::-1]], ks[::-1]
                    dq.append(nn)
        return None
    out['S2'] = {'N': ans(s2(lambda j: N[j]), G), 'PS': ans(s2(lambda j: G.PSv[j]), G)}
    out['S3'] = ans(G.bfs(starts(G, 'n0'), GW, lambda j: N[j]), G)

    # S4
    wit_u = wit_b = None
    for i in range(len(st)):
        for k in KINDS:
            for j in G.succ[k][i]:
                if wit_u is None and st[j][4] != st[i][4]:
                    wit_u = G.path([i, j], [k])
                if mname == 'M0b' and wit_b is None and st[j][5] != st[i][5]:
                    wit_b = G.path([i, j], [k])
    s4 = {'u_changes': wit_u is not None}
    if wit_u: s4['u_witness'] = wit_u
    if mname == 'M0b':
        s4['b_changes'] = wit_b is not None
        if wit_b: s4['b_witness'] = wit_b
    out['S4'] = s4

    # S4-T
    if mname == 'M0':
        GT = Graph(mname, I, full_ax - {'A6'})
        out['S4-T'] = dict(ans(GT.bfs(starts(GT, 'n1'), GW, lambda j: GT.Nv[j]), GT),
                           axiom_set='full minus A6')
    elif mname == 'M0b':
        GT = Graph(mname, I, full_ax - {'B6'})
        out['S4-T'] = dict(ans(GT.bfs(starts(GT, 'n1'), GW, lambda j: GT.Nv[j]), GT),
                           axiom_set='full minus B6')
    else:
        out['S4-T'] = 'NOT_RUN'

    if mname == 'M1':
        out['S5'] = ans(G.bfs(starts(G, 'n1'), GW, lambda j: N[j] and st[j][6] == 1), G)
    else:
        out['S5'] = 'NOT_REPRESENTABLE'
    out['S6'] = ans(G.bfs(starts(G, 'n2'), ALLK,
                          lambda j: N[j] and not inu(st[j][2], st[j][4])), G)
    return out


# ---------------------------------------------------------------- driver
def selftest():
    report = []
    I = INSTANCES[0]
    cases = [(m, set(BASE_AXIOMS) | set(MODELS[m]['axioms'])) for m in MODEL_ORDER]
    for a in BASE_AXIOMS:
        cases.append(('M0', set(BASE_AXIOMS) - {a}))
    for a in list(MODELS['M1']['axioms']):
        cases.append(('M1', (set(BASE_AXIOMS) | set(MODELS['M1']['axioms'])) - {a}))
    for m, ax in cases:
        G = Graph(m, I, ax)
        nv = G.naive_edges()
        ok = all(sorted(G.succ[k][i]) == nv[k][i] for k in KINDS for i in range(len(G.states)))
        report.append({'model': m, 'instance': I.name, 'axioms_removed':
                       sorted((set(BASE_AXIOMS) | set(MODELS[m]['axioms'])) - ax), 'match': ok})
        if not ok:
            raise SystemExit('selftest mismatch %s %s' % (m, ax))
    return report


def main():
    results = {'spec': 'SPEC-G2-r2', 'generator': 'verifier_g2.py',
               'kinds': list(KINDS), 'properties': PROPS, 'models': {}}
    results['selftest_successor_generation_vs_bruteforce'] = selftest()
    for mname in MODEL_ORDER:
        M = MODELS[mname]
        added = list(M['axioms'])
        results['models'][mname] = {}
        for I in INSTANCES:
            if M['needs_bot'] and I.bot is None:
                results['models'][mname][I.name] = {
                    'status': 'NOT_APPLICABLE',
                    'properties': {p: res('NOT_APPLICABLE') for p in PROPS}}
                continue
            full = frozenset(BASE_AXIOMS) | frozenset(added)
            G = Graph(mname, I, full)
            counts, props = evaluate(G)
            entry = {'status': 'RUN', 'bottom': I.elems[I.bot] if I.bot is not None else None,
                     'full_axiom_set': BASE_AXIOMS + added}
            entry.update(counts)
            entry['properties'] = props
            if I.name == 'chain3':
                entry['scenarios'] = scenarios(mname, I, full, G)
            # ablation
            abl = {}
            for a in BASE_AXIOMS + added:
                Ga = Graph(mname, I, full - {a})
                c, pr = evaluate(Ga)
                abl[a] = dict(c, properties=pr)
                print(mname, I.name, 'ablate', a, file=sys.stderr)
            entry['ablation'] = abl
            # subsets of added axioms (base kept)
            truth = {}
            for r in range(len(added) + 1):
                for sub in itertools.combinations(added, r):
                    if set(sub) == set(added):
                        pr = props
                    else:
                        _, pr = evaluate(Graph(mname, I, frozenset(BASE_AXIOMS) | frozenset(sub)),
                                         want_witness=False)
                    truth[sub] = {p: pr[p]['holds'] for p in PROPS}
            entry['added_axiom_subsets_truth'] = [
                {'subset': list(sub), 'holds': truth[sub]} for sub in truth]
            minimal = {}
            for p in PROPS:
                if not props[p]['holds']:
                    continue
                good = [set(sub) for sub in truth if truth[sub][p]]
                mins = [s for s in good if not any(t < s for t in good)]
                minimal[p] = sorted([sorted(s, key=added.index) for s in mins],
                                    key=lambda s: (len(s), [added.index(a) for a in s]))
            entry['minimal_sets'] = minimal
            used = set(a for p in minimal for s in minimal[p] for a in s)
            entry['redundant_added_axioms'] = [a for a in added if a not in used]
            results['models'][mname][I.name] = entry
            print(mname, I.name, 'done', file=sys.stderr)
    with open(sys.argv[1] if len(sys.argv) > 1 else 'results.json', 'w') as f:
        json.dump(results, f, indent=1, sort_keys=False)


if __name__ == '__main__':
    main()
