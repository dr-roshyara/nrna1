#!/usr/bin/env python3
"""
GN-46 mathematical verification — EXP-01 INDEPENDENT RECHECK (testing tool only).

Independent reference implementation of the four EXP-01 aggregation operators
(design: 20260827-134514; verdict: 20260827-135038; CSV:
tests/experiments/knowledgeos_evidence_calculus_property_tests.csv).

This script REPRODUCES the reasoning independently. It modifies nothing.
The GN-27 adjudication of the CSV-vs-verdict discrepancy stands; this script
only determines what each cell is TRUE OF, under two explicit semantics:

  RAW  — operator applied directly to all item strengths (no preprocessing)
  PIPE — dependency/equivalence normalization (E -> E/~, one contribution per
         root observation) and relevance filtering run BEFORE the operator,
         exactly as the verdict's own pipeline (N then Q then rho) prescribes.

Evidence item: (id, polarity, s in [0,1], relevance in [0,1], root, current).
`root` identifies the underlying observation (dependency/equivalence class).
"""

from itertools import permutations

# ---------------------------------------------------------------- operators
def op_max(ss):
    return max(ss) if ss else 0.0

def op_wmean(ss):
    return sum(ss) / len(ss) if ss else float("nan")

def op_sat(ss):
    p = 1.0
    for s in ss:
        p *= (1.0 - s)
    return 1.0 - p

def op_bayes(ss, prior=0.5):
    """Naive-Bayes-like scalar combiner: P(e|H)=s, P(e|~H)=1-s, prior 0.5."""
    num, den = prior, 1.0 - prior
    for s in ss:
        num *= s
        den *= (1.0 - s)
    if num + den == 0.0:
        return float("nan")          # s=1 and s=0 simultaneously: undefined
    return num / (num + den)

OPS = {"MAX": op_max, "WEIGHTED_MEAN": op_wmean,
       "SATURATING": op_sat, "BAYES_LIKE": op_bayes}

# ---------------------------------------------------------------- pipeline
def raw(items):
    """RAW semantics: every supporting strength enters the operator."""
    return [it["s"] for it in items if it["pol"] > 0]

def pipe(items):
    """PIPE semantics: relevance filter, then collapse each dependency/
    equivalence class (same root) to a single contribution (its best member,
    capped at the root observation's own strength when the root is present)."""
    kept = [it for it in items if it["pol"] > 0 and it["rel"] > 0]
    classes = {}
    for it in kept:
        classes.setdefault(it["root"], []).append(it)
    out = []
    for members in classes.values():
        roots = [m["s"] for m in members if m["id"] == m["root"]]
        cap = roots[0] if roots else max(m["s"] for m in members)
        out.append(cap)
    return out

def ev(i, s, root=None, rel=1.0, pol=+1, current=True):
    return {"id": i, "s": s, "root": root or i, "rel": rel,
            "pol": pol, "current": current}

def close(a, b, eps=1e-12):
    return abs(a - b) < eps

# ---------------------------------------------------------------- properties
results = {}   # results[op][prop][mode] = True/False/None
notes = []

def rec(op, prop, mode, ok):
    results.setdefault(op, {}).setdefault(prop, {})[mode] = ok

print("=" * 78)
print("EXP-01 INDEPENDENT RECHECK — four operators, RAW vs PIPE semantics")
print("=" * 78)

# --- A. Duplicate invariance (heterogeneous base set) ------------------------
base = [ev("e1", 0.8), ev("e3", 0.4)]
dup  = base + [ev("e2", 0.8, root="e1")]          # exact epistemic duplicate of e1
sing = [ev("e1", 0.8)]
sdup = sing + [ev("e2", 0.8, root="e1")]
print("\nA. Duplicate invariance  A(E u {e1,e2}) = A(E u {e1}),  e1 ~ e2")
for name, f in OPS.items():
    r_ok = close(f(raw(base)), f(raw(dup)))
    p_ok = close(f(pipe(base)), f(pipe(dup)))
    s_ok = close(f(raw(sing)), f(raw(sdup)))      # singleton-base subtest
    rec(name, "A", "raw", r_ok); rec(name, "A", "pipe", p_ok)
    print(f"  {name:14s} raw(het-base): {f(raw(base)):.4f} vs {f(raw(dup)):.4f}"
          f" -> {'PASS' if r_ok else 'FAIL'} | pipe: {'PASS' if p_ok else 'FAIL'}"
          f" | raw(singleton-base): {'PASS' if s_ok else 'FAIL'}")

