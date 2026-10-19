import io, contextlib
with contextlib.redirect_stdout(io.StringIO()):
    from cases_m1 import iss, act, ev, auth_of
from m1 import M1, T, F, U
print('F-2  three-level annulment (w annuls z annuls revocation y): claim should be revoked =F')
w=[iss('g',0,1),act('x','adopt','c',2,2,cited=[['g']]),act('y','revoke','c',3,3,root=True),
   act('z','annul-act','y',4,4,root=True,eff=3),act('w','annul-act','z',5,5,root=True,eff=4)]
print('     M1 gives', ev(w,'c',6))
print('F-3  stage-2 over-suppression, at-the-time, ups=dnc: v1(stratified) says x not validly made =F')
w=[iss('g',0,1,holder='X'),iss('h',0,2,holder='X'),act('y1','end-grant','g',1,3,cited=None,kind='revocation'),
   act('y2','end-grant','h',2,4,cited=[['g']],kind='revocation'),act('x','adopt','c',3,5,cited=[['h']])]
print('     M1 gives', ev(w,'c',10,rho='at-the-time',ups='does-not-count'))
print('F-8  single-use, use u t=1 s=2, use x t=2 s=1, at-the-time: both authorized?')
w=[iss('g',0,0.5,single_use=True),act('u','adopt','c1',1,2,cited=[['g']]),act('x','adopt','c2',2,1,cited=[['g']])]
print('     A(u)=',auth_of(w,'u',rho='at-the-time'),' A(x)=',auth_of(w,'x',rho='at-the-time'))
print('F-14 adding a basis turns T into F under at-the-time')
k=dict(iss('k',0,0.2,to=0.5))
b=[iss('g',0,0.1,single_use=True),k,act('x','adopt','c',1,1,cited=[['g','k']],val=(1,2)),act('x3','adopt','c',3,2,cited=[['g']])]
a=[dict(e) for e in b]; a[2]['cited']=[['g','k'],['g']]
print('     before', ev(b,'c',10,rho='at-the-time'),' after adding basis', ev(a,'c',10,rho='at-the-time'))
print('F-15 views differ with no late entry (lambda_e=record-time, tau(y)=6)')
w=[iss('g',0,0.1),act('x','adopt','c',1,1,cited=[['g']],rt=1),act('y','revoke','c',5,2,root=True,rt=6)]
print('     now', ev(w,'c',5.5),' at-the-time', ev(w,'c',5.5,rho='at-the-time'))
