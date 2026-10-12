import sys
sys.path.insert(0, "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/c3901d37-f846-4ebe-b010-0da3929cbcb5/scratchpad")
exec(open(sys.path[0]+'/q2r1.py').read().split('N=int(')[0])
from enc import cross, PC, LOCPC
for en,e in ENCS:
    r={}
    for rule in ['S','S1','S2']:
        r[rule]=[sum(cross(e,x,PC,rule,LOCPC) for x in rows) for rows in (TA,TA+TB,ALL)]
    dif=[x for x in ALL if cross(e,x,PC,'S')!=cross(e,x,PC,'S1')]
    d2=[x for x in ALL if cross(e,x,PC,'S2',LOCPC)!=cross(e,x,PC,'S1')]
    pd=[ncut_count(rsets(e,rows),CUTS['P-d observed|unobserved']) for rows in (TA,TA+TB,ALL)]
    pc=[ncut_count(rsets(e,rows),CUTS['P-c records|norm+work']) for rows in (TA,TA+TB,ALL)]
    print(en,'P-c S/S\'/S\'\' (TA,TA+TB,all):',r['S'],r['S1'],r['S2'],'| S vs S\' rows',len(dif),dif,'| S\' vs S\'\'',d2,'| P-d placed-only',pd,'P-c placed-only',pc)
diff=[(x,sorted(aud[x][0]^audc[x][0]),aud[x][1],audc[x][1]) for x in ALL if aud[x]!=audc[x]]
print(len(diff),'rows changed A0->A1')