# --- B. Independent corroboration --------------------------------------------
one = [ev("e1", 0.7)]
two = one + [ev("e2", 0.7)]                        # genuinely independent
print("\nB. Independent corroboration  A(e1,e2) > A(e1),  e1 _|_ e2")
for name, f in OPS.items():
    ok = f(raw(two)) > f(raw(one)) + 1e-12
    rec(name, "B", "raw", ok); rec(name, "B", "pipe", ok)   # same either way
    print(f"  {name:14s} {f(raw(one)):.4f} -> {f(raw(two)):.4f} : "
          f"{'PASS' if ok else 'FAIL'}")

# --- C. Order invariance ------------------------------------------------------
ss = [0.3, 0.8, 0.55, 0.1]
print("\nC. Order invariance (all permutations of a 4-item set)")
for name, f in OPS.items():
    vals = {round(f(list(p)), 12) for p in permutations(ss)}
    ok = len(vals) == 1
    rec(name, "C", "raw", ok); rec(name, "C", "pipe", ok)
    print(f"  {name:14s} {'PASS' if ok else 'FAIL'}")

# --- D. Associativity (designed criterion with NO historical matrix outcome) --
print("\nD. Associativity of the induced binary combiner (PF-4: never executed)")
def bin_max(a, b): return max(a, b)
def bin_sat(a, b): return 1 - (1 - a) * (1 - b)
def bin_mean(a, b): return (a + b) / 2.0            # naive stateless binary mean
def bin_bayes(a, b):                                 # odds-form product
    na, nb = a / (1 - a), b / (1 - b)
    o = na * nb
    return o / (1 + o)
triples = [(0.2, 0.5, 0.9), (0.7, 0.7, 0.1), (0.35, 0.6, 0.6)]
for name, g in [("MAX", bin_max), ("WEIGHTED_MEAN(naive)", bin_mean),
                ("SATURATING", bin_sat), ("BAYES_LIKE(odds)", bin_bayes)]:
    ok = all(close(g(g(a, b), c), g(a, g(b, c)), 1e-9) for a, b, c in triples)
    key = name.split("(")[0]
    rec(key, "D", "raw", ok)
    print(f"  {name:22s} {'PASS' if ok else 'FAIL'}"
          + ("   (state-carrying (sum,count) mean IS associative)" if "MEAN" in name else ""))

# --- E. Contradiction preservation --------------------------------------------
print("\nE. Contradiction preservation (designed; narrative-only historically)")
strong = [ev("p", 0.9), ev("n", 0.9, pol=-1)]
weak   = [ev("p", 0.1), ev("n", 0.1, pol=-1)]
for name, f in OPS.items():
    # netted scalar: aggregate support minus aggregate counter-support, clipped
    def netted(items):
        sp = f([i["s"] for i in items if i["pol"] > 0])
        sn = f([i["s"] for i in items if i["pol"] < 0])
        return max(0.0, sp - sn)
    conflated = close(netted(strong), netted(weak))
    rec(name, "E", "raw", False)   # no scalar output retains (S+, S-)
    print(f"  {name:14s} netted(strong-conflict)={netted(strong):.3f} "
          f"netted(weak-conflict)={netted(weak):.3f} "
          f"-> distinct epistemic situations {'CONFLATED' if conflated else 'nearly conflated'}; "
          f"scalar output cannot retain (S+,S-): FAIL as operator property")
