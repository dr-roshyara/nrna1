import random
exec(open('a5.py').read().split("for name,enc in")[0])
for name,enc in [('audit',aud),('mine',mine)]:
    for lab,rows in [('TA',TA),('TA+TB',TA+TB),('all',ALL)]:
        rs=rowsets(enc,rows); u=[set(x) for x in {frozenset(s) for s in rs}]
        def cut(A,R): return sum(1 for s in R if s&A and s-A)
        pc=cut(CB1,u); random.seed(5); v=[cut(set(random.sample(PLACED,9)),u) for _ in range(20000)]
        print(name,lab,'distinct rows',len(u),'of',len(rs),'Pc',pc,'mean %.1f'%(sum(v)/len(v)),'p %.4f'%(sum(x<=pc for x in v)/len(v)))
