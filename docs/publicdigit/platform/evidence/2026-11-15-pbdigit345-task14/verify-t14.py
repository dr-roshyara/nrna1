# PBDIGIT-345 task 14: verification of the committed acceptance package. Reads git HEAD content only (git show), so an
# uncommitted file can never make a check pass. Prints one line per check: PASS / FAIL / NOTE, and exits 1 on any FAIL.
import subprocess, re, sys, hashlib, xml.etree.ElementTree as ET
def git(*a): return subprocess.run(['git',*a],capture_output=True,text=True,check=True).stdout
def show(p): return subprocess.run(['git','show',f'HEAD:{p}'],capture_output=True,check=True).stdout
def tracked(prefix): return [l for l in git('ls-tree','-r','--name-only','HEAD',prefix).splitlines()]
fails=0
def check(ok,msg):
    global fails; fails+= not ok; print(('PASS ' if ok else 'FAIL ')+msg)
E='docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task13'
# 1 indexes
for area in ('platform','database','testing'):
    idx=show(f'developer_guide/{area}/00_index.md').decode()
    files=[f.split('/')[-1] for f in tracked(f'developer_guide/{area}/') if re.match(r'.*/\d\d_.*\.md$',f) and not f.endswith('00_index.md')]
    missing=[f for f in files if f'({f})' not in idx]
    links=re.findall(r'\]\(([^)#]+\.md)\)',idx)
    dangling=[l for l in links if not l.startswith('../') and f'developer_guide/{area}/{l}' not in tracked(f'developer_guide/{area}/')]
    check(not missing and not dangling, f'index {area}: {len(files)} guides, all indexed; {len(links)} links, none dangling' + (f' MISSING {missing} DANGLING {dangling}' if missing or dangling else ''))
# 2 every evidence seal, from committed bytes
ev=tracked('docs/publicdigit/platform/evidence/'); sums=[f for f in ev if f.endswith('/SHA256SUMS')]; bad=[]; covered=set()
for s in sums:
    d=s.rsplit('/',1)[0]
    for line in show(s).decode().splitlines():
        h,_,n=line.partition('  '); p=f"{d}/{n[2:] if n.startswith('./') else n}"; covered.add(p)
        try:
            if hashlib.sha256(show(p)).hexdigest()!=h: bad.append(p)
        except subprocess.CalledProcessError: bad.append(p+' (not committed)')
unc=[f for f in ev if not f.endswith('/SHA256SUMS') and f not in covered]
check(not bad and not unc, f'evidence: {len(sums)} SHA256SUMS over committed bytes; {len(ev)-len(sums)} files; mismatched {len(bad)}; uncovered {len(unc)}' + (f' {bad[:3]} {unc[:3]}' if bad or unc else ''))
# 3 JUnit results committed in task 13
def junit(p):
    r={}
    for tc in ET.fromstring(show(p)).iter('testcase'):
        r[((tc.get('class') or tc.get('classname') or '').split('\\')[-1],tc.get('name'))]=any(c.tag in('failure','error','skipped') for c in tc)
    return r
db=junit(f'{E}/t13-03-db-free-junit.xml'); cl=junit(f'{E}/t13-04-cluster-junit.xml')
check(len(cl)==410 and not any(cl.values()), f'cluster JUnit: {len(cl)} test cases, {sum(cl.values())} failing/erroring/skipped')
check(len(db)==560 and not any(db.values()), f'database-free JUnit: {len(db)} test cases, {sum(db.values())} failing/erroring/skipped')
suite=show(f'{E}/t13-04-isolated-cluster-junit/suite.txt').decode(); run=show(f'{E}/t13-04-isolated-cluster-junit/run.txt').decode()
check('Tests: 410' in suite and show(f'{E}/t13-04-isolated-cluster-junit/suite.exit').decode().strip()=='0', 'cluster JUnit run: suite.txt Tests: 410, suite.exit 0')
c=re.search(r'commit: (\w+)',run).group(1); check(c.startswith('8b6fcd569'), f'cluster JUnit run.txt commit {c[:9]} = the code under account 8b6fcd569')
check(git('diff','--name-only','8b6fcd569','HEAD','--','app','config','routes','database','scripts','tests').strip()=='', 'no code change between 8b6fcd569 and HEAD (task 13 and 14 are documentation and evidence only)')
# 4 T-matrix re-derived from the committed JUnit and compared
res={**{k:('db-free',v) for k,v in db.items()},**{k:('cluster',v) for k,v in cl.items()}}
out=[]; 
for t in range(1,27):
    hs=sorted((k,v) for k,v in res.items() if re.search(r'(?:^|_)t%d(?=_|$)'%t,k[1][5:]) and 'PostgresMemberRepositoryInvariants' not in k[0])
    out.append((t,len(hs),sum(1 for _,v in hs if not v[1])))
mx=show(f'{E}/05-t-matrix.txt').decode()
check(all(p>0 for _,_,p in out) and sum(n for _,n,_ in out)==79 and 'SUMMARY: 79 tests cited; 0 failing; T rows without a passing test: none' in mx,
      'T-1..T-26 re-derived from committed JUnit: every T has a passing test; 79 cited; matches 05-t-matrix.txt')
