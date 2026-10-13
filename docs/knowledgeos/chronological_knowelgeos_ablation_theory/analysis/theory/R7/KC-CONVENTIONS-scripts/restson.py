import re,sys,io,contextlib
R7=sys.argv[1]; sys.argv=['kc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import kc
B=kc.B
t2=open(R7+'/T2-RULE-CANDIDATES.md').read().splitlines()
CB1={13,14,15,16,17,18,23,24,27}; CB23={2,3,4,5,6,7,8,9,10,11,12,19,20,21}
enc={}
for l in t2:
    m=re.match(r'^\| (R\d+) \|',l)
    if not m: continue
    r=m.group(1)
    if r in enc: continue
    cells=[c.strip() for c in l.split('|')]
    # rests-on cell is the one containing ' · C'
    rc=[c for c in cells if re.search(r'·\s*C\d',c)]
    if not rc: enc[r]=None; continue
    part=rc[0].split('·')[-1]
    cs={int(x) for x in re.findall(r'C(\d+)',part)}
    enc[r]=cs
missing=[r for r in B.ALL if enc.get(r) is None]
print('missing rests-on:',missing)
def side(cs,rule):
    a=1 in cs; s=cs-{1}
    x=bool(s&CB1); y=bool(s&CB23)
    if a and rule=='S': x=y=True
    return 'X' if x and y else 'CB1' if x else 'CB23' if y else 'none'
for rule in ('S','S1'):
    rel=[r for r in B.TA if enc.get(r) is not None and side(enc[r],rule)=='X' and r not in B.HOSTED]
    diffs=[]
    for r in B.TA+B.TB:
        if enc.get(r) is None: continue
        s=side(enc[r],rule); rat=[k for k in B.RAT[rule] if r in B.RAT[rule][k]][0]
        if s!=rat: diffs.append(f'{r}:{rat}->{s}')
    print(rule,'E3-REL',len(rel),rel); print('  host diffs',diffs)
for r in ['R11','R14','R15','R16','R17','R21','R79','R80','R27','R28']: print(r,sorted(enc[r]) if enc.get(r) else None)
