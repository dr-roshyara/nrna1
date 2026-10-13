"""Audit Q6 test 2: clause x coordinate dependence check (finite, exhaustive).

Analysis only. Enumerates a small universe of R4 r3.1 histories (Def 1.7, frame F1 + F2)
and tests, for every Def 3.1 / Table 9.4 clause, whether its truth value can vary when
only a given set X of coordinates varies (h ~_X h' over the enumerated frame).

Universe (finite; one target, so R-a coincides with R0 here, R4 Rem 9.8):
  T = {0,1}; one target; V = {p,q}; P = {p}; texts {s1,s2};
  actors A in {{x},{x,y}}; optional issue act a1; optional adoption act b1 (obj a1);
  optional work event w1; optional record r1; any Cap, Ref, con, sigma satisfying F1, F2.
Run: python3 -B clause_coords.py   [test names...]  (one enumeration pass per test; about 2-4 min per pass)
"""
import itertools as it
from collections import defaultdict

T = (0, 1); V = ('p', 'q'); P = {'p'}; INF = 'inf'; TEXTS = ('s1', 's2')

def subsets(xs):
    xs = list(xs)
    for n in range(len(xs) + 1):
        for c in it.combinations(xs, n):
            yield frozenset(c)

COORDS = ['A', 'Cap', 'Eiss', 'Eadp', 'W', 'R', 'alpha', 'd', 'v', 'prep', 'f', 'obj',
          'alphaW', 't', 'r', 'c', 'k', 'Ref', 'con', 'sigma']
IDX = {n: i for i, n in enumerate(COORDS)}

def histories():
    for A in (('x',), ('x', 'y')):
        caps = list(subsets([(a, kd) for a in A for kd in ('iss', 'adp')]))
        for has_a in (0, 1):
            a_opts = [None] if not has_a else list(it.product(A, T, V, (0, 1), (0, 1, INF)))
            for aa in a_opts:
                b_opts = [None] if not has_a else [None] + list(it.product(A, T))
                for bb in b_opts:
                    for ww in [None] + list(it.product(A, T)):
                        for rr in [None] + list(it.product(T, TEXTS, A)):
                            Eiss = ('a1',) if aa else ()
                            Eadp = ('b1',) if bb else ()
                            W = ('w1',) if ww else ()
                            R = ('r1',) if rr else ()
                            targets = list(Eiss + Eadp + W)
                            refpairs = [(rho, x) for rho in R for x in targets]
                            con_opts = [frozenset()]
                            if R:
                                con_opts = list(subsets([(e, 'r1') for e in Eiss + Eadp]))
                            for sig in it.product(V, V):
                                # F1
                                if aa:
                                    al, d, v, prep, f = aa
                                    if f != INF and sig[f] != v:
                                        continue
                                for Ref in subsets(refpairs):
                                    for con in con_opts:
                                        ok = True
                                        dd = {}
                                        if aa: dd['a1'] = aa[1]
                                        if bb: dd['b1'] = bb[1]
                                        for (e, rho) in con:  # F2
                                            if (rho, e) not in Ref or dd[e] != rr[0]:
                                                ok = False
                                        if not ok:
                                            continue
                                        for Cap in caps:
                                            alpha = []
                                            if aa: alpha.append(('a1', aa[0]))
                                            if bb: alpha.append(('b1', bb[0]))
                                            h = (A, Cap, Eiss, Eadp, W, R,
                                                 tuple(alpha), tuple(sorted(dd.items())),
                                                 (('a1', aa[2]),) if aa else (),
                                                 (('a1', aa[3]),) if aa else (),
                                                 (('a1', aa[4]),) if aa else (),
                                                 (('b1', 'a1'),) if bb else (),
                                                 (('w1', ww[0]),) if ww else (),
                                                 (('w1', ww[1]),) if ww else (),
                                                 (('r1', rr[0]),) if rr else (),
                                                 (('r1', rr[1]),) if rr else (),
                                                 (('r1', rr[2]),) if rr else (),
                                                 Ref, con, sig)
                                            yield h

def g(h, n): return dict(h[IDX[n]]) if n in ('alpha', 'd', 'v', 'prep', 'f', 'obj', 'alphaW', 't', 'r', 'c', 'k') else h[IDX[n]]

def chg(h):
    s = h[IDX['sigma']]
    return [1] if s[1] != s[0] else []

def adopted(h, a, t):
    al, d, prep, Cap, obj = g(h, 'alpha'), g(h, 'd'), g(h, 'prep'), h[IDX['Cap']], g(h, 'obj')
    if prep[a] == 0 and (al[a], 'adp') in Cap and d[a] <= t:
        return True
    for b in h[IDX['Eadp']]:
        if obj[b] == a and (al[b], 'adp') in Cap and d[b] <= t:
            return True
    return False

def act_disj(h, t, cond=None):
    s = h[IDX['sigma']]; f, v = g(h, 'f'), g(h, 'v')
    for a in h[IDX['Eiss']]:
        if f[a] == t and v[a] == s[t] and (cond is None or cond(h, a, t)):
            return True
    return False

def work_disj(h, t):
    tt = g(h, 't')
    return any(tt[w] < t for w in h[IDX['W']])

capiss = lambda h, a, t: (g(h, 'alpha')[a], 'iss') in h[IDX['Cap']]
adpt = lambda h, a, t: adopted(h, a, t)

