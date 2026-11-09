import itertools
V=frozenset('ab'); subs=[frozenset(s) for r in range(3) for s in itertools.combinations('ab',r)]
opts=[None]+subs
specs=list(itertools.product(opts,repeat=2))
def comps(s): return itertools.product(*[subs if x is None else [x] for x in s])
def sv(f,A,B):
    vals={f(a,b) for a in comps(A) for b in comps(B)}
    return 'T' if vals=={True} else 'F' if vals=={False} else 'U'
cont=lambda a,b: all(x<=y for x,y in zip(a,b)) or any(len(x)==0 for x in a)
over=lambda a,b: all(x&y for x,y in zip(a,b))
def and3(v):
    return 'F' if 'F' in v else 'U' if 'U' in v else 'T'
def rule_cont(A,B,steps3=True):
    if any(x is not None and len(x)==0 for x in A): return 'T'
    v=[]
    for x,y in zip(A,B):
        if x is not None and y is not None: v.append('T' if x<=y else 'F')
        elif x is not None and y is None: v.append('U')
        elif x is None and y==V: v.append('T')
        else: v.append('U')
    r=and3(v)
    if steps3 and r=='F' and any(x is None for x in A): r='U'
    return r
def rule_over(A,B):
    v=[]
    for x,y in zip(A,B):
        if x is not None and y is not None: v.append('T' if x&y else 'F')
        elif (x is not None and len(x)==0) or (y is not None and len(y)==0): v.append('F')
        else: v.append('U')
    return and3(v)
n=0;bad3=0;bad2=0;bo=0
for A in specs:
    for B in specs:
        n+=1
        s=sv(cont,A,B)
        if rule_cont(A,B)!=s: bad3+=1
        if rule_cont(A,B,False)!=s: bad2+=1
        if rule_over(A,B)!=sv(over,A,B): bo+=1
print(n,'steps1-3 mismatches',bad3,'steps1-2 mismatches',bad2,'overlap mismatches',bo)
