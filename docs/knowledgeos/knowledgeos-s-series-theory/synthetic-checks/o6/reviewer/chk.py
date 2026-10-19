import itertools
def det(nfa,init,fin,alpha):
    start=frozenset(init); seen={start}; st=[start]; tr={}
    while st:
        B=st.pop()
        for a in alpha:
            C=frozenset(t for s in B for t in nfa.get((s,a),()))
            tr[(B,a)]=C
            if C not in seen: seen.add(C); st.append(C)
    return seen,tr,start,lambda B: bool(B&fin)
def mindfa(nfa,init,fin,alpha):
    S,tr,s0,acc=det(nfa,init,fin,alpha)
    part={B:acc(B) for B in S}
    while True:
        new={B:(part[B],)+tuple(part[tr[(B,a)]] for a in alpha) for B in S}
        if len(set(new.values()))==len(set(part.values())): return len(set(new.values()))
        part=new
def equivDFA(n1,n2,alpha):
    # product of determinizations: exact language equality
    S1,t1,a1,acc1=det(*n1,alpha); S2,t2,a2,acc2=det(*n2,alpha)
    seen={(a1,a2)}; st=[(a1,a2)]
    while st:
        x,y=st.pop()
        if acc1(x)!=acc2(y): return False
        for a in alpha:
            p=(t1[(x,a)],t2[(y,a)])
            if p not in seen: seen.add(p); st.append(p)
    return True
A='01'
ref=({(0,'0'):{0},(0,'1'):{1}},{0},{0,1})
ii=({(0,'0'):{0,1},(0,'1'):{1}},{0},{0,1})
print('CE1 exact equal:',equivDFA(ref,ii,A))
# exhaustive 1-state NFAs
ok1=[]
for f in [set(),{0}]:
  for l0 in [0,1]:
    for l1 in [0,1]:
      n={}
      if l0:n[(0,'0')]={0}
      if l1:n[(0,'1')]={0}
      if equivDFA(ref,(n,{0},f),A): ok1.append((f,l0,l1))
print('1-state matches:',ok1)
# CE2
for n in range(1,7):
    nfa={}
    for a in A: nfa.setdefault((0,a),set()).add(0)
    nfa[(0,'0')].add(1)
    for i in range(1,n):
        for a in A: nfa[(i,a)]={i+1}
    print(n, 'NFA states',n+1,'minDFA',mindfa(nfa,{0},{n},A))
