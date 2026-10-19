import itertools,sys
K=3
def gen(n):
    # canonical BFS-ordered trim DFAs, state 0 start
    T=[[None]*K for _ in range(n)]
    def rec(pos,m):
        if pos==n*K:
            if m==n: yield [row[:] for row in T]
            return
        i,j=divmod(pos,K)
        if i>=m: return
        for t in range(min(m+1,n)):
            T[i][j]=t
            yield from rec(pos+1,max(m,t+1))
        T[i][j]=None
    yield from rec(0,1)
def reach(T,s):
    seen={s};st=[s]
    while st:
        x=st.pop()
        for y in T[x]:
            if y not in seen: seen.add(y);st.append(y)
    return seen
def adm(T):
    n=len(T);cnt=[0]*n
    for x in range(K):
        for q in reach(T,T[0][x]): cnt[q]+=1
    return max(cnt)<=2
def quot(T,p,q):
    n=len(T);par=list(range(n))
    def f(x):
        while par[x]!=x: x=par[x]
        return x
    todo=[(p,q)]
    while todo:
        a,b=todo.pop();a,b=f(a),f(b)
        if a==b: continue
        par[a]=b
        for j in range(K): todo.append((T[a][j],T[b][j]))
    reps=sorted({f(x) for x in range(n)});idx={r:i for i,r in enumerate(reps)}
    Q=[[None]*K for _ in reps]
    for x in range(n):
        for j in range(K): Q[idx[f(x)]][j]=idx[f(T[x][j])]
    # start = idx[f(0)] ; relabel so start=0
    s=idx[f(0)]
    perm=list(range(len(reps)));perm[0],perm[s]=perm[s],perm[0]
    inv={perm[i]:i for i in range(len(reps))}
    return [[inv[Q[perm[i]][j]] for j in range(K)] for i in range(len(reps))]
for n in range(1,6):
    tot=a=mx=0
    for T in gen(n):
        tot+=1
        if not adm(T): continue
        a+=1
        if all(not adm(quot(T,p,q)) for p in range(n) for q in range(p+1,n)): mx+=1
    print(n,tot,a,mx,flush=True)
