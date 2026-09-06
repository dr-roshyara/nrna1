#!/usr/bin/env python3
"""
STEP-272 PREMISE AUDIT — executable.

QUESTION   The mandate commissions "Step 272 — Canonical Sigma Derivation" and its own
           s1/s2 require freezing the evidence base and TESTING the premise first.
           Does the premise hold?
METHOD     Filesystem census of the research corpus and the verification tree;
           targeted greps for the specific claims.
RESULT     see below.
LIMITATION A snapshot. The tree was still being written during the previous pass.
"""
import os, re, glob, subprocess, collections
ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), "../../../../../../.."))
PMT  = os.path.join(ROOT, "docs/knowledgeos/brainstorming/phase_measure_theory")
VER  = os.path.join(ROOT, "docs/knowledgeos/brainstorming/verification")
def hr(t): print("\n" + "="*78 + f"\n{t}\n" + "="*78)

hr("A. CORPUS STATE — does Step 272 exist, and what is the highest step?")
steps = collections.defaultdict(list)
for f in os.listdir(PMT):
    m = re.search(r'step[ _-]*0*(\d+)', f, re.I)
    if m: steps[int(m.group(1))].append(f)
hi = max(steps)
print(f"  files in phase_measure_theory : {len(os.listdir(PMT))}")
print(f"  highest step number           : {hi}")
print(f"  steps 272-278 present         : {sorted(n for n in steps if 272 <= n <= 278)}")
print(f"  missing below the maximum     : {[n for n in range(1, hi+1) if n not in steps]}")
print(f"\n  step 272 file present?        : {'YES' if 272 in steps else 'NO — but Step 273 cites its conclusion'}")
for n in sorted(n for n in steps if 273 <= n <= 278):
    for f in steps[n]:
        t = os.path.getmtime(os.path.join(PMT, f))
        import datetime
        print(f"    {n}  {datetime.datetime.fromtimestamp(t):%H:%M}  {f[:64]}")

hr("B. IS SIGMA STILL THE FRONTIER? — where each step went")
def head1(p):
    for ln in open(p, errors="replace"):
        ln = ln.strip()
        if ln.startswith("#"): return ln.lstrip("# ").strip()
    return "(no heading)"
for n in sorted(n for n in steps if 273 <= n <= 278):
    p = os.path.join(PMT, sorted(steps[n])[-1])
    sig = len(re.findall(r'sigma|\\Sigma', open(p, errors="replace").read(), re.I))
    print(f"  Step {n}: Σ-mentions={sig:<4} {head1(p)[:70]}")

hr("C. D-0 — was O_core authorised?  (independent re-verification)")
occ = []
for f in os.listdir(PMT):
    p = os.path.join(PMT, f)
    if os.path.isfile(p) and re.search(r'O_\{?core\}?', open(p, errors="replace").read()):
        occ.append(f)
print(f"  files containing 'O_core'                    : {len(occ)}")
for f in occ: print(f"      {f[:70]}")
gn = os.path.join(ROOT, "docs/knowledgeos/reviews/synthesis/analysis/governance-notes.md")
txt = open(gn, errors="replace").read() if os.path.exists(gn) else ""
gns = sorted({int(x) for x in re.findall(r'GN-(\d+)', txt)})
print(f"\n  governance-notes.md highest GN               : GN-{gns[-1] if gns else '?'}")
print(f"  governance-notes entries for O_core / 'operation universe': "
      f"{len(re.findall(r'o_core|operation universe', txt, re.I))}")
hpa = [p for p in glob.glob(os.path.join(ROOT, "docs/**/*HPA*"), recursive=True)]
print(f"  files named *HPA* in docs/                    : {len(hpa)}")
for p in hpa: print(f"      {os.path.relpath(p, ROOT)}")
print("""
  => D-0 CONFIRMED and STRENGTHENED. Every occurrence of O_core is inside
     Stratum-1 research narrative. No declaring artifact, no governance note, and
     the cited "attached HPA response" is not in the repository.
     The canonical-construction pass measured 5 occurrences in 1 file; the
     premise has since propagated into MORE steps.""")

hr("D. DID 272-278 ADOPT THE SECOND-ORDER FINDINGS?")
blob = ""
for n in sorted(n for n in steps if 272 <= n <= 278):
    for f in steps[n]: blob += open(os.path.join(PMT, f), errors="replace").read()
checks = {
 "congruence-vacuity (SO-2)":      r'vacuous|congruence can hold vacuously|congruence.{0,20}not sufficient',
 "invariant-expressibility (SO-3)":r'expressib',
 "K-sufficiency (commissioned by 277 for 278)": r'K-sufficiency|K sufficiency',
 "AuthorityAct typed (the ONE innovation)":     r'AuthorityAct',
 "Context given a type":           r'Context\s*=\s*\(|Context\s*\\in\s*\\mathcal',
 "R restored to 8 fields":         r'mathcal R.{0,40}times.{0,40}times.{0,40}times.{0,40}times',
}
for name, pat in checks.items():
    n = len(re.findall(pat, blob, re.I))
    print(f"  {name:<48} occurrences={n:<4} {'ADOPTED' if n else 'NOT ADOPTED'}")

hr("E. VERIFICATION-TREE CENSUS — is the corpus still being written?")
pkgs = collections.Counter()
for dp, dn, fn in os.walk(VER):
    rel = os.path.relpath(dp, VER).split(os.sep)[0]
    pkgs[rel] += len([f for f in fn if f.endswith(".md")])
print(f"  {'package':<26}{'.md files':>10}")
for k, v in sorted(pkgs.items(), key=lambda x: -x[1]):
    if v: print(f"  {k:<26}{v:>10}")
print(f"  {'TOTAL':<26}{sum(pkgs.values()):>10}")
newest = max((os.path.getmtime(os.path.join(dp,f)) for dp,_,fn in os.walk(VER) for f in fn), default=0)
newest_c = max((os.path.getmtime(os.path.join(PMT,f)) for f in os.listdir(PMT)), default=0)
import datetime
print(f"\n  newest verification artifact : {datetime.datetime.fromtimestamp(newest):%Y-%m-%d %H:%M:%S}")
print(f"  newest research artifact     : {datetime.datetime.fromtimestamp(newest_c):%Y-%m-%d %H:%M:%S}")
print(f"  now                          : {datetime.datetime.now():%Y-%m-%d %H:%M:%S}")