# 5 the 53 account
acc=show(f'{E}/01-d193-proof-obligation-account.md').decode(); reg=show('docs/publicdigit/architecture/reviews/2026-11-11-platform-proof-obligation-register.md')
check(hashlib.sha256(reg).hexdigest().startswith('a3fe00f28f8fe70c'), 'register at HEAD still has the hash D-193 (4) cites (a3fe00f2…)')
regids=re.findall(r'^\| (\d+[a-d]?|[A-F]-\d+) \|',reg.decode(),re.M); check(len(regids)==53, f'register rows: {len(regids)}')
rows={}
for sec in re.split(r'^## ',acc,flags=re.M):
    if not sec.startswith('Section'): continue
    sec=sec.split('\n### ')[0]   # the section's own table only; B-7/B-9/B-10 sub-tables use other numbering
    for m in re.finditer(r'^\| \*{0,2}(\d+[a-d]?|[A-F]-\d+)\*{0,2} \|(.*)$',sec,re.M):
        st=re.findall(r'\*{0,2}(OUT OF SCOPE|NOT PROVEN|BLOCKED|PROVEN|FAILED)',m.group(2)); rows[m.group(1)]=st[0] if st else '?'
check(sorted(rows)==sorted(regids), f'account rows: {len(rows)}, the same ids as the register')
from collections import Counter; cnt=Counter(rows.values())
check(cnt=={'OUT OF SCOPE':47,'NOT PROVEN':3,'BLOCKED':3}, f'status counts {dict(cnt)} = summary table (47/3/3; 0 PROVEN, 0 FAILED)')
# cited test names exist as test cases in the committed JUnit (or as test methods in tests/ at HEAD)
names=set(n for _,n in res); srcnames=set(re.findall(r'function (test_\w+)',git('grep','-h','function test_','HEAD','--','tests')))
cited=set(re.findall(r'::(test_[a-z0-9_]+?)(?:…|`)',acc)); full=[n for n in cited]; unknown=[n for n in full if n not in names and n not in srcnames]
prefix=set(re.findall(r'::(test_[a-z0-9_]+)…',acc)); unknown_prefix=[p for p in prefix if not any(n.startswith(p) for n in names|srcnames)]
check(not [u for u in unknown if u not in prefix] and not unknown_prefix, f'account cites {len(cited)} test names (incl. {len(prefix)} shortened with …, each matching a real test): all exist' + (f' UNKNOWN {[u for u in unknown if u not in prefix][:5]} {unknown_prefix[:5]}' if [u for u in unknown if u not in prefix] or unknown_prefix else ''))
# 6 backlog 351-353
for n in ('351','352','353'):
    f=[x for x in tracked('docs/publicdigit/backlog/') if f'PBDIGIT-{n}-' in x]
    txt=show(f[0]).decode() if f else ''
    refs=[p for p in ('docs/publicdigit/backlog/PBDIGIT-345-platform-routing-and-organisation-isolation.md','docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md',f'{E}/01-d193-proof-obligation-account.md','developer_guide/platform/13_proof_obligations_and_evidence.md') if re.search(r'PBDIGIT-%s|PBDIGIT-35\d(?:/35\d)*/%s|PBDIGIT-35\d, 35\d, %s|PBDIGIT-35\d, %s'%(n,n,n,n), show(p).decode())]
    check(len(f)==1 and '**Status:** OPEN' in txt and 'not authorised' in txt and len(refs)==4, f'PBDIGIT-{n}: one file, OPEN, not authorised; referenced from story, plan, account, guide 13 ({len(refs)}/4)')
# 7 the four architecture failures
four=['ElectionLifecycleStateConsistencyTest','ElectionStateMachineConsistencyTest','ConstitutionalBoundaryTest','Phase_C25_SovereigntyLeakagePreventionTest']
af=show(f'{E}/06-architecture/architecture-full.failures').decode(); txt=show(f'{E}/06-architecture/architecture-full.txt').decode()
check(all(x in af for x in four) and len(af.splitlines())==4 and 'Failures: 4' in txt, 'architecture at HEAD: exactly the 4 known failures, recorded as failures (Failures: 4)')
docs=['developer_guide/platform/13_proof_obligations_and_evidence.md',f'{E}/README.md',f'{E}/04-baseline-comparison/README.md']
claims=[d for d in docs if re.search(r'architecture (suite )?(passes|passed|is green|all pass)',show(d).decode(),re.I)]
check(not claims and all(all(x in show(d).decode() for x in four) for d in docs[:1]+docs[2:]), 'no document claims the architecture suite passes; guide 13 and the baseline README name all 4')
# 8 commits named in the plan and the story exist
for p in ('docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md','docs/publicdigit/backlog/PBDIGIT-345-platform-routing-and-organisation-isolation.md'):
    hs=sorted(set(re.findall(r'`([0-9a-f]{9})`',show(p).decode()))); missing=[h for h in hs if subprocess.run(['git','merge-base','--is-ancestor',h,'HEAD'],capture_output=True).returncode!=0]
    check(not missing, f'{p.split("/")[-1][:40]}: {len(hs)} commit hashes, all ancestors of HEAD' + (f' MISSING {missing}' if missing else ''))
print(f'RESULT: {fails} failing checks'); sys.exit(1 if fails else 0)
