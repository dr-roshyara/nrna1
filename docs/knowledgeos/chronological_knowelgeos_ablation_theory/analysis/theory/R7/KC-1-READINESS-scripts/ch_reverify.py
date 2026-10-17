# KC-1-READINESS-AUDIT r2 §12.1: re-verification of the r1 computations and artifact versions. Analysis only; decides nothing.
# Re-runs repro.py, readiness.py, lex.py, blind_eval.py, blind/results/agree.py, blind2_eval.py and the r2 scripts with
# python3 -B into a scratch directory (argument 2), compares each with its committed .out byte for byte, and prints the
# sha256 prefixes of the F-LOG-0303..0307 artifacts. Writes nothing outside the scratch directory.
# Run from this folder:  python3 -B ch_reverify.py .. <scratch dir>     (about 1 min)
import sys, os, subprocess, hashlib, difflib
R7 = os.path.abspath(sys.argv[1]); SC = os.path.abspath(sys.argv[2]); os.makedirs(SC, exist_ok=True)
HERE = os.path.dirname(os.path.abspath(__file__))
ENV = dict(os.environ, PYTHONDONTWRITEBYTECODE='1')
RUNS = [('.', 'repro.py', [R7, os.path.join(SC, 'repro_scratch')]), ('.', 'readiness.py', [R7]), ('.', 'lex.py', [R7]),
        ('.', 'blind_eval.py', [R7]), ('blind/results', 'agree.py', ['.']), ('.', 'blind2_eval.py', [R7]),
        ('.', 'ch_boundary.py', [R7]), ('.', 'ch_rho.py', [R7])]
for d, s, args in RUNS:
    wd = os.path.join(HERE, d); out = os.path.join(SC, s.replace('.py', '.out'))
    with open(out, 'w') as fh:
        subprocess.run([sys.executable, '-B', s] + args, cwd=wd, stdout=fh, stderr=subprocess.STDOUT, env=ENV, check=False)
    ref = open(os.path.join(wd, s.replace('.py', '.out')), 'rb').read(); new = open(out, 'rb').read()
    if ref == new:
        print(f'== {d}/{s}: BYTE-IDENTICAL'); continue
    diff = [l for l in difflib.unified_diff(ref.decode().splitlines(), new.decode().splitlines(), 'committed', 'rerun', n=0, lineterm='')
            if l[:1] in '+-' and l[:3] not in ('+++', '---')]
    print(f'== {d}/{s}: {len(diff)} changed lines')
    for l in diff: print('   ', l)
print('\n== sha256 prefixes (16 hex): F-LOG-0303..0307 artifacts and the log ==')
for f in ['KC-MKC-REASSESSMENT.md', 'AUDIT-Q6-TEST2-CLAUSE-COORDINATE-TABLE.md', 'KC-1-DECISION-MATRIX.md', 'KC-GUARD-PURPOSE-ANALYSIS.md',
          'KC-CONVENTIONS-PROPOSAL.md', 'T3-CANDIDATE-MODEL.md', 'AUDIT-Q6-TEST1-FROZEN-ENCODING.md',
          os.path.join('..', '..', '..', 'F-GOVERNANCE-LOG.md')]:
    p = os.path.normpath(os.path.join(R7, f))
    print(f'  {f:60s} {hashlib.sha256(open(p, "rb").read()).hexdigest()[:16]}')
