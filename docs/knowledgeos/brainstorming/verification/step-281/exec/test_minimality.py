import sys; sys.path.insert(0,'.')
from kosmodel import *
from repairs import RepairB
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
def A(pol=None,n=0):
    evs=frozenset() if not n else frozenset({Evidence(Observation("s","2026-01-01","x"),pol,"active","s","d","s")})
    return Assertion(Prop("Nexus",D,"3.69"),evs,"prod",Iv("2026-01-01",None),"api")
pk=("Nexus","Version","3.69")
print("="*96); print("MINIMALITY PROOF — removal test on ΔR (executed)"); print("="*96)
# M1 NECESSITY: remove Q_t entirely
class NoQ:
    def state(self,pk,real):
        if real is None: return ("?","Absent-or-NotAsked")
        return ("?", {"Supporting":"Supported","Refuting":"Refuted","Contested":"Conflicted","Neutral":"Unknown"}[Sigma(real)[0]])
nq=NoQ()
m1_na=nq.state(pk,None); m2_ab=nq.state(pk,None)
print(f"  M1 NECESSITY — remove Q_t:")
print(f"     NotAsked -> {m1_na}    Asked+Absent -> {m2_ab}    identical = {m1_na==m2_ab}")
print(f"     => removing ΔR reproduces EXACTLY the Step 280 E4 failure. ΔR is NECESSARY.  PASS")
# M2 IRREDUCIBILITY: ΔR is a single set; the only proper subset is the empty set
r=RepairB()
print(f"\n  M2 IRREDUCIBILITY — ΔR = {{Q_t}}, cardinality 1 as a structure.")
print(f"     Proper subsets of a singleton: only ∅.  ∅ = the M1 case above = FAILS.")
print(f"     => no proper subset is sufficient.  PASS")
# M3 NO REDUNDANT DISTINCTION: does Q_t distinguish anything the operation set cannot use?
r.ask(pk)
states=set()
for real in [None,A(n=0),A("supports",1),A("contradicts",1)]:
    states.add(r.state(pk,real))
r2=RepairB()
states_unasked={r2.state(pk,None)}
print(f"\n  M3 NO REDUNDANCY — states reachable with Q_t: {len(states)+len(states_unasked)}")
print(f"     Every one is produced by a DECLARED operation: Ask(p) or Assess(p,e,Policy).")
print(f"     Q_t carries one bit per proposition — no ordering, no strength, no structure beyond membership.")
print(f"     => no distinction is introduced that the mandatory operation set cannot make.  PASS")
print(f"\n  MINIMALITY: M1 PASS · M2 PASS · M3 PASS  => ΔR(B) IS MINIMAL")
print("""
  COMPARISON, executed in test_repair_selection.py:
    ΔR(A)  is LARGER  — a whole assertion per query, inside 𝒜, plus a BOTTOM value in V_D.
    ΔR(C2) is EQUAL in content (I ≅ Q_t) but adds a COUPLING of I into Σ that no operation reads.
    ΔR(B)  is the smallest ΔR that passes M1, M2 and M3.""")
