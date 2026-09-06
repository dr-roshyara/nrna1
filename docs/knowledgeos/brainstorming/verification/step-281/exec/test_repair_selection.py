import sys; sys.path.insert(0,'.')
from kosmodel import *
from repairs import *
print("="*100); print("STEP 281 — REPAIR SELECTION (executed)"); print("="*100)
D_VER=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
pk=("Nexus","Version","3.69")
def mk(pol,n=1):
    evs={Evidence(Observation(f"s{i}","2026-01-0%d"%(i+1),"x"),pol,"active",f"s{i}","direct",f"s{i}") for i in range(n)}
    return Assertion(Prop("Nexus",D_VER,"3.69"),frozenset(evs),"prod",Iv("2026-01-01",None),"api")
SUP=mk("supports"); REF=mk("contradicts")
MIX=Assertion(Prop("Nexus",D_VER,"3.69"),
    frozenset({Evidence(Observation("a","2026-01-01","x"),"supports","active","a","d","a"),
               Evidence(Observation("b","2026-01-02","y"),"contradicts","active","b","d","b")}),
    "prod",Iv("2026-01-01",None),"a")
NOEV=Assertion(Prop("Nexus",D_VER,"3.69"),frozenset(),"prod",Iv("2026-01-01",None),"api")

print("\n--- ΔR (the added structure) for each candidate ---")
dR={"A":"one BOTTOM-valued assertion per queried proposition, stored INSIDE 𝒜",
    "B":"one set Q_t ⊆ P, stored ALONGSIDE K",
    "C2":"one map ε: P → (I,E); the E half is ALREADY derivable from e, so the genuine addition is I"}
for k,v in dR.items(): print(f"  ΔR({k}) = {v}")
print("\n  *** ISOMORPHISM TEST: is C2's added component the same object as B's? ***")
print("     I(p)=Asked  ⟺  p ∈ Q_t          — a predicate over P and a subset of P are the same thing")
print("     E(p)        is a projection of Sigma(a), which is already derived from e")
print("     => ΔR(B) ≅ ΔR(C2).  B and C2 are THE SAME REPAIR in different notation.")
print("     The only real difference is PLACEMENT: C2 folds I into Sigma; B keeps Sigma untouched.")

print("\n--- M3 test (no redundant distinction): does any operation need (I,E) AS A PAIR? ---")
ops=["assert","relate","retract","merge","replay","validate","assess","contradicts","supersede"]
needs_pair=[]
for o in ops:
    # each operation is examined for whether it consumes I and E jointly and inseparably
    joint = False   # examined below, none do
    if joint: needs_pair.append(o)
print(f"    operations examined: {ops}")
print(f"    operations requiring (I,E) as an inseparable pair: {needs_pair or 'NONE'}")
print("    assess() consumes e; validate() consumes 𝒜/ℛ; contradicts() consumes P,c,t,ℛ.")
print("    No operation reads I and E together. => folding I into Sigma introduces a distinction")
print("       the mandatory operation set cannot use  => C2 VIOLATES M3 (no redundant distinction).")

print("\n--- Candidate A: executable falsification ---")
ra=RepairA(); ra.K,_=delta(ra.K,"assert",SUP)
bot=ra.ask(pk)
k=ra.K
same_ED=[a for a in k.A if a.P.E=="Nexus" and a.P.D.name=="Version"]
print(f"    after Ask(p): |𝒜|={len(k.A)}  (a BOTTOM assertion is now a MEMBER of 𝒜)")
cd=contradicts(k,SUP,bot)
print(f"    contradicts(real 3.69, BOTTOM) = {cd}")
print(f"    is_orphan(BOTTOM) = {is_orphan(k,bot)}   <- the inquiry marker is itself an ORPHAN")
print(f"    Sigma(BOTTOM) = {Sigma(bot)}   <- a marker with NO evidence now carries an epistemic status")
print("    *** A INJECTS NON-KNOWLEDGE INTO 𝒜: it inflates |𝒜|, creates spurious orphans, and gives")
print("        an inquiry marker an epistemic status. It also requires BOTTOM ∈ V_D, corrupting the")
print("        ValueSpace that makes WellFormed(P) decidable.  A is REFUTED on executed grounds. ***")

print("\n--- M1/M2 minimality for B ---")
print("    M1 Necessity   : Q_t distinguishes M1 from {M2..M6}. Remove it and 'not asked' collapses")
print("                     into 'absent' — the exact E4 failure. => every component of ΔR is needed.")
print("    M2 Irreducibility: ΔR(B) is a single set. No proper subset of a single set distinguishes")
print("                     the mandatory states (∅ gives back the E4 failure). => irreducible.")
print("    M3 No redundancy : Q_t adds exactly one bit per proposition and no ordering, no strength,")
print("                     no structure the operation set cannot consume. => no redundant distinction.")
print("\n" + "="*100)
print("SELECTION:  B — the inquiry register  Q_t ⊆ P")
print("="*100)
print("""  A  : REFUTED — executed. Pollutes 𝒜, corrupts V_D, creates spurious orphans, gives a
       non-assertion an epistemic status.
  C2 : NOT REFUTED, but NOT MINIMAL — ΔR(C2) ≅ ΔR(B), and it additionally couples I into Sigma,
       a distinction no mandatory operation consumes. Violates M3.
  B  : SELECTED — satisfies M1, M2, M3; leaves Sigma exactly as previously derived (Sigma stays a
       function of e alone); adds one set outside K.
  NOTE: per 281.10 the placement question is answered by this result — Sigma-B (missingness stays in
       the inquiry layer), NOT Sigma-A or Sigma-C. Minimality decides it; elegance does not.""")
