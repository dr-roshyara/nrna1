"""Empirical corpus: >=36 cases, stratified, >=20% negative/boundary.
REAL cases are drawn from the running EKP; SYNTHETIC cases cover classes the EKP cannot express."""
import re,glob,os
from kosmodel import *
EKP="/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledge"
def clean(v): return re.sub(r'^\[|\]$','',re.sub(r'#.*$','',v).strip().strip('"\'')).strip()
def load_ekp():
    out=[]
    for p in glob.glob(EKP+"/**/*.md",recursive=True):
        if "/archive/" in p or "scaffold" in p: continue
        tx=open(p,encoding="utf-8",errors="ignore").read()
        m=re.match(r'^---\n(.*?)\n---',tx,re.S)
        if not m: continue
        d={}
        for ln in m.group(1).split("\n"):
            if re.match(r'^[A-Za-z_]+\s*:',ln):
                k,_,v=ln.partition(":"); d[k.strip()]=clean(v)
        if d.get("knowledge_id"): d["_path"]=p; out.append(d)
    return out
EKPDOCS=load_ekp()
D_STATUS=Dimension("status",("draft","discovery","reviewed","approved","baseline","frozen","superseded","archived"),"ordinal")
D_AUTH  =Dimension("authority",("authoritative","derived","generated","historical","provisional"),"ordinal")
D_VER   =Dimension("Version",("3.68","3.69","3.70","3.71"),"ordinal")
def ekp_assertion(d,dim):
    v=d.get(dim.name)
    o=Observation("ekp-frontmatter","2026-08-30",f"{d['knowledge_id']}:{dim.name}={v}")
    e=Evidence(o,"supports","active","ekp-frontmatter","direct","ekp-frontmatter")
    return Assertion(Prop(d["knowledge_id"],dim,v),frozenset({e}),"global",Iv("2026-08-30",None),"ekp-frontmatter")

CASES=[]
def case(cid,cls,kind,real,payload): CASES.append(dict(id=cid,cls=cls,kind=kind,real=real,**payload))

# --- CLASS 1 ordinary assertion (REAL x3) ---
for i,d in enumerate(EKPDOCS[:3]):
    case(f"C01.{i+1}","ordinary","positive",True,{"a":ekp_assertion(d,D_STATUS),"doc":d["knowledge_id"]})
# --- CLASS 2 evidence-supported (REAL x3: docs with authoritative authority) ---
auth=[d for d in EKPDOCS if d.get("authority")=="authoritative"][:3]
for i,d in enumerate(auth):
    case(f"C02.{i+1}","evidence-supported","positive",True,{"a":ekp_assertion(d,D_AUTH),"doc":d["knowledge_id"]})
# --- CLASS 3 evidence-refuted (SYNTHETIC x3 — EKP has no refutation) ---
for i,v in enumerate(("3.68","3.70","3.71")):
    o=Observation("probe","2026-02-0%d"%(i+1),"not v=%s"%v)
    e=Evidence(o,"contradicts","active","probe","direct","probe")
    case(f"C03.{i+1}","evidence-refuted","positive",False,
         {"a":Assertion(Prop("Nexus",D_VER,v),frozenset({e}),"prod",Iv("2026-02-01",None),"probe")})
# --- CLASS 4 unknown / missing (3: 1 REAL-derived, 2 SYNTHETIC negative) ---
case("C04.1","unknown","negative",False,{"a":Assertion(Prop("Nexus",D_VER,"3.69"),frozenset(),"prod",Iv("2026-01-01",None),"none")})
case("C04.2","unknown","negative",False,{"never_asserted":("Nexus",D_VER,"3.71")})
case("C04.3","unknown","negative",True,{"orphan":[d["knowledge_id"] for d in EKPDOCS][:1]})
# --- CLASS 5 contradictory (SYNTHETIC x3) ---
for i,(v1,v2) in enumerate((("3.69","3.70"),("3.68","3.71"),("3.70","3.71"))):
    mk=lambda v,s: Assertion(Prop("Nexus",D_VER,v),frozenset({Evidence(Observation(s,"2026-01-01","v="+v),"supports","active",s,"direct",s)}),"prod",Iv("2026-01-01",None),s)
    case(f"C05.{i+1}","contradictory","negative",False,{"a1":mk(v1,"srcA"),"a2":mk(v2,"srcB")})
# --- CLASS 6 supersession (SYNTHETIC x2 + REAL x1) ---
for i,(v1,v2) in enumerate((("3.68","3.69"),("3.69","3.70"))):
    mk=lambda v,vf: Assertion(Prop("Nexus",D_VER,v),frozenset({Evidence(Observation("api",vf,"v="+v),"supports","active","api","direct","api")}),"prod",Iv(vf,None),"api")
    case(f"C06.{i+1}","supersession","positive",False,{"old":mk(v1,"2026-01-01"),"new":mk(v2,"2026-06-01")})
sup=[d for d in EKPDOCS if d.get("status")=="superseded"]
case("C06.3","supersession","positive",True,{"ekp_superseded":len(sup),"note":"real EKP superseded docs"})
# --- CLASS 7 historical replay (3) ---
for i in range(3):
    mk=lambda v,vf: Assertion(Prop("Nexus",D_VER,v),frozenset({Evidence(Observation("api",vf,"v="+v),"supports","active","api","direct","api")}),"prod",Iv(vf,None),"api")
    case(f"C07.{i+1}","replay","positive",False,{"H":[("assert",mk("3.68","2026-01-01")),("assert",mk("3.69","2026-03-01"))][:i+1] or [("assert",mk("3.68","2026-01-01"))]})
# --- CLASS 8 policy-controlled (3: 2 positive 1 negative) ---
case("C08.1","policy-controlled","positive",False,{"gates":{"WellFormed":True,"Evidence":True}})
case("C08.2","policy-controlled","positive",False,{"gates":{"WellFormed":True,"Evidence":True,"TwoSource":True}})
case("C08.3","policy-controlled","negative",False,{"gates":{"WellFormed":True,"Evidence":False}})
# --- CLASS 9 authority-controlled (3: 1 positive 2 negative) ---
case("C09.1","authority-controlled","positive",False,{"scope_ok":True,"juris_ok":True})
case("C09.2","authority-controlled","negative",False,{"scope_ok":False,"juris_ok":True})
case("C09.3","authority-controlled","negative",False,{"none":True})
# --- CLASS 10 policy change (3) ---
case("C10.1","policy-change","positive",False,{"v1":"v1","v2":"v2","authorised":True})
case("C10.2","policy-change","negative",False,{"v1":"v1","v2":"v2","authorised":False})
case("C10.3","policy-change","boundary",False,{"expired":True})
# --- CLASS 11 conditional decision (3) ---
for i,s in enumerate(("WAIT","REQUEST","BLOCK")):
    case(f"C11.{i+1}","conditional","positive" if s!="BLOCK" else "negative",False,{"strategy":s})
# --- CLASS 12 measurement / quantitative (3, incl 1 malformed) ---
case("C12.1","measurement","positive",False,{"scale":"ordinal","op":"compare"})
case("C12.2","measurement","negative",False,{"scale":"ordinal","op":"mean"})
case("C12.3","measurement","negative",False,{"scale":"nominal","op":"order"})
