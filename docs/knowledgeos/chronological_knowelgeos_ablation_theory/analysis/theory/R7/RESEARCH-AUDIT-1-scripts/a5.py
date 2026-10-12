from enc import *
import random, itertools
from collections import defaultdict
PLACED=sorted(CB1|CB23)
OBS={13,14,15,24}
PA_parts=[CB1,{2,3,4,5,6,7,8,9,10,11,12},{19,20,21}]
PB_parts=[CB1,{2,8,9,10,11,12},{3,4,5,6,7,19,20,21}]  # acts | sigma+work, auditor's [E]: C2 with acts, C3-5 with sigma
PC_parts=[CB1,CB23]
PD_parts=[OBS,set(PLACED)-OBS]
def rowsets(enc,rows): return [enc[x][0]&set(PLACED) for x in rows if enc[x][0]&set(PLACED)]
def cutstats(rs,A):
    B=set(PLACED)-A
    cut=sum(1 for s in rs if s&A and s&B)
    vA=sum(len(s&A) for s in rs); vB=sum(len(s&B) for s in rs)
    cond=cut/min(vA,vB) if min(vA,vB)>0 else float('inf')
    nc=cut/vA+cut/vB if vA and vB else float('inf')
    return cut,vA,vB,cond,nc
def modularity(rs,parts):
    w=defaultdict(float); deg=defaultdict(float)
    for s in rs:
        k=len(s)
        if k<2: continue
        for a,b in itertools.combinations(sorted(s),2):
            w[(a,b)]+=1/(k-1); deg[a]+=1/(k-1); deg[b]+=1/(k-1)
    m=sum(w.values())
    lab={}
    for i,p in enumerate(parts):
        for c in p: lab[c]=i
    Q=0
    for i,p in enumerate(parts):
        Lc=sum(v for (a,b),v in w.items() if lab.get(a)==i and lab.get(b)==i)
        dc=sum(deg[c] for c in p)
        Q+=Lc/m-(dc/(2*m))**2
    return Q
def cohesion(rs,parts):
    lab={}
    for i,p in enumerate(parts):
        for c in p: lab[c]=i
    res=[]
    for i,p in enumerate(parts):
        ins=tot=0
        for s in rs:
            for a in s&p:
                for b in s:
                    if b==a: continue
                    tot+=1; ins+= (lab.get(b)==i)
        res.append((ins,tot,round(ins/tot,2) if tot else None))
    return res
for name,enc in [('audit',aud),('mine',mine)]:
    for lab,rows in [('TA',TA),('TA+TB',TA+TB)]:
        rs=rowsets(enc,rows)
        c=cutstats(rs,CB1)
        random.seed(7); conds=[];ncs=[];mods=[]
        for _ in range(20000):
            A=set(random.sample(PLACED,9)); s=cutstats(rs,A); conds.append(s[3]); ncs.append(s[4]); mods.append(modularity(rs,[A,set(PLACED)-A]) if _<2000 else None)
        pct=sum(x<c[3] for x in conds)/len(conds); pnc=sum(x<=c[4] for x in ncs)/len(ncs)
        mm=[x for x in mods if x is not None]
        print(name,lab,'Pc cut,volA,volB,cond,ncut',[round(v,3) if isinstance(v,float) else v for v in c],
              'cond pct(strictly lower) %.2f'%pct,'ncut p %.4f'%pnc)
        print('   modularity Pc %.3f Pa %.3f Pb %.3f Pd %.3f rand mean %.3f max %.3f'%(modularity(rs,PC_parts),modularity(rs,PA_parts),modularity(rs,PB_parts),modularity(rs,PD_parts),sum(mm)/len(mm),max(mm)))
        print('   cohesion CB1,CB23',cohesion(rs,PC_parts))