def D_chg(h): return all(act_disj(h, t, capiss) for t in chg(h))
def D_chg_e(h): return all(act_disj(h, t) for t in chg(h))
def D_chg_j(h): return D_chg(h) and len(chg(h)) > 0
def D_st(h):
    f = g(h, 'f')
    return all(f[a] == INF or adopted(h, a, f[a]) for a in h[IDX['Eiss']])
def D_st_plus(h):
    f = g(h, 'f')
    return D_st(h) and all(not any(adopted(h, a, t) for t in T) or f[a] != INF for a in h[IDX['Eiss']])
def D_rec(h):
    E = set(h[IDX['Eiss']]) | set(h[IDX['Eadp']])
    return any(x in E for (_, x) in h[IDX['Ref']])
def B_recW(h): return any(x in h[IDX['W']] for (_, x) in h[IDX['Ref']])
def B_rec(h): return D_rec(h) and B_recW(h)
def B_rec_h(h):
    refd = {x for (_, x) in h[IDX['Ref']]}
    items = set(h[IDX['Eiss']]) | set(h[IDX['Eadp']]) | set(h[IDX['W']])
    return B_rec(h) and items <= refd
def B_rec_i(h): return len(h[IDX['Eiss']]) + len(h[IDX['Eadp']]) > 0
def B_chg(h): return all(act_disj(h, t) or work_disj(h, t) for t in chg(h))
B_chg_a = B_chg  # one target: tau(w) = tau always (R4 Rem 9.8)
def B_chg_g(h): return B_chg(h) and any(work_disj(h, t) for t in chg(h))
def B_chg_gp(h): return B_chg_g(h) and any(act_disj(h, t) for t in chg(h))
def B_chg_kst(h): return all(act_disj(h, t, adpt) or work_disj(h, t) for t in chg(h))
def B_chg_kcap(h): return all(act_disj(h, t, capiss) or work_disj(h, t) for t in chg(h))
def B_conf(h):
    s, tt = h[IDX['sigma']], g(h, 't')
    return all(s[tt[w]] in P for w in h[IDX['W']])
def B_conf_b(h):
    s, tt = h[IDX['sigma']], g(h, 't')
    return any(s[tt[w]] in P for w in h[IDX['W']])

CLAUSES = [('D-chg', D_chg), ('D-chg^e (R-e)', D_chg_e), ('D-chg^j (R-j)', D_chg_j),
           ('D-st', D_st), ('D-st+ (R-d)', D_st_plus), ('D-rec', D_rec),
           ('B-chg', B_chg), ('B-chg^g (R-g)', B_chg_g), ('B-chg^g+ (R-g+)', B_chg_gp),
           ('B-chg^k-st (R-k)', B_chg_kst), ('B-chg^k-cap (R-k)', B_chg_kcap),
           ('B-conf', B_conf), ('B-conf^b (R-b)', B_conf_b),
           ('B-recW', B_recW), ('B-rec', B_rec), ('B-rec^h (R-h)', B_rec_h),
           ('E!=0 (R-i)', B_rec_i)]

REC = ['R', 'r', 'c', 'k', 'Ref', 'con']                      # P5 record coordinates
TESTS = [[x] for x in ['Cap', 'alpha', 'd', 'v', 'prep', 'f', 'alphaW', 't',
                       'r', 'c', 'k', 'Ref', 'con', 'sigma', 'A', 'R']]
TESTS += [['REC:P5'] + REC,
          ['REC:Sprime'] + [x for x in REC if x != 'k'],
          ['REC:S(+A,alpha,alphaW)'] + REC + ['A', 'alpha', 'alphaW'],
          ['ACT-VALUED(A,alpha,alphaW,k)', 'A', 'alpha', 'alphaW', 'k'],
          ['E-block', 'Eiss', 'Eadp', 'alpha', 'd', 'v', 'prep', 'f', 'obj'],
          ['W-block', 'W', 'alphaW', 't']]

def main():
    # Streaming, one pass per test (memory-bounded); key = hash of the other coordinates.
    import sys
    only = sys.argv[1:]  # optional: names of tests to run
    res = {}
    n = 0
    for test in TESTS:
        if only and test[0] not in only:
            continue
        X = [c for c in test if c in IDX]
        keep = [i for i, nme in enumerate(COORDS) if nme not in X]
        groups = {}
        dep = [False] * len(CLAUSES)
        n = 0
        for h in histories():
            n += 1
            key = hash(tuple(h[i] for i in keep))
            mask = 0
            for j, (_, fn) in enumerate(CLAUSES):
                mask |= (1 << (2 * j + (1 if fn(h) else 0)))
            groups[key] = groups.get(key, 0) | mask
        for m in groups.values():
            for j in range(len(CLAUSES)):
                if (m >> (2 * j)) & 3 == 3:
                    dep[j] = True
        res[test[0]] = dep
        print('done', test[0], 'histories', n, 'groups', len(groups), flush=True)
        del groups
    w = max(len(nm) for nm, _ in CLAUSES)
    cols = list(res)
    print('dependence (1 = truth varies with X, all other coordinates fixed)')
    for j, (nm, _) in enumerate(CLAUSES):
        print(nm.ljust(w), ' '.join(f"{c}={int(res[c][j])}" for c in cols))
    for c in cols:
        if c.startswith('REC') or c.startswith('ACT'):
            print('clauses depending on', c, ':', [nm for j, (nm, _) in enumerate(CLAUSES) if res[c][j]])

if __name__ == '__main__':
    main()
