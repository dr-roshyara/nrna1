import sys
sys.path.insert(0, sys.argv[1])
import numpy as np
exec(open(sys.path[0]+'/q2r1.py').read().split('N=int(')[0])
a2=dict(audc); a2['R83']=(audc['R83'][0]|{23},None); a2['R58']=({5,8,9},None); a2['R63']=({8,9},'N')
idx={c:i for i,c in enumerate(PLACED)}; n=23; full=(1<<n)-1
A=np.arange(0,1<<n,dtype=np.int64)
sz=np.zeros(A.shape,dtype=np.int8); t=A.copy()
while t.any(): sz+=(t&1).astype(np.int8); t>>=1
sel=A[sz==9]; pcm=sum(1<<idx[c] for c in CB1)
for rl,rows in [('TA',TA),('TA+TB',TA+TB)]:
  ms=[sum(1<<idx[c] for c in s) for s in rsets(a2,rows)]
  cr=np.zeros(sel.shape,dtype=np.int32)
  for m in ms: cr+=(((sel&m)!=0)&(((~sel)&full&m)!=0))
  pc=sum(1 for m in ms if (m&pcm) and (m&~pcm&full))
  print(rl,pc,int((cr<pc).sum()),int((cr==pc).sum()))
  for cn,parts in CUTS.items(): print('  ',cn,stats(rsets(a2,rows),parts)[0])
