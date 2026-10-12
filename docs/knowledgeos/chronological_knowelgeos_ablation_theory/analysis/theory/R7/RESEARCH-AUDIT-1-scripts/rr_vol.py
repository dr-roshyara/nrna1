import sys
sys.path.insert(0, sys.argv[1])
import numpy as np
exec(open(sys.path[0]+'/q2r1.py').read().split('N=int(')[0])
idx={c:i for i,c in enumerate(PLACED)}; n=23; full=(1<<n)-1
A=np.arange(0,1<<n,dtype=np.int64)
sz=np.zeros(A.shape,dtype=np.int8); t=A.copy()
while t.any(): sz+=(t&1).astype(np.int8); t>>=1
sel=A[sz==9]
pcm=sum(1<<idx[c] for c in CB1)
for ename,enc in ENCS:
  rows=TA+TB
  rs=rsets(enc,rows)
  deg={c:sum(1 for s in rs if c in s) for c in PLACED}
  print(ename,'degrees CB1',{c:deg[c] for c in sorted(CB1)}, 'CB23',{c:deg[c] for c in sorted(CB23)})
  ms=[sum(1<<idx[c] for c in s) for s in rs]
  cr=np.zeros(sel.shape,dtype=np.int32); vol=np.zeros(sel.shape,dtype=np.int32)
  for m in ms: cr+=(((sel&m)!=0)&(((~sel)&full&m)!=0))
  for c in PLACED: vol+=((sel>>idx[c])&1).astype(np.int32)*deg[c]
  pc=sum(1 for m in ms if (m&pcm) and (m&~pcm&full)); pv=sum(deg[c] for c in CB1)
  print('  P-c cross',pc,'vol',pv,'; 9-sets with vol<=',int((vol<=pv).sum()),'of',len(sel),'; among them cross<pc',int(((vol<=pv)&(cr<pc)).sum()),'cross<=pc',int(((vol<=pv)&(cr<=pc)).sum()))
  for w in (0,2,5):
    m=(abs(vol-pv)<=w); print('  vol within',w,':',int(m.sum()),'mean cross',cr[m].mean().round(1),'P(<=pc)',round(float((cr[m]<=pc).mean()),4))
