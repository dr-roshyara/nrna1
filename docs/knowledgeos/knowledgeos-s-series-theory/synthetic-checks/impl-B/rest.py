import itertools
def onebit(n):
    fs=[]
    for c in (0,1): fs.append(('const',c,lambda x,c=c:c))
    for i in range(n):
        fs.append(('bit',i,lambda x,i=i:x[i])); fs.append(('notbit',i,lambda x,i=i:1-x[i]))
    return fs
codes2=list(itertools.product((0,1),repeat=2))
# E4
d=lambda c:1 if c in (1,2) else 0
encs=list(itertools.permutations(codes2))  # enc[c]=code
G=[]
for perm in itertools.permutations(range(2)):
    for fl in itertools.product((0,1),repeat=2):
        G.append(lambda x,perm=perm,fl=fl: tuple(x[perm[i]]^fl[i] for i in range(2)))
seen=set(); orbits=[]
for e in encs:
    if e in seen: continue
    orb={tuple(g(x) for x in e) for g in G}; seen|=orb; orbits.append(orb)
adm=lambda e:any(all(f(e[c])==d(c) for c in range(4)) for _,_,f in onebit(2))
admorb=[o for o in orbits if all(adm(e) for e in o)]
assert all(len({adm(e) for e in o})==1 for o in orbits)
print('E4',len(G),len(encs),len(orbits),len(admorb),sorted(len(o) for o in orbits),sum(map(adm,encs)))
# E5
for n in (2,3,4):
    cs=list(itertools.product((0,1),repeat=n)); cnt=0;tot=0
    for e in itertools.permutations(cs,3):
        tot+=1
        if all(any(all(f(e[c])==(1 if c==X else 0) for c in range(3)) for _,_,f in onebit(n)) for X in range(3)): cnt+=1
    print('E5 n=%d injective=%d admissible=%d'%(n,tot,cnt))
# O7c
Sig=['x0','x1','y0','y1']
def step(s,a):
    x,y=s; return (int(a[1]),y) if a[0]=='x' else (x,int(a[1]))
dec=lambda s:s[0]^s[1]
states=set([(0,0)]); fr=[(0,0)]
while fr:
    s=fr.pop()
    for a in Sig:
        t=step(s,a)
        if t not in states: states.add(t); fr.append(t)
def sig(s,L=4):
    out=[]
    for n in range(L+1):
        for w in itertools.product(Sig,repeat=n):
            t=s
            for a in w: t=step(t,a)
            out.append(dec(t))
    return tuple(out)
ner={}
for s in states: ner.setdefault(sig(s),set()).add(s)
nerpart=sorted(sorted(b) for b in ner.values())
print('O7c reachable',len(states),'Nerode classes',len(ner))
stats={'xor':lambda s:s[0]^s[1],'x,y':lambda s:s,'x,xor':lambda s:(s[0],s[0]^s[1]),'y,xor':lambda s:(s[1],s[0]^s[1])}
for nm,phi in stats.items():
    upd={};wd=True;dd={}
    for s in states:
        if dd.setdefault(phi(s),dec(s))!=dec(s): wd=False
        for a in Sig:
            if upd.setdefault((phi(s),a),phi(step(s,a)))!=phi(step(s,a)): wd=False
    part={}
    for s in states: part.setdefault(phi(s),set()).add(s)
    print(' ',nm,'decision+update well-defined' if wd else 'NOT well-defined', 'equals Nerode' if sorted(sorted(b) for b in part.values())==nerpart else 'differs')
# O7a
dA=lambda n:1 if n%4 in (1,2) else 0
sigA=lambda n:tuple(dA(n+k) for k in range(12))
print('O7a Nerode classes (n<40):',len({sigA(n) for n in range(40)}))
for nm,seq in [('gray',[(0,0),(0,1),(1,1),(1,0)]),('binary',[(0,0),(0,1),(1,0),(1,1)])]:
    decs=[(k,i) for k,i,f in onebit(2) if all(f(seq[r])==dA(r) for r in range(4))]
    flips=[sum(a!=b for a,b in zip(seq[r],seq[(r+1)%4])) for r in range(4)]
    print(' ',nm,'one-bit decoders',decs,'flips per step (incl wrap)',flips)
