#!/usr/bin/env python3
"""
EXPERIMENTS 4-7 — Measurement theory, executed.

Reference standard: Roberts, *Measurement Theory* (in the corpus at
kernel/20260825-184234) -- a numerical statement is MEANINGFUL iff its truth
value is invariant under every ADMISSIBLE transformation of the scale.

  nominal  : any bijection
  ordinal  : any strictly increasing f
  interval : f(x)=ax+b, a>0
  ratio    : f(x)=ax,   a>0

The corpus states this correctly (Step 264 s264.7-264.10, s264.23 'no averaging
of ordinal epistemic strength'). These experiments test whether the FORMULAS the
corpus actually proposes obey it.
"""
import math, itertools, random
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

# ------------------------------------------------------------------- EXP-4
hr("EXP-4  Is the epistemic-status ladder safe under averaging?  (Step 264 s264.23)")
LADDER = ["Candidate", "Supported", "Accepted"]      # Step 264 / ladder_dc_reference.py
enc_A  = {"Candidate":1, "Supported":2, "Accepted":3}          # admissible
enc_B  = {"Candidate":1, "Supported":2, "Accepted":10}         # admissible (strictly increasing)
enc_C  = {"Candidate":0, "Supported":5, "Accepted":6}          # admissible

portfolio = ["Candidate", "Candidate", "Accepted"]
print(f"  assertions: {portfolio}")
print(f"  rule under test:  mean(sigma) >= 2  ->  PROCEED")
verdicts = {}
for nm, enc in (("A",enc_A), ("B",enc_B), ("C",enc_C)):
    m = sum(enc[s] for s in portfolio)/len(portfolio)
    verdicts[nm] = (round(m,3), m >= 2)
    print(f"    encoding {nm} {enc}:  mean={m:.3f}  PROCEED={m>=2}")
flips = len({v[1] for v in verdicts.values()}) > 1
print(f"\n  decision flips across admissible re-encodings? {flips}")
print("  => MEAN over an ordinal status ladder is MEANINGLESS (Roberts). CONFIRMED by execution.")

print("\n  Controls -- operations that ARE ordinal-admissible:")
for op_name, op in (("min", min), ("max", max)):
    outs = {nm: LADDER[[enc[s] for s in LADDER].index(op(enc[s] for s in portfolio))]
            for nm, enc in (("A",enc_A),("B",enc_B),("C",enc_C))}
    print(f"    {op_name}: {outs}  invariant={len(set(outs.values()))==1}")
med = sorted(portfolio, key=lambda s: enc_A[s])[len(portfolio)//2]
print(f"    median: {med}   (rank-based, invariant by construction)")

# ------------------------------------------------------------------- EXP-5
hr("EXP-5  AggregateSupport = sum(s_i) / (1 + log n)   (Step 270 s270.28)")
def agg(strengths): return sum(strengths)/(1+math.log(len(strengths)))
cases = {
    "1 item  @1.0":        [1.0],
    "2 items @1.0":        [1.0]*2,
    "10 items@1.0":        [1.0]*10,
    "100 items@1.0":       [1.0]*100,
    "3 items @0.1":        [0.1]*3,
}
for k,v in cases.items():
    print(f"    {k:<18} -> {agg(v):8.4f}")
print(f"""
  OBSERVED PROPERTIES:
    * unbounded: 100 unit-strength items give {agg([1.0]*100):.2f}, not a support DEGREE.
    * not idempotent: agg([1.0]) = {agg([1.0]):.4f} but agg([1.0,1.0]) = {agg([1.0]*2):.4f}
      -- duplicating the SAME evidence twice increases support by
      {100*(agg([1.0]*2)/agg([1.0])-1):.1f}%.
    * dimensionally undefined: sum(s_i) has the unit of s; log n is dimensionless;
      the quotient has the unit of s but no interpretation as a probability,
      a likelihood ratio, or a degree of belief.
    * n=1 gives sum/1 -- so the formula reduces to the raw strength only in the
      singleton case, i.e. it is a DISCOUNT on plurality, not an aggregation rule.
  => The formula is computable (Level B) but is NOT a measurement of support
     under any declared scale (Level C fails).  Step 270's own suspicion --
     'elegance is not derivation' -- is CONFIRMED by execution.""")

# ------------------------------------------------------------------- EXP-6
hr("EXP-6  IndependenceFactor = 1/(1+depth)   (Step 270 s270.1)")
print("""  Construction: two evidence items about the same proposition.
    e1: vendor API response,          dependency depth 0 from the root observation
    e2: a mirror that VERBATIM COPIES the vendor API, depth 1
  Ground truth: e2 carries ZERO additional information about the proposition;
  the true count of independent observations is 1.""")
def indep(depth): return 1/(1+depth)
print(f"    IndependenceFactor(e1)= {indep(0):.3f}")
print(f"    IndependenceFactor(e2)= {indep(1):.3f}")
print(f"    total 'independent weight' = {indep(0)+indep(1):.3f}   (truth: 1.000)")
print("""  Now a genuinely independent second source at the same depth:
    e3: an independent scanner, depth 1 from ITS own root -- also 0.500.
    The formula assigns e2 (a verbatim copy) and e3 (an independent
    observation) the SAME weight, because depth is a graph property and
    independence is a statistical one.
  => IndependenceFactor is not a measure of independence. CONFIRMED.
     It is a path-length discount. Naming it 'independence' is the category
     error Step 270 predicted; this executes the demonstration.""")

# ------------------------------------------------------------------- EXP-7
hr("EXP-7  Is 'uncertainty' in the corpus a probability?  (mandate s12)")
print("""  The mandate requires: if probability is used, locate Omega, F, P.
  Grep result over the primary corpus for a declared probability TRIPLE
  attached to an epistemic quantity is reported in 07-MEASUREMENT-THEORY-GAP.md.
  Here we test the CONSEQUENCE of not having one.""")
# A 'confidence' in [0,1] that is not a probability: does it obey additivity?
conf = {"version=3.69": 0.8, "version=3.70": 0.7}
print(f"    conf(P)   = {conf['version=3.69']}")
print(f"    conf(¬P)  = {conf['version=3.70']}   (mutually exclusive values of one dimension)")
print(f"    sum       = {sum(conf.values())}  -- exceeds 1")
print("""  If these were probabilities on a common (Omega,F,P) with the two events
  disjoint, the sum could not exceed 1.  They can and do, because the corpus
  assigns confidence per-assertion with no shared sample space.
  => 'confidence in [0,1]' is NOT a probability unless a common (Omega,F,P) is
     declared.  The corpus declares one only inside the abandoned measure-theory
     regime (kernel 25 Aug), never for Sigma or for assertion confidence.
     Therefore 'uncertainty' is currently a NUMBER, not a MEASUREMENT.""")
