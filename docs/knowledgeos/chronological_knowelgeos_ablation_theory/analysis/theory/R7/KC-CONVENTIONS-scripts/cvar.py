import sys,io,contextlib
R7=sys.argv[1]; sys.argv=['kc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import kc
B=kc.B; C=kc.SETS['C (content)']; FZ=B.FZ
v1=B.var(C,R15=(C['R15'][0]|FZ['R3'][0],None))
v2=B.var(v1,R14=({11,12,14},None))
for nm,e in (('C',C),('C+R15 reads R3 (row ref, C rule)',v1),('C+R15+R14 "may coincide"',v2)):
    for rule in ('S','S1'):
        d,n,rel=kc.hdiff(e,rule); print(nm,rule,'E3-REL',n,rel)
