import sys,json,re,collections
exec(open(sys.argv[1]).read().split("labs=sorted(ctx.plan)")[0])   # reuse definitions (req, seq_nextfit, ffd_into, strat, pairs_by_lab)
labs=sorted(ctx.plan)
def pair_aware(files,rows,B,lab):
    units=seq_nextfit(sorted(rows),B)
    partners=collections.defaultdict(set)
    for _,ev in pairs_by_lab.get(lab,[]):
        e={s for s in ev if s in files}
        for s in e: partners[s]|=e-{s}
    for s in sorted(files-rows,key=lambda x:(-size[x],x)):
        best=None;score=-1
        for i,u in enumerate(units):
            if sum(size[x] for x in u)+size[s]<=B:
                sc=len(partners[s]&set(u))
                if sc>score: best,score=i,sc
        if best is None: units.append([s])
        else: units[best].append(s)
    return units
for B in (300_000,600_000):
    U=RA=PS=NEW=0
    for lab in labs:
        files,rows=req(lab)
        if not files: continue
        units=pair_aware(files,rows,B,lab); uo={s:i for i,u in enumerate(units) for s in u}
        base={s:i for i,u in enumerate(strat("A",files,rows,B)) for s in u}
        U+=len(units); RA+=sum(1 for a,b in zip(sorted(rows),sorted(rows)[1:]) if uo[a]!=uo[b])
        for pid,ev in pairs_by_lab.get(lab,[]):
            e=[s for s in ev if s in uo]
            if len({uo[s] for s in e})>1:
                PS+=1; NEW+= len({base[s] for s in e})<=1
    print(B,{"units":U,"row_adjacencies":RA,"pair_evidence_split":PS,"pair_splits_new_vs_FFD":NEW})
