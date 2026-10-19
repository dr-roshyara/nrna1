import random,sys,itertools
sys.path.insert(0,'.')
import exp
rnd=random.Random(exp.SEED); ms=[exp.draw(rnd) for _ in range(40)]
def cong2(tab):
    n=len(tab); c=0
    for g in itertools.product([0,1],repeat=n):
        if g[0]!=0 or len(set(g))<2: continue
        if all(g[tab[a][x]]==g[tab[b][x]] for a in range(n) for b in range(n) if g[a]==g[b] for x in range(2)): c+=1
    return c
nocong=0; anyq=0; nontrivq=0
for M in ms:
    k=cong2(M[0]); nocong+= k==0
    L,A=exp.optimum(M,2,0.5)
    q=[C for C,f in A if exp.quotient_type(M,C)]
    anyq+= bool(q); nontrivq+= any(len(set(exp.partition(C)))==2 for C in q)
print("machines without nontrivial 2-block congruence:",nocong,"/40")
print("some optimum quotient-type:",anyq,"; some optimum nontrivial quotient-type:",nontrivq)
