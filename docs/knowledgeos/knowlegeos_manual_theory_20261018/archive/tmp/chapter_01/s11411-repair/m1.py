"""Candidate repaired model M1 for §1.14.1 (PROPOSAL; scratchpad encoding, not part of the book).
One monotone operator over a finite set of keys: acts ('A',x), grant windows ('W',g,point,view,judge), claims ('C',c,t).
upsilon is applied AFTER the U-valued least fixed point (stage 2). This encoding checks the encoding, not the text."""
T, F, U = 'T', 'F', 'U'
NEG = float('-inf')
def NOT(a): return {T: F, F: T, U: U}[a]
def AND(*v): v = list(v); return F if F in v else (U if U in v else T)
def OR(*v): v = list(v); return T if T in v else (U if U in v else F)
def LT(a, b): return T if a < b else F

class M1:
    def __init__(s, acts, par, K=None):
        s.p = dict(rho='now', kappa='evidential', chi='cosign', pi='undetermined', ups='undetermined', sigma='standalone',
                   C1='represents', P=set(), delta=False, contain=True, late_grant='U', late_effect='record-time', closed_end=False)
        s.p.update(par)
        s.acts = {a['id']: a for a in acts if K is None or a['s'] < K}
        s.issuer = {a['obj']: a for a in s.acts.values() if a['act'] == 'issue-grant'}
        s.suppressed = set()
    # ---- domains: world time t; record position s; points (t, s) with time bounds as (t, -inf)
    def pt(s, x): return (x['t'], x['s'])
    def epoint(s, y): e = y.get('eff', y['t']); return (e, y['s']) if e == y['t'] else (e, NEG)
    def rectime(s, y): return y.get('rt', y['s'])          # recording time tau(s); here given or = position
    def visible(s, y, view): return view is None or y['s'] < view
    def view_of(s, x): return x['s'] if s.p['rho'] == 'at-the-time' else None
    # ---- evaluation with a valuation V (dict); missing keys read as U (bottom)
    def get(s, V, k): s.need.add(k); return V.get(k, U)
    def valid_at(s, V, y, point, view):
        """y has effect at `point`: authorized and not annulled (with effect) by a visible act annulment."""
        ann = [s.get(V, ('A', z['id'])) for z in s.acts.values() if z['act'] == 'annul-act' and z['obj'] == y['id']
               and z['id'] not in s.suppressed and s.visible(z, view) and s.epoint(z) <= point]
        return AND(s.get(V, ('A', y['id'])), NOT(OR(*ann)))
    def window(s, V, g, point, view, judge):
        iss = s.issuer.get(g)
        if iss is None or not s.visible(iss, view):
            return F if iss is None else (s.p['late_grant'])     # C11: grant recorded after the act it is cited for
        frm, to = iss.get('from', iss['t']), iss.get('to')
        w1 = AND(s.valid_at(V, iss, point, view), T if s.pt(iss) <= point else F, T if (frm, NEG) <= point else F)
        if to is None: w2 = U if s.p['pi'] == 'undetermined' else T
        else: w2 = T if (point < (to, NEG) if not s.p['closed_end'] else point <= (to, float('inf'))) else F
        closers = []
        for y in s.acts.values():
            if y['id'] in s.suppressed or not s.visible(y, view) or y['id'] == judge or y.get('cause') == judge: continue
            if y['act'] in ('end-grant', 'annul-grant') and y['obj'] == g:
                if y.get('kind') == 'suspension':
                    if s.epoint(y) <= point < (y['until'], NEG): closers.append(s.valid_at(V, y, point, view))
                elif s.epoint(y) <= point: closers.append(s.valid_at(V, y, point, view))
            if iss.get('single_use') and y['act'] not in ('issue-grant',) and y.get('cited') and any(g in B for B in y['cited']) \
                    and s.pt(y) < point:                                  # consumption = effect of a VALID use (C13 candidate)
                closers.append(s.get(V, ('A', y['id'])))
        w4 = T
        if s.p['delta'] and iss.get('cited'):                             # delegation cascade (13.3(d), reserved)
            w4 = OR(*[AND(*[s.get(V, ('W', h, point, view, judge)) for h in B]) for B in iss['cited']])
        return AND(w1, w2, NOT(OR(*closers)), w4)
    def grant_attr(s, g): return s.issuer.get(g, {})
    def covers(s, B, x):
        gs = [s.grant_attr(g) for g in B]
        if any(not g for g in gs): return F
        if any(g.get('holder') is None for g in gs): return U          # today's records: holder not recorded (F-14)
        act_ok = all(x['act'] in g['acts'] for g in gs)
        hold_ok = all(g['holder'] in x['actors'] for g in gs) and all(any(g['holder'] == a for g in gs) for a in x['actors'])
        if s.p['chi'] == 'cosign': sc_ok = all(x['scope'] <= g['scope'] for g in gs)
        else: sc_ok = x['scope'] <= set().union(*[g['scope'] for g in gs])
        ok = act_ok and hold_ok and sc_ok
        if ok and x['act'] == 'issue-grant' and s.p['contain']:          # 13.3(a): containment of scope and period (Ded, proposed)
            ok = all(x['scope'] <= g['scope'] and (g.get('to') is None or (x.get('to') is not None and x['to'] <= g['to'])) for g in gs)
        return T if ok else F
    def auth(s, V, x):
        if x.get('root'): return T
        c = x.get('cited')
        if c is None: return F if s.p['kappa'] == 'constitutive' else U
        if c == []: return F
        view = s.view_of(x)
        return OR(*[AND(*[s.get(V, ('W', g, s.pt(x), view, x['id'])) for g in B], s.covers(B, x)) for B in c])
    def effective_for(s, y, x):
        """When a later-recorded ending y takes effect for x's product under at-the-time (reserved decision 2)."""
        if s.p['rho'] == 'now' or y['s'] < x['s']: return s.epoint(y)
        if s.p['late_effect'] == 'record-time': return max(s.epoint(y), (s.rectime(y), NEG))
        return max(s.epoint(y), (y['t'], NEG))
    def claim(s, V, c, t):
        tp = (t, float('inf'))
        ads = [x for x in s.acts.values() if x['act'] == 'adopt' and x['obj'] == c and x['t'] <= t]
        eps = [] if ads else [F if s.p['C1'] == 'constitutes' else U]       # H2: C1 inside the formula (F-5)
        out = []
        for x in ads:
            bases = [[]] if x.get('root') else ([None] if x.get('cited') is None else (x['cited'] or [[]]))
            for B in bases:
                if x.get('root'): c1 = T
                elif B is None: c1 = F if s.p['kappa'] == 'constitutive' else U
                elif B == []: c1 = F
                else: c1 = AND(*[s.get(V, ('W', g, s.pt(x), s.view_of(x), x['id'])) for g in B], s.covers(B, x))
                ann = [s.valid_at(V, z, tp, None) for z in s.acts.values() if z['act'] == 'annul-act' and z['obj'] == x['id']
                       and z['id'] not in s.suppressed and s.effective_for(z, x) <= tp]
                c1 = AND(c1, NOT(OR(*ann)))
                lo, hi = x.get('val', (x['t'], float('inf'))); c2 = T if lo <= t < hi else F
                rv = []
                for y in s.acts.values():
                    if y['act'] not in ('revoke', 'supersede') or y['obj'] != c or y['id'] in s.suppressed: continue
                    if s.pt(y) <= s.pt(x) or s.effective_for(y, x) > tp: continue
                    v = s.valid_at(V, y, tp, None)
                    if y['act'] == 'supersede' and s.p['sigma'] == 'conditional': v = AND(v, s.get(V, ('C', y['succ'], t)))
                    rv.append(v)
                c3 = NOT(OR(*rv))
                ends = []
                for g in (B or []):
                    iss = s.issuer.get(g, {})
                    if 'expiry' in s.p['P'] and iss.get('to') is not None and x['t'] < iss['to'] <= t: ends.append(T)
                    for y in s.acts.values():
                        if y['obj'] != g or y['act'] not in ('end-grant', 'annul-grant') or y['id'] in s.suppressed: continue
                        k = y.get('kind', 'annulment')
                        if y.get('cause') == x['id'] or k not in s.p['P']: continue
                        if k == 'suspension':
                            if s.epoint(y) <= tp < (y['until'], NEG): ends.append(s.valid_at(V, y, tp, None))
                        elif s.pt(x) < s.effective_for(y, x) <= tp: ends.append(s.valid_at(V, y, tp, None))
                c4 = NOT(OR(*ends))
                out.append(AND(c1, c2, c3, c4))
        return OR(*out, *eps)
    def phi(s, V):
        s.need = set(); N = {}
        for k in list(V.keys()) + [('A', a) for a in s.acts]:
            if k in N: continue
            if k[0] == 'A': N[k] = s.auth(V, s.acts[k[1]])
            elif k[0] == 'W': N[k] = s.window(V, *k[1:])
            elif k[0] == 'C': N[k] = s.claim(V, k[1], k[2])
        for k in s.need:
            N.setdefault(k, U)
        return N
    def lfp(s, seed=()):
        V = {k: U for k in seed}
        for i in range(500):
            N = s.phi(V)
            if N == V: return V, i
            V = N
        raise RuntimeError('no fixed point reached (operator not monotone?)')
    def evaluate(s, c, t):
        V, n = s.lfp([('C', c, t)])
        if s.p['ups'] == 'does-not-count':          # stage 2: endings whose authorization is U are suppressed, then re-solve
            s.suppressed = {k[1] for k, v in V.items() if k[0] == 'A' and v == U and s.acts[k[1]]['act'] in
                            ('end-grant', 'annul-grant', 'annul-act', 'revoke', 'supersede')}
            V, n = s.lfp([('C', c, t)])
        return V[('C', c, t)], V
