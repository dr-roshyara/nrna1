from enc import *
for name,enc in [('mine',mine),('audit',aud)]:
    for rows,lab in [(TA,'TA'),(TA+TB,'TA+TB'),(ALL,'all')]:
        r={rule:sum(cross(enc,x,PC,rule,LOCPC) for x in rows) for rule in ['S','S1','S2']}
        print(name,lab,r)
    dif=[x for x in ALL if cross(enc,x,PC,'S')!=cross(enc,x,PC,'S1')]
    dif2=[x for x in ALL if cross(enc,x,PC,'S2',LOCPC)!=cross(enc,x,PC,'S1')]
    print(name,'S vs S1 differ',len(dif),dif,'S1 vs S2',dif2)
# row-level comparison of crossing status
for rule in ['S','S1']:
    d=[x for x in ALL if cross(mine,x,PC,rule,LOCPC)!=cross(aud,x,PC,rule,LOCPC)]
    print('status disagreement',rule,d)
# concept-set disagreement
n=0
for x in ALL:
    if mine[x]!=aud[x]:
        n+=1; print(x,'mine-aud',sorted(mine[x][0]-aud[x][0]),'aud-mine',sorted(aud[x][0]-mine[x][0]),mine[x][1],aud[x][1])
print('rows differing in encoding',n)
