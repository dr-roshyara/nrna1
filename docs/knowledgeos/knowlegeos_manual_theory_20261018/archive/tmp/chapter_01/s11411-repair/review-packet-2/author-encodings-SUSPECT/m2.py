"""Candidate model M2 for §1.14.1 (PROPOSAL; scratchpad encoding; evidence about the encoding only)."""
T, F, U = 'T', 'F', 'U'
NEG, POS = float('-inf'), float('inf')
def NOT(a): return {T: F, F: T, U: U}[a]
def AND(*v): v = list(v); return F if F in v else (U if U in v else T)
def OR(*v): v = list(v); return T if T in v else (U if U in v else F)
def ATLEAST(n, vals):                      # Kleene threshold: monotone in the information order
    nt = sum(v == T for v in vals); nu = sum(v == U for v in vals)
    return T if nt >= n else (F if nt + nu < n else U)
ENDINGS = ('end-grant', 'annul-grant', 'annul-act', 'revoke', 'supersede')
STAGE = {'not adopted by t': 0, 'not validly made': 0, 'annulled (void)': 0, 'not yet valid': 1,
         'revoked': 2, 'superseded': 2, 'overridden by a later conflicting decision': 2,
         'authority ended': 2, 'suspended': 2, 'no longer valid': 2}
CAUSAL = ['not adopted by t', 'not validly made', 'annulled (void)', 'revoked', 'superseded',
          'overridden by a later conflicting decision', 'authority ended', 'suspended', 'no longer valid', 'not yet valid']

