import random, numpy as np, sys
sys.path.insert(0,'.')
from audit import ROWS, RECORDS, NORMWORK, CUTS, sides_read, side_of, count
placed=sorted(RECORDS|NORMWORK)
def vol(S,ts):
    return sum(1 for r,(t,cs,l) in ROWS.items() if t in ts for c in cs if c in S)
rng=random.Random(7)
for ts in ['A','AB']:
    vA=vol(RECORDS,ts); vB=vol(NORMWORK,ts); x=count(CUTS['P-c (records | norm+work)'],"S'",ts)[0]
    ncut=x/vA+x/vB; cond=x/min(vA,vB)
    print(f'tiers {ts}: vol CB1={vA} vol CB23={vB} cross={x} conductance={cond:.3f} Ncut={ncut:.3f}')
    cs=[];ns=[]
    for _ in range(20000):
        sh=placed[:]; rng.shuffle(sh); a,b=set(sh[:9]),set(sh[9:])
        xx=count([a,b],"S'",ts)[0]; va,vb=vol(a,ts),vol(b,ts)
        cs.append(xx/min(va,vb)); ns.append(xx/va+xx/vb)
    cs=np.array(cs); ns=np.array(ns)
    print(f'   random 9|14: conductance mean {cs.mean():.3f}, P(rand<=P-c)={(cs<=cond).mean():.3f}; Ncut mean {ns.mean():.3f}, P(rand<=P-c)={(ns<=ncut).mean():.3f}')
    for name,cut in CUTS.items():
        if name.startswith('P-d'): cut=[cut[0],cut[1]|{1}]; rule='P'
        else: rule="S'"
        x=count(cut,rule,ts,c1_in_cut=1)[0]
        vs=[vol(s - ({1} if rule!='P' else set()),ts) for s in cut]
        print(f'   {name:36s} cross={x} vols={vs}')
