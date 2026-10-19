import itertools,sys
sys.path.insert(0,'.')
import exp
M=([(1,1),(1,2),(0,2)],[0,1,0])
res=[]
for C,f in exp.candidates(2):
    if exp.quotient_type(M,C): res.append((exp.loss(M,C,f,0.5),C,f,len(set(exp.partition(C)))))
res.sort(); print(res[:3]); print("max blocks among quotient-type:",max(r[3] for r in res))
