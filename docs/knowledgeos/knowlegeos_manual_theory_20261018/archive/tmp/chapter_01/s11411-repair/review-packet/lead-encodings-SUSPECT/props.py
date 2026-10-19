import random,copy
from model2 import *
from suite import BASE,G,A
random.seed(7)
def world(late=True):
    gs=[G(f'g{i}',h=random.choice('XY'),scope=''.join(random.sample('AB',random.randint(1,2))),end=random.choice([None,None,6]),open_=random.random()<.8) for i in range(3)]
    acts=[];seq=list(range(1,9)); random.shuffle(seq) if late else None
    for j in range(random.randint(2,6)):
        k=random.choice(['adopt','adopt','revoke','end-grant','annul','supersede'])
        t=random.randint(1,8); s=seq[j] if late else j+1
        if not late: t=j+1
        cited=random.choice([None,[],[[random.choice(gs)['id']]],[[gs[0]['id']],[gs[1]['id']]],[[gs[0]['id'],gs[2]['id']]]])
        obj='c' if k in('adopt','revoke','supersede') else random.choice([g['id'] for g in gs])
        d=A(f'a{j}',k,obj,t,s,cited=cited,actors=(random.choice('XY'),),scope=random.choice('AB'),root=random.random()<.2,
            kind=random.choice(['revocation','expiry','suspension']),until=t+2,succ='c2')
        if k=='annul': d['eff']=random.choice([t,0]) if late else t
        acts.append(d)
    return gs,acts
cnt={'total':0,'P1 violations':0,'P4 violations (no late/backdated entries)':0,'revoker-alternative ended a claim (expected possible)':0}
for n in range(6000):
    late=random.random()<.5
    gs,acts=world(late); par=dict(BASE); par['P']=set(random.sample(['revocation','expiry','annulment','suspension'],random.randint(0,2)))
    for rho in('now','at-the-time'):
        par['rho']=rho; w=W(gs,acts,par); v,r=w.current('c',random.randint(1,9),w.fixpoint())
        h=headline(v,r); cnt['total']+= h is not None
    t=random.randint(1,9); par['rho']='now'
    w=W(gs,acts,par); v,_=w.current('c',t,w.fixpoint())
    ads=[a for a in acts if a['act']=='adopt' and a['cited']]
    if ads and v is T:
        a2=copy.deepcopy(acts); x=[a for a in a2 if a['id']==ads[0]['id']][0]; x['cited']=x['cited']+[[gs[2]['id']]]
        w2=W(gs,a2,par); v2,_=w2.current('c',t,w2.fixpoint())
        if v2 is not T: cnt['P1 violations']+=1
    if not late:
        vs=[]
        for rho in('now','at-the-time'):
            par['rho']=rho; w=W(gs,acts,par); vs.append(w.current('c',t,w.fixpoint())[0])
        if vs[0]!=vs[1]: cnt['P4 violations (no late/backdated entries)']+=1
    revs=[a for a in acts if a['act']=='revoke' and a['cited']]
    if revs and v is T:
        a2=copy.deepcopy(acts); y=[a for a in a2 if a['id']==revs[0]['id']][0]; y['cited']=y['cited']+[[g['id']] for g in gs]
        w2=W(gs,a2,par); v2,_=w2.current('c',t,w2.fixpoint())
        if v2 is not T: cnt['revoker-alternative ended a claim (expected possible)']+=1
print(cnt)
