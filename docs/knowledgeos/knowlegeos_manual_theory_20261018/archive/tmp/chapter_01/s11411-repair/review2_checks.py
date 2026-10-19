import io, contextlib
with contextlib.redirect_stdout(io.StringIO()):
    from tests_m2 import G, A, H, AUTH
POS=float('inf')
print('G-1 CE1: greedy dnc under rho=now suppresses an authorized revocation')
w=[G('g1',0,1), A('x','adopt','c',1,2,root=True), A('y','revoke','c',10,3,cited=[['g1']]), A('z','end-grant','g1',5,4,cited=None,kind='revocation')]
print('   now+dnc:', H(w,'c',11,rho='now',ups='does-not-count'), '| z removed:', H([e for e in w if e['id']!='z'],'c',11,rho='now'), '| at-recording+dnc:', H(w,'c',11,ups='does-not-count'))
print('G-2 CE2: v1 timing "from its own act time" not reproducible')
w=[G('g',0,1), A('x','adopt','c',2,2,cited=[['g']]), A('y','annul-grant','g',5,3,root=True,eff=0)]
for late in ('reach','never'):
    print(f'   late={late}: t=3', H(w,'c',3,P={'annulment'},late=late), ' t=6', H(w,'c',6,P={'annulment'},late=late))
print('   v1 expected: t=3 current, t=6 authority ended')
print('G-4 CE3: issuance debits a single-use parent')
w=[G('g1',0,1,quota=1,holder='Y',acts=('adopt','issue-grant')), G('g2',1,2,root=False,cited=[['g1']],actors=('Y',),scope='A',acts=('adopt',),holder='Y'), A('x','adopt','c',3,3,cited=[['g2']],actors=('Y',))]
print('   delta=yes:', H(w,'c',4,delta=True), '| unlimited quota:', H([dict(w[0],quota=None)]+w[1:],'c',4,delta=True))
print('G-5 CE4: at-act with a late-filed grant')
w=[G('g',1,1,rt=6), A('x','adopt','c',5,2,rt=7,cited=[['g']])]
print('   at-act:', H(w,'c',8,rho='at-act'), '| now:', H(w,'c',8,rho='now'), '| at-recording:', H(w,'c',8))
