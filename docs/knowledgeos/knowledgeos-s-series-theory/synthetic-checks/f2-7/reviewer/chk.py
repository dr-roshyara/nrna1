import itertools, random, sys
def setparts(lst):
    if not lst: yield []; return
    h,*r=lst
    for p in setparts(r):
        for i in range(len(p)):
            yield p[:i]+[[h]+p[i]]+p[i+1:]
        yield [[h]]+p
def pc_families(W,n):
    # yields list of dicts level->{xprefix:classid}
    def rec(s,prev,acc):
        if s>n: yield acc; return
        Xs=sorted({w[0][:s] for w in W})
        groups={}
        for x in Xs: groups.setdefault(prev[x[:-1]] if s>1 else 0,[]).append(x)
        glist=list(groups.values())
        for combo in itertools.product(*[list(setparts(g)) for g in glist]):
            lab={};k=0
            for parts in combo:
                for b in parts:
                    for x in b: lab[x]=k
                    k+=1
            yield from rec(s+1,lab,acc+[lab])
    yield from rec(1,None,[])
def analyze(W,n,F):
    d=lambda x:F[len(x)-1][x]
    Ws=[{(w[0][:s],w[1][:s]) for w in W} for s in range(1,n+1)]
    Xs=[sorted({x for x,o in Ws[s-1]}) for s in range(1,n+1)]
    def conf(s,a,b):
        if d(a)==d(b): return False
        oa={o for x,o in Ws[s-1] if x==a}; ob={o for x,o in Ws[s-1] if x==b}
        return any(o[-1] in (d(a),d(b)) for o in oa&ob)
    sjoint=all(any(conf(s,a[:s],b[:s]) for s in range(1,t+1))
        for t in range(1,n+1) for a,b in itertools.combinations(Xs[t-1],2) if d(a)!=d(b))
    smulti=all(conf(t,a,b) for t in range(1,n+1) for a,b in itertools.combinations(Xs[t-1],2) if d(a)!=d(b))
    J=True; nG=0
    for G in pc_families(W,n):
        nG+=1
        ver=all(len({c for (x,o),c in [((x,o),o[-1]==d(x)) for x,o in Ws[s-1]] if (G[s-1][x],o)==key})<=1
                for s in range(1,n+1) for key in {(G[s-1][x],o) for x,o in Ws[s-1]})
        det=all(len({d(x) for x in Xs[s-1] if G[s-1][x]==k})<=1 for s in range(1,n+1) for k in set(G[s-1].values()))
        if ver!=det: J=False
        if det and not ver: print("DET-NOT-VER",W,F); sys.exit(1)
    return J,sjoint,smulti,nG
