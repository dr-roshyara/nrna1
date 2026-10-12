import random, numpy as np, sys
sys.path.insert(0,'.')
import audit
from audit import ROWS, RECORDS, NORMWORK, CUTS, count, CLASS
# robustness: drop class-rule rows (whose read set contains CLASS)
drop=[r for r,(t,cs,l) in ROWS.items() if CLASS <= cs]
print('class-rule rows dropped:', drop)
for r in drop: del ROWS[r]
placed=sorted(RECORDS|NORMWORK); rng=random.Random(11)
for ts in ['A','AB']:
    obs=count(CUTS['P-c (records | norm+work)'],"S'",ts)[0]
    s=[]
    for _ in range(20000):
        sh=placed[:]; rng.shuffle(sh); s.append(count([set(sh[:9]),set(sh[9:])],"S'",ts)[0])
    s=np.array(s); n=sum(1 for r,(t,_,_) in ROWS.items() if t in ts)
    print(f'tiers {ts} ({n} rows): P-c={obs} null mean {s.mean():.1f} sd {s.std():.1f} P(null<=P-c)={(s<=obs).mean():.4f}')
