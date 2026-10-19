from model2 import *
BASE=dict(rho='now',kappa='evidential',chi='each',pi='undetermined',ups='undetermined',sigma='standalone',C1='represents',P=set())
def G(i,h='X',acts=('adopt','revoke','supersede','end-grant','annul'),scope='AB',start=0,end=None,open_=True):
    return dict(id=i,holder=h,acts=set(acts),scope=set(scope),start=start,end=end,open=open_)
def A(i,act,obj,t,s,cited=None,actors=('X',),scope='A',**k):
    d=dict(id=i,act=act,obj=obj,t=t,s=s,cited=cited,actors=set(actors),scope=set(scope)); d.update(k); return d
def run(grants,acts,c,t,**par):
    p=dict(BASE); p.update(par); w=W(grants,acts,p); val=w.fixpoint(); v,r=w.current(c,t,val); return headline(v,r)
res=[]
def check(name,got,exp): res.append((name,got,exp,got==exp))
g=[G('g')]
# 1 no adoption
check('1 no adoption | C1 represents',run(g,[],'c',5),'cannot determine')
check('1 no adoption | C1 constitutes',run(g,[],'c',5,C1='constitutes'),'never adopted')
# 2 empty vs unknown citation
check('2 empty citation',run(g,[A('x','adopt','c',1,1,cited=[])],'c',5),'not validly made')
check('2 unknown citation | kappa evidential',run(g,[A('x','adopt','c',1,1,cited=None)],'c',5),'cannot determine')
check('2 unknown citation | kappa constitutive',run(g,[A('x','adopt','c',1,1,cited=None)],'c',5,kappa='constitutive'),'not validly made')
# 3 revocation of grant made t=1, recorded s=10; adoption t=5 s=5
acts=[A('r','end-grant','g',1,10,root=True,kind='revocation'),A('x','adopt','c',5,5,cited=[['g']])]
check('3 late-recorded revocation | rho now',run(g,acts,'c',6),'not validly made')
check('3 late-recorded revocation | rho at-the-time',run(g,acts,'c',6,rho='at-the-time'),'current')
# 4 unauthorized attempt to end grant
gs=[G('g'),G('gy',h='Y',acts=('adopt',))]
acts=[A('e','end-grant','g',2,2,cited=[['gy']],actors=('Y',),kind='revocation'),A('x','adopt','c',5,5,cited=[['g']])]
check('4 unauthorized grant ending',run(gs,acts,'c',6),'current')
# 5 authorized grant ending at 3
acts=[A('e','end-grant','g',3,3,root=True,kind='revocation'),A('x','adopt','c',5,5,cited=[['g']])]
check('5 authorized ending, act after',run(g,acts,'c',6),'not validly made')
acts=[A('x','adopt','c',2,1,cited=[['g']]),A('e','end-grant','g',3,3,root=True,kind='revocation')]
check('5 authorized ending, act before | P empty',run(g,acts,'c',6),'current')
check('5 authorized ending, act before | P={revocation}',run(g,acts,'c',6,P={'revocation'}),'authority ended')
# 6 mutual annulment (backdated to 0)
gs=[G('g1'),G('g2')]
acts=[A('a1','annul','g1',1,1,cited=[['g2']],eff=0),A('a2','annul','g2',2,2,cited=[['g1']],eff=0),A('x','adopt','c',3,3,cited=[['g1']])]
check('6 mutual annulment | rho now',run(gs,acts,'c',5),'cannot determine')
check('6 mutual annulment | rho at-the-time',run(gs,acts,'c',5,rho='at-the-time'),'not validly made')
# 7 identical timestamps
acts=[A('x','adopt','c',5,5,cited=[['g']]),A('y','revoke','c',5,6,root=True)]
check('7 same time, revocation recorded after',run(g,acts,'c',5),'revoked')
acts=[A('y','revoke','c',5,4,root=True),A('x','adopt','c',5,5,cited=[['g']])]
check('7 same time, revocation recorded before',run(g,acts,'c',5),'current')
# 8 expired / consumed
ge=[G('g',end=4,open_=False)]
check('8 expired, act after',run(ge,[A('x','adopt','c',5,5,cited=[['g']])],'c',6),'not validly made')
check('8 expired, act before | P empty',run(ge,[A('x','adopt','c',2,2,cited=[['g']])],'c',6),'current')
acts=[A('x','adopt','c',2,2,cited=[['g']]),A('k','end-grant','g',2,3,root=True,kind='consumption',cause='x')]
check('8 consumed by the act itself | P={consumption}',run(g,acts,'c',6,P={'consumption'}),'current')
# 9 alternatives
gs=[G('g1'),G('g2')]
acts=[A('x','adopt','c',2,2,cited=[['g1'],['g2']]),A('e','end-grant','g1',3,3,root=True,kind='revocation')]
check('9 alternative bases, one revoked | P={revocation}',run(gs,acts,'c',6,P={'revocation'}),'current')
# 10 joint
gs=[G('gx',h='X',scope='A'),G('gy',h='Y',scope='B')]
acts=[A('x','adopt','c',2,2,cited=[['gx','gy']],actors=('X','Y'),scope='AB')]
check('10 joint | co-signature',run(gs,acts,'c',6),'not validly made')
check('10 joint | pooled',run(gs,acts,'c',6,chi='pooled'),'current')
# 11 supersession by invalid successor
acts=[A('x','adopt','c',2,2,cited=[['g']]),A('x2','adopt','c2',3,3,cited=[]),A('y','supersede','c',3,4,root=True,succ='c2')]
check('11 invalid successor | sigma standalone',run(g,acts,'c',6),'superseded')
check('11 invalid successor | sigma conditional',run(g,acts,'c',6,sigma='conditional'),'current')
# 12 re-adoption
acts=[A('x','adopt','c',1,1,cited=[['g']]),A('y','revoke','c',3,3,root=True),A('x3','adopt','c',5,5,cited=[['g']])]
check('12 between revocation and re-adoption',run(g,acts,'c',4),'revoked')
check('12 after re-adoption',run(g,acts,'c',6),'current')
# 13 expired and cancelled
acts=[A('x','adopt','c',1,1,cited=[['g']],val=(1,6)),A('y','revoke','c',4,4,root=True)]
check('13 expired and revoked',run(g,acts,'c',8),'revoked')
# 14 unverifiable cancellation
acts=[A('x','adopt','c',1,1,cited=[['g']]),A('y','revoke','c',3,3,cited=None,actors=('Z',))]
check('14 unverifiable cancellation | ups undetermined',run(g,acts,'c',5),'cannot determine')
check('14 unverifiable cancellation | ups evidenced-only',run(g,acts,'c',5,ups='evidenced-only'),'current')
# 15 annulment, both views (backdated eff 0, made t=7 recorded s=10; adoption t=5 s=5)
acts=[A('x','adopt','c',5,5,cited=[['g']]),A('n','annul','g',7,10,root=True,eff=0)]
check('15 annulment | rho now',run(g,acts,'c',8),'not validly made')
check('15 annulment | at-the-time, P empty',run(g,acts,'c',8,rho='at-the-time'),'current')
check('15 annulment | at-the-time, P={annulment}, t=8',run(g,acts,'c',8,rho='at-the-time',P={'annulment'}),'authority ended')
check('15 annulment | at-the-time, P={annulment}, t=6',run(g,acts,'c',6,rho='at-the-time',P={'annulment'}),'current')
ok=sum(r[3] for r in res)
for n,gt,e,o in res: print(('PASS ' if o else 'FAIL ')+n+f' -> {gt}'+('' if o else f' (expected {e})'))
print(f'{ok}/{len(res)} as expected')
