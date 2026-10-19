import itertools, random, math
random.seed(7)
S=2; N=4; M=2
def hist(n): return list(itertools.product(range(S),repeat=n+1))  # h_u has u+1 symbols, u=0..N-1
def mkD(k):
    base={}
    for u in range(N):
        for h in hist(u):
            for t in range(u+1):
                base[(h,t)]=random.randrange(M)
    def D(h,t):
        u=len(h)-1
        if k is not None and u>t+k: return D(h[:t+k+1],t)
        return base[(h,t)]
    return D
def kern(rec,u):
    cl={}
    for h in hist(u): cl.setdefault(rec(h),set()).add(h)
    return frozenset(frozenset(c) for c in cl.values())
def full(D): return lambda h: tuple(tuple(D(h[:v+1],t) for t in range(v+1)) for v in range(len(h)))
def win(D,k): return lambda h: tuple(tuple(D(h[:v+1],t) for t in range(max(0,v-k),v+1)) for v in range(len(h)))
bad=0
for trial in range(200):
    for k in range(0,N+1):
        D=mkD(k)
        for u in range(N):
            if kern(full(D),u)!=kern(win(D,k),u): bad+=1
print("H1 mismatches",bad)
# H3: truncation of one base D: kernels nested
def refines(A,B): # A finer than B
    return all(any(a<=b for b in B) for a in A)
bad=0;strict=0
for trial in range(100):
    D=mkD(None)
    Dk=lambda k:(lambda h,t: D(h[:min(len(h)-1,t+k)+1],t))
    for k in range(N):
        for u in range(N):
            A=kern(full(Dk(k+1)),u);B=kern(full(Dk(k)),u)
            if not refines(A,B): bad+=1
print("H3 nonnested",bad)
# g1 realizable context-free counterexample: no corrections
D=lambda h,t:h[t]
u=3
F={tuple(D(h[:u+1],t) for t in range(u+1)) for h in hist(u)}
Df={(tuple(t for t in range(u) if D(h[:u+1],t)!=D(h[:u],t)),D(h,u)) for h in hist(u)}
print("full realizable",len(F),"diff realizable",len(Df))
