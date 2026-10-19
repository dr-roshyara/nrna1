import random, itertools, io, contextlib
with contextlib.redirect_stdout(io.StringIO()):
    from tests_m2 import G, A
from m2 import M2, T, F, U
random.seed(23); n=0; headless=0; errors=[]; order_viol=0
for trial in range(700):
    gs=[G(f'g{i}',0,i+1,holder=random.choice(['X','Y',None]),quota=random.choice([None,None,1,2]),to=random.choice([None,6,9]),
          no_act=random.random()<.15) for i in range(2)]
    if random.random()<.4: gs.append(G('gd',1,3,root=False,cited=[['g0']],actors=(gs[0]['holder'] or 'X',),scope='A',acts=('adopt',),to=8))
    acts=list(gs); s=10
    for j in range(random.randint(2,6)):
        kind=random.choice(['adopt','adopt','revoke','end-grant','annul-grant','annul-act','supersede'])
        t=random.randint(1,8); rt=t+random.choice([0,0,0,3])
        obj='c' if kind in('adopt','revoke','supersede') else (random.choice([g['obj'] for g in gs]) if kind!='annul-act' else random.choice([a['id'] for a in acts]))
        d=A(f'a{j}',kind,obj,t,s,rt=rt,cited=random.choice([None,[],[['g0']],[['g1']],[['g0'],['g1']],[['gd']]]),
            actors=(random.choice('XY'),),root=random.random()<.15,kind='revocation',succ='c',eff=random.choice([t,t,0]))
        if kind=='end-grant': d['kind']=random.choice(['revocation','suspension','expiry']); d['until']=t+2
        acts.append(d); s+=1
    par=dict(rho=random.choice(['now','at-recording','at-act']),ups=random.choice(['undetermined','does-not-count']),
             delta=random.random()<.5,C1=random.choice(['represents','constitutes']),sigma=random.choice(['standalone','conditional']),
             P=frozenset(random.sample(['revocation','expiry','annulment','suspension'],random.randint(0,2))),
             beta=random.choice(['half-open','closed']),consume_view=random.choice(['view','now']),late=random.choice(['reach','never']))
    try:
        v,h=M2(acts,par,random.choice([float('inf'),9])).evaluate('c',random.randint(1,10)); n+=1
        if v==F and h is None: headless+=1
    except AssertionError: order_viol+=1
    except Exception as e: errors.append(repr(e)[:120])
print(f'evaluations {n} · F without headline {headless} · information-order violations {order_viol} · errors {len(errors)}', errors[:3])
