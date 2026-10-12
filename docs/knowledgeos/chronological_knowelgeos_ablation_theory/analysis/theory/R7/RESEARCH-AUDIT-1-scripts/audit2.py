import itertools, numpy as np, sys
sys.path.insert(0,'.')
from audit import ROWS, RECORDS, NORMWORK, CUTS, sides_read, side_of
cutc = CUTS['P-c (records | norm+work)']
cutd = [{13,14,15,24}, (RECORDS|NORMWORK|{1})-{13,14,15,24}]
print('== P-c (S\') vs P-d row differences ==')
for r in ROWS:
    a = len(sides_read(r, cutc, "S'"))>=2
    b = len(sides_read(r, cutd, 'P', 1))>=2
    if a!=b: print('  ', r, ROWS[r][0], 'P-c:', a, 'P-d:', b)
print('\n== P-d: R0 clauses ==')
for r in ['R25','R27','R29','R30','R32','R35','R36','R79']:
    print('  ', r, len(sides_read(r, cutd,'P',1))>=2)
# per-concept internal fraction under P-c, T-A+T-B, C1 excluded
placed = sorted(RECORDS|NORMWORK)
for ts in ['A','AB']:
    print(f'\n== per-concept share of co-reads staying inside own P-c side (tiers {ts}) ==')
    tot={c:0 for c in placed}; ins={c:0 for c in placed}
    for r,(t,cs,loc) in ROWS.items():
        if t not in ts: continue
        cc=[c for c in cs if c in placed]
        for a,b in itertools.permutations(cc,2):
            tot[a]+=1; ins[a]+= side_of(a,cutc)==side_of(b,cutc)
    for side,name in [(RECORDS,'CB1'),(NORMWORK,'CB23')]:
        print('  ',name, ' '.join(f'C{c}:{ins[c]}/{tot[c]}' for c in sorted(side)))
        T=sum(tot[c] for c in side); I=sum(ins[c] for c in side)
        print(f'     side total inside share {I}/{T} = {I/max(T,1):.2f}')
# conductance search over all 2-way cuts, tiers AB, C1 excluded
idx={c:k for k,c in enumerate(placed)}; n=len(placed); full=(1<<n)-1
for ts in ['A','AB']:
    masks=[]; 
    for r,(t,cs,loc) in ROWS.items():
        if t in ts:
            m=0
            for c in cs:
                if c in idx: m|=1<<idx[c]
            if m and bin(m).count('1')>=1: masks.append(m)
    masks=np.array(masks,dtype=np.int64)
    # volume of concept = number of rows reading it
    vol=np.zeros(n)
    for m in masks:
        for i in range(n):
            if (m>>i)&1: vol[i]+=1
    A=np.arange(1,1<<(n-1),dtype=np.int64)
    cross=np.zeros(A.shape,dtype=np.int64)
    for m in masks:
        cross+=(((A&m)!=0)&((((~A)&full)&m)!=0))
    volA=np.zeros(A.shape)
    for i in range(n):
        volA+= ((A>>i)&1)*vol[i]
    volB=vol.sum()-volA
    cond=cross/np.maximum(np.minimum(volA,volB),1e-9)
    cond[np.minimum(volA,volB)==0]=np.inf
    pcm=sum(1<<idx[c] for c in RECORDS)
    j=np.where(A==pcm)[0]
    if len(j)==0:
        pcm2=full&~pcm; j=np.where(A==pcm2)[0]
    pc=cond[j[0]]
    order=np.argsort(cond)
    print(f'\n== conductance (tiers {ts}): P-c={pc:.3f}; rank={int((cond<pc).sum())} of {len(A)} cuts strictly better ==')
    for k in order[:5]:
        a=int(A[k]); s=[placed[i] for i in range(n) if (a>>i)&1]; o=[placed[i] for i in range(n) if not (a>>i)&1]
        sm=s if len(s)<=len(o) else o
        print(f'   cond={cond[k]:.3f} cross={cross[k]} smaller side C{sorted(sm)}')
