# Encoding of corrected Ch.1 §1.14.1 (strong Kleene; least fixed point; bitemporal; parameters). Test aid only.
T,F,U=True,False,None
INF=10**9
def AND(*v):
    if any(x is F for x in v): return F
    if any(x is U for x in v): return U
    return T
def OR(*v):
    if any(x is T for x in v): return T
    if any(x is U for x in v): return U
    return F
def NOT(x): return U if x is U else (not x)
class W:  # world
    def __init__(s,grants,acts,par):
        s.g={g['id']:g for g in grants}; s.a={a['id']:a for a in acts}; s.p=par
        s.order=sorted(acts,key=lambda a:(a['t'],a['s']))
    def visible(s,e,x):            # ρ: may act x's judgement see entry e?
        return True if s.p['rho']=='now' else e['s']<x['s']
    def effect(s,v):               # υ: how an undetermined ending counts
        return v if s.p['ups']=='undetermined' else (T if v is T else F)
    def window(s,gid,t,x,val):
        g=s.g.get(gid)
        if g is None: return U
        if g.get('end') is None and not g.get('open'):
            base = U if s.p['pi']=='undetermined' else (T if g['start']<=t else F)
        else:
            base = T if g['start']<=t<(g['end'] if g.get('end') is not None else INF) else F
        ended=[]
        for e in s.a.values():
            if e['obj']!=gid or e['act'] not in('end-grant','annul') or not s.visible(e,x) or e.get('cause')==x['id'] or e['id']==x['id']: continue
            v=s.effect(val[e['id']])
            if e['act']=='annul':
                te=e.get('eff',e['t'])
                if te<=t: ended.append(v)
            elif e.get('kind')=='suspension':
                if e['t']<=t<e['until']: ended.append(v)
            elif e.get('eff',e['t'])<=t: ended.append(v)
        return AND(base,NOT(OR(*ended))) if ended else base
    def covers(s,B,x):
        gs=[s.g[b] for b in B]; A=x['actors']
        if not all(g['holder'] in A and x['act'] in g['acts'] for g in gs): return F
        if not all(any(g['holder']==a for g in gs) for a in A): return F
        if s.p['chi']=='each': return T if all(x['scope']<=g['scope'] for g in gs) else F
        return T if x['scope']<=set().union(*[g['scope'] for g in gs]) else F
    def auth_via(s,x,B,val):
        return AND(*(s.window(b,x['t'],x,val) for b in B), s.covers(B,x))
    def auth(s,x,val):
        if x.get('root'): return T
        c=x.get('cited')
        if c is None: return F if s.p['kappa']=='constitutive' else U
        if c==[]: return F
        return OR(*(s.auth_via(x,B,val) for B in c))
    def fixpoint(s):
        val={k:(T if a.get('root') else U) for k,a in s.a.items()}
        for _ in range(200):
            new={k:s.auth(a,val) for k,a in s.a.items()}
            # least fixed point by Kleene iteration from bottom: only allow U->T/F changes
            new={k:(val[k] if val[k] is not U else new[k]) for k in new}
            if new==val: return val
            val=new
        return val
    def annulled(s,x,t,val):
        vs=[s.effect(val[e['id']]) for e in s.a.values() if e['act']=='annul' and e['obj']==x['id'] and e.get('eff',e['t'])<=t]
        return OR(*vs) if vs else F
    def current(s,c,t,val,depth=0):
        ad=[x for x in s.order if x['act']=='adopt' and x['obj']==c and x['t']<=t]
        if not ad: return (F,['never adopted']) if s.p['C1']=='constitutes' else (U,['no adoption on record'])
        res=[];reasons=[]
        for x in ad:
            bases=x.get('cited')
            if x.get('root'): bases=[[]]
            if bases is None: bases=[None]
            if bases==[]: bases=[[]]
            for B in bases:
                if B is None: c1=F if s.p['kappa']=='constitutive' else U
                elif B==[] and not x.get('root'): c1=F
                elif x.get('root'): c1=T
                else: c1=s.auth_via(x,B,val)
                c1=AND(c1,NOT(s.annulled(x,t,val)))
                lo,hi=x.get('val',(0,INF)); c2=T if lo<=t<hi else F
                rv=[]
                for y in s.order:
                    if y['obj']!=c or y['act'] not in('revoke','supersede'): continue
                    if (y['t'],y['s'])<=(x['t'],x['s']) or y.get('eff',y['t'])>t: continue
                    v=s.effect(val[y['id']])
                    if y['act']=='supersede' and s.p['sigma']=='conditional' and depth<3:
                        v=AND(v,s.current(y['succ'],t,val,depth+1)[0])
                    rv.append((y['act'],v))
                c3=NOT(OR(*[v for _,v in rv])) if rv else T
                c4=T
                if B:
                    P=s.p['P']; parts=[]
                    for b in B:
                        for e in s.a.values():
                            if e['obj']!=b or e['act'] not in('end-grant','annul'): continue
                            k=e.get('kind','annulment')
                            if k=='suspension':
                                if 'suspension' in P and e['t']<=t<e['until']: parts.append(s.effect(val[e['id']]))
                                continue
                            te=e.get('eff',e['t']) if s.visible(e,x) else max(e.get('eff',e['t']),e['t'])
                            if k in P and x['t']<te<=t and e.get('cause')!=x['id']:
                                parts.append(s.effect(val[e['id']]))
                    c4=NOT(OR(*parts)) if parts else T
                r=AND(c1,c2,c3,c4); res.append(r)
                if r is not T:
                    if c1 is F: reasons.append('not validly made')
                    for a,v in rv:
                        if v is T: reasons.append('revoked' if a=='revoke' else 'superseded')
                    if c4 is F: reasons.append('authority ended')
                    if c2 is F: reasons.append('no longer valid' if t>=hi else 'not yet valid')
        v=OR(*res)
        return v,reasons
def headline(v,reasons):
    if v is T: return 'current'
    if v is U: return 'cannot determine'
    order=['never adopted','not validly made','revoked','superseded','authority ended','suspended','no longer valid','not yet valid']
    for o in order:
        if o in reasons: return o
    return 'not current'
