# Independent method: layer-by-layer refinement recursion. A PC relation = chain P0..P3 where
# each block of P_k has all parents in one block of P_{k-1}; so P_k = independent partition of
# each child-set C(B) for B in P_{k-1}.  For F(Dv): additionally each block Dv-homogeneous.
import itertools, sys
from collections import Counter
from functools import lru_cache
def parts(items):
    items=list(items)
    if not items: yield []; return
    first,rest=items[0],items[1:]
    for p in parts(rest):
        for i in range(len(p)):
            yield p[:i]+[[first]+p[i]]+p[i+1:]
        yield [[first]]+p
def bell(n): return sum(1 for _ in parts(range(n)))
print("Bell",[bell(i) for i in range(9)])
def count(D=None, k=0, P=(('',),)):
    if k==3: return 1
    # children partitions per block, homogeneous w.r.t. D
    opts=[]
    for B in P:
        C=[b+c for b in B for c in '01']
        if D is None: groups=[C]
        else:
            g={}
            for h in C: g.setdefault(D[h],[]).append(h)
            groups=list(g.values())
        # partitions of C into D-homogeneous blocks = product of partitions of each group
        gp=[list(parts(gr)) for gr in groups]
        opts.append([sum(c,[]) for c in itertools.product(*gp)])
    tot=0
    for combo in itertools.product(*opts):
        newP=tuple(tuple(b) for blocks in combo for b in blocks)
        tot+=count(D,k+1,newP)
    return tot
print("|PC| =",count())
H=['']+[''.join(p) for k in range(1,4) for p in itertools.product('01',repeat=k)]
dist=Counter()
for code in range(1<<15):
    D={h:(code>>i)&1 for i,h in enumerate(H)}
    dist[count(D)]+=1
print(dict(sorted(dist.items())))