print("  NOTE: retaining (S+,S-) is a REPRESENTATION-layer property; no scalar")
print("  operator can satisfy E. This is what defeats every scalar as foundation.")

# --- F. Dependency awareness (API -> LLM1, LLM2) -------------------------------
api_only = [ev("api", 0.8)]
chain    = [ev("api", 0.8), ev("llm1", 0.85, root="api"), ev("llm2", 0.9, root="api")]
print("\nF. Dependency awareness: A({api,llm1,llm2}) must equal A({api})")
for name, f in OPS.items():
    r_ok = close(f(raw(api_only)), f(raw(chain)))
    p_ok = close(f(pipe(api_only)), f(pipe(chain)))
    rec(name, "F", "raw", r_ok); rec(name, "F", "pipe", p_ok)
    print(f"  {name:14s} raw: {f(raw(api_only)):.4f} vs {f(raw(chain)):.4f} "
          f"-> {'PASS' if r_ok else 'FAIL'} | pipe: {'PASS' if p_ok else 'FAIL'}")

# --- G. Irrelevance -------------------------------------------------------------
rel_base = [ev("e1", 0.6)]
with_irr = rel_base + [ev("junk", 0.95, rel=0.0)]   # reliable but irrelevant
print("\nG. Irrelevance: Relevance(e,P)=0 must not strengthen P")
for name, f in OPS.items():
    r_ok = close(f(raw(rel_base)), f(raw(with_irr)))
    p_ok = close(f(pipe(rel_base)), f(pipe(with_irr)))
    rec(name, "G", "raw", r_ok); rec(name, "G", "pipe", p_ok)
    print(f"  {name:14s} raw: {f(raw(rel_base)):.4f} vs {f(raw(with_irr)):.4f} "
          f"-> {'PASS' if r_ok else 'FAIL'} | pipe: {'PASS' if p_ok else 'FAIL'}")
print("  NOTE: irrelevance handling is a normalization-layer property for ALL")
print("  four operators, not only MAX/mean (the historical footnote's scope).")

# --- H. Temporal validity --------------------------------------------------------
print("\nH. Temporal validity: store-level property (stale item retained, not")
print("   counted as current). No operator has time semantics; PASS is a store")
print("   property under either semantics, N/A as a raw operator property.")
for name in OPS:
    rec(name, "H", "pipe", True)

# --- Bounded [0,1] (the matrix's extra column, not among designed A-J) ----------
print("\nBounded [0,1] (executed matrix's extra column; not a designed criterion)")
edge_sets = [[0.0], [1.0], [0.0, 1.0], [0.5] * 5, []]
for name, f in OPS.items():
    oks, caveat = [], ""
    for es in edge_sets:
        v = f(es)
        if v != v:                                   # NaN
            caveat = " CAVEAT: undefined on " + repr(es)
            continue
        oks.append(0.0 <= v <= 1.0)
    ok = all(oks)
    rec(name, "Bounded", "raw", ok)
    print(f"  {name:14s} {'PASS' if ok else 'FAIL'}{caveat}")
print("  BAYES_LIKE is undefined (0/0) on {0,1} jointly; WEIGHTED_MEAN undefined")
print("  on the empty set; MAX needs an empty-set convention. Numerical caveats.")

# --- Adversarial: 100 duplicates -------------------------------------------------
print("\nADVERSARIAL: 100 duplicates of one s=0.7 observation")
dup100 = [ev(f"c{i}", 0.7, root="c0") for i in range(100)]
for name, f in OPS.items():
    print(f"  {name:14s} raw={f(raw(dup100)):.6f}  pipe={f(pipe(dup100)):.6f}"
          f"   (must equal 0.7 to be duplicate-safe)")

