import os, re, collections
ROOT = '.'
ALT = {
 "KnowledgeState": r"(?:KnowledgeState|Knowledge State|K_t|K)",
 "EpistemicStatus": r"(?:EpistemicStatus|Epistemic ?Status|\\Sigma|Sigma)",
 "GovernanceStatus": r"(?:GovernanceStatus|Governance ?Status|\\Gamma)",
 "AuthorityAct": r"(?:AuthorityAct|Authority ?Act)",
 "TemporalValidity": r"(?:TemporalValidity|Temporal ?Validity|ValidityInterval)",
 "ValueSpace": r"(?:ValueSpace|Value ?Space|V_D)",
 "Relation": r"(?:Relation|\\mathcal R)",
 "Provenance": r"(?:Provenance|\\Pi)",
}
CONCEPTS = ["Assertion","Proposition","Observation","Evidence","Dimension","ValueSpace","Value",
 "KnowledgeState","Relation","Transformation","Operation","Policy","Authority","Authorization",
 "Governance","Provenance","Lineage","History","EpistemicStatus","GovernanceStatus","Uncertainty",
 "Missingness","Conflict","Identity","Version","Constitution","Sufficiency","Admissibility",
 "Assessment","Verdict","Decision","AuthorityAct","Zero","TemporalValidity"]
PAT = {}
for c in CONCEPTS:
    body = ALT.get(c, c)
    PAT[c] = re.compile(r'(?<![A-Za-z_])' + body + r'\s*(?::=|=|\\triangleq|\\equiv|:)\s*([^\n]{4,90})')
files = []
for dp, dn, fn in os.walk(ROOT):
    if '/verification/' in dp: continue
    for f in fn:
        files.append(os.path.join(dp, f))
def stamp(p):
    m = re.match(r'(\d{8})-?(\d{6})?', os.path.basename(p))
    return (m.group(1) + (m.group(2) or '000000')) if m else '99999999999999'
files.sort(key=stamp)
rows = {}
for c in CONCEPTS:
    first = None; defs = collections.Counter(); nf = 0
    for p in files:
        try: txt = open(p, encoding='utf-8', errors='replace').read()
        except Exception: continue
        ms = PAT[c].findall(txt)
        if ms:
            nf += 1
            if first is None: first = (stamp(p), os.path.basename(p)[:56])
            for m in ms:
                k = re.sub(r'[\s${}`*\\]', '', m)[:52]
                if len(k) > 3: defs[k] += 1
    rows[c] = dict(first=first, distinct=len(defs), files=nf, top=[d for d, _ in defs.most_common(4)])
print("| Concept | First appearance | Files | Distinct RHS |")
print("|---|---|---|---|")
for c in CONCEPTS:
    r = rows[c]
    fa = r['first'][1] if r['first'] else '**NEVER DEFINED**'
    d = r['first'][0][:8] if r['first'] else '—'
    print(f"| {c} | {d} {fa} | {r['files']} | {r['distinct']} |")
print("\n=== competing RHS samples ===")
for c in CONCEPTS:
    if rows[c]['distinct'] > 1:
        print(f"\n{c} ({rows[c]['distinct']}):")
        for t in rows[c]['top']: print("   -", t)
