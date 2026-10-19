import random, itertools
from m1 import M1, T, F, U
R = dict(id='R', act='root', obj=None, t=0, s=0, root=True)
def iss(g, t, s, holder='X', acts=('adopt', 'revoke', 'supersede', 'end-grant', 'annul-grant', 'annul-act', 'issue-grant'),
        scope='AB', to=100, cited=None, actors=('R',), root=True, **k):
    d = dict(id='i_' + g, act='issue-grant', obj=g, t=t, s=s, holder=holder, acts=set(acts), scope=set(scope), to=to,
             cited=cited, actors=set(actors), root=root); d.update(k); return d
def act(i, a, obj, t, s, cited=None, actors=('X',), scope='A', **k):
    d = dict(id=i, act=a, obj=obj, t=t, s=s, cited=cited, actors=set(actors), scope=set(scope)); d.update(k); return d
def ev(acts, c, t, **p): return M1(acts, p).evaluate(c, t)[0]
def auth_of(acts, x, **p):
    m = M1(acts, p); V, _ = m.lfp([('A', x)])
    if p.get('ups') == 'does-not-count':
        m.suppressed = {k[1] for k, v in V.items() if k[0] == 'A' and v == U and m.acts[k[1]]['act'] in ('end-grant', 'annul-grant', 'annul-act', 'revoke', 'supersede')}
        V, _ = m.lfp([('A', x)])
    return V[('A', x)]
res = []
def check(name, got, exp): res.append((name, got, exp))

# W1 mutual annulment (F-1)
w = [iss('g1', 0, 1), iss('g2', 0, 2, holder='Y'),
     act('a1', 'annul-grant', 'g1', 5, 3, cited=[['g2']], actors=('Y',), eff=0),
     act('a2', 'annul-grant', 'g2', 6, 4, cited=[['g1']], eff=0)]
check('W1 mutual annulment, rho=now, ups=undetermined: a1', auth_of(w, 'a1'), U)
check('W1 mutual annulment, rho=now, ups=does-not-count: a1 (stage 2 defined, effects suppressed)', auth_of(w, 'a1', ups='does-not-count'), T)
check('W1 mutual annulment, rho=at-the-time: a1 (acyclic)', auth_of(w, 'a1', rho='at-the-time'), T)
check('W1 mutual annulment, rho=at-the-time: a2 (sees a1)', auth_of(w, 'a2', rho='at-the-time'), F)
# W2 consumption race at the same act time (F-8)
w = [iss('g', 0, 1, single_use=True), act('x1', 'adopt', 'c1', 5, 2, cited=[['g']]), act('x2', 'adopt', 'c2', 5, 3, cited=[['g']])]
check('W2 race: first recorded use', auth_of(w, 'x1'), T)
check('W2 race: second use', auth_of(w, 'x2'), F)
# W3 expiry (F-7) and boundary
w = [iss('g', 0, 1, to=4), act('x', 'adopt', 'c', 9, 2, cited=[['g']])]
check('W3 act after expiry', ev(w, 'c', 10), F)
for t, exp_open, exp_closed in [(3, T, T), (4, F, T), (5, F, F)]:
    w = [iss('g', 0, 1, to=4), act('x', 'adopt', 'c', t, 2, cited=[['g']])]
    check(f'W3 boundary act at t={t}, half-open', ev(w, 'c', t), exp_open)
    check(f'W3 boundary act at t={t}, closed', ev(w, 'c', t, closed_end=True), exp_closed)
w = [iss('g', 0, 1, to=None), act('x', 'adopt', 'c', 3, 2, cited=[['g']])]
check('W3 no recorded period, pi=undetermined', ev(w, 'c', 4), U)
check('W3 no recorded period, pi=open-ended', ev(w, 'c', 4, pi='open-ended'), T)
# W4 unauthorized issuance (F-3)
w = [iss('g', 1, 1, root=False, cited=[], actors=('Q',)), act('x', 'adopt', 'c', 3, 2, cited=[['g']])]
check('W4 grant issued by an act citing nothing', ev(w, 'c', 4), F)
w = [iss('h', 0, 1, holder='Y', acts=('adopt',)), iss('g', 1, 2, root=False, cited=[['h']], actors=('Y',)),
     act('x', 'adopt', 'c', 3, 3, cited=[['g']])]
check('W4 delegation without a right to delegate', ev(w, 'c', 4), F)
# W5 delegation cascade (F-4)
w = [iss('h', 0, 1, holder='Y'), iss('g', 1, 2, root=False, cited=[['h']], actors=('Y',), scope='A'),
     act('e', 'end-grant', 'h', 3, 3, root=True, kind='revocation'), act('x', 'adopt', 'c', 5, 4, cited=[['g']])]
