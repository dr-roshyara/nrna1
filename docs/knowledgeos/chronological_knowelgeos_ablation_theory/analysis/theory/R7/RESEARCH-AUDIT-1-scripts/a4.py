from enc import *
import itertools
PLACED=sorted(CB1|CB23); idx={c:i for i,c in enumerate(PLACED)}
FULL=(1<<23)-1
def masks(enc,rows):
    out=[]
    for x in rows:
        m=0
        for c in enc[x][0]:
            if c in idx: m|=1<<idx[c]
        if m: out.append(m)
    return out
pcm=sum(1<<idx[c] for c in CB1)
for name,enc in [('audit',aud),('mine',mine)]:
    for lab,rows in [('TA',TA),('TA+TB',TA+TB),('all',ALL)]:
        ms=masks(enc,rows)
        def cut(A): return sum(1 for m in ms if (m&A) and (m&~A&FULL))
        pc=cut(pcm); less=0; eq=0; tot=0
        for comb in itertools.combinations(range(23),9):
            A=0
            for i in comb: A|=1<<i
            v=cut(A); tot+=1
            if v<pc: less+=1
            elif v==pc: eq+=1
        print(name,lab,'Pc',pc,'strictly fewer',less,'equal',eq,'of',tot,flush=True)
