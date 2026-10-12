import sys
sys.path.insert(0, "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/c3901d37-f846-4ebe-b010-0da3929cbcb5/scratchpad")
import numpy as np
exec(open(sys.path[0]+'/q2r1.py').read().split('N=int(')[0])
idx={c:i for i,c in enumerate(PLACED)}; n=23; full=(1<<n)-1
A=np.arange(0,1<<n,dtype=np.int64)
sz=np.zeros(A.shape,dtype=np.int8); t=A.copy()
while t.any(): sz+=(t&1).astype(np.int8); t>>=1
sel=A[sz==9]
pcm=sum(1<<idx[c] for c in CB1)
for ename,enc in ENCS:
    for rlab,rows in [('TA',TA),('TA+TB',TA+TB)]:
        ms=[sum(1<<idx[c] for c in s) for s in rsets(enc,rows)]
        cr=np.zeros(sel.shape,dtype=np.int32)
        for m in ms: cr+=(((sel&m)!=0)&(((~sel)&full&m)!=0))
        pc=sum(1 for m in ms if (m&pcm) and (m&~pcm&full))
        print(ename,rlab,'P-c',pc,'9|14 cuts strictly fewer',int((cr<pc).sum()),'equal',int((cr==pc).sum()),'of',len(sel))
