import re,glob
prop=open('E2b-AMENDMENT-2-PROPOSAL.md').read().split('\n')
src=''
for f in ['T3-DPRIME-SYNC-PROPOSAL.md','E3-ARCHITECTURE-PROPOSAL.md','E2b-ADOPTION-TEXT.md','T3-CANDIDATE-MODEL.md','../../../F-GOVERNANCE-LOG.md','O3-PREPARATION-AUTHORIZATION-PACKAGE.md','VP-DECISION-REGISTER.md','ARB-DECISION-PACKAGE.md']:
    src+=open(f).read()+'\n'
norm=lambda s:s.replace('**','').replace('\\|','|')
S=norm(src)
for i,ln in enumerate(prop[:335],1):
    if ln.startswith('| ') and re.match(r'\| \d+ \|',ln) and 118<i<152: continue
    for q in re.findall(r'"((?:[^"]|"(?=[A-Za-z0-9\[]))*?)"(?![A-Za-z0-9])',ln):
        if len(q)<12: continue
        frags=[f.strip(' .') for f in norm(q).split('…') if len(f.strip())>6]
        miss=[f for f in frags if f not in S]
        if miss: print(i,'MISS',[m[:100] for m in miss])
