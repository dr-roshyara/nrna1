import sys; sys.path.insert(0, sys.argv[1])
from alm_r1 import *
from oracle_r1 import Oracle, to_dict, theta_dict
ROOTSET = frozenset({("R", ("*", "*"))})
PERF = frozenset({("PERFORM", ("implement", "w"))})
T=Theta(root=ROOTSET,C1="a",RD1={"decision":"persist"},delta="independent",BASIS="any",C10="given",iota_g="closed",iota_c="closed",C6="off",BAR="any")
g0=Act("g0","GRANT","R",tau=1,ord=0,grantee="a",scope=frozenset({("GRANT",("PERFORM",("implement","w")))}))
g=Act("g","GRANT","a",tau=2,ord=0,grantee="b",scope=PERF)
e=Act("e","PERFORM","b",performer="p1",tau=4,ord=0,activity="implement",work="w")
rho={"g0":3,"g":2,"e":4}
H=[g0,g,e]
imp=History(H,T,rho); O=Oracle([to_dict(x) for x in H],theta_dict(T),rho).fixed_points()[0]
print("recursive reading impl:",imp.valid("e"),"oracle:",O["e"])
# literal: evaluate Valid_b on H^a_e = {f: rho(f)<=tau(e)} u {e}
Tb=Theta(**{**T.__dict__,"C1":"b"})
Hae=[x for x in H if rho[x.id]<=e.tau]
print("literal 'V on H^a_e' reading (C1=b eval on H^a_e):",History(Hae,Tb).valid("e"))
