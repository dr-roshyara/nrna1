# Research audit r1: Q2 re-run on three encodings with size-matched nulls.
import random, itertools, sys
import numpy as np
sys.path.insert(0, "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/c3901d37-f846-4ebe-b010-0da3929cbcb5/scratchpad")
from collections import defaultdict
from enc import TA, TB, TC, TD, ALL, aud, mine as rev, mk, CB1, CB23

# ---- audit-corrected encoding (audc): aud + explicit conventions ----
# K1: C13 whenever the statement names a record (rho, "record(s)"); attribute names alone (r, c) do not add C13.
# K2: "conforms"/"violates" read through Def 1.10: sigma(t(w))(tau(w)) in P -> {3,4,6,19,20,21}.
# K3: unspecified "act"/E = E^iss u E^adp -> {8,9}; "issue act" -> {8}.
# K4: class rule (T3 rule 3) adds CLASS with actor flag N (D-chg's alpha); keeper reads add R -> flag B.
D_CHG={2,3,5,6,7,8,12}; D_ST={8,10,12}; D_REC={8,9,13,17}
B_CHG={3,5,6,7,8,12,19,20}; CONF={3,4,6,19,20,21}; B_RECW={13,17,19}
CL=mk(D_CHG,D_ST,D_REC,B_CHG,CONF,B_RECW)
audc=dict(aud)
E={8,9}
upd={
 'R2':({13,17,18}|E,None), 'R3':({11,13,14,18}|E,None), 'R9':({13,17,18,19}|E,None),
 'R11':({6}|E,None), 'R12':(CL|E,'N'), 'R13':(set(CL),'N'),
 'R16':({13,17,19,23}|E,None), 'R17':({6,19}|E,None), 'R22':({13,19}|E,'U'),
 'R23':({2,6,11,12,13,16,17,18,19}|E,'U'), 'R24':(CL|{13,17,18,27}|E,'N'),
 'R29':(set(D_REC),None), 'R32':(set(CONF),None), 'R33':(set(CONF),None), 'R34':(set(CONF),None),
 'R35':(set(B_RECW),None), 'R36':(D_REC|B_RECW,None), 'R25':(set(D_CHG),'N'), 'R26':(D_CHG-{2},None),
 'R30':(set(B_CHG),None),'R31':(set(B_CHG),None),'R38':(set(D_CHG),'N'),
 'R39':({13,18}|E,None), 'R79':(set(E),None), 'R83':({13,17,19}|E,None),
 'R40':({12,13,14},None), 'R41':({13,17,19}|E,None), 'R44':({13,18}|E,None), 'R45':({13,18}|E,None),
 'R47':({6}|E,None), 'R49':({13,19,23}|E,None), 'R51':(set(CL),'N'), 'R52':({15,18}|E,None),
 'R66':(CL|{2,6,12,13,16,17,18,19,26}|E,'B'), 'R67':(CL|{3,4,19,20,26},'N'), 'R68':(CL|{2,6,10,26},'N'),
 'R69':(CL|{6,19,20,26},'N'), 'R70':({13,16,18,25}|E,'B'), 'R71':(D_CHG|B_CHG,'N'),
 'R77':({13}|E,None), 'R78':({13,18}|E,None), 'R82':(CL|{6,12,13,17,18,19},'N'),
}
audc.update(upd)
ENCS=[('A0 audit r0 (App. A)',aud),('A1 audit corrected',audc),('RV reviewer',rev)]
PLACED=sorted(CB1|CB23); PS=set(PLACED)
OBS={13,14,15,24}
CUTS={
 'P-c records|norm+work':[CB1,CB23],
 'P-a records|acts+sigma|work':[CB1,{2,3,4,5,6,7,8,9,10,11,12},{19,20,21}],
 'P-b records|acts|sigma+work':[CB1,{2,8,9,10,11,12},{3,4,5,6,7,19,20,21}],
 'P-d observed|unobserved':[OBS,PS-OBS],
 'sort: acts {2,8-12}|rest':[{2,8,9,10,11,12},PS-{2,8,9,10,11,12}],
 'sort: work {19-21}|rest':[{19,20,21},PS-{19,20,21}],
 'sort: norm {3-7}|rest':[{3,4,5,6,7},PS-{3,4,5,6,7}],
}
def rsets(enc,rows): return [enc[x][0]&PS for x in rows if enc[x][0]&PS]
def lab(parts):
    d={}
    for i,p in enumerate(parts):
        for c in p: d[c]=i
    return d
