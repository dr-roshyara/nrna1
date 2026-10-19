import sys; sys.path.insert(0, sys.argv[1])
from alm_r1 import *
from oracle_r1 import Oracle, to_dict, theta_dict
ROOTSET = frozenset({("R", ("*", "*"))})
PERF = frozenset({("PERFORM", ("implement", "w")), ("ADOPT", "s")})
def th(**o):
    d = dict(C1="b", RD1={"decision": "persist"}, delta="independent", BASIS="any", C10="given",
             iota_g="closed", iota_c="closed", C6="off", BAR="any"); d.update(o); return Theta(root=ROOTSET, **d)
INFI=INF
print("== N1 ord-domain: unrecorded ord of a REVOKE can never be placed before recorded same-instant acts")
g=Act("g","GRANT","R",tau=1,ord=0,grantee="b",scope=PERF)
e=Act("e","PERFORM","b",performer="p1",tau=2,ord=0,activity="implement",work="w")
f=Act("f","PERFORM","b",performer="p1",tau=2,ord=1,activity="implement",work="w")
r=Act("r","REVOKE","R",tau=2,ord=None,target="g")
E=[Entry(1,g),Entry(2,e),Entry(3,f),Entry(4,r)]
print(" Rec_4(valid e) =",rec(E,4,("valid","e"),th(),100,0,INFI), "| true world with ord(r)=-1:",History([g,e,f,replace(r,ord=-1)],th()).valid("e"))
# simpler: recorded ords large
e2=replace(e,ord=10); r2=replace(r,ord=None)
E=[Entry(1,g),Entry(2,e2),Entry(3,r2)]
print(" with ord(e)=10, ord(r)=⊥: Rec =",rec(E,3,("valid","e"),th(),100,0,INFI), "| world ord(r)=11:",History([g,e2,replace(r,ord=11)],th()).valid("e"))
print("== N2 ⊥ holder on a recorded REVOKE is treated as a definite non-holder (RD6 υ decided)")
rN=Act("r","REVOKE",None,tau=2,ord=0,target="g"); e3=Act("e","PERFORM","b",performer="p1",tau=3,ord=0,activity="implement",work="w")
E=[Entry(1,g),Entry(2,rN),Entry(3,e3)]
print(" Rec(valid e) =",rec(E,3,("valid","e"),th(),100,0,INFI),"| world holder=R:",History([g,replace(rN,holder="R"),e3],th()).valid("e"))
xN=Act("x","END","R",tau=2,ord=0,target=None); a=Act("a","ADOPT","R",tau=1,ord=0,subject="s")
E=[Entry(1,a),Entry(2,xN)]
print(" END with ⊥ target: Rec(current a,3) =",rec(E,2,("current","a",3),th(),100,0,INFI))
print("== N3 recorded REVOKE by holder with no recorded authority -> T under r1.1 (closed world over authority)")
rc=Act("r","REVOKE","c",tau=2,ord=0,target="g")
E=[Entry(1,g),Entry(2,rc),Entry(3,e3)]
print(" Rec(valid e) =",rec(E,3,("valid","e"),th(),100,0,INFI))
xc=Act("x","END","c",tau=2,ord=0,target="a")
E=[Entry(1,a),Entry(2,xc)]
print(" END by c (authority unrecorded): Rec(current a,3) =",rec(E,2,("current","a",3),th(),100,0,INFI))
print("== N4 Auth conflated with Form: anachronistic grant by root")
gA=Act("gA","GRANT","R",tau=3,ord=0,grantee="b",scope=PERF,period=(1,9))
H=History([gA],th()); print(" form",H.form(gA),"auth(code)",H.auth("gA"),"spec: Basis∋ROOT so Auth=T")
print(" C1=a, rho(e)=⊥: auth(code)=",History([Act("p","PERFORM","R",tau=1,ord=0,activity="implement",work="w")],th(C1="a"),{}).auth("p"))
print("== N5 C1=a: claim current before the adopting act is recorded; END needs record, ADOPT does not")
a1=Act("a","ADOPT","R",tau=1,ord=0,subject="s")
H=History([a1],th(C1="a"),{"a":50}); print(" current(a,2) with rho(a)=50:",H.current("a",2))
x1=Act("x","END","R",tau=2,ord=0,target="a")
H=History([a1,x1],th(C1="a"),{"a":1,"x":50}); print(" END at 2 recorded at 50: current(a,3):",H.current("a",3))
print("== N6 oracle vs impl, C1=a & C6=later_prevails")
a1=Act("a1","ADOPT","R",tau=1,ord=0,subject="s"); a2=Act("a2","ADOPT","R",tau=2,ord=0,subject="s")
rho={"a1":5,"a2":2}; T6=th(C1="a",C6="later_prevails")
imp=History([a1,a2],T6,rho); O=Oracle([to_dict(a1),to_dict(a2)],theta_dict(T6),rho); V=O.fixed_points()[0]
print(" impl current(a1,6):",imp.current("a1",6)," oracle:",O.current(V,"a1",6))
print("== N7 rec_at_date upper bound from a lower-bound witness")
pe=Act("e","PERFORM","b",performer="p1",tau=3,ord=0,activity="implement",work="w")
rv=Act("r","REVOKE","R",tau=2,ord=0,target="g")
E=[Entry(1,g),Entry(2,pe),Entry(3,rv)]
print(" witnesses {(2,2),(5,3)}, d=3:",rec_at_date(E,3,{(2,2),(5,3)},("valid","e"),th(),lambda k:3,0,INFI),
      "| Rec_3 =",rec(E,3,("valid","e"),th(),3,0,INFI))
print("== N8 BAR=all with ROOT in basis + a barring grant (spec: every non-root g bars -> INELIGIBLE)")
VS=frozenset({("PERFORM",("verify","w"))})
gb=Act("gb","GRANT","R",tau=1,ord=0,grantee="R",scope=VS,bars=frozenset({("iv","pp","*")}))
v=Act("v","PERFORM","R",performer="pp",tau=2,ord=0,activity="verify",work="w")
print(" elig:",History([gb,v],th(BAR="all")).elig("v","iv","w"), "basis",History([gb,v],th(BAR="all")).basis("v"))
print("== N9 IND-2 with ⊥ implementer performer -> ELIGIBLE")
gi=Act("g","GRANT","R",tau=1,ord=0,grantee="h",scope=frozenset({("PERFORM",("implement","w")),("PERFORM",("verify","w"))}))
i=Act("i","PERFORM","h",performer=None,tau=2,ord=0,activity="implement",work="w")
vv=Act("v","PERFORM","h",performer="p1",tau=3,ord=0,activity="verify",work="w")
print(" elig:",History([gi,i,vv],th()).elig("v","independent_verification","w"))
