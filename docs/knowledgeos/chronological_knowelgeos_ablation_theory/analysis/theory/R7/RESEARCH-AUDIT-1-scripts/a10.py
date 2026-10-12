import random
exec(open('a5.py').read().split("for name,enc in")[0])
cands={'Pc records':CB1,'Pd observed':{13,14,15,24},'acts C2,C8-12':{2,8,9,10,11,12},'work C19-21':{19,20,21},
'norm C3-7':{3,4,5,6,7},'records+Cap':CB1|{2},'evidence-of-acts (B5)':{2,8,9,13,16,17,18,19,23,27}}
for name,enc in [('audit',aud),('mine',mine)]:
    rs=rowsets(enc,TA+TB)
    def cut(A): return sum(1 for s in rs if s&A and s-A)
    for k,A in cands.items():
        n=len(A); random.seed(1); v=[cut(set(random.sample(PLACED,n))) for _ in range(5000)]
        c=cutstats(rs,A)
        print(name,'TA+TB',k,'size',n,'cut',cut(A),'null mean %.1f'%(sum(v)/len(v)),'p %.4f'%(sum(x<=cut(A) for x in v)/len(v)),'Q %.3f'%modularity(rs,[A,set(PLACED)-A]),'cond %.3f'%c[3])