def ncut_count(rs,parts):
    L=lab(parts); return sum(1 for s in rs if len({L[c] for c in s})>=2)
def vols(rs,parts): return [sum(len(s&p) for s in rs) for p in parts]
def stats(rs,parts):
    x=ncut_count(rs,parts); v=vols(rs,parts)
    cond=x/min(v) if min(v)>0 else float('inf')
    nc=sum(x/vi for vi in v) if min(v)>0 else float('inf')
    return x,cond,nc
def modularity(rs,parts):
    w=defaultdict(float); deg=defaultdict(float)
    for s in rs:
        k=len(s)
        if k<2: continue
        for a,b in itertools.combinations(sorted(s),2):
            w[(a,b)]+=1/(k-1); deg[a]+=1/(k-1); deg[b]+=1/(k-1)
    m=sum(w.values()); L=lab(parts); Q=0
    for i,p in enumerate(parts):
        Lc=sum(v for (a,b),v in w.items() if L.get(a)==i and L.get(b)==i)
        Q+=Lc/m-(sum(deg[c] for c in p)/(2*m))**2
    return Q
def randparts(sizes,rng):
    sh=PLACED[:]; rng.shuffle(sh); out=[]; i=0
    for n in sizes: out.append(set(sh[i:i+n])); i+=n
    return out
def cohesion(rs,parts):
    L=lab(parts); res=[]
    for i,p in enumerate(parts):
        ins=tot=0
        for s in rs:
            for a in s&p:
                for b in s:
                    if b!=a: tot+=1; ins+=(L[b]==i)
        res.append(ins/tot if tot else float('nan'))
    return res
N=int(sys.argv[1]) if len(sys.argv)>1 else 4000
for ename,enc in ENCS:
    print('\n#####',ename)
    for rlab,rows in [('TA',TA),('TA+TB',TA+TB)]:
        rs=rsets(enc,rows)
        print(f'--- rows {rlab}')
        print(f'{"cut":32s} {"cross":>5s} {"null":>5s} {"p(x<=)":>7s} {"cond":>5s} {"pct":>4s} {"ncut":>5s} {"p":>6s} {"Q":>6s} {"p(Q>=)":>7s}')
        for cname,parts in CUTS.items():
            sizes=[len(p) for p in parts]
            x,cond,nc=stats(rs,parts); Q=modularity(rs,parts)
            rng=random.Random(20261013)
            xs=[];cs=[];ns=[];qs=[]
            for k in range(N):
                rp=randparts(sizes,rng); a,b,c=stats(rs,rp); xs.append(a);cs.append(b);ns.append(c)
                if k<N//4: qs.append(modularity(rs,rp))
            xs=np.array(xs);cs=np.array(cs);ns=np.array(ns);qs=np.array(qs)
            print(f'{cname:32s} {x:5d} {xs.mean():5.1f} {(xs<=x).mean():7.4f} {cond:5.3f} {(cs<cond).mean():4.2f} {nc:5.3f} {(ns<=nc).mean():6.4f} {Q:6.3f} {(qs>=Q).mean():7.4f}')
        # cohesion with null (P-c)
        c=cohesion(rs,[CB1,CB23]); rng=random.Random(3); r9=[];r14=[]
        for _ in range(2000):
            rp=randparts([9,14],rng); cc=cohesion(rs,rp); r9.append(cc[0]); r14.append(cc[1])
        r9=np.array([v for v in r9 if v==v]); r14=np.array(r14)
        print(f'   cohesion CB1 {c[0]:.2f} (random-9 mean {r9.mean():.2f}, P(rand>=) {(r9>=c[0]).mean():.3f}); '
              f'CB23 {c[1]:.2f} (random-14 mean {r14.mean():.2f}, P(rand>=) {(r14>=c[1]).mean():.4f})')