class M2:
    DEFAULTS = dict(rho='at-recording', kappa='evidential', C1='represents', chi='cosign', pi='undetermined',
                    ups='undetermined', sigma='standalone', P=frozenset(), delta=False, beta='half-open',
                    q='inclusive', lam='U', late='reach', c6='both-current', headline='progress', conflicts=(), consume_view='view', contain_period=True)
    def __init__(s, acts, par=None, k=POS):
        s.p = dict(s.DEFAULTS); s.p.update(par or {})
        for a in acts:
            if a['rt'] < a['t']: raise ValueError(f"{a['id']}: recorded before it was done (axiom rt >= t)")
        s.acts = {a['id']: a for a in acts if s.rt(a) <= k}          # bitemporal: records known by k
        s.issuer = {a['obj']: a for a in s.acts.values() if a['act'] == 'issue-grant'}
        s.S = set(); s.Suse = set()
    @staticmethod
    def rt(a): return a['rt']                                          # recording time: REQUIRED, no default
    def pt(s, x): return (x['t'], x['s'])
    def ep(s, y): e = y.get('eff', y['t']); return (e, y['s']) if e == y['t'] else (e, NEG)
    def view(s, x):
        r = s.p['rho']
        if r == 'now': return None
        if r == 'at-recording': return ('rec', x['s'])
        return ('act', x['t'], x['s'])
    def vis(s, y, v):
        if v is None: return True
        if v[0] == 'rec': return y['s'] < v[1]
        return (s.rt(y), y['s']) < (v[1], v[2])
    def bound_lt(s, p, to):                                            # p before the end of a period [beta]
        return p < (to, NEG) if s.p['beta'] == 'half-open' else p <= (to, POS)
    def g(s, V, k): s.need.add(k); return V.get(k, U)
    # ---------------- terms
    def valid(s, V, y, p, v):
        ann = [s.g(V, ('V', z['id'], p, v)) for z in s.acts.values() if z['act'] == 'annul-act' and z['obj'] == y['id']
               and z['id'] not in s.S and s.vis(z, v) and s.ep(z) <= p]
        return AND(s.g(V, ('A', y['id'])), NOT(OR(*ann)))
    def covers(s, B, x):
        gs = [s.issuer.get(h) for h in B]
        if any(gr is None for gr in gs): return F
        data_ok = all(x['act'] in gr['acts'] for gr in gs)
        if s.p['chi'] == 'cosign': data_ok = data_ok and all(x['scope'] <= gr['scope'] for gr in gs)
        else: data_ok = data_ok and x['scope'] <= set().union(*[gr['scope'] for gr in gs])
        if x['act'] == 'issue-grant':                                   # delegation conditions (D: containment)
            data_ok = data_ok and all(x['scope'] <= gr['scope'] and set(x['acts']) <= gr['acts'] and
                                      (not s.p['contain_period'] or gr.get('to') is None or (x.get('to') is not None and x['to'] <= gr['to'])) for gr in gs)
        if not data_ok: return F                                        # a definite mismatch is F (Kleene)
        if any(gr.get('holder') is None for gr in gs): return U
        ok = all(gr['holder'] in x['actors'] for gr in gs) and all(any(gr['holder'] == a for gr in gs) for a in x['actors'])
        return T if ok else F
    def base_ok(s, V, x, B):
        return AND(*[s.g(V, ('W', h, s.pt(x), s.view(x), x['id'])) for h in B], s.covers(B, x))
    def auth(s, V, x):
        if x.get('root'): return T
        c = x.get('cited')
        if c is None: return F if s.p['kappa'] == 'constitutive' else U
        if c == []: return F
        return OR(*[s.base_ok(V, x, B) for B in c])
    def window(s, V, h, p, v, j):
        iss = s.issuer.get(h)
        if iss is None: return F
        if not s.vis(iss, v): return s.p['lam']
        if iss.get('no_act'): w0 = T if s.p['C1'] == 'constitutes' else U          # kappa_g tied to C1
        else: w0 = s.g(V, ('V', iss['id'], p, v))
        w1 = AND(w0, T if s.pt(iss) <= p else F, T if (iss.get('from', iss['t']), NEG) <= p else F)
        to = iss.get('to')
        w2 = (U if s.p['pi'] == 'undetermined' else T) if to is None else (T if s.bound_lt(p, to) else F)
        closers = []
        for y in s.acts.values():
            if y['id'] in s.S or y['id'] == j or y.get('cause') == j or not s.vis(y, v): continue
            if y['act'] in ('end-grant', 'annul-grant') and y['obj'] == h:
                if y.get('kind') == 'suspension':
                    if s.ep(y) <= p < (y['until'], NEG): closers.append(s.g(V, ('V', y['id'], p, v)))
                elif s.ep(y) <= p: closers.append(s.g(V, ('V', y['id'], p, v)))
        quota = iss.get('quota')
        if quota:
            debits = [OR(*[s.base_ok(V, u, B) for B in u['cited'] if h in B])
                      for u in s.acts.values() if u['id'] != j and u['id'] not in s.Suse
                      and (s.vis(u, v) if s.p['consume_view'] == 'view' else True)
                      and isinstance(u.get('cited'), list) and any(h in B for B in u['cited']) and s.pt(u) < p]
            closers.append(ATLEAST(quota, debits))
        w4 = T
        if s.p['delta']:
            parents = iss.get('cited') if not iss.get('root') and iss.get('cited') else [[]]   # root: {∅}
            w4 = OR(*[AND(*[s.g(V, ('W', hh, p, v, j)) for hh in B]) for B in parents])
        return AND(w1, w2, NOT(OR(*closers)), w4)
    def ancestors(s, h, seen=None):
        seen = seen or set(); iss = s.issuer.get(h)
        if iss is None or iss.get('root') or not iss.get('cited'): return []
        out = []
        for B in iss['cited']:
            for hh in B:
                if hh not in seen: seen.add(hh); out += [hh] + s.ancestors(hh, seen)
        return out
    def claim_parts(s, V, c, t):
        tp = (t, POS) if s.p['q'] == 'inclusive' else (t, NEG)
        ads = [x for x in s.acts.values() if x['act'] == 'adopt' and x['obj'] == c and s.pt(x) <= tp]
        parts = []
        if not ads: parts.append((F if s.p['C1'] == 'constitutes' else U, ['not adopted by t']))
        for x in ads:
            bases = [[]] if x.get('root') else ([None] if x.get('cited') is None else (x['cited'] or [[]]))
            for B in bases:
                if x.get('root'): c1 = T
                elif B is None: c1 = F if s.p['kappa'] == 'constitutive' else U
                elif B == []: c1 = F
                else: c1 = s.base_ok(V, x, B)
                ann = OR(*[s.g(V, ('V', z['id'], tp, None)) for z in s.acts.values() if z['act'] == 'annul-act'
                           and z['obj'] == x['id'] and z['id'] not in s.S and s.ep(z) <= tp])
                lo, hi = x.get('val', (x['t'], None))
                c2 = T if ((lo, NEG) <= tp and (hi is None or s.bound_lt(tp, hi))) else F
                rv = []
                for y in s.acts.values():
                    if y['act'] not in ('revoke', 'supersede') or y['obj'] != c or y['id'] in s.S: continue
                    if s.pt(y) <= s.pt(x) or s.ep(y) > tp: continue
                    v = s.g(V, ('V', y['id'], tp, None))
                    if y['act'] == 'supersede' and s.p['sigma'] == 'conditional': v = AND(v, s.g(V, ('C', y['succ'], t)))
                    rv.append(('revoked' if y['act'] == 'revoke' else 'superseded', v))
                if s.p['c6'] == 'later-prevails':
                    for z in s.acts.values():
                        if z['act'] == 'adopt' and (c, z['obj']) in s.p['conflicts'] and s.pt(x) < s.pt(z) <= tp:
                            rv.append(('overridden by a later conflicting decision', s.g(V, ('V', z['id'], tp, None))))
                ends = []
                for h in (B or []):
                    for hh in [h] + (s.ancestors(h) if s.p['delta'] else []):
                        iss = s.issuer.get(hh, {})
                        if 'expiry' in s.p['P'] and iss.get('to') is not None and s.pt(x) < (iss['to'], NEG) <= tp:
                            ends.append(('authority ended', T))
                        for y in s.acts.values():
                            if y['obj'] != hh or y['act'] not in ('end-grant', 'annul-grant') or y['id'] in s.S: continue
                            kind = y.get('kind', 'annulment')
                            if y.get('cause') == x['id'] or kind not in s.p['P']: continue
                            if kind == 'suspension':
                                if s.ep(y) <= tp < (y['until'], NEG): ends.append(('suspended', s.g(V, ('V', y['id'], tp, None))))
                                continue
                            after_x = s.pt(x) < s.ep(y)
                            unseen = not s.vis(y, s.view(x)) and s.p['late'] == 'reach'   # D-6: ending unseen when x was judged
                            if s.ep(y) <= tp and (after_x or unseen):
                                ends.append(('authority ended', s.g(V, ('V', y['id'], tp, None))))
                c3 = NOT(OR(*[v for _, v in rv])); c4 = NOT(OR(*[v for _, v in ends]))
                val = AND(c1, NOT(ann), c2, c3, c4)
                reasons = []
                if val == F:
                    if c1 == F: reasons.append('not validly made')
                    if ann == T: reasons.append('annulled (void)')
                    if c2 == F: reasons.append('not yet valid' if tp < (lo, NEG) else 'no longer valid')
                    reasons += [r for r, v in rv if v == T] + [r for r, v in ends if v == T]
                parts.append((val, reasons))
        return parts
    def claim(s, V, c, t): return OR(*[v for v, _ in s.claim_parts(V, c, t)])
    # ---------------- fixed point
    def phi(s, V):
        s.need = set(); N = {}
        for key in set(V) | {('A', a) for a in s.acts}:
            if key[0] == 'A': N[key] = s.auth(V, s.acts[key[1]])
            elif key[0] == 'V': N[key] = s.valid(V, s.acts[key[1]], key[2], key[3])
            elif key[0] == 'W': N[key] = s.window(V, *key[1:])
            elif key[0] == 'C': N[key] = s.claim(V, key[1], key[2])
        for key in s.need: N.setdefault(key, U)
        return N
    def lfp(s, seed=()):
        V = {key: U for key in seed}
        for _ in range(10_000):
            N = s.phi(V)
            if N == V: return V
            assert all(V.get(k2, U) == U or V[k2] == N[k2] for k2 in V), 'information order violated'
            V = N
        raise RuntimeError('no convergence')
    def solve(s, seed):
        V = s.lfp(seed)
        if s.p['ups'] == 'does-not-count':                    # greedy suppression in record order
            for y in sorted(s.acts.values(), key=lambda a: a['s']):
                is_end = y['act'] in ENDINGS
                is_use = any(s.issuer.get(h, {}).get('quota') for B in (y.get('cited') or []) if isinstance(B, list) for h in B)
                if (is_end or is_use) and V.get(('A', y['id'])) == U:
                    if is_end: s.S.add(y['id'])
                    if is_use: s.Suse.add(y['id'])
                    V = s.lfp(seed)
        return V
    def evaluate(s, c, t):
        V = s.solve([('C', c, t)])
        parts = s.claim_parts(V, c, t); val = OR(*[v for v, _ in parts])
        if val == T: return val, 'current'
        if val == U: return val, 'cannot determine'
        if s.p['headline'] == 'progress':
            best = max((min(STAGE[r] for r in rs) if rs else -1, -min(CAUSAL.index(r) for r in rs) if rs else 0, rs)
                       for v, rs in parts)
            rs = best[2]
        else:
            rs = [r for _, x in parts for r in x]
        if not rs: return val, None                                # would falsify totality
        return val, min(rs, key=CAUSAL.index)
