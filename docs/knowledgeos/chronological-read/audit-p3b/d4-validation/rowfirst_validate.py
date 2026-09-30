import sys,json,re,math,collections
sys.path.insert(0,"scripts")
import p3b_s5_prepare as P, p3b_s5_pilot as PL, p3b_ob0018_pilot as ob, p3b_s5_common as c
ctx=P.Context(); size=ctx.size; fm=c.files_meta()
def req(lab):
    s2=set() if lab in ctx.hubs else {s for r in ([ctx.hits[lab]] if lab in ctx.hits else [])+ctx.dims.get(lab,[]) if r["record"]=="LABEL-HITS" for s in list(r.get("raw_hits") or {})+list(r.get("ledger_hits") or {})}
    rows={s["source_id"] for s in ctx.bi[lab]["sources"]}
    return {f for f in s2|rows if f in size},{f for f in rows if f in size}
def seq_nextfit(items,B):
    units=[[]]
    for s in items:
        if units[-1] and sum(size[x] for x in units[-1])+size[s]>B: units.append([])
        units[-1].append(s)
    return [u for u in units if u]
def ffd_into(units,items,B):
    for s in sorted(items,key=lambda x:(-size[x],x)):
        for u in units:
            if sum(size[x] for x in u)+size[s]<=B: u.append(s); break
        else: units.append([s])
    return units
def strat(name,files,rows,B):
    if name=="A": return PL.partition(sorted(files),size,budget=B)
    if name=="B": return seq_nextfit(sorted(rows),B)+ffd_into([],files-rows,B)          # pure row-first: remainder in own units
    if name=="C": return ffd_into(seq_nextfit(sorted(rows),B),files-rows,B)            # row-first + FFD fill (published)
    if name=="D": return seq_nextfit(sorted(files),B)                                   # sequential next-fit, S-id order
# pair evidence per label
pairs_by_lab=collections.defaultdict(list)
for p in c.discovery_pairs():
    ev=set(re.findall(r"S\d{4}",json.dumps(p.get("what_says_this"))+json.dumps(p.get("basis"))))
    for lab in (p["a"],p["b"]): pairs_by_lab[lab].append((p["pair_id"],ev))
labs=sorted(ctx.plan)
R={}
# shared row sources (label-local partition => no cross-label placement coupling)
rowlabs=collections.defaultdict(set)
for lab in labs:
    for s in req(lab)[1]: rowlabs[s].add(lab)
shared={s:v for s,v in rowlabs.items() if len(v)>1}
R["shared_row_sources"]={"files":len(shared),"labels_affected":len(set().union(*shared.values())) if shared else 0,
  "max_labels_per_file":max(len(v) for v in shared.values()) if shared else 0}
# chronology proxy: S-id order vs best_historical_date for row sources
inv=tot=labs_inv=0; dated=0; undated=set()
for lab in labs:
    rows=sorted(req(lab)[1]); d=[]
    for s in rows:
        m=re.match(r"(\d{4}-\d{2}-\d{2})",str(fm.get(s,{}).get("best_historical_date") or ""))
        if m: d.append((s,m.group(1)))
        else: undated.add(s)
    dated+=len(d); li=0
    for i in range(len(d)):
        for j in range(i+1,len(d)):
            tot+=1
            if d[i][1]>d[j][1]: inv+=1; li+=1
    labs_inv+=li>0
R["chronology_proxy"]={"row_sources_without_iso_date":len(undated),"row_source_pairs_compared":tot,"sid_order_vs_date_inversions":inv,"labels_with_inversion":labs_inv}
rb=sorted(sum(size[s] for s in req(l)[1]) for l in labs)
q=lambda v,p: v[min(len(v)-1,int(p*len(v)))]
R["row_bytes"]={"median":q(rb,.5),"p90":q(rb,.9),"p99":q(rb,.99),"max":rb[-1]}
for B in (300_000,600_000):
    out={}; lower=0; resp=[]
    for lab in labs:
        rows=req(lab)[1]; rbytes=sum(size[s] for s in rows)
        if rows: lower+=max(0,math.ceil(rbytes/B)-1)
    for name in "ABCD":
        U=RA=LA=BD=MX=PS=NEWPS=CHV=0; multi=0
        for lab in labs:
            files,rows=req(lab)
            if not files: continue
            units=strat(name,files,rows,B); uo={s:i for i,u in enumerate(units) for s in u}
            base={s:i for i,u in enumerate(strat("A",files,rows,B)) for s in u}
            adj=sum(1 for a,b in zip(sorted(rows),sorted(rows)[1:]) if uo[a]!=uo[b])
            U+=len(units); BD+=len(units)-1; multi+=len(units)>1; RA+=adj; LA+=adj>0; MX=max(MX,max(sum(size[x] for x in u) for u in units))
            if name=="C" and adj: resp.append((lab,len(rows),sum(size[s] for s in rows)))
            seqr=[uo[s] for s in sorted(rows)]
            # chronology: does unit index ever decrease along S-id order of row sources? (order of units, not evidence order)
            CHV+=any(b<a for a,b in zip(seqr,seqr[1:]))
            for pid,ev in pairs_by_lab.get(lab,[]):
                e=[s for s in ev if s in uo]
                if len({uo[s] for s in e})>1:
                    PS+=1
                    if len({base[s] for s in e})<=1: NEWPS+=1
        out[name]={"units":U,"labels_multi_unit":multi,"unit_boundaries":BD,"row_adjacencies":RA,"labels_with_row_adjacency":LA,
                   "max_unit_bytes":MX,"pair_evidence_split":PS,"pair_splits_new_vs_FFD":NEWPS,"labels_row_unit_order_nonmonotone":CHV}
    out["row_adjacency_lower_bound"]=lower
    out["labels_row_sources_fit_one_unit"]=sum(1 for l in labs if req(l)[1] and sum(size[s] for s in req(l)[1])<=B)
    out["labels_needing_multiple_row_units"]=sum(1 for l in labs if sum(size[s] for s in req(l)[1])>B)
    out["C_residual_labels"]=sorted(resp,key=lambda x:-x[2])
    R[B]=out
print(json.dumps(R,indent=1,default=str))
