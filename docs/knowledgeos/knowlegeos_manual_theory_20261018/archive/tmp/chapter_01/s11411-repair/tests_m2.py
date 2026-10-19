"""Requirement-derived tests for candidate M2. Each expectation names its SOURCE:
v1-case-n (baseline §1.14.1 case), SG-n (business safeguard n), RV F-n (review finding), OPT (documented option behaviour).
Passing = supporting evidence only; not a proof."""
from m2 import M2, T, F, U, POS
ALL = ('adopt','revoke','supersede','end-grant','annul-grant','annul-act','issue-grant')
def G(g, t, s, rt=None, holder='X', acts=ALL, scope='AB', to=100, root=True, cited=None, actors=('R',), **k):
    d = dict(id='i_'+g, act='issue-grant', obj=g, t=t, s=s, rt=t if rt is None else rt, holder=holder, acts=set(acts),
             scope=set(scope), to=to, root=root, cited=cited, actors=set(actors)); d.update(k); return d
def A(i, a, obj, t, s, rt=None, cited=None, actors=('X',), scope='A', **k):
    d = dict(id=i, act=a, obj=obj, t=t, s=s, rt=t if rt is None else rt, cited=cited, actors=set(actors), scope=set(scope)); d.update(k); return d
def H(acts, c, t, k=POS, **p): return M2(acts, p, k).evaluate(c, t)
def AUTH(acts, x, k=POS, **p):
    m = M2(acts, p, k); V = m.solve([('A', x)]); return V[('A', x)]
R = []
def check(name, src, got, exp): R.append((name, src, got, exp))

# ---------- regression: the five reproduced counterexamples
w = [G('g',0,1), A('x','adopt','c',2,2,cited=[['g']]), A('y','revoke','c',3,3,root=True),
     A('z','annul-act','y',4,4,root=True,eff=3), A('w','annul-act','z',5,5,root=True,eff=4)]
check('F-2 annulment of an annulment of a revocation', 'RV F-2; SG-2', H(w,'c',6), (F,'revoked'))
w = [G('g',0,1), G('h',0,2), A('y1','end-grant','g',1,3,cited=None,kind='revocation'),
     A('y2','end-grant','h',2,4,cited=[['g']],kind='revocation'), A('x','adopt','c',3,5,cited=[['h']])]
check('F-3 only intrinsically unverifiable endings suppressed', 'RV F-3 (v1 stratified answer)', H(w,'c',10,ups='does-not-count'), (F,'not validly made'))
w = [G('g',0,0.5,quota=1), A('u','adopt','c1',1,2,cited=[['g']]), A('x','adopt','c2',2,1,cited=[['g']])]
check('F-8 single-use, own view: both uses authorized', 'OPT consume_view=view (good faith)', (AUTH(w,'u'),AUTH(w,'x')), (T,T))
check('F-8 single-use, consumption judged on the whole record', 'OPT consume_view=now', (AUTH(w,'u',consume_view='now'),AUTH(w,'x',consume_view='now')), (T,F))
b = [G('g',0,0.1,quota=1), G('k',0,0.2,to=0.5), A('x','adopt','c',1,1,cited=[['g','k']],val=(1,2)), A('x3','adopt','c',3,2,cited=[['g']])]
a = [dict(e) for e in b]; a[2]['cited'] = [['g','k'],['g']]
check('F-14 adding a basis can turn T into F (documented non-property)', 'RV F-14', (H(b,'c',10)[0], H(a,'c',10)[0]), (T,F))
w = [G('g',0,0.1), A('x','adopt','c',1,1,cited=[['g']]), A('y','revoke','c',5,2,rt=6,root=True)]
check('F-15 views agree without late entries (bitemporal query)', 'RV F-15; Property 4', (H(w,'c',5.5,rho='now'), H(w,'c',5.5)), ((F,'revoked'),(F,'revoked')))

# ---------- the six v1 defects
w = [G('g1',0,1), G('g2',0,2,holder='Y'), A('a1','annul-grant','g1',5,3,cited=[['g2']],actors=('Y',),eff=0),
     A('a2','annul-grant','g2',6,4,cited=[['g1']],eff=0), A('x','adopt','c',7,5,cited=[['g1']])]