check('W5 parent revoked before the act, delta=no', ev(w, 'c', 6), T)
check('W5 parent revoked before the act, delta=yes', ev(w, 'c', 6, delta=True), F)
# W6 headline inputs (F-5, F-6)
check('W6 no adoption, C1 represents', ev([iss('g', 0, 1)], 'c', 5), U)
check('W6 no adoption, C1 constitutes', ev([iss('g', 0, 1)], 'c', 5, C1='constitutes'), F)
w = [iss('g', 0, 1), act('x', 'adopt', 'c', 2, 2, cited=[['g']]), act('z', 'annul-act', 'x', 4, 3, root=True, eff=2)]
check('W6 adoption annulled from the start', ev(w, 'c', 5), F)
# F-13 revoked annuller (policy case C10)
w = [iss('g', 0, 1), iss('h', 0, 2, holder='Y'), act('x', 'end-grant', 'h', 5, 3, cited=[['g']], kind='revocation'),
     act('z', 'annul-grant', 'g', 10, 4, cited=[['h']], actors=('Y',), eff=1)]
check('F-13 rho=now, ups=undetermined: revoker x', auth_of(w, 'x'), U)
check('F-13 rho=now, ups=does-not-count: revoked actor z regains effect? z', auth_of(w, 'z', ups='does-not-count'), T)
check('F-13 rho=at-the-time: revoker x', auth_of(w, 'x', rho='at-the-time'), T)
check('F-13 rho=at-the-time: revoked actor z', auth_of(w, 'z', rho='at-the-time'), F)
# late-recorded revocation (reviewer case 3): revoked t=1 recorded s=10 (rt=10); act t=5 recorded s=6 (rt=6)
w = [iss('g', 0, 1), act('r', 'end-grant', 'g', 1, 10, root=True, kind='revocation', rt=10),
     act('x', 'adopt', 'c', 5, 6, cited=[['g']], rt=6)]
check('late revocation, rho=now', ev(w, 'c', 12), F)
check('late revocation, at-the-time, P={} ', ev(w, 'c', 12, rho='at-the-time'), T)
check('late revocation, at-the-time, P={revocation}, before recording (t=8)', ev(w, 'c', 8, rho='at-the-time', P={'revocation'}), T)
check('late revocation, at-the-time, P={revocation}, after recording (t=12)', ev(w, 'c', 12, rho='at-the-time', P={'revocation'}), F)
# annulment of a revocation (reviewer 18)
w = [iss('g', 0, 1), act('x', 'adopt', 'c', 2, 2, cited=[['g']]), act('y', 'revoke', 'c', 3, 3, root=True),
     act('z', 'annul-act', 'y', 4, 4, root=True, eff=3)]
check('annulled revocation (void from start)', ev(w, 'c', 5), T)
# sigma conditional mutual supersession (F-11)
w = [iss('g', 0, 1), act('xa', 'adopt', 'A', 1, 2, cited=[['g']]), act('xb', 'adopt', 'B', 1, 3, cited=[['g']]),
     act('sa', 'supersede', 'A', 2, 4, root=True, succ='B'), act('sb', 'supersede', 'B', 2, 5, root=True, succ='A')]
check('mutual conditional supersession (inside the fixed point)', ev(w, 'A', 3, sigma='conditional'), U)
# today's records: holder not recorded
w = [dict(iss('g', 0, 1), holder=None), act('x', 'adopt', 'c', 2, 2, cited=[['g']])]
check("today's grant records (no holder field)", ev(w, 'c', 3), U)

bad = [r for r in res if r[1] != r[2]]
for n, g, e in res: print(('ok   ' if g == e else 'DIFF ') + f'{n}: {g}' + ('' if g == e else f'  (expected {e})'))
print(f'{len(res) - len(bad)}/{len(res)} as expected by the lead (expectations are the lead\'s; see proposal §6)')

# ---- monotonicity probe of the operator (encoding property; the proof is structural)
order_ok = lambda a, b: a == U or a == b
random.seed(11); viol = 0; trials = 0
for n in range(400):
    gs = [iss(f'g{i}', 0, i + 1, holder=random.choice('XY'), single_use=random.random() < .3, to=random.choice([None, 6, 9])) for i in range(2)]
    acts = list(gs); s = 3
    for j in range(random.randint(2, 5)):
        k = random.choice(['adopt', 'end-grant', 'annul-grant', 'annul-act', 'revoke'])
        obj = 'c' if k in ('adopt', 'revoke') else (random.choice(['g0', 'g1']) if k != 'annul-act' else random.choice([a['id'] for a in acts]))
        acts.append(act(f'a{j}', k, obj, random.randint(1, 8), s, cited=random.choice([None, [], [['g0']], [['g1']], [['g0'], ['g1']]]),
                        actors=(random.choice('XY'),), eff=random.choice([None, 0]) or random.randint(1, 8), kind='revocation', root=random.random() < .15))
        s += 1
    for rho in ('now', 'at-the-time'):
        m = M1(acts, dict(rho=rho, delta=random.random() < .5, P={'revocation'}))
        V, _ = m.lfp([('C', 'c', 7)]); keys = list(V)
        for _ in range(5):
            Wv = {k: random.choice([T, F, U]) for k in keys}; Vv = {k: (U if random.random() < .5 else Wv[k]) for k in keys}
            a, b = m.phi(Vv), m.phi(Wv); trials += 1
            if any(not order_ok(a.get(k, U), b.get(k, U)) for k in set(a) & set(b)): viol += 1
print(f'monotonicity probe: {viol} violations in {trials} random pairs V <= W')
