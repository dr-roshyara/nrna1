import itertools
exec(open('a5.py').read().split("for name,enc in")[0])
OBS={13,14,15,24}
for name,enc in [('audit',aud),('mine',mine)]:
    for lab,rows in [('TA',TA),('TA+TB',TA+TB),('all',ALL)]:
        rs=rowsets(enc,rows)
        def cut(A): return sum(1 for s in rs if s&A and s-A)
        pd=cut(OBS); vals=[cut(set(c)) for c in itertools.combinations(PLACED,4)]
        mu=sum(vals)/len(vals)
        less=sum(v<pd for v in vals); le=sum(v<=pd for v in vals)
        c=cutstats(rs,OBS)
        conds=[cutstats(rs,set(cc))[3] for cc in itertools.combinations(PLACED,4)]
        print(name,lab,'Pd',pd,'4|19 mean %.1f'%mu,'P(<=Pd) %.3f'%(le/len(vals)),'strictly fewer',less,'of',len(vals),
              'Pd cond %.3f'%c[3],'cond pct %.2f'%(sum(x<c[3] for x in conds)/len(conds)), 'Q %.3f'%modularity(rs,[OBS,set(PLACED)-OBS]))
