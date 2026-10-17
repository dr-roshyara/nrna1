# KC-1-READINESS-AUDIT r0: reproduction of the committed outputs and artifact hashes. Analysis only; decides nothing.
# Re-runs kc.py, mkc.py, adhoc.py and quotes.py with python3 -B into a scratch directory (argument 2), diffs against the
# committed .out files, and prints sha256 prefixes of every artifact reviewed.
# Run from this folder:  python3 -B repro.py .. <scratch dir>
import sys, os, subprocess, hashlib, difflib
R7 = os.path.abspath(sys.argv[1]); SC = os.path.abspath(sys.argv[2]); os.makedirs(SC, exist_ok=True)
LOG = os.path.normpath(os.path.join(R7, '..', '..', '..', 'F-GOVERNANCE-LOG.md'))
RUNS = [('KC-CONVENTIONS-scripts', 'kc.py', [R7]), ('KC-MKC-REASSESSMENT-scripts', 'mkc.py', [R7]),
        ('KC-MKC-REASSESSMENT-scripts', 'adhoc.py', [R7]), ('KC-GUARD-PURPOSE-scripts', 'quotes.py', ['..'])]
for d, s, args in RUNS:
    wd = os.path.join(R7, d); out = os.path.join(SC, s.replace('.py', '.out'))
    with open(out, 'w') as fh:
        subprocess.run([sys.executable, '-B', s] + args, cwd=wd, stdout=fh, stderr=subprocess.STDOUT, check=False)
    ref = open(os.path.join(wd, s.replace('.py', '.out'))).read().splitlines(); new = open(out).read().splitlines()
    diff = list(difflib.unified_diff(ref, new, 'committed', 'rerun', n=0, lineterm=''))
    print(f'== {d}/{s}: {"BYTE-IDENTICAL" if not diff else f"{sum(1 for l in diff if l[:1] in "+-" and l[:3] not in ("+++", "---"))} changed lines"}')
    for l in diff: print('   ', l)
print('\n== sha256 prefixes (16 hex) of artifacts reviewed ==')
FILES = ['KC-1-DECISION-MATRIX.md', 'KC-GUARD-PURPOSE-ANALYSIS.md', 'KC-MKC-REASSESSMENT.md', 'KC-CONVENTIONS-PROPOSAL.md',
         'AUDIT-Q6-TEST2-CLAUSE-COORDINATE-TABLE.md', 'T3-CANDIDATE-MODEL.md', 'T2-RULE-CANDIDATES.md', 'E2b-ADOPTION-TEXT.md',
         'E3-ARCHITECTURE-PROPOSAL.md', 'RESEARCH-AUDIT-1.md',
         'KC-CONVENTIONS-scripts/kc.py', 'KC-CONVENTIONS-scripts/kc.out', 'KC-MKC-REASSESSMENT-scripts/mkc.py', 'KC-MKC-REASSESSMENT-scripts/mkc.out',
         'KC-MKC-REASSESSMENT-scripts/adhoc.py', 'KC-MKC-REASSESSMENT-scripts/adhoc.out', 'KC-GUARD-PURPOSE-scripts/quotes.py',
         'KC-GUARD-PURPOSE-scripts/quotes.out', 'AUDIT-Q6-TEST1-scripts/blind_enc.py']
for f in FILES + [LOG]:
    p = f if os.path.isabs(f) else os.path.join(R7, f)
    print(f'  {os.path.relpath(p, R7):60s} {hashlib.sha256(open(p, "rb").read()).hexdigest()[:16]}')
