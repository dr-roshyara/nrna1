#!/usr/bin/env python3
"""
Independent verification of the load-bearing claims in GN-75, GN-77 and GN-84.

These four claims carry the whole implementation-readiness verdict. Each is
mechanically checkable. This script checks them; it decides nothing.

  C1  vocabulary disjointness  — verification-lane symbols have 0 occurrences in
                                 the RATIFIED architecture (GN-75)
  C2  0 operation signatures in the governed surface (GN-77)
  C3  "postcondition" occurs 0 times in the governed surface (GN-77)
  C4  exactly 1 "precondition" hit, and it describes Authorization, not an
      operation's precondition (GN-77)
"""
import os, re, subprocess, collections
ROOT = subprocess.run(["git","rev-parse","--show-toplevel"],capture_output=True,text=True,
                      cwd=os.path.dirname(os.path.abspath(__file__))).stdout.strip()
def hr(t): print("\n"+"="*78+f"\n{t}\n"+"="*78)

# ---- the governed surface, per GN-77: v0.2 + v0.1 + FA-1..FA-9 -------------
MODEL = os.path.join(ROOT,"docs/knowledgeos/reviews/synthesis/model")
gov = []
for dp,dn,fn in os.walk(MODEL):
    for f in fn:
        if f.endswith(".md"): gov.append(os.path.join(dp,f))
gov += [os.path.join(ROOT,"docs/knowledge/Knowledge-Constitution.md")]
gov = [p for p in gov if os.path.exists(p)]
hr("The governed surface actually inspected")
for p in sorted(gov): print(f"  {os.path.relpath(p,ROOT)}  ({os.path.getsize(p)} B)")
blob = "\n".join(open(p,errors="replace").read() for p in gov)
print(f"\n  total governed text inspected: {len(blob):,} chars across {len(gov)} files")

# ---------------------------------------------------------------------- C1
hr("C1 — vocabulary disjointness (GN-75: '0 occurrences each')")
SYMBOLS = {
 "script-A (𝒜)":      r'𝒜|\\mathcal\{?A\}?\b',
 "script-R (ℛ)":      r'ℛ|\\mathcal\{?R\}?\b',
 "Sigma (Σ)":         r'Σ|\\Sigma\b',
 "Q_t":               r'Q_?\{?t\}?\b',
 "script-O (𝒪)":      r'𝒪|\\mathcal\{?O\}?\b',
 "Provenance":        r'\bProvenance\b',
 "Replay":            r'\bReplay\b',
 "Measurement":       r'\bMeasurement\b',
}
print(f"  {'symbol':<20}{'hits in governed surface':>26}")
print("  "+"-"*46)
c1 = {}
for name,pat in SYMBOLS.items():
    n = len(re.findall(pat, blob))
    c1[name]=n
    print(f"  {name:<20}{n:>26}")
zero = sum(1 for v in c1.values() if v==0)
print(f"\n  symbols with ZERO occurrences: {zero}/{len(SYMBOLS)}")
print(f"  => C1 {'CONFIRMED' if zero==len(SYMBOLS) else 'PARTIALLY CONFIRMED — see counts'}")

# ---------------------------------------------------------------------- C2
hr("C2 — operation signatures in the governed surface (GN-77 claims 0)")
SIG_PATTERNS = {
 "X × Y → Z (unicode)":  r'\S+\s*×\s*\S+\s*(?:→|->|\\rightarrow|⇀)\s*\S+',
 "f : A → B":            r'\b[A-Z][A-Za-z_]*\s*:\s*\S+\s*(?:→|->|\\rightarrow|⇀)\s*\S+',
 "\\to / \\rightarrow":  r'\\(?:to|rightarrow|rightharpoonup)\b',
}
tot=0
for name,pat in SIG_PATTERNS.items():
    hits = re.findall(pat, blob)
    tot += len(hits)
    print(f"  {name:<24} {len(hits)}")
    for h in hits[:4]: print(f"        {h[:88]}")
print(f"\n  total signature-shaped strings: {tot}")
print(f"  => C2 {'CONFIRMED (0)' if tot==0 else 'NOT literally 0 — each hit needs inspection (see above)'}")

# ---------------------------------------------------------------------- C3/C4
hr("C3/C4 — pre/postconditions in the governed surface")
for term in ("postcondition","post-condition","precondition","pre-condition"):
    hits = [(m.start(), blob[max(0,m.start()-90):m.start()+110].replace("\n"," "))
            for m in re.finditer(term, blob, re.I)]
    print(f"\n  '{term}': {len(hits)} hit(s)")
    for _,ctx in hits[:3]: print(f"      …{ctx.strip()[:150]}…")
print(f"""
  => C3 is the decisive one: if 'postcondition' is 0 across the governed surface,
     then no operation has a declared effect, and the operation contract cannot be
     written from canon regardless of which registry is chosen.""")

# ------------------------------------------------------- what IS in the canon
hr("What the governed surface DOES contain (counter-check, so the finding is not one-sided)")
POS = {"invariant":r'\binvariant', "primitive":r'\bprimitive', "K_t":r'K_?\{?t\}?\b',
       "authority":r'\bauthorit', "policy":r'\bpolic', "transition":r'\btransition',
       "illegal|forbid|must not":r'\b(?:illegal|forbid|must not)'}
for name,pat in POS.items():
    print(f"  {name:<22}{len(re.findall(pat, blob, re.I)):>6}")
print("""
  READING: the canon is rich in INVARIANTS, PRIMITIVES and PROHIBITIONS and empty
  of SIGNATURES and POSTCONDITIONS. That is GN-77's formulation, mechanically:
  'the ratified canon defines when a transition would be ILLEGAL without ever
   defining what a transition IS.'""")
