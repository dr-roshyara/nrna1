import sys
sys.path.insert(0, "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/chapter_01/alm")
sys.dont_write_bytecode=True
from alm import *
PERF=frozenset({("PERFORM","s"),("ADOPT","s")})
ALL=frozenset({(k,"*") for k in ["PERFORM","ADOPT","END_CLAIM","GRANT","REVOKE","CONFIRM"]})
C=Model(kappa="cascade")
# CE1 cascade uses ANY grant, not the authorizing one
H=[Act("g1","R","GRANT",1,holder="b",scope=PERF,period=(1,3)),Act("a","b","ADOPT",2,subject="s"),
   Act("g2","R","GRANT",4,holder="b",scope=PERF,period=(4,INF))]
print("CE1 cascade current at 5 though authorizing g1 expired:", C.current(H,"a",5))
# CE2 END_CLAIM given BEFORE adoption ends the claim
H=[Act("x","R","END_CLAIM",1,target="a"),Act("a","R","ADOPT",2,subject="s")]
print("CE2 claim current at 3 (END_CLAIM precedes ADOPT):", Model().current(H,"a",3))
# CE3 upstream revocation never cascades to delegated grant, even under kappa=cascade
H=[Act("gRa","R","GRANT",1,holder="a",scope=ALL),Act("gab","a","GRANT",2,holder="b",scope=PERF),
   Act("r","R","REVOKE",3,target="gRa"),Act("pb","b","PERFORM",4,subject="s"),Act("ab","b","ADOPT",2,subject="s")]
print("CE3 pb valid after upstream revoke:", C.validity(H)["pb"], " ab current@5 under cascade:", C.current(H,"ab",5))
# CE4 target-specific REVOKE scope (spec: (REVOKE,target)) never covers in code
H=[Act("g","R","GRANT",1,holder="b",scope=PERF),Act("gc","R","GRANT",1,holder="c",scope=frozenset({("REVOKE","g")})),
   Act("r","c","REVOKE",2,target="g"),Act("w","b","PERFORM",3,subject="s")]
v=Model().validity(H); print("CE4 revoke by c with scope (REVOKE,g) valid:", v["r"], " w valid:", v["w"])
# CE5 bars: one authorizing grant bars, one does not
H=[Act("g1","R","GRANT",1,holder="p",scope=frozenset({("PERFORM","verify")}),bars=frozenset({("iv","p")})),
   Act("g2","R","GRANT",1,holder="p",scope=frozenset({("PERFORM","verify")})),Act("v","p","PERFORM",2,subject="verify")]
print("CE5 eligible with one barring grant + one non-barring:", Model().eligible(H,"v","iv"))
# CE6 lambda=inf: even a fully recorded root act is UNDETERMINED
H=[Act("e","R","PERFORM",1,subject="s")]
print("CE6 root act recognized at lam=inf:", Model().recognize_valid(H,{"e":1},1,"e",now=10**9,lam=INF))
# CE7 Theorem 3 broken in code: END_CLAIM coverage reads a field of a later/unrecorded target
H=[Act("g","R","GRANT",1,holder="b",scope=frozenset({("END_CLAIM","s")})),Act("x","b","END_CLAIM",2,target="a"),
   Act("a","R","ADOPT",3,subject="s")]
pos={"g":1,"x":2}
print("CE7 Valid(x) in H:", Model().validity(H)["x"], " Rec_k(x) with Comp(k,tau(x)) true:", Model().recognize_valid(H,pos,2,"x",now=100,lam=0))
# CE8 same-tau ordering: list order of H decides; permuting the input list flips validity
g=Act("g","R","GRANT",1,holder="b",scope=PERF); r=Act("r","R","REVOKE",2,target="g"); w=Act("w","b","PERFORM",2,subject="s")
print("CE8 w valid [g,r,w]:", Model().validity([g,r,w])["w"], " [g,w,r]:", Model().validity([g,w,r])["w"])
# CE9 Recognition order: records list order is H list order, not pos -> recognizer needs out-of-record info
# CE10 well-formedness from>=tau not enforced
H=[Act("g","R","GRANT",5,holder="b",scope=PERF,period=(0,INF)),Act("w","b","PERFORM",6,subject="s")]
print("CE10 ill-formed grant accepted, w valid:", Model().validity(H)["w"])
# CE11 Theorem 6 'positive negative evidence': recorded revocation does not license NOT_AUTHORIZED
H=[Act("g","R","GRANT",1,holder="b",scope=PERF),Act("r","R","REVOKE",2,target="g"),Act("w","b","PERFORM",3,subject="s")]
late=Act("g2","R","GRANT",1,holder="b",scope=PERF)
print("CE11 w valid w/ recorded revoke:",Model().validity(H)["w"]," after late root grant:",Model().validity(H+[late])["w"])
# CE12 half_open vs closed on claim end boundary
H=[Act("a","R","ADOPT",1,subject="s",claim_period=(1,3))]
print("CE12 current at 3 closed/half_open:", Model().current(H,"a",3), Model(iota="half_open").current(H,"a",3))