check('V1-a mutual annulment, now, upsilon undetermined', 'v1-case-13', H(w,'c',8,rho='now')[0], U)
check('V1-a mutual annulment, now, upsilon dnc: defined', 'RV (a): a value must exist', H(w,'c',8,rho='now',ups='does-not-count')[0] in (T,F,U), True)
check('V1-a mutual annulment, own view: defined, a2 sees a1', 'v1-case-13 (at-the-time)', (AUTH(w,'a1'),AUTH(w,'a2')), (T,F))
w = [G('g',1,1,root=False,cited=[],actors=('Q',)), A('x','adopt','c',3,2,cited=[['g']])]
check('V1-b grant issued by an act citing nothing', 'SG-2; RV (b)', H(w,'c',4), (F,'not validly made'))
w = [G('h',0,1,holder='Y'), G('g',1,2,root=False,cited=[['h']],actors=('Y',),scope='A',acts=('adopt',)),
     A('e','end-grant','h',3,3,root=True,kind='revocation'), A('x','adopt','c',5,4,cited=[['g']])]
check('V1-c cascade off: delegate keeps acting', 'OPT delta=no (v1-case-11)', H(w,'c',6)[0], T)
check('V1-c cascade on: delegate falls with delegator', 'OPT delta=yes (v1-case-11)', H(w,'c',6,delta=True), (F,'not validly made'))
check('F-1 root-issued grant under cascade on', 'RV F-1', H([G('g',0,1),A('x','adopt','c',2,2,cited=[['g']])],'c',3,delta=True)[0], T)
check('V1-d no adoption on record, C1-b', 'v1-case-14', H([G('g',0,1)],'c',5)[0], U)
check('V1-d no adoption by t, C1-a', 'v1-case-14; RV F-12', H([G('g',0,1)],'c',5,C1='constitutes'), (F,'not adopted by t'))
check('F-12 adoption only after t, C1-a', 'RV F-12', H([G('g',0,1),A('x','adopt','c',7,2,cited=[['g']])],'c',5,C1='constitutes'), (F,'not adopted by t'))
w = [G('g',0,1), A('x','adopt','c',2,2,cited=[['g']]), A('z','annul-act','x',4,3,root=True,eff=2)]
check('V1-d annulled adoption headline', 'RV (d)', H(w,'c',5), (F,'annulled (void)'))
for t0, ho, cl in [(3,T,T),(4,F,T),(5,F,F)]:
    w = [G('g',0,1,to=4), A('x','adopt','c',t0,2,cited=[['g']])]
    check(f'V1-e act at t={t0}, half-open', 'OPT beta=half-open', H(w,'c',t0)[0], ho)
    check(f'V1-e act at t={t0}, closed', 'OPT beta=closed', H(w,'c',t0,beta='closed')[0], cl)
check('V1-e no recorded period, pi undetermined', 'OPT pi', H([G('g',0,1,to=None),A('x','adopt','c',3,2,cited=[['g']])],'c',4)[0], U)
w = [G('g',0,1,quota=1), A('x1','adopt','c1',5,2,cited=[['g']]), A('x2','adopt','c2',5,3,cited=[['g']])]
check('V1-f same act time: first recorded use wins', 'RV (f) conflation 1', (AUTH(w,'x1'),AUTH(w,'x2')), (T,F))
w = [G('g',0,1), A('x','adopt','c',5,5,cited=[['g']]), A('y','revoke','c',5,6,root=True)]
check('F-5 question instant inclusive: v1 case 12', 'v1-case-12', H(w,'c',5), (F,'revoked'))
check('F-5 question instant exclusive', 'OPT q=exclusive', H(w,'c',5,q='exclusive')[0], U)
w = [G('g',0,1), A('r','end-grant','g',1,10,rt=10,root=True,kind='revocation'), A('x','adopt','c',5,6,cited=[['g']])]
check('NEW-2 late early revocation, known (k=12)', 'OPT late=reach; SG-3', H(w,'c',12,k=12,P={'revocation'}), (F,'authority ended'))
check('NEW-2 late early revocation, not yet known (k=8)', 'SG-3 (records as they stood)', H(w,'c',8,k=8,P={'revocation'})[0], T)
check('NEW-2 late=never (v1 literal)', 'OPT late=never', H(w,'c',12,k=12,P={'revocation'},late='never')[0], T)
check('NEW-2 rho=now: x never validly made', 'v1-case-10 (now)', H(w,'c',12,rho='now'), (F,'not validly made'))
w = [G('g',0,1), A('x','adopt','c',2,2,rt=10,cited=[['g']])]
check('F-7 late adoption unknown at k=5', 'RV F-7 (symmetry)', H(w,'c',5,k=5)[0], U)
check('F-7 late adoption known at k=11', 'RV F-7 (symmetry)', H(w,'c',5,k=11)[0], T)
w = [G('g',0,1), A('n','annul-grant','g',15,2,rt=16,root=True,eff=1), A('x','adopt','c',5,3,rt=20,cited=[['g']])]
check('F-6 own record position: late-recorded act sees the annulment', 'OPT rho=at-recording', AUTH(w,'x'), F)
check('F-6 record state at act time: it does not', 'OPT rho=at-act', AUTH(w,'x',rho='at-act'), T)

