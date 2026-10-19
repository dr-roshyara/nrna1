# PROPOSED / NOT RATIFIED; materials only; not executed.
# KC-1 external blind replication: leak and balance check of the encoder packets.
# Run from external-protocol/:   python3 -B tools/leak_check.py .  > tools/leak_check.out
# Exit code 0 = every check passes; 1 = at least one FAIL. It checks only the text sent to encoders
# (between the BEGIN and END markers), never the file header.
import sys, os, re, difflib

base = sys.argv[1] if len(sys.argv) > 1 else '.'
MANDATORY = ['T3', 'SH', 'PARSE']
OPTIONAL = ['PARSE_B', 'GEN']
FILES = {a: f'packet_{a}.md' for a in MANDATORY + OPTIONAL}
GUARD = {'R11', 'R14', 'R15', 'R16', 'R17', 'R21'}
CONTROLS = {'R1', 'R6', 'R9', 'R79', 'R80'}
fails = []
def check(ok, msg):
    print(('PASS ' if ok else 'FAIL ') + msg)
    if not ok: fails.append(msg)

def body(path):
    t = open(path, encoding='utf-8').read()
    check(t.startswith('PROPOSED / NOT RATIFIED; materials only; not executed'), f'{os.path.basename(path)}: status header present')
    i = t.index('<!-- BEGIN PACKET'); i = t.index('\n', i) + 1; j = t.index('<!-- END PACKET -->')
    return t[i:j]

def sections(b):
    parts = re.split(r'^(## \d\. .*)$', b, flags=re.M)
    return {parts[k].split('.')[0][3:]: parts[k] + parts[k + 1] for k in range(1, len(parts), 2)}

B = {a: body(os.path.join(base, f)) for a, f in FILES.items()}
S = {a: sections(b) for a, b in B.items()}

print('\n== 1. shared sections byte-identical across all packets ==')
for sec, name in (('1', 'task'), ('3', 'glossary'), ('4', 'concept items'), ('5', 'rows')):
    check(len({S[a][sec] for a in S}) == 1, f'section {sec} ({name}) identical in all {len(S)} packets')

print('\n== 2. section 2: only rule 6 (and, for GEN, the last sentence of rule 1) differs ==')
def rules15(sec2):
    return sec2.split('\n6. ')[0]
ref = rules15(S['T3']['2'])
for a in S:
    r = rules15(S[a]['2'])
    if a == 'GEN':
        diff = [l for l in difflib.ndiff(ref.splitlines(), r.splitlines()) if l[:1] in '+-']
        check(len(diff) == 2 and all(l[2:].startswith('1. **Statement.**') for l in diff), 'GEN: rules 1-5 differ from T3 only in rule 1')
    else:
        check(r == ref, f'{a}: rules 1-5 identical to T3')
def col1(sec2):
    return [l.split('|')[1].strip() for l in sec2.splitlines() if re.match(r'\s*\| \([a-g]\)', l)]
c1 = {a: col1(S[a]['2']) for a in S}
check(all(len(v) == 7 for v in c1.values()), 'rule-6 table has 7 parts (a)-(g) in every packet')
check(len({tuple(v) for a, v in c1.items() if a != 'GEN'}) == 1, 'rule-6 part descriptions identical across T3, SH, PARSE, PARSE_B')

print('\n== 3. rows: same set and order; listed rows are exactly the six guard rows ==')
order = {a: re.findall(r'^\*\*(R\d+)\.\*\*', S[a]['5'], flags=re.M) for a in S}
check(len({tuple(o) for o in order.values()}) == 1, 'row order identical: ' + ', '.join(order['T3']))
check(set(order['T3']) == GUARD | CONTROLS and len(order['T3']) == 11, '11 rows = 6 listed + 5 controls')
for a in S:
    if a == 'GEN':
        check(not re.search(r'listed rows are', B[a]), 'GEN: no row list (general form)')
        continue
    m = re.search(r'The listed rows are (R[^.]*)\.', B[a])
    lst = set(re.findall(r'R\d+', m.group(1))) if m else set()
    check(lst == GUARD, f'{a}: listed rows = {sorted(lst, key=lambda r: int(r[1:]))}')

print('\n== 4. banned vocabulary (verdicts, sides, analyses, preferences, arm names) ==')
BANNED = [r'CB\d', r'\bcross\w*', r'\bhost\w*', r'E3', r'REL\b', r'\bguard\w*', r'placement', r'\bplaced\b', r'\bT-[ABCD]\b',
          r'\bK1c?\b', r'\bSH\b', r'\bT3\b', r'\bMKC\b', r'\bKC\b', r'\bFZ\b', r'verdict', r'[Ii]nterpretation', r'expected',
          r'\bcorrect', r'prefer', r'recommend', r'coupl\w*', r'spann\w*', r'\bS′', r'\bARB\b', r'Claude', r'analyst',
          r'KnowledgeOS', r'PublicDigit', r'F-LOG', r'\bVP\d', r'principle', r'\bP-c\b', r'\bX\b', r'\bnone\b', r'\bside\b',
          r'\bboth sides\b', r'convention [A-Z]\b', r'\bparse\b', r'\bsplit\b']
ALLOW = {r'prefer': ['There is no preferred answer']}
fixed = S['T3']['4'] + S['T3']['5']        # corpus text, identical in all packets (checked in section 1)
for a in S:
    variable = B[a].replace(S[a]['4'], '').replace(S[a]['5'], '')
    hits = []
    for pat in BANNED:
        for m in re.finditer(pat, variable):
            ctx = variable[max(0, m.start() - 40): m.end() + 40].replace('\n', ' ')
            if any(w in ctx for w in ALLOW.get(pat, [])): continue
            hits.append(f'{pat!r}: …{ctx}…')
    check(not hits, f'{a}: no banned term outside the fixed corpus text' + ('' if not hits else ' -> ' + ' | '.join(hits)))
fh = sorted({m.group(0) for pat in BANNED for m in re.finditer(pat, fixed)})
print(f'INFO banned-pattern matches inside the fixed corpus text (rows, concepts; identical in every packet, so not arm-revealing): {fh}')

print('\n== 5. no answer lists in the packets ==')
for a in S:
    lists = re.findall(r'\[\s*"C\d+"[^\]]*\]', B[a])
    check(lists == ['["C2", "C10"]'], f'{a}: only the format example carries a C-id list ({lists})')

print('\n== 6. matched length of the varying text (rule 6) ==')
wc = lambda t: len(re.findall(r'\S+', t))
r6 = {a: wc('6. ' + S[a]['2'].split('\n6. ', 1)[1]) for a in S}
mean = sum(r6[a] for a in MANDATORY) / len(MANDATORY)
for a in S:
    dev = (r6[a] - mean) / mean
    check(abs(dev) <= 0.25, f'{a}: rule-6 words {r6[a]} ({dev:+.0%} vs mandatory-arm mean {mean:.0f}; tolerance ±25%)')
for a in S: print(f'INFO {a}: packet words {wc(B[a])}')

print('\n== 7. packet codes are neutral ==')
codes = {a: re.match(r'# Encoding packet (\S+)', B[a]).group(1) for a in S}
check(len(set(codes.values())) == len(codes) and all(re.fullmatch(r'[A-Z]', c) and c != 'X' for c in codes.values()), f'codes distinct single letters, no X: {sorted(codes.values())}')

print('\nRESULT:', 'ALL PASS' if not fails else f'{len(fails)} FAIL')
sys.exit(1 if fails else 0)
