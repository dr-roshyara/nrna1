from enc import *
import random, itertools, math
PLACED=sorted(CB1|CB23)
def ncross(enc,rows,A):  # A: set of concepts side0 ; others in PLACED side1 ; only placed concepts count
    n=0
    for x in rows:
        cs=enc[x][0]&set(PLACED)
        a=len(cs&A)>0; b=len(cs-A)>0
        n+= a and b
    return n
def ncross_g(enc,rows,part):
    return sum(len({part[c] for c in enc[x][0] if c in part})>=2 for x in rows)
rowsets=[('TA',TA),('TA+TB',TA+TB),('all',ALL)]
for name,enc in [('audit',aud),('mine',mine)]:
    print('==',name)
    for lab,rows in rowsets:
        pc=ncross(enc,rows,CB1)
        # P-d variants
        OBS={13,14,15,24}
        pd1=ncross(enc,rows,OBS)  # only placed concepts
        part={c:0 for c in OBS}; part.update({c:1 for c in range(2,29) if c not in OBS})  # unplaced on unobserved side
        pd2=ncross_g(enc,rows,part)
        random.seed(20261012)
        vals=[]
        for _ in range(20000):
            A=set(random.sample(PLACED,9)); vals.append(ncross(enc,rows,A))
        mu=sum(vals)/len(vals); sd=(sum((v-mu)**2 for v in vals)/len(vals))**.5
        p=sum(v<=pc for v in vals)/len(vals)
        print(lab,'Pc',pc,'Pd(placed only)',pd1,'Pd(unplaced->unobs)',pd2,'rand mean %.1f sd %.1f p %.4f'%(mu,sd,p))