# ---------- remaining review findings
w = [G('g',0,1), G('k',0,2,no_act=True), A('u','adopt','c1',2,3,cited=[['g','k']]), A('x','adopt','c2',3,4,cited=[['g']])]
w[0]['quota'] = 1
check('F-4 unverifiable use under upsilon=dnc does not consume', 'RV F-4; SG-6', AUTH(w,'x',ups='does-not-count'), T)
check('F-4 under upsilon=undetermined it freezes', 'OPT ups=undetermined', AUTH(w,'x'), U)
w = [G('g',0,1,quota=1), G('h',0,2), A('u','adopt','c1',2,3,cited=[['g'],['h']]), A('x','adopt','c2',3,4,cited=[['g']])]
check('F-9 use via its alternative basis also debits g', 'OPT debit-through-any-satisfied-base', AUTH(w,'x'), F)
w = [G('h',0,1,holder='Y',acts=('adopt','issue-grant')), G('g',1,2,root=False,cited=[['h']],actors=('Y',),scope='A',acts=('adopt','revoke')),
     A('x','adopt','c',3,3,cited=[['g']])]
check('F-10 delegation cannot add an activity (narrower boundary)', 'RV F-10; AMS "narrower boundary"', H(w,'c',4)[0], F)
w = [G('g',0,1,holder=None,acts=('revoke',)), A('x','adopt','c',2,2,cited=[['g']])]
check('F-10 definite mismatch beats unknown holder', 'RV F-10 (Kleene)', H(w,'c',3)[0], F)
w = [G('g',0,1,no_act=True), A('x','adopt','c',2,2,cited=[['g']])]
check('F-11 grant record without act, C1-a', 'RV F-11', H(w,'c',3,C1='constitutes')[0], T)
check('F-11 grant record without act, C1-b', 'RV F-11', H(w,'c',3)[0], U)
w = [G('g',0,1), A('x1','adopt','c',1,2,cited=[]), A('x2','adopt','c',2,3,cited=[['g']]), A('y','revoke','c',4,4,root=True)]
check('F-13 headline follows the furthest-progressed adoption', 'RV F-13; v1 rationale for causal order', H(w,'c',5), (F,'revoked'))
w = [G('g',0,1), A('xa','adopt','A',1,2,cited=[['g']]), A('xb','adopt','B',1,3,cited=[['g']]),
     A('sa','supersede','A',2,4,root=True,succ='B'), A('sb','supersede','B',2,5,root=True,succ='A')]
check('F-11/σ mutual conditional supersession', 'v1 σ inside the fixed point', H(w,'A',3,sigma='conditional')[0], U)
w = [G('g',0,1), A('x','adopt','c',1,2,cited=[['g']]), A('z','adopt','d',3,3,cited=[['g']])]
check('F-20 later prevails', 'OPT c6=later-prevails', H(w,'c',4,c6='later-prevails',conflicts={('c','d')}), (F,'overridden by a later conflicting decision'))
check('F-20 both stay current', 'OPT c6=both-current', H(w,'c',4,conflicts={('c','d')})[0], T)
w = [G('g',0,0.5,quota=2), A('u1','adopt','a',1,3,cited=[['g']]), A('u2','adopt','b',2,4,cited=[['g']]),
     A('x','adopt','c',3,1,cited=[['g']]), A('y','adopt','d',4,2,cited=[['g']])]
check('F-21 quota 2, own view: over-consumption visible', 'RV F-21 (documented)', sum(AUTH(w,i)==T for i in ('u1','u2','x','y')), 4)
check('F-21 quota 2, consumption on whole record', 'OPT consume_view=now', sum(AUTH(w,i,consume_view='now')==T for i in ('u1','u2','x','y')), 2)
w = [G('g',0,1), A('x','adopt','c',2,2,cited=[['g']]), A('p','annul-act','q',3,3,root=True,eff=3), A('q','annul-act','p',3,4,root=True,eff=3)]
check('annul-act cycle among roots stays defined', 'T1', H(w,'c',5)[0], T)

bad = [r for r in R if r[2] != r[3]]
for n, s, g, e in R: print(('ok   ' if g == e else 'DIFF ') + f'{n}  [{s}]: {g}' + ('' if g == e else f'   expected {e}'))
print(f'\n{len(R)-len(bad)}/{len(R)} expectations met')
