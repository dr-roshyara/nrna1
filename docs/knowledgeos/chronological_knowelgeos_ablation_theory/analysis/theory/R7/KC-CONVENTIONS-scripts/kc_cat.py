# 5-category totals (crosses / internal CB23 / internal CB1 / touches CB4 only / none) per set and rule, all 83 rows and by tier
import sys,os,io,contextlib
R7=sys.argv[1]; sys.argv=['kc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import kc
B=kc.B
CB4={25,26,28}
def cat(enc,r,rule):
    s=B.side(enc,r,rule)
    if s=='none' and enc[r][0]&CB4: return 'CB4'
    return s
for k,e in kc.SETS.items():
    print('##',k)
    for rule in ('S','S1'):
        out=[]
        for name,rows in (('T-A',B.TA),('T-B',B.TB),('T-C',B.TC),('T-D',B.TD),('all',B.ALL)):
            c=[cat(e,r,rule) for r in rows]
            out.append(f"{name} {c.count('X')}/{c.count('CB23')}/{c.count('CB1')}/{c.count('CB4')}/{c.count('none')}")
        print('  ',kc.RN[rule],' | '.join(out))
