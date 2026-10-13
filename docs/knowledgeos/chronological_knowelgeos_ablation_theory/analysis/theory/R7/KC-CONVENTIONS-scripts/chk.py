import sys,os,io,contextlib,itertools
R7=sys.argv[1]; sys.argv=['kc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import kc
B=kc.B; FZ=B.FZ
CB1={13,14,15,16,17,18,23,24,27}; CB23={2,3,4,5,6,7,8,9,10,11,12,19,20,21}
def cat(cs):
    a=bool(cs&CB1); b=bool(cs&CB23)
    return 'X' if a and b else 'CB1' if a else 'CB23' if b else 'none'
pairs={'R14':'R43','R16':'R49','R17':'R48','R11':'R47','R21':'R46','R15':'R42'}
for g,t in pairs.items():
    print(g,t,'guard FZ',cat(FZ[g][0]),'paired T-C',cat(FZ[t][0]))
# independent rank under S for T3/FZ: rows with actor flag cross every cut
P=sorted(CB1|CB23); n=len(P)
rows=B.TA+B.TB
def crosses(cs,a,side1,rule):
    if rule=='S' and a: return True
    s=cs&set(P)
    return bool(s&side1) and bool(s-side1)
pc_side=CB1
for name,enc in (('T3',FZ),('K',kc.SETS['K (contribution)'])):
    pc=sum(crosses(enc[r][0],enc[r][1],pc_side,'S') for r in rows)
    lt=eq=0
    for comb in itertools.combinations(P,9):
        s=set(comb); c=sum(crosses(enc[r][0],enc[r][1],s,'S') for r in rows)
        if c<pc: lt+=1
        elif c==pc: eq+=1
    print(name,'S rank TA+TB',pc,lt,eq)