# --- Calibration demonstration ---------------------------------------------------
print("\nCALIBRATION (designed 'most important statistical test'; never executed)")
weak10 = [ev(f"w{i}", 0.3) for i in range(10)]
print(f"  SATURATING over 10 independent weak items (s=0.3): {op_sat(raw(weak10)):.4f}")
print("  -> a '0.97' with no probability space, no likelihood, no calibration:")
print("     exactly the design's 'number pretending to be probability'.")
dep3 = [ev("api", 0.8), ev("l1", 0.8, root="api"), ev("l2", 0.8, root="api")]
print(f"  BAYES_LIKE treating the API->LLM1/LLM2 chain as independent: "
      f"{op_bayes(raw(dep3)):.4f} vs correct single-source {op_bayes(raw([ev('api',0.8)])):.4f}")
print("  -> severe overconfidence from double-counting dependent evidence.")

# --- Matrix comparison -------------------------------------------------------------
print("\n" + "=" * 78)
print("COMPARISON WITH HISTORY (verdict table and CSV; GN-27: prose prevails)")
print("=" * 78)
hist_verdict = {  # from 20260827-135038 §1 (x* = fails raw, normalization-layer job)
 "MAX":           {"A": "PASS", "B": "FAIL", "F": "PASS", "G": "FAIL*", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "WEIGHTED_MEAN": {"A": "FAIL", "B": "FAIL", "F": "FAIL", "G": "FAIL*", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "SATURATING":    {"A": "PASS", "B": "PASS", "F": "PASS", "G": "PASS",  "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "BAYES_LIKE":    {"A": "PASS", "B": "PASS", "F": "PASS", "G": "PASS",  "C": "PASS", "H": "PASS", "Bounded": "PASS"},
}
hist_csv = {
 "MAX":           {"A": "PASS", "B": "FAIL", "F": "PASS", "G": "PASS", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "WEIGHTED_MEAN": {"A": "PASS", "B": "FAIL", "F": "FAIL", "G": "FAIL", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "SATURATING":    {"A": "PASS", "B": "PASS", "F": "PASS", "G": "PASS", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
 "BAYES_LIKE":    {"A": "PASS", "B": "PASS", "F": "PASS", "G": "FAIL", "C": "PASS", "H": "PASS", "Bounded": "PASS"},
}
def fmt(b): return {True: "PASS", False: "FAIL", None: "n/a"}.get(b, "n/a")
hdr = f"{'op':14s} {'prop':7s} {'RAW':6s} {'PIPE':6s} {'verdict':9s} {'CSV':6s}"
print(hdr); print("-" * len(hdr))
for op in OPS:
    for prop in ["A", "B", "C", "F", "G", "H", "Bounded"]:
        r = results.get(op, {}).get(prop, {})
        print(f"{op:14s} {prop:7s} {fmt(r.get('raw')):6s} {fmt(r.get('pipe')):6s} "
              f"{hist_verdict[op].get(prop,'-'):9s} {hist_csv[op].get(prop,'-'):6s}")

print("""
READING (independent determination, no artifact modified):
* Every historical PASS cell that my RAW run fails (SAT/A, SAT/F, BAYES/A,
  BAYES/F, SAT/G, BAYES/G) is TRUE ONLY of the normalized pipeline
  (N: E -> E/~ plus relevance filtering run before the operator). The
  verdict's own §§2-3 and §8 say the collapse happens BEFORE the operator,
  so the prose record is internally consistent under that reading.
* CSV oddities re-derived: WM/A 'True' is exactly what a singleton-base
  duplication test produces (mean(s,s)=s); MAX/G 'True' is the normalized
  reading; BAYES/G 'False' is the raw reading. The CSV mixes semantics
  across cells -- consistent with GN-27's 'inconsistent under any single
  reading; unreliable standalone'. The adjudication STANDS.
* The published negative verdict ('no simple scalar operator is sufficient
  as the epistemic foundation') does NOT follow from the 7-column matrix
  alone (SATURATING passes all seven columns). It DOES follow from the
  matrix PLUS criterion E (no scalar can retain (S+,S-)) PLUS the
  pre-operator dependency/duplicate work PLUS the calibration caution --
  all of which the verdict states narratively. Independent recheck:
  the negative conclusion is CONFIRMED and is in fact provable.
""")
