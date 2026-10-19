import itertools,random
random.seed(1)
def verif(X,F,Phi,E):
    P=[(x,o) for (x,o) in Phi]
    return all((o==F[x])==(o2==F[x2]) for (x,o) in P for (x2,o2) in P if o==o2 and E[x]==E[x2])
def conf(X,F,Phi,x,y):
    return F[x]!=F[y] and any((x,o) in Phi and (y,o) in Phi for o in (F[x],F[y]))
def sep(X,F,Phi,E): return all(E[x]!=E[y] for x in X for y in X if conf(X,F,Phi,x,y))
def det(X,E,F): return all(F[x]==F[y] for x in X for y in X if E[x]==E[y])
bad21=bad21a=0;bad22=0
for t in range(20000):
    n=random.randint(1,4);V=list(range(random.randint(1,4)));X=list(range(n))
    F={x:random.choice(V) for x in X}
    Phi={(x,o) for x in X for o in V if random.random()<.5}
    E={x:random.randint(0,2) for x in X}
    v=verif(X,F,Phi,E)
    if v!=sep(X,F,Phi,E): bad21+=1
    if det(X,E,F) and not v: bad21a+=1
    allconf=all(conf(X,F,Phi,x,y) for x in X for y in X if F[x]!=F[y])
    eq=all(verif(X,F,Phi,dict(zip(X,Es)))==det(X,dict(zip(X,Es)),F) for Es in itertools.product(range(n),repeat=n))
    if eq!=allconf: bad22+=1
print("F2-1 mismatches",bad21,"F2-1a",bad21a,"F2-2",bad22)
# F2-3
X=[0,1];F={0:0,1:2};Phi={(x,o) for x in X for o in (F[x],F[x]+2)}
print("F2-3 empty-evidence verifiable?",verif(X,F,Phi,{0:0,1:0}))
# F3-5 strictness counterexample: range 3, Phi o in {F,F+2}
X=[0,1,2];F={0:0,1:1,2:2};Phi={(x,o) for x in X for o in (F[x],F[x]+2)}
def part(view): 
    d={}
    for p in Phi: d.setdefault(view(*p),set()).add(p)
    return sorted(map(sorted,d.values()))
print("(o,c)==(o,d) partition under F+2 model:",part(lambda x,o:(o,o==F[x]))==part(lambda x,o:(o,F[x])))
# F2-8 joint model
import itertools
F1=lambda x:x; F2=lambda x:x
P=[]
for x1,x2 in itertools.product([0,1],repeat=2):
    for inv in (0,1): P.append((x1,x2,F1(x1)^inv,F2(x2)^inv))
ok=all((a[3]==F2(a[1]))==(b[3]==F2(b[1])) for a in P for b in P if (a[0],a[2],a[3])==(b[0],b[2],b[3]))
print("F2-8 step2 verifiable with step1 full evidence only:",ok)
