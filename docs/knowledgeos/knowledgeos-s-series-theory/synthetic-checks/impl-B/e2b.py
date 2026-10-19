import itertools, sys
S='abc'
def words(L):
    out=['']
    for n in range(1,L+1):
        out+= [''.join(p) for p in itertools.product(S,repeat=n)]
    return out
def congruences(k):
    """Right congruences with exactly k classes, as restricted-growth labelings of all words
    of length <=k in shortlex order; u~v => ua~va enforced via a (class,letter) cache."""
    W=words(k); idx={w:i for i,w in enumerate(W)}
    lab=[None]*len(W); res=[]; tr={}
    def rec(i,mx):
        if i==len(W):
            if mx+1!=k: return
            delta=dict(tr)
            if len(delta)!=3*k: return
            for j,w in enumerate(W):
                if len(w)<k:
                    for a in S: assert lab[idx[w+a]]==delta[(lab[j],a)]
            res.append((tuple(lab),delta)); return
        w=W[i]
        if w:
            key=(lab[idx[w[:-1]]],w[-1])
            if key in tr:
                lab[i]=tr[key]; rec(i+1,mx); lab[i]=None; return
            for c in range(min(mx+2,k)):
                if c==mx+1 and len(w)==k: continue
                lab[i]=c; tr[key]=c; rec(i+1,max(mx,c)); del tr[key]; lab[i]=None
        else:
            lab[i]=0; rec(i+1,0); lab[i]=None
    rec(0,-1); return res
def firsts(k,delta):
    F=[set() for _ in range(k)]
    for s in S:
        st=delta[(0,s)]; seen={st}; stack=[st]
        while stack:
            q=stack.pop()
            for a in S:
                r=delta[(q,a)]
                if r not in seen: seen.add(r); stack.append(r)
        for q in seen: F[q].add(s)
    return F
def admissible(k,delta): return all(len(f)<=2 for f in firsts(k,delta))
def setparts(xs):
    if not xs: yield []; return
    x=xs[0]
    for p in setparts(xs[1:]):
        for i in range(len(p)): yield p[:i]+[[x]+p[i]]+p[i+1:]
        yield [[x]]+p
def run(w,delta):
    q=0
    for a in w: q=delta[(q,a)]
    return q
for k in range(1,5):
    C=congruences(k)
    A=[c for c in C if admissible(k,c[1])]
    out=[k,len(C),len(A)]
    if k>=2:
        minimal=0
        for lab,delta in A:
            ok=True
            for p in setparts(list(range(k))):
                if len(p)==k: continue
                blk={q:i for i,b in enumerate(p) for q in b}
                d2={}; good=True
                for (q,a),t in delta.items():
                    if d2.setdefault((blk[q],a),blk[t])!=blk[t]: good=False;break
                if not good: continue
                # admissibility of quotient: first sets union per block
                F=firsts(k,delta)
                if all(len(set().union(*[F[q] for q in b]))<=2 for b in p): ok=False;break
            minimal+=ok
        out.append(minimal)
    # sanity: relation on words<=5 is right congruence & distinct
    W5=words(5); rels=set()
    for lab,delta in C[:25]:
        r=tuple(run(w,delta) for w in W5)
        for w in words(4):
            for v in words(4):
                if run(w,delta)==run(v,delta):
                    for a in S: assert run(w+a,delta)==run(v+a,delta)
        # restricted-growth labels are canonical so tuple distinctness = relation distinctness
        rels.add(r)
    out.append(len({c[0] for c in C})==len(C))
    print(out,flush=True)
